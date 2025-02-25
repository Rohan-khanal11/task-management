<?php
include('connect.php');

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $first_name = trim($_POST['first-name']);
    $last_name = trim($_POST['last-name']);
    $username = $first_name . " " . $last_name;
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if fields are empty
    if (empty($first_name) || empty($last_name) || empty($email) || empty($dob) || empty($gender) || empty($password) || empty($confirm_password)) {
        echo '<script>alert("All fields are required!"); window.location.href = "register.html";</script>';
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format!"); window.location.href = "register.html";</script>';
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match!"); window.location.href = "register.html";</script>';
        exit();
    }

    // Secure password hashing
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists using prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<script>alert("Email already exists! Please log in."); window.location.href = "login.html";</script>';
    } else { 
        // Insert user data using prepared statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, dob, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $dob, $gender);
        
        if ($stmt->execute()) {
            echo '<script>alert("Registration Successful!"); window.location.href = "login.html";</script>';
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
