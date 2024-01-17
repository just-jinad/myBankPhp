<?php
// require('connection.php');
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Content-Type: application/json');
// $input = json_decode(file_get_contents('php://input'),true);
// echo json_encode( $input['userPin'] );

//     $userName=$input['userName'];
//     $userPhone=$input['userPhone'];
//     $userAdd=$input['userAdd'];
//     $userGender=$input['userGender'];
//     $userEmail=$input['userEmail'];
//     $userDOB=$input['userDOB'];
//     $userPin=$input['userPin'];


// $query  = "SELECT * FROM userbio WHERE userEmail=? OR userPhone=?";
// $emailandPhoneverify=$dbconnection->prepare($query);
// $emailandPhoneverify->bind_param('ss', $userEmail, $userPhone);
// $emailandPhoneverify->execute();
// $res=$emailandPhoneverify->get_result();



// if ($res->num_rows > 0) {
//     $existingUser = $res->fetch_assoc();


//     if ($existingUser['userEmail'] == $userEmail && $existingUser['userPhone'] == $userPhone) {
       
//         echo json_encode(array("status" => false, "message" => "Email and Phone number already exist!"));
//     } elseif ($existingUser['userEmail'] == $userEmail) {
     
//         echo json_encode(array("status" => false, "message" => "Email already exists!"));
//     } elseif ($existingUser['userPhone'] == $userPhone) {
       
//         echo json_encode(array("status" => false, "message" => "Phone number already exists!"));
//     }

// } else {
//     $hashedpassword = password_hash($input['userPin'], PASSWORD_DEFAULT);

//     $dbquery = "INSERT INTO userbio( userName, userEmail, userPhone, userGender, userDOB, userAdd, userPin) VALUES (?,?,?,?,?,?,?)";
//     $prepare = $dbconnection->prepare($dbquery);
//     $bind = $prepare->bind_param("ssssiss", $userName, $userEmail, $userPhone,  $userGender, $userDOB, $userAdd, $hashedpassword); 

//     $prepare->execute();

//     if ($prepare->affected_rows > 0) {
//         echo json_encode(array("status" => true, "message" => "Registration successful"));
//     } else {
//         echo json_encode(array("status" => false, "message"=>"Unsuccessful registration!"));
//     }
// };

?>


<?php
require('connection.php');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Assuming 'connection.php' includes your $dbconnection

$input = json_decode(file_get_contents('php://input'), true);

// Check if required fields are present
if (!isset($input['userName'], $input['userPhone'], $input['userAdd'], $input['userGender'], $input['userEmail'], $input['userDOB'], $input['userPin'])) {
    echo json_encode(array("status" => false, "message" => "Incomplete data received"));
    exit();
}

$userName = $input['userName'];
$userPhone = $input['userPhone'];
$userAdd = $input['userAdd'];
$userGender = $input['userGender'];
$userEmail = $input['userEmail'];
$userDOB = $input['userDOB'];
$userPin = $input['userPin'];
// $userAmount=$input[1000];

$query = "SELECT * FROM userbio WHERE userEmail=? OR userPhone=?";
$emailAndPhoneVerify = $dbconnection->prepare($query);
$emailAndPhoneVerify->bind_param('ss', $userEmail, $userPhone);
$emailAndPhoneVerify->execute();
$res = $emailAndPhoneVerify->get_result();

if ($res->num_rows > 0) {
    $existingUser = $res->fetch_assoc();

    if ($existingUser['userEmail'] == $userEmail && $existingUser['userPhone'] == $userPhone) {
        echo json_encode(array("status" => false, "message" => "Email and Phone number already exist!"));
    } elseif ($existingUser['userEmail'] == $userEmail) {
        echo json_encode(array("status" => false, "message" => "Email already exists!"));
    } elseif ($existingUser['userPhone'] == $userPhone) {
        echo json_encode(array("status" => false, "message" => "Phone number already exists!"));
    }
} else {
    $initialAmount = 3000;

    // Hash the user's PIN
    $hashedPassword = password_hash($userPin, PASSWORD_DEFAULT);

    // Modify the query to include the initial amount
    $dbquery = "INSERT INTO userbio (userName, userEmail, userPhone, userGender, userDOB, userAdd, userAmount, userPin) VALUES (?,?,?,?,?,?,?,?)";
    $prepare = $dbconnection->prepare($dbquery);
    $prepare->bind_param("ssssssis", $userName, $userEmail, $userPhone, $userGender, $userDOB, $userAdd, $initialAmount, $hashedPassword);

    if ($prepare->execute()) {
        echo json_encode(array("status" => true, "message" => "Registration successful"));
    } else {
        echo json_encode(array("status" => false, "message" => "Unsuccessful registration!"));
    }

}

?>
