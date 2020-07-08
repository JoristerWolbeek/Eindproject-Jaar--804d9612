<?php
function sendingEmail() 
{
    if (isset($_POST['email']) && !isset($_POST['emailreset'])) {
        require("PHPMAIL/PHPMailer.php");
        require("PHPMAIL/SMTP.php");
        require("PHPMAIL/Exception.php");
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->Username = "codemonkeys.bit.inc@gmail.com";
        $mail->Password = "aqekqklecfxaewib";
        $mail->SetFrom("social-gamia@noreply.com");
        $mail->Subject = "You forgot your password!";
        $mail->Body = '<html>
        <head>
        <title>To reset your password press the button down below!</title>
        </head>
        <body>
            <h1>To reset your password press the button down below!</h1>
            <form action="http://localhost/Eindproject-Jaar--804d9612/reset.php" method="GET">
                <input type="hidden" name="emailreset" value="' . $_POST['email'] . '">
                <input type="submit" name="submitreset" value="Press me to reset!" />
            </form>
        </body>
        </html>';
        $mail->AddAddress($_POST['email']);

        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }
    }


?>

<!DOCTYPE html>
<html >
<head>
    <link rel="stylesheet" type="text/css" href="Css/style.css">
    <script src="JS/script.js"></script>
    <title>CV Maker | Password Recovery </title>
</head>
<header class="backLink">
    <div >
        <a class="backLink" href="index.php">Back</a>
    </div>
</header>
    <body>
        <div class="loginPostbody">

            <form class="emailContainer" id="register" method="post">

                <h1>Password recovery</h1>
                    <input class="field" type="email" name="email" placeholder="Email of your account">
                    <input class="button" type="submit" name="submitemail" value="Send recovery email">
                </form>
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
}
try {
    sendingEmail();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>