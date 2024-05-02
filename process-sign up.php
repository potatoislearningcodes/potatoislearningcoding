<?php

if (empty($_POST["name"])) {
    die("Name is rquired");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["Password"]) < 8){
    die("Password must be at least contain 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["Password"])) {
    die("Password must contain at least one letter");
} 

if ( ! preg_match("/[0-9]/i", $_POST["Password"])) {
    die("Password must contain at least one number");
} 

if ($_POST["Password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["Password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);

if ($stmt->execute()) {

    header("Location: signup-successful.html");
    exit;

} else {

    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}