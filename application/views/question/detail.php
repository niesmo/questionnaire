<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="message"></div>                    
            </div>
            <?php
            if(is_admin()){
                $data = array();
                $data['qq_id'] = $qq_id;
                $data['qn_id'] = $questionnaire->get_id();
                $data['questionBtn'] = TRUE;
                $data['questionnaireBtn'] = TRUE;
                $data['createQuestionBtn'] = TRUE;
                $this->load->view('panels/admin.php', $data);
                echo "<hr />";
            }
            ?>
            <?php
            echo heading("Question Detail", 2);
            ?>
            <dl class="dl-horizontal">
                <dt>Question</dt>
                <dd><?=$question->get_content(TRUE);?>?</dd>
                <dt>Concept</dt>
                <dd><?=$question->get_concept(TRUE)?></dd>
                <dt>Category</dt>
                <dd><?=$question->get_category_name()?></dd>

            </dl>
            <?php
            //The user is logged in
            if(is_logged_in()){
                //if the user is logged in then we need to ask them 
                //what project and questionnaire they want to add this questionnaire to
                
                $dropdownProjectArr=array();
                $dropdownProjectArr['-1'] = "Please select a project";
                foreach($projects as $project){
                    $dropdownProjectArr[$project->get_id()] = $project->get_name();
                }
                    
                $modalData = array();
                $modalData ['modal_id'] = "project-selection";
                $modalData ['modalLabel'] = "Select Project";
                $modalData ['modalTitle'] = "Select Project & Questionnaire";
                $modalData ['dropdownProjectArr'] = $dropdownProjectArr;
                $modalData ['q_id'] = $question->get_id();
                $modalData ['qn_id'] = $questionnaire->get_id();
                    
                $this->load->view("modals/select_create_project.php", $modalData);
                echo '<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#project-selection">Add This Question</button>';
                
                
                if($quickAdd == TRUE){
                    echo '<button id="quick-add" class="btn btn-warning btn-sm left-margin-sm" data-toggle="tooltip" data-placement="right" title="'.$project_name . "->" .$qn_name.'" data-original-questionnaire="'.$questionnaire->get_id().'" data-question-id="'.$question->get_id().'" data-project-id="'.$project_id.'" data-questionnaire-id="'.$user_qn_id.'">Quick Add</button>';
                }

            }
            else{
                echo anchor("auth/login?location=".urlencode($_SERVER['REQUEST_URI']), "Add This Question", "class='btn btn-success btn-sm'");
            }
            ?>
            <hr />
            <?php
            echo heading("Other Questions In This Questionnaire", 2);
            echo "<div class='btn-group bottom-margin-sm'>";
                echo "<button type='button' id='same-category-question-filter' data-category='{$question->get_category_id()}' class='btn btn-info btn-sm right-margin-sm'>Same Category</button>";
                echo "<button type='button' id='same-concept-question-filter' data-concept='".urlencode($question->get_concept())."' class='btn btn-info btn-sm'>Same Concept</button>";
            echo "</div>";
            echo "<ol id='other-questions'>";
            foreach($otherQuestions as $o_question){
                echo "<li data-category='{$o_question->get_category_id()}' data-concept='".urlencode($o_question->get_concept())."'>".anchor("question/detail/{$questionnaire->get_id()}/{$o_question->get_id()}", $o_question->get_content())."</li>";
            }
            echo "</ol>";
            ?>
            <hr />
            <?php
            echo heading("Statistics", 2);
            ?>
            <dl class="dl-horizontal">
                <dt>View Count</dt>
                <dd><?= $viewCount ?></dd>
                <dt>Select Count</dt>
                <dd><?= $selectedCount ?></dd>
            </dl>
            
        </div>
    </div>
</div>