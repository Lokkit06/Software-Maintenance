<?php
declare(strict_types=1);

/**
 * Lightweight fixture factory for creating/deleting rows the app depends on.
 * Uses raw mysqli to keep tests hermetic without touching production data.
 */
final class TestDataFactory
{
    /**
     * @return array{pid:int,fname:string,lname:string,gender:string,email:string,contact:string,password:string}
     */
    public static function createPatient(mysqli $db, array $overrides = []): array
    {
        // email column is varchar(30); generate a short, unique value
        $defaults = [
            'fname'     => 'Test',
            'lname'     => 'Patient',
            'gender'    => 'Female',
            'email'     => 'p' . uniqid('', false) . '@t.io',
            'contact'   => str_pad((string) random_int(0, 999999999), 10, '0', STR_PAD_LEFT),
            'password'  => 'secret123',
        ];
        $data = array_merge($defaults, $overrides);
        $data['cpassword'] = $data['password'];

        $stmt = $db->prepare('INSERT INTO patreg (fname,lname,gender,email,contact,password,cpassword) VALUES (?,?,?,?,?,?,?)');
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare patient insert: ' . $db->error);
        }
        $stmt->bind_param(
            'sssssss',
            $data['fname'],
            $data['lname'],
            $data['gender'],
            $data['email'],
            $data['contact'],
            $data['password'],
            $data['cpassword']
        );
        $ok = $stmt->execute();
        $pid = $db->insert_id;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException('Failed to insert patient: ' . $db->error);
        }

        return [
            'pid'      => (int) $pid,
            'fname'    => $data['fname'],
            'lname'    => $data['lname'],
            'gender'   => $data['gender'],
            'email'    => $data['email'],
            'contact'  => $data['contact'],
            'password' => $data['password'],
        ];
    }

    public static function deletePatient(mysqli $db, int $pid): void
    {
        $stmt = $db->prepare('DELETE FROM patreg WHERE pid = ?');
        if ($stmt) {
            $stmt->bind_param('i', $pid);
            $stmt->execute();
            $stmt->close();
        }
    }

    /**
     * @return array{username:string,password:string,docFees:int,email:string,spec:string}
     */
    public static function createDoctor(mysqli $db, array $overrides = []): array
    {
        $defaults = [
            'username' => 'doctor_' . uniqid('', true),
            'password' => 'docpass',
            'email'    => 'doctor+' . uniqid('', true) . '@example.com',
            'spec'     => 'General',
            'docFees'  => 500,
        ];
        $data = array_merge($defaults, $overrides);

        $stmt = $db->prepare('INSERT INTO doctb (username,password,email,spec,docFees) VALUES (?,?,?,?,?)');
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare doctor insert: ' . $db->error);
        }
        $stmt->bind_param(
            'ssssi',
            $data['username'],
            $data['password'],
            $data['email'],
            $data['spec'],
            $data['docFees']
        );
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException('Failed to insert doctor: ' . $db->error);
        }

        return $data;
    }

    public static function deleteDoctor(mysqli $db, string $username): void
    {
        $stmt = $db->prepare('DELETE FROM doctb WHERE username = ?');
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->close();
        }
    }

    /**
     * @return array{username:string,password:string}
     */
    public static function createAdmin(mysqli $db, array $overrides = []): array
    {
        $defaults = [
            'username' => 'admin_' . uniqid('', true),
            'password' => 'adminpass',
        ];
        $data = array_merge($defaults, $overrides);

        $stmt = $db->prepare('INSERT INTO admintb (username,password) VALUES (?,?)');
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare admin insert: ' . $db->error);
        }
        $stmt->bind_param('ss', $data['username'], $data['password']);
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException('Failed to insert admin: ' . $db->error);
        }

        return $data;
    }

    public static function deleteAdmin(mysqli $db, string $username): void
    {
        $stmt = $db->prepare('DELETE FROM admintb WHERE username = ?');
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->close();
        }
    }

    /**
     * @return int inserted appointment ID
     */
    public static function createAppointment(mysqli $db, array $data): int
    {
        $stmt = $db->prepare(
            'INSERT INTO appointmenttb (pid,fname,lname,gender,email,contact,doctor,docFees,appdate,apptime,userStatus,doctorStatus)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'
        );
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare appointment insert: ' . $db->error);
        }
        $stmt->bind_param(
            'issssssissii',
            $data['pid'],
            $data['fname'],
            $data['lname'],
            $data['gender'],
            $data['email'],
            $data['contact'],
            $data['doctor'],
            $data['docFees'],
            $data['appdate'],
            $data['apptime'],
            $data['userStatus'],
            $data['doctorStatus']
        );
        $ok = $stmt->execute();
        $id = $db->insert_id;
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException('Failed to insert appointment: ' . $db->error);
        }

        return (int) $id;
    }

    public static function deleteAppointment(mysqli $db, int $id): void
    {
        $stmt = $db->prepare('DELETE FROM appointmenttb WHERE ID = ?');
        if ($stmt) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    /**
     * @return int inserted prescription ID value
     */
    public static function createPrescription(mysqli $db, array $data): int
    {
        $stmt = $db->prepare(
            'INSERT INTO prestb (doctor,pid,ID,fname,lname,appdate,apptime,disease,allergy,prescription)
             VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare prescription insert: ' . $db->error);
        }
        $stmt->bind_param(
            'siisssssss',
            $data['doctor'],
            $data['pid'],
            $data['ID'],
            $data['fname'],
            $data['lname'],
            $data['appdate'],
            $data['apptime'],
            $data['disease'],
            $data['allergy'],
            $data['prescription']
        );
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) {
            throw new RuntimeException('Failed to insert prescription: ' . $db->error);
        }

        return (int) $data['ID'];
    }

    public static function deletePrescription(mysqli $db, int $id): void
    {
        $stmt = $db->prepare('DELETE FROM prestb WHERE ID = ?');
        if ($stmt) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}


