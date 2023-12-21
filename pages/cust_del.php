<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];

    if ($Aa == 'User') {
        ?>
        <script type="text/javascript">
            // then it will be redirected
            alert("Restricted Page! You will be redirected to POS");
            window.location = "pos.php";
        </script>
        <?php
    }
}

if (!isset($_GET['do']) || $_GET['do'] != 1) {
    if (isset($_GET['type'])) {
        $validTypes = ['customer', 'other_type']; // Add more valid types as needed
        $type = $_GET['type'];

        if (in_array($type, $validTypes)) {
            if (isset($_GET['id'])) {
                $customerId = mysqli_real_escape_string($db, $_GET['id']);
                $query = 'DELETE FROM customer WHERE CUST_ID = ' . $customerId;
                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                ?>
                <script type="text/javascript">alert("Customer Successfully Deleted.");window.location = "customer.php";</script>
                <?php
            } else {
                echo "Error: Missing 'id' parameter for customer deletion.";
            }
        } else {
            echo "Error: Invalid 'type' parameter.";
        }
    } else {
        echo "Error: Missing 'type' parameter.";
    }
}
?>
