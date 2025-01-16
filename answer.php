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
          $mysqli->close();
          exit;
        }
        $sql->close();
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
          $mysqli->close();
          exit;
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
    <title><?php echo $election_name; ?>|主様の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="script.js"></script>
  </head>
  <body id="answer">
    <script>
        //保存変数
        var answer = [];
        //初期処理
        window.onload = (event) => {
            //プログレスバー初期化
            document.getElementById("progress_bar").value = 0;
            //前へボタン非表示
            document.getElementById("before_button").display = 'none';
            //初回質問セット
            document.getElementById("questionnaire_text").value = '<?php echo $questionnaire[0]['questionnaire_text']; ?>';
        };
        //表示する質問を変更する処理

    </script>
    <header>主様の選択 ©</header>
    <div id="election_name"><?php echo $election_name; ?></div>
    <div id="election_dates">告示：<?php echo $start_date; ?>　投開票：<?php echo $election_date; ?></div>
    <div id="today">今日：<?php echo date("Y-m-d"); ?></div>
    <progress id="progress_bar" max="<?php echo count($questionnaire); ?>" value="0"></progress>
    <label id="questionnaire_text"></label>
    <form method="POST">
        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
        <input type="radio" id="answer1" name="answer" class="answer" value="1" /><label for="answer1">そう思う</label>
        <input type="radio" id="answer2" name="answer" class="answer" value="2" /><label for="answer2">どちらかと言えばそう思う</label>
        <input type="radio" id="answer3" name="answer" class="answer" value="3" /><label for="answer3">どちらでもない</label>
        <input type="radio" id="answer4" name="answer" class="answer" value="4" /><label for="answer4">どちらかと言えばそう思わない</label>
        <input type="radio" id="answer5" name="answer" class="answer" value="5" /><label for="answer5">そう思わない</label>
        <div id="button_area">
            <input type="button" id="before_button" onclick="" value="前へ" />
            <input type="button" id="next_button" onclick="" value="次へ" />
        </div>
    </form>
    <footer><a href="./index.php">トップに戻る</a></footer>
  </body>
</html>
