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
                    echo heading("Edit Questionnaire Page", 2);
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
                        "questionnaire_id"=>$questionnaire->get_id(),
                    );
                    echo form_open("admin/questionnaire/edit/{$qq_id}",array("class"=>"form-horizontal", "role"=>"form"), $hiddenInput);
                    ?>
                        <div class="form-group">
                            <?= form_label("Name", "name", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_textarea(array("name"=>"name", "rows"=>"3", "class"=>"form-control", "id"=>"name", "placeholder"=>"Name" , "value"=>"{$questionnaire->get_name()}"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Author", "author", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"author", "class"=>"form-control", "placeholder"=>"Author" , "value"=>"{$questionnaire->get_author()}"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Year", "year", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"year", "class"=>"form-control", "placeholder"=>"Year" , "value"=>"{$questionnaire->get_year()}"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?= form_submit(array("class"=>"btn btn-success", "value"=>"Save Changes", "name"=>"edit_submit"))?>
                            </div>
                        </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <?= heading("Questions in this Questionnaire", 2, "id='edit_question_heading'") ?>
                    <div class="row">
                        <div class="col-md-6">
                        <?php
                        if(!empty($questions)){
                            echo "<ol>";
                            foreach ($questions as $question){
                                echo "<li>".anchor("#", $question->get_content(), array("class"=>"quick-question-edit","data-q-id"=>$question->get_id(), "data-qn-id"=>$questionnaire->get_id())). "</li>";
                            }
                            echo "</ol>";
                        }
                        ?>
                        </div>
                        <div class="col-md-6 hide" id="question_update">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-2 error-box" id="error-box">
                                    <ul class="list-group"></ul>
                                </div>
                            </div>
                            <div class="form-horizontal" role="form" >
                                <?=form_hidden("question_id", "");?>
                                <div class="form-group">
                                    <?= form_label("Content", "content", array("class"=>"col-sm-2 control-label"))?>
                                    <div class="col-sm-10">
                                        <?= form_textarea(array("name"=>"content", "rows"=>"3", "class"=>"form-control", "id"=>"content"))?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= form_label("Concept", "concept", array("class"=>"col-sm-2 control-label"))?>
                                    <div class="col-sm-10">
                                        <?= form_input(array("name"=>"concept", "class"=>"form-control", "id"=>"concept"))?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= form_label("Scale", "scale", array("class"=>"col-sm-2 control-label"))?>
                                    <div class="col-sm-10">
                                        <?= form_input(array("name"=>"scale", "class"=>"form-control", "id"=>"scale"))?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= form_label("Category", "category", array("class"=>"col-sm-2 control-label"))?>
                                    <div class="col-sm-10">
                                        <?= form_dropdown("category_id", $options, "", "class='form-control' id='category'"); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <?= form_submit(array("class"=>"btn btn-success", "id"=>"ajax_question_submit", "value"=>"Save Changes", "name"=>"edit_submit"))?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr id="end_of_page" />
                </div>
            </div>
        </div>
    </div>
</div>