<?php
include "Commondiv.php";

// Establish database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "Websitedatabase"; // Your MySQL database name
$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the user is logged in and has a valid UserId
if (!isset($_SESSION["UserLoggedIn"])) {
    header("Location: login.php");
    exit;
}

// Fetch user's role
$username = $_SESSION["UserName"];
$stmt = $db->prepare("SELECT Role FROM Users WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userRole);
$stmt->fetch();
$stmt->close();

if ($userRole == 'Admin') {
    $sql = "SELECT u.UserName, o.OrderId, 
                   COALESCE(o.TotalItems, SUM(up.CountOfItemsBought)) as TotalItems, 
                   COALESCE(o.TotalPrice, SUM(up.CountOfItemsBought * up.Price)) as TotalPrice, 
                   o.Status 
            FROM Orders o
            LEFT JOIN userstoproducts up ON o.OrderId = up.OrderId
            LEFT JOIN Users u ON up.UserId = u.UserId
            GROUP BY o.OrderId, o.TotalItems, o.TotalPrice";
    $result = $db->query($sql);
} else {
    $sql = "SELECT u.UserName, o.OrderId, 
                   COALESCE(o.TotalItems, SUM(up.CountOfItemsBought)) as TotalItems, 
                   COALESCE(o.TotalPrice, SUM(up.CountOfItemsBought * up.Price)) as TotalPrice, 
                   o.Status 
            FROM Orders o
            LEFT JOIN userstoproducts up ON o.OrderId = up.OrderId
            LEFT JOIN Users u ON up.UserId = u.UserId
            WHERE u.UserName = ?
            GROUP BY o.OrderId, o.TotalItems, o.TotalPrice";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Handle order processing if the form is submitted
if ($userRole == 'Admin' && isset($_POST['processOrder'])) {
    // Get the order ID to process
    $orderIdToProcess = $_POST['ProcessOrderId'];

    // Calculate the total price of the order
    $orderTotalSql = "SELECT SUM(CountOfItemsBought * Price) AS TotalPrice, SUM(CountOfItemsBought) AS TotalItems 
                      FROM userstoproducts WHERE OrderId = ?";
    $orderTotalStmt = $db->prepare($orderTotalSql);
    $orderTotalStmt->bind_param("i", $orderIdToProcess);
    $orderTotalStmt->execute();
    $orderTotalStmt->bind_result($totalPrice, $totalItems);
    $orderTotalStmt->fetch();
    $orderTotalStmt->close();

    // Update the order status to 'Processed' and set the total price and total items
    $updateStatusSql = "UPDATE Orders SET Status = 'Processed', TotalPrice = ?, TotalItems = ? WHERE OrderId = ?";
    $updateStatusStmt = $db->prepare($updateStatusSql);
    $updateStatusStmt->bind_param("dii", $totalPrice, $totalItems, $orderIdToProcess);
    $updateStatusStmt->execute();
    $updateStatusStmt->close();

    // Redirect to the same page to refresh the order history
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit;
}
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
            font-family: 'Josefin Sans', sans-serif;
            background-color: black;
            color: white;
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
            background-color: #000;
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

        .top-nav {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
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
    </style>
</head>
<body>
<section>
    <div class="Bgimg">
        <p class="ElectronicsShop">
            <?= ($ArrayOfStrings["CommonShopName"]); ?>
        </p>
    </div>
    <?php
    topnav(8, $language);
    ?>
</section>
<h1>Your Order History</h1>
<table>
    <tr>
        <th>User Name</th>
        <th>Order ID</th>
        <th>Total Items</th>
        <th>Total Price</th>
        <th>Status</th>
        <?php if ($userRole == 'Admin') { ?>
            <th>Action</th>
        <?php } ?>
        <!-- Add more columns as needed -->
    </tr>
    <?php
    // Check if there are results and display them
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["UserName"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["OrderId"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["TotalItems"]) . "</td>";
            // Display the total price with the appropriate message based on the order status
            if ($row["Status"] == "Pending") {
                echo "<td>" . htmlspecialchars($row["TotalPrice"]) . " (can fluctuate)</td>";
            } else {
                echo "<td>" . htmlspecialchars($row["TotalPrice"]) . "</td>";
            }
            echo "<td>" . htmlspecialchars($row["Status"]) . "</td>";
            if ($userRole == 'Admin' && $row["Status"] == "Pending") {
                // Display process order button for Admin
                echo "<td>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='ProcessOrderId' value='" . htmlspecialchars($row["OrderId"]) . "'>";
                echo "<input type='submit' name='processOrder' value='Process'>";
                echo "</form>";
                echo "</td>";
            } else {
                echo "<td></td>";
            }
            echo "<td>";
            if (isset($_POST["OrderId"]) && ($_POST["OrderId"] == $row["OrderId"])) {
                echo "<table>";
                echo "<tr><th>Product Name</th><th>Count</th></tr>";
                $selectProducts = $db->prepare("SELECT Product_Name, CountOfItemsBought FROM userstoproducts WHERE OrderId = ?");
                $selectProducts->bind_param("i", $_POST["OrderId"]);
                $selectProducts->execute();
                $resultProducts = $selectProducts->get_result();

                while ($rowProducts = $resultProducts->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($rowProducts["Product_Name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($rowProducts["CountOfItemsBought"]) . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<form method='POST'>";
                echo "<input type='hidden' name='OrderId' value='" . $row["OrderId"] . "'>";
                echo "<input type='submit' value='Details'>";
                echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No orders found.</td></tr>";
    }
    ?>
</table>
</body>
</html>

