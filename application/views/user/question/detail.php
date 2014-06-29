<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3" id="response">

        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?php
            echo heading("Question Detail", 2);
            $date = date_create($question->get_creation_date());
            ?>
            <dl class="dl-horizontal">
                <dt>Content</dt>
                <dd id="c_content"><?=$question->get_content()?></dd>
                <dt>Created By</dt>
                <dd><?=$question->get_user_name()?></dd>
                <dt>Creation Date</dt>
                <dd><?=date_format( $date, 'l jS \of F Y h:i:s A' )?></dd>
                <dt>Questionnaire</dt>
                <dd><?=$question->get_questionnaire_name()?></dd>
            </dl>
            <br />
            <button class="btn btn-primary" id="open-question-modification">Modify Question</button>
            <?= br(2);?>
            <fieldset id="modify-panel" class="hidden">
                <textarea id="content" rows="3" cols="50"><?=$question->get_content()?></textarea>
                <br />
                <button class="btn btn-success" id="save-question-content" data-question-id="<?=$question->get_id()?>">Save Changes</button>
                <button class="btn btn-warning" id="compare">Compare</button>
            </fieldset>
        </div>
        <div class="col-md-4" id="txt-sim-holder">
            <span id="similarity"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo heading("Modification History" ,3);
            if(empty($questionHistory)){
                echo "<p>There has been no changes to this question</p>";
            }
            else{
                echo "<ul class='list-unstyled'>";
                foreach($questionHistory as $qh){
                    echo "<li class='question-history-item' data-toggle='tooltip' data-placement='left' title='".$qh->get_modification_date("F j, Y, g:i a")."'>{$qh->get_content()} <span class='small grey'>by {$qh->get_modified_by_name()}</span></li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
</div>