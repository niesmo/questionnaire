<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $qn_id = $questionnaire->get_id();
            echo heading("Questionnaire Detail", 2);
            ?>
            <dl class="dl-horizontal">
                <dt>Questionnaire</dt>
                <dd><?php echo $questionnaire->get_name();?></dd>
                <dt>Author</dt>
                <dd><?php echo $questionnaire->get_author();?></dd>
                <dt>Year</dt>
                <dd><?php echo $questionnaire->get_year();?></dd>
            </dl>
            <button class="btn btn-sm btn-success bottom-margin-sm">Add Entire Questionnaire</button>

            <?php
            $hiddenInput = array("path"=>"questionnaire/qsearch/{$qn_id}");
            echo form_open("search/pre","", $hiddenInput);
            ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <?= form_input(array("name"=>"search", "class"=>"form-control", "placeholder"=>"Search", "autofocus"=>"autofocus"));?>
                            <span class="input-group-btn">
                                <?=form_submit(array("name"=>"search-btn", "value"=>"Search", "class"=>"btn btn-default"));?>
                            </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-4 -->
                </div>
            <?php
            echo form_close();
            echo "<hr />";
            ?>
            <?php
            if(is_logged_in()){
                $dropdownProjectArr=array();
                $dropdownProjectArr['-1'] = "Please select a project";
                /*foreach($projects as $project){
                    $dropdownProjectArr[$project->get_id()] = $project->get_name();
                }*/

                $modalData = array();
                $modalData ['modal_id'] = "project-selection";
                $modalData ['modalLabel'] = "Select Project";
                $modalData ['modalTitle'] = "Select Project & Questionnaire";
                $modalData ['dropdownProjectArr'] = $dropdownProjectArr;
                $modalData ['q_id'] = 1;//$question->get_id();
                $modalData ['qn_id'] = 1;//$questionnaire->get_id();

                $this->load->view("modals/select_create_project.php", $modalData);
                echo '<button class="btn btn-success btn-sm bottom-margin-sm add-selected-question" data-toggle="modal" data-target="#project-selection" disabled>Add Selected Questions</button>';
            }
            else{
                echo anchor("auth/login?location=".urlencode($_SERVER['REQUEST_URI']), "Add Selected Questions", "class='btn btn-success btn-sm bottom-margin-sm add-selected-question'");
            }
            ?>

            <ul class="list-unstyled">
            <?php
            foreach($questions as $question){
                $id= $question->get_id();
                $content =$question->get_content();
                echo "<li>".form_checkbox($qn_id."_".$id,null,null,"class='q_checkbox'") ." ".anchor("question/detail/{$qn_id}/{$id}/", $content)."</li>";
            }
            ?>
            </ul>
            <?php
            if(is_logged_in()){
                echo '<button class="btn btn-success btn-sm add-selected-question" disabled>Add Selected Questions</button>';
            }
            else{
                echo anchor("auth/login?location=".urlencode($_SERVER['REQUEST_URI']), "Add Selected Questions", "class='btn btn-success btn-sm bottom-margin-sm add-selected-question'");
            }
            ?>

        </div>
    </div>
</div>