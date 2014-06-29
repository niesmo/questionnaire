<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php
            echo heading("Create New Questionnaire" , 2);
            ?>
            <div class="row">
                <div class="col-md-12">
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
                    echo form_open("admin/questionnaire/create",array("class"=>"form-horizontal", "role"=>"form"));
                    ?>
                        <div class="form-group">
                            <?= form_label("Name", "name", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_textarea(array("name"=>"name", "rows"=>"3", "class"=>"form-control", "id"=>"name", "placeholder"=>"Name", "value"=>set_value('name') ))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Author", "author", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"author", "class"=>"form-control", "placeholder"=>"Author", "value"=>set_value('author')))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= form_label("Year", "year", array("class"=>"col-sm-2 control-label"))?>
                            <div class="col-sm-10">
                                <?= form_input(array("name"=>"year", "class"=>"form-control", "placeholder"=>"Year", "value"=>set_value('year') ))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?= form_submit(array("class"=>"btn btn-success", "value"=>"Create Questionnaire", "name"=>"create_submit"))?>
                            </div>
                        </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
                
            </div>
        </div>
        <div class="col-md-3 col-md-offset-1">
            <?php
            if(isset($createSuccess)){
                $data = array();
                $data['qn_id'] = $qn_id;
                $data['createQuestionBtn'] = TRUE;
                $this->load->view('panels/admin.php', $data);
            }
            ?>
        </div>
    </div>
</div>