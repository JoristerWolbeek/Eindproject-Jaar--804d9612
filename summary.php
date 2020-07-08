<?php
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$query = $pdo->query("SELECT * FROM users");
$query2 = $pdo->prepare("SELECT * FROM profile_pages WHERE user_id=?")
?>
<!DOCTYPE html>
    <html>
    <center>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="JS/script.js"></script>
    </head>
    <header>
        <div id="header_container">
            <h1 id="header_summary">Summary of all users</h1>
        </div>
    </header>
    <body id="soDone">
    <table id="myTable">
    <tr><th><input autocomplete="off" class='in_summary' name='username' type='text' placeholder='Search by Username'><div class="Switch"  id="name" >Username</div></th>
    <th><input autocomplete="off" class='in_summary' name='name' type='text' placeholder='Search by Name'><div class="Switch" id="name" >Name</div></th></tr>
    <?php 
    while ($show = $query->fetch(PDO::FETCH_ASSOC)) {
        $query2->execute([$show['id']]);
        $show_real_name = $query2->fetch(PDO::FETCH_ASSOC);
        echo '<tr><td style="text-align:center;"><a href="profile.php?selected_user=' . $show['id'] . '">';
        echo($show['username'] . '</a></td><td style="text-align:center;">' .  $show_real_name['name']);
        echo '</td></tr>';
    }?>
    </table>
    </body>
    <footer>
    </footer>
    </center>
    </html>