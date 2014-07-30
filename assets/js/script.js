var base_url = "http://localhost/questionnaire/index.php";
var requestInProgres = false;


function filter_other_questions_list(data, type) {
    $("#other-questions li:not([data-"+type+"='" + data + "'])").toggleClass("hide");
}

function filder_other_questions_same_concept(concept) {
    $("#other-questions li:not([data-concept='" + concept+ "'])").toggleClass("concept-filtered");
    $("#other-questions li:not([data-concept='" + concept+ "']):not(.category-filtered)").toggleClass("hide");
}

function filder_other_questions_same_category(category) {
    $("#other-questions li:not([data-category='" + category + "'])").toggleClass("category-filtered");
    $("#other-questions li:not([data-category='" + category + "']):not(.concept-filtered)").toggleClass("hide");
}

function create_questionnaire(questionnaireData, callback) {
    $.ajax({
        url: base_url + "/user/ajax/create_questionnaire",
        type: "POST",
        data: questionnaireData ,
        success: function (data) {
            //parse the result
            data = trim(data);
            if (data.indexOf("SUCCESS") != -1) {
                data = data.split(":");
                questionnaireData["newQuestionnaire_id"] = data[1];
                callback(questionnaireData);
            }
            else {
                alert("Something went wrong! Please try again.");
            }
        }
    });
}

function show_new_questionnaire(questionnaireData) {
    var questionnaireList = $("ul#questionnaire-list");
    var newElement = "<li class='has-remove-icon' data-questionnaire-id='" + questionnaireData['newQuestionnaire_id'] + "'><a href='" + base_url + "/user/questionnaire/" + questionnaireData['newQuestionnaire_id'] + "'>" + questionnaireData['questionnaireName'] + "</a><a href='#' class='pull-right hidden remove-questionnaire-item trash-can' data-questionnaire-id='" + questionnaireData['newQuestionnaire_id'] + "'><i class='fa fa-trash-o '></i></a></li>";

    $("#no-questionnaire").remove();
    questionnaireList.append(newElement);
    bind_trash_icon(".has-remove-icon");
    $(".remove-questionnaire-item").on("click", function () {
        var questionnaire_id = $(this).attr("data-questionnaire-id");
        prep_for_remove_questionnaire(questionnaire_id);
    });

    $("#questionnaire-name").val("");
}

function edit_project_description(projectData, callback) {
    $.ajax({
        url:base_url + "/user/ajax/set_project_description/",
        type: "POST",
        data: projectData,
        success:alertMessages
    })
}

function get_questionnaires(project_id, callback) {
    var results;
    $.ajax({
        url: base_url + "/user/ajax/get_questionnaires",
        type:"POST",
        data: { project_id: project_id },
        success: function (data) {
            data = trim(data);
            
            if (data == "EMPTY") {
                callback({});
                return;
            }
            data = data.split("\n");
            results = Array(data.length);
            var questionnaire;
            for (var i = 0; i < data.length; i++) {
                questionnaire = data[i].split(', ');
                results[i] = {
                    'id': questionnaire[0],
                    'name': questionnaire[1]
                };
            }

            callback(results);
        }
    });    
}

function add_to_select(select_id, newList) {
    // the `newList` is an array that each element should have two
    // members:
    // 1) id
    // 2) name

    //removing all the options:
    $("#" + select_id + " option").each(function () { $(this).remove(); });

    if ($.isEmptyObject(newList)) {
        return;
    }

    //add ing the please select option
    $("#" + select_id).append("<option value='" + -1 + "'>" + "Please select a questionnaire" + "</option>")

    newList.forEach(function (element, index, array) {
        $("#" + select_id).append("<option value='" + element['id'] + "'>" + element['name'] + "</option>")
    });
}

function add_user_question(user_questionnaire_id, original_questionnaire_id, question_id, project_id, callback) {
    $.ajax({
        url: base_url + "/user/ajax/add_question",
        type: "POST",
        data: {
            user_questionnaire_id: user_questionnaire_id,
            original_questionnaire_id :original_questionnaire_id,
            question_id: question_id,
            project_id:project_id
        },
        success: function (data) {
            callback(data);
        }
    })
}

