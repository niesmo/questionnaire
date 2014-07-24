<?php 
$totalCountQuestionnairs = count($questionnares);
$qs_each_col = ceil($totalCountQuestionnairs/3);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php 
            echo heading('List of All Questionnaires', 2);
            $hiddenInput = array("path"=>"questionnaire/search");
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
            <div class="row">
                <!-- The result should be like google results -->

                <div class="col-md-7">
                    <?php
                    foreach($questionnares as $questionnaire){
                        echo "<div class='questionnaire-item'>";
                            echo heading(anchor("questionnaire/detail/".$questionnaire->get_id(),$questionnaire->get_name()),3);
                            echo "<div class='detail'>";
                                echo "<dl class='dl-horizontal'>";
                                    echo "<dt>Author</dt>";
                                    echo "<dd>".$questionnaire->get_author()."</dd>";
                                    echo "<dt>Year</dt>";
                                    echo "<dd>".$questionnaire->get_year()."</dd>";
                                echo "</dl>";
                            echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
                <!-- I want to have the list of questionnaires in a 3 column list -->
                <?php
                    /*for($i=0; $i<3; $i++){
                        echo "<div class='col-md-4'><ul class='list-unstyled'>";
                        for($j=0; $j<$qs_each_col; $j++){
                            if (($i*$qs_each_col)+$j >= $totalCountQuestionnairs)
                                break;
                            $q_id = $questionnares[($i*$qs_each_col)+$j]->get_id();
                            $phrase = $questionnares[($i*$qs_each_col)+$j]->get_name();
                            echo "<li class='bottom-padding-sm'>".anchor("questionnaire/detail/".$q_id,$phrase)."</li>\n";
                        }
                        echo "</ul></div>";
                    }*/
                ?>
            </div>
        </div>
    </div>
</div>