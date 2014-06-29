<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            echo heading("Project {$project->get_name()}", 2);
            ?>
            <div class="row">
                <div class="col-md-8">
                    <?php
                    echo heading("Description <button class='btn btn-success btn-xs' id='edit-description'>Edit</button><button class='btn btn-info btn-xs hidden' id='save-description' data-project-id='{$project->get_id()}'>Save</button>", 3);
                    echo "<textarea rows='5' cols='50' id='project-description' disabled>{$project->get_description()}</textarea>";
                    //echo "<p id='project-description' contenteditable='false'>{$project->get_description()}</p>";
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo heading("Invite Collaborators", 3);
                            ?>
                            <div class="row"><div class="col-md-6" id="response"></div></div>
                            <div class="input-group col-md-6"> 
                                <?php
                                $inputArr = array("class"=>"form-control", "placeholder"=>"Collaborator Email", "id"=>"collaborator-email");
                                echo form_input($inputArr);
                                ?> 
                                <span class="input-group-btn">
                                    <button class="btn btn-success" id="invite-collaborator" data-project-id="<?= $project->get_id()?>" type="button">Invite</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo heading("Current Collaborators", 3); ?>
                            <?php
                            if(empty($collaborators)){
                                echo "<p>No collaborators are added to this project";
                            }
                            else{
                                echo "<ul class='list-unstyled'>";
                                foreach($collaborators as $collaborator){
                                    echo "<li>" . $collaborator->get_username() ."</li>";
                                }
                                echo "</ul>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 well">
                    <?php
                    echo heading("Questionnaires List", 3, "class='no-top-margin'");
                    echo "<ul class='list-unstyled' id='questionnaire-list'>";
                    if(!empty($questionnaires)){
                        foreach($questionnaires as $questionnaire){
                            echo "<li class='has-remove-icon' data-questionnaire-id='{$questionnaire->get_id()}'>".anchor("user/questionnaire/detail/{$questionnaire->get_id()}" ,$questionnaire->get_name())."<a href='#' class='pull-right hidden remove-questionnaire-item trash-can' data-questionnaire-id='{$questionnaire->get_id()}'><i class='fa fa-trash-o '></i></a></li>";
                        }
                    }
                    else{
                        echo "<p id='no-questionnaire'>You have not created any questionnaire yet</p>";
                    }
                    echo "</ul>";
                    ?>
                    <div class="input-group"> 
                        <?php
                        $inputArr = array("class"=>"form-control", "placeholder"=>"Questionnaire Name", "id"=>"questionnaire-name");
                        echo form_input($inputArr);
                        ?> 
                        <span class="input-group-btn">
                            <button class="btn btn-success" id="create-questionnaire" data-project-id="<?= $project->get_id()?>" type="button">Create Questionnaire</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div>
        </div>
    </div>
</div>