function remove_project(project_id, callback) {
    $.ajax({
        url: base_url + "/user/ajax/remove_project",
        type: "POST",
        data: { project_id: project_id },
        success: function (data) {
            callback(data);
        }
    });
}

function prep_for_remove_project(project_id) {
    if (confirm("Are you sure you want to remove this project?")) {
        remove_project(project_id, function (data) {
            data = trim(data);
            if (data == "SUCCESS") {
                //remove the li from the list
                //$("#project-list li[data-project-id=" + project_id + "]").fadeOut(300, function () { $(this).remove(); });
                console.log("Item was deleted");
                window.location = base_url + "/user/dashboard";
            }
            else {
                alert("Something went wrong! Please refresh and try again.");
            }
        });
    }
}

function remove_user_questionnaire(questionnaire_id, callback) {
    $.ajax({
        url: base_url + "/user/ajax/remove_user_questionnaire",
        type: "POST",
        data: { questionnaire_id: questionnaire_id },
        success: function (data) {
            callback(data);
        }
    });

}

function prep_for_remove_questionnaire(questionnaire_id) {
    if (confirm("Are you sure you want to remove this questionnaire?")) {
        remove_user_questionnaire(questionnaire_id, function (data) {
            data = trim(data);
            if (data == "SUCCESS") {               
                //remove the li from the list
                $("#questionnaire-list li[data-questionnaire-id=" + questionnaire_id + "]").fadeOut(300, function () { $(this).remove(); });
            }
            else {
                alert("Something went wrong! Please refresh and try again.");
            }
        });
    }
}

function compare_contents(current, recent, callback) {
    $.ajax({
        url: base_url + "/user/ajax/compare_content",
        type: "POST",
        data: {
            current: current,
            newContent: recent
        },
        success: function (data) {
            callback(data);
        }
    });
}

function change_user_question_content(question_id, newContent, callback) {
    $.ajax({
        url: base_url + "/user/ajax/change_user_question",
        type: "POST",
        data:{
            question_id: question_id,
            content: newContent
        },
        success: function (data) {
            callback(data);
        }
    });
}

function update_response(status , responses) {
    status = trim(status);
    if (status == "SUCCESS") {
        //show a success message
        var successElement = "<p class='bg-success'>"+responses['success']+"</p>";
        $("#response").append(successElement).fadeIn(500).delay(2000).fadeOut(500, function () {
            $("#response p").remove();
        });
    }
    else if (status == "REPEAT") {
        var successElement = "<p class='bg-warning'>" + responses['warning'] + "</p>";
        $("#response").append(successElement).fadeIn(500).delay(2000).fadeOut(500, function () {
            $("#response p").remove();
        });
    }
    else {
        var errorElement = "<p class='bg-danger'>Something went wrong! Please try again</p>";
        $("#response").append(errorElement).fadeIn(500).delay(2000).fadeOut(500, function () {
            $("#response p").remove();
        });
        console.log(status);
    }
}

function invite_collaborator(email, project_id, callback) {
    $.ajax({
        url: base_url + "/user/ajax/invite_collaborator",
        type: "POST",
        data: {
            email: email,
            project_id: project_id
        },
        success: function (data) {
            callback(data);
        }
    });
}

function get_notifications(callback) {
    $.ajax({
        url: base_url + "/user/ajax/get_notifications",
        type: "POST",
        success: function (data) {
            callback(data);
        }
    });

}

