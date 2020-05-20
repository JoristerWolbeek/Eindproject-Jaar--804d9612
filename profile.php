
<head>    
    <script src="JS/profile_script.js"></script>
</head>
<?php
class User
{
    public $id;
    public $username;
    public $email;
    public $phone;
    public $adress;
    public $age;
    public $linkedin;
    public $hobbies;
    public $skills;
    public $past_work;
    public $motivation;

    public function __construct($id, $username, $email){
        $this->username = $username;
        $this->id = $id;
        $this->email = $email;
    }
}



$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

if(!isset($_COOKIE["loggedInUser"])){
    header('Location: login.php');
}

try {
    $stmt = $pdo->query('SELECT * FROM users WHERE id = ' . $_COOKIE["loggedInUser"]);
    if($stmt->rowCount() == 0) {
        header('Location: login.php');
    }
    while($row = $stmt->fetch()) {
        $user = new User($row["id"], $row["username"], $row["email"]);
    }
}catch(Exception $e) {
    echo "<h3>$e</h3>";
}
?>
<div id="position_log">
    <div class="logout_btn">
    <a href="logout.php">Logout</a>
    </div>
    </div>

    <main class="profile_main">
        <?php
        if($user->id == $_COOKIE["loggedInUser"]) {
        ?>
            <a class="edit_account" href="account_edit.php?user_id=<?= $user->id?>"> Edit account <div class="edit-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div></a>
        <?php
        }

        try {
            $stmt = $pdo->query('SELECT * FROM profile_pages WHERE user_id = '.$user->id);
            if($stmt->rowCount() == 0) {
                throw new Exception("No profile page found!");
            }

            while($row = $stmt->fetch()){
                $profile_id = $row["id"];
                ?>
                <div class="profile_page_content">
                    <?php
                    if($user->id == $_COOKIE["loggedInUser"]) {
                        ?>
                        <div contenteditable="true">
                        <?php
                    } else {
                        ?>
                        <div contenteditable="false">
                        <?php
                    }
                    ?>
                        <h1 id="profile_page_title"><?= $row["title"]?></h1>
                    </div>
                    <!-- PROFILE PAGE CONTENT START -->
                    <div id="profile_page_content" class="">
                        <?php
                        $html = $row["html"];
                        if($user->id != $_COOKIE["loggedInUser"]) {
                            $html = str_replace( 'contenteditable="true"', 'contenteditable="false"', $html);
                            $html = str_replace( '<button class="delete danger padding" onclick="deleteHtml(this)">Delete</button>', '', $html);
                        }
                        echo $html;
                        ?>
                    </div>
                    <!-- PROFILE PAGE CONTENT END -->
                </div>
                <?php
                if($user->id == $_COOKIE["loggedInUser"]) {
                    ?>
                    <div class="profile_page_controls">
                        <select id="element_to_add" class="blue padding" onchange="selectChange()">
                            <option value="text">Text</option>
                            <option value="h1">Title</option>
                            <option value="h3">Header</option>
                            <option value="ul">Unordered list</option>
                            <option value="ol">Ordered list</option>
                            <option value="img">Image</option>
                        </select>
                        <br>
                        <div id="color_options">
                            <label for="text_color">Text: </label>
                            <input type="color" id="text_color" class="blue padding">
                            <br>
                            <label for="bg_color">Background: </label>
                            <input type="checkbox" id="bg_on" class="blue padding" style="width:15px;height:15px">
                            <input type="color" id="bg_color" class="blue padding" value="#ffffff">
                            <br>
                            <button class="margin_top blue padding" onclick="addHtml()">Add selected element</button>
                            <br>
                        </div>
                        <div id="image_options" style="display:none;">
                            <form action="profile.php?user=<?= $_GET["user"]?>" method="post" enctype="multipart/form-data">
                                <input class="blue padding" type="file" name="fileToUpload"><br>
                                <label for="width_val">Image Width(%): </label><input class="blue padding" type="number" name="width_val" id="width_val" style="width:50px;" value="100" min="10" max="100" step="5"><br>
                                <input class="margin_top blue padding" type="submit" name="file_submit" value="Add selected element">
                            </form>
                        </div>
                        <button class="green padding" onclick="getHtml(<?= $_GET['user']?>)">Save changes</button>
                    </div>
                    <?php
                }
            }
        }catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>

    </main>
    
    <footer></footer>
