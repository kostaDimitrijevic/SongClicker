
<script>

    // Kosta Dimitrijević 0467/2018

    $(document).ready(function () {
        $("#deleteAcc").click(function () {
            $.post("<?= base_url("Administrator/deleteAccount") ?>", {
                accountToDelete: $("#toDelete").val()
            }, function (data) {
                $("#error").empty();
                if(data !== ""){
                    let col = $("<td></td>").append(data);
                    col.attr("style", "color : red").attr("colspan", "2").addClass("borderless");
                    $("#error").append(col);
                }
                else{
                    let col1 = $("<td></td>").append("You successfully deleted an account!");
                    col1.attr("style", "color : green").attr("colspan", "2").addClass("borderless");
                    $("#error").append(col1);
                }

            });
        });

        $("#goBack").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/adminMenu") ?>");
        });
    });
</script>
<table class="table deleteTable" style="text-align: center;">
    <tr id="error">

    </tr>
    <tr>
        <td colspan="2" class="borderless" >
            <h3>Delete account</h3>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="borderless" style="font-weight: bold;">
            Username:
            <input type="text" name="accountToDelete" id="toDelete">
        </td>
    </tr>
    <tr>
        <td class="borderless">
            <input class="btn btn-dark" type="button" id="deleteAcc" value="Delete" style="width: 50%">
        </td>
        <td class="borderless">
            <input class="btn btn-dark" type="button" id="goBack" value="Finish" style="width: 50%">
        </td>
    </tr>
</table>
