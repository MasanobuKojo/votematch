function appDescriptionToggle(){
    const title = document.getElementById("information_head");
    const info = document.getElementById("information_contents");
    if(info.style.display == 'none'){
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}


function electionInfoToggle(){
    const title = document.getElementById("show_election_information");
    const info = document.getElementById("election_info");
    if(info.style.display == 'none'){
        title.value = "選挙の情報を閉じる";
        info.style.display = 'block';
    } else {
        title.value = "選挙の情報を見る";
        info.style.display = 'none';
    }
}

function ShowElection(i){
    $("#election_id").val(i);
    $("#form").submit(); 
 }

function changeOption(i){
    //前の選択肢を解除
    const fv = $('#selected' + i).val();
    if(fv > 0){
        for(n=0;n<$('select').length;++n){
            $('select')[n].options[fv].disabled = false;
        }
    }
    //選択値を取得
    const op = $('select')[i-1].selectedIndex;
    //選択されており、インデックスが0でない場合
    if(op > 0){
        //全ての回答を取得
        //選択されたものをdisabledに
        for(n=0;n<$('select').length;++n){
            //選択したボックスは除外
            if(n != i-1){
                //選択肢から除外
                $('select')[n].options[op].disabled = true;
            }
        }
    } 
    $('#selected' + i).val(op);
}

function confirm(){
    //入力チェック
    //全ての回答を取得
    const selects = $('select');
    flg = false;
    //チェック１：未選択はないか
    for(i=0;i<selects.length;++i){
        if(selects[i].value == ''){
            alert('未選択の順位があります。必ず選択してください。');
            selects[i].focus();
            flg = true;
            return;
        }
    }
    //チェック２：重複はないか
    for(i=0;i<selects.length;++i){
        for(n=0;n<selects.length;++n){
            if(i != n && selects[i].value == selects[n].value){
                alert('重複している分野があります。重複しないように選択してください。');
                selects[n].focus();
                flg = true;
                return;
            }
        }
    }
    if(flg == false){
    //チェックOKなら回答配列を作成
        answer_list = "";
        for(i=0;i<selects.length-1;++i){
            answer_list += selects[i].value + ",";
        }
        answer_list += selects[selects.length-1].value;
        //値をinputに転記
        $('#answers').val(answer_list);
        //確認画面へポスト
        $("#questionnaire").submit(); 
    }
}

function resultInfoToggle(i){
    const title = document.getElementById("rank_button" + i);
    const info = document.getElementById("result_info" + i);
    if(info.style.display == 'none'){
        title.value = "詳細を閉じる";
        info.style.display = 'block';
    } else {
        title.value = "詳細を表示";
        info.style.display = 'none';
    }
}

function candidateInfoToggle(i){
    const title = document.getElementById("candidate_view_button" + i);
    const info = document.getElementById("candidate_info" + i);
    if(info.style.display == 'none'){
        title.value = "この候補者の情報を閉じる";
        info.style.display = 'block';
    } else {
        title.value = "この候補者の情報を見る";
        info.style.display = 'none';
    }
}
