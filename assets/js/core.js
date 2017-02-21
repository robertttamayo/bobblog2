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
    $(".post-edit").on("click", function(){
        var postid = $(this).data("postid");
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php?postid=" + postid, function(){
            editContainerCallbacks();
            load("stop");
        });
    });
    
});
function catPopCallbacks(){
    $(".create-tag").on("click", function(){
        console.log("event listener fired");
        saveCat();
    });
    $(".tag-name").on("click", function(){
        if ($(this).hasClass("active-tag")) {
            removeCatFromPost($(this), $("#postid-hidden").text());
        } else {
            addCatToPost($(this), $("#postid-hidden").text());
        }
    });
    $(".dim, #close-tag-pop").on("click", function(){
        $(".tag-pop-wrap").fadeOut("medium", function(){
            $(this).remove();
        });
    });
}
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
    $(".post-edit").on("click", function(){
        var postid = $(this).data("postid");
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php?postid=" + postid, function(){
            editContainerCallbacks();
            load("stop");
        });
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
        var postid = $("#postid-hidden").text();
        var tagPop = document.createElement("div");
        var url = homeUrl + "src/tags.php?postid=" + postid;
        console.log(url);
        tagPop = $(tagPop);
        $(tagPop).load(url, function(){
            $("body").append($(tagPop));
            $(".dim").show().data("lock", "tags");
            tagPopCallbacks();
        });
    });
    $(".manage-cats").on("click", function(){
        var postid = $("#postid-hidden").text();
        var catPop = document.createElement("div");
        var url = homeUrl + "src/categories.php?postid=" + postid;
        console.log(url);
        catPop = $(catPop);
        $(catPop).load(url, function(){
            $("body").append($(catPop));
            $(".dim").show().data("lock", "tags");
            catPopCallbacks();
        });
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
function addCatToPost(catElement, postid) {
    $(".tag-name").removeClass("active-tag");
    catElement.addClass("active-tag");
    var catid = catElement.data("catid");
    console.log("adding tag " + catElement.data("catid") + " to post " + postid);
    $.post("#", {
        action: actionAddCatToPost,
        catid: catid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function removeCatFromPost(catElement, postid) {
    $(".tag-name").removeClass("active-tag");
    var catid = catElement.data("catid");
    console.log("removing tag " + catElement.data("catid") + " from post " + postid);
    $.post("#", {
        action: actionRemoveCatFromPost,
        catid: catid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function saveTag(){
    var tagName = $("#tag-create-input").val();
    var postid = $("#postid-hidden").text();
    console.log(tagName);
    $.post("#", {
        action: actionSaveTag,
        name: tagName,
        postid: postid
    }, function(_data){
        console.log("callback");
        _data = JSON.parse(_data);
        console.log(_data);
        var event = new CustomEvent("tag_saved", {"detail": _data });
        document.dispatchEvent(event);
        console.log("after dispatching event");
    });
}
function saveCat(){
    console.log("event function fired");
    var catName = $("#tag-create-input").val();
    var postid = $("#postid-hidden").text();
    console.log(catName);
    $.post("#", {
        action: actionSaveCat,
        name: catName,
        postid: postid
    }, function(_data){
        console.log("callback");
        _data = JSON.parse(_data);
        console.log(_data);
        var event = new CustomEvent("cat_saved", {"detail": _data });
        document.dispatchEvent(event);
        console.log("after dispatching event");
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
    
    tagName.addClass("tag-name")
            .addClass("active-tag")
            .data("tagid", data.tag_id)
            .text(data.tag_name)
            .appendTo("#active-tags");
    tagId.addClass("tag-id")
            .text(data.tag_id)
            .appendTo(tagName);
    
}, false);

document.addEventListener("cat_saved", function(event){
    console.log(event.detail);
    var data = event.detail;
    createMessage("New category \"" + data.cat_name + "\" created!");
    // create div and append it to end of tag list with the tag id and tag name
    var tagName = document.createElement("div");
    var tagId = document.createElement("div");
    
    tagName = $(tagName);
    tagId = $(tagId);
    
    tagName.addClass("tag-name")
            .addClass("active-tag")
            .data("catid", data.cat_id)
            .text(data.cat_name)
            .appendTo("#active-tags");
    tagId.addClass("tag-id")
            .text(data.cat_id)
            .appendTo(tagName);
    
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
