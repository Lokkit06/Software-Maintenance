<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../config/logger.php';

/**
 * Authenticate an admin by username/password.
 *
 * @return array<string,mixed>|null
 */
function authenticate_admin(mysqli $con, string $username, string $password): ?array
{
    try {
        $stmt = $con->prepare('SELECT * FROM admintb WHERE username = ? AND password = ? LIMIT 1');
        if (!$stmt) {
            throw new Exception('Failed to prepare admin lookup statement');
        }
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row ?: null;
    } catch (Throwable $e) {
        app_log('Admin authentication error', [
            'action' => 'authenticate_error',
            'user_type' => 'admin',
            'username' => $username,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}

if (isset($_POST['adsub'])) {
	$username = $_POST['username1'];
	$password = $_POST['password2'];

    try {
        $admin = authenticate_admin($con, $username, $password);
        if ($admin) {
            $_SESSION['username'] = $admin['username'];
            app_log('Admin logged in successfully', [
                'action' => 'login_success',
                'user_type' => 'admin',
                'username' => $admin['username']
            ]);
            header("Location: ../views/admin/dashboard.php");
            exit;
        }

        echo "<script>alert('Invalid Username or Password. Try Again!');
              window.location.href = '../views/public/index.php';</script>";
        app_log('Admin login failed - wrong password', [
            'action' => 'login_failed',
            'user_type' => 'admin',
            'username' => $username,
            'reason' => 'invalid_credentials'
        ]);
        exit;
    } catch (Throwable $e) {
        app_log('Admin login error - system failure', [
            'action' => 'login_error',
            'user_type' => 'admin',
            'username' => $username,
            'error' => $e->getMessage()
        ]);
        echo "<script>alert('An unexpected error occurred. Please try again.');
              window.location.href = '../views/public/index.php';</script>";
        exit;
    }
}
if(isset($_POST['update_data']))
{
	$contact=$_POST['contact'];
	$status=$_POST['status'];
	$query="update appointmenttb set payment='$status' where contact='$contact';";
	$result=mysqli_query($con,$query);
	if($result)
		header("Location:updated.php");
}




if (!function_exists('display_docs')) {
	function display_docs()
	{
		global $con;
		$query="select * from doctb";
		$result=mysqli_query($con,$query);
		while($row=mysqli_fetch_array($result))
		{
			$name=$row['name'];
			# echo'<option value="" disabled selected>Select Doctor</option>';
			echo '<option value="'.$name.'">'.$name.'</option>';
		}
	}
}

if(isset($_POST['doc_sub']))
{
	$name=$_POST['name'];
	$query="insert into doctb(name)values('$name')";
	$result=mysqli_query($con,$query);
	if($result)
		header("Location:adddoc.php");
}
?>