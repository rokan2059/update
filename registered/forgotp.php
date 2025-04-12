<?php
// filepath: c:\xampp\htdocs\mylibrary\registered\forgotp.php
include("connection.php");

$security_question_1 = null;
$security_question_2 = null;
$error = null;

if (isset($_POST['check_username'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $sql = "SELECT security_question_1, security_question_2 FROM signup WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $security_question_1 = $row['security_question_1'];
        $security_question_2 = $row['security_question_2'];
    } else {
        $error = "Username not found.";
    }
}

if (isset($_POST['reset_password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $answer_1 = mysqli_real_escape_string($conn, $_POST['answer_1']);
    $answer_2 = mysqli_real_escape_string($conn, $_POST['answer_2']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    $sql = "SELECT * FROM signup WHERE username = '$username' AND security_answer_1 = '$answer_1' AND security_answer_2 = '$answer_2'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE signup SET password = '$hash' WHERE username = '$username'";
        if (mysqli_query($conn, $update_sql)) {
            echo '<script>
                    alert("Password reset successfully.");
                    window.location.href = "login.php";
                  </script>';
        } else {
            $error = "Failed to reset password.";
        }
    } else {
        $error = "Incorrect answers to security questions.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Forgot Password</h1>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="forgotp.php" method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="username" class="form-label">Enter Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                    </div>
                    <?php if ($security_question_1 && $security_question_2): ?>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($security_question_1) ?></label>
                            <input type="text" class="form-control" name="answer_1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($security_question_2) ?></label>
                            <input type="text" class="form-control" name="answer_2" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <button type="submit" name="reset_password" class="btn btn-success w-100">Reset Password</button>
                    <?php else: ?>
                        <button type="submit" name="check_username" class="btn btn-primary w-100">Check Username</button>
                    <?php endif; ?>
                </form>
                <?php if ($error): ?>
                    <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>