
<script>

    // Kosta Dimitrijević 0467/2018
    // Mladen Mirčić 0413/2018

    $(document).ready(function () {
        $("#selectGenre").click(function () {
            toggleOtherButtons($(this));
            setTimeout(function () {
                $.get("<?= base_url("User/echoView/selectGenreToPlay/selectAvailableGenresForUser") ?>", function (data) {
                    $("#insertable").html(data);
                    $(".center").append("<input type='submit' id='confirmGenre' class='btn btn-dark btnRegister btnTransition' value='Choose' disabled><br>");
                    $(".center").append("<input type='submit' id='return' class='btn btn-dark btnRegister btnTransition' value='Return to menu'>");
                });
            }, 500)
        });

        $("#training").click(function () {
            toggleOtherButtons($(this));
            setTimeout(function () {
                $(".center").load("<?= base_url("User/echoView/training") ?>");
            }, 500)
        });

        $("#leaderboards").click(function (){
            toggleOtherButtons($(this));
            setTimeout(function () {
                $(".center").load("<?=base_url('User/echoView/leaderboards')?>");
            }, 500)
        });

        $("#quit").click(function () {
            toggleOtherButtons($(this));
            setTimeout(function () {
                $(".center").load("<?= base_url("User/echoView/quit") ?>");
            }, 500)
        });

        $("#playlists").click(function () {
            toggleOtherButtons($(this));
            setTimeout(function () {
                $(".center").load("<?= base_url("User/echoView/genresAndPlaylists") ?>");
            }, 500)
        });
        
        function toggleOtherButtons(clicked) {
            let buttons = $(".userInterfaceForm").find(".btnTransition");
            for (let i = 0; i < buttons.length; i++) {
                buttons[i] = $(buttons[i]);
                if (clicked.attr("id") !== buttons[i].attr("id"))
                    buttons[i].addClass("btnDisappear");
            }
            clicked.css({ "width": "100%", "background-color": "#721817" });
        }

    });
</script>

<div id="insertable">
    <table class="table userInterfaceForm">
        <tr>
            <td>
                <input class="btn btn-dark btnTransition" type="button" value="Search for opponents" id="selectGenre">
            </td>
        </tr>
        <tr>
            <td>
                <input class="btn btn-dark btnTransition" type="submit" value="Genres & playlists" id="playlists">
            </td>
        </tr>
        <tr>
            <td>
                <input class="btn btn-dark btnTransition" type="submit" value="Training" id="training">
            </td>
        </tr>
        <tr>
            <td>
                <input class="btn btn-dark btnTransition" type="submit" id="leaderboards" value="Leaderboards">
            </td>
        </tr>
        <tr>
            <td>
                <input class="btn btn-dark btnTransition" type="submit" value="Quit" id="quit">
            </td>
        </tr>
    </table>
</div>
