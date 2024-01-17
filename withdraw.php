<?php
require("connection.php");
session_start();
if (!isset($_SESSION['userEmail'])) {
    header("Location: login.php");
    exit();
}
// Check if the user is logged in
if (!isset($_SESSION['userEmail'])) {
    echo json_encode(array("status" => false, "message" => "User not logged in"));
    exit();
}


// Use $_SESSION['userEmail'] to get the logged-in user's email
$userEmail = $_SESSION['userEmail'];

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['amount'], $input['userPin'])) {
    echo json_encode(array("status" => false, "message" => "Incomplete data received"));
    exit();
}

$withdrawAmount = $input['amount'];
$userPin = $input['userPin'];

// Perform validation, authorization, and any other necessary checks here

// Check the user's balance and ensure they have enough funds
$balanceQuery = "SELECT userAmount, userPin FROM userbio WHERE userEmail=?";
$balanceStmt = $dbconnection->prepare($balanceQuery);
$balanceStmt->bind_param('s', $userEmail);  // Use $userEmail from the session
$balanceStmt->execute();
$balanceResult = $balanceStmt->get_result();


if ($balanceResult->num_rows > 0) {
    $user = $balanceResult->fetch_assoc();
    $currentBalance = $user['userAmount'];
    $hashedPin = $user['userPin'];

 
    if (password_verify($userPin, $hashedPin)) {
        if ($currentBalance >= $withdrawAmount) {
            // Update the user's balance after withdrawal
            $newBalance = $currentBalance - $withdrawAmount;
            $updateQuery = "UPDATE userbio SET userAmount=? WHERE userEmail=?";
            $updateStmt = $dbconnection->prepare($updateQuery);
            $updateStmt->bind_param('is', $newBalance, $userEmail);
            $updateStmt->execute();

            echo json_encode(array("status" => true, "message" => "Withdrawal successful", "newBalance" => $newBalance));
        } else {
            echo json_encode(array("status" => false, "message" => "Insufficient funds"));
        }
    } else {
        echo json_encode(array("status" => false, "message" => "Incorrect PIN"));
    }
} else {
    echo json_encode(array("status" => false, "message" => "User not found"));
}

?>
