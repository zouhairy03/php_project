<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

$query = "SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE ID = {$_SESSION['MEMBER_ID']}";
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
  $user_type = $row['TYPE'];

  if ($user_type == 'User') {
    echo "<script>alert('Restricted Page! You will be redirected to POS');</script>";
    echo "<script>window.location = 'pos.php';</script>";
    exit;
  }
}
?>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h4 class="m-2 font-weight-bold text-primary">Inventory</h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Product Code</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>On Hand</th>
            <th>Category</th>
            <th>Date Stock In</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT p.PRODUCT_ID, p.PRODUCT_CODE, p.NAME, COUNT(s.QTY_STOCK) AS QTY_STOCK, COUNT(s.ON_HAND) AS ON_HAND, c.CNAME, p.DATE_STOCK_IN
                    FROM product p
                    JOIN category c ON p.CATEGORY_ID = c.CATEGORY_ID
                    LEFT JOIN stock s ON p.PRODUCT_ID = s.PRODUCT_ID
                    GROUP BY p.PRODUCT_ID, p.PRODUCT_CODE, p.NAME, c.CNAME, p.DATE_STOCK_IN";
          $result = mysqli_query($db, $query) or die(mysqli_error($db));

          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['PRODUCT_CODE'] . '</td>';
            echo '<td>' . $row['NAME'] . '</td>';
            echo '<td>' . $row['QTY_STOCK'] . '</td>';
            echo '<td>' . $row['ON_HAND'] . '</td>';
            echo '<td>' . $row['CNAME'] . '</td>';
            echo '<td>' . $row['DATE_STOCK_IN'] . '</td>';
            echo '<td align="right">
                    <a type="button" class="btn btn-primary bg-gradient-primary" href="inv_searchfrm.php?action=edit&id=' . $row['PRODUCT_CODE'] . '"><i class="fas fa-fw fa-th-list"></i> View</a>
                  </td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
include '../includes/footer.php';
?>