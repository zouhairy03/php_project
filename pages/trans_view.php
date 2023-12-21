<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Validate and sanitize input
$trans_id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : null;

if (!$trans_id) {
    // Handle invalid or missing ID (redirect or show an error message)
    echo "Invalid or missing transaction ID.";
} else {
    // Query to retrieve transaction details
    $query = "SELECT ID, t.TYPE, FIRST_NAME, LAST_NAME, PHONE_NUMBER, EMPLOYEE, ROLE
              FROM transaction T
              JOIN customer C ON T.`CUST_ID`=C.`CUST_ID`
              JOIN transaction_details tt ON tt.`TRANS_D_ID`=T.`TRANS_D_ID`
              JOIN users u ON u.ID = T.USER_ID
              JOIN type t ON t.TYPE_ID=u.TYPE_ID
              WHERE TRANS_ID = $trans_id";

    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    // Check user type and redirect if necessary
    while ($row = mysqli_fetch_assoc($result)) {
        $userType = $row['TYPE'];

        if ($userType == 'User') {
            ?>
            <script type="text/javascript">
                //then it will be redirected
                alert("Restricted Page! You will be redirected to POS");
                window.location = "pos.php";
            </script>
            <?php
            exit; // Stop further execution if redirected
        }

        // Process the retrieved data
        $fname = $row['FIRST_NAME'];
        $lname = $row['LAST_NAME'];
        $pn = $row['PHONE_NUMBER'];
        $date = $row['DATE'];
        $tid = $row['TRANS_D_ID'];
        $cash = $row['CASH'];
        $sub = $row['SUBTOTAL'];
        $less = $row['LESSVAT'];
        $net = $row['NETVAT'];
        $add = $row['ADDVAT'];
        $grand = $row['GRANDTOTAL'];
        $role = $row['EMPLOYEE'];
        $roles = $row['ROLE'];
    }
    ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Rest of your code for displaying transaction details -->
            <!-- ... -->
        </div>
    </div>
    <?php
}

include '../includes/footer.php';
?>
