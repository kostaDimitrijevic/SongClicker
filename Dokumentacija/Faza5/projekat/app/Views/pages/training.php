
<script>
    $(document).ready(function () {

        // Kosta Dimitrijević 0467/2018

        $.post("<?= base_url("User/echoView/printGenreImages/getGenres") ?>", function (data) {
            let row = $("<tr></tr>").html(data);
            $(".genres-table").append(row);
            $('[data-toggle="popover"]').popover();

            let cnt=0;
            let isHovering=false;
            let genres = new Map();
            setMap();
            function setMap() {
                let genreimages = $(".genres-table").find(".toMove");
                for(let i = 0; i < genreimages.length; i++){
                    let data = $(genreimages[i]).attr("data-content").split(" ");
                    genres.set(data[0], {"isLocked" : data[2]});
                }
            }


            $(".toMove").click(function() {
                let data = $(this).attr("data-content").split(" ");
                if (isHovering == false) {
                    if ($(this).hasClass("chosen") == false && $(this).hasClass("unchosen") == false && cnt<1) {
                        //this is chosen first time - both classes are false
                        cnt++;
                        $(this).toggleClass("chosen");

                        if(data[2] == "unlocked"){
                            $("#train").attr("disabled", false);
                        }
                        else {
                            $("#unlock").attr("disabled", false);
                            $(this).attr("data-content", data[0] + " - " + "unlock");
                        }

                    } else if ($(this).hasClass("chosen") == true) {
                        //this is unchosen
                        cnt--;
                        if (genres.get(data[0]).isLocked == "locked") {
                            $(this).attr("data-content", data[0] + " - " + "locked");
                            $("#unlock").attr("disabled", true);
                        }
                        else
                            $("#train").attr("disabled", true);
                        $(this).toggleClass("chosen");
                        $(this).toggleClass("unchosen");
                    }
                    else if ($(this).hasClass("unchosen") == true && cnt<1) {
                        //this is chosen

                        if (genres.get(data[0]).isLocked == "locked") {
                            $("#unlock").attr("disabled", false);
                            $(this).attr("data-content", data[0] + " - " + "unlock");
                        }
                        else
                            $("#train").attr("disabled", false);
                        $(this).toggleClass("chosen");
                        $(this).toggleClass("unchosen");
                        cnt++;
                    }
                    if(cnt==1){
                        $("#confirmGenres").prop("disabled", false);
                    }
                    else $("#confirmGenres").prop("disabled", true);
                }
            });

            $("#confirmGenres").click(function (){
                let chosen=document.getElementsByClassName("chosen");
                $("#g1").attr("value", chosen[0].id);
                $("#g2").attr("value", chosen[1].id);
            });

            $(".toMove").hover(function(){
                if(!$(this).hasClass("chosen")) {
                    isHovering = true;
                    $(this).trigger('click');
                    isHovering = false;
                }
            });
        })
        $("#train").click(function () {
            let chosenGenre = $(".chosen").attr("data-content").split(" ");
            $("#chosenGenre").attr("value", chosenGenre[0]);
            $("#mode").attr("value", "train");
        });

        $("#unlock").click(function () {
            let chosenGenre = $(".chosen").attr("data-content").split(" ");
            $("#chosenGenre").attr("value", chosenGenre[0]);
            $("#mode").attr("value", "unlock");
        });

        $("#return").click(function () {
            $(".center").load("<?= base_url("User/echoView/userInterface") ?>");
        });
    })
</script>


<br>
<h4>Training mode</h4>
<br>
<br>
<div class = "genreChoices">
    <table class="genres-table table">

    </table>
</div>
<br>
<br>
<br>
<form method="post" action="<?= base_url("User/goToTraining") ?>">
    <input type="submit" value = "Train" id="train" class="btn btn-dark btnRegister btnTransition" disabled = true>
    <input type="submit" value = "Unlock" id="unlock" class="btn btn-dark btnRegister btnTransition" disabled = true>
    <input type="button" value="Return to menu" id="return" class="btn btn-dark btnRegister btnTransition">
    <input type="hidden" name="chosenGenre" value="" id="chosenGenre">
    <input type="hidden" name="mode" value="" id="mode">
</form>
