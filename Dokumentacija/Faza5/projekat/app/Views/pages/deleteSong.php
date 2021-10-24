<script>
    $(document).ready(function () {

        // Teodora Mijatović 0314/2018

        deleteSong();

        function deleteSong() {
            $.post("<?=base_url("PrivilegedUser/getPlaylists") ?>", function (data) {
                let playlists = data.split(",");
                let arr = [];

                for (let i = 0; i < playlists.length - 1; i++) {
                    let pl = playlists[i].split("/");

                    let s = pl[1].toString();
                    if (!s.localeCompare("easy")) pl.push(1);
                    if (!s.localeCompare("medium")) pl.push(2);
                    if (!s.localeCompare("hard")) pl.push(3);
                    arr.push(pl);

                }
                arr.sort(function (a, b) {
                    if (a[0] > b[0]) return -1;  //genre
                    if (b[0] > a[0]) return 1;
                    if (a[4] > b[4]) return 1;  //level
                    if (a[4] < b[4]) return -1;
                    return a[2] - b[2];     //number
                })
                for (let i = 0; i < arr.length; i++) {
                    let option = $("<option></option");
                    let genre = arr[i][0].toLowerCase().replace(/\b[a-z]/g, function (letter) {
                        return letter.toUpperCase();
                    });
                    let diff = arr[i][1].toLowerCase().replace(/\b[a-z]/g, function (letter) {
                        return letter.toUpperCase();
                    });
                    option.attr("id", arr[i][0] + "/" + arr[i][1] + "/" + arr[i][2]);
                    option.addClass("optPlaylist");
                    option.append(genre + " " + diff + " " + arr[i][2]);
                    $("#playlists").append(option);
                }

            });
        }

        $("#playlists").on("change", function () {
            if($("#playlistDefault").prop("selected") === true){
                $("#songs").empty().append("<option id='songDefault' selected>Song</option>");
            }
            else {
                let optionSelected = $("option:selected", this);
                let pl = optionSelected.attr("id");
                let arr = pl.split("/");
                $.post("<?=base_url("PrivilegedUser/getSongs") ?>", {
                    "genre": arr[0],
                    "difficulty": arr[1],
                    "number": arr[2]
                }, function (data) {
                    let songs = data.split(",");
                    songs.pop();
                    $("#songs").empty().append("<option id='songDefault' selected>Song</option>");
                    for (let i = 0; i < songs.length; i++) {
                        nameArtist = songs[i].split("/");
                        let option = $("<option></option>");

                        option.attr("id", nameArtist[2]);
                        option.addClass("optSong");
                        option.append(nameArtist[1] + " - " + nameArtist[0]);
                        $("#songs").append(option);
                    }
                });
            }
        });

        $("#deleteSong").click(function(){
            $("#error").empty();
            if($("#playlistDefault").prop("selected") === true){
                $("#error").append("You must choose playlist");
            }
            else if($("#songDefault").prop("selected") === true){
                $("#error").append("You must choose song");
            }
            else{
                let songs = document.getElementsByClassName("optSong");
                let songId="";
                for(let i=0;i<songs.length;i++) {
                    if (songs[i].selected) {
                        songId = songs[i].id;
                        break;
                    }
                }
                $.post("<?=base_url("PrivilegedUser/deleteSong") ?>", {
                    "idS": songId
                }, function(data) {
                    $("#change").empty().append("<br><br><h3>Song deleted successfully</h3>");


                });
            }
        });
    });
</script>
<div id="error" class="red">

</div>
<table class="table" >
    <tr>
        <td>Playlist:</td>
        <td>
            <select style="width: 100%" id="playlists" class="form-select  form-select-lg mb-3" aria-label=".form-select-lg example">
                <option id="playlistDefault" selected>Playlist</option>

            </select>
        </td>
    </tr>
    <tr>
        <td>Song:</td>
        <td>
            <select style="width: 100%" id="songs" class=" form-select  form-select-lg mb-3" aria-label=".form-select-lg example">
                <option id="songDefault" selected>Song</option>
            </select>
        </td>
    </tr>
</table>
<input type="button" id="deleteSong" class="btn btn-dark" value="Delete song">