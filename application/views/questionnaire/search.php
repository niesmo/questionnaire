<?php
function highlight($text, $words) {
    preg_match_all('~\w+~', $words, $m);
    if(!$m)
        return $text;
    $re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
    return preg_replace($re, '<b>$0</b>', $text);
}

function highlightWords($string, $term){
    $term = preg_replace('/\s+/', ' ', trim($term));
    $words = explode(' ', $term);

    $highlighted = array();
    foreach ( $words as $word ){
        $highlighted[] = "<b>".$word."</b>";
    }

    return str_ireplace($words, $highlighted, $string);
}

$totalCountQuestionnairs = count($searchResult);
//$qs_each_col = ceil($totalCountQuestionnairs/3);
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
                <div class="col-md-9">
                    <?php
                    foreach($searchResult as $result){
                        $questions = $result->get_questions();
                        echo "<div class='questionnaire-item'>";
                            $qnName = $result->get_decorated_title($searchTerm);
                            echo heading(anchor("questionnaire/detail/".$result->get_id(),$qnName),3);
                            echo "<div class='detail'>";
                                echo "<dl class='dl-horizontal'>";
                                    echo "<dt>Author</dt>";
                                    $qnAuthor = highlight_phrase($result->get_author(),$searchTerm, '<b>','</b>');
                                    echo "<dd>".$qnAuthor."</dd>";
                                    echo "<dt>Year</dt>";
                                    $qnYear = highlight_phrase($result->get_year(),$searchTerm, '<b>','</b>');
                                    if($searchYear == $result->get_year()){
                                        $qnYear = "<b>".$result->get_year()."</b>";
                                    }
                                    echo "<dd>".$qnYear."</dd>";
                                echo "</dl>";
                            echo "</div>";
                            echo "<div class='related-questions'><ol>";

                                foreach($questions as $question){
                                    $highlighted = highlightWords($question->content, $searchTerm);
                                    echo "<li>".anchor("question/detail/{$result->get_id()}/{$question->question_id}",$highlighted)."</li>";
                                }
                            echo "</ol></div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>