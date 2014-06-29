<?php $questions = $questionnaire->get_questions();?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            echo heading("Questionnaire Detail", 2);
            $date = date_create($questionnaire->get_creation_date());
            ?>
            <dl class="dl-horizontal">
                <dt>Name</dt>
                <dd><?=$questionnaire->get_name()?></dd>
                <dt>Created By</dt>
                <dd><?=$questionnaire->get_user_name()?></dd>
                <dt>Creation Date</dt>
                <dd><?=date_format( $date, 'l jS \of F Y h:i:s A' )?></dd>
                <dt>Project</dt>
                <dd><?=$questionnaire->get_project_name()?></dd>
            </dl>

            <?php
            echo heading("Questions in this questionnaire", 3);
            ?>
            <ol>
                <?php
                if(empty($questions)){
                    echo "<p>There is currently no question in this questionnaire.</p>";
                }
                else{
                    foreach($questions as $question){
                        echo "<li>".anchor("user/question/detail/{$question->get_id()}" , $question->get_content()) . "</li>";
                    }
                }
                ?>
            </ol>
        </div>
    </div>
</div>