<?php
/**
 * School Year options manager
 * Stores configurable school year options in the local payments DB.
 * Options are managed from Admin > School Years and used by the payment forms.
 */

if (!function_exists('schoolYearCurrentStart')) {
    /**
     * Start year of the current school year.
     * A school year starts in June; Jan-May still belongs to the previous start year.
     */
    function schoolYearCurrentStart() {
        $year = (int)date('Y');
        $month = (int)date('n');
        return $month >= 6 ? $year : $year - 1;
    }
}

if (!function_exists('schoolYearCurrentLabel')) {
    function schoolYearCurrentLabel() {
        $start = schoolYearCurrentStart();
        return $start . '-' . ($start + 1);
    }
}

if (!function_exists('schoolYearStatus')) {
    /**
     * Returns "past", "present", or "future" relative to the current school year.
     */
    function schoolYearStatus($schoolYear) {
        $current = schoolYearCurrentLabel();
        if ($schoolYear === $current) return 'present';
        return strcmp($schoolYear, $current) > 0 ? 'future' : 'past';
    }
}

if (!function_exists('isValidSchoolYearLabel')) {
    function isValidSchoolYearLabel($schoolYear) {
        if (!preg_match('/^(\d{4})-(\d{4})$/', $schoolYear, $m)) return false;
        $start = (int)$m[1];
        $end = (int)$m[2];
        return $end === $start + 1 && $start >= 1980 && $start <= 2100;
    }
}

if (!function_exists('ensureSchoolYearsTable')) {
    function ensureSchoolYearsTable($con) {
        if (!$con) return false;

        $sql = "CREATE TABLE IF NOT EXISTS school_years (
            id INT AUTO_INCREMENT PRIMARY KEY,
            school_year VARCHAR(20) NOT NULL UNIQUE,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if (!@mysqli_query($con, $sql)) return false;

        // Seed the default range once: past years, the present year, and two future years.
        $res = @mysqli_query($con, "SELECT COUNT(*) AS c FROM school_years");
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            if ((int)($row['c'] ?? 0) === 0) {
                $current = schoolYearCurrentStart();
                $values = [];
                for ($y = 1980; $y <= $current + 2; $y++) {
                    $label = $y . '-' . ($y + 1);
                    $values[] = "('" . mysqli_real_escape_string($con, $label) . "', 1)";
                }
                if (!empty($values)) {
                    @mysqli_query($con, "INSERT IGNORE INTO school_years (school_year, is_active) VALUES " . implode(',', $values));
                }
            }
        }

        // Keep the present school year available even after a year rollover.
        $currentLabel = schoolYearCurrentLabel();
        $currentEsc = mysqli_real_escape_string($con, $currentLabel);
        @mysqli_query($con, "INSERT IGNORE INTO school_years (school_year, is_active) VALUES ('{$currentEsc}', 1)");

        return true;
    }
}

if (!function_exists('getSchoolYearRows')) {
    function getSchoolYearRows($con, $activeOnly = false) {
        if (!$con) return [];
        $sql = "SELECT id, school_year, is_active, created_at FROM school_years";
        if ($activeOnly) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY school_year DESC";
        $res = @mysqli_query($con, $sql);
        $rows = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
}

if (!function_exists('getPaymentSchoolYears')) {
    /**
     * School years shown on the payment forms.
     * Active options only, ordered present -> future (nearest first) -> past (recent first).
     */
    function getPaymentSchoolYears($con) {
        $rows = getSchoolYearRows($con, true);

        if (empty($rows)) {
            // Fallback if the table is unavailable: generate past, present, and future options.
            $current = schoolYearCurrentStart();
            for ($y = $current + 2; $y >= 1980; $y--) {
                $rows[] = ['id' => 0, 'school_year' => $y . '-' . ($y + 1), 'is_active' => 1];
            }
            return $rows;
        }

        // Always include the present school year even if its row was removed.
        $currentLabel = schoolYearCurrentLabel();
        $hasCurrent = false;
        foreach ($rows as $row) {
            if ($row['school_year'] === $currentLabel) {
                $hasCurrent = true;
                break;
            }
        }
        if (!$hasCurrent) {
            $rows[] = ['id' => 0, 'school_year' => $currentLabel, 'is_active' => 1];
        }

        $rank = ['future' => 0, 'present' => 1, 'past' => 2];
        usort($rows, function ($a, $b) use ($rank) {
            $sa = schoolYearStatus($a['school_year']);
            $sb = schoolYearStatus($b['school_year']);
            if ($rank[$sa] !== $rank[$sb]) return $rank[$sa] <=> $rank[$sb];
            // Future, present, and past are each listed newest first.
            return strcmp($b['school_year'], $a['school_year']);
        });

        return $rows;
    }
}
