<?php
    //DB接続
    $mysqli = new mysqli("localhost", "root", "", "nushisama_choice", 3306);
    if($mysqli->connect_error){
        echo $mysqli->connect_error;
        exit();
    } else {
        $mysqli->set_charset("utf8mb4");
    }
        //既存の選挙読込
    $result = $mysqli->query("SELECT election_id,election_name FROM election_tbl ORDER BY start_date ASC");
    //DB切断
    $mysqli->close();
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>トップページ|主様の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="jquery-3.7.1.min.js"></script>
  </head>
  <body>
    <div id="title_head">ボートマッチングアプリ</div>
    <div id="title">主様の選択</div>
    <div id="information_head">このアプリの説明</div>
    <div id="information_contents"></div>
    <div id="select_election">選挙を選んでください</div>
    <div id="elections">
      <form id="form" action="./election.php" method="POST">
        <input type="hidden" name="election_id" id="election_id" value="" />
        <?php foreach($result as $election): ?>
            <input class="election_button" type="button" onclick="ShowElection(<?php echo htmlspecialchars($election['election_id']); ?>)" value="<?php echo htmlspecialchars($election['election_name']); ?>" />
        <?php endforeach; ?>
      </form>
    </div>
    <footer>© 主様の選択</footer>
    <script>
      function ShowElection(i){
         $("#election_id").val(i);
         $("#form").submit(); 
      }
    </script>
  </body>
</html>
