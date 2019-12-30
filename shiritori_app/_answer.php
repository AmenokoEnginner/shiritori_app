<?php
require_once(__DIR__ . '/config.php');

try {
  $shiritori = new \MyApp\Shiritori();
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}

try {
  $error = false;
  $words = $_POST['current'];
  $char = $shiritori->getOneCharacter($words);

  if (!$char) {
    $error = true;
  }
} catch (Exception $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
  echo $e->getMessage();
  exit;
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
  'current' => $words,
  'next' => $char,
  'error' => $error,
]);
