<?php
session_start();

// Hardcoded admin credentials (you can change these)
$admin_username = 'admin';
$admin_password = 'admin123';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = 'Invalid username or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TripZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0F4C5C, #2A9D8F);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 2rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 30px 50px rgba(0,0,0,0.2);
        }
        .logo {
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(130deg, #0F4C5C, #2A9D8F);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }
        .logo span { color: #E76F51; }
        .admin-badge {
            text-align: center;
            background: #E76F51;
            color: white;
            padding: 5px 10px;
            border-radius: 50px;
            display: inline-block;
            width: auto;
            margin: 0 auto 1.5rem;
            font-size: 0.8rem;
        }
        h2 { text-align: center; color: #0F4C5C; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1C2E2A; }
        input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #EAE5DC;
            border-radius: 1rem;
            font-size: 1rem;
        }
        input:focus { border-color: #2A9D8F; outline: none; }
        button {
            width: 100%;
            background: #E76F51;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #CF5A3C; transform: translateY(-2px); }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 0.8rem;
            border-radius: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: #2A9D8F;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">TripZone<span>.</span></div>
        <div style="text-align: center;">
            <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin Panel</span>
        </div>
        <h2>Admin Login</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" required placeholder="Enter admin username">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required placeholder="Enter admin password">
            </div>
            <button type="submit"><i class="fas fa-sign-in-alt"></i> Login to Admin Panel</button>
        </form>
        
        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>
</body>
</html>