<?php
/**
 * Doctor data helpers to keep views free from inline SQL.
 */

/**
 * Fetch all doctors.
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_all_doctors(mysqli $con): array {
    try {
        $stmt = $con->prepare('SELECT * FROM doctb');
        if (!$stmt) {
            throw new Exception('Failed to prepare fetch_all_doctors');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    } catch (Throwable $e) {
        error_log('fetch_all_doctors failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Fetch distinct doctor specializations.
 *
 * @param mysqli $con
 * @return array<int,string>
 */
function fetch_doctor_specs(mysqli $con): array {
    try {
        $stmt = $con->prepare('SELECT DISTINCT spec FROM doctb WHERE spec IS NOT NULL AND spec <> ""');
        if (!$stmt) {
            throw new Exception('Failed to prepare fetch_doctor_specs');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        // Flatten to string array (compat: no arrow functions)
        return array_values(array_map(function ($r) { return $r['spec']; }, $rows));
    } catch (Throwable $e) {
        error_log('fetch_doctor_specs failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Fetch doctors with specialization and fees (for booking forms).
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_doctors_with_fees(mysqli $con): array {
    try {
        $stmt = $con->prepare('SELECT username, spec, email, docFees FROM doctb');
        if (!$stmt) {
            throw new Exception('Failed to prepare fetch_doctors_with_fees');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    } catch (Throwable $e) {
        error_log('fetch_doctors_with_fees failed: ' . $e->getMessage());
        return [];
    }
}

