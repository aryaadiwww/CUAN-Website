<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit();
}

$targetDir = '../uploads/bahan_ajar/';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $desc = isset($_POST['desc']) ? trim($_POST['desc']) : '';
    $link = isset($_POST['link']) ? trim($_POST['link']) : '';
    $teacher = $_SESSION['username'];
    $date = date('Y-m-d H:i:s');
    $metaFile = '../uploads/bahan_ajar/bahan_ajar.json';
    $data = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
    $entry = [
        'title' => $title,
        'desc' => $desc,
        'date' => $date,
        'teacher' => $teacher
    ];
    $hasFile = isset($_FILES['file']) && $_FILES['file']['error'] === 0 && $_FILES['file']['name'] !== '';
    $hasLink = $link !== '';
    if (!$hasFile && !$hasLink) {
        echo json_encode(['error' => 'Isi file atau link minimal satu!']);
        exit();
    }
    if ($hasFile) {
        $file = $_FILES['file'];
        $filename = basename($file['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'mp4', 'mp3'];
        if (!in_array($ext, $allowed)) {
            echo json_encode(['error' => 'File type not allowed']);
            exit();
        }
        $newName = uniqid('bahanajar_', true) . '.' . $ext;
        $targetFile = $targetDir . $newName;
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            echo json_encode(['error' => 'Failed to upload file']);
            exit();
        }
        $entry['filename'] = $newName;
        $entry['original'] = $filename;
    }
    if ($hasLink) {
        $entry['link'] = $link;
    }
    array_unshift($data, $entry);
    file_put_contents($metaFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['success' => true, 'entry' => $entry]);
    exit();
}
echo json_encode(['error' => 'Invalid request']);
