<?php
    //DB接続
    $mysqli = new mysqli("localhost", "root", "", "nushisama_choice", 3306);
    if($mysqli->connect_error){
        echo $mysqli->connect_error;
        exit();
    } else {
        $mysqli->set_charset("utf8mb4");
    }
    //既存の選挙読込（候補者がいるものに限定）
    $result = $mysqli->query("SELECT election_id,election_name FROM election_tbl e WHERE (SELECT COUNT(*) FROM candidate_tbl c WHERE c.election_id = e.election_id) > 0 ORDER BY start_date ASC");
    //DB切断
    $mysqli->close();
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>トップページ|王の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="favicon.ico">
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  </head>
  <body>
    <div id="title_head">ボートマッチングアプリ</div>
    <h1 id="title">王の選択</h1>
    <input type="button" id="information_head" onclick="appDescriptionToggle()" value="このアプリの説明" />
    <div id="information_contents">
      <p>このアプリは、この国や自治体の<b>王</b>たる皆さんと、<br/>
      <b>僕（しもべ）</b>たる候補者が同じ質問に回答し、<br/>
      その回答の一致度を使って、皆さんの考えと近い候補者をリスト化します。</p>
      <p>最も一致度が高い候補者からお見せすることで、<br/>
      候補者を探す手間を軽減します。</p>
      <p>これにより、投票率が上り、より皆さんの考えが<br/>
      国や自治体の政治に反映されることを期待しています。</p>
      <p>開発者：豊の国をつくる会（代表　古城正信）<br/>
      お問い合わせ：info@toyo-no-kuni.jp<br/>
      WEBサイト：https://toyo-no-kuni.jp
      </p>
    </div>
    <div id="message">「王様、選挙をお選びください」</div>
    <div id="elections">
      <form id="form" action="./election.php" method="POST">
        <input type="hidden" name="election_id" id="election_id" value="" />
        <?php foreach($result as $election): ?>
            <input class="election_button" type="button" onclick="ShowElection(<?php echo htmlspecialchars($election['election_id']); ?>)" value="<?php echo htmlspecialchars($election['election_name']); ?>" />
        <?php endforeach; ?>
      </form>
    </div>
    <footer>
      <p>© 豊の国をつくる会</p>
    </footer>
  </body>
</html>
