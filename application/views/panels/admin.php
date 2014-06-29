<div class="row" id="admin-panel">
    <div class="col-md-12">
        <?php
        $class= "";
        if(isset($headingClass)){
            $class = $headingClass;
        }
        echo heading("Admin Panel", 2, "class='{$class}'");
        if(isset($createQuestionnaireBtn) && $createQuestionnaireBtn == TRUE){
            echo anchor("admin/questionnaire/create","Create New Questionnaire", "class='btn btn-sm btn-warning bottom-margin-sm'");
            echo br();
        }
        if(isset($createQuestionBtn) &&  $createQuestionBtn == TRUE){
            $link = "admin/question/create";
            $name = "Create New Question";
            if(isset($qn_id)){
                $link .= "/{$qn_id}";
                $name = "Add Question to this Questionnaire";
            }
            echo anchor($link,$name, "class='btn btn-sm btn-warning bottom-margin-sm'");
            echo br();
        }
        if(isset($questionBtn) && $questionBtn == TRUE){
            echo anchor("admin/question/edit/{$qq_id}","Edit this question", "class='btn btn-sm btn-warning bottom-margin-sm'");
            echo br();
        }
        if(isset($questionnaireBtn) && $questionnaireBtn == TRUE){
            echo anchor("admin/questionnaire/edit/{$qq_id}","Edit the questionnaire", "class='btn btn-sm btn-warning'");
        }
        ?>
    </div>
</div>