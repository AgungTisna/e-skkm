<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='../index.php';</script>";
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data user dan nama ormawa
$query = "
    SELECT 
        u.nama,
        u.email,
        u.username,
        u.password,
        o.nama_ormawa
    FROM user u
    LEFT JOIN user_detail_ormawa o ON u.id_user = o.id_user
    WHERE u.id_user = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('Data pengguna tidak ditemukan!'); window.history.back();</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Ormawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .profile-container { display: flex; height: 100vh; }
        .profile-sidebar {
            background-color: #d3d3d3;
            width: 30%;
            padding: 20px;
            text-align: center;
        }
        .btn { margin-top: 80%; }
        .profile-content { flex: 1; padding: 40px; }
        .profile-info { font-size: 18px; margin-bottom: 10px; }
        .btn-custom {
            width: 100%;
            border: 1px solid #000;
            padding: 10px;
            border-radius: 10px;
            background-color: white;
            text-align: center;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-container input {
            width: 100%;
            padding-right: 40px;
        }
        .btn-eye {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
            padding-left: 10px;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-sidebar">
        <img src="../../asset/img/profile.png" height="38%" width="58%" />
        <br><br>
        <a href="../index.php"><button class="btn btn-outline-dark">Kembali</button></a>
    </div>

    <div class="profile-content">
        <div class="row">
            <div class="col-md-6">
                <p class="profile-info"><strong>Nama Ketua</strong><br><?= htmlspecialchars($user['nama']) ?></p>
                <p class="profile-info"><strong>Email</strong><br><?= htmlspecialchars($user['email']) ?></p>
                <p class="profile-info"><strong>Nama Ormawa</strong><br><?= htmlspecialchars($user['nama_ormawa'] ?? '-') ?></p>
            </div>
            <div class="col-md-6">
                <p class="profile-info"><strong>Username</strong><br><?= htmlspecialchars($user['username']) ?></p>
                <p class="profile-info"><strong>Password</strong><br>
                    <div class="password-container">
                        <input type="password" class="form-control" id="passwordField" value="<?= htmlspecialchars($user['password']) ?>" readonly>
                        <button class="btn-eye" type="button" onclick="togglePassword()">
                            <span id="eyeIcon">👁</span>
                        </button>
                    </div>
                </p>
            </div>
        </div>

        <a href="edit_profile_ormawa.php"><button class="btn-custom">Ubah Profil</button></a>
        <a href="ubah_username.php"><button class="btn-custom">Ubah Username</button></a>
        <a href="ubah_password.php"><button class="btn-custom">Ubah Password</button></a>
    </div>
</div>

<div class="footer">© Agung Tisna</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById("passwordField");
        const icon = document.getElementById("eyeIcon");
        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "🙈";
        } else {
            input.type = "password";
            icon.textContent = "👁";
        }
    }
</script>

</body>
</html>
