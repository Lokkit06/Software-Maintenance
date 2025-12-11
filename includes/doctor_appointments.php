<?php
/**
 * Doctor-specific appointment helpers.
 */

/**
 * Fetch appointments for a doctor.
 *
 * @param mysqli $con
 * @param string $doctorUsername
 * @return array<int,array<string,mixed>>
 */
function fetch_doctor_appointments(mysqli $con, string $doctorUsername): array {
    $stmt = $con->prepare(
        'SELECT pid, ID, fname, lname, gender, email, contact, appdate, apptime, userStatus, doctorStatus
         FROM appointmenttb
         WHERE doctor = ?'
    );
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param('s', $doctorUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

function format_doctor_app_status(array $row): string {
    if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) {
        return 'Active';
    }
    if ($row['userStatus'] == 0 && $row['doctorStatus'] == 1) {
        return 'Cancelled by Patient';
    }
    if ($row['userStatus'] == 1 && $row['doctorStatus'] == 0) {
        return 'Cancelled by You';
    }
    return 'Unknown';
}

