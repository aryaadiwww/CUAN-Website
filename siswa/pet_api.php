<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'siswa') {
  echo json_encode(["success" => false, "message" => "Unauthorized"]);
  exit();
}

$username = $_SESSION['username'];
$data_file = __DIR__ . "/pet_data/{$username}.json";

if (!file_exists(__DIR__ . "/pet_data")) {
  mkdir(__DIR__ . "/pet_data", 0777, true);
}

// Load pet data
function loadPet($file) {
  if (file_exists($file)) {
    return json_decode(file_get_contents($file), true);
  } else {
    // Default pet setup saat pertama kali
    return [
      "pet_type" => "dino",
      "pet_name" => "Dino",
      "pet_desc" => "Pet lucu yang bisa berkembang seiring kamu bermain!",
      "pet_level" => 0,
      "pet_food" => 0,
      "pet_happy" => 0
    ];
  }
}

// Simpan pet data
function savePet($file, $data) {
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// GET: Ambil data pet
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $data = loadPet($data_file);
  echo json_encode($data);
  exit();
}

// POST: Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pet = loadPet($data_file);

  // 1. Pemberian makan pet
  if (isset($_POST['action']) && $_POST['action'] === 'feed') {
    $food_gain = intval($_POST['food_gain'] ?? 0);
    $level_gain = intval($_POST['level_gain'] ?? 0);
    $happy_gain = intval($_POST['happy_gain'] ?? 5);

    $pet['pet_food'] += $food_gain;
    $pet['pet_level'] += $level_gain;

    // Kebahagiaan naik setiap makan
    $pet['pet_happy'] = min(100, $pet['pet_happy'] + $happy_gain);

    if ($pet['pet_food'] < 0) $pet['pet_food'] = 0;

    savePet($data_file, $pet);
    echo json_encode(["success" => true, "pet_food" => $pet['pet_food'], "pet_level" => $pet['pet_level'], "pet_happy" => $pet['pet_happy']]);
    exit();
  }
  
  // 1.5 Update statistik pet dari game
  if (isset($_POST['action']) && $_POST['action'] === 'update_stats') {
    $food_gain = intval($_POST['food_gain'] ?? 0);
    $level_gain = intval($_POST['level_gain'] ?? 0);
    $happy_gain = intval($_POST['happy_gain'] ?? 0);

    $pet['pet_food'] += $food_gain;
    $pet['pet_level'] += $level_gain;
    $pet['pet_happy'] = min(100, $pet['pet_happy'] + $happy_gain);

    if ($pet['pet_food'] < 0) $pet['pet_food'] = 0;

    savePet($data_file, $pet);
    echo json_encode(["success" => true, "pet_food" => $pet['pet_food'], "pet_level" => $pet['pet_level'], "pet_happy" => $pet['pet_happy']]);
    exit();
  }

  // 2. Update jenis, nama, deskripsi pet
  if (isset($_POST['pet_type'], $_POST['pet_name'], $_POST['pet_desc'])) {
    $pet['pet_type'] = htmlspecialchars($_POST['pet_type']);
    $pet['pet_name'] = htmlspecialchars($_POST['pet_name']);
    $pet['pet_desc'] = htmlspecialchars($_POST['pet_desc']);

    savePet($data_file, $pet);
    echo json_encode(["success" => true]);
    exit();
  }

  echo json_encode(["success" => false, "message" => "Invalid request"]);
  exit();
}
?>
