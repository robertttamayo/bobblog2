var WELCOME = 1;
var EDITOR = 2;

$(document).ready(function(){
    $("#main-container").html("").load(dashboardTemplateDir + "welcome.html", function(){
        mainContainerCallbacks();
    });
    
    $(".new-post").on("click", function(){
        $("#main-container").html("").load(dashboardTemplateDir + "editor.html", function(){
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
    var draft = "draft";
    var postid = document.getElementById("postid-hidden").textContent;
    $.post("#", {    
        action: actionSavePost,
        name: title,
        file: raw,
        draft: draft,
        postid: postid
    },
    function(data){ //callback for debugging
        alert(data);
    });
}
