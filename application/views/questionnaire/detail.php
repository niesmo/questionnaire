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
            <ul class="list-unstyled">
            <?php
            foreach($questions as $question){
                $id= $question->get_id();
                $content =$question->get_content();
                echo "<li>".anchor("question/detail/{$qn_id}/{$id}/", $content)."</li>";
            }
            ?>
            </ul>
        </div>
    </div>
</div>