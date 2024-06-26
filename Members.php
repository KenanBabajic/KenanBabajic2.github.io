<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Website4.css?val=<?=time(); ?>">
    <title></title>
</head>
<body>
    <section>
    <div class="Bgimg">
        <p class="ElectronicsShop">Electronics Shop</p>
        <a href="MembersFR.php"><img src="FrenchFlag.png" alt="" height="100px" width="100px"></a>
    </div>
    <?php
    include "Commondiv.php";
    topnav(5, $language);

    ?>
    </section>
    <section>
      <p class="Welcome3">All of our members:</p>
 
    <table class="myTable">
    <tr>
    <th>Employees</th><th>Position</th><th>E-mail Adress</th>
    </tr>
    <tr>
    <td>Kenan Babajic</td><td>Owner</td><td>kenanbabajic@gmail.com</td>
    </tr>
    <tr>
    <td>Laura Gaynor</td><td>Marketing Manager</td><td>lauragaynor@gmail.com</td>
    </tr>
    <tr>
        <td>Adelaide Catharine</td><td>Data Entry Clerk.</td><td>adelaidecatharine@gmail.com</td>
        </tr>
        <tr>
            <td>Svanhild Murali</td><td>Customer Service Representative</td><td>svanhildmurali@gmail.com</td>
            </tr>
            <tr>
                <td>Sabina Hans</td><td>Marketing Manager</td><td>sabinahans@gmail.com</td>
                </tr>
                <tr>
                    <td>Merja Zion</td><td>Executive Assistant</td><td>merjazion@gmail.com</td>
                    </tr>
    </table>
</section>
    <footer>
        <p>HTML Babajic Kenan 2022</p>
    </footer>
</body>
</html>