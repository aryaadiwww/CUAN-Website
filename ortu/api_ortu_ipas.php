<?php
header('Content-Type: application/json');

// Ambil username siswa dari parameter GET
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Bahan ajar
$bahan_ajar = [];
if (file_exists('../uploads/bahan_ajar/bahan_ajar.json')) {
    $bahan_ajar_data = json_decode(file_get_contents('../uploads/bahan_ajar/bahan_ajar.json'), true);
    if (is_array($bahan_ajar_data)) {
        foreach ($bahan_ajar_data as $item) {
            $bahan_ajar[] = [
                'judul' => $item['judul'] ?? $item['title'] ?? '-',
                'deskripsi' => $item['deskripsi'] ?? $item['desc'] ?? $item['description'] ?? '-',
                'date' => $item['date'] ?? $item['tanggal'] ?? date('Y-m-d'),
                'teacher' => $item['teacher'] ?? $item['guru'] ?? 'Guru IPAS',
                'file' => $item['file'] ?? null,
                'link' => $item['link'] ?? null
            ];
        }
    }
}

// Tugas dari API
$tugas_api = [];
$tugas_api_data = @file_get_contents('http://localhost/CUAN/api/tugas_api.php');
if ($tugas_api_data) {
    $tugas_api = json_decode($tugas_api_data, true);
    if (!is_array($tugas_api)) $tugas_api = [];
}

// Evaluasi dari API
$evaluasi_api = [];
$evaluasi_api_data = @file_get_contents('http://localhost/CUAN/api/evaluasi_api.php');
if ($evaluasi_api_data) {
    $evaluasi_api = json_decode($evaluasi_api_data, true);
    if (!is_array($evaluasi_api)) $evaluasi_api = [];
}

// Tugas dan Evaluasi submissions
$tugas = [];
$evaluasi = [];
$tugas_submissions = [];
$evaluasi_submissions = [];

if (file_exists('../tugas_submissions.json')) {
    $tugas_submissions = json_decode(file_get_contents('../tugas_submissions.json'), true);
    if (!is_array($tugas_submissions)) $tugas_submissions = [];
}

if (file_exists('../evaluasi_submissions.json')) {
    $evaluasi_submissions = json_decode(file_get_contents('../evaluasi_submissions.json'), true);
    if (!is_array($evaluasi_submissions)) $evaluasi_submissions = [];
}

// Fungsi untuk mendapatkan status tugas
function get_tugas_status($tugas_id, $siswa_id, $submissions) {
    foreach ($submissions as $sub) {
        if (($sub['tugas_id'] == $tugas_id || $sub['tugas_id'] == $tugas_id) && $sub['siswa_id'] == $siswa_id) {
            return $sub;
        }
    }
    return null;
}

// Fungsi untuk mendapatkan status evaluasi
function get_evaluasi_status($evaluasi_id, $siswa_id, $submissions) {
    foreach ($submissions as $sub) {
        if (($sub['evaluasi_id'] == $evaluasi_id || $sub['evaluasi_id'] == $evaluasi_id) && $sub['siswa_id'] == $siswa_id) {
            return $sub;
        }
    }
    return null;
}

// Proses data tugas
foreach ($tugas_api as $t) {
    $status = get_tugas_status($t['id'] ?? $t['judul'] ?? $t['title'], $username, $tugas_submissions);
    $tugas[] = [
        'id' => $t['id'] ?? $t['judul'] ?? $t['title'],
        'judul' => $t['judul'] ?? $t['title'] ?? '-',
        'deskripsi' => $t['deskripsi'] ?? $t['description'] ?? '-',
        'deadline' => $t['deadline'] ?? '-',
        'tanggal' => $t['tanggal'] ?? $t['date'] ?? date('Y-m-d'),
        'status' => $status ? 'Selesai' : 'Belum Dikerjakan',
        'nilai' => $status ? ($status['nilai'] ?? null) : null,
        'file' => $status ? ($status['file'] ?? null) : null,
        'link' => $status ? ($status['link'] ?? null) : null,
        'upload_time' => $status ? ($status['upload_time'] ?? '-') : '-'
    ];
}

// Proses data evaluasi
foreach ($evaluasi_api as $e) {
    $status = get_evaluasi_status($e['id'] ?? $e['judul'] ?? $e['title'], $username, $evaluasi_submissions);
    $evaluasi[] = [
        'id' => $e['id'] ?? $e['judul'] ?? $e['title'],
        'judul' => $e['judul'] ?? $e['title'] ?? '-',
        'deskripsi' => $e['deskripsi'] ?? $e['description'] ?? '-',
        'deadline' => $e['deadline'] ?? '-',
        'tanggal' => $e['tanggal'] ?? $e['date'] ?? date('Y-m-d'),
        'status' => $status ? 'Selesai' : 'Belum Dikerjakan',
        'nilai' => $status ? ($status['nilai'] ?? null) : null,
        'file' => $status ? ($status['file'] ?? null) : null,
        'link' => $status ? ($status['link'] ?? null) : null,
        'upload_time' => $status ? ($status['upload_time'] ?? '-') : '-'
    ];
}

// Diskusi dari ipas_chat.json
$diskusi = [];
if (file_exists('../ipas_chat.json')) {
    $chat_data = json_decode(file_get_contents('../ipas_chat.json'), true);
    if (is_array($chat_data)) {
        // Kelompokkan chat berdasarkan thread (untuk contoh sederhana, kita gunakan tanggal sebagai thread)
        $threads = [];
        foreach ($chat_data as $chat) {
            // Hanya tampilkan diskusi yang relevan dengan siswa yang dipilih
            if ($username && ($chat['from'] == $username || $chat['target'] == $username || $chat['target'] == 'all')) {
                $date = substr($chat['time'], 0, 10); // Ambil tanggal saja
                $thread_id = $date . '-' . ($chat['level'] ?? 'general');
                if (!isset($threads[$thread_id])) {
                    $threads[$thread_id] = [];
                }
                $threads[$thread_id][] = $chat;
            }
        }
        
        // Format diskusi
        foreach ($threads as $thread_id => $chats) {
            $date = substr($thread_id, 0, 10);
            $level = substr($thread_id, 11);
            $thread = [
                'thread' => 'Diskusi ' . ($level != 'general' ? $level . ' - ' : '') . 'tanggal ' . $date,
                'oleh' => $chats[0]['from'],
                'tanggal' => $date,
                'balasan' => []
            ];
            
            // Tambahkan pesan pertama sebagai isi thread
            $thread['isi'] = $chats[0]['msg'];
            
            foreach ($chats as $index => $chat) {
                if ($index > 0) { // Skip yang pertama karena sudah jadi thread
                    $thread['balasan'][] = [
                        'oleh' => $chat['from'],
                        'isi' => $chat['msg'],
                        'waktu' => $chat['time']
                    ];
                }
            }
            
            $diskusi[] = $thread;
        }
    }
}

// Output JSON
$data = [
    "bahan_ajar" => $bahan_ajar,
    "tugas" => $tugas,
    "evaluasi" => $evaluasi,
    "diskusi" => $diskusi
];
echo json_encode($data);
