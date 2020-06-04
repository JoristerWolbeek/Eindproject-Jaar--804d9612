<?php
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$targetDir = "uploads/";
$check = $pdo->prepare("SELECT user_id FROM images WHERE user_id=?");
$check->execute([$_COOKIE['loggedInUser']]);
if ($check->fetch()) {
    $file_name = $pdo->prepare("SELECT file_name FROM images WHERE user_id=?");
    $file_name->execute([$_COOKIE['loggedInUser']]);
    $file_name = $file_name->fetch();
    unlink($targetDir . $file_name['file_name']);
    $delete_old_pic = $pdo->prepare("DELETE FROM images WHERE user_id=?");
    $delete_old_pic->execute([$_COOKIE['loggedInUser']]);
    
}
$statusMsg = '';

$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if (isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            $insert = $pdo->query("INSERT into images (file_name, uploaded_on, user_id) VALUES ('".$fileName."', NOW(), '".$_COOKIE['loggedInUser']."')");
            if ($insert) {
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            } else {
                $statusMsg = "File upload failed, please try again.";
            } 
        } else {
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    } else {
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
} else {
    $statusMsg = 'Please select a file to upload.';
}

echo $statusMsg;
sleep(2);
header("Location: profile.php");

?>
