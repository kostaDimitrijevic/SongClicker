<!--
    Kosta Dimitrijević 0467/2018
-->

<?php
    foreach ($genres as $key=>$value) {
        $id = strtolower($key);
        $path = base_url("images/{$key}.png");

        echo  "<td class='borderless'>
                        <img src='{$path}' id='{$id}' class='toMove' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='$key - $value'>
                    </td>";
    }
