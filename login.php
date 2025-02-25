<?php
session_start();
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        echo '<script>alert("Both email and password are required!"); window.location.href = "login.html";</script>';
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format!"); window.location.href = "login.html";</script>';
        exit();
    }

    // Prepare SQL statement to fetch user
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If user exists, verify the password
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $db_email, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            // Store user details in session
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $db_email;

            // Redirect to dashboard or front page
            echo '<script>alert("Login Successful!"); window.location.href = "frontpage.html";</script>';
            exit();
        } else {
            echo '<script>alert("Incorrect password!"); window.location.href = "login.html";</script>';
        }
    } else {
        echo '<script>alert("No account found with this email!"); window.location.href = "login.html";</script>';
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
