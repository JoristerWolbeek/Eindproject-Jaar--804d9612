<?php
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$query = $pdo->prepare("SELECT * FROM images WHERE user_id=?");
if (isset($_COOKIE['loggedInUser'])) {
    $selected_user = $_COOKIE['loggedInUser'];
    $query->execute([$_COOKIE['loggedInUser']]);
} else {
    $selected_user = $_GET['selected_user'];
    $query->execute([$_GET['selected_user']]);
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$selected_user]);
$stmt = $stmt->fetch();

$default_profile_pic = 'uploads/download.jfif';
$edit="<div></div>";
$stmt1 = $pdo->prepare("SELECT * FROM profile_pages WHERE user_id=?");
$stmt1->execute([$_COOKIE['loggedInUser']]);
$show = $stmt1->fetch();
?>
<!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="JS/script.js"></script>
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
    <body id="soDone">
        <div id="overlord">
            <div id="top_container">
                <div id="profile_pic_container"><?php
                    if($query){
                        while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                $imageURL = 'uploads/'.$row["file_name"];
                        }
                        $image = isset($imageURL) ? $imageURL : $default_profile_pic;?>
                            <img id="profile_pic" width="200" height="200" src="<?= $image?>" alt="" />
                        <?php 
                    }else{ ?>
                        <p>No image(s) found...</p>
                    <?php } ?>
                    <form id="change_pic" action="upload.php" method="post" enctype="multipart/form-data" hidden>
                        <input id="choose_pic" type="file" name="file" hidden>
                        <input id="up_pic" type="submit" name="submit" value="Upload" hidden>
                    </form>
                </div>
                <div>
                    <div>  
                        <h1 class="switchTo" id="name"><?= $show['name']?></h1>
                        <form method='post'><input class='in' name='name' type='text' placeholder='<?= $show['name']?>' hidden>
                    </div>
                    <div>
                        <input class='in' name='personalia' type='text' placeholder='<?= $show['personalia']?>' hidden></form>
                        <h2 class="switchTo" id="personalia"><?= $show['personalia']?></h2>
                        <ul>
                            <li class="resizing_list"><div class="switchTo" id="ulist1"><?= $show['ulist1']?></div><input class='in' name='ulist1' type='text' placeholder='<?= $show['ulist1']?>' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist2"><?= $show['ulist2']?></div><input class='in' name='ulist2' type='text' placeholder='<?= $show['ulist2']?>' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist3"><?= $show['ulist3']?></div><input class='in' name='ulist3' type='text' placeholder='<?= $show['ulist3']?>' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist4"><?= $show['ulist4']?></div><input class='in' name='ulist4' type='text' placeholder='<?= $show['ulist4']?>' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist5"><?= $show['ulist5']?></div><input class='in' name='ulist5' type='text' placeholder='<?= $show['ulist5']?>' hidden></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="mid_container">
                <div>
                    <input class='in' name='skills' type='text' placeholder='<?= $show['skills']?>' hidden></form>
                    <h2 class="switchTo" id="skills"><?= $show['skills']?></h2>
                    <ol>
                        <li class="resizing_list"><div class="switchTo" id="olist1"><?= $show['olist1']?></div><input class='in' name='olist1' type='text' placeholder='<?= $show['olist1']?>' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist2"><?= $show['olist2']?></div><input class='in' name='olist2' type='text' placeholder='<?= $show['olist2']?>' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist3"><?= $show['olist3']?></div><input class='in' name='olist3' type='text' placeholder='<?= $show['olist3']?>' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist4"><?= $show['olist4']?></div><input class='in' name='olist4' type='text' placeholder='<?= $show['olist4']?>' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist5"><?= $show['olist5']?></div><input class='in' name='olist5' type='text' placeholder='<?= $show['olist5']?>' hidden></li>
                    </ol>
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