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
            color: white;
            text-align: center;
        }

        .unauthorized-container {
            padding: 50px;
        }

        .unauthorized-container h1 {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .unauthorized-container p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .unauthorized-container a {
            text-decoration: none;
            color: white;
            background-color: #3498db;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .unauthorized-container a:hover {
            background-color: #2980b9;
        }
    </style>
    <title>Unauthorized Access</title>
</head>
<body>
    <div class="unauthorized-container">
        <h1>Unauthorized Access</h1>
        <p>You don't have access to this page.</p>
        <a href="Home.php">Go Back</a>
    </div>
</body>
</html>
