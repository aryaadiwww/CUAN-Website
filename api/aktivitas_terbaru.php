<?php
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../koneksi.php'; // koneksi DB untuk mapping nama pengguna

// Helper untuk baca JSON aman
function read_json($path) {
    if (!file_exists($path)) return [];
    $raw = @file_get_contents($path);
    if ($raw === false || $raw === '') return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

// Map username -> nama dari DB (siswa dan guru)
$user_map = [];
$res_users = mysqli_query($koneksi, "SELECT username, nama FROM users");
if ($res_users) {
    while ($u = mysqli_fetch_assoc($res_users)) {
        $user_map[$u['username']] = $u['nama'];
    }
}

// Muat definisi Tugas (untuk judul dan lampiran)
$tugas_defs = [];
$defs1 = read_json(__DIR__ . '/tugas_api.php'); // file JSON berisi array tugas
$defs2 = read_json(__DIR__ . '/../tugas.json'); // fallback jika ada
$defs_merge = [];
foreach ($defs1 as $t) { if (isset($t['id'])) { $defs_merge[(string)$t['id']] = $t; } }
foreach ($defs2 as $t) { if (isset($t['id']) && !isset($defs_merge[(string)$t['id']])) { $defs_merge[(string)$t['id']] = $t; } }
$tugas_defs = $defs_merge; // id => row

// Muat definisi Evaluasi
$eval_defs = [];
$edef1 = read_json(__DIR__ . '/evaluasi_api.php');
$eval_defs = [];
foreach ($edef1 as $e) { if (isset($e['id'])) { $eval_defs[(string)$e['id']] = $e; } }

// Muat submissions Tugas/Evaluasi oleh siswa
$tugas_subs = read_json(__DIR__ . '/../tugas_submissions.json');
$eval_subs  = read_json(__DIR__ . '/../evaluasi_submissions.json');

// Muat Bahan Ajar
$bahan_ajar = read_json(__DIR__ . '/../uploads/bahan_ajar/bahan_ajar.json');

// Muat pesan diskusi grup (group chat)
$chat_msgs = read_json(__DIR__ . '/../ipas_chat.json');

// Muat logins (JSON opsional)
$logins = read_json(__DIR__ . '/../users_log.json');

$all_activities = [];

// 1) Aktivitas: Pengumpulan Tugas oleh siswa
foreach ($tugas_subs as $row) {
    $tid = isset($row['tugas_id']) ? (string)$row['tugas_id'] : '';
    $sid = isset($row['siswa_id']) ? (string)$row['siswa_id'] : '';
    $judul = isset($tugas_defs[$tid]['judul']) ? $tugas_defs[$tid]['judul'] : (isset($tugas_defs[$tid]['title']) ? $tugas_defs[$tid]['title'] : ('Tugas ' . $tid));
    $waktu = $row['upload_time'] ?? null;
    if (!$waktu && !empty($row['file']) && preg_match('/_(\d+)\.[a-zA-Z0-9]+$/', $row['file'], $m)) {
        $waktu = date('Y-m-d H:i:s', (int)$m[1]);
    }
    if (!$waktu) { $waktu = date('Y-m-d H:i:s'); }
    $all_activities[] = [
        'username' => $sid,
        'nama' => isset($user_map[$sid]) ? $user_map[$sid] : $sid,
        'kategori' => 'tugas',
        'judul' => $judul,
        'waktu' => $waktu,
        'nilai' => isset($row['nilai']) ? $row['nilai'] : null,
        'file' => isset($row['file']) ? $row['file'] : null,
        'link' => isset($row['link']) ? $row['link'] : null,
        'peran' => 'siswa', // untuk frontend membedakan deskripsi
        'aksi' => 'kumpul'
    ];
}

// 2) Aktivitas: Pengumpulan Evaluasi oleh siswa
foreach ($eval_subs as $row) {
    $eid = isset($row['evaluasi_id']) ? (string)$row['evaluasi_id'] : '';
    $sid = isset($row['siswa_id']) ? (string)$row['siswa_id'] : '';
    $judul = isset($eval_defs[$eid]['judul']) ? $eval_defs[$eid]['judul'] : ('Evaluasi ' . $eid);
    $waktu = $row['upload_time'] ?? null;
    if (!$waktu && !empty($row['file']) && preg_match('/_(\d+)\.[a-zA-Z0-9]+$/', $row['file'], $m)) {
        $waktu = date('Y-m-d H:i:s', (int)$m[1]);
    }
    if (!$waktu) { $waktu = date('Y-m-d H:i:s'); }
    $all_activities[] = [
        'username' => $sid,
        'nama' => isset($user_map[$sid]) ? $user_map[$sid] : $sid,
        'kategori' => 'evaluasi',
        'judul' => $judul,
        'waktu' => $waktu,
        'nilai' => isset($row['nilai']) ? $row['nilai'] : null,
        'file' => isset($row['file']) ? $row['file'] : null,
        'link' => isset($row['link']) ? $row['link'] : null,
        'peran' => 'siswa',
        'aksi' => 'kumpul'
    ];
}

// 3) Aktivitas: Guru membuat Tugas
foreach ($tugas_defs as $row) {
    $tid = isset($row['id']) ? (string)$row['id'] : '';
    $judul = isset($row['judul']) ? $row['judul'] : (isset($row['title']) ? $row['title'] : ('Tugas ' . $tid));
    $teacher = isset($row['teacher']) ? (string)$row['teacher'] : 'guru';
    $waktu = isset($row['date']) ? $row['date'] : null;
    if (!$waktu) { $waktu = date('Y-m-d H:i:s'); }
    $file = isset($row['lampiran_file']) ? $row['lampiran_file'] : (isset($row['filename']) ? $row['filename'] : null);
    $all_activities[] = [
        'username' => $teacher,
        'nama' => isset($user_map[$teacher]) ? $user_map[$teacher] : $teacher,
        'kategori' => 'tugas',
        'judul' => $judul,
        'waktu' => $waktu,
        'nilai' => null,
        'file' => $file,
        'link' => isset($row['lampiran_link']) ? $row['lampiran_link'] : (isset($row['link']) ? $row['link'] : null),
        'peran' => 'guru',
        'aksi' => 'buat'
    ];
}

// 4) Aktivitas: Guru membuat Evaluasi
foreach ($edef1 as $row) {
    $eid = isset($row['id']) ? (string)$row['id'] : '';
    $judul = isset($row['judul']) ? $row['judul'] : ('Evaluasi ' . $eid);
    $teacher = isset($row['teacher']) ? (string)$row['teacher'] : 'guru';
    $waktu = isset($row['date']) ? $row['date'] : date('Y-m-d H:i:s');
    $all_activities[] = [
        'username' => $teacher,
        'nama' => isset($user_map[$teacher]) ? $user_map[$teacher] : $teacher,
        'kategori' => 'evaluasi',
        'judul' => $judul,
        'waktu' => $waktu,
        'nilai' => null,
        'file' => isset($row['lampiran_file']) ? $row['lampiran_file'] : null,
        'link' => isset($row['lampiran_link']) ? $row['lampiran_link'] : null,
        'peran' => 'guru',
        'aksi' => 'buat'
    ];
}

// 5) Aktivitas: Bahan Ajar diunggah guru
foreach ($bahan_ajar as $row) {
    $teacher = isset($row['teacher']) ? (string)$row['teacher'] : 'guru';
    $all_activities[] = [
        'username' => $teacher,
        'nama' => isset($user_map[$teacher]) ? $user_map[$teacher] : $teacher,
        'kategori' => 'bahanajar',
        'judul' => isset($row['title']) ? $row['title'] : '-',
        'waktu' => isset($row['date']) ? $row['date'] : date('Y-m-d H:i:s'),
        'nilai' => null,
        'file' => isset($row['filename']) ? $row['filename'] : null,
        'link' => isset($row['link']) ? $row['link'] : null
    ];
}

// 6) Aktivitas: Diskusi grup (group chat)
foreach ($chat_msgs as $msg) {
    if (!is_array($msg)) continue;
    if (isset($msg['target']) && $msg['target'] === 'all') {
        $from = isset($msg['from']) ? (string)$msg['from'] : 'user';
        $all_activities[] = [
            'username' => $from,
            'nama' => isset($user_map[$from]) ? $user_map[$from] : $from,
            'kategori' => 'diskusi',
            'judul' => isset($msg['msg']) ? strip_tags($msg['msg']) : '-',
            'waktu' => isset($msg['time']) ? $msg['time'] : date('Y-m-d H:i:s'),
            'nilai' => null,
            'file' => null
        ];
    }
}

// 7) Aktivitas: Login (dari JSON log)
foreach ($logins as $lg) {
    if (!is_array($lg)) continue;
    $usr = isset($lg['username']) ? (string)$lg['username'] : null;
    $waktu = isset($lg['waktu']) ? $lg['waktu'] : date('Y-m-d H:i:s');
    if ($usr) {
        $all_activities[] = [
            'username' => $usr,
            'nama' => isset($user_map[$usr]) ? $user_map[$usr] : $usr,
            'kategori' => 'login',
            'judul' => 'Login',
            'waktu' => $waktu,
            'nilai' => null,
            'file' => null
        ];
    }
}

// Urutkan berdasarkan waktu terbaru
foreach ($all_activities as &$act) {
    $act['_ts'] = isset($act['waktu']) ? strtotime($act['waktu']) : 0;
}
unset($act);
usort($all_activities, function($a, $b) { return ($b['_ts'] ?? 0) <=> ($a['_ts'] ?? 0); });
foreach ($all_activities as &$act) { unset($act['_ts']); }
unset($act);

echo json_encode([
    'status' => 'success',
    'data' => $all_activities
]);
