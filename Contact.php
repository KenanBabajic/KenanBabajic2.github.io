<?php include "Commondiv.php"; ?>
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
            background-color: #000; /* Set the background color to black */
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
            margin-bottom: 70px;
            padding: 0;
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

        .welcome {
            font-size: 24px;
            color: white;
            text-align: center;
        }

        .address {
            text-align: center;
            justify-content: center;
            align-items: center;
            margin-top: 20px;

        }

        iframe {
            width: 50%;
            height: 200px;
            border: none;
            margin-top: 20px;
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
        .section2 {
            width: 400px;
            margin: 20px auto;
padding: 20px;
            border: 1px solid black;
            border-radius: 10px;
            background-color: white;
            box-shadow: rgba(52, 152, 219, 0.4) 5px 5px, rgba(52, 152, 219, 0.3) 10px 10px, rgba(52, 152, 219, 0.2) 15px 15px, rgba(52, 152, 219, 0.1) 20px 20px, rgba(52, 152, 219, 0.05) 25px 25px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        .section2:hover {
            transform: scale(0.95); /* Slightly smaller size when selected */

        }
        .welcome {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #3498db;
        }

        .address p {
            margin: 10px 0;
            font-size: 16px;
            color: black;
        }

        a .link {
            color: black;
            text-decoration: none;
        }

        #link {
            color: black;
        }

        #link:hover {
            color: #3498db;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 10px;
            margin-top: 20px;
        }
        #Spacesection {
            padding-bottom: 30px;
        }
    </style>
    <title></title>
</head>

<body>

    <section>      
        <div class="Bgimg">
            <p class="ElectronicsShop"><?=($ArrayOfStrings["CommonShopName"]);?></p>
        </div>
        <?php
        topnav(3, $language);
        ?>
    </section>
    <section id="Spacesection">
    <section class="section2">
        <p class="welcome"><?=($ArrayOfStrings["ContactHeader"]);?></p>
        <address class="address">
            <p><?=($ArrayOfStrings["ContactAddress"]);?>: 50 Rue de Beggen, 1220 Luxembourg</p>
            <p><?=($ArrayOfStrings["ContactEmail"]);?>: <a id="link" href="mailto:electronicsshopKenanBabajic@gmail.com">electronicsshopKenanBabajic@gmail.com</a>.</p>
            <p><?=($ArrayOfStrings["ContactPhoneNumber"]);?>:<a id="link" href="tel:691242042222">691 242 042 222</a></p>
        </address>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10336.120780609705!2d6.1349077!3d49.6348855!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xdf0b2f1f4e71633b!2sLyc%C3%A9e%20Priv%C3%A9%20Emile%20Metz!5e0!3m2!1sde!2slu!4v1651656582603!5m2!1sde!2slu"
            allowfullscreen; loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    <footer>
        <p>HTML Babajic Kenan 2022</p>
    </footer>
</body>

</html>
