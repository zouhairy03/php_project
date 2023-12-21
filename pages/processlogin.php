<?php
require('../includes/connection.php');
require('session.php');

if (isset($_POST['btnlogin'])) {
  $username = trim($_POST['user']);
  $password = trim($_POST['password']);

  if (empty($password)) {
    echo "<script>alert('Password is missing!');</script>";
    echo "<script>window.location = 'login.php';</script>";
    exit;
  }

  $hashed_password = sha1($password);
  $sql = "SELECT u.ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, e.EMAIL, e.PHONE_NUMBER, j.JOB_TITLE, l.PROVINCE, l.CITY, t.TYPE
          FROM users u
          JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
          JOIN location l ON e.LOCATION_ID = l.LOCATION_ID
          JOIN job j ON e.JOB_ID = j.JOB_ID
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE u.USERNAME = '$username' AND u.PASSWORD = '$hashed_password'";

  $result = $db->query($sql);

  if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['MEMBER_ID'] = $user['ID'];
    $_SESSION['FIRST_NAME'] = $user['FIRST_NAME'];
    $_SESSION['LAST_NAME'] = $user['LAST_NAME'];
    $_SESSION['GENDER'] = $user['GENDER'];
    $_SESSION['EMAIL'] = $user['EMAIL'];
    $_SESSION['PHONE_NUMBER'] = $user['PHONE_NUMBER'];
    $_SESSION['JOB_TITLE'] = $user['JOB_TITLE'];
    $_SESSION['PROVINCE'] = $user['PROVINCE'];
    $_SESSION['CITY'] = $user['CITY'];
    $_SESSION['TYPE'] = $user['TYPE'];

    if ($_SESSION['TYPE'] == 'Admin') {
      echo "<script>alert('{$_SESSION['FIRST_NAME']} Welcome!');</script>";
      echo "<script>window.location = 'index.php';</script>";
      exit;
    } elseif ($_SESSION['TYPE'] == 'User') {
      echo "<script>alert('{$_SESSION['FIRST_NAME']} Welcome!');</script>";
      echo "<script>window.location = 'pos.php';</script>";
      exit;
    }
  } else {
    echo "<script>alert('Username or Password Not Registered! Contact Your administrator.');</script>";
    echo "<script>window.location = 'index.php';</script>";
    exit;
  }
}

$db->close();
?>