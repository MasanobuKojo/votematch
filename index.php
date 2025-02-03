<?php
    //DB接続
    $ini_array = parse_ini_file("setting.ini");
    $mysqli = new mysqli($ini_array["DB_HOST"], $ini_array["DB_USER"], $ini_array["DB_PASS"], $ini_array["DB_NAME"], $ini_array["DB_PORT"]);
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
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>トップページ|市民の選択</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="favicon.ico">
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  </head>
  <body>
    <div id="title_head">ボートマッチングアプリ</div>
    <h1 id="title">市民の選択</h1>
    <input type="button" id="information_head" onclick="appDescriptionToggle()" value="このアプリの説明" />
    <div id="information_contents">
      <p>このアプリは、この国や自治体の<b>主権者たる市民</b>の皆さんと、<br/>
      <b>奉仕者たる候補者</b>が同じ質問に回答し、<br/>
      その回答の一致度を使って、皆さんの考えと近い候補者をリスト化します。</p>
      <p>最も一致度が高い候補者からお見せすることで、<br/>
      候補者を探す手間を軽減します。</p>
      <p>これにより、投票率が上り、より市民の皆さんの考えが<br/>
      国や自治体の政治に反映されることを期待しています。</p>
      <p>開発：豊の国をつくる会（代表　古城正信）<br/>
      お問い合わせ：info@toyo-no-kuni.jp<br/>
      WEBサイト：<a href="https://toyo-no-kuni.jp">https://toyo-no-kuni.jp</a>
      </p>
      <p>協力：おおいたの政治についてしんけん考える県民有志の会<br/>
      <a href="https://www.instagram.com/oitasinken/"><img id="yushinokai_logo" src="yushinokai_logo.jpg" /></a><br/>
      Instagram:<a href="https://www.instagram.com/oitasinken/">@oitasinken</a></p>
    </div>
    <div id="message">選択してください</div>
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
