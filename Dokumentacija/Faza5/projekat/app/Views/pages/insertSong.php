<script>
    $(document).ready(function (){

        // Teodora Mijatović 0314/2018

        insertSong();
        function insertSong(){
            $.post("<?= site_url('PrivilegedUser/getPlaylists')?>", function(data){
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
                    if(a[0]>b[0])return -1;  //name
                    if(b[0]>a[0]) return 1;
                    if(a[4]>b[4])return 1;  //level
                    if(a[4]<b[4]) return -1;
                    return a[2]-b[2];  //number
                })
                for(let i=0;i<arr.length;i++){
                    let option= $("<option></option");
                    let genre=arr[i][0].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    let diff=arr[i][1].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    option.attr("id", arr[i][0]+"/"+ arr[i][1] + "/"+ arr[i][2]);
                    option.addClass("opt");
                    option.append(genre+" "+ diff + " "+ arr[i][2]);
                    $("#playlists").append(option);
                }

            });


        }
        $("#insertSong").click(function(){
            $("#error").empty();
            let name=$("#name").val();
            let performer=$("#performer").val();
           
            if(name==""){
                $("#error").append("You must enter name");
            }
            else if(performer==""){
                $("#error").append("You must enter performer");
            }
            else if($("#genreDefault").prop("selected")==true){
                $("#error").append("You must select playlist");
            }
            else{

                let playlists = document.getElementsByClassName("opt");
                let pl="";
                for(let i=0;i<playlists.length;i++) {
                    if (playlists[i].selected) {
                        pl = playlists[i].id;
                        break;
                    }
                }
                let plArr=pl.split("/");
                let location="music/"+plArr[0]+ "/"+plArr[1]+"-"+plArr[2]+"/"+performer+" - "+name+".mp3";
                $.post("<?=base_url('PrivilegedUser/insertSong')?>",{
                    'name': name,
                    'performer': performer,
                    'genre': plArr[0],
                    'difficulty': plArr[1],
                    'number': plArr[2],
                    'location': location
                });
                $("#change").empty().append("<br><br><h3>Song inserted successfully</h3>");
                $("#change").append("<br><h5>Location:<br>"+ location+ "</h5>");

            }

        });
    })


</script>
<div id="error" class="red">

</div>
<table class="table " >
    <tr>
        <td>Name:</td>
        <td><input type="text" id="name"></td>
    </tr>
    <tr>
        <td>Performer:</td>
        <td><input type="text" id="performer"></td>
    </tr>
    <tr>
        <td>Playlist:</td>
        <td>
            <select style="width: 100%" id="playlists" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option id="genreDefault" selected>Playlist</option>
            </select>
        </td>
    </tr>

</table>
<input type="button" id="insertSong" class="btn btn-dark" value="Insert song">
