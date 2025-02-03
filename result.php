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
        $sql->close();
        //候補者抽出処理
        $candidates = [];
        //SQLインジェクション対策でプリぺアードステートメント作成
        $sql = $mysqli->prepare("SELECT candidate_id,politician_id,candidate_name,candidate_information FROM candidate_tbl WHERE election_id = ?");
        $sql->bind_param("i",$election_id);
        //候補者データ読込
        try{
          $sql->execute();
          $sql->bind_result($candidate_id,$politician_id,$candidate_name,$candidate_information);
          //全質問ループ
          while($row = $sql->fetch()){
            array_push($candidates,[$candidate_id,$politician_id,$candidate_name,$candidate_information]);
          }
        } catch(Exception $e) {
          echo $e->getMessage();
          $sql->close();
          $mysqli->close();
          exit;
        }
        //候補者がいなければエラー
        if(count($candidates) == 0){
            echo "<p>候補者が登録されていません。戻って選挙を選択してください。</p><a href='./index.php'>戻る</a>";
            $sql->close();
            $mysqli->close();
            exit;
        }
        //候補者の回答抽出
        $candidate_answers = [];
        //SQLインジェクション対策でプリぺアードステートメント作成
        $sql = $mysqli->prepare("SELECT answer_id,candidate_id,question_number,answer_value,answer_comment FROM candidate_answer_tbl WHERE election_id = ?");
        $sql->bind_param("i",$election_id);
        //候補者回答データ読込
        try{
          $sql->execute();
          $sql->bind_result($answer_id,$candidate_id,$question_number,$answer_value,$answer_comment);
          //回答データ転記
          while($row = $sql->fetch()){
            array_push($candidate_answers,[$answer_id,$candidate_id,$question_number,$answer_value,$answer_comment]);
          }
        } catch(Exception $e) {
          echo $e->getMessage();
          $sql->close();
          $mysqli->close();
          exit;
        }
        //回答比較と保存処理
        $match_data = [];
        $match_info = [];
        //候補者別ループ
        foreach($candidates as $c){
            $match_ratio = 0;
            $absSum = 0;
            //回答が無かった場合に判断するフラグ
            $errFlg = true;
            //質問ごとに比較
            foreach($questionnaire as $q){
                //候補者の回答を抽出
                foreach($candidate_answers as $ca){
                    //候補者IDと質問番号が一致する場合
                    if($ca[1] == $c[0] && $ca[2] == $q[0]){
                        //有権者と候補者の回答の差の絶対値を計算して加算
                        $absSum += abs($answers[$q[0]-1] - $ca[3]);
                        //詳細情報を保存
                        array_push($match_info,[$q[0],$q[1],$answers[$q[0]-1],$ca[1],$ca[3],$ca[4]]);
                        //存在フラグ
                        $errFlg = false;
                        break;
                    }
                }
                //回答がなかったらデータ不足として判断
                if($errFlg){
                    $absSum = -1;
                    break;
                }
            }
            //全ての質問の差の絶対値の差が出たら、比率を計算
            if($absSum > 0){
                $match_ratio = round((1 - ($absSum / 144)) * 100);
            } else if($absSum == 0){
                $match_ratio = 100;
            } else {
                $match_ratio = 999;
            }
            //結果をマッチ度配列に保存
            array_push($match_data,[$c[0],$c[1],$c[2],$c[3],$match_ratio]);
        }
        //結果をソート
        array_multisort(array_column($match_data, 4), SORT_DESC, $match_data);
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
  <body id="answer">
    <header id="title_head">ボートマッチングアプリ | 王の選択</header><br/>
    <div id="election_name"><?php echo $election_name; ?></div>
    <div id="election_dates">告示：<?php echo date_format(date_create_from_format('Y-m-d', $start_date),'Y年n月j日'); ?>　投開票：<?php echo date_format(date_create_from_format('Y-m-d', $election_date),'Y年n月j日'); ?></div>
    <div id="today">本日：<?php echo date("Y年n月j日"); ?></div>
    <div id="message">
    <h2>マッチング結果</h2>
    <p>「王様、しもべ（候補者）のマッチ度は下記のとおりとなりました。」</p>
    <p>「投票の参考になされてください。」</p>
    <p>「よもや、投票に行くことをお忘れなく！」</p>
    </div>
    <form method="POST" id="confirm" action="./result.php" >
        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
        <input type="hidden" name="answers" value="<?php echo htmlspecialchars($_POST["answers"]); ?>" />
        <?php for($i = 1; $i <= count($match_data); $i++): ?>
            <div class="result_set">
                <?php if($match_data[$i-1][4] !== 999): ?>
                <div class="rank_top" ><?php echo $i; ?>位　<?php echo $match_data[$i-1][2]; ?></div>
                <div class="rank_match">マッチ度 <?php echo $match_data[$i-1][4] ?>%</div>
                <input type="button" class="rank_button" id="rank_button<?php echo $i; ?>" onclick="resultInfoToggle(<?php echo $i; ?>)" value="詳細を表示" />
                <div id="result_info<?php echo $i; ?>" class="result_info">
                    <div class="match_info_row">
                    <?php for($n = 0;$n < count($answers);$n++): ?>
                        <div class="q_row">
                            <?php foreach($match_info as $inf): ?>
                                <?php if($inf[0] == $answers[$n] && $inf[3] == $match_data[$i-1][0]): ?>
                                    <p><div class="policy"><?php echo $inf[1]; ?></div>　王様：<?php echo $n+1; ?>位 / 候補者：<?php echo $inf[4]; ?>位<br/>
                                    候補者コメント：<?php if($inf[5] != "") echo "「".$inf[5]."」"; else echo "なし"; ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endfor; ?>
                    </div>
                <?php else: ?>
                <h3 class="rank_top" >-位 <?php echo $match_data[$i-1][2]; ?> / マッチ度 計測不能</h3>
                <input type="button" class="rank_button" id="rank_button<?php echo $i; ?>" onclick="resultInfoToggle(<?php echo $i; ?>)" value="詳細を表示" />
                <div id="result_info<?php echo $i; ?>">
                    <div class="match_info_row">
                        対応する回答が不足していたため計測できませんでした。
                    </div>
                <?php endif; ?>
                    <div class="candidate_info_row">
                        <input type="button" class="candidate_button" id="candidate_view_button<?php echo $i; ?>" onclick="candidateInfoToggle(<?php echo $i; ?>)" value="この候補者の情報を見る" />
                        <div class="candidate_info" id="candidate_info<?php echo $i; ?>">
                            <?php if( $match_data[$i-1][3] != ""): ?>
                                <?php  echo str_replace(["\r\n", "\r","\n"], "<br/>", $match_data[$i-1][3]); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div> 
        <?php endfor; ?>
        <div class="button_area">
            <input type="submit" class="button" id="backto_answer" formaction="./answer.php" value="選びなおす" />
            <input type="submit" class="button" id="show_election" formaction="./election.php" value="選挙に戻る" />
        </div>
    </form>
    <footer>
      <a href="./index.php"><input type="button" id="top_button" value="トップに戻る" /></a>
      <p>© 豊の国をつくる会</p>
    </footer>
  </body>
</html>
