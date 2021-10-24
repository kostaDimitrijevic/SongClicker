
<script>

    // Kosta Dimitrijević 0467/2018

    $(document).ready(function () {
        $("#confirm").click(function () {
            $.post("<?= base_url("Administrator/checkModerator") ?>",
                {
                    "modUsername" : $("#moderatorUsername").val(),
                    "modPassword" : $("#moderatorPassword").val()
                }
                , function (data1) {
                $("#error").empty();
                let username = $("#moderatorUsername").val();
                let password =$("#moderatorPassword").val()
                    if(data1 != ""){
                        let col = $("<td></td>").attr("style", "color : red").attr("colspan", "2").addClass("borderless");
                        col.append(data1);
                        $("#error").append(col);
                        return;
                    }else if(username != "" && password != ""){
                        if(/^\w{5,}$/.test(username) == false){
                            let col = $("<td></td>").attr("style", "color : red").attr("colspan", "2").addClass("borderless");
                            col.append("Username needs to contain at least 5 characters");
                            $("#error").append(col);
                            return;
                        }
                        else if(/^\w{5,}$/.test(password) == false){
                            let col = $("<td></td>").attr("style", "color : red").attr("colspan", "2").addClass("borderless");
                            col.append("Password needs to contain at least 5 characters");
                            $("#error").append(col);
                            return;
                        }
                        $.post("<?= base_url("Administrator/saveNewModerator") ?>",{
                            "modUsername" : $("#moderatorUsername").val(),
                            "modPassword" : $("#moderatorPassword").val()
                        }, function (data) {
                            let col = $("<td></td>").attr("style", "color : green").attr("colspan", "2").addClass("borderless");
                            col.append("You added new moderator successfully");
                            $("#error").append(col);
                        });
                    }
                    else if(username == ""){
                        let col = $("<td></td>").attr("style", "color : red").attr("colspan", "2").addClass("borderless");
                        col.append("Please enter username");
                        $("#error").append(col);
                    }
                    else if(password == ""){
                        let col = $("<td></td>").attr("style", "color : red").attr("colspan", "2").addClass("borderless");
                        col.append("Please enter password");
                        $("#error").append(col);
                    }
                }
            );
        });

        $("#backToMenu").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/adminMenu") ?>");
        });
    });
</script>


<div class="registerModerator">
    <table class="table registerModeratorTable center">
        <tr id="error">

        </tr>
        <tr>
            <td>
                Moderator username:
            </td>
            <td>
                <input type="text" id="moderatorUsername">
            </td>
        </tr>
        <tr>
            <td>
                Moderator password:
            </td>
            <td>
                <input type="password" id="moderatorPassword">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <input type="button" class="btn btn-dark" value="Confirm" id="confirm">
            </td>
        </tr>
    </table>
    <input type="button" class="btn btn-dark" value="Return to menu" id="backToMenu">
</div>