<?php
// Script upload media pembelajaran
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit();
}
$targetDir = '../uploads/media_pembelajaran/';
$jsonFile = $targetDir . 'media_pembelajaran.json';
if (!file_exists($jsonFile)) {
  file_put_contents($jsonFile, '[]');
}
$title = $_POST['title'] ?? '';
$desc = $_POST['desc'] ?? '';
$link = $_POST['link'] ?? '';
$filename = '';
$original = '';
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
  $original = $_FILES['file']['name'];
  $filename = time() . '_' . basename($original);
  move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . $filename);
}
$data = json_decode(file_get_contents($jsonFile), true);
$data[] = [
  'title' => $title,
  'desc' => $desc,
  'link' => $link,
  'filename' => $filename,
  'original' => $original,
  'date' => gmdate('Y-m-d H:i:s'),
];
file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
