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
    $stmt = $con->prepare('SELECT * FROM doctb');
    if (!$stmt) {
        return [];
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

/**
 * Fetch distinct doctor specializations.
 *
 * @param mysqli $con
 * @return array<int,string>
 */
function fetch_doctor_specs(mysqli $con): array {
    $stmt = $con->prepare('SELECT DISTINCT spec FROM doctb WHERE spec IS NOT NULL AND spec <> ""');
    if (!$stmt) {
        return [];
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    // Flatten to string array (compat: no arrow functions)
    return array_values(array_map(function ($r) { return $r['spec']; }, $rows));
}

/**
 * Fetch doctors with specialization and fees (for booking forms).
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_doctors_with_fees(mysqli $con): array {
    $stmt = $con->prepare('SELECT username, spec, email, docFees FROM doctb');
    if (!$stmt) {
        return [];
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

