var WELCOME = 1;
var EDITOR = 2;

$(document).ready(function(){
    $("#main-container").html("").load(dashboardTemplateDir + "welcome.html");
    
    $(".new-post").on("click", function(){
        $("#main-container").html("").load(dashboardTemplateDir + "editor.html");
    });
    
    
});
