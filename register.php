<?php
session_start();
function registeringUser() 
{
    $dsn = "mysql:host=localhost;dbname=cv_maker";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['username'])) {
    $email_check = $pdo->prepare("SELECT email FROM users WHERE email=?");
    $email_check->execute([$_POST['email']]);
    $reference_email = $email_check->fetch();
    $username_check = $pdo->prepare("SELECT username FROM users WHERE username=?");
    $username_check->execute([$_POST['username']]);
    $reference_username = $username_check->fetch();
        if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/", $_POST['password'])) {
            $simple_error = "<br><div id='simple_error' style='color: red;'>Password has to contain:<ul><li>one capital letter</li><li>one lowercase letter</li><li>one number</li><li>between 8 and 16 characters</li></ul></div>";
            return $simple_error;
        } if ($reference_email) {
            $simple_error = "<div id='simple_error' style='color: red;'>Email already registered!</div>";
            return $simple_error;
        } if ($reference_username) {
            $simple_error = "<div id='simple_error' style='color: red;'>Username already taken!</div>";
            return $simple_error;
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $token = openssl_random_pseudo_bytes(16);
            $token = bin2hex($token);
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $check_attempt = $pdo->prepare("INSERT INTO users (email, username, password, token) VALUES (?, ?, ?, ?)");
            $check_attempt->execute([$_POST['email'], $_POST['username'], $hash, $token]);
            //profile page for the user
            $stmt = $pdo->query('SELECT * FROM users WHERE username = "'.$_POST["username"].'"');
            while($row = $stmt->fetch()) {
                $stmt_profile = $pdo->prepare(
                    "INSERT INTO profile_pages (user_id)
                    VALUES (".$row["id"].")"
                );
                $stmt_profile->execute();
            }
            if ($_POST['submit'] == "Register") {
                echo '<script>window.location="index.php";</script>';
            }
        } else {
            throw new Exception("<div style='color: red;'>Invalid Email input</div>");
        }
    }
}
try {
    $simple_error = "<div id='simple_error' style='color: red;'></div>";
    $username_value = "";
    $email_value = "";
    if(isset($_POST['username'])) {
    $simple_error = registeringUser();
    if ($simple_error != "<div id='simple_error' style='color: red;'></div>") {
        $username_value = "value= '" . $_POST['username'] . "'";
        $email_value = "value= '" . $_POST['email'] . "'";
    }
}
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Css/style.css">
    <title>Register CV Maker</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="JS/script.js"></script>
</head>
<body>
    <header>
    </header>
    <main>
        <div class="loginPostbody">
            <form id="register" method="post">
                <div class="loginContainer">
                    <h1>Register</h1>
                    <input class="field" type="email" name="email" placeholder="Email" <?= $email_value?> autocomplete="off">
                    <input class="field" type="text" name="username" placeholder="Username" <?= $username_value?> autocomplete="off">
                    <input class="field" type="password" name="password" placeholder="Password" onchange="checkPass()">
                    <input class="field" type="password" name="passwordCheck" placeholder="Confirm password" onchange="checkPass()">
                    <input class="button" type="submit" name="submit" value="Register">
                    <?= $simple_error?>
                </div>
            </form>
        </div>
    </main>
        <div class="footerContainer">
            <h4> Code Monkey Incorporated© CV Maker</h4>
            <h4 href="About-us"><a href="https://www.notion.so/bitacademy/2020-Code-Monkey-Incorporated-7c231c7df5f84c4e888f6c85849e0a07">About us</a></h4>
        </div>
</body>
</html>
