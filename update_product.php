<?php
session_start();

if (!isset($_SESSION["UserLoggedIn"]) || $_SESSION["UserLoggedIn"] !== true) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'];
    $name = $_POST['productName'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    echo "Product ID: " . $productID . "<br>";
    echo "Name: " . $name . "<br>";
    echo "Description: " . $description . "<br>";
    echo "Price: " . $price . "<br>";
    echo "Image: " . $image . "<br>";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Websitedatabase";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE Products SET Product_Name = ?, Description = ?, Price = ?, Image = ? WHERE Product_ID = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $productID);

    if ($stmt->execute()) {
        // Update successful
        // Redirect back to the Products page or display a success message
        header("Location: products.php");
        exit();
    } else {
        // Error occurred during update
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    echo "Invalid request";
}
