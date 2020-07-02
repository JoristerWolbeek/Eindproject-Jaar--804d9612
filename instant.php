<?php
session_start();
$dsn = "mysql:host=localhost;dbname=cv_maker";
$user = "root";
$passwd = "";
$ordered = array("olist1", "olist2", "olist3", "olist4", "olist5");
$unordered = array("ulist1", "ulist2", "ulist3", "ulist4", "ulist5", "ulist6", "ulist7", "ulist8", "ulist9", "ulist10");
$work = array("name_work1", "name_work2", "name_work3");
$dates = array("date_work1", "date_work2", "date_work3", "date_work4", "date_work5", "date_work6");
$pdo = new PDO($dsn, $user, $passwd);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$check_login = $pdo->prepare("SELECT token FROM users WHERE id=?");
if (isset($_COOKIE['loggedInUser'])) {
    $check_login->execute([$_COOKIE['loggedInUser']]);
} else {
    $check_login->execute([$_POST['selected_user']]);
}
$check_login = $check_login->fetch(PDO::FETCH_ASSOC);
if (isset($_SESSION['token']) && $check_login['token'] == $_SESSION['token']) {
    if (isset($_POST['input_name'])) {
        try {
            if (in_array($_POST['input_name'], $dates)) {
                $profile_retrieving = $pdo->prepare("SELECT id FROM profile_pages WHERE user_id = ?");
                $profile_retrieving->execute([$_COOKIE['loggedInUser']]);
                $profile_id_filling = $profile_retrieving->fetch(PDO::FETCH_ASSOC);
                $method = $pdo->prepare("SELECT element_id FROM profile_data_entries WHERE element_id=? AND profile_id=?");
                $method->execute([$_POST['input_name'], $profile_id_filling['id']]);
                $check_method = $method->fetch(PDO::FETCH_ASSOC);
                if ($check_method['element_id'] == $_POST['input_name']) {
                    $filling = $pdo->prepare("UPDATE profile_data_entries SET date=? WHERE element_id=? AND profile_id=?");
                    $filling->execute([$_POST['input_value'], $_POST['input_name'], $profile_id_filling['id']]);
                } else {
                    $profile_retrieving = $pdo->prepare("SELECT id FROM profile_pages WHERE user_id = ?");
                    $profile_retrieving->execute([$_COOKIE['loggedInUser']]);
                    $profile_id_filling = $profile_retrieving->fetch(PDO::FETCH_ASSOC);
                    $filling = $pdo->prepare("INSERT INTO profile_data_entries (profile_id, element_id, date) VALUES (?,?,?)");
                    $filling->execute([$profile_id_filling['id'], $_POST['input_name'], $_POST['input_value']]);
                }
            }
            if (in_array($_POST['input_name'], $unordered) || in_array($_POST['input_name'], $ordered) || in_array($_POST['input_name'], $work)) {
                $profile_retrieving = $pdo->prepare("SELECT id FROM profile_pages WHERE user_id = ?");
                $profile_retrieving->execute([$_COOKIE['loggedInUser']]);
                $profile_id_filling = $profile_retrieving->fetch(PDO::FETCH_ASSOC);
                $method = $pdo->prepare("SELECT element_id FROM profile_data_entries WHERE element_id=? AND profile_id=?");
                $method->execute([$_POST['input_name'], $profile_id_filling['id']]);
                $check_method = $method->fetch(PDO::FETCH_ASSOC);
                if ($check_method['element_id'] == $_POST['input_name']) {
                    $filling = $pdo->prepare("UPDATE profile_data_entries SET data=? WHERE element_id=? AND profile_id=?");
                    $filling->execute([$_POST['input_value'], $_POST['input_name'], $profile_id_filling['id']]);
                } else {
                    $profile_retrieving = $pdo->prepare("SELECT id FROM profile_pages WHERE user_id = ?");
                    $profile_retrieving->execute([$_COOKIE['loggedInUser']]);
                    $profile_id_filling = $profile_retrieving->fetch(PDO::FETCH_ASSOC);
                    $filling = $pdo->prepare("INSERT INTO profile_data_entries (profile_id, element_id, data) VALUES (?,?,?)");
                    $filling->execute([$profile_id_filling['id'], $_POST['input_name'], $_POST['input_value']]);
                }
            }
            $filling = $pdo->prepare("UPDATE profile_pages SET " . $_POST['input_name'] . "=? WHERE user_id=?");
            if($_POST['input_value'] != "" && !in_array($_POST['input_name'], $unordered) && !in_array($_POST['input_name'], $ordered) && !in_array($_POST['input_name'], $work) && !in_array($_POST['input_name'], $dates)) {
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
            $fetched_entries = fetchThatData($retrieved['id'], $pdo);
            foreach ($fetched_entries as $x => $array) {
                if (in_array($array['element_id'], $unordered) || in_array($array['element_id'], $ordered) || in_array($array['element_id'], $work)) {
                    $data = array_push_assoc($data, $array['element_id'], $array['data']);
                } else if (in_array($array['element_id'], $dates)) {
                    $data = array_push_assoc($data, $array['element_id'], $array['date']);
                }
                
            }
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

function fetchThatData($profile_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM profile_data_entries WHERE profile_id=?");
    $stmt->execute([$profile_id]);
    $profile_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $profile_data;
}
?>