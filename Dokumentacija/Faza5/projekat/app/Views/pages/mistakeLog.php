
<script>

    // Kosta Dimitrijević 0467/2018

    $(document).ready(function (){

        $.get("<?= base_url("PrivilegedUser/getMistakes") ?>", function (data1) {
            $("#mistake").empty();
            mistakes = data1.split(',');
            for (let i=0; i<mistakes.length -1 ;i++)
            {
                let mistake = [];
                mistake = mistakes[i].split('/');
                let idM = mistake[0];
                let idS = mistake[1];
                let row = $("<tr></tr>");
                let col1 = $("<td></td>").append(idS).attr("style", "color : white");
                let col2 = $("<td></td>").append($("<input>").attr("type", "button").attr("value", "delete").addClass("deleteMistake").attr("id", idS)
                    .addClass("btn").addClass("btn-sm").addClass("btn-light"));
                row.append(col1);
                row.append(col2);

                $(".mistakeLogTable").append(row);
            }

            $("#backToMenu").click(function(){
                $(".center").load("<?php
                    if (session()->get("type") == "mod") echo base_url('Moderator/echoView/modMenu');
                    else echo base_url("Administrator/echoView/adminMenu") ?>");
            });

            $(".deleteMistake").click(function () {
                let id = this.id;
                $.post("<?= base_url("Administrator/deleteMistake") ?>",{ "idSong" : id}, function (data) {
                    $("#mistake").append("You successfully deleted logged mistake!").css({"color" : "green", "text-align" : "center"});
                });
                let td = $(this).parent();
                $($(td).parent()).remove();
            });

            $("#info").click(function () {
                let ids = $("#ids").val();
                if(ids != ""){
                    $.post("<?= base_url("PrivilegedUser/echoView/songInfo") ?>", function (data) {
                        $(".center").html(data);
                        $.post("<?= base_url('PrivilegedUser/getSongInfo') ?>"
                            ,{
                                "idS" : ids
                            }, function (data1) {
                                $("#errorLog").empty();
                                if(data1 == "mistake")
                                {
                                    $(".songInfoTable").hide();
                                    $("#playAudio").hide();
                                    $("#stopAudio").hide();
                                    $("#errorLog").append("Invalid id of song").css({"text-align" : "center", "color" : "red"});
                                }
                                else{
                                    let songInfo = [];
                                    songInfo = data1.split(",");
                                    let row1 = $("<tr></tr>");
                                    let col1 = $("<td></td>");
                                    col1.append("Song ID:");
                                    let col2 = $("<td></td>");
                                    col2.append(songInfo[0]);
                                    row1.append(col1);
                                    row1.append(col2);

                                    let row2 = $("<tr></tr>");
                                    let col21 = $("<td></td>");
                                    col21.append("Name:");
                                    let col22 = $("<td></td>");
                                    col22.append(songInfo[1]);
                                    row2.append(col21);
                                    row2.append(col22);

                                    let row3 = $("<tr></tr>");
                                    let col31 = $("<td></td>");
                                    col31.append("Artist:");
                                    let col32 = $("<td></td>");
                                    col32.append(songInfo[2]);
                                    row3.append(col31);
                                    row3.append(col32);

                                    $(".songInfoTable").append(row1);
                                    $(".songInfoTable").append(row2);
                                    $(".songInfoTable").append(row3);

                                    window.request = new XMLHttpRequest();
                                    window.request.open("GET", "<?= base_url("/") ?>" + "/" + songInfo[3], true);
                                    window.request.responseType = "blob";
                                    window.request.onload = function() {
                                        if (this.status == 200) {
                                            window.audio = new Audio(URL.createObjectURL(this.response));
                                            window.audio.load();

                                        }
                                    }
                                    request.send();
                                }

                            });
                    });
                }
            });
        });
    });
</script>


<div class="insertable">
    <br>
    <br>
    <div id="mistake">

    </div>
    <h2 style="text-align: center">Mistake log</h2>
    <div class="scroll offset-sm-3 col-sm-6">
        <h5 style="text-align: left">Song id</h5>
        <table class="table mistakeLogTable" style="text-align: right">

        </table>
    </div>
    <div class="songInfo">
        <br>
        Song ID:
        <input type="text" style="margin-left: 10px" id="ids">
        <input class="btn btn-dark btn-sm" type="button" style="margin-left: 10px" value="Get song info" id="info">
        <br>
        <br>
        <input type="button" class="btn btn-sm btn-dark" value="Return to menu" id="backToMenu">
    </div>
</div>

