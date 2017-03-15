var WELCOME = 1;
var EDITOR = 2;

var postid;

var iconLeftJustify = "<i class=\"fa fa-align-left\" aria-hidden=\"true\"></i>";
var iconCenterJustify = "<i class=\"fa fa-align-center\" aria-hidden=\"true\"></i>";
var iconRightJustify = "<i class=\"fa fa-align-right\" aria-hidden=\"true\"></i>";
var iconRemove = "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>";
var iconClose = "<i class=\"fa fa-window-close-o\" aria-hidden=\"true\"></i>";

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
    initEditor();
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
function uploadImage(imgData){
    console.log("Uploading image");
    var data = {
            "action": actionUploadImage, 
            "imgdata": imgData
        };
    console.log(data);
    $.ajax({
        url: "#",
        type: "POST",
        processData: false,
        contentType: false,
        data: imgData
    }).done(function(_data){
        console.log("done uploading image");
        console.log(_data);
        var event = new Event("image_uploaded");
        document.dispatchEvent(event);
    });
//    $.post("#", {
//        action: actionUploadImage,
//        processData: false,
//        contentType: false,
//        imgdata: imgData
//    }, function(_data){
//        console.log("done uploading image");
//        console.log(_data);
//        var event = new Event("image_uploaded");
//        document.dispatchEvent(event);
//    });
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
document.addEventListener("image_uploaded", function(event){
    createMessage("The image was uploaded");
});
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

/** EDITOR FUNCTIONS */
function initEditor(){
    // bold italic underline strike
    $("#italic").on("click", function(){
        document.execCommand("italic");
    });
    $("#bold").on("click", function(){
        document.execCommand("bold");
    });
    $("#underline").on("click", function(){
        document.execCommand("underline");
    });
    $("#strike").on("click", function(){
        document.execCommand("strikeThrough");
    });
    // justify block
    $("#left").on("click", function(){
        document.execCommand("justifyLeft");
    });
    $("#center").on("click", function(){
        document.execCommand("justifyCenter");
    });
    $("#right").on("click", function(){
        document.execCommand("justifyRight");
    });
    $("#justify").on("click", function(){
        document.execCommand("justifyFull");
    });
    // undo redo
    $("#undo").on("click", function(){
        document.execCommand("undo");
    });
    $("#redo").on("click", function(){
        document.execCommand("redo");
    });
    // font types
    $("#header").on("click", function(){
        document.execCommand("formatBlock", false, "H2");
    });
    //colors
    $("#show-colors-modal").on("click", function(){
       $("#colors-modal").css("display", "inline-block");
    });
    $(".color-choose").on("click", function(){
        console.log("stuff is happen");
        var selection = document.getSelection();
        console.log("selection" + selection);
        var r = $(this).data("r");
        var g = $(this).data("g");
        var b = $(this).data("b");
        var color = "rgb(" + r + ", " + g + ", " + b + ")";
        document.execCommand("foreColor", false, color);
    });
    // links 
    $("#link").on("click", function(){
        var selection = document.getSelection();
        if (selection == "") {
            return;
        } 
        var value = "";
        if (selection.anchorNode.parentNode){
            if (selection.anchorNode.parentNode.href){
                value = selection.anchorNode.parentNode.href;
            }
        }
        var href = prompt("Enter the link address", value);
        
        if (href != null) {
            if (!href.includes("http://") && !href.includes("https://")) {
                href = "http://" + href;
            }
            document.execCommand("createLink", false, href);
        }
    });
    $("#unlink").on("click", function(){
        document.execCommand("unlink");
    });
    // lists
    $("#ol").on("click", function(){
        document.execCommand("insertOrderedList");
    });
    $("#ul").on("click", function(){
        document.execCommand("insertUnorderedList");
    });
    // images
    $("#image").on("click", function(){
        document.getElementById("img-dialog").showModal();
    });
        
    $("#image-form").on("submit", function(event){
        console.log("image form submitted");
        event.preventDefault();

        var imgData = new FormData(document.getElementById("image-form"));
        imgData.append("action", actionUploadImage);
        uploadImage(imgData);
    });
//        if (document.execCommand("insertImage", false, "http://placebear.com/300/200")) {
//           $("#content").find("img").each(function(){
//               if (!$(this).hasClass("edit-img")){
//                   var suffix = new Date().getTime();
//                   var className = "edit-img" + suffix;
//                   wrapImage($(this), suffix);
//                   attachImageEditor($(this), suffix);
//                   $(this).addClass("edit-img").addClass(className).on("click", function(){
//                       
//                   });
//                   imgEditorCallbacks();
//               }
//           }); 
//        } 
//    });
}

function wrapImage(imgElem, suffix){
    imgElem
        .wrap("<div class=\"inline img-editor-wrap img-editor-wrap" + suffix + "\"></div>")
        .on("click", function(){
        $(this)
            .parent()
            .find(".img-editor-bar")
                .show();
    });
}

function attachImageEditor(imgElem, suffix){
    imgElem.after(
        "<div class='img-editor-bar flex flex-hor container'>" +
            "<div data-imgwrap='.img-editor-wrap" + suffix + "' data-imgtarget='.edit-img" + suffix + "' class='img-editor-group flex flex-hor'>" +
                "<button type='button' class='btn img-small'><i class='fa fa-picture-o' aria-hidden='true'></i></button>" +
                "<button type='button' class='btn img-medium'><i class='fa fa-picture-o' aria-hidden='true'></i></button>" +
                "<button type='button' class='btn img-large'><i class='fa fa-picture-o' aria-hidden='true'></i></button>" +
                "<button type='button' class='btn img-full'><i class='fa fa-arrows-h' aria-hidden='true'></i></button>" +
            "</div>" +
            "<div data-imgwrap='.img-editor-wrap" + suffix + "' data-imgtarget='.edit-img" + suffix + "' class='img-editor-group flex flex-hor'>" +
                "<button class='btn img-left'>" + iconLeftJustify + "</button>" +
                "<button class='btn img-center'>" + iconCenterJustify + "</button>" +
                "<button class='btn img-right'>" + iconRightJustify + "</button>" +
            "</div>" +
            "<div data-imgwrap='.img-editor-wrap" + suffix + "' data-imgtarget='.edit-img" + suffix + "' class='img-editor-group flex flex-hor'>" +
                "<button class='btn img-remove'>" + iconRemove + "</button>" +
                "<button class='btn img-close'>" + iconClose + "</button>" +
            "</div>" +
        "</div>"
    );
}
function imgEditorCallbacks(){
    // size
    $(".img-small").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "40%");
    });
    $(".img-medium").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "60%");
    });
    $(".img-large").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "80%");
    });
    $(".img-full").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "100%");
    });
    
    // alignment
    $(".img-left").on("click", function(){
        $($(this).parent().data("imgwrap")).css({
            "float": "left",
            "display": "inline-block"
        });
    });
    $(".img-center").on("click", function(){
        $($(this).parent().data("imgwrap"))
            .css({
            "float": "none",
            "margin": "auto",
            "display": "block"
        });
    });
    $(".img-right").on("click", function(){
        getImgWrap($(this)).css({
            "float": "right",
            "display": "inline-block"
        });
    });
    
    // remove
    $(".img-remove").on("click", function(){
        if (confirm("Are you sure you want to remove this image?")) {
            getImgWrap($(this)).remove();
        }
    });
    
    // close
    $(".img-close").on("click", function(){
        getImgEditorBar($(this)).fadeOut();
    });
    
    function getImgWrap(elem){
        return $(elem.parent().data("imgwrap"));
    }
    function getImgEditorBar(elem){
        return (elem.parent().parent());
    }
}
