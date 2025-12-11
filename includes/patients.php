<?php
/**
 * Patient data helpers to keep views free from inline SQL.
 */

/**
 * Fetch all patients.
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_all_patients(mysqli $con): array {
    $stmt = $con->prepare('SELECT * FROM patreg');
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
 * Fetch a single patient by contact.
 *
 * @param mysqli $con
 * @param string $contact
 * @return array<string,mixed>|null
 */
function fetch_patient_by_contact(mysqli $con, string $contact): ?array {
    $stmt = $con->prepare('SELECT * FROM patreg WHERE contact = ? LIMIT 1');
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $contact);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    return $row ?: null;
}

