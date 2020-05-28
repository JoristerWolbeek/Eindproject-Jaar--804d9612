<?php
function resettingPassword() 
{   
    if (isset($_GET['emailreset'])) {
       $email = $_GET['emailreset']; 
    }
    $dsn = "mysql:host=localhost;dbname=cv_maker";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['submitpassword'])) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $check_attempt = $pdo->prepare("UPDATE users SET password = ? WHERE email=?;");
            $check_attempt->execute([$hash, $_GET['emailreset']]);
            header('Location: login.php');
        } else if (!isset($_POST['submitpassword'])) {
            throw new Exception("Something went wrong with the communication");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Css/style.css">
    <script src="JS/script.js"></script>
</head>
<body>
    <main>
        <div id="loginPostBody">
            <div class="resetContainer">
            <h2>Reset Password</h2>
            <form id="register" method="post">
                <input class="field" type="password" name="password" placeholder="New password" onkeyup="checkPass()">
                <input class="field" type="password" name="passwordCheck" placeholder="Confirm new password" onkeyup="checkPass()">
                <input class="button" class="disable" type="submit" name="submitpassword" value="Reset password">
            </form>
            </div>
        </div>
    </main>
    <footer>
            <div class="footerContainer">
                <h4> Code Monkey Incorporated© CV Maker</h4>
                <h4 href="About-us"><a href="https://www.notion.so/bitacademy/2020-Code-Monkey-Incorporated-7c231c7df5f84c4e888f6c85849e0a07">About us</a></h4>
            </div>
        </footer>
    
</body>
</html>

<?php
try {
    resettingPassword();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>