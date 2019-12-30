<?php
require_once(__DIR__ . '/config.php');

try {
  $shiritori = new \MyApp\Shiritori();
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}
$shiritori->deleteDB();
$_SESSION['next'] = 'し';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>しりとりアプリ</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
  <body>
    <div id="container">
      <h1>しりとりアプリ</h1>
      <p id="prev-1"></p>
      <p id="prev-2">しりとり</p>
      <h3 id="current"><?php h($shiritori->getOneCharacter("しりとり")); ?></h3>
      <span id="player">Player1</span>
      <input id="token" type="hidden" value="<?php h($_SESSION['token']); ?>">
      <input id="input" type="text" value="">
      <input id="submit" type="submit" value="ターン終了">
      <p id="error">エラー</p>
    </div>
    <script src="script.js"></script>
  </body>
</html>
