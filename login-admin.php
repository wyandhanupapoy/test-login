<?php
session_start();
include 'db.php'; // Pastikan file ini ada dan terhubung ke database

// Aktifkan error reporting untuk melihat detail kesalahan
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap data dari form
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    try {
        // Siapkan query untuk mengambil data admin berdasarkan username
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Jika username dan password cocok, buat session dan redirect ke halaman dashboard admin
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            echo '<script>
                alert("Login Admin Berhasil!");
                window.location.href = "admin.php"; // Ganti dengan halaman dashboard admin
            </script>';
            exit();
        } else {
            // Jika gagal login
            echo '<script>
                alert("Username atau Password salah!");
            </script>';
        }
    } catch(PDOException $e) {
        echo '<script>
            alert("Login Gagal: ' . $e->getMessage() . '");
        </script>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PT. Sumber Ganda Mekar</title>
    
    <!-- Link ke Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --background-color: #f0f2f5;
            --text-color: #333;
            --light-bg: #ffffff;
            --dark-bg: #1a1a1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            background-color: var(--background-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        header {
            background: var(--light-bg);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            z-index: 1000;
            transition: background 0.3s ease;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            padding: 0 20px;
        }

        .logo h1 {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 70px);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 20px;
            box-sizing: border-box;
            animation: fadeIn 1s ease-out;
        }

        .login-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            padding: 60px 40px;
            width: 100%;
            max-width: 450px;
            color: #fff;
        }

        .login-form h2 {
            text-align: center;
            color: var(--light-bg);
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--light-bg);
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease;
            font-size: 1rem;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .submit-button {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color var(--transition-speed), transform var(--transition-speed);
            font-weight: 600;
            font-size: 1rem;
        }

        .submit-button:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }

        .auth-switch {
            text-align: center;
            margin-top: 20px;
        }

        .auth-switch a {
            color: var(--light-bg);
            text-decoration: none;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        footer {
    background: var(--dark-bg);
    color: #fff;
    text-align: center;
    padding: 30px 20px;
    position: relative;
    border-top: 3px solid var(--primary-color);
}
footer p {
    font-size: 1rem;
}

    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>PT. Sumber Ganda Mekar</h1>
            </div>
        </nav>
    </header>

    <div class="login-container">
        <form class="login-form" action="login-admin.php" method="POST">
            <h2>Login Admin</h2>
            <div class="form-group">
                <label for="admin-username">Username Admin</label>
                <input type="text" id="admin-username" name="admin_username" required placeholder="Masukkan username admin">
            </div>
            <div class="form-group">
                <label for="admin-password">Kata Sandi</label>
                <input type="password" id="admin-password" name="admin_password" required placeholder="Masukkan kata sandi">
            </div>
            <button type="submit" class="submit-button">Login</button>
            <div class="auth-switch">
                <a href="login-pegawai.php">Kembali ke Login Pegawai</a>
            </div>
        </form>
    </div>
    <footer>
    <p>&copy; <?php echo date("Y"); ?> PT. Sumber Ganda Mekar. Semua Hak Dilindungi.</p>
</footer>
</body>
</html>
