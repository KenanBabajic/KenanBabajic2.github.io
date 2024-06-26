<?php include "Commondiv.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["UserLoggedIn"]) || $_SESSION["UserLoggedIn"] !== true) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "Websitedatabase");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is an admin
$username = $_SESSION["UserName"];
$stmt = $conn->prepare("SELECT Role FROM Users WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userRole);
$stmt->fetch();
$stmt->close();

// Check if user has admin role
if ($userRole !== 'Admin') {
    // Redirect user to unauthorized page if not an admin
    header("Location: unauthorized.php");
    exit();
}

$registrationMessage = '';

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productName']) && isset($_POST['productDescription']) && isset($_POST['productImage']) && isset($_POST['productPrice'])) {
        $productName = $_POST['productName'];
        $productDescription = $_POST['productDescription'];
        $productImage = $_POST['productImage'];
        $productPrice = $_POST['productPrice'];

        // Insert product into database
        $stmt = $conn->prepare("INSERT INTO Products (Product_Name, Description, Image, Price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $productName, $productDescription, $productImage, $productPrice);

        if ($stmt->execute()) {
            $registrationMessage = "Product added successfully";
        } else {
            $registrationMessage = "Error adding product: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Website4.css?val=<?=time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Josefin Sans', sans-serif; /* Updated font */
            background-color: black;
        }

        .Bgimg {
            background: black; /* Add your pattern background */
            color: white;
            text-align: center;
            padding: 50px;
            position: relative;
            overflow: hidden; /* Hide overflowing content */
        }

        .Bgimg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.2; /* Adjust the opacity of the pattern */
        }

        .ElectronicsShop {
            font-size: 100px;
            margin: 0;
            background-color: black; /* Set the background color to black */
            color: #3498db; /* Set the text color to blue */
            padding: 20px; /* Add some padding for better visibility */
            border-radius: 10px; /* Add rounded corners */
            animation: colorChange 5s infinite alternate; /* Add animation */
        }

        @keyframes colorChange {
            0% {
                background-color: #000;
                color: #3498db;
            }
            100% {
                background-color: #3498db;
                color: #000;
            }
        }

        section {
            background: black;
        }

        .top-nav {
            background-color: #333;
            overflow: hidden;
        }

        .top-nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .top-nav a:hover {
            background-color: #ddd;
            color: black;
        }

        footer {
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        div.message {
            margin-top: 10px;
            color: #ff0000;
        }
        form {
            color: black;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            margin: auto;
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            align-items: center;
            box-shadow: rgba(52, 152, 219, 0.4) 5px 5px, rgba(52, 152, 219, 0.3) 10px 10px, rgba(52, 152, 219, 0.2) 15px 15px, rgba(52, 152, 219, 0.1) 20px 20px, rgba(52, 152, 219, 0.05) 25px 25px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        form label {
            display: block;
            margin-bottom: 10px;
        }

        form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #2980b9;
        }

        form:hover {
            transform: scale(0.95); /* Slightly smaller size when selected */
        }
    </style>
    <title>Add Products</title>
</head>
<body>
    <section>
        <div class="Bgimg">
            <p class="ElectronicsShop"><?= ($ArrayOfStrings["CommonShopName"]); ?></p>
        </div>
        <?php
        topnav(6, $language);
        ?>
        <form method="POST">
            <label for="productName"><?= ($ArrayOfStrings["AddProducts-ProductName"]); ?></label>
            <input type="text" name="productName" required><br>

            <label for="productDescription"><?= ($ArrayOfStrings["AddProducts-ProductDescription"]); ?></label>
            <input type="text" name="productDescription" required><br>

            <label for="productImage"><?= ($ArrayOfStrings["AddProducts-ProductImage"]); ?></label>
            <input type="text" name="productImage" required><br>

            <label for="productPrice"><?= ($ArrayOfStrings["AddProducts-ProductPrice"]); ?></label>
            <input type="text" name="productPrice" required><br>

            <input type="submit" name="addProduct" value="<?= ($ArrayOfStrings["AddProducts-Addbutton"]); ?>">
            <div class="message">
                <?php echo $registrationMessage; ?>
            </div>
        </form>
    </section>
    <footer>
        <p>HTML Babajic Kenan 2022</p>
    </footer>
</body>
</html>
