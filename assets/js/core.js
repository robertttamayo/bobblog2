var WELCOME = 1;
var EDITOR = 2;

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
}
function mainContainerCallbacks(){
    $(".dim").on("click", function(){
        $(".dim").hide();
        $("#message").fadeOut();
    });
    $("#message-close").on("click", function(){
        $(".dim").hide();
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
        var event = new CustomEvent("tag_saved", {tag_name: _data.tag_name});
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
document.addEventListener("post_saved", function (e){ 
    createMessage("Your post is safe!");
}, false);
document.addEventListener("tag_saved", function(event){
    createMessage("New tag \"" + event.tag_name + "\" created!");
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
