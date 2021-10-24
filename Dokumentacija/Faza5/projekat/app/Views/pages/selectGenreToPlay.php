<!--
    Mladen Mirčić 2018/0413
-->

<script>
    $(document).ready(function () {

        // Mladen Mirčić 0413/2018

        let selected = null;

       $(".genreChoices img").on({
           click: function () {
               if (selected == null) {
                   $("#confirmGenre").prop("disabled", false);
                   selected = $(this).attr("id");
                   $(this).addClass("selectedGenre");
                   $(this).removeClass("deselectedGenre");
               }
               else if (selected === $(this).attr("id")) {
                   $("#confirmGenre").prop("disabled", true);
                   $(this).addClass("deselectedGenre");
                   $(this).removeClass("selectedGenre");
                   selected = null;
               }
           },
           mouseenter: function () {
               if (selected == null) {
                   $(this).addClass("selectedGenre");
                   $(this).removeClass("deselectedGenre");
               }

           },
           mouseleave: function() {
               if (selected == null) {
                   $(this).addClass("deselectedGenre");
                   $(this).removeClass("selectedGenre");
               }
           }
       });

        $("#confirmGenre").click(function () {
            if ($(this).parent().hasClass("center")) {
                $.post("<?= base_url("User/echoView/waitForPlayer/setChosenGenre") ?>",{
                    chosenGenre: selected
                }, function (data) {
                    $("#insertable").html(data);
                    $("#confirmGenre").remove();
                    $("#return").remove();
                });
            }
            if ($(this).parent().attr("id") === "confirmGenreForm")
                $(this).parent().children("#chosenGenre").attr("value", selected);
        });

        $("#return").click(function () {
            $(".center").load("<?= base_url("User/echoView/userInterface") ?>");
        });
    });
</script>
<br>
<h4>Choose a genre to compete in</h4>
<br>
<div class="genreChoices">
    <?php
    foreach ($userInfo as $oneInfo) {
        $path = base_url("images/{$oneInfo->genre}.png");
        echo  "
               <picture>
                    <img src='{$path}' id='{$oneInfo->genre}'>
               </picture>
                ";
    }
    ?>
</div>
<br>
<br>