<?php
namespace MyApp;

class Shiritori {
  private $db;

  public function __construct() {
    $this->connectDB();
    $this->createToken();
  }

  // データベース接続
  private function connectDB() {
    try {
      $this->db = new \PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      throw new \Exception('データベースの接続に失敗しました');
    }
  }

  // データベース内のinstantテーブルを初期化
  public function deleteDB() {
    $sql = "TRUNCATE TABLE instant";
    $res = $this->db->query($sql);
  }

  // トークン作成
  private function createToken() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  // CSRF対策
  private function validateToken() {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!');
    }
  }

  // 最後尾の文字を取得する関数
  public function getOneCharacter($chars) {
    if (isset($_POST['token'])) {
      $this->validateToken();
    }

    // 半角を全角に変換
    $chars = mb_convert_kana($chars, "KV");

    // すでに同じ単語を使っている場合
    if ($this->overlapWords($chars)) {
      return false;
    }

    // 最後尾の文字を取得(ただし、"ー"の場合は最後尾から1つ前の文字を取得)
    if (mb_substr($chars, -1, 1) == "ー") {
      $char = mb_substr($chars, -2, 1);
    } else {
      $char = mb_substr($chars, -1, 1);
    }

    // カタカナを平仮名に変換(最初に変換処理をしてください)
    $char = mb_convert_kana($char, "c");

    // 小文字の平仮名を大文字に変換
    $char = $this->convertSmallKana($char);

    // 最後尾の文字が平仮名の場合
    if (preg_match("/^[ぁ-ん]+$/u", $char)) {
      // 最後尾の文字が「ん」の場合
      if ($char == "ん") {
        $_SESSION['loser'] = $_POST['player'];
        return $char;
      }

      // 前の単語の末尾と一致しなかった場合
      if (mb_substr(mb_convert_kana($chars, "c"), 0, 1) != $_SESSION['next']) {
        return false;
      }

      // 一度使った単語を記憶する
      $this->throwAwayWords($chars);

      // 末尾の文字を記憶しておく
      $_SESSION['next'] = $char;

      return $char;
    }

    // それ以外の場合
    return false;
  }

  // すでに使っている単語があるかどうかを確認する関数
  private function overlapWords($chars) {
    $sql = "SELECT * FROM instant";
    $stmt = $this->db->query($sql);

    while ($instant = $stmt->fetch()) {
      if ($instant['word'] == $chars) {
        return true;
      }
    }

    return false;
  }

  // 小文字の平仮名を大文字に変換する関数
  private function convertSmallKana($char) {
    $small_kanas = [
      "ぁ", "ぃ", "ぅ", "ぇ", "ぉ", "ゃ", "ゅ", "ょ", "っ"
    ];
    $big_kanas = [
      "あ", "い", "う", "え", "お", "や", "ゆ", "よ", "つ"
    ];

    for ($i = 0; $i < 9; $i++) {
      if ($char == $small_kanas[$i]) {
        $char = $big_kanas[$i];
      }
    }

    return $char;
  }

  // 一度使った単語を一時的に記憶する関数
  private function throwAwayWords($chars) {
    $sql = 'INSERT INTO instant SET word=?';
    $statement = $this->db->prepare($sql);
    $statement->execute([$chars]);
  }
}
