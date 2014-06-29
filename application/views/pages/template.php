<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 id="front-page-logo">iUSuR</h1>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php
                    $hiddenInput = array("path"=>"questionnaire/search");
                    echo form_open("search/pre","", $hiddenInput);
                    ?>
                    <div class="input-group">
                        <input type="text" name="search" value="" class="form-control" placeholder="Search" autofocus="autofocus">
                        <span class="input-group-btn"><button type="submit" name="search-btn" value="search" class="btn btn-primary search-btn-width"><i class="fa fa-search"></i></button></span>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>