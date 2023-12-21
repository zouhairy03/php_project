<?php
include '../includes/connection.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $customer = $_POST['customer'] ?? '';
    $subtotal = $_POST['subtotal'] ?? '';
    $lessvat = $_POST['lessvat'] ?? '';
    $netvat = $_POST['netvat'] ?? '';
    $addvat = $_POST['addvat'] ?? '';
    $total = $_POST['total'] ?? '';
    $cash = $_POST['cash'] ?? '';
    $emp = $_POST['employee'] ?? '';
    $rol = $_POST['role'] ?? '';
    
    // Generate a unique transaction ID
    $today = date("mdGis"); 

    // Check if 'name' is set in the POST array
    if (isset($_POST['name']) && is_array($_POST['name'])) {
        $countID = count($_POST['name']);

        // Use prepared statements to avoid SQL injection
        $insertDetailQuery = "INSERT INTO `transaction_details` 
                             (`ID`, `TRANS_D_ID`, `PRODUCTS`, `QTY`, `PRICE`, `EMPLOYEE`, `ROLE`) 
                             VALUES (NULL, ?, ?, ?, ?, ?, ?)";
        $detailStatement = mysqli_prepare($db, $insertDetailQuery);

        if ($detailStatement) {
            // Bind parameters and execute for each detail
            mysqli_stmt_bind_param($detailStatement, 'ssssss', $today, $productName, $quantity, $price, $emp, $rol);

            for ($i = 0; $i < $countID; $i++) {
                $productName = $_POST['name'][$i];
                $quantity = $_POST['quantity'][$i];
                $price = $_POST['price'][$i];
                
                mysqli_stmt_execute($detailStatement);
            }

            mysqli_stmt_close($detailStatement);
        }

        // Insert into the 'transaction' table
        $insertTransactionQuery = "INSERT INTO `transaction` 
                                  (`TRANS_ID`, `CUST_ID`, `NUMOFITEMS`, `SUBTOTAL`, `LESSVAT`, `NETVAT`, `ADDVAT`, `GRANDTOTAL`, `CASH`, `DATE`, `TRANS_D_ID`) 
                                  VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $transactionStatement = mysqli_prepare($db, $insertTransactionQuery);

        if ($transactionStatement) {
            // Bind parameters and execute for the transaction
            mysqli_stmt_bind_param($transactionStatement, 'ssssssssss', $customer, $countID, $subtotal, $lessvat, $netvat, $addvat, $total, $cash, $date, $today);
            
            mysqli_stmt_execute($transactionStatement);
            mysqli_stmt_close($transactionStatement);
        }

        unset($_SESSION['pointofsale']);

        // Redirect after successful submission
        echo "<script type='text/javascript'>
                alert('Success.');
                window.location = 'pos.php';
              </script>";
    } else {
        // Handle the case where 'name' is not set
        echo "<script type='text/javascript'>
                alert('Error: Product details not received.');
                window.location = 'pos.php';
              </script>";
    }
} else {
    // Handle the case where the form is not submitted
    echo "<script type='text/javascript'>
            alert('Error: Form not submitted.');
            window.location = 'pos.php';
          </script>";
}
?>
