<?php 
include "Commondiv.php";

// Initialize variables
$messageForIncorrectLoging = false;
$registrationMessage = '';

// Check if user is logged in
if (!isset($_SESSION["UserLoggedIn"])) {
    $_SESSION["UserLoggedIn"] = false;
}

$password = 'adminpassword';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
echo $hashedPassword;

// Login logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $username = $_POST["UserName"];
    $password = $_POST["Password"];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "Websitedatabase");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username exists and fetch the hashed password
    $stmt = $conn->prepare("SELECT Password FROM Users WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["Password"])) {
            $_SESSION["UserLoggedIn"] = true;
            $_SESSION["UserName"] = $username;
        } else {
            $messageForIncorrectLoging = true;
        }
    } else {
        $messageForIncorrectLoging = true;
    }

    $conn->close();
}

// Registration logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    $username = $_POST["UserNameRegistration"];
    $password = $_POST["PasswordRegistration"];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "Websitedatabase");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username is NOT already taken
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $registrationMessage = "Username is already taken.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database with hashed password
        $stmt = $conn->prepare("INSERT INTO Users (UserName, Password, Role) VALUES (?, ?, 'Guest')");
        $stmt->bind_param("ss", $username, $hashedPassword);
        if ($stmt->execute()) {
            $registrationMessage = "Registration successful.";
        } else {
            $registrationMessage = "Error: " . $conn->error;
        }
        $stmt->close();
    }

    $conn->close();
}

// Logout logic
if (isset($_POST["Logout"])) {
    $_SESSION["UserLoggedIn"] = false;
    session_unset();
    session_destroy();
    header("Location: login.php"); // Redirect to login page after logout
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Website4.css?val=<?= time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&display=swap" rel="stylesheet">
    <title>User Registration and Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Josefin Sans', sans-serif;
            /* Updated font */
            background-color: black;
        }

        .Bgimg {
            background: black;
            /* Add your pattern background */
            color: white;
            text-align: center;
            padding: 50px;
            position: relative;
            overflow: hidden;
            /* Hide overflowing content */
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
            /* Adjust the opacity of the pattern */
        }

        .ElectronicsShop {
            font-size: 100px;
            margin: 0;
            background-color: #000;
            /* Set the background color to black */
            color: #3498db;
            /* Set the text color to blue */
            padding: 20px;
            /* Add some padding for better visibility */
            border-radius: 10px;
            /* Add rounded corners */
            animation: colorChange 5s infinite alternate;
            /* Add animation */
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

        form:hover {
            transform: scale(0.95);
            /* Slightly smaller size when selected */
        }

        input {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
        }

        div.message {
            margin-top: 10px;
            color: #ff0000;
        }

        h2 {
            text-align: center;
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
            margin-bottom: 100px;
        }

        h1 {
            color: white;
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
        topnav(5, $language);
        ?>

    </section>
    <section>

    <?php if (!$_SESSION["UserLoggedIn"]) : ?>
        <!-- Registration Form -->
        <form method="POST">
            <h2><?= ($ArrayOfStrings["UserRegistrationHeader"]); ?></h2>
            <input type="text" name="UserNameRegistration" placeholder="<?= ($ArrayOfStrings["UserUsername"]); ?>" required>
            <input type="password" name="PasswordRegistration" placeholder="<?= ($ArrayOfStrings["UserPassword"]); ?>" required>
            <input type="submit" value="<?= ($ArrayOfStrings["UserRegistrationPegister"]); ?>" name="register">
            <div class="message"><?= $registrationMessage; ?></div>
        </form>

        <!-- Login Form -->
        <form method="POST" id="Spacesection">
            <h2><?= ($ArrayOfStrings["UserLoginHeader"]); ?></h2>
            <input type="text" name="UserName" placeholder="<?= ($ArrayOfStrings["UserUsername"]); ?>" required>
            <input type="password" name="Password" placeholder="<?= ($ArrayOfStrings["UserPassword"]); ?>" required>
            <input type="submit" value="<?= ($ArrayOfStrings["UserLoginLogin"]); ?>" name="login">
            <div class="message"><?= $messageForIncorrectLoging ? $ArrayOfStrings["WrongPassword"] : ""; ?></div>
        </form>
    <?php else : ?>
        <h1>Welcome back, <?= $_SESSION["UserName"] ?></h1>
        <form method="POST">
            <input type="submit" name="Logout" value="Logout">
        </form>
    <?php endif; ?>

    </section>
    <footer>
        <p>HTML Babajic Kenan 2022</p>
    </footer>
</body>

</html>
