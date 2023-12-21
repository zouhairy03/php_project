<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check user type
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $userType = $row['TYPE'];

    if ($userType == 'User') {
        ?>
        <script type="text/javascript">
            alert("Restricted Page! You will be redirected to POS");
            window.location = "pos.php";
        </script>
        <?php
        exit; // Terminate script execution for users with 'User' type
    }
}

// Check if 'id' is set in the URL
if (!isset($_GET['id'])) {
    echo "Error: Missing 'id' parameter.";
} else {
    // Safely handle 'id' parameter to prevent SQL injection
    $customerId = mysqli_real_escape_string($db, $_GET['id']);

    // Check if the provided 'id' is a non-empty string
    if (!empty($customerId)) {
        // Retrieve customer information
        $query = 'SELECT * FROM customer WHERE CUST_ID =' . $customerId;
        $result = mysqli_query($db, $query) or die(mysqli_error($db));

        if ($row = mysqli_fetch_array($result)) {
            $zz = $row['CUST_ID'];
            $i = $row['FIRST_NAME'];
            $a = $row['LAST_NAME'];
            $b = $row['PHONE_NUMBER'];
        } else {
            echo "Error: Customer not found.";
            exit; // Terminate script execution if customer not found
        }
    } else {
        echo "Error: Invalid 'id' parameter.";
        exit; // Terminate script execution for invalid 'id' parameter
    }
}
?>

            
            <center><div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
              <h4 class="m-2 font-weight-bold text-primary">Customer's Detail</h4>
            </div>
            <a href="customer.php" type="button" class="btn btn-primary bg-gradient-primary btn-block"> <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back </a>
            <div class="card-body">
                

                    <div class="form-group row text-left">

                      <div class="col-sm-3 text-primary">
                        <h5>
                          Full Name<br>
                        </h5>
                      </div>

                      <div class="col-sm-9">
                        <h5>
                          : <?php echo $i; ?> <?php echo $a; ?> <br>
                        </h5>
                      </div>

                    </div>

                    <div class="form-group row text-left">

                      <div class="col-sm-3 text-primary">
                        <h5>
                          Contact #<br>
                        </h5>
                      </div>

                      <div class="col-sm-9">
                        <h5>
                          : <?php echo $b; ?> <br>
                        </h5>
                      </div>
                      
                    </div>
            </div>
          </div>

          <?php
include '../includes/footer.php';
?>