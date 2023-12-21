<?php
require_once(__DIR__ . "/partials/nav.php");

// Function to add product to cart
function addToCart($productId, $name, $price)
{
    try {
        $db = getDB();
        $query = "INSERT INTO cart (product_id, name, price, quantity) VALUES (:product_id, :name, :price, 1)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":product_id", $productId);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":price", $price);
        $stmt->execute();

        echo "Product added to cart successfully!";
    } catch (PDOException $e) {
        echo "Error adding product to cart: " . $e->getMessage();
    }
}

// Function to update quantity of a product in cart
function updateQuantity($productId, $quantity)
{
    try {
        $db = getDB();
        $query = "UPDATE cart SET quantity = :quantity WHERE product_id = :product_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();

        echo "Quantity updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating quantity: " . $e->getMessage();
    }
}

// Function to remove a product from cart
function removeFromCart($productId)
{
    try {
        $db = getDB();
        $query = "DELETE FROM cart WHERE product_id = :product_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();

        echo "Product removed from cart successfully!";
    } catch (PDOException $e) {
        echo "Error removing product from cart: " . $e->getMessage();
    }
}

// Function to clear the cart
function clearCart()
{
    try {
        $db = getDB();
        $query = "DELETE FROM cart";
        $stmt = $db->prepare($query);
        $stmt->execute();

        echo "Cart cleared successfully!";
    } catch (PDOException $e) {
        echo "Error clearing cart: " . $e->getMessage();
    }
}

// Check if product is being added to the cart
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $name = $_GET['name'];
    $price = $_GET['price'];

    // Ensure that product_id, name, and price are not empty
    if (!empty($productId) && !empty($name) && !empty($price)) {
        addToCart($productId, $name, $price);
    } else {
        echo "Invalid product details.";
    }
}

// Check if quantity is being updated
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_quantity']) && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Ensure product_id and quantity are not empty
    if (!empty($productId) && !empty($quantity)) {
        updateQuantity($productId, $quantity);
    } else {
        echo "Invalid quantity update details.";
    }
}

// Check if a product is being removed
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_item']) && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Ensure product_id is not empty
    if (!empty($productId)) {
        removeFromCart($productId);
    } else {
        echo "Invalid product details for removal.";
    }
}

// Check if cart is being cleared
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['clear_cart'])) {
    clearCart();
}

// Retrieve cart items from the database
$cartItems = [];
try {
    $db = getDB();
    $query = "SELECT * FROM cart";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle any errors here...
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($cartItems as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                        <input type="submit" name="update_quantity" value="Update">
                    </form>
                </td>
                <td><?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="submit" name="remove_item" value="Remove">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong><?php echo number_format($total, 2); ?></strong></td>
                <td>
                    <form method="POST" action="">
                        <input type="submit" name="clear_cart" value="Clear Cart">
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
