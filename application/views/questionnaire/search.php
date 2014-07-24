<?php 
$totalCountQuestionnairs = count($questionnares);
$qs_each_col = ceil($totalCountQuestionnairs/3);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php 
            echo heading('Search Result', 2);
            $hiddenInput = array("path"=>"questionnaire/search"
            );
            echo form_open("search/pre","", $hiddenInput);
            ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div id="search-box" class="input-group" >
                            <?= form_input(array("name"=>"search", "class"=>"form-control typeahead", "data-ta-author"=>"true", "data-ta-questionnaire"=>"true" ,"placeholder"=>"Search", "autocomplete"=>"off","autofocus"=>"autofocus", "value"=>$searchTerm));?>
                            <span class="input-group-btn">
                                <?=form_submit(array("name"=>"search-btn", "value"=>"Search", "class"=>"btn btn-default"));?>
                            </span>

                        </div><!-- /input-group -->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="search-suggestions" class="hide">
                                    <div id="questionnaire"></div>
                                    <div id="author"></div>
                                </div>
                            </div>
                        </div>
                        <?php
                        anchor("questionnaire/", "Show all questionnaire");
                        ?>
                    </div><!-- /.col-lg-4 -->
                    <?php
                    echo form_hidden("field","all");
                    ?>
                    <div class="col-md-2">
                        <select name="year" class="form-control">
                            <option value="-1">Filter by year</option>
                            <?php
                            foreach($years as $year){
                                if($searchYear == $year){
                                    echo "<option value='{$year}' selected>".$year."</option>";
                                }
                                else{
                                    echo "<option value='{$year}'>".$year."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php
            echo form_close();
            //echo br();
            echo "<hr />";
            echo "<div><p class='search-stats'>{$totalCountQuestionnairs} results found ({$elapsedSearchTime} seconds)<p></div>";
            ?>
            <div class="row">
                <!-- The result should be like google results -->
                <div class="col-md-7">
                    <?php
                    foreach($questionnares as $questionnaire){
                        echo "<div class='questionnaire-item'>";
                            $qnName = highlight_phrase($questionnaire->get_name(),$searchTerm, '<b>','</b>');
                            echo heading(anchor("questionnaire/detail/".$questionnaire->get_id(),$qnName),3);
                            echo "<div class='detail'>";
                                echo "<dl class='dl-horizontal'>";
                                    echo "<dt>Author</dt>";
                                    $qnAuthor = highlight_phrase($questionnaire->get_author(),$searchTerm, '<b>','</b>');
                                    echo "<dd>".$qnAuthor."</dd>";
                                    echo "<dt>Year</dt>";
                                    $qnYear = highlight_phrase($questionnaire->get_year(),$searchTerm, '<b>','</b>');
                                    if($searchYear == $questionnaire->get_year()){
                                        $qnYear = "<b>".$questionnaire->get_year()."</b>";
                                    }
                                    echo "<dd>".$qnYear."</dd>";
                                echo "</dl>";
                            echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
                <!-- I want to have the list of questionnaires in a 3 column list -->
                <?php
                /*
                    for($i=0; $i<3; $i++){
                        echo "<div class='col-md-4'><ul class='list-unstyled'>";
                        for($j=0; $j<$qs_each_col; $j++){
                            if (($i*$qs_each_col)+$j >= $totalCountQuestionnairs)
                                break;
                            $q_id = $questionnares[($i*$qs_each_col)+$j]->get_id();
                            $phrase = $questionnares[($i*$qs_each_col)+$j]->get_name();
                            $phrase = highlight_phrase($phrase,$searchTerm, '<span style="background-color:yellow">','</span>');
                            echo "<li class='bottom-padding-sm'>".anchor("questionnaire/detail/".$q_id,$phrase)."</li>\n";
                        }
                        echo "</ul></div>";
                    }
                */
                ?>
            </div>
        </div>
    </div>
</div>