function Guid() {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x7|0x8)).toString(16);
    });
    return uuid;
};

function popup(title, message, extraBtns){
	var divId = Guid();
	$("<div/>", {
		id : divId,
		'class':"alert-popup absolute-center"
	}).appendTo("body");
	
	//adding the title div
	$("<div/>", {
		'class':"title"
	}).appendTo("#"+divId);
	
	//adding the paragraph for the title
	$("<p/>",{
		text:title
	}).appendTo("#"+divId+" .title");
	
	//adding the body div
	$("<div/>", {
		'class':"body"
	}).appendTo("#"+divId);
	
	//adding the paragraph for the message
	$("<p/>", {
		text:message
	}).appendTo("#"+divId+" .body");
	
	//adding the buttons div
	$("<div/>", {
		'class':"btns"
	}).appendTo("#"+divId);
	
	//creating the ok button
	$("<button/>",{
		text:"Okay",
        'class':"btn btn-sm btn-success"
	}).appendTo("#"+divId + " .btns").bind("click", function(){
		$("#" + divId).fadeOut("slow", function(){
			this.remove();
		});
	});
	
	if(extraBtns != undefined){
        $.each(extraBtns, function(i, val){
            $("<a/>",{
                href:val.uri,
                text:val.title,
                'class':"btn btn-sm btn-primary"
            }).appendTo("#"+divId + " .btns");
        });
    }
	
	return divId;
}

//how to use it
/*
$(document).ready(function(){
	$("#btn-id").click(function(){
		var btns = [{title:"view", uri:"http://google.com"}, {title:"Check", uri:"http://microsoft.com"}];
		popup("title","This is where the message usually would go!", btns);
	});
});
*/