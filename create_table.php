<?php
include "koneksi.php";

// Query untuk membuat tabel users jika belum ada
$createTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    level ENUM('siswa', 'guru', 'ortu') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($koneksi, $createTable)) {
    echo "Tabel users berhasil dibuat atau sudah ada!<br>";
} else {
    echo "Error creating table: " . mysqli_error($koneksi) . "<br>";
}

// Tampilkan struktur tabel
$showTable = "DESCRIBE users";
$result = mysqli_query($koneksi, $showTable);

if ($result) {
    echo "<h3>Struktur Tabel Users:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error showing table structure: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?> 