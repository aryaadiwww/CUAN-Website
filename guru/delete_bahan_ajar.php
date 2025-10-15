<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  http_response_code(403);
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit();
}
header('Content-Type: application/json');
$metaFile = '../uploads/bahan_ajar/bahan_ajar.json';
$targetDir = '../uploads/bahan_ajar/';
$input = json_decode(file_get_contents('php://input'), true);
$filename = isset($input['filename']) ? $input['filename'] : '';
$link = isset($input['link']) ? $input['link'] : '';
$title = isset($input['title']) ? $input['title'] : '';
if (!file_exists($metaFile)) {
  echo json_encode(['success' => false, 'error' => 'Data tidak ditemukan']);
  exit();
}
$data = json_decode(file_get_contents($metaFile), true);
if (!is_array($data)) $data = [];
$found = false;
$new_data = [];
foreach ($data as $entry) {
  $match = false;
  if ($filename && isset($entry['filename']) && $entry['filename'] === $filename) $match = true;
  if ($link && isset($entry['link']) && $entry['link'] === $link) $match = true;
  if ($title && isset($entry['title']) && $entry['title'] === $title) $match = true;
  if ($match) {
    $found = true;
    // Hapus file jika ada
    if (isset($entry['filename']) && $entry['filename']) {
      $file_path = $targetDir . $entry['filename'];
      if (file_exists($file_path)) @unlink($file_path);
    }
    continue; // skip entry
  }
  $new_data[] = $entry;
}
if (!$found) {
  echo json_encode(['success' => false, 'error' => 'Bahan ajar tidak ditemukan']);
  exit();
}
// Simpan data baru
file_put_contents($metaFile, json_encode($new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['success' => true]);
