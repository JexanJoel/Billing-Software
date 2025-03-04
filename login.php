<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'billing_software';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Fetch user from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password (hashed)
            if (hash('sha256', $password) === $user['password']) {
                // Set session variables
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'owner') {
                    header('Location: owner_dashboard.php');
                    exit();
                } else {
                    header('Location: user_dashboard.php');
                    exit();
                }
            } else {
                $error_message = "Invalid password!";
            }
        } else {
            $error_message = "User not found!";
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        /* Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #2575fc;
        }

        /* Input Fields */
        .login-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .login-container input:focus {
            border-color: #2575fc;
            outline: none;
        }

        /* Button */
        .login-container button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-container button:hover {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
        }

        /* Error Message */
        .error-message {
            color: #ff4d4d;
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* Footer Link */
        .login-container{
            margin-top: 15px;
            font-size: 14px;
            color: #2575fc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-container:hover {
            color: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>