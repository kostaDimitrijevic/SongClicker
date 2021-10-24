<script>
    $(document).ready(function (){

        // Mladen Mirčić 0413/2018

        $("#update").click(function(){
            $(".center").load("<?=base_url('Moderator/echoView/insertDelete')?>");
        });

        $("#mistakeLog").click(function(){
            $.get("<?= base_url("Moderator/echoView/mistakeLog") ?>", function (data) {
                $(".center").html(data);
                $.get("<?= base_url("PrivilegedUser/getMistakes") ?>", function (data1){

                    let mistakes;
                    mistakes = data1.split(',');
                    for (let i = 0; i < mistakes.length - 1; i++)
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
            });
        });

        $("#quit").click(function () {
            $(".center").load("<?= base_url("Moderator/echoView/quit") ?>");
        });

        $("#leaderboards").click(function () {
            $(".center").load("<?= base_url("Moderator/echoView/leaderboardsPrivileged") ?>");
        });
    });
</script>

<table class="table modMenu" style="margin-top: 20px; text-align: center">
    <tr>
        <td>
            <input class="btn btn-dark btnTransition" type="submit" id="leaderboards" value="Leaderboards">
        </td>
    </tr>
    <tr>
        <td>
            <input class="btn btn-dark btnTransition" type="submit" id="update" value="Update">
        </td>
    </tr>
    <tr>
        <td>
            <input class="btn btn-dark btnTransition" type="submit" value="Mistakes Log" id="mistakeLog">
        </td>
    </tr>
    <tr>
        <td>
            <input class="btn btn-dark btnTransition" type="submit" value="Quit" id="quit">
        </td>
    </tr>
</table>
