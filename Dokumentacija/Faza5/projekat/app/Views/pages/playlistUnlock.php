
<script>

    // Kosta Dimitrijević 0467/2018
    // Teodora Mijatović 0314/2018

    $(document).ready(function () {
        $.post("<?= base_url("User/getPlaylists") ?>",{chosenGenre : localStorage.getItem("chosenGenre")}, function (data) {
            localStorage.setItem("playlists", data);
            let numberOfTokens;
            let genre = localStorage.getItem("chosenGenre");
            $.post("<?= base_url("User/getMyTokens") ?>", {chosenGenre : genre}, function (data) {
                numberOfTokens = data;
                $("#head").append("Available playlists in " + genre);
                $("#tokens").append("TOKENS: " + numberOfTokens).append($("<img>").attr("src", "images/" + genre + "Token.png").css("width" , "12%"));

            })

            drawPlaylistTable();
            $('[data-toggle="popover"]').popover();

            function drawPlaylistTable() {
                let playlists = localStorage.getItem("playlists").split("/");
                let arr = [];

                for(let i=0;i<playlists.length-1;i++){
                    let pl=playlists[i].split(",");

                    let s=pl[0].toString();
                    if(!s.localeCompare("easy")) pl.push(1);
                    if(!s.localeCompare("medium")) pl.push(2);
                    if(!s.localeCompare("hard")) pl.push(3);
                    arr.push(pl);
                }

                arr.sort(function (a,b) {
                    if(a[5]>b[5])return 1;  //level
                    if(a[5]<b[5]) return -1;
                    return a[1]-b[1];  //number
                })

                for(let i = 0; i < arr.length; i++){

                    let row = $("<tr></tr>");

                    let col1 = $("<td></td>").css("width", "30%");
                    let col2 = $("<td></td>").css({"width" : "30%", "text-align" : "center"});
                    let col3 = $("<td></td>").css({"width" : "30%", "text-align" : "center"});

                    let playlist=arr[i][0].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });

                    col1.append($("<img>").attr("src", "images/playlist.png").css("width", "30%"));
                    col1.append(" " + playlist + " " + arr[i][1]);

                    col2.append(arr[i][2]);
                    col2.append($("<img>").attr("src", "images/" + genre + "Token.png").css("width", "25%"));

                    if(arr[i][4] == "locked"){
                        col3.append($("<img>").attr("src", "images/locked.png").css("width", "25%").addClass("toUnlock").attr("id", arr[i][3]).attr("data-container", "body").attr("data-toggle", "popover").attr("data-trigger", "hover").attr("data-placement", "bottom").attr("data-content", "Click to unlock"));
                    }
                    else{
                        col3.append($("<img>").attr("src", "images/unlocked.png").css({"width" : "28%", "margin-right" : "-5px"}));
                    }

                    row.append(col1);
                    row.append(col2);
                    row.append(col3);

                    $(".playlistTable").append(row);
                }

            }

            $(".toUnlock").click(function () {
                localStorage.setItem("idPlaylist", this.id);
                localStorage.setItem("numOfTokens", numberOfTokens);
                $(".center").load("<?= base_url("User/echoView/confirmPurchase") ?>");
            });
        });

        $("#return").click(function () {
            localStorage.clear();
            $(".center").load("<?= base_url("User/echoView/genresAndPlaylists") ?>");
        });
    })
</script>


<br>
<h3 id="head"></h3>
<div id="tokens" style="text-align: center; font-size: 20px; font-weight: bold">

</div>
<br>
<div class="scroll" style="margin: auto; background-color: #353943; width: 80%">
    <table class="table playlistTable table-dark">

    </table>
</div>
<br>
<br>
<input type="button" class="btn btn-dark btnTransition btnRegister" value="Back" id="return">
