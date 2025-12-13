<?php
function check_login()
{
    try {
        if(strlen($_SESSION['login'])==0)
    	{	
    		$host = $_SERVER['HTTP_HOST'];
    		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    		$extra="./user-login.php";		
    		header("Location: http://$host$uri/$extra");
    	}
    } catch (Throwable $e) {
        error_log('check_login failed: ' . $e->getMessage());
        // Fallback: attempt to send user to login page
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri  = isset($_SERVER['PHP_SELF']) ? rtrim(dirname($_SERVER['PHP_SELF']), '/\\') : '';
        $extra="./user-login.php";
        header("Location: http://$host$uri/$extra");
        exit;
    }
}
?>