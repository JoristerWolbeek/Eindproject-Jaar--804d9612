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
            <h2 id="resetPhpTitel">Reset Password</h2>
        </div>
        <div class="resetContainer" >
           <form class="positionResetContainer" id="register" method="post">
                <input class="field" type="password" name="password" placeholder="New password" onkeyup="checkPass()">
                <input class="field" type="password" name="passwordCheck" placeholder="Confirm new password" onkeyup="checkPass()">
                <input class="button" class="disable" type="submit" name="submitpassword" value="Reset">
            </form>
        </div>
    </main>
    <footer>
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