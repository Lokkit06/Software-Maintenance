<?php

/**
 * Fetch all appointments.
 *
 * @param mysqli $con
 * @return array<int,array<string,mixed>>
 */
function fetch_all_appointments(mysqli $con): array {
    try {
        $stmt = $con->prepare('SELECT * FROM appointmenttb');
        if (!$stmt) {
            throw new Exception('Failed to prepare fetch_all_appointments');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    } catch (Throwable $e) {
        error_log('fetch_all_appointments failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Fetch appointments by contact (patient contact number).
 *
 * @param mysqli $con
 * @param string $contact
 * @return array<int,array<string,mixed>>
 */
function fetch_appointments_by_contact(mysqli $con, string $contact): array {
    try {
        $stmt = $con->prepare('SELECT * FROM appointmenttb WHERE contact = ?');
        if (!$stmt) {
            throw new Exception('Failed to prepare fetch_appointments_by_contact');
        }
        $stmt->bind_param('s', $contact);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    } catch (Throwable $e) {
        error_log('fetch_appointments_by_contact failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Fetch appointments by patient id (pid).
 *
 * @param mysqli $con
 * @param int $pid
 * @return array<int,array<string,mixed>>
 */
function fetch_appointments_by_pid(mysqli $con, int $pid): array {
    try {
        $stmt = $con->prepare('SELECT ID, doctor, docFees, appdate, apptime, userStatus, doctorStatus FROM appointmenttb WHERE pid = ?');
        if (!$stmt) {
            throw new Exception('Failed to prepare fetch_appointments_by_pid');
        }
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    } catch (Throwable $e) {
        error_log('fetch_appointments_by_pid failed: ' . $e->getMessage());
        return [];
    }
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

