
<script>

    // Kosta Dimitrijević 0467/2018
    // Teodora Mijatović 0314/2018

    $(document).ready(function () {
        let numOfTokens = localStorage.getItem("numOfTokens");
        $(".popover").remove();
        $.post("<?= base_url("User/getPlaylistById") ?>",{idP : localStorage.getItem("idPlaylist")}, function (data) {
            let playlist = data.split("/");
            if(numOfTokens < parseInt(playlist[1])){
                $("#head").append("You don't have enough money to buy the playlist!");
                $(".buttonsConfirm").empty().append("<input type='button' value='OK' class='btn btn-dark' id='ok'>");
                $("#ok").click(function () {
                    $.get("<?= base_url("User/echoView/playlistUnlock") ?>", function (data) {
                        $(".center").html(data);
                    })
                });
            }
            else{
                $("#head").append("Are you sure you want to buy playlist " + playlist[0] + "?");
                $("#price").append("Price: " + playlist[1]).append($("<img>").attr("src", "images/" + localStorage.getItem("chosenGenre") + "Token.png").css("width","10%"));
            }
        });

        $("#yes").click(function () {
            let genre = localStorage.getItem("chosenGenre");
            $.post("<?= base_url("User/buyPlaylist") ?>",{
                    genre : genre,
                    idP : localStorage.getItem("idPlaylist")
                },
                function (data) {
                $("#head").empty().append("You have successfully unlocked the playlist!");
                $("#price").empty();
                $(".buttonsConfirm").empty().append("<input type='button' value='OK' class='btn btn-dark' id='ok'>");
                $("#ok").click(function () {
                    $.get("<?= base_url("User/echoView/playlistUnlock") ?>", function (data) {
                        $(".center").html(data);
                    })
                });
            });
        });

        $("#no").click(function () {
            $(".center").load("<?= base_url("User/echoView/playlistUnlock") ?>");
        });
    });
</script>

<br>
<br>
<h3 id="head"></h3>
<br>
<h4 id="price">

</h4>
<div class="buttonsConfirm">
    <input type="button" value="YES" class="btn btn-dark" id="yes">
    <input type="button" value="NO" class="btn btn-dark" id="no">
</div>