function accept_collaboration(request_id) {
    $.ajax({
        url: base_url + "/user/ajax/accept_collaboration",
        type: "POST",
        data: {
            request_id: request_id
        },
        success: function (data) {
            data = trim(data);
            console.log(data);
            if (data == "SUCCESS") {
                console.log(data ," yay");
                //remove the notification item from the list
                //if there is nothing left, close it and get rid of the whole notification badge
                $("#notifications li[data-id='" + request_id + "']").fadeOut(500, function () {

                    $("#notifications li[data-id='" + request_id + "']").remove();
                    if ($("#notifications li").length == 0) {
                        $("#notification-list").addClass("hidden");
                        $(".notification-badge").text("");
                    }
                    else {
                        var notificationNumber = parseInt($(".notification-badge").text());
                        $(".notification-badge").text(notificationNumber-1);
                    }
                });
            }
            else {
                alert("Something went wrong! Please refresh and try again");
            }
        }
    });
}

function decline_collaboration(request_id) {
    $.ajax({
        url: base_url + "/user/ajax/decline_collaboration",
        type: "POST",
        data: {
            request_id: request_id
        },
        success: function (data) {
            data = trim(data);
            console.log(data);
            if (data == "SUCCESS") {
                $("#notifications li[data-id='" + request_id + "']").fadeOut(500, function () {

                    $("#notifications li[data-id='" + request_id + "']").remove();
                    if ($("#notifications li").length == 0) {
                        $("#notification-list").addClass("hidden");
                        $(".notification-badge").text("");
                    }
                    else {
                        var notificationNumber = parseInt($(".notification-badge").text());
                        $(".notification-badge").text(notificationNumber - 1);
                    }
                });
            }
            else {
                alert("Something went wrong! Please refresh and try again");
            }
        }
    });
}

function suggest_search(search){
    $.ajax({
        url:base_url+"/ajax/suggest_search",
        type:"POST",
        data:{
            "search":search.search,
            "filter":search.filter
        },
        success:function(data){
            data = trim(data);
            var resQn = new Array();
            if(data!=""){
                var questionnaires = $(data);
                questionnaires.find("questionnaire").each(function (index) {
                    var parsedQn = {
                        "id":$(this).attr("id"),
                        "name":$(this).find("name").text(),
                        "author":$(this).find("author").text(),
                        "year":$(this).find("year").text()
                    };
                    resQn.push(parsedQn);
                });
                if(search.search.name != undefined){
                    populate_search_suggestion(resQn,"questionnaire", "Questionnaire");
                }
                else if(search.search.author != undefined){
                    populate_search_suggestion(resQn,"author", "Author");
                }
            }
            else{
                if(search.search.name != undefined){
                    wipe_search_suggestion("questionnaire");
                }
                else if(search.search.author != undefined){
                    wipe_search_suggestion("author");
                }
            }
        },
        error:function(data){
            console.log(data.responseText);
            console.log("SOMETHING WENT WRONG IN THE AJAX CALL FOR SUGGESTING SEARCH TERMS");
        }
    })
}

function wipe_search_suggestion(id){
    $("#"+id).empty();
    if($("#author").is(":empty") && $("#questionnaire").is(":empty")){
        $("#search-suggestions").addClass("hide");
    }
}

function populate_search_suggestion(result, id, catName){
    if(result.length == 0){
        $("#"+id).empty();
        return;
    }
    //$("#search-suggestions").width($("#search-box").width());
    var itemsHtml = "<div class='title'><p>"+catName+"</p></div>";
    for(var i =0;i<result.length;i++){
        itemsHtml += "<div data-id='"+result[i].id+"' class='item'>"+
            "<p>"+(id=="author"?result[i].author:result[i].name)+"</p>"
            +"</div>";
    }
    $("#"+id).empty();
    $("#"+id).append(itemsHtml);

    $("#search-suggestions").removeClass("hide");
}

$( document ).ajaxStart(function() {
    requestInProgres = true;
});

$( document ).ajaxComplete(function(){
    requestInProgres = false;
});

$(document).on('click', '#questionnaire .item', function(){
    var id = $(this).data("id");
    window.location = base_url + "/questionnaire/detail/"+id;
});

$(document).on('click', '#author .item', function(){
    var id = $(this).data("id");
    var author = $(this).text();
    var year = $("select[name=year]").val();
    var url = window.location;
    window.location = base_url + "/questionnaire/search/"+author+"/author/"+year;
});

