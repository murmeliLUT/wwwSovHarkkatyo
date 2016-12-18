
$(document).ready(function() { /* Function listening Send post button.
    When triggered parses user input to decreace SLQ-injections. */
    $('#addPostB').click(function() {
        var temp = $("#postArea").val();
        var content = temp.replace(
        /[^-a-zåäöûüïîêëéèæøžñßç 0-9+*/&§€$£¥#"`´'❝❞‘’“”^~¨^@!¡?¿|_/\\.:,(){}[\]]/gi,'');
/* Parse input by whilelisting specific characters. Not trying to black list all invalid ones. */
        if (content != "") {
            var addButton = $("#addPostB");

            addButton.attr("disabled", "disabled"); /* Disable button to reduce spam. */
            addButton.text("Sending..."); /* And block accidential duplicates. */

            var request = $.ajax({
                url: "DHandler.php",
                type: "POST",
                data: {"postContent": content},
                dataType: "html"
            });

            request.done(function(message) { /* When AJAX request is done return to staring state. */
                /*alert(message);*/
                addButton.removeAttr("disabled");
                addButton.text("Send post");
                $("#postArea").val("");
            });

            request.fail(function(parameter1, textStatus) {
                /* IF AJAX request fails make button availabe again. */
                /*alert(textStatus);*/
                addButton.removerAttr("disabled");
                addButton.text("Send post");
            });
            getPosts(); /* Get all post after creating new. */
        }
    });
});


function getPosts() { /* Parse content from JSON data and set data to postlist element. */
    $.getJSON("posts.php", function(data) {
        var content = "";
        /*JSON data keys are pID, postContent, pDT, uName*/
        data.forEach(function(post) {
            content += "<li class='postListElement'><u>" + post.pDT + " <strong>" +
            post.uName + "</strong></u></p><p>" + post.postContent + " </p></li>";
        });
        document.getElementById("postList").innerHTML = content;
    });
}

function createNewTopic() { /* When user click create new topic button. */
    temp = $("#topicField").val(); /* Gets topic name */
    temp = temp.replace(/[^a-z0-9_ ]/gi,''); /* Name is parsed to contain only alphanumeric characters and spaces. */
    table = temp.replace(/[ ]/gi,'_'); /* Spaces are replaced to _ to make it possible to store then to database table. */
    temp = $("#postArea").val(); /* Get post content. */
    content = "OP: " + temp.replace( /* Add Original Post OP tag to make original post more visible. */
    /[^-a-zåäöûüïîêëéèæøžñßç 0-9+*/&§€$£¥#"`´'❝❞‘’“”^~¨^@!¡?¿|_/\\.:,(){}[\]]/gi,'');
/* Parse input by whilelisting specific characters. Not trying to black list all invalid ones. */

    $("#topicField").val(""); /* Clears both text fields. */
    $("#postArea").val("");
    /* Send data to DHandler to get it processed and written to database. */
    $.ajax({
        url: "DHandler.php",
        type: "POST",
        data: {"topic": table,
                "OPcontent": content},
        dataType: "html",
        success: function(data) {
            getTopics(); /* When sending success calls getTopics to update topic list. */
        }
    });
}

function getTopics() { /* AJAX Function to update topic list. */
    $.ajax({
        url: "DHandler.php",
        type: "GET",
        data: {"getTopics": "getTopics"},
        dataType: "html",
        success: function(data){
            $("#topicList").html(data); /* Set data (topics) to #topicList element. */
        }
    });
}

$(document).ready(function() { /* Function to draws advertisement canvas image every time page any loads. */
    var image = new Image(); /* Image object where source image is drawn. */
    var advertisement = "http://www2.it.lut.fi/project/STX/lutlogo.jpg"; /* URL of source image. */

    if(document.getElementById('canvasAd') != null) {
        var canvas = document.getElementById('canvasAd');
        var content = canvas.getContext('2d'); /* Set image to be 2D format. */

        image.onload = function() {
            content.drawImage(image, 0, 0, 630, 193);
            /* Draw image to canvas element. Start at 0, 0 and end to 630, 193.*/
        };
        image.src = advertisement; /* Source to image which will be drawn. */
    }
});

function downloadPdf(){ /* Function to generate PDF using jsPDF library. */
    var pdf = new jsPDF();
    pdf.fromHTML($('#postList').get(0),10,10,{'width': 500});
    /* Start PDF at point 10, 10 and get contet from #postList.
    Only posts of current topic will be added to PDF. */
	pdf.save("GreenShadesTopic.pdf"); /* Name for generated PDF. */
}
