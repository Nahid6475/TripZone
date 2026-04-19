<?php
require_once 'connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (strlen($password) < 4) {
        $error = 'Password must be at least 4 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already registered. Please login.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! Please login.';
                header("refresh:2; url=login.php");
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TripZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0F4C5C, #2A9D8F); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .register-container { background: white; border-radius: 2rem; padding: 2.5rem; width: 100%; max-width: 500px; box-shadow: 0 30px 50px rgba(0,0,0,0.2); }
        .logo { text-align: center; font-size: 2rem; font-weight: 800; background: linear-gradient(130deg, #0F4C5C, #2A9D8F); -webkit-background-clip: text; background-clip: text; color: transparent; margin-bottom: 1.5rem; }
        .logo span { color: #E76F51; }
        h2 { text-align: center; color: #0F4C5C; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1C2E2A; }
        input { width: 100%; padding: 0.8rem 1rem; border: 2px solid #EAE5DC; border-radius: 1rem; font-size: 1rem; }
        input:focus { border-color: #2A9D8F; outline: none; }
        button { width: 100%; background: #E76F51; color: white; border: none; padding: 0.8rem; border-radius: 50px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.3s; }
        button:hover { background: #CF5A3C; transform: translateY(-2px); }
        .error { background: #f8d7da; color: #721c24; padding: 0.8rem; border-radius: 1rem; margin-bottom: 1rem; text-align: center; }
        .success { background: #d4edda; color: #155724; padding: 0.8rem; border-radius: 1rem; margin-bottom: 1rem; text-align: center; }
        .login-link { text-align: center; margin-top: 1.5rem; }
        .login-link a { color: #2A9D8F; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">TripZone<span>.</span></div>
        <h2>Create an Account</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="name" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required placeholder="Create a password (min 4 chars)">
            </div>
            <div class="form-group">
                <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="Confirm your password">
            </div>
            <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>