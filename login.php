<?php
require_once 'connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password';
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: index.php");
                exit();
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'User not found. Please register first.';
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
    <title>Login - TripZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #0F4C5C, #2A9D8F); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .login-container { background: white; border-radius: 2rem; padding: 2.5rem; width: 100%; max-width: 450px; box-shadow: 0 30px 50px rgba(0,0,0,0.2); }
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
        .register-link { text-align: center; margin-top: 1.5rem; }
        .register-link a { color: #2A9D8F; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">TripZone<span>.</span></div>
        <h2>Login to Your Account</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit"><i class="fas fa-key"></i> Login</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>