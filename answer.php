<?php
    //選挙IDがパラメータにあるか確認
    if(isset($_POST["election_id"]) && $_POST["election_id"] !== ""){
        //検証
        $election_id = htmlspecialchars($_POST["election_id"]);
        //DB接続
        $ini_array = parse_ini_file("setting.ini");
        $mysqli = new mysqli($ini_array["DB_HOST"], $ini_array["DB_USER"], $ini_array["DB_PASS"], $ini_array["DB_NAME"], $ini_array["DB_PORT"]);
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
          $sql->close();
          $mysqli->close();
          exit;
        }
        //POST変数に回答データがあるか:戻ってきた場合
        $answers = [];
        if(isset($_POST["answers"]) && $_POST["answers"] !== ""){
          //回答データを転記
          $answers = explode(",",htmlspecialchars($_POST["answers"]));
          if(count($answers) !== count($questionnaire)){
            echo "<p>選択されていない順位があります。はじめからやり直してください。</p><a href='./index.php'>トップに戻る</a>";
            $sql->close();
            $mysqli->close();
            exit;
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
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $election_name; ?>|市民の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="favicon.ico">
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  </head>
  <body id="answer">
    <header id="title_head">ボートマッチングアプリ | 市民の選択</header><br/>
    <div id="election_name"><?php echo $election_name; ?></div>
    <div id="election_dates">告示：<?php echo date_format(date_create_from_format('Y-m-d', $start_date),'Y年n月j日'); ?>　投開票：<?php echo date_format(date_create_from_format('Y-m-d', $election_date),'Y年n月j日'); ?></div>
    <div id="today">本日：<?php echo date("Y年n月j日"); ?></div>
    <div id="message">
    <p>次の分野について、<br/>
    あなたが優先すべきと考える順番に、<br/>
    1位から17位まで選択してください。<br/>
    同順位にはできません。<br/>
    かならず順位をつけてください。</p>
    <p>一度選択した分野は他の順位では選択できません。<br/>
    （灰色になります）</p>
    <p>既に選択した分野を別の順位にしたい場合は、<br/>
    一度分野を選択している順位で別の分野を選択するか、<br/>
    【-選択してください-】に戻してから、<br/>
    正しい順位で選択してください。</p>
    </div>
    <?php for($i = 1; $i <= count($questionnaire); $i++): ?>
      <div class="select_set">
      <label for="rank<?php echo $i; ?>"><?php echo $i; ?>位</label>
      <select class="answer_list" name="answer<?php echo $i; ?>" id="answer<?php echo $i; ?>" onchange="changeOption(<?php echo $i; ?>)">
        <option value="" <?php if(count($answers) == 0) echo "selected "; ?>>-選択してください-</option>
        <?php foreach($questionnaire as $q): ?>
          <option value="<?php echo $q[0]; ?>" <?php if(count($answers) != 0 && $answers[$i-1] == $q[0]) echo "selected "; ?>><?php echo $q[1]; ?></option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" id="selected<?php echo $i; ?>" value="" />
      </div>
    <?php endfor; ?>
    <form method="POST" id="questionnaire" action="./confirm.php" >
        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
        <input type="hidden" name="answers" id="answers" value="" />
        <div class="button_area">
          <input type="button" class="button" id="check_button" onclick="confirm()" value="確認する" />
          <input type="submit" class="button" id="show_election" formaction="./election.php" value="選挙に戻る" />
        </div>
    </form>
    <footer>
      <a href="./index.php"><input type="button" id="top_button" value="トップに戻る" /></a>
      <p>© 豊の国をつくる会</p>
    </footer>
  </body>
</html>
