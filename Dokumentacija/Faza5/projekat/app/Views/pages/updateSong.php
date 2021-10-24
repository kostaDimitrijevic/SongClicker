<script>
    $(document).ready(function (){

        // Teodora Mijatović 0467/2018

        showSongs();

        function showSongs() {
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
       $("#songs").on("change", function () {
           $("#text1").empty();
           $(".toHide").prop("hidden", true);
       });
        $("#playlists").on("change", function () {
            $("#text1").empty();
            $(".toHide").prop("hidden", true);
            if($("#playlistDefault").prop("selected") === true) {
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

        $(".btnEdit").click(function () {
            localStorage.setItem("toChange", this.id);
            let selectedSong= $("option:selected", $("#songs"));
            let selectedPlaylist= $("option:selected", $("#playlists"));
            if(selectedSong.attr("id")==="songDefault" || selectedPlaylist.attr("id")==="playlistDefault"){
                $("#text1").empty();
                $("#text1").append("<h4>You must choose song</h4>");
            }
            else{
                $("#text1").empty();
                $("#text").empty().append("Enter new "+ this.id + " name:").prop("hidden", false);
                $("#newName").prop("hidden", false);
                $("#newName").val("");
                $("#updateOk").prop("hidden", false).prop("disabled", true).addClass("btn btn-dark btn-sm");

            }
        });

        $("#newName").on("input",function () {
            if (this.value===""){
                $("#updateOk").prop("disabled", true);
            }
            else
                $("#updateOk").prop("disabled", false);
        });

        $("#updateOk").click(function() {
            let songId=$("option:selected", $("#songs")).attr("id");
            $.post("<?=base_url("PrivilegedUser/updateSong") ?>", {
                "toChange":localStorage.getItem("toChange"),
                "songId": songId,
                "name": $("#newName").val()
            },function (data){
                $("#text1").empty().append("Change made successfully");
                $(".toHide").prop("hidden", true);
            });
        });

    });
</script>

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
            <select  style="width: 100%" id="songs" class=" form-select  form-select-lg mb-3" aria-label=".form-select-lg example">
                <option id="songDefault" selected>Song</option>
            </select>
        </td>
    </tr>
</table>

<table class="table " style="text-align: center;">
    <tr>
        <td class="borderless">
            <input class="btn btn-sm btn-dark btnEdit" id="name" type="button" value="Edit name">
        </td>
        <td class="borderless">
            <input class="btn btn-sm btn-dark btnEdit" id="performer" type="button" value="Edit performer">
        </td>
    </tr>
</table>
<div id="text1"></div>
<table class="table">
    <tr>
        <td class="borderless">
            <div class="toHide" id="text"></div>
        </td>
        <td class="borderless">
            <input class="toHide" type="text" hidden id="newName">
        </td>
        <td class="borderless">
            <input class="toHide" type="button" id="updateOk" class="btn btn-sm btn-dark" value="Ok" hidden>
        </td>
    </tr>
</table>




