<?php
header('Content-Type: application/json');

$filename = __DIR__ . '/kehadiran_data.json';
// Jika file tidak ada, buat file kosong
if (!file_exists($filename)) {
    file_put_contents($filename, json_encode([]));
}
// Baca file JSON, jika corrupt, tampilkan data kosong tanpa reset file
$json_raw = file_get_contents($filename);
if ($json_raw === false) {
    // Tidak bisa baca file
    $data = [];
} else {
    $data = json_decode($json_raw, true);
    if (!is_array($data)) {
        // Jika file corrupt, tampilkan data kosong, JANGAN reset file
        $data = [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;
    $username = isset($_GET['username']) ? $_GET['username'] : null;
    $result = [];
    foreach ($data as $row) {
        if ((is_null($bulan) || $row['bulan'] == $bulan) && (is_null($username) || $row['username'] == $username)) {
            $result[] = $row;
        }
    }
    echo json_encode(['status'=>'success','data'=>$result]);
    exit();
}

// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;
//     $username = isset($_GET['username']) ? $_GET['username'] : null;
//     $result = [];
//     foreach ($data as $row) {
//         if ((is_null($bulan) || $row['bulan'] == $bulan) && (is_null($username) || $row['username'] == $username)) {
//             $result[] = $row;
//         }
//     }
//     echo json_encode(['status'=>'success','data'=>$result]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? null;
    $bulan = $input['bulan'] ?? null;
    $hari = $input['hari'] ?? null;
    $status = $input['status'] ?? null;
    if (!$username || !$bulan || !$hari || !$status) {
        http_response_code(400);
        echo json_encode(['error' => 'Parameter tidak lengkap']);
        exit();
    }
    // Hapus data lama untuk user/bulan/hari yang sama
    $data = array_filter($data, function($row) use ($username, $bulan, $hari) {
        return !($row['username'] === $username && $row['bulan'] == $bulan && $row['hari'] == $hari);
    });
    // Tambahkan data baru
    $data[] = [
        'username' => $username,
        'bulan' => $bulan,
        'hari' => $hari,
        'status' => $status
    ];
    $json_save = json_encode(array_values($data));
    if (file_put_contents($filename, $json_save) === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan data kehadiran!']);
        exit();
    }
    // LOG setiap POST ke file log
    $logfile = __DIR__ . '/kehadiran_log.txt';
    $logmsg = date('Y-m-d H:i:s') . " | POST | " . json_encode($input) . "\n";
    file_put_contents($logfile, $logmsg, FILE_APPEND);
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
