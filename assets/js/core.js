var WELCOME = 1;
var EDITOR = 2;

$(document).ready(function(){
    $("#main-container").html("").load(dashboardTemplateDir + "welcome.html", function(){
        mainContainerCallbacks();
    });
    
    $(".new-post").on("click", function(){
        $("#main-container").html("").load(dashboardTemplateDir + "editor.php", function(){
            editContainerCallbacks();
        });
    });
    $(".load-edit-post").on("click", function(){
        var postid = $(this).data("postid");
        $("#main-container").html("").load(dashboardTemplateDir + "editor.php?postid=" + postid, function(){
            editContainerCallbacks();
        });
    });
    
});
function mainContainerCallbacks(){
    
}
function editContainerCallbacks(){
    $(".save-post").on("click", function(){
        savePost();
    });
}
function savePost(){
    var raw = $("#content").html();
    var title = $("#post-title").val();
    var draft = false;
    var postid = $("postid-hidden").text();
    console.log(postid);
    if (postid == "") {
        draft = true;
    }
    $.post("#", {    
        action: actionSavePost,
        name: title,
        file: raw,
        draft: draft,
        postid: postid
    },
    function(data){ //callback for debugging
        console.log(data);
    });
}
