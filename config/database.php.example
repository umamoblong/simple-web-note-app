<?php
// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'simple_note_app');

// Membuat koneksi tanpa memilih database terlebih dahulu
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 1. Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (mysqli_query($conn, $sql)) {
    echo "Database berhasil dibuat/tersedia.<br>";
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Pilih database
mysqli_select_db($conn, DB_NAME);

// 2. Buat tabel notes jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS notes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($conn, $sql)) {
    die("Error creating table: " . mysqli_error($conn));
}

// 3. Cek apakah tabel notes sudah berisi data (untuk keperluan demo)
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM notes");
$row = mysqli_fetch_assoc($result);
if ($row['total'] == 0) {
    // Tambahkan data dummy untuk demo
    $dummy_notes = [
        ["Judul Pertama", "Ini adalah catatan pertama saya di Simple Note App. Aplikasi ini sangat membantu!"],
        ["Ide Proyek PHP", "Buat proyek e-commerce sederhana dengan PHP Native dan MySQL."],
        ["Belajar Laravel", "Minggu depan mulai belajar Laravel dari dokumentasi resmi."],
        ["Meeting Client", "Jangan lupa meeting dengan client jam 10:00 besok pagi."],
        ["Resep Masakan", "Bahan: Telur, Bawang, Cabe. Cara: Tumis bawang dan cabe, masukkan telur."]
    ];
    
    foreach ($dummy_notes as $note) {
        $title = mysqli_real_escape_string($conn, $note[0]);
        $content = mysqli_real_escape_string($conn, $note[1]);
        $sql = "INSERT INTO notes (title, content) VALUES ('$title', '$content')";
        mysqli_query($conn, $sql);
    }
    echo "Data dummy berhasil ditambahkan.<br>";
}
?>