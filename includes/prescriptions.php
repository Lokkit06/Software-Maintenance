<?php
/**
 * Prescription data helpers to keep views free from inline SQL.
 * SRP: data access lives here; views render only.
 */

/**
 * Fetch all prescriptions.
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_all_prescriptions(mysqli $con): array {
    $stmt = $con->prepare('SELECT * FROM prestb');
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
 * Fetch prescriptions for a specific patient (by pid).
 *
 * @param mysqli $con
 * @param int $pid
 * @return array<int,array<string,mixed>>
 */
function fetch_prescriptions_by_patient(mysqli $con, int $pid): array {
    $stmt = $con->prepare('SELECT doctor, ID, appdate, apptime, disease, allergy, prescription FROM prestb WHERE pid = ?');
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

