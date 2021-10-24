
<script>

    // Kosta Dimitrijević 0467/2018

    $("#playAudio").click(function () {
        window.audio.play();
    });

    $("#stopAudio").click(function () {
        window.audio.pause();
    });

    $("#backToMistake").click(function(){
        $.post("<?= base_url("PrivilegedUser/echoView/mistakeLog") ?>", function (data) {
            window.audio.pause();
            $(".center").html(data);
            $.get("<?= base_url("PrivilegedUser/getMistakes") ?>", function (data1) {

                mistakes = data1.split(',');
                for (let i=0; i < mistakes.length -1 ;i++)
                {
                    let mistake = [];
                    mistake = mistakes[i].split('/');
                    let idM = mistake[0];
                    let idS = mistake[1];
                    let row = $("<tr></tr>");
                    let col1 = $("<td></td>").append(idS).attr("style", "color : white");
                    let col2 = $("<td></td>").append($("<input>").attr("type", "button").attr("value", "delete")
                        .addClass("btn").addClass("btn-sm").addClass("btn-light"));
                    row.append(col1);
                    row.append(col2);

                    $(".mistakeLogTable").append(row);
                }
            });
        })
    });
</script>
<div class="offset-sm-2 col-sm-8 infoPart">
    <h3 id="errorLog">

    </h3>
    <table class="table table-striped table-dark songInfoTable">
        <tr>
            <thead style="text-align: center">
                <th colspan="2" style="border-bottom: 5px solid #9c1616">
                    Song info
                </th>
            </thead>
        </tr>
    </table>
    <input type="button" class="btn btn-sm btn-dark" value="Play" id="playAudio">
    <input type="button" class="btn btn-sm btn-dark" value="Stop" id="stopAudio">
    <br>
    <br>
    <input type="button" class="btn btn-dark" value="Back" id="backToMistake">
</div>