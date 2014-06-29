<?php
$options = array();
foreach($categories as $category){
    $options[$category->get_id()] = $category->get_name();
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
                    <?php
                    echo heading("Edit Question Page", 2);
                    ?>
                    <?php 
                    if(isset($editErrors)){
                        $errorData['errors'] = $editErrors;
                        $errorData['col_width'] = 12;
                        $this->load->view("message/form_validation_error_panel.php", $errorData);
                    }
                    elseif(isset($editSuccess)){
                        $successData['messages'] = $editSuccess;
                        $successData['col_width'] = 12;
                        $this->load->view("message/form_successfull_panel.php" , $successData);
                    }
                    ?>
                    <?php
                    $hiddenInput = array(
                        "question_id"=>$question->get_id(),
                        "category_id"=>$question->get_category_id()
                    );
                    echo form_open("admin/question/edit/{$qq_id}",array("class"=>"form-horizontal", "role"=>"form"), $hiddenInput);?>
                        <div class="form-group">
                            <?= form_label("Content", "content", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_textarea(array("name"=>"content", "rows"=>"3", "class"=>"form-control", "id"=>"content", "placeholder"=>"Content" , "value"=>"{$question->get_content()}"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Concept", "concept", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"concept", "class"=>"form-control", "placeholder"=>"Concept" , "value"=>"{$question->get_concept()}"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Scale", "scale", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"scale", "class"=>"form-control", "placeholder"=>"Scale" , "value"=>"{$question->get_scale()}"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Category", "category", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_dropdown("category_id", $options, $question->get_category_id(), "class='form-control'"); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?= form_submit(array("class"=>"btn btn-success", "value"=>"Save Changes", "name"=>"edit_submit"))?>
                            </div>
                        </div>
                    <?= form_close();?>
                </div>
                <div class="col-md-3 col-md-offset-1">
                    <?php
                    $data=array();
                    $data['qq_id'] = $qq_id;
                    $data['questionnaireBtn'] = TRUE;
                    $this->load->view("panels/admin.php", $data);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>