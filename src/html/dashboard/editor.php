<?php

$postid = "";
$postcontent = "";

if (isset($_GET["postid"])) {
    $postid = $_GET["postid"];
    $post = $bb->getPost($postid);
    $postcontent = $post->postcontent;
}

?>

<div class="post-title flex flex-hor">
    <div class="label">What's the name of your post?</div>
    <input name="post-title" type="text" id="post-title" placeholder="Post Title">
</div>
<div class="post-meta">
    <div class="label">You can choose a category or create a new one</div>
    <div class="categories"></div>
    <div class="label">What tags are related to this post?</div>
    <div class="tags"></div>
</div>
<div class="post-submit-options flex flex-hor">
    <button class="save-post">Save &amp; Publish</button>
    <button class="draft-post">Save as Draft</button>
    <button class="copy-post">Save as Copy</button>
    <button class="unpublish-post">Unpublish</button>
    <button class="schedule-post">Schedule Publish</button>
</div>
<div class="edit-container flex flex-hor">
    <div class="edit-item i flex flex-hor">
        <a style="display: none" id="writer-type">Normal</a>
        <button class="drop-button btn-ii" id="header" onclick="changeWriter(&#34;header&#34;);"><i class="fa fa-header" aria-hidden="true"></i></button>
        <button class="drop-button btn-ii" id="normal" onclick="changeWriter(&#34;normal&#34;);"><i class="fa fa-font" aria-hidden="true"></i></button>
    </div>
    <div class="edit-item ii flex flex-hor">
        <button id="undo" class="btn-ii" type="button" onclick="myUndo();" style="font-size: large;"><i class="fa fa-undo" aria-hidden="true"></i></button>
        <button id="redo" class="btn-ii" type="button" onclick="myRedo()"style="font-size: large"><i class="fa fa-repeat" aria-hidden="true"></i></button>
    </div>
    <div class="edit-item i flex flex-hor">
        <button id="italic" class="btn-ii" type="button" onclick="modifyWith(&#34;I&#34;);"><i class="fa fa-italic" aria-hidden="true"></i></button>
        <button id="bold" class="btn-ii" type="button" onclick="modifyWith(&#34;B&#34;);"><i class="fa fa-bold" aria-hidden="true"></i></button>
        <button id="underline" class="btn-ii" type="button" onclick="modifyWith(&#34;U&#34;);"><i class="fa fa-underline" aria-hidden="true"></i></button>
        <button id="strike" class="btn-ii" type="button" onclick="modifyWith(&#34;STRIKE&#34;);"><i class="fa fa-strikethrough" aria-hidden="true"></i></button>
    </div>
    <div class="edit-item ii flex flex-hor">
        <button class="btn-ii drop-button" id="left" onclick="changeTextAlign(&#34;left&#34;);"><i class="fa fa-align-left" aria-hidden="true"></i></button>
        <button class="btn-ii drop-button" id="center" onclick="changeTextAlign(&#34;center&#34;);"><i class="fa fa-align-center" aria-hidden="true"></i></button>
        <button class="btn-ii drop-button" id="right" onclick="changeTextAlign(&#34;right&#34;);"><i class="fa fa-align-right" aria-hidden="true"></i></button>
        <button class="btn-ii drop-button" id="justify" onclick="changeTextAlign(&#34;justify&#34;);"><i class="fa fa-align-justify" aria-hidden="true"></i></button>
    </div>

    <!--<div class="edit-item">
        <button id="ol" type="button" onclick="addList(true);">OL</button>
        <button id="ul" type="button" onclick="addList(false);">UL</button>
        <button id="block" type="button" onclick="addBlockquote();"><b>&#34;  &#34;</b></button>
    </div>-->

    <div class="edit-item i">
        <!--<button id="image" type="button" onclick="showLinkDialog();">Link</button>-->
        <button class="btn-ii" id="image" type="button" onclick="showDialog();"><i class="fa fa-picture-o" aria-hidden="true"></i></button>
        <!--<button id="image" type="button" onclick="showVideoDialog();">Video</button>-->
    </div>
