<?php
    //選挙IDがパラメータにあるか確認
    if(isset($_POST["election_id"]) && $_POST["election_id"] !== ""){
        //検証
        $election_id = htmlspecialchars($_POST["election_id"]);
        //DB接続
        $mysqli = new mysqli("localhost", "root", "", "nushisama_choice", 3306);
        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit();
        } else {
            $mysqli->set_charset("utf8mb4");
        }
        //選挙情報の取得：SQLインジェクション対策でプリぺアードステートメント作成
        $sql = $mysqli->prepare("SELECT election_name,start_date,election_date FROM election_tbl WHERE election_id = ?");
        $sql->bind_param("i",$election_id);
        //対象選挙データ読込
        try{
          $sql->execute();
          $sql->bind_result($election_name,$start_date,$election_date);
          //件数確認
          $sql->fetch();  
        } catch(Exception $e) {
          echo $e->getMessage();
          $sql->close();
          $mysqli->close();
          exit;
        }
        //質問データの取得:回答データがあるか
        if(!isset($_POST["answers"]) || $_POST["answers"] === ""){
          echo "<p>回答データがありません。はじめからやり直してください。</p><a href='./index.php'>トップに戻る</a>";
          $sql->close();
          $mysqli->close();
          exit;
        }
        $sql->close();
        //回答データを転記
        $answers = explode(",",htmlspecialchars($_POST["answers"]));
        //ある場合、回答数が合っているか
        //質問データの取得
        $questionnaire = [];
        //SQLインジェクション対策でプリぺアードステートメント作成
        $sql = $mysqli->prepare("SELECT question_number,question_text FROM questionnaire_tbl WHERE election_id = ? ORDER BY question_number ASC");
        $sql->bind_param("i",$election_id);
        //質問データ読込
        try{
          $sql->execute();
          $sql->bind_result($question_number,$question_text);
          //全質問ループ
          while($row = $sql->fetch()){
            array_push($questionnaire,[$question_number,$question_text]);
          }
        } catch(Exception $e) {
          echo $e->getMessage();
          $sql->close();
          $mysqli->close();
          exit;
        }
        if(count($answers) !== count($questionnaire)){
          echo "<p>選択されていない順位があります。はじめからやり直してください。</p><a href='./index.php'>トップに戻る</a>";
          $sql->close();
          $mysqli->close();
          exit;
        }
        //記載用に整理
        $list = [];
        foreach($answers as $a){
          foreach($questionnaire as $q){
            if($a == $q[0]){
              array_push($list,$q[1]);
            }
          }
        }
        //DB切断
        $sql->close();
        $mysqli->close();
    } else {
      echo "<p>選挙が選択されていません。戻って選挙を選択してください。</p><a href='./index.php'>戻る</a>";
      exit;
    }
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title><?php echo $election_name; ?>|王の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="favicon.ico">
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  </head>
  <body id="confirm">
    <header id="title_head">ボートマッチングアプリ | 王の選択</header><br/>
    <div id="election_name"><?php echo $election_name; ?></div>
    <div id="election_dates">告示：<?php echo date_format(date_create_from_format('Y-m-d', $start_date),'Y年n月j日'); ?>　投開票：<?php echo date_format(date_create_from_format('Y-m-d', $election_date),'Y年n月j日'); ?></div>
    <div id="today">本日：<?php echo date("Y年n月j日"); ?></div>
    <div id="message">
      <p>「王様、回答内容を確認させてください。」</p>
      <p>「下記の順位でよろしければ、<br/>
      【結果を見る】ボタンを押してください。」</p>
    </div>
    <div id="confirm_list">
    <?php for($i = 1; $i <= count($answers); $i++): ?>
      <p class="rank"><?php echo $i; ?>位：<?php echo $list[$i-1]; ?></p>
    <?php endfor; ?>
    </div>
    <form method="POST" id="confirm" action="./result.php" >
        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
        <input type="hidden" name="answers" value="<?php echo htmlspecialchars($_POST["answers"]); ?>" />
        <div class="button_area">
          <input type="submit" class="button" id="result_button" value="結果を見る" />
          <input type="submit" class="button" id="backto_confirm" formaction="./answer.php" value="選びなおす" />
          <input type="submit" class="button" id="show_election" formaction="./election.php" value="選挙に戻る" />
        </div>
    </form>
    <footer>
      <a href="./index.php"><input type="button" id="top_button" value="トップに戻る" /></a>
      <p>© 豊の国をつくる会</p>
    </footer>
  </body>
</html>
