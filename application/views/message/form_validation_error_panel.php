<?php
if(!isset($col_width)){
    $col_width = 10;
}
?>
<div class="row">
    <div class="col-md-<?=$col_width?> error-box" >
        <ul class="list-group">
        <?php
        foreach($errors as $error){
            echo $error;
        }?>
        </ul>
    </div>
</div>