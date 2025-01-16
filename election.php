<?php
    //選挙IDがパラメータにあるか確認
    if($_POST["election_id"] !== null && $_POST["election_id"] !== ""){
        //検証
        $election_id = $_POST["election_id"];
        //DB接続
        $mysqli = new mysqli("localhost", "root", "", "nushisama_choice", 3306);
        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit();
        } else {
            $mysqli->set_charset("utf8mb4");
        }
        //SQLインジェクション対策でプリぺアードステートメント作成
        $sql = $mysqli->prepare("SELECT election_name,start_date,election_date,election_information FROM election_tbl WHERE election_id = ?");
        $sql->bind_param("i",$election_id);
        //対象選挙データ読込
        try{
          $sql->execute();
          $sql->bind_result($election_name,$start_date,$election_date,$election_information);
          //件数確認
          $sql->fetch();  
        } catch(Exception $e) {
          echo $e->getMessage();
          $mysqli->close();
          exit;
        }
        //DB切断
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
    <title><?php echo $election_name; ?>|主様の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="script.js"></script>
  </head>
  <body>
    <header>主様の選択 ©</header>
    <div id="election_name"><?php echo $election_name; ?></div>
    <div id="election_dates">告示：<?php echo $start_date; ?>　投開票：<?php echo $election_date; ?></div>
    <div id="today">今日：<?php echo date("Y-m-d"); ?></div>
    <form method="POST">
      <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
      <input type="submit" class="election_view_button" id="start_matching" formaction="./answer.php" value="マッチングを始める" />
      <input type="submit" class="election_view_button" id="show_results" formaction="./result.php" value="以前のマッチング結果を見る" />
      <input type="submit" class="election_view_button" id="show_candidate_answer" formaction="./candidate.php" value="候補者の回答を見る" />
    </form>
    <input type="button" class="election_view_button" id="show_election_information" onclick="electionInfoToggle()" value="選挙の情報を見る" />
    <div class="election_info" id="election_info"><?php echo str_replace(["\r\n", "\r","\n"], "<br/>", $election_information); ?></div>
    <footer><a href="./index.php">トップに戻る</a></footer>
  </body>
</html>
