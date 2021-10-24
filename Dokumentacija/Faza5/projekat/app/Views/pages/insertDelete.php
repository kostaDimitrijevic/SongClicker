<script>
    $(document).ready(function () {

        // Teodora Mijatović 0314/2018

        $("#confirmOperation").click(function () {
            let ops = document.getElementsByClassName("operation");
            let types = document.getElementsByClassName("type");
            let operation = "";
            for (let i = 0; i < ops.length; i++) {
                if (ops[i].selected) {
                    operation=ops[i].value;
                    break;
                }
            }
            let type="";
            for (let i = 0; i < types.length; i++) {
                if (types[i].selected) {
                    type=types[i].value;
                    break;
                }
            }
            operationAndType(operation,type);

        });
        $("#operation").on("change", function (){
            //alert("promena");
            let optionSelected = $("option:selected", this);
            if(optionSelected.attr("id")==="update") {
                $("#defaultType").prop("selected", true);
                $("#pl").hide();
            }
            else
                $("#pl").show();
        });

        $("#return").click(function(){
            $.post("<?php
                if (session()->get("type") == "mod") echo base_url('Moderator/echoView/modMenu');
                else echo base_url("Administrator/echoView/adminMenu") ?>", function(data){
                $(".center").html(data);
            });
        });

        function operationAndType(operation, type) {
            if (operation === "insert" && type === "song") {
                $("#change").load("<?php
                    if (session()->get("type") == "mod") echo base_url('Moderator/echoView/insertSong');
                    else echo base_url("Administrator/echoView/insertSong") ?>");
            }
            else if((operation === "delete" && type === "song")){
                $("#change").load("<?php
                    if (session()->get("type") == "mod") echo base_url('Moderator/echoView/deleteSong');
                    else echo base_url("Administrator/echoView/deleteSong") ?>");
            }
            else if((operation === "insert" && type === "playlist")){
                $("#change").load("<?php
                    if (session()->get("type") == "mod") echo base_url('Moderator/echoView/insertPlaylist');
                    else echo base_url("Administrator/echoView/insertPlaylist") ?>");
            }
            else if((operation === "delete" && type === "playlist")){
                $("#change").load("<?php
                    if (session()->get("type") == "mod") echo base_url('Moderator/echoView/deletePlaylist');
                    else echo base_url("Administrator/echoView/deletePlaylist") ?>");
            }
            else if((operation==="update" && type==="song")){
                $("#change").load("<?php
                    if (session()->get("type") == "mod") echo base_url('Moderator/echoView/updateSong');
                    else echo base_url("Administrator/echoView/updateSong") ?>");
            }
            else {
                $("#change").empty().append("<br><br><h4>You must choose operation and type </h4>");
            }
        }
    });
</script>

<table style="text-align: center" class="table">
    <tr>
        <td class=" borderless">
            <select id="operation" class="form-select formWidth form-select-lg mb-3" aria-label=".form-select-lg example">
                <option selected>Choose operation</option>
                <option class="operation" value="insert">Insert</option>
                <option class="operation" value="delete">Delete</option>
                <option id="update" class="operation" value="update">Update</option>
            </select>
        </td>

        <td class=" borderless">
            <select  class="form-select  formWidth form-select-lg mb-3" aria-label=".form-select-lg example">
                <option id="defaultType" selected>Choose type</option>
                <option id="pl" class="type" value="playlist">Playlist</option>
                <option class="type" value="song">Song</option>
            </select>
        </td>
    </tr>
</table>
<input type="button" id="confirmOperation" class="btn btn-dark ok" value="OK">
<br>
<br>

<div  id="change">

</div>
<br>
<br>
<input type="button" class="btn btn-dark" id="return" value="Return to menu">




