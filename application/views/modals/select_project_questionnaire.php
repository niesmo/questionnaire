<?php
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 9/18/14
 * Time: 1:10 AM
 */

$dropdownQuestionnaireArr = array();
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
                echo form_dropdown('projects', $dropdownProjectArr, -1, "id='project-list-pq' class='project-list' data-action='populate-questionnaire' data-dest='questionnaire-list-pq'");
                echo br();
                echo anchor("user/dashboard/", "Create New Project", "class='small'");
                echo br(2);
                echo heading("Questionnaire", 3);
                echo form_dropdown('questionnaires', $dropdownQuestionnaireArr , -1, "id='questionnaire-list-pq'");
                echo br();
                echo anchor("user/dashboard/", "Create New Questionnare", "class='small' id='project-detail-link'");
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                <button type="button" id='<?=$submit_btn_id?>' data-dismiss="modal" data-original-questionnaire="<?=$qn_id?>" class="btn btn-sm btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>