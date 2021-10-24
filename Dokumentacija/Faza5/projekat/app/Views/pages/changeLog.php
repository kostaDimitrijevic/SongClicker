<script>
    $(document).ready(function (){

        // Teodora Mijatović 0314/2018

        changeLog();
        function changeLog(){
            $.post("<?= base_url("Administrator/getChangeLog") ?>", function (data) {
                let logs=data.split("/");
                logs.pop();
                for(let i=0;i<logs.length;i++){
                    let info=logs[i].split(",");
                    let dateTime=info[2].split(" ");
                    let tr=$("<tr></tr>");
                    let message=info[0]+" "+info[1]+" on "+dateTime[0]+ " at "+ dateTime[1];
                    let td=$("<td></td>").append(message);
                    tr.append(td);
                    $("#changeLog").append(tr);
                }
            });
        }
        $("#return").click(function() {
            $(".center").load("<?=base_url('Administrator/echoView/adminMenu')?>");
        });
    });

</script>
<br>
<h3>Changes Log:</h3>
<br>
<div>
    <div style="margin:auto;  background-color: rgba(251,151,57,0); width : 90%; border: none" class="scroll">
        <table style="text-align: left; border:solid 3px dimgrey; background-color:rgba(114,24,23,0.80) " class= "table table-dark table-sm table-bordered" id="changeLog">

        </table>
    </div>
</div>
<br>
<input type="button" class="btn btn-dark" value="Return to menu" id="return">