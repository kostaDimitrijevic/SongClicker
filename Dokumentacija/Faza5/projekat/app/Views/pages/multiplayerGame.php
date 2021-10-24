<!--
    Mladen Mirčić 2018/0413
    Kosta Dimitrijević 2018/0467
    Teodora Mijatović 2018/0314
-->

<script>
    $(document).ready(function () {

        // Mladen Mirčić 0413/2018

        const timerFill = "       ";

        let myself;
        let opponent;
        let songDuration;
        let currentGenreImage = 9;
        let guessed = false;
        let songToBePlayed;
        let tokensAcquired = 0;
        let usedSongs = [];
        let logo;
        let myTime;
        let myTimer;
        let songData;
        let selected = false;
        let opponentTime = -1;
        let audio;
        let myPoints = 0;

        let gameId = localStorage.getItem("gameId");
        let opponentUsername = localStorage.getItem("opponent");
        let opponentPoints = 0;

        let button = $("<input>").attr("type", "button").attr("value", "Report a mistake").addClass("btn").addClass("btn-warning").addClass("btn-sm").attr("id", "report").css({"margin-right" : "10%" , "margin-top" : "50%"});
        $(".optional").append(button);

        $("#report").click(function () {
            $(this).attr("disabled", true);
            $.post("<?= base_url("User/reportMistake") ?>", {idSong : songToBePlayed.idS}, function () {
                $(".optional").append($("<div></div>").append("Mistake reported").attr("id", "reported").css({"color" : "red", "text-align" : "center", "margin-right" : "10%"}));
            })
        });

        function LoadSongsAndPlayAudio() {
            songData = JSON.parse(window.songs);
            songToBePlayed = songData.songToBePlayed;
            usedSongs.push(songToBePlayed.artist + ":" + songToBePlayed.name);
            let options = $(".userInterfaceForm").find(".guess");
            for (let i = 0; i < 4; i++) {
                options[i] = $(options[i]);
                options[i].attr("value", songData.songs[i]);
            }
            myTime = 0;
            myTimer = setInterval(function () {
                myTime += 0.01;
            }, 10)
            playAudio();
        }

        function colorButtons() {
            let options = $(".userInterfaceForm").find(".guess");
            for (let i = 0; i < 4; i++) {
                options[i] = $(options[i]);
                options[i].toggleClass("btn-dark").prop("disabled", true);
                if (window.songOrArtist >= 50) {
                    if (options[i].val() === songToBePlayed.name)
                        options[i].toggleClass("btn-success");
                    else
                        options[i].toggleClass("btn-danger");
                }
                else {
                    if (options[i].val() === songToBePlayed.artist)
                        options[i].toggleClass("btn-success");
                    else
                        options[i].toggleClass("btn-danger");
                }

            }
        }

        function borderAndTimeBothAnswers() {
            let options = $(".userInterfaceForm").find(".guess");
            for (let i = 0; i < 4; i++) {
                options[i] = $(options[i]);
                if (options[i].hasClass("myAnswer") && options[i].hasClass("opponentAnswer")) {
                    options[i].css(
                        {
                            "border-left": "5px solid blue",
                            "border-bottom": "5px solid blue",
                            "border-top": "5px solid red",
                            "border-right": "5px solid red"
                        });
                }
                else if (options[i].hasClass("opponentAnswer"))
                    options[i].css("border", "5px solid red");
            }

            if (opponentTime !== -1) {
                if ($(".myAnswerParent").hasClass("opponentAnswerParent"))
                    $(".myAnswerParent").attr("time-after", opponentTime.toFixed(2));
                else {
                    $(".opponentAnswerParent").attr("time-before", timerFill);
                    $(".opponentAnswerParent").attr("time-after", opponentTime.toFixed(2));
                }
            }
        }

        function utilizeButtons() {
            let options = $(".userInterfaceForm").find(".guess");
            for (let i = 0; i < 4; i++) {
                options[i] = $(options[i]);
                options[i].removeClass("opponentAnswer myAnswer");
                options[i].toggleClass("btn-dark").prop("disabled", false);
                if (window.songOrArtist >= 50) {
                    if (options[i].val() === songToBePlayed.name)
                        options[i].toggleClass("btn-success");
                    else
                        options[i].toggleClass("btn-danger");
                }
                else {
                    if (options[i].val() === songToBePlayed.artist)
                        options[i].toggleClass("btn-success");
                    else
                        options[i].toggleClass("btn-danger");
                }
                options[i].css("border", "none");
            }
        }

        function playAudio() {
            let request = new XMLHttpRequest();
            request.open("GET", "<?= base_url("/") ?>" + "/" + songToBePlayed.path, true);
            request.responseType = "blob";
            request.onload = function() {
                if (this.status === 200) {
                    audio = new Audio(URL.createObjectURL(this.response));
                    audio.load();
                    audio.onloadedmetadata = function () {
                        songDuration = audio.duration;
                        audio.currentTime = songData.randomTime * (songDuration - 5);
                        audio.play();
                        $("#" + currentGenreImage).addClass("hiddenGenreImageForGame");
                        currentGenreImage--;
                        setTimeout(function () {
                            window.conn.send("sendPoints|" + gameId);
                            if (myself.hasClass("selectedGenre")) {
                                myself.addClass("deselectedGenre");
                                myself.removeClass("selectedGenre");
                            }
                            if (opponent.hasClass("selectedGenre")) {
                                opponent.addClass("deselectedGenre");
                                opponent.removeClass("selectedGenre");
                            }

                            clearInterval(myTimer);

                            let vol = 1;
                            let fadeOutInterval = setInterval(function () {
                                if (vol > 0.1) {
                                    vol -= 0.1;
                                    audio.volume = vol.toFixed(1);
                                } else {
                                    clearInterval(fadeOutInterval);
                                    audio.pause();
                                }
                            }, 10);

                            colorButtons();
                            borderAndTimeBothAnswers();
                            setTimeout(function () {
                                $(".myAnswerParent")
                                    .attr("time-before", "")
                                    .attr("time-after", "")
                                    .removeClass("myAnswerParent");
                                $(".opponentAnswerParent")
                                    .attr("time-before", "")
                                    .attr("time-after", "")
                                    .removeClass("opponentAnswerParent");

                                $(".guess").toggle(1000);
                                setTimeout(function () {
                                    if (currentGenreImage >= 0) {
                                        guessed = false;
                                        utilizeButtons();
                                        myself.removeClass("deselectedGenre");
                                        opponent.removeClass("deselectedGenre");
                                        window.conn.send("endOfRound|" + gameId);
                                        $(".guess").toggle(1000);
                                        selected = false;
                                        opponentTime = -1;
                                    }
                                    else {
                                        window.conn.close();
                                        logo.toggleClass("logo logoForGame");
                                        $(".header-content")
                                                            .empty()
                                                            .append(logo)
                                                            .css("justify-content", "center");

                                        localStorage.setItem("usedSongs", usedSongs.toString());
                                        localStorage.setItem("myself", JSON.stringify({
                                            username: "<?= session()->get("username") ?>",
                                            points: myPoints
                                        }));

                                        localStorage.setItem("opponent", JSON.stringify({
                                            username: opponentUsername,
                                            points: opponentPoints
                                        }));
                                        $(".optional").empty();
                                        $(".center").load("<?= base_url("User/echoView/endGameScreen") ?>");
                                    }

                                }, 2000);

                            }, 2000);

                        }, 5000, audio)
                    }
                }
            }
            request.send();
        }

        window.conn.onmessage = function (e) {
            let messageReceived = e.data.split("|");
            switch (messageReceived[0]) {
                case "answered": {
                    opponentTime = parseFloat(messageReceived[1]);
                    $("#" + messageReceived[2]).addClass("opponentAnswer");
                    $($("#" + messageReceived[2]).parent()).addClass("opponentAnswerParent");
                    opponent.addClass("selectedGenre");
                    break;
                }
                case "points": {
                    let forMe = parseInt(messageReceived[1]);
                    let forOpponent = parseInt(messageReceived[2]);
                    myPoints += forMe;
                    opponentPoints += forOpponent;
                    let signForMe = forMe > 0 ? "+" : "";
                    let signForOpponent = forOpponent > 0 ? "+" : "";
                    myself.attr("data-content", signForMe + messageReceived[1] + " pts").popover("show");
                    opponent.attr("data-content", signForOpponent + messageReceived[2] + " pts").popover("show");
                    setTimeout(function () {
                        myself.popover("hide");
                        opponent.popover("hide");
                    }, 3000, myself, opponent);
                    break;
                }
                case "newRound": {
                    $("#reported").empty();
                    $("#report").attr("disabled", false);
                    window.songs = messageReceived[1];
                    window.songOrArtist = messageReceived[2];
                    LoadSongsAndPlayAudio();
                    break;
                }
                case "playerLeft": {
                    window.conn.close();
                    audio.pause();
                    $(".popover").remove();
                    $(".optional").empty();
                    logo.toggleClass("logo logoForGame");

                    $(".header-content")
                        .empty()
                        .append(logo)
                        .css("justify-content", "center");
                    $(".userWelcome").html("Welcome,<br> <b><?= session()->get('username') ?></b>");
                    $(".center").load("<?= base_url("User/echoView/userInterface") ?>");
                    break;
                }
            }
        }

        myself = $("<div></div>")
                                .append("<?= session()->get('username') ?>")
                                .css({"margin-right": "30px", "color": "blue", "padding": "1%"})
                                .addClass("borderTransition")
                                .attr("data-container", "body")
                                .attr("data-toggle", "popover")
                                .attr("data-placement", "bottom")
                                .attr("data-content", "");

        opponent = $("<div></div>")
                                .append(opponentUsername)
                                .css({"margin-right": "30px", "color": "red", "padding": "1%"})
                                .addClass("borderTransition")
                                .attr("data-container", "body")
                                .attr("data-toggle", "popover")
                                .attr("data-placement", "bottom")
                                .attr("data-content", "");

        logo = $($(".header-content").children(".logo")).toggleClass("logo logoForGame");
        $(".userWelcome").html(logo);

        $(".header-content")
            .prepend(myself)
            .append(opponent)
            .css({"font-size": "20px", "font-weight": "bold", "justify-content": "space-between"});

        $('[data-toggle="popover"]').popover().click(function () {
            $(this).popover("hide");
        });

        localStorage.clear();
        LoadSongsAndPlayAudio();

        $(".guess").click(function () {
            if (selected === false) {
                selected = true
                clearInterval(myTimer);

                $($(this).parent()).addClass("myAnswerParent");
                $(".myAnswerParent")
                    .attr("time-before", myTime.toFixed(2))
                    .attr("time-after", timerFill);

                myself.addClass("selectedGenre");
                let isCorrect;
                if (window.songOrArtist >= 50)
                    isCorrect = $(this).val() === songToBePlayed.name ? 1 : 0;
                else
                    isCorrect = $(this).val() === songToBePlayed.artist ? 1 : 0;
                window.conn.send("answered|" + gameId + "|" + myTime.toFixed(2) + "|" + $(this).attr("id") + "|" + isCorrect);
                $(this).addClass("myAnswer").css("border", "5px solid blue");
            }
        });
    });
</script>

<div class="imagesForGame">
    <?php
    for ($i = 0; $i < 10; $i++) {
        $path = base_url("images/" . session()->get("chosenGenre") . ".png");
        echo "<img id='$i' class='genreImageForGame' src='$path' alt=''>";
    }
    ?>
</div>

<table class="table userInterfaceForm">
    <tr>
        <td>
            <input id="answer1" class='btn btn-dark guess' type='button' value=''>
        </td>
    </tr>
    <tr>
        <td>
            <input id="answer2" class='btn btn-dark guess' type='button' value=''>
        </td>
    </tr>
    <tr>
        <td>
            <input id="answer3" class='btn btn-dark guess' type='button' value=''>
        </td>
    </tr>
    <tr>
        <td>
            <input id="answer4" class='btn btn-dark guess' type='button' value=''>
        </td>
    </tr>
</table>