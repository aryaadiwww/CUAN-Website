<?php
session_start();
header('Content-Type: application/json');

$chat_file = __DIR__ . '/ipas_chat.json';
if (!file_exists($chat_file)) {
    file_put_contents($chat_file, json_encode([]));
}

function get_username() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}
function get_level() {
    return isset($_SESSION['level']) ? $_SESSION['level'] : null;
}

// Ambil pesan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $target = isset($_GET['target']) ? $_GET['target'] : 'all';
    $username = get_username();
    $level = get_level();
    $data = json_decode(file_get_contents($chat_file), true);
    $result = [];
    foreach ($data as $msg) {
        // Chat grup
        if ($target === 'all' && $msg['target'] === 'all') {
            $result[] = $msg;
        }
        // Chat pribadi
        else if ($target !== 'all') {
            // Guru melihat chat ke siswa tertentu
            if ($level === 'guru' && $msg['target'] === $target && $msg['from'] === $username) {
                $result[] = $msg;
            }
            // Siswa melihat chat dari guru ke dirinya
            else if ($level === 'siswa' && $msg['target'] === $username && $msg['from'] !== $username) {
                $result[] = $msg;
            }
            // Siswa melihat chat yang dia kirim ke guru
            else if ($level === 'siswa' && $msg['from'] === $username && $msg['target'] === 'guru') {
                $result[] = $msg;
            }
            // Guru melihat chat dari siswa ke dirinya
            else if ($level === 'guru' && $msg['target'] === 'guru' && $msg['from'] === $target) {
                $result[] = $msg;
            }
            // Orang tua melihat chat yang dia kirim ke guru
            else if ($level === 'ortu' && $msg['from'] === $username && $msg['target'] === $target) {
                $result[] = $msg;
            }
            // Orang tua melihat chat dari guru ke dirinya
            else if ($level === 'ortu' && $msg['target'] === $username && $msg['from'] === $target) {
                $result[] = $msg;
            }
            // Guru melihat chat dari orang tua ke dirinya
            else if ($level === 'guru' && $msg['target'] === $username && $msg['from'] === $target) {
                $result[] = $msg;
            }
            // Guru melihat chat yang dia kirim ke orang tua
            else if ($level === 'guru' && $msg['from'] === $username && $msg['target'] === $target && $target !== 'guru') {
                $result[] = $msg;
            }
        }
    }
    echo json_encode($result);
    exit();
}

// Kirim pesan atau hapus pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = get_username();
    $level = get_level();
    // Hapus pesan
    if (isset($input['action']) && $input['action'] === 'delete') {
        $time = isset($input['time']) ? $input['time'] : null;
        $from = isset($input['from']) ? $input['from'] : null;
        if (!$username || !$time || !$from) {
            http_response_code(400);
            echo json_encode(['error' => 'Parameter tidak lengkap']);
            exit();
        }
        $data = json_decode(file_get_contents($chat_file), true);
        $found = false;
        foreach ($data as $i => $msg) {
            if ($msg['from'] === $username && $msg['from'] === $from && $msg['time'] === $time) {
                array_splice($data, $i, 1);
                $found = true;
                break;
            }
        }
        if ($found) {
            file_put_contents($chat_file, json_encode($data));
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Pesan tidak ditemukan atau bukan milik Anda']);
        }
        exit();
    }
    // Kirim pesan
    $msg = isset($input['msg']) ? trim($input['msg']) : '';
    $target = isset($input['target']) ? $input['target'] : 'all';
    if (!$msg || !$username) {
        http_response_code(400);
        echo json_encode(['error' => 'Pesan kosong atau tidak login']);
        exit();
    }
    $new_msg = [
        'from' => $username,
        'level' => $level,
        'target' => $target,
        'msg' => htmlspecialchars($msg),
        'time' => date('Y-m-d H:i:s')
    ];
    $data = json_decode(file_get_contents($chat_file), true);
    $data[] = $new_msg;
    file_put_contents($chat_file, json_encode($data));
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
