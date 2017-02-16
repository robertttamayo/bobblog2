var WELCOME = 1;
var EDITOR = 2;

var postid;

$(document).ready(function(){
    $("#main-container").html("").load("src/welcome.php", function(){
        mainContainerCallbacks();
    });
    
    $(".new-post").on("click", function(){
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php", function(){
            load("stop");
            editContainerCallbacks();
        });
    });
    $(".load-edit-post").on("click", function(){
        var postid = $(this).data("postid");
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php?postid=" + postid, function(){
            editContainerCallbacks();
            load("stop");
        });
    });
    
});
function tagPopCallbacks(){
    $(".create-tag").on("click", function(){
        saveTag();
    });
    $(".tag-name").on("click", function(){
        if ($(this).hasClass("active-tag")) {
            removeTagFromPost($(this), $("#postid-hidden").text());
        } else {
            addTagToPost($(this), $("#postid-hidden").text());
        }
    });
    $(".dim, #close-tag-pop").on("click", function(){
        $(".tags-pop-wrap").fadeOut("medium", function(){
            $(this).remove();
        });
    });
}
function mainContainerCallbacks(){
    $(".dim, #message-close").on("click", function(){
        $(".dim:not([data*=tags])").hide();
        $("#message").fadeOut();
    });
    
}
function editContainerCallbacks(){
    $(".save-post").on("click", function(){
        console.log("Attempting to save post");
        if (!validatePost()){
            return;
        }
        savePost();
    });
    $("#content").on("click focus", function(){
        $(this).html("")
            .find("div")
                .focus();
    });
    $(".manage-tags").on("click", function(){
        var tagPop = document.createElement("div");
        tagPop = $(tagPop);
        $(tagPop).load(homeUrl + "src/tags.php", function(){
            tagPopCallbacks();
        });
        $("body").append($(tagPop));
        $(".dim").show().data("lock", "tags");
    });
}
function addTagToPost(tagElement, postid) {
    tagElement.toggleClass("active-tag");
    var tagid = tagElement.data("tagid");
    console.log("adding tag " + tagElement.data("tagid") + " to post " + postid);
    $.post("#", {
        action: actionAddTagToPost,
        tagid: tagid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function removeTagFromPost(tagElement, postid) {
    tagElement.toggleClass("active-tag");
    var tagid = tagElement.data("tagid");
    console.log("removing tag " + tagElement.data("tagid") + " from post " + postid);
    $.post("#", {
        action: actionRemoveTagFromPost,
        tagid: tagid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function saveTag(){
    var tagName = $("#tag-create-input").val();
    console.log(tagName);
    $.post("#", {
        action: actionSaveTag,
        name: tagName
    }, function(_data){
        console.log(_data);
        _data = JSON.parse(_data);
        console.log(_data);
        var event = new CustomEvent("tag_saved", {"detail": _data });
        document.dispatchEvent(event);
    });
}
function savePost(){
    var raw = $("#content").html();
    var title = $("#post-title").val();
    var draft = false;
    var postid = $("#postid-hidden").text();
    console.log(postid);
    
    $.post("#", {    
        action: actionSavePost,
        name: title,
        file: raw,
        draft: draft,
        postid: postid
    },
    function(_data){ //callback for debugging
        console.log(_data);
        _data = JSON.parse(_data);
        $("#postid-hidden").text(_data.postid);
        var event = new Event("post_saved");
        document.dispatchEvent(event);
    });
}
var data;
function createMessage(message){
    $("#message-message").html(message);
    $(".dim").show();
    $("#message").fadeIn();
}
function validatePost(){
    var title = $("#post-title").val();
    if (title == "") {
        createMessage("Your post needs a title.");
        return false;
    }
    return true;
}
// Event listeners
document.addEventListener("post_saved", function (event){ 
    createMessage("Your post is safe!");
}, false);
document.addEventListener("tag_saved", function(event){
    console.log(event.detail);
    var data = event.detail;
    createMessage("New tag \"" + data.tag_name + "\" created!");
    // create div and append it to end of tag list with the tag id and tag name
    var tagName = document.createElement("div");
    var tagId = document.createElement("div");
    
    tagName = $(tagName);
    tagId = $(tagId);
    
    tagName.addClass("tag-name").text(data.tag_name).appendTo("#tag-name-wrapper");
    tagId.addClass("tag-id").text(data.tag_id).appendTo(tagName);
    
}, false);

function load(mode){
    switch(mode){
        case "start":
            $(".dim").show();
            $(".loading").show();
            break;
        case "stop":
            $(".dim").hide();
            $(".loading").hide();
            break;
    }
}
