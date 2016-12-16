
function createNewTopic() {
    table = $("#topicField").val();
    $("#topicField").val("");
    $.ajax({
        url: "DHandler.php",
        type: "POST",
        data: {"topic": table},
        dataType: "html",
        success: function(data) {
            getTopics();
        }
    });
}


function createNewPost() {
    content = $("#postArea").val();
    $("#postArea").val("");
    $.ajax({
        url: "DHandler.php",
        type: "POST",
        data: {"postContent": content},
        dataType: "html",
        success: function(data) {
            getPosts();
        }
    });
}


function sendPost(uname, tname) {
    var content = document.getElementById("postArea").value;

    $.ajax({
        url: "DHandler.php",
        type: "POST",
        data: {
            "content": content,
            "posterName": uname,
            "topicName": tname
        },
        dataType: "html",
        success: function(data) {
            getPosts();
        }
    });
}

function getTopics() {
    $.ajax({
        url: "DHandler.php",
        type: "GET",
        data: {"getTopics": "getTopics"},
        dataType: "html",
        success: function(data){
            $("#topicList").html(data);
        }
    });
}

function getPosts(){
	$.ajax({
			url: "DHandler.php",
			type: "GET",
			data:{"postName": "<?php echo $_SESSION['topicName'];?>"},
			dataType: "html",
			success: function(data){
				$("#postList").html(data);
			}
	});
}


function getPdf(){
	var pdf = new jsPDF();
	pdf.fromHTML($('#posts').get(0),20,20,{'width': 500});
	pdf.save("forum.pdf");
}
