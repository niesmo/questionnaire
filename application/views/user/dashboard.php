<?php
$dropdownProjectArr=array();
$dropdownProjectArr['-1'] = "Please select default project";
foreach($projects as $project){
    $dropdownProjectArr[$project->get_id()] = $project->get_name();
}
$colors = $this->config->item('colors');
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
                <div class="col-md-6">
                    <?php
                    echo heading("Current Projects", 3, "class='no-top-margin'");
                    /*echo "<ul class='list-unstyled' id='project-list'>";
                    if(!empty($projects)){
                        foreach($projects as $project){
                            $class="";
                            echo "<li class='{$class} has-remove-icon' data-project-id='{$project->get_id()}'>".anchor("user/project/detail/{$project->get_id()}", $project->get_name()). "</li>";
                        }
                    }
                    else{
                        echo "<p id='no-project'>You have not created any project yet</p>";
                    }
                    echo "</ul>";*/
                    ?>
                    <div class="row">
                        <div class="col-md-12" id='project-list'>
                            <?php
                            if(!empty($projects)){
                                foreach($projects as $project){
                                    echo "<div class='col-md-4 square' data-href='user/project/detail/{$project->get_id()}' style='background-color: #".$project->get_color()."'>";
                                        echo "<div class='project-detail'>";
                                            echo anchor("user/project/detail/{$project->get_id()}", $project->get_name(), "class='title'");
                                            echo "<p>".$project->get_created_name()."</p>";
                                            echo "<p>".$project->get_creation_date_pretty()."</p>";
                                        echo "</div>";
                                    echo "</div>";
                                }
                            }
                            ?>
                            <?php
                            /*
                            echo heading("Projects Summary", 3);
                            if(!empty($projects)){
                                echo "<ol>";
                                foreach($projects as $project){
                                    echo "<li>". $project->get_name() . "</li>";
                                    echo "<dl class='dl-horizontal'>";
                                        echo "<dt>"."Created By"."</dt>";
                                        echo "<dd>".$project->get_created_name()."</dd>";

                                        echo "<dt>"."Creation Date"."</dt>";
                                        echo "<dd>".$project->get_creation_date_pretty()."</dd>";

                                        echo "<dt>"."Last Modified By"."</dt>";
                                        echo "<dd>".$project->get_last_modified_name()."</dd>";

                                        echo "<dt>"."Last Modified Date"."</dt>";
                                        echo "<dd>".$project->get_last_modification_date_pretty()."</dd>";
                                    echo "</dl>";
                                }
                                echo "</ol>";
                            }

                            */
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-2">
                            <?php
                            echo heading("Create New Projects" , 3, "class='no-top-margin'");
                            ?>
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
                            <?php
                            echo br();
                            echo anchor($this->session->userdata('last_search_uri'), "Continue Searcing");
                            ?>
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
                </div>
            </div>
        </div>
    </div>
</div>