</div> <!--end edit container-->

<div class="flex flex-hor">

<button class="btn-ii" type="button" id="conduit-undo" onclick="addUndoState()" style="position: absolute; top: -100em;"></button>
<button class="btn-ii" type="button" id="conduit-init" onclick="initCurrentState()" style="position: absolute; top: -105em;"></button>

</div>
<span id="postid-hidden" style="position: absolute; top: -110em; display: none;"><?= $postid ?></span>
<dialog id="img-dialog">
    <div class="dialog-container">
        <p style="text-align: center;">Please choose an image to upload</p>
        <div style="width: 120px; height: 120px; display: block; border: 1px dotted black; position: relative; padding: 1px;
                    text-align: center;">
            <img id="upload-preview" src="" alt="image preview" style="max-height: 100%; max-width: 100%; object-fit: contain;
                position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        </div>
        <div>
            <form method="post" id="image-form" name="image-form" onsubmit="return closeDialog()"
                  onchange="uploadPreview(document.getElementById('upload-image-holder').value);">
                <input type="file" accept="image/*" name="imgfile[]" id="upload-image-holder">
        </div>
        <div>
            <button class="exit-dialog" type="submit">Done</button>
            <button class="exit-dialog" type="button" onclick="cancelDialog()">Cancel</button>
        </div>
            </form>
    </div>
</dialog>

