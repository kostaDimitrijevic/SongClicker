<!--
    Mladen Mirčić 2018/0413
-->

<script>

    // Mladen Mirčić 0413/2018

    $(document).ready(function () {
        window.conn = new WebSocket('ws://localhost:8081?username=<?= session()->get('username') ?>&chosenGenre=<?= session()->get('chosenGenre') ?>');

        window.conn.onmessage = function(e) {
            let messageReceived = e.data.split("|");
            localStorage.setItem("opponent", messageReceived[0]);
            localStorage.setItem("gameId", messageReceived[1]);
            window.songs = messageReceived[2];
            window.songOrArtist = messageReceived[3];
            $("#insertable").load("<?= base_url("User/echoView/multiplayerGame") ?>");
        }

        $("#toUserMenu").click(function () {
            window.conn.close();
            $(".center").load("<?= base_url("User/echoView/userInterface") ?>");
        });
    });
</script>

<h4>Please wait while we find you a mate to compete with</h4>
<br>
<br>
<div class="spinner-border text-secondary" style="width: 5rem; height: 5rem;" role="status">
</div>
<br>
<br>
<br>
<input id="toUserMenu" class="btn btn-dark btnTransition btnRegister" type="button" value="Return to menu">