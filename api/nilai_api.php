<?php
header('Content-Type: application/json');

$nilai_file = __DIR__ . '/nilai_data.json';

if (!file_exists($nilai_file)) {
    file_put_contents($nilai_file, json_encode([]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ambil semua data nilai
        $data = json_decode(file_get_contents($nilai_file), true);
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;
    case 'POST':
        // Simpan nilai baru
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['siswa_id']) || !isset($input['jenis']) || !isset($input['nilai']) || !isset($input['mapel'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            exit;
        }
        $data = json_decode(file_get_contents($nilai_file), true);
        $input['id'] = uniqid('nilai_');
        $input['timestamp'] = date('Y-m-d H:i:s');
        $data[] = $input;
        file_put_contents($nilai_file, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'data' => $input]);
        break;
    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
        break;
}
