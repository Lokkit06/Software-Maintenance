<?php
require_once __DIR__ . '/../config/db_connect.php';
if(isset($_POST['btnSubmit']))
{
	$name = $_POST['txtName'];
	$email = $_POST['txtEmail'];
	$contact = $_POST['txtPhone'];
	$message = $_POST['txtMsg'];

    try {
        $query="insert into contact(name,email,contact,message) values('$name','$email','$contact','$message');";
        $result = mysqli_query($con,$query);
        
        if($result)
        {
            echo '<script type="text/javascript">'; 
            echo 'alert("Message sent successfully!");'; 
            echo 'window.location.href = "../views/public/contact.php";';
            echo '</script>';
            exit;
        }

        throw new Exception('Failed to save contact message');
    } catch (Throwable $e) {
        error_log('process_contact failed: ' . $e->getMessage());
        echo '<script type="text/javascript">'; 
        echo 'alert("An unexpected error occurred. Please try again.");'; 
        echo 'window.location.href = "../views/public/contact.php";';
        echo '</script>';
        exit;
    }
}
?>