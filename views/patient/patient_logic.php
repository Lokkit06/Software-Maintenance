<?php
include('../../actions/patient_login.php');
include('../../actions/update_status.php');
require_once '../../config/db_connect.php';

// Ensure required session data exists; redirect to login if not
if(!isset($_SESSION['pid'])){
  header("Location: ../public/login.php");
  exit();
}

$pid = $_SESSION['pid'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$fname = $_SESSION['fname'];
$gender = $_SESSION['gender'];
$lname = $_SESSION['lname'];
$contact = $_SESSION['contact'];

// Handle Appointment Booking
if(isset($_POST['app-submit']))
{
  try {
    $pid = $_SESSION['pid'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $gender = $_SESSION['gender'];
    $contact = $_SESSION['contact'];
    $doctor=$_POST['doctor'];
    $email=$_SESSION['email'];
    $docFees=$_POST['docFees'];

    $appdate=$_POST['appdate'];
    $apptime=$_POST['apptime'];
    $cur_date = date("Y-m-d");
    date_default_timezone_set('Asia/Kolkata');
    $cur_time = date("H:i:s");
    $apptime1 = strtotime($apptime);
    $appdate1 = strtotime($appdate);
    
    if(date("Y-m-d",$appdate1)>=$cur_date){
      if((date("Y-m-d",$appdate1)==$cur_date and date("H:i:s",$apptime1)>$cur_time) or date("Y-m-d",$appdate1)>$cur_date) {
        $check_query = mysqli_query($con,"select apptime from appointmenttb where doctor='$doctor' and appdate='$appdate' and apptime='$apptime'");
        if($check_query === false){
          throw new Exception('Failed to check appointment availability');
        }

          if(mysqli_num_rows($check_query)==0){
            $query=mysqli_query($con,"insert into appointmenttb(pid,fname,lname,gender,email,contact,doctor,docFees,appdate,apptime,userStatus,doctorStatus) values($pid,'$fname','$lname','$gender','$email','$contact','$doctor','$docFees','$appdate','$apptime','1','1')");

            if($query)
            {
              echo "<script>alert('Your appointment successfully booked');</script>";
            }
            else{
              throw new Exception('Failed to book appointment');
            }
        }
        else{
          echo "<script>alert('We are sorry to inform that the doctor is not available in this time or date. Please choose different time or date!');</script>";
        }
      }
      else{
        echo "<script>alert('Select a time or date in the future!');</script>";
      }
    }
    else{
        echo "<script>alert('Select a time or date in the future!');</script>";
    }
  } catch (Throwable $e) {
    error_log('patient_logic appointment booking failed: ' . $e->getMessage());
    echo "<script>alert('An unexpected error occurred. Please try again.');</script>";
  }
}

// Helper function for Bill Generation using prepared statements
function generate_bill(){
  global $con;
  $pid = $_SESSION['pid'];
  $id  = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;
  if ($id <= 0) {
    return '';
  }

  try {
    $output='';
    $stmt = $con->prepare("
      SELECT p.pid,p.ID,p.fname,p.lname,p.doctor,p.appdate,p.apptime,p.disease,p.allergy,p.prescription,a.docFees
      FROM prestb p
      INNER JOIN appointmenttb a ON p.ID = a.ID
      WHERE p.pid = ? AND p.ID = ?
    ");
    if (!$stmt) {
      throw new Exception('Failed to prepare bill statement');
    }
    $stmt->bind_param('ii', $pid, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
      $output .= '
      <label> Patient ID : </label>'.$row["pid"].'<br/><br/>
      <label> Appointment ID : </label>'.$row["ID"].'<br/><br/>
      <label> Patient Name : </label>'.$row["fname"].' '.$row["lname"].'<br/><br/>
      <label> Doctor Name : </label>'.$row["doctor"].'<br/><br/>
      <label> Appointment Date : </label>'.$row["appdate"].'<br/><br/>
      <label> Appointment Time : </label>'.$row["apptime"].'<br/><br/>
      <label> Disease : </label>'.$row["disease"].'<br/><br/>
      <label> Allergies : </label>'.$row["allergy"].'<br/><br/>
      <label> Prescription : </label>'.$row["prescription"].'<br/><br/>
      <label> Fees Paid : </label>'.$row["docFees"].'<br/>
      ';
    }
    $stmt->close();
    return $output;
  } catch (Throwable $e) {
    error_log('generate_bill failed: ' . $e->getMessage());
    return '';
  }
}

// Handle PDF Generation
if(isset($_GET["generate_bill"])){
  try {
    if (!isset($_GET['ID']) || (int)$_GET['ID'] <= 0) {
      echo "<script>alert('Invalid bill request'); window.location.href='../patient/dashboard.php';</script>";
      exit;
    }
    require_once '../../assets/TCPDF/tcpdf.php';
    $obj_pdf = new TCPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
    $obj_pdf -> SetCreator(PDF_CREATOR);
    $obj_pdf -> SetTitle("Generate Bill");
    $obj_pdf -> SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
    $obj_pdf -> SetHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
    $obj_pdf -> SetFooterFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
    $obj_pdf -> SetDefaultMonospacedFont('helvetica');
    $obj_pdf -> SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf -> SetMargins(PDF_MARGIN_LEFT,'5',PDF_MARGIN_RIGHT);
    $obj_pdf -> SetPrintHeader(false);
    $obj_pdf -> SetPrintFooter(false);
    $obj_pdf -> SetAutoPageBreak(TRUE, 10);
    $obj_pdf -> SetFont('helvetica','',12);
    $obj_pdf -> AddPage();

    $content = '';
    $content .= '
        <br/>
        <h2 align ="center"> Global Hospitals</h2></br>
        <h3 align ="center"> Bill</h3>
    ';
   
    $content .= generate_bill();
    $obj_pdf -> writeHTML($content);
    ob_end_clean();
    $obj_pdf -> Output("bill.pdf",'I');
  } catch (Throwable $e) {
    error_log('generate_bill pdf failed: ' . $e->getMessage());
    echo "<script>alert('Unable to generate bill right now. Please try again.'); window.location.href='../patient/dashboard.php';</script>";
    exit;
  }
}

// Helper function to fetch specializations
function get_specs(){
  global $con;
  try {
    $query=mysqli_query($con,"select username,spec from doctb");
    if(!$query){
      throw new Exception('Failed to fetch doctor specs');
    }
    $docarray = array();
      while($row =mysqli_fetch_assoc($query))
      {
          $docarray[] = $row;
      }
      return json_encode($docarray);
  } catch (Throwable $e) {
    error_log('get_specs failed: ' . $e->getMessage());
    return json_encode(array());
  }
}
?>