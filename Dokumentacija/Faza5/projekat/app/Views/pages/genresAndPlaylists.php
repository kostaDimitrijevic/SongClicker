<script>

    // Teodora Mijatović 0314/2018
    // Kosta Dimitrijević 0467/2018

    $(document).ready(function () {
        $.post("<?= base_url("User/getMyGenres") ?>", function (data) {
            let cnt = 0;
            let genres = data.split(",");
            let row = $("<tr></tr>");
            for(let i = 0; i<genres.length - 1; i++){
                let path = "images/" + genres[i] + ".png";
                let col = $("<td></td>").addClass("borderless");
                col.append($("<img>").attr("src", path).attr("id", i).addClass("toMove").attr("data-container", "body")
                    .attr("data-toggle", "popover").attr("data-trigger", "hover").attr("data-placement", "bottom").attr("data-content", genres[i]));

                row.append(col);
            }

            $(".yourGenresTable").append(row);
            $('[data-toggle="popover"]').popover();

            $(".toMove").click(function() {
                let data = $(this).attr("data-content").split(" ");
                if (isHovering == false) {
                    if ($(this).hasClass("chosen") == false && $(this).hasClass("unchosen") == false && cnt<1) {
                        //this is chosen first time - both classes are false
                        cnt++;
                        $(this).toggleClass("chosen");

                    } else if ($(this).hasClass("chosen") == true) {
                        //this is unchosen
                        cnt--;
                        $(this).toggleClass("chosen");
                        $(this).toggleClass("unchosen");
                    }
                    else if ($(this).hasClass("unchosen") == true && cnt<1) {
                        //this is chosen

                        $(this).toggleClass("chosen");
                        $(this).toggleClass("unchosen");
                        cnt++;
                    }
                    if(cnt==1){
                        $("#show").prop("disabled", false);
                    }
                    else $("#show").prop("disabled", true);
                }
            });

            $(".toMove").hover(function(){
                if(!$(this).hasClass("chosen")) {
                    isHovering = true;
                    $(this).trigger('click');
                    isHovering = false;
                }
            });
        })

        $("#show").click(function () {
            $(".popover").remove();
            let data = $(".chosen").attr("data-content").split(" ");
            let chosenGenre = data[0];
            localStorage.setItem("chosenGenre", chosenGenre);
            $(".center").load("<?= base_url("User/echoView/playlistUnlock") ?>");
        });

        $("#return").click(function () {
            $(".popover").remove();
            $(".center").load("<?= base_url("User/echoView/userInterface") ?>");
        });
    });
</script>


<br>
<h4>Your genres</h4>
<br>
<div class = "genreChoices" style="margin-bottom: 40px">
    <table class="yourGenresTable table">

    </table>
</div>
<input type="button" class="btn btn-dark btnRegister btnTransition" disabled value="Show playlists" id="show" >
<input type="button" class="btn btn-dark btnRegister btnTransition" value="Return to menu" id="return">
<br>
<br>
<br>