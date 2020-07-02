<?php
session_start();
function loggingIn($User) 
{
    $dsn = "mysql:host=localhost;dbname=cv_maker";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($User)) {
        $check_attempt = $pdo->prepare("SELECT * FROM users WHERE username=?");
        $check_attempt->execute([$User]);
        $check_attempt = $check_attempt->fetch();
        $_SESSION['token'] = $check_attempt['token'];
        if (!$check_attempt) {
            throw new Exception("This username and password combination is not registered.");
        } else if (!password_verify($_POST['password'], $check_attempt['password'])) {
            throw new Exception("This username and password combination is not registered.");
        } else {
            setcookie('loggedInUser', $check_attempt['id'], time() + (86400));
            header('Location: profile.php');
        }
    }
}
?>

<!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="Css/style.css"></link>
    </head>
        <header>   
            
        </header>
    <body>
        <div class="loginPostbody">
            <img src="Css/images/logo.svg" class="bitLogo">
                <div class="loginContainer">
                    <h1>Login</h1>
                    <form method="post">
                        <input class="field"  type="text" name="username" placeholder="Username" autocomplete="off">
                        <input class="field" type="password" name="password" placeholder="Password">
                        <input class="button" type="submit" name="submit" value="Login">
                    </form>
                    <h5><a href="register.php">Not registered?</a></h5>
                    <h5><a href="send_email.php">Forgot password?</a></h5>
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

<?php
try {
    if (isset($_POST['username'])) {
        loggingIn($_POST['username']);
    }
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>