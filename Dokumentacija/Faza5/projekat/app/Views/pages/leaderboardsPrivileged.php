<script>
    $(document).ready(function () {

        // Teodora Mijatović 0314/2018

        showGenres();
        function showGenres(){
            $.post("<?= base_url('PrivilegedUser/getAllGenres')?>", function (data) {
                let genres=data.split(",");
                genres.pop();
                for(let i=0;i<genres.length;i++){
                    let option=$("<option></option>");
                    option.addClass("optGenre");
                    option.attr("id", genres[i]);
                    let genre=genres[i].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    option.append(genre);
                    $("#genres").append(option);

                }
            });
        }

        $("#genres").on("change", function (){
            $("#picture").empty();
            let optionSelected = $("option:selected", this);
            let genre = optionSelected.attr("id");
            if($("#levelDefault").prop("selected")==true){
                $("#leaderboard").empty();
            }
            else{
                leaderBoard(genre);
            }
        });

        function leaderBoard(genre){
            $.post("<?= site_url('PrivilegedUser/getGenrePoints')?>",{
                "genre":genre
            }, function(data){
                if(genre!="allGenres") {
                    let path = "<?= base_url('images')?>" + "/" + genre + ".png";
                    let img = $("<img>").attr("src", path).attr("style", "width:10%");
                    $("#picture").empty().append(img);
                }
                let users=data.split(",");
                let myUsername=users.pop();
                users.sort(function (a,b){
                    return  b.split("/")[1]- a.split("/")[1];
                });
                //let div=$("<div></div>").attr("style", 'border: solid #721817');
                $("#leaderboard").empty().append("<tr style='border-bottom: solid #721817'><th>Rank</th><th>Username</th> <th>Points</th></tr>");
                //$("#leaderboard").empty().append(div);
                for(let i=0;i<users.length;i++){
                    let arr=users[i].split("/");
                    let r=i+1;
                    let rank=$("<td></td>").append(r.toString()+".");
                    let username=$("<td></td>").append(arr[0]);
                    let points=$("<td></td>").append(arr[1]);
                    let tr= $("<tr></tr>");
                    tr.append(rank);
                    tr.append(username);
                    tr.append(points);
                    $("#leaderboard").append(tr);
                }
            });
        }

        $("#return").click(function(){
            $.post("<?php
                if (session()->get("type") == "mod") echo base_url('Moderator/echoView/modMenu');
                else echo base_url("Administrator/echoView/adminMenu") ?>", function(data){
                $(".center").html(data);
            });
        });
    });
</script>



<select style="color:#721817;" id="genres" class="selectpicker">
    <option id="levelDefault" selected>Genre</option>
    <option id="allGenres">All genres</option>
</select>
<br>
<br>
<h3>Leaderboards:</h3>
<div style="height: 50px"id="picture"></div>
<br>
<div>
    <div style="width:80%; margin:auto; background-color: rgba(251,151,57,0.0); border: none" class="scroll">
        <table style="text-align: center" class=" table table-dark table-sm"  id="leaderboard">
        </table>
    </div>
</div>
<br>
<input type="button" class="btn btn-dark" value="Return to menu" id="return">


