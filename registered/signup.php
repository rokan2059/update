<?php
// filepath: c:\xampp\htdocs\mylibrary\registered\signup.php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
}
?>
<?php
include("connection.php");

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpass']);
    $security_question_1 = mysqli_real_escape_string($conn, $_POST['security_question_1']);
    $security_answer_1 = mysqli_real_escape_string($conn, $_POST['security_answer_1']);
    $security_question_2 = mysqli_real_escape_string($conn, $_POST['security_question_2']);
    $security_answer_2 = mysqli_real_escape_string($conn, $_POST['security_answer_2']);

    // Check if passwords match
    if ($password !== $cpassword) {
        echo '<script>
                alert("Passwords do not match!");
                window.location.href = "signup.php";
              </script>';
        exit();
    }

    // Check if password meets the complexity requirements
    if (!preg_match('/[A-Z]/', $password) || // At least one uppercase letter
        !preg_match('/[a-z]/', $password) || // At least one lowercase letter
        !preg_match('/[0-9]/', $password) || // At least one number
        !preg_match('/[\W]/', $password)) {  // At least one special character
        echo '<script>
                alert("Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character!");
                window.location.href = "signup.php";
              </script>';
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO signup (username, password, security_question_1, security_answer_1, security_question_2, security_answer_2) 
            VALUES ('$username', '$hash', '$security_question_1', '$security_answer_1', '$security_question_2', '$security_answer_2')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: login.php");
    } else {
        echo '<script>
                alert("Signup failed. Please try again.");
                window.location.href = "signup.php";
              </script>';
    }
}
?>
<?php
include("navbar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Signup Form</h1>
        <div class="row justify-content-center ">
            <div class="col-md-6 col-lg-4">
                <form action="signup.php" method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="user" class="form-label">Enter Username:</label>
                        <input type="text" class="form-control" id="user" name="user" required>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Create Password:</label>
                        <input type="password" class="form-control" id="pass" name="pass" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpass" class="form-label">Retype Password:</label>
                        <input type="password" class="form-control" id="cpass" name="cpass" required>
                    </div>
                    <div class="mb-3">
                        <label for="security_question_1" class="form-label">Security Question 1:</label>
                        <select class="form-select" id="security_question_1" name="security_question_1" required>
                            <option value="What is your pet's name?">What is your pet's name?</option>
                            <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                            <option value="What is your favorite book?">What is your favorite book?</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="security_answer_1" class="form-label">Answer 1:</label>
                        <input type="text" class="form-control" id="security_answer_1" name="security_answer_1" required>
                    </div>
                    <div class="mb-3">
                        <label for="security_question_2" class="form-label">Security Question 2:</label>
                        <select class="form-select" id="security_question_2" name="security_question_2" required>
                            <option value="What is your favorite color?">What is your favorite color?</option>
                            <option value="What is your first school?">What is your first school?</option>
                            <option value="What is your dream job?">What is your dream job?</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="security_answer_2" class="form-label">Answer 2:</label>
                        <input type="text" class="form-control" id="security_answer_2" name="security_answer_2" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>