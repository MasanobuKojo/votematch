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
