<?php
$options = array();
foreach($categories as $category){
    $options[$category->get_id()] = $category->get_name();
}
$questionnaireOptions = array();
foreach($questionnaires as $questionnaire){
    $questionnaireOptions[$questionnaire->get_id()] = $questionnaire->get_name();
}
if($hasQuestionnaire){
    $disabled = "disabled";
}
else{
    $disabled = "";
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <?php
            echo heading("Create New Question",2);
            ?>
            <div class="row">
                <div class="col-md-9">
                    <?php 
                    if(isset($createErrors)){
                        $errorData['errors'] = $createErrors;
                        $errorData['col_width'] = 12;
                        $this->load->view("message/form_validation_error_panel.php", $errorData);
                    }
                    elseif(isset($createSuccess)){
                        $successData['messages'] = $createSuccess;
                        $successData['col_width'] = 12;
                        $this->load->view("message/form_successfull_panel.php" , $successData);
                    }
                    ?>
                    <?php
                    echo form_open("admin/question/create",array("class"=>"form-horizontal", "role"=>"form"));?>
                        
                        <div class='form-group'>
                            <?= form_label("Questionnaire", "qn_id", array("class"=>"col-sm-2 control-label")) ?>
                            <div class='col-sm-10'>
                                <?= form_dropdown("qn_id", $questionnaireOptions, "" , "class='form-control' {$disabled}")?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Content", "content", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_textarea(array("name"=>"content", "rows"=>"3", "class"=>"form-control", "id"=>"content", "placeholder"=>"Content" , "value"=>set_value('content')))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Concept", "concept", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"concept", "class"=>"form-control", "placeholder"=>"Concept" , "value"=>set_value('concept')))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Scale", "scale", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"scale", "class"=>"form-control", "placeholder"=>"Scale" , "value"=>set_value('scale')))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Category", "category", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_dropdown("category_id", $options, "" , "class='form-control'"); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?= form_submit(array("class"=>"btn btn-success", "value"=>"Create Question", "name"=>"create_submit"))?>
                            </div>
                        </div>
                    <?= form_close();?>
                </div>
                
            </div>
        </div>
        <div class="col-md-3">
            <?php
            $data = array();
            $data['createQuestionnaireBtn'] = TRUE;
            
            $this->load->view('panels/admin.php', $data);
            ?>
        </div>
    </div>
</div>