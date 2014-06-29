<?php

if(!isset($col_width)){
    $col_width = 10;
}
?>
<div class="row">
    <div class="col-md-<?=$col_width?> error-box" >
        <ul class="list-group">
        <?php
        foreach($messages as $msg){
            echo "<li class='list-group-item list-group-item-success'>{$msg}</li>";
        }?>
        </ul>
    </div>
</div>