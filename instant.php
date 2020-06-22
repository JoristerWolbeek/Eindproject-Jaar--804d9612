<?php
session_start();
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";
$ordered = array("olist1", "olist2", "olist3", "olist4", "olist5");
$unordered = array("ulist1", "ulist2", "ulist3", "ulist4", "ulist5", "ulist6", "ulist7", "ulist8", "ulist9", "ulist10");
$pdo = new PDO($dsn, $user, $passwd);
$check_login = $pdo->prepare("SELECT token FROM users WHERE id=?");
if (isset($_COOKIE['loggedInUser'])) {
    $check_login->execute([$_COOKIE['loggedInUser']]);
} else {
    $check_login->execute([$_POST['selected_user']]);
}
$check_login = $check_login->fetch(PDO::FETCH_ASSOC);
if (isset($_SESSION['token']) && $check_login['token'] == $_SESSION['token']) {
    if (isset($_POST['input_name'])) {
        $filling = $pdo->prepare("UPDATE profile_pages SET " . $_POST['input_name'] . "=? WHERE user_id=?");
        try {
            if ($_POST['input_value'] == "" && !in_array($_POST['input_name'], $unordered) && !in_array($_POST['input_name'], $ordered)) {
                $default = $_POST['input_name'] . " here";
                $filling->execute([$default, $_COOKIE['loggedInUser']]);
            } else if ($_POST['input_value'] == "" && in_array($_POST['input_name'], $unordered)) {
                $default = "personalia here";
                $filling->execute([$default, $_COOKIE['loggedInUser']]);
            } else if ($_POST['input_value'] == "" && in_array($_POST['input_name'], $ordered)) {
                $default = "skills here";
                $filling->execute([$default, $_COOKIE['loggedInUser']]);
            } else {
                $filling->execute([$_POST['input_value'], $_COOKIE['loggedInUser']]);
            }
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
}

function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
}

?>