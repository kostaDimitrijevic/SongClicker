<!--
    Mladen Mirčić 2018/0413
-->

<script>
    $(document).ready(function () {

        // Mladen Mirčić 0413/2018

        $(".popover").remove();
        let myself = JSON.parse(localStorage.getItem("myself"));
        let opponent = JSON.parse(localStorage.getItem("opponent"));

        localStorage.removeItem("myself");
        localStorage.removeItem("opponent");

        $("#myName").append(myself.username).css("color", "blue");
        $("#opponentName").append(opponent.username).css("color", "red");

        if (myself.points > opponent.points)
            $("#winner1").append($("<img id='winnerImage' alt=''>").attr("src", "<?= base_url("images/winnerCup.png") ?>"));
        else
            $("#winner2").append($("<img id='winnerImage' alt=''>").attr("src", "<?= base_url("images/winnerCup.png") ?>"));
        else {
            $("#winner1").append($("<img id='winnerImage' alt=''>").attr("src", "<?= base_url("images/winnerCup.png") ?>"));
            $("#winner2").append($("<img id='winnerImage' alt=''>").attr("src", "<?= base_url("images/winnerCup.png") ?>"));
        }

        $("#myPoints").append("Points: " + myself.points);
        $("#opponentPoints").append("Points: " + opponent.points);

        $("#myTokens").append($("<img alt=''>").attr("src", "<?= base_url("images/" . session()->get("chosenGenre") . "Token.png") ?>").css({"width": "50px", "height": "40px"}))
                      .append((myself.points > 0 ? myself.points : 0) * 10);

        $("#opponentTokens").append($("<img alt=''>").attr("src", "<?= base_url("images/" . session()->get("chosenGenre") . "Token.png") ?>").css({"width": "50px", "height": "40px"}))
                            .append((opponent.points > 0 ? opponent.points : 0) * 10);

        $.post("<?= base_url("User/savePointsAndTokens") ?>", {
            points: myself.points,
            tokens: (myself.points > 0 ? myself.points : 0) * 10
        });

        $("#toSongList").click(function () {
            $(".center").load("<?= base_url("User/echoView/songList") ?>");
        });

        $("#back").click(function () {
            $(".userWelcome").html("Welcome,<br> <b><?= session()->get('username') ?></b>");
            $(".center").load("<?= base_url("User/echoView/userInterface") ?>");
        });
    });
</script>

<table class="table">
    <tr>
        <td colspan="2" style="font-size: 30px; font-weight: bolder; text-align: center" class="borderless">
            Result
        </td>
    </tr>
    <tr>
        <td style=" text-align: center; width: 50%" class="borderless">
            <div id="winner1">

            </div>
        </td>
        <td style="text-align: center; width: 50%" class="borderless">
            <div id="winner2">

            </div>
        </td>
    </tr>
    <tr>
        <td style="font-size: 20px; font-weight: bold; text-align: center; width: 50%" class="borderless">
            <div id="myName">

            </div>
        </td>
        <td style="font-size: 20px; font-weight: bold; text-align: center; width: 50%" class="borderless">
            <div id="opponentName">

            </div>
        </td>
    </tr>
    <tr>
        <td style="font-size: 20px; font-weight: bold; text-align: center; width: 50%" id="myPoints" class="borderless">

        </td>
        <td style="font-size: 20px; font-weight: bold; text-align: center; width: 50%" id="opponentPoints" class="borderless">

        </td>
    </tr>
    <tr>
        <td style="font-size: 20px; font-weight: bold; text-align: center; width: 50%" id="myTokens" class="borderless">

        </td>
        <td style="font-size: 20px; font-weight: bold; text-align: center; width: 50%" id="opponentTokens" class="borderless">

        </td>
    </tr>
    <tr>
        <td style="text-align: center;" colspan="2" class="borderless">
            <input id="toSongList" type="button" class="btn btn-dark" value="See all played songs">
        </td>
    </tr>
    <tr>
        <td style="text-align: center;" colspan="2" class="borderless">
            <input id="back" type="button" class="btn btn-dark" value="Return to menu">
        </td>
    </tr>
</table>