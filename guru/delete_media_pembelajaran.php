<?php
// Script hapus media pembelajaran
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit();
}
$targetDir = '../uploads/media_pembelajaran/';
$jsonFile = $targetDir . 'media_pembelajaran.json';
$data = json_decode(file_get_contents($jsonFile), true);
$input = json_decode(file_get_contents('php://input'), true);
$filename = $input['filename'] ?? '';
$link = $input['link'] ?? '';
$title = $input['title'] ?? '';
$newData = [];
foreach ($data as $entry) {
  if ($entry['title'] === $title && ($entry['filename'] === $filename || $entry['link'] === $link)) {
    if ($filename && file_exists($targetDir . $filename)) {
      unlink($targetDir . $filename);
    }
    continue;
  }
  $newData[] = $entry;
}
file_put_contents($jsonFile, json_encode($newData, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
