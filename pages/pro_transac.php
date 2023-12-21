<?php
include '../includes/connection.php';

// Retrieve form data
$pc = $_POST['prodcode'];
$name = $_POST['name'];
$desc = $_POST['description'];
$qty = $_POST['quantity'];
$pr = $_POST['price'];
$cat = $_POST['category'];
$supp = $_POST['supplier'];
$dats = $_POST['datestock'];

// Ensure $qty is a positive integer
$qty = (int)$qty;
if ($qty <= 0) {
    die('Quantity must be a positive integer.');
}

// Prepare the INSERT statement
$stmt = $db->prepare("INSERT INTO product 
    (PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, ON_HAND, PRICE, CATEGORY_ID, SUPPLIER_ID, DATE_STOCK_IN) 
    VALUES (NULL, ?, ?, ?, 1, 1, ?, ?, ?, ?)");

// Bind parameters
$stmt->bind_param("sssdiss", $pc, $name, $desc, $pr, $cat, $supp, $dats);

// Insert products
for ($i = 0; $i < $qty; $i++) {
    // Execute the statement
    $stmt->execute();
}

// Close the statement
$stmt->close();

// Redirect to product.php
header("Location: product.php");
exit;

include '../includes/footer.php';
?>
