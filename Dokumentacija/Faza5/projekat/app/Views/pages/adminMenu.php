<script>
    $(document).ready(function (){

        // Teodora Mijatović 0314/2018

        $("#mistakeLog").click(function() {
            $.get("<?= base_url("Administrator/echoView/mistakeLog") ?>", function (data) {
                $(".center").html(data);
            });
        });

        $("#leaderboards").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/leaderboardsPrivileged") ?>");
        });

        $("#update").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/insertDelete") ?>");
        });

        $("#registerMod").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/registerModerator") ?>");
        });

        $("#delete").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/deleteAccount") ?>");
        });

        $("#quit").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/quit") ?>");
        });

        $("#changeLog").click(function () {
            $(".center").load("<?= base_url("Administrator/echoView/changeLog") ?>");
        });
    });
</script>

    <table class="table tableAdminMenu">
        <tr >
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Leaderboards" id="leaderboards">
            </td>
        </tr>
        <tr>
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Update" id="update">
            </td>
        </tr>
        <tr>
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Mistake Log" id="mistakeLog">
            </td>
        </tr>
        <tr>
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Change Log" id="changeLog">
            </td>
        </tr>
        <tr>
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Delete Account" id="delete">
            </td>
        </tr>
        <tr>
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Register Moderator" id = "registerMod">
            </td>
        </tr>
        <tr>
            <td class="borderless">
                <input type="submit" class="btn btnMenu btn-dark btnTransition" name="submit" value="Quit" id="quit">
            </td>
        </tr>
    </table>

