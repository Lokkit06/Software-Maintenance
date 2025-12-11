<?php

/**
 * Fetch all appointments.
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_all_appointments(mysqli $con): array {
    $stmt = $con->prepare('SELECT * FROM appointmenttb');
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
 * Fetch appointments by contact (patient contact number).
 *
 * @param mysqli $con
 * @param string $contact
 * @return array<int,array<string,mixed>>
 */
function fetch_appointments_by_contact(mysqli $con, string $contact): array {
    $stmt = $con->prepare('SELECT * FROM appointmenttb WHERE contact = ?');
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param('s', $contact);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

/**
 * Fetch appointments by patient id (pid).
 *
 * @param mysqli $con
 * @param int $pid
 * @return array<int,array<string,mixed>>
 */
function fetch_appointments_by_pid(mysqli $con, int $pid): array {
    $stmt = $con->prepare('SELECT ID, doctor, docFees, appdate, apptime, userStatus, doctorStatus FROM appointmenttb WHERE pid = ?');
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

/**
 * Compute a human-readable appointment status.
 *
 * @param array<string,mixed> $row
 */
function format_app_status(array $row): string {
    if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) {
        return 'Active';
    }
    if ($row['userStatus'] == 0 && $row['doctorStatus'] == 1) {
        return 'Cancelled by Patient';
    }
    if ($row['userStatus'] == 1 && $row['doctorStatus'] == 0) {
        return 'Cancelled by Doctor';
    }
    return 'Unknown';
}

