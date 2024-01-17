<?php
require('connection.php');
session_start();

$input = json_decode(file_get_contents('php://input'), true);
// echo json_encode( $input['userPin'] );


if (!isset($input['userEmail'], $input['userPin'])) {
    echo json_encode(array("status" => false, "message" => "Incomplete data received"));
    exit();
}

$userEmail = $input['userEmail'];
$userPin = $input['userPin'];

$query = "SELECT * FROM userbio WHERE userEmail=?";
$emailVerify = $dbconnection->prepare($query);
$emailVerify->bind_param('s', $userEmail);
$emailVerify->execute();
$res = $emailVerify->get_result();

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    $hashedPassword = $user['userPin'];
    if (password_verify($userPin, $hashedPassword)) {
        echo json_encode(array("status" => true, "message" => "Login successful"));

     $_SESSION['userEmail'] = $userEmail;

    } else {
        echo json_encode(array("status" => false, "message" => "Incorrect PIN"));
    }
} else {
    echo json_encode(array("status" => false, "message" => "Email not found"));
}
?>
