<script>
    $(document).ready(function () {

        // Mladen Mirčić 0413/2018
        // Teodora Mijatović 0314/2018


        $.post("<?php
                    if (session()->has("forRegister"))
                        echo base_url("Register/echoView/printGenreImages/getGenres");
                    else
                        echo base_url("User/echoView/printGenreImages/getGenres");
                ?>", function (data) {
            let row = $("<tr></tr>").html(data);
            $(".genres-table").append(row);
            $('[data-toggle="popover"]').popover();

            let cnt=0;
            let isHovering=false;

            $(".toMove").click(function() {
                if (isHovering === false) {
                    if ($(this).hasClass("chosen") === false && $(this).hasClass("unchosen") === false && cnt<2) {
                        //this is chosen first time - both classes are false
                        cnt++;
                        $(this).toggleClass("chosen");

                        let data = $(this).attr("data-content").split(" ");
                        $(this).attr("data-content", data[0] + " - " + "unlocked");

                    } else if ($(this).hasClass("chosen") === true) {
                        //this is unchosen
                        cnt--;
                        let data = $(this).attr("data-content").split(" ");
                        $(this).attr("data-content", data[0] + " - " + "locked");

                        $(this).toggleClass("chosen");
                        $(this).toggleClass("unchosen");
                    } else if (cnt < 2) {
                        //this is chosen
                        let data = $(this).attr("data-content").split(" ");
                        $(this).attr("data-content", data[0] + " - " + "unlocked");
                        $(this).toggleClass("chosen");
                        $(this).toggleClass("unchosen");
                        cnt++;
                    }
                    if(cnt === 2){
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
        });


    });
</script>
<br>
<h4>Choose two genres</h4>
<table class="genres-table table">
</table>

<form method="post" action=" <?= base_url("Register/confirmGenres") ?>">
    <input type="submit" id="confirmGenres" class="btn btn-dark" value="Choose" disabled>
    <input type="hidden" id="g1" name="g1" value="">
    <input type="hidden" id="g2" name="g2" value="">
</form>