$(document).on( 'click', function ( e ) {
    if ( $( e.target ).closest( "#search-suggestions" ).length === 0 ) {
        $("#search-suggestions").addClass("hide");
    }
});

$(document).ready(function () {
    
    $('form').validator();
    $('#quick-add').tooltip();
    $('.question-history-item').tooltip();

    $(".add-selected-question").click(function(){
        var checkboxes = $('.q_checkbox:checked');
        if(checkboxes.length === 0){
            return false;
        }

        var data = new Array(checkboxes.length);
        for(var i=0;i<checkboxes.length;i++){
            data[i] = checkboxes[i].getAttribute("name");
        }
        console.log(data);
        //alert("This feature has not be completely implemented yet!")
    });

    $(".q_checkbox").change(function(){
        $(".add-selected-question").removeClass("disabled");
        var checkedLen = $('.q_checkbox:checked').length;
        if(checkedLen === 0){
            $(".add-selected-question").text("Add Selected Questions");
            $(".add-selected-question").attr("disabled", true);

        }
        else{
            $(".add-selected-question").text("("+checkedLen+") Add Selected Questions");
            $(".add-selected-question").attr("disabled", false);
        }
    });

    $(".item").on("click",function(){
       alert($(this).data("id"));
    });

    $(".typeahead").keyup(function(e){
        if(e.keyCode === 27){ //ESC
            $("#search-suggestions").addClass("hide");
            return;
        }
        var searchTerm = trim($(this).val());
        var year = $("select[name=year]").val();
        if(searchTerm == ""){
            $("#search-suggestions").addClass("hide");
            return;
        }

        var search = {
            "search":{},
            "filter":{
                "year":year
            }
        };

        if($(this).data("ta-questionnaire")){
            search.search ={"name":searchTerm};
            suggest_search(search);
        }

        var search = {
            "search":{},
            "filter":{
                "year":year
            }
        };

        if($(this).data("ta-author")){
            search.search ={"author":searchTerm};
            suggest_search(search);
        }

    });

    $(".notification-badge").click(function () {
        
        if ($("#notification-list").hasClass("hidden")) {
            get_notifications(function (data) {
                console.log(data);
                var res = $.parseXML(data);
                var from, date, project, id ;
                var notifications = $(res);
                
                notifications.find("notification").each(function (index) {
                    id = $(this).attr("data-id");
                    from = $(this).find("from");
                    date = $(this).find("date").text();
                    project = $(this).find("project");
                    project_id = project.attr("data-id");
                    from_id = from.attr("data-id");

                    $("#notification-list ul").append("<li data-id='"+id+"'><div class='notification-item-left'><p>Collaboration Request</p><p>From <strong>" + from.text() + "</strong></p><p>Project <strong>" + project.text() + "</strong></p></div><div class='notification-item-right' data-project-id='" + project_id + "' data-sender='" + from_id + "'><button class=' btn btn-success btn-sm' onclick='accept_collaboration(" + id + ");'><i class='fa fa-check'></i></button><button class='btn btn-danger btn-sm' onclick='decline_collaboration(" + id + ");'><i class='fa fa-times'></i></button></div><hr class='separator' /></li>");
                });
                
            });
        }
        else {
            $("#notification-list ul").empty();
        }
        $("#notification-list").toggleClass("hidden");
        
    });

    $("#invite-collaborator").click(function () {
        var email = $("#collaborator-email").val();
        var project_id = $(this).attr("data-project-id");
        var thisBtn = $(this);

        if (email == "" || is_valid_email(email) == false) {
            $(this).closest("div").addClass("has-error").delay(1000).queue(function () {
                $(this).removeClass("has-error");
                $(this).dequeue();
            });
            return;
        }
        
        thisBtn.button("loading");
        invite_collaborator(email,project_id, function (data) {
            data = trim(data);
            console.log(data);
            update_response(data, {
                "success": "The collaborator is successfully invited",
                "warning": "The collaborator is already a part of the project"
            });
            thisBtn.button("reset");
        });

    });

    $("#compare").click(function () {
        var curContent = $("#c_content").text();
        var newContent = $("#content").val();
        var compareBtn = $(this);

        console.log("Current : ", curContent);
        console.log("New : ", newContent);
        compareBtn.button('loading');
        compare_contents(curContent, newContent, function (data) {
            console.log("Result raw : ", data);
            percent = parseFloat(data);
            data = percent * 100;
            if (data < 25) {
                $("#similarity").css("color","red");
            }
            else if(data<75){
                $("#similarity").css("color","orange");
            }
            else {
                $("#similarity").css("color","green");
            }

            $("#similarity").text(data + "%");
            compareBtn.button('reset');
        });
    });

    $("#save-question-content").click(function () {
        var newContent = $("#content").val();
        var question_id = $(this).attr("data-question-id");

        change_user_question_content(question_id, newContent, function (data) {
            update_response(data, {
                "success":"The question was successfully saved"
            });
            if(trim(data) == "SUCCESS")
                $("#c_content").text(newContent);
        })
        
    });

    $("#open-question-modification").click(function () {
        $("#modify-panel").toggleClass("hidden");
    }); 

    $("#quick-add").click(function () {
        var project_id = $(this).attr("data-project-id");
        var user_questionnaire_id = $(this).attr("data-questionnaire-id");
        var question_id = $(this).attr("data-question-id");
        var questionnaire_id = $(this).attr("data-original-questionnaire");

        add_user_question(user_questionnaire_id, questionnaire_id, question_id, project_id, function (data) {
            data = trim(data);
            if (data == "SUCCESS") {
                //show a success message
                var successElement = "<p class='bg-success'>The question was successfully added</p>";
                $("#message").append(successElement).delay(1000).fadeOut(500, function () {
                    $("#message p").remove();
                });
            }
            else {
                var errorElement = "<p class='bg-danger'>Something went wrong! Please try again</p>";
                $("#message").append(errorElement).delay(1000).fadeOut(500, function () {
                    $("#message p").remove();
                });
                console.log(data);
                //alert("Something went wrong! Please refresh and try again");
            }

        })

    });

    $(".remove-project-item").click(function () {
        var project_id = $(this).attr("data-project-id");
        prep_for_remove_project(project_id);

    });

    $(".remove-questionnaire-item").click(function () {
        var questionnaire_id = $(this).attr("data-questionnaire-id");
        prep_for_remove_questionnaire(questionnaire_id);
    });

    $("#add_question_modal").click(function () {
        var user_questionnaire_id = $("#questionnaire-list option:selected").val();
        var project_id = $("#project-list option:selected").val();
        var questionnaire_id = $(this).attr("data-original-questionnaire");
        var question_id = $(this).attr("data-question-id");
        


        add_user_question(user_questionnaire_id, questionnaire_id, question_id,project_id, function (data) {
            data = trim(data);
            if (data == "SUCCESS") {
                //show a success message
                var successElement = "<p class='bg-success'>The question was successfully added</p>";
                $("#message").append(successElement).delay(1000).fadeOut(500, function () {
                    $("#message p").remove();
                });
            }
            else {
                var errorElement = "<p class='bg-danger'>Something went wrong! Please try again</p>";
                $("#message").append(errorElement).delay(1000).fadeOut(500, function () {
                    $("#message p").remove();
                });
                console.log(data);
                //alert("Something went wrong! Please refresh and try again");
            }
        })
    });

    $("#project-list").change(function () {
        var project_id = $(this).val();
        if (project_id == -1)
            return;

        $("#project-detail-link").attr('href', base_url + "/user/project/detail/" + project_id);
        get_questionnaires(project_id, function (questionnaires) {
            add_to_select("questionnaire-list", questionnaires);
        });
    })

    $("#edit-description").click(function () {
        var descriptionBox = $("#project-description");

        descriptionBox.prop('disabled', false);
        descriptionBox.focus();
        descriptionBox.val("");
        $(this).addClass("hidden");
        $("#save-description").removeClass("hidden");
    });

    $("#save-description").click(function () {
        var descriptionBox = $("#project-description");
        
        if (descriptionBox.val() == "") {
            descriptionBox.val("No description is defined");
        }
        else {
            var projectData = {
                project_id: $(this).attr("data-project-id"),
                description: descriptionBox.val()
            };
            edit_project_description(projectData, alertMessages);
        }

        descriptionBox.prop('disabled', true);
        $(this).addClass("hidden");
        $("#edit-description").removeClass("hidden");
    });

    $("#create-questionnaire").click(function () {
        var data = {
            questionnaireName: $("#questionnaire-name").val(),
            project_id:$(this).attr("data-project-id")
        };
        create_questionnaire(data, show_new_questionnaire);

    });

    /*
    $("#add-to-project").click(function () {
        var q_id = $(this).attr("data-q-id");
        var qn_id = $(this).attr("data-qn-id");
        var project_id = $(this).attr("data-project-id");

        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: base_url + "/user/ajax/add_question",
            type: "POST",
            data: {
                "project_id": project_id,
                "questionnaire_id": qn_id,
                "question_id": q_id,
            },
            success: function (data) {
                data = trim(data);
                $("#message").show();
                if (data != "SUCCESS") {
                    
                    $("#message").addClass("bg-danger");
                    $("#message").text("Something went wrong while adding this question. Please refresh and try again.");

                }
                else {
                    $("#message").addClass("bg-success");
                    $("#message").text("Question was successfully added to the project");
                }
                $("#message").delay(3000).fadeOut();
            }
        }).always(function () {
            btn.button('reset')
            $('#add-to-project').prop('disabled', true);
        });

    });*/

    $("#set-default-project").click(function () {
        var defaultProject_id = $("#default-project-list :selected").val();

        //checking if they have selected the `please select`
        if (defaultProject_id == -1)
            return;

        //ajax call
        $.ajax({
            url:base_url + "/user/ajax/set_default",
            type:"POST",
            data:{"defaultProjectId":defaultProject_id},
            success: function (data) {
                data = trim(data)
                if (data != "SUCCESS") {
                    alert("Something went wrong! Please refresh the page and try again!");
                }
                
            }
        });

        //bolding the new default
        $("#project-list li.bold").removeClass("bold");
        $("#project-list li[data-project-id='" + defaultProject_id + "']").addClass("bold");

    });

    $("#create-project").click(function () {
        //clearing the error box
        $("#project-errors").text("");
        var projectName = $("#project-name").val();

        if (projectName == "") {
            $("#project-errors").fadeIn("fast").append("<p class='bg-danger'>Please enter a name for the project</p>").delay(1000).fadeOut("fast",function(){
                $("#project-errors p").remove();
            });
            return;
        }
        if (projectName.length > 45) {
            $("#project-errors").fadeIn("fast").append("<p class='bg-danger'>The project name is too long ( should be less than 45 charactors ) </p>").delay(1000).fadeOut("fast", function(){
                $("#project-errors p").remove();
            });
            return;
        }
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: base_url + "/user/ajax/create_project",
            type: "POST",
            data: { "name": projectName },
            success: function (data) {
                if (data.indexOf("SUCCESS") != -1) {
                    //trimming the `data` 
                    data = trim(data);

                    //getting the project_id
                    var info = data.split(':');
                    var project_id = info[1];

                    //remove the paragraph tag that say `there is no project`
                    $("#no-project").remove();

                    //adding the new text to the current project list
                    $("#project-list").append("<li class='has-remove-icon' data-project-id='" + project_id + "'><a href='" + base_url + "/user/project/detail/" + project_id + "'>" + projectName + "</li>");

                    //</a><a href='#' class='pull-right remove-project-item hidden trash-can' data-project-id='" + project_id + "'><i class='fa fa-trash-o'></i></a>
                    /*bind_trash_icon(".has-remove-icon");
                    $(".remove-project-item").on("click", function () {
                        var project_id = $(this).attr("data-project-id");
                        prep_for_remove_project(project_id);
                    });
                    
                    $("#default-project-list").append("<option value='" + project_id + "'>" + projectName + "</option>");
                     */
                    $("#project-name").val("");
                }
                else {
                    alert("Something went wrong! Refresh and try again!");
                    console.log(data);
                }
            }
        }).always(function(){
            btn.button('reset');
        });
        $(this).button("reset");  

    });

    $("#ajax_question_submit").click(function () {

        var q_id = $("input[name=question_id]").val();
        var content = trim($("#content").val());
        var concept = trim($("#concept").val());
        var scale = $("#scale").val();
        var category_id = $("#category").val();


        $.ajax({
            url: base_url+"/admin/ajax/question_update",
            type: "POST",
            data: {
                "question_id":q_id,
                "content": content,
                "concept": concept,
                "scale": scale,
                "category_id": category_id
            },
            success: function (data) {
                $(".error-box ul li").remove();
                if (data == true) {
                    $(".error-box ul").append("<li class='list-group-item list-group-item-success'>The question is successfully updated</li>");
                    $(".quick-question-edit[data-q-id='" + q_id + "']").html(content).fadeIn("slow");
                }
                else {
                    var errors = data.split(", ");
                    console.log(errors);
                    for (var i = 0; i < errors.length; i++)
                        $(".error-box ul").append("<li class='list-group-item list-group-item-danger'>"+errors[i]+"</li>");
                }
                $(".error-box").hide().fadeIn("slow").delay(2000).fadeOut(500);
                
            }
        })
    });

    $("#same-category-question-filter").click(function () {
        var m_category = $(this).attr("data-category");
        filder_other_questions_same_category(m_category);
        $(this).toggleClass("btn-info active");

    });

    $("#same-concept-question-filter").click(function () {
        var m_concept = $(this).attr("data-concept");
        filder_other_questions_same_concept(m_concept);
        $(this).toggleClass("btn-info active");
    })

    $(".quick-question-edit").click(function (e) {

        var topPos = $('#edit_question_heading').position();
        var bottomPos = $("#end_of_page").position();
        var relY = e.pageY - topPos.top - 515;
        if (relY > bottomPos.top - 500) {
            relY = bottomPos.top - 400;
        }
        $(".quick-question-edit.bg-success").removeClass("bg-success");
        $(this).addClass("bg-success");
        var q_id = $(this).attr("data-q-id");
        $("#question_update").removeClass("hide");
        $("#question_update").css("top", relY);


        $.ajax({
            type: "GET",
            url: base_url + "/admin/ajax/get_question_info/" + q_id,
            success: function (data) {
                console.log(data);
                var regex = "(.*)"; //"(.|[\r\n])";
                var content = data.match("content:"+regex+":content")[1];
                var concept = data.match("concept:" + regex + ":concept")[1];
                var scale = data.match("scale:" + regex + ":scale")[1];
                var category_id = data.match("category_id:"+regex+":category_id")[1];


                $("input[name=question_id]").val(q_id);
                $("#content").val(content);
                $("#concept").val(concept);
                $("#scale").val(scale);
                $("#category").val(category_id);
                


            }
        });

        return false;
    });

    $(".has-remove-icon").mouseenter(function () {
        $(this).find("a.trash-can").removeClass("hidden");
    });
    $(".has-remove-icon").mouseleave(function () {
        $(this).find("a.trash-can").addClass("hidden");
    });

});

function is_valid_email(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function bind_trash_icon(selector) {
    $(selector).on("mouseenter", function () {
        $(this).find("a.trash-can").removeClass("hidden");
    });
    $(selector).on("mouseleave", function () {
        $(this).find("a.trash-can").addClass("hidden");
    });
}

function trim(data) {
    return data.replace(/^\s+|\s+$/g, '');
}

function alertMessages(data) {
    console.log(data);
}
