<?php
session_start();
include "Commondiv.php";

if (!isset($_SESSION["UserLoggedIn"]) || $_SESSION["UserLoggedIn"] !== true) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

$username = $_SESSION["UserName"];
$conn = new mysqli("localhost", "root", "", "Websitedatabase");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is an admin
$stmt = $conn->prepare("SELECT Role FROM Users WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userRole);
$stmt->fetch();
$stmt->close();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buy'])) {
        $productID = $_POST['productID'];
        $quantity = 1;
        $_SESSION['cart'][$productID] = isset($_SESSION['cart'][$productID]) ? $_SESSION['cart'][$productID] + $quantity : $quantity;
    } elseif (isset($_POST['updateProduct']) && $userRole === 'Admin') {
        $productID = $_POST['productID'];
        $productName = $_POST['productName'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];

        $updateSql = "UPDATE Products SET Product_Name=?, Description=?, Price=?, Image=? WHERE Product_ID=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssdsi", $productName, $description, $price, $image, $productID);
        $stmt->execute();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="Website4.css?val=<?= time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Josefin Sans', sans-serif;
            background-color: black;
        }

        .Bgimg {
            background: black;
            color: white;
            text-align: center;
            padding: 50px;
            position: relative;
            overflow: hidden;
        }

        .Bgimg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.2;
        }

        .ElectronicsShop {
            font-size: 100px;
            margin: 0;
            background-color: black;
            color: #3498db;
            padding: 20px;
            border-radius: 10px;
            animation: colorChange 5s infinite alternate;
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
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .top-nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .AllProducts {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            align-items: center;
            padding-top: 50px;
        }

        .OneProduct {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            border-radius: 10px;
            width: 400px;
            height: 600px;
            text-align: center;
            background-color: white;
            box-shadow: rgba(52, 152, 219, 0.4) 5px 5px, rgba(52, 152, 219, 0.3) 10px 10px, rgba(52, 152, 219, 0.2) 15px 15px, rgba(52, 152, 219, 0.1) 20px 20px, rgba(52, 152, 219, 0.05) 25px 25px;
            margin: 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        .OneProduct:hover {
            transform: scale(0.95);
        }

        .OneProduct img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-details {
            padding: 20px;
            color: black;
        }

        #BuyShop {
            width: 100px;
            height: 50px;
            border-radius: 10px;
            background-color: #3498db;
            border: none;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            margin-bottom: 20px;
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

        #Spacesection {
            padding-bottom: 100px;
        }
    </style>
</head>

<body>
    <section>
        <div class="Bgimg">
            <p class="ElectronicsShop"><?= ($ArrayOfStrings["CommonShopName"]); ?></p>
        </div>
        <?php
        topnav(4, $language);
        ?>
    </section>
    <section id="Spacesection">
        <div class="AllProducts">
            <?php
            $sql = "SELECT * FROM Products";
            $result = $conn->query($sql);
            $totalItemsInCart = 0;
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $totalItemsInCart = array_sum($_SESSION['cart']);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $quantityInCart = 0;
                    if (isset($_SESSION['cart'][$row["Product_ID"]])) {
                        $quantityInCart = $_SESSION['cart'][$row["Product_ID"]];
                    }
                    ?>
                    <div class="OneProduct">
                        <img src="<?php echo $row["Image"]; ?>" alt="<?php echo $row["Product_Name"]; ?>" style="max-width: 300px;">
                        <div class="product-details">
                            <?php echo $row["Product_Name"]; ?>
                            <div><strong>Description:</strong> <?php echo $row["Description"]; ?></div>
                            <div><strong>Price:</strong> <?php echo $row["Price"]; ?>€</div>
                            <form method="POST">
                                <input name="productID" value="<?php echo $row["Product_ID"]; ?>" type="hidden">
                                <input type="submit" name="buy" value="BUY" id="BuyShop">
                            </form>
                            <div>Quantity in Cart: <?php echo $quantityInCart; ?></div>

                            <?php if ($userRole === 'Admin') { ?>
                                <form method="POST">
                                    <input type="hidden" name="productID" value="<?php echo $row["Product_ID"]; ?>">
                                    <label>Name:</label>
                                    <input type="text" name="productName" value="<?php echo $row["Product_Name"]; ?>"><br>
                                    <label>Description:</label>
                                    <input type="text" name="description" value="<?php echo $row["Description"]; ?>"><br>
                                    <label>Price:</label>
                                    <input type="text" name="price" value="<?php echo $row["Price"]; ?>"><br>
                                    <label>Image URL:</label>
                                    <input type="text" name="image" value="<?php echo $row["Image"]; ?>"><br>
                                    <input type="submit" name="updateProduct" value="Update">
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </div>
    </section>
    <footer>
        <p>HTML Babajic Kenan 2022</p>
    </footer>
</body>

</html>
