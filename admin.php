<?php
session_start();
include 'db.php'; // Koneksi ke database

// Cek apakah admin sudah login, jika tidak redirect ke halaman login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login-admin.php');
    exit();
}

// Ambil data admin berdasarkan session
$admin_id = $_SESSION['admin_id'];

try {
    // Ambil data admin berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();

    if (!$admin) {
        echo "Data admin tidak ditemukan!";
        exit();
    }

    // Proses untuk menambah pegawai baru
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_pegawai'])) {
        $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
        $posisi = isset($_POST['posisi']) ? $_POST['posisi'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

        // Pastikan data tidak kosong
        if (!empty($nama) && !empty($posisi) && !empty($email) && !empty($password)) {
            // Memasukkan data pegawai baru ke dalam tabel
            $stmt = $pdo->prepare("INSERT INTO pegawai (nama, posisi, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama, $posisi, $email, $password]);

            echo '<script>alert("Pegawai berhasil ditambahkan!"); window.location.href="admin.php";</script>';
        } else {
            echo '<script>alert("Harap isi semua field dengan benar!");</script>';
        }
    }

    // Proses untuk menghapus pegawai
    if (isset($_GET['delete_id'])) {
        $pegawai_id = $_GET['delete_id'];

        // Pastikan pegawai yang akan dihapus ada di database
        $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE id = ?");
        $stmt->execute([$pegawai_id]);
        $pegawai = $stmt->fetch();

        if ($pegawai) {
            // Menghapus data pegawai
            $stmt = $pdo->prepare("DELETE FROM pegawai WHERE id = ?");
            $stmt->execute([$pegawai_id]);
            echo '<script>alert("Pegawai berhasil dihapus!"); window.location.href="admin.php";</script>';
        } else {
            echo '<script>alert("Pegawai tidak ditemukan!"); window.location.href="admin.php";</script>';
        }
    }

    // Ambil daftar pegawai dari database
    $stmt = $pdo->prepare("SELECT * FROM pegawai");
    $stmt->execute();
    $pegawais = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PT. Sumber Ganda Mekar</title>
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
            padding-top: 70px; /* Adjust this value to match the header height */
        }

        header {
            background: var(--light-bg);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed; /* Fixes the header at the top */
            top: 0;
            left: 0;
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

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: var(--light-bg);
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(108, 99, 255, 0.1);
        }

        .box {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .header-section {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px;
        }

        .header-section h2 {
            margin-bottom: 10px;
            font-size: 2rem;
        }

        /* Header Styles */
header {
    background: var(--light-bg);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    transition: background 0.3s ease;
}
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
    padding: 0 20px;
}
.logo h1 {
    font-size: 1.8rem;
    color: var(--primary-color);
    transition: color var(--transition-speed);
}
.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
}
.nav-links li a {
    text-decoration: none;
    color: var(--text-color);
    font-weight: 600;
    transition: color var(--transition-speed);
    display: flex;
    align-items: center;
    gap: 10px;
}
.nav-links li a:hover {
    color: var(--primary-color);
}

        .add-pegawai-form {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .submit-button {
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-button:hover {
            background-color: var(--secondary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        .delete-btn {
            background-color: var(--secondary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: red;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <h1>PT. Sumber Ganda Mekar</h1>
        </div>
        <ul class="nav-links">
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <div class="box">
        <div class="header-section">
            <h2>Dashboard Admin</h2>
            <p>Selamat datang, <?php echo htmlspecialchars($admin['username']); ?>!</p>
        </div>

        <h3>Tambah Pegawai Baru</h3>
        <form class="add-pegawai-form" action="admin.php" method="POST">
            <div class="form-group">
                <label for="nama">Nama Pegawai</label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama pegawai">
            </div>
            <div class="form-group">
                <label for="posisi">Posisi</label>
                <input type="text" id="posisi" name="posisi" required placeholder="Masukkan posisi pegawai">
            </div>
            <div class="form-group">
                <label for="email">Email Pegawai</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email pegawai">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password pegawai">
            </div>
            <button type="submit" name="add_pegawai" class="submit-button">Tambah Pegawai</button>
        </form>

        <h3>Daftar Pegawai</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Posisi</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pegawais as $pegawai): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pegawai['nama']); ?></td>
                        <td><?php echo htmlspecialchars($pegawai['posisi']); ?></td>
                        <td><?php echo htmlspecialchars($pegawai['email']); ?></td>
                        <td>
                            <a href="edit-pegawai.php?id=<?php echo $pegawai['id']; ?>" class="submit-button">Edit</a>
                            <a href="?delete_id=<?php echo $pegawai['id']; ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus pegawai ini?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
