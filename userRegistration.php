<?php
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validate form data
    $errors = array();

    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }

    // Check if user already exists
    $file = 'users.json';

    if (file_exists($file) && filesize($file) > 0) {
        $jsondata = file_get_contents($file);
        $arr_data = json_decode($jsondata, true);

        foreach ($arr_data as $user) {
            if ($user['username'] === $username || $user['email'] === $email) {
                $errors[] = 'User already exists with this username or email.';
                break;
            }
        }
    }

    if (empty($errors)) {
        // Store data in JSON file
        $arr_data = array();

        if (file_exists($file) && filesize($file) > 0) {
            $jsondata = file_get_contents($file);
            $arr_data = json_decode($jsondata, true);
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $formdata = array(
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email,
        );

        array_push($arr_data, $formdata);

        $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

        if (file_put_contents($file, $jsondata)) {
            echo 'Data successfully saved';
        } else {
            echo 'Error saving data';
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo '<p class="alert alert-danger">' . $error . '</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container p-5">
        <h2 class="text-center">Registration Form</h2>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>
            <input type="submit" name="submit" class="btn btn-primary mt-3" value="Submit">
        </form>
        <a class="btn btn-secondary" href="index.php">Go to login</a>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
    crossorigin="anonymous"></script>
</body>

</html>