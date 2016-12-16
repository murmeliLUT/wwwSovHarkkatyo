
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

function getTopics() {
    $.ajax({
        url: "DHandler.php",
        type: "GET",
        data: {"getTopics": "getTopics"},
        dataType: "html"
    });
}

function sendPost() {
    var content = document.getElementById("postArea").value;

    $.ajax({
        url: "DHandler.php",
        type: "POST",
        data: {
            "content": content,
            "poster": "<?php print $_SESSION['username'] ?>",
            "topic": "<?php print $_SESSION['topic'] ?>"
        },
        dataType: "html",
        success: function(data) {
            getPosts();
        }
    });
}

function getPosts(){

	$.ajax({
			url: "datahandler.php",
			type: "GET",
			data:{
				"thrd": "<?php echo $_SESSION['thread'];?>",
				"posts": 'getPosts'
			},
			dataType: "html",
			success: function(data){
				$("#posts").html(data);

			}
	});
}


function getPdf(){
	var pdf = new jsPDF();
	pdf.fromHTML($('#posts').get(0),20,20,{'width': 500});
	pdf.save("forum.pdf");
}
