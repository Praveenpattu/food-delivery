<?php
include '../includes/connect.php';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM users WHERE username = ? AND password = ? AND role = 'Administrator' AND deleted = 0";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $success = true;
        $user_id = $row['id'];
        $name = $row['name'];
        $role = $row['role'];
    } else {
        // If not an administrator, check for customers
        $query = "SELECT * FROM users WHERE username = ? AND password = ? AND role = 'Customer' AND deleted = 0";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $success = true;
            $user_id = $row['id'];
            $name = $row['name'];
            $role = $row['role'];
        }
    }

    if ($success) {
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;

        if ($role === 'Administrator') {
            $_SESSION['admin_sid'] = session_id();
            header("location: ../admin-page.php");
        } else {
            $_SESSION['customer_sid'] = session_id();
            header("location: ../index.php");
        }
    } else {
        header("location: ../login.php");
    }
} else {
    // Handle the case when the form is not submitted
    header("location: ../login.php");
}
?>
