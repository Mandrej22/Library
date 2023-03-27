<?php
// define variables and set to empty values
$username = $password = "";
$invalid_login = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    // check if user exists in JSON file
    $users = json_decode(file_get_contents('users.json'), true);
    if ($users) {
        foreach ($users as $user) {
            if ($user['username'] == $username && password_verify($password, $user['password'])) {
                // user found and password correct
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['name'] = $user['name'];
                header("Location: library.php");
                exit;
            }
        }
    }
    // user not found or password incorrect
    $invalid_login = true;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>