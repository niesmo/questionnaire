<?php
$dropdownProjectArr=array();
$dropdownProjectArr['-1'] = "Please select default project";
foreach($projects as $project){
    $dropdownProjectArr[$project->get_id()] = $project->get_name();
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
        <?php
        if(is_admin()){
            $data = array();
            $data['createQuestionnaireBtn'] = TRUE;
            $data['createQuestionBtn'] = TRUE;
            $this->load->view('panels/admin.php', $data);
            echo "<hr />";
        }
        ?>
        <?php
            echo heading("User Dashboard" ,2);
        ?>
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="input-group"> 
                                <?php
                                $inputArr = array("class"=>"form-control", "placeholder"=>"Project Name", "id"=>"project-name");
                                echo form_input($inputArr);
                                ?> 
                                <span class="input-group-btn">
                                    <button class="btn btn-success" id="create-project" type="button">Create Project</button>
                                </span>
                            </div><!-- /input-group -->
                            <div class="top-margin-sm" id="project-errors" style="display: none;"></div>
                        </div><!-- /.col-md-10 -->
                    </div>
                    <!--<div class="row">
                        <div class="col-md-12">
                            <?php
                                //echo heading("Default Project", 3);
                                //echo form_dropdown('default-project', $dropdownProjectArr, $defaultProject_id , "id='default-project-list'");
                                //echo br();
                                //echo "<button class='btn btn-sm btn-success top-margin-sm' id='set-default-project'>Set Default</button>";
                            ?>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo heading("Projects Summary", 3);
                            echo "<p>I dont know what to put here yet . . . . :(</p>";
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 well">
                    <?php
                        echo heading("Current Projects", 3, "class='no-top-margin'");
                        echo "<ul class='list-unstyled' id='project-list'>";
                        if(!empty($projects)){
                            foreach($projects as $project){
                                if($project->get_id() == $defaultProject_id){
                                    $class="bold";
                                }
                                else{
                                    $class="";
                                }
                                echo "<li class='{$class} has-remove-icon' data-project-id='{$project->get_id()}'>".anchor("user/project/detail/{$project->get_id()}", $project->get_name()). "<a href='#' class='pull-right hidden remove-project-item trash-can' data-project-id='{$project->get_id()}'><i class='fa fa-trash-o '></i></a></li>";
                            }
                        }
                        else{
                            echo "<p id='no-project'>You have not created any project yet</p>";
                        }
                        echo "</ul>";
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>