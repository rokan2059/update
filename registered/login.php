<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
}
?>
<?php
$login = false;
include('connection.php');
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $captcha = mysqli_real_escape_string($conn, $_POST['captcha']);

    // Validate CAPTCHA
    if ($captcha !== $_SESSION['captcha']) {
        echo '<script>
                alert("CAPTCHA validation failed");
                window.location.href = "login.php";
              </script>';
        exit();
    }

    // Validate username and password
    $sql = "SELECT * FROM signup WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    // Check if the query failed
    if (!$result) {
        die("Error in query: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($row) {
        // Validate password
        if (password_verify($password, $row["password"])) {
            $login = true;
            session_start();

            $_SESSION['username'] = $row['username'];
            $_SESSION['loggedin'] = true;
            header("Location: welcome.php");
        } else {
            echo '<script>
                    alert("Invalid password");
                    window.location.href = "login.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Login failed. Invalid username or password!!");
                window.location.href = "login.php";
              </script>';
    }
}
?>
<?php 
include("connection.php");
include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <br><br>
    <div id="form">
        <h1 id="heading">Login Form</h1>
        <form name="form" action="login.php" method="POST">
            <label>Enter Username: </label>
            <input type="text" id="user" name="user" required></br></br>

            <label>Password: </label>
            <input type="password" id="pass" name="pass" required></br></br>

            <label for="captcha">CAPTCHA: </label>
            <div class="d-flex">
                <img src="captcha.php" alt="CAPTCHA" class="me-2">
                <input type="text" class="form-control" id="captcha" name="captcha" required>
            </div></br></br>

            <input type="submit" id="btn" value="Login" name="submit"/>
        </form>
        <br>
        <!-- Forgot Password Button -->
        <div class="text-center">
            <button class="btn btn-outline-secondary" onclick="location.href='forgotp.php'" type="button">Forgot Password?</button>
        </div>
    </div>
</body>
</html>