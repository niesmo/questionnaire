<?php
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 9/11/14
 * Time: 12:53 PM
 */
?>
<div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalLabel; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="<?php echo $modalLabel; ?>"><?php echo $modalTitle; ?></h4>
            </div>
            <div class="modal-body">
                <?php
                echo heading("Project", 3);
                echo form_dropdown('projects', $dropdownProjectArr, -1, "id='project-list'");
                echo br();
                echo anchor("user/dashboard/", "Create New Project", "class='small'");
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                <button type="button" id='add-entire-questionnaire' data-dismiss="modal" data-questionnaire-name="<?= $qn_name ?>" data-questionnaire-id="<?=$qn_id?>" class="btn btn-sm btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>