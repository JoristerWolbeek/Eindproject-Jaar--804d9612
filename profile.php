<?php
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$query = $pdo->prepare("SELECT * FROM images WHERE user_id=?");
$stmt1 = $pdo->prepare("SELECT * FROM profile_pages WHERE user_id=?");
if (isset($_COOKIE['loggedInUser'])) {
    $btn = '<div class="logout_btn">
    <a href="logout.php">Logout</a>
</div>';
    $selected_user = $_COOKIE['loggedInUser'];
    $query->execute([$_COOKIE['loggedInUser']]);
    $stmt1->execute([$_COOKIE['loggedInUser']]);
} else {
    $btn = '<div class="logout_btn">
    <a href="summary.php">Back</a>
</div>';
    $selected_user = $_GET['selected_user'];
    $query->execute([$_GET['selected_user']]);
    $stmt1->execute([$_GET['selected_user']]);
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$selected_user]);
$stmt = $stmt->fetch();
$ordered = array("olist1", "olist2", "olist3", "olist4", "olist5");
$unordered = array("ulist1", "ulist2", "ulist3", "ulist4", "ulist5", "ulist6", "ulist7", "ulist8", "ulist9", "ulist10");
$work = array("name_work1", "name_work2", "name_work3");
$dates = array("date_work1", "date_work2", "date_work3", "date_work4", "date_work5", "date_work6");
$default_profile_pic = 'uploads/download.jfif';
$edit="<div></div>";
$show = $stmt1->fetch();
$stmt2 = $pdo->prepare("SELECT * FROM profile_data_entries WHERE profile_id=?");
$stmt2->execute([$show['id']]);
$data_entries = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$show2= array();
foreach ($data_entries as $key => $data) {
    if (in_array($data['element_id'], $unordered) || in_array($data['element_id'], $ordered) || in_array($data['element_id'], $work)) {
        $show2 = array_push_assoc($show2, $data['element_id'], $data['data']);
    } else if (in_array($data['element_id'], $dates)) {
        $show2 = array_push_assoc($show2, $data['element_id'], $data['date']);
    }
}
function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
}
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
            <?= $btn?>
            <h1 id="header_profile">CV Maker | <?= $stmt['username']?>'s profile</h1>
            <?= $edit?>
            </div>
    </header>
    <body>
        <div class="overlord">
            <div id="top_container">
                <div id="profile_pic_container"><?php
                    if($query){
                        while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                $imageURL = 'uploads/'.$row["file_name"];
                        }
                        $image = isset($imageURL) ? $imageURL : $default_profile_pic;?>
                            <img class="profile_pic" width="200" height="200" src="<?= $image?>" alt="" />
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
                            <li class="resizing_list"><div class="switchTo" id="ulist1"><?= isset($show2['ulist1'])? $show2['ulist1'] : "Double click me!"?></div><input class='in' name='ulist1' type='text' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist2"><?= isset($show2['ulist2'])? $show2['ulist2'] : "Double click me!"?></div><input class='in' name='ulist2' type='text' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist3"><?= isset($show2['ulist3'])? $show2['ulist3'] : "Double click me!"?></div><input class='in' name='ulist3' type='text' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist4"><?= isset($show2['ulist4'])? $show2['ulist4'] : "Double click me!"?></div><input class='in' name='ulist4' type='text' hidden></li>
                            <li class="resizing_list"><div class="switchTo" id="ulist5"><?= isset($show2['ulist5'])? $show2['ulist5'] : "Double click me!"?></div><input class='in' name='ulist5' type='text' hidden></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="mid_container">
                <div>
                    <input class='in' name='skills' type='text' placeholder='<?= $show['skills']?>' hidden>
                    <h2 class="switchTo" id="skills"><?= $show['skills']?></h2>
                    <ol>
                        <li class="resizing_list"><div class="switchTo" id="olist1"><?= isset($show2['olist1'])? $show2['olist1'] : "Double click me!"?></div><input class='in' name='olist1' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist2"><?= isset($show2['olist2'])? $show2['olist2'] : "Double click me!"?></div><input class='in' name='olist2' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist3"><?= isset($show2['olist3'])? $show2['olist3'] : "Double click me!"?></div><input class='in' name='olist3' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist4"><?= isset($show2['olist4'])? $show2['olist4'] : "Double click me!"?></div><input class='in' name='olist4' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="olist5"><?= isset($show2['olist5'])? $show2['olist5'] : "Double click me!"?></div><input class='in' name='olist5' type='text' hidden></li>
                    </ol>
                </div>
                <div>
                    <input class='in' name='work' type='text' placeholder='<?= $show['work']?>' hidden>
                    <h2 class="switchTo" id="work"><?= $show['work']?></h2>
                    <div>
                        <input class='in' name="name_work1" type='text' placeholder='<?= isset($show2['name_work1'])? $show2['name_work1'] : "Double click me! (Name of workplace)"?>' hidden></form>
                        <h3 class="switchTo" id="name_work1"><?= isset($show2['name_work1'])? $show2['name_work1'] : "Double click me! (Name of workplace)"?></h3>
                        <div>
                            <div class="date">
                                From
                                <h4 class="switchTo" id="date_work1"><?= isset($show2['date_work1'])? $show2['date_work1'] : date("Y-m-d")?></h4><input class='in' name='date_work1' type='date' placeholder="<? $show['date_work1']?>" hidden>
                                To
                                <h4 class="switchTo" id="date_work2"><?= isset($show2['date_work2'])? $show2['date_work2'] : date("Y-m-d")?></h4><input class='in' name='date_work2' type='date' placeholder="<? $show['date_work2']?>" hidden>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input class='in' name="name_work2" type='text' placeholder='<?= isset($show2['name_work2'])? $show2['name_work2'] : "Double click me! (Name of workplace)"?>' hidden></form>
                        <h3 class="switchTo" id="name_work2"><?= isset($show2['name_work2'])? $show2['name_work2'] : "Double click me! (Name of workplace)"?></h3>
                        <div>
                            <div class="date">
                                From
                                <h4 class="switchTo" id="date_work3"><?= isset($show2['date_work3'])? $show2['date_work3'] : date("Y-m-d")?></h4><input class='in' name='date_work3' type='date' placeholder="<? $show['date_work3']?>" hidden>
                                To
                                <h4 class="switchTo" id="date_work4"><?= isset($show2['date_work4'])? $show2['date_work4'] : date("Y-m-d")?></h4><input class='in' name='date_work4' type='date' placeholder="<? $show['date_work4']?>" hidden>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input class='in' name="name_work3" type='text' placeholder='<?= isset($show2['name_work3'])? $show2['name_work3'] : "Double click me! (Name of workplace)"?>' hidden></form>
                        <h3 class="switchTo" id="name_work3"><?= isset($show2['name_work3'])? $show2['name_work3'] : "Double click me! (Name of workplace)"?></h3>
                        <div>
                            <div class="date">
                                From
                                <h4 class="switchTo" id="date_work5"><?= isset($show2['date_work5'])? $show2['date_work5'] : date("Y-m-d")?></h4><input class='in' name='date_work5' type='date' placeholder="<? $show['date_work5']?>" hidden>
                                To
                                <h4 class="switchTo" id="date_work6"><?= isset($show2['date_work6'])? $show2['date_work6'] : date("Y-m-d")?></h4><input class='in' name='date_work6' type='date' placeholder="<? $show['date_work6']?>" hidden>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bot_container">
                <div>
                    <input class='in' name='personalia1' type='text' placeholder='<?= $show['personalia1']?>' hidden></form>
                    <h2 class="switchTo" id="personalia1"><?= $show['personalia1']?></h2>
                    <ul>
                        <li class="resizing_list"><div class="switchTo" id="ulist6"><?= isset($show2['ulist6'])? $show2['ulist6'] : "Double click me!"?></div><input class='in' name='ulist6' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="ulist7"><?= isset($show2['ulist7'])? $show2['ulist7'] : "Double click me!"?></div><input class='in' name='ulist7' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="ulist8"><?= isset($show2['ulist8'])? $show2['ulist8'] : "Double click me!"?></div><input class='in' name='ulist8' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="ulist9"><?= isset($show2['ulist9'])? $show2['ulist9'] : "Double click me!"?></div><input class='in' name='ulist9' type='text' hidden></li>
                        <li class="resizing_list"><div class="switchTo" id="ulist10"><?= isset($show2['ulist10'])? $show2['ulist10'] : "Double click me!"?></div><input class='in' name='ulist10' type='text' hidden></li>
                    </ul>
                </div>   
            </div>
            </center>
        </div>
    </body>
    <footer>
    </footer>
    </html>