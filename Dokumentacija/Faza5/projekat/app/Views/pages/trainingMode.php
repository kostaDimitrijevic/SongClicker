<script>

    // Mladen Mirčić 0413/2018
    // Kosta Dimitrijević 0467/2018

    $(document).ready(function () {
        let songDuration;
        let currentGenreImage = 9;
        let guessed = false;
        let songToBePlayed;
        let tokensAcquired = 0;
        let usedSongs = [];
        let logo;
        let numberOfCorrectGuesses = 0;
        let mode = "<?php echo session()->get("mode"); ?>";

        function getSongsFromDatabase() {
            $.get("<?= base_url('Training/pickSongs/true'); ?>", function (data) {
                let songData = JSON.parse(data);
                songToBePlayed = songData.songToBePlayed;
                usedSongs.push(songToBePlayed.artist + ":" + songToBePlayed.name);
                let options = $(".userInterfaceForm").find(".guess");
                for (let i = 0; i < 4; i++) {
                    options[i] = $(options[i]);
                    options[i].attr("value", songData.songs[i]);
                }
                playAudio();
            });
        }

        function colorButtons() {
            let options = $(".userInterfaceForm").find(".guess");
            for (let i = 0; i < 4; i++) {
                options[i] = $(options[i]);
                options[i].toggleClass("btn-dark").prop("disabled", true);
                if (options[i].val() === songToBePlayed.name)
                    options[i].toggleClass("btn-success");
                else
                    options[i].toggleClass("btn-danger");
            }
        }

        function utilizeButtons() {
            let options = $(".userInterfaceForm").find(".guess");
            for (let i = 0; i < 4; i++) {
                options[i] = $(options[i]);
                options[i].toggleClass("btn-dark").prop("disabled", false);
                if (options[i].val() === songToBePlayed.name)
                    options[i].toggleClass("btn-success");
                else
                    options[i].toggleClass("btn-danger");
                options[i].css("border", "none");
            }
        }

        function playAudio() {
            let request = new XMLHttpRequest();
            request.open("GET", "<?= base_url("/") ?>" + "/" + songToBePlayed.path, true);
            request.responseType = "blob";
            let audio;
            request.onload = function() {
                if (this.status === 200) {
                    audio = new Audio(URL.createObjectURL(this.response));
                    audio.load();
                    audio.onloadedmetadata = function () {
                        songDuration = audio.duration;
                        audio.currentTime = Math.random() * (songDuration - 5);
                        audio.play();
                        $("#" + currentGenreImage).addClass("hiddenGenreImageForGame");
                        currentGenreImage--;
                        setTimeout(function () {
                            let vol = 1;
                            let fadeOutInterval = setInterval(function () {
                                if (vol > 0.1) {
                                    vol -= 0.1;
                                    audio.volume = vol.toFixed(1);
                                } else {
                                    clearInterval(fadeOutInterval);
                                    audio.pause();
                                }
                            }, 100);

                            if (guessed === false)
                                colorButtons();
                            setTimeout(function () {
                                $(".guess").toggle(1000);
                                setTimeout(function () {
                                    if (currentGenreImage >= 0) {
                                        guessed = false;
                                        utilizeButtons();
                                        $(".guess").toggle(1000);
                                        getSongsFromDatabase();
                                    }
                                    else {
                                        logo.toggleClass("logo logoForGame");
                                        $(".header-content")
                                                        .empty()
                                                        .append(logo)
                                                        .css("justify-content", "center");
                                        localStorage.setItem("usedSongs", usedSongs);

                                        if(mode === "train")
                                            $.post("<?= base_url('Training/saveUserTokens') ?>", { tokens: tokensAcquired });

                                        $.post("<?= base_url('Training/echoView/songList') ?>", function (data) {
                                            localStorage.setItem("numberOfGuesses", numberOfCorrectGuesses.toString());
                                            $(".center").html(data);
                                        });
                                    }

                                }, 2000);

                            }, 2000);

                        }, 5000, audio)
                    }
                }
            }
            request.send();
        }

            logo = $($(".header-content").children(".logo")).toggleClass("logo logoForGame");
            $(".userWelcome").append(logo);

            if(mode === "train") {
                let tokenSection = $("<div></div>").addClass("token-section")
                    .append($("<div></div>").append("0").attr("id", "tokens"))
                    .append($("<img>").attr("src", "<?= base_url("images/" . session()->get("chosenGenre") . "Token.png") ?>").css({"width": "15%", "height": "25%"}));
                $(".header-content")
                    .prepend($("<div></div>").append("<?= session()->get('username') ?>").css("margin-right", "30px"))
                    .append(tokenSection)
                    .css({"font-size": "20px", "font-weight": "bold", "justify-content": "space-between"});
            }
            else {
                let path = "<?php echo base_url("images/" . session()->get("chosenGenre") . ".png");?>";
                 $(".header-content")
                     .prepend($("<div></div>").append("<?= session()->get('username') ?>").css("margin-right", "30px"))
                     .append($("<div></div>").append($("<div></div>").addClass("textNumber").append("0/10")).append($("<img>").attr("src", path).attr("id", "toUnlock")).addClass("numberOfGuesses"))
                     .css({"font-size": "30px", "font-weight": "bold", "justify-content": "space-between"});
            }


            getSongsFromDatabase();

            $(".guess").click(function () {
                if ($(this).val() === songToBePlayed.name) {
                    numberOfCorrectGuesses ++;
                    $(".textNumber").empty();
                    $(".textNumber").prepend(numberOfCorrectGuesses + "/" + "10");
                    tokensAcquired += 10;
                    $("#tokens").html(tokensAcquired);
                }
                $(this).css("border", "5px solid black");
                guessed = true;
                colorButtons();
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
            <input type="button" class="btn guess btn-dark">
        </td>
    </tr>
    <tr>
        <td>
            <input type="button" class="btn guess btn-dark">
        </td>
    </tr>
    <tr>
        <td>
            <input type="button" class="btn guess btn-dark">
        </td>
    </tr>
    <tr>
        <td>
            <input type="button" class="btn guess btn-dark">
        </td>
    </tr>
</table>
