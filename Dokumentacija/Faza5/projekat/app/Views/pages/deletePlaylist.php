
<script>
    $(document).ready(function(){

        // Teodora Mijatović 0314/2018

        deletePlaylist();
        function deletePlaylist(){
            $.get("<?=base_url("PrivilegedUser/getPlaylists") ?>", function(data){
                let playlists=data.split(",");
                let arr=[];

                for(let i=0;i<playlists.length-1;i++){
                    let pl=playlists[i].split("/");

                    let s=pl[1].toString();
                    if(!s.localeCompare("easy")) pl.push(1);
                    if(!s.localeCompare("medium")) pl.push(2);
                    if(!s.localeCompare("hard")) pl.push(3);
                    arr.push(pl);

                }
                arr.sort(function (a,b){
                    if(a[0]>b[0])return -1;  //genre
                    if(b[0]>a[0]) return 1;
                    if(a[4]>b[4])return 1;   //level
                    if(a[4]<b[4]) return -1;
                    return a[2]-b[2];    //number
                })
                for(let i=0;i<arr.length;i++){
                    let option= $("<option></option");
                    let genre=arr[i][0].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    let diff=arr[i][1].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    option.attr("id", arr[i][3]);
                    option.addClass("opt");
                    option.append(genre+" "+ diff + " "+ arr[i][2]);
                    $("#playlists").append(option);
                }

            });
        }
        $("#deletePlaylist").click(function(){
            $("#error").empty();
            if($("#playlistDefault").prop("selected") === true){
                $("#error").append("You must choose playlist");
            }
            else{
                let playlists = document.getElementsByClassName("opt");
                let playlistId="";
                for(let i=0;i<playlists.length;i++) {
                    if (playlists[i].selected) {
                        playlistId = playlists[i].id;
                        break;
                    }
                }
                $.post("<?=base_url("PrivilegedUser/deletePlaylist") ?>", {
                    "idP": playlistId
                }, function() {
                    $("#change").empty().append("<br><br><h3>Playlist deleted successfully</h3>");
                });
            }
        });
    });
</script>

<div id="error" class="red">

</div>
<table class="table " >
    <tr>
        <td>Playlist:</td>
        <td>
            <select style="width: 100%" id="playlists" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option id="playlistDefault" selected>Playlist</option>
            </select>
        </td>
    </tr>
</table>
<input type="button" id="deletePlaylist" class="btn btn-dark" value="Delete playlist">