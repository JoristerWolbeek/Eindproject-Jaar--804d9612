<?php
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$query = $pdo->prepare("SELECT * FROM images WHERE user_id=?");
if (isset($_COOKIE['loggedInUser'])) {
    $edit = '<a href="profile.php?editing=true">Edit Account</a>';
    $selected_user = $_COOKIE['loggedInUser'];
    $query->execute([$_COOKIE['loggedInUser']]);
} else {
    $edit="<div></div>";
    $selected_user = $_GET['selected_user'];
    $query->execute([$_GET['selected_user']]);
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$selected_user]);
$stmt = $stmt->fetch();

$default_profile_pic = 'uploads/download.jfif';
if (isset($_GET['editing']) && $_GET['editing'] == 'true') {
    $name = "<form method='post'><input name='name' type='text' placeholder='Name here'>";
    $personalia = "<input name='personalia' type='text' placeholder='personalia here'></form>";
    $edit = "<button onclick='sendingData()'>Save changes</button>";
} else {
    $name = isset($_GET['val']) ? "<h1>Name here</h1>" : "<h1>" . $_GET['val'] . "</h1>";
    $personalia = "<h2>Personalia here</h2>";
}
?>
<!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="JS/script.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>CV Maker | <?= $stmt['username']?>'s profile</title>
    </head>
        <header>
            <div id="header_container">
                <div class="logout_btn">
                    <a href="logout.php">Logout</a>
                </div>
                <h1 id="header_profile">CV Maker | <?= $stmt['username']?>'s profile</h1>
                <?= $edit?>
            </div>
        </header>

    <body>
        <div>
            <div><?php
                if($query){
                    while($row = $query->fetch(PDO::FETCH_ASSOC)){
                            $imageURL = 'uploads/'.$row["file_name"];
                    }
                    $image = isset($imageURL) ? $imageURL : $default_profile_pic;?>
                        <img id="profile_pic" width="200" height="200" src="<?= $image?>" alt="" />
                    <?php 
                }else{ ?>
                    <p>No image(s) found...</p>
                <?php }
                if (isset($_GET['editing']) && $_GET['editing']=='true') {?>
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    Select Image File to Upload:
                    <input type="file" name="file">
                    <input type="submit" name="submit" value="Upload">
                </form>
                <?php } ?>
            </div>
            <div>
                <div>  
                    <?= $name ?>
                    <?= $_GET['val'] ?>
                </div>
                <div>
                    <?= $personalia ?>
                </div>
            </div>
        </div>
        <footer>
            <div class="footerContainer">
                <h4> Code Monkey Incorporated© CV Maker</h4>
                <h4 href="About-us"><a href="https://www.notion.so/bitacademy/2020-Code-Monkey-Incorporated-7c231c7df5f84c4e888f6c85849e0a07">About us</a></h4>
            </div>
        </footer>
    </body>
    </html>