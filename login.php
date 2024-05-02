<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf ("SELECT * FROM user
                     WHERE email = '%s'",
                     $mysqli->real_escape_string($_POST["email"]));


    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if ($user) {

        if (password_verify($_POST["Password"], $user["password_hash"])) {

            session_start();

            $_SESSION["user_id"] = $user["id"];

            header("Location: index.php");
            exit;
        }
    }

    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <meta charset="UTF-8">
    <title>Log in</title>
    <link rel="stylesheet" href="Log in page style.css">
    <script src="https://kit.fontawesome.com/f3c1b54261.js" crossorigin="anonymous"></script>
</head>
<body>
<section class="login-box">
    <img src="images para sa log in page/logo.png" class="logo">
    <h2>Welcome back</h2>

    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>

    <form method="post">
        <div>
            <input type="email" placeholder="Email" id="email" name="email"
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        </div>
        
        <div>
            <input type="password" placeholder="Password" id="password" name="Password">
        </div>

        <button>Log in</button>
        <p>Dont have an acccount? <a href="Sign up page.html">Sign up</a></p>

</body>
</html>