<div id="frame">
    <div contenteditable="true" id="content" onmouseup="adjustEditButtons()" onkeypress="checkEnter(event)" onkeyup="resetUndoTimer()">
        <div>
            <?php if ($postcontent == "") { ?>
            Write your post here.
            <?php } else { ?>
            <?= $postcontent ?>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    var italic = "I";
    var bold = "B";
    var underline = "U";
    var strike = "STRIKE";
    
    function mainJs(){
        alert("content calling mainJs");
        var js;
        var text;
        
        for (var i = 0; i < window.top.document.scripts.length; i++) {
            js = window.top.document.scripts[i];
            if (js.id == "main-js") {
                text = document.createTextNode(js.text);
                document.getElementById("main-js-copy").appendChild(text);
            }
        }
        
    }
    function adjustEditButtons() {
        //alert("content calling adjustEditButtons()");
        if (window.getSelection() == null) {
            alert("window selection is null");
        }
        if (window.getSelection().rangeCount == 0) {
            return;
        }
        var pDocument = window.top.document;
        var selection = window.getSelection();
        var range = selection.getRangeAt(0);
        var anode = range.startContainer;
        var isWriterH3 = hasParentOf(anode, "H3");
        var hasItalic = hasParentOf(anode, italic);
        var hasBold = hasParentOf(anode, bold);
        var hasUnderline = hasParentOf(anode, underline);
        var hasStrike = hasParentOf(anode, strike);
        if (hasItalic) {
            pDocument.getElementById("italic").style.backgroundColor = "#dcdcdc";
        } else {
            pDocument.getElementById("italic").style.backgroundColor = "white";
        }
        if (hasBold) {
            pDocument.getElementById("bold").style.backgroundColor = "#dcdcdc";
        } else {
            pDocument.getElementById("bold").style.backgroundColor = "white";
        }
        if (hasUnderline) {
            pDocument.getElementById("underline").style.backgroundColor = "#dcdcdc";
        } else {
            pDocument.getElementById("underline").style.backgroundColor = "white";
        }
        if (hasStrike) {
            pDocument.getElementById("strike").style.backgroundColor = "#dcdcdc";
        } else {
            pDocument.getElementById("strike").style.backgroundColor = "white";
        }
        if (isWriterH3) {
            pDocument.getElementById("writer-type").innerHTML = "Header";
        } else {
            pDocument.getElementById("writer-type").innerHTML = "Normal";
        }
        if (document.getElementById("selection") != null) {
            removeSpanContents(document.getElementById("selection"));
            removeAllSpanElementContents();
        }
    }
    function hasParentOf(anode, property) {
        //alert("content calling hasParentOf");
        if (anode.parentNode) {
            if (anode.parentNode.nodeName === property) {
                return true;
            } else if (isNodeNameSpecial(anode.parentNode)) {
                return hasParentOf(anode.parentNode, property);
            } else if (anode.nodeName === property) {
                return true;
            }
        } else if (anode.nodeName === property) {
            return true;
        }
        return false;
    }
    function isNodeNameSpecial(node){
        //alert("content calling isNodeNameSpecial");
        return (
            node.nodeName === bold ||
            node.nodeName === italic ||
            node.nodeName === underline ||
            node.nodeName === strike ||
            node.nodeName === "SPAN" ||
            node.nodeName === "A"
               );
    }
    function removeAllSpanElementContents(){
        alert("content calling removeAllSpanElementContents");
        var spans = document.getElementsByClassName("select-class");
        var span;
        var range;
        var contents;
        for (var i = 0; i < spans.length; i++) {
            span = spans[i];
            range = document.createRange();
            range.selectNodeContents(span);
            contents = range.extractContents();
            range.setStartBefore(span);
            range.insertNode(contents);
            span.parentNode.removeChild(span);
        }
    }
    function removeSpanContents(span){
        alert("content calling removeSpanContents");
        var range = document.createRange();
        range.selectNodeContents(span);
        var contents = range.extractContents();
        range.setStartBefore(span);
        range.insertNode(contents);
        span.parentNode.removeChild(span);
        //if (document.getElementById("selection") == null) alert("success!");
    }
    var lastKeyUp;
    var undoTimer;
    function resetUndoTimer(){
        //alert("content calling resetUndoTimer");
        clearTimeout(undoTimer);
        undoTimer = setTimeout("callAddUndoState()", 500);
    }
    function callAddUndoState(){
        //alert("content calling callAddUndoState");
        window.top.document.getElementById("conduit-undo").click();
    }
    function initCurrentState(){
        //alert("content calling initCurrentState");
        window.top.document.getElementById("conduit-init").click();
    }
    function checkEnter(e){
        //alert("content calling checkEnter");
        return;
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) { //Enter keycode
            cancelEvent(e);
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);
            var contents = range.extractContents();
            
            var br;
            var hasList = hasParentOf(range.startContainer, "LI");
            var masterList;
            var endList = false;
            if (hasList) {
                var ordered = hasParentOf(range.startContainer, "OL");
                if (!ordered) {
                    masterList = findParentOf(range.startContainer, "UL");
                } else {
                    masterList = findParentOf(range.startContainer, "OL");
                }
                var listItem = findParentOf(range.startContainer, "LI");
                if (listItem.textContent === "" || !listItem.hasChildNodes()) {
                    //alert("doing what I want to happen here");
                    br = document.createElement("BR");
                    listItem.parentNode.removeChild(listItem);
                    range.setStartAfter(masterList);
                    endList = true;
                } else {
                    //alert("doing else");
                    br = document.createElement("LI");
                    range.setStartAfter(listItem);
                }
            } else {
                br = document.createElement("BR");
            }
            //var contents = range.extractContents();
            
            contents.appendChild(br);
            range.insertNode(contents);
            if (endList) {
                range.setStartAfter(masterList);
                range.setEndAfter(masterList);
            } else if (hasList) {
                range.selectNodeContents(br);
            } else {
                range.setStartAfter(br);
                range.setEndAfter(br);
            }
            selection.removeAllRanges();
            selection.addRange(range);
        }
    }
    function cancelEvent(e){  
        alert("content calling cancelEvent");
      try {
          e.preventDefault(true);
          e.stopPropagation(true);
          e.stopped = true;
      } catch (err) {}
    }
    function findParentOf(node, name) {
        alert("content calling findParentOf");
        if (node.nodeName === name) {
            return node;
        }
        if (node.parentNode) {
            return findParentOf(node.parentNode, name);
        }
        return null;
    }
</script>