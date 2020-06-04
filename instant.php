<?php
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
if (isset($_POST['input_name'])) {
    $filling = $pdo->prepare("UPDATE profile_pages SET " . $_POST['input_name'] . "=? WHERE user_id=?");
    try {
        // if ($_POST['input_value'] == "") {
        //     // else {
        //     //     $default = $_POST['input_name'] . " here";
        //     // }
        //     // $filling->execute([$default, $_COOKIE['loggedInUser']]);
        // } else{
            $filling->execute([$_POST['input_value'], $_COOKIE['loggedInUser']]);
        // }
    } catch (PDOException $e) {
        error_log($e->getMessage(),3,"error.log");
    }
    $retrieving = $pdo->prepare("SELECT * FROM profile_pages WHERE user_id=?");
    try {
        $retrieving->execute([$_COOKIE['loggedInUser']]);
        $retrieved = $retrieving->fetch(PDO::FETCH_ASSOC);
        $data = array_push_assoc($retrieved, 'input_name', $_POST['input_name']);
        echo json_encode($data);
    } catch (PDOException $e) {
        error_log($e->getMessage(),3,"error.log");
    }
}

function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
 }

?>