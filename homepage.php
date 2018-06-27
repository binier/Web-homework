<?php
session_start();
if(!isset($_SESSION) || !isset($_SESSION['userId'])){
	header('Location: ./index.php', TRUE, 302);
	die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Homepage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
	var build_note = (title, text) =>
		`<div class="alert alert-info">
			<strong name="noteTitle">${title}</strong><br><br>
			<div name="noteText">${text}</div> 
		</div>`;
	function add_note(title, text){
		$('#myNotes').prepend(build_note(title,text));
	}
	$(function(){
		$.post('./action.php', {
			action: 3
		}, function(resp) {
			let notes = JSON.parse(resp);
			for(let i = 0; i < notes.length; ++i){
				let note = notes[i];
				add_note(note.title, note.text);
			}
		});
		$('#LogMeOut').click(function(){
			$.post('./action.php', {action: 5}, function(resp) {
				if(resp == 1)
					return window.location.replace("./");
				alert("couldn't log you out!");
			});
		});
		$('#newNoteSave').click(function(){
			let title = $('#newNoteTitle').val();
			let text = $('#newNoteBody').val();
			
			$.post('./action.php', {
				action: 4,
				title,
				text
			}, function(resp) {
				if(resp == -1)
					alert('operation failed! title cant be empty.');
				else if(resp == -2)
					alert('operation failed! note body cant be empty.');
				else if(resp == 1){
					$('#newNoteForm').collapse('toggle');
					add_note(title, text);
				}
			});
		});
	});
  </script>
</head>
<body>

<div class="container">
  <h2>My Notes</h2>
  <p>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#newNoteForm" aria-expanded="true" aria-controls="collapseExample">
    Add new Note
  </button>
  <button id="LogMeOut" type="button" class="btn btn-danger">Log Out</button>
  
  </p>
	<div id="newNoteForm" class="collapse" aria-expanded="false" style="height: 0px;">
		<div class="alert alert-info">
			<label for="newNoteTitle">Note Title</label>
			<input class="form-control" id="newNoteTitle" placeholder="" type="text"><br>
			<label for="newNoteBody">Note body</label>
			<textarea rows="3" style="width: 1107px; height: 119px;" id="newNoteBody" class="form-control"></textarea>
			<br>
			<button id="newNoteSave" type="button" class="btn btn-success">Save</button>
		</div>
	</div>
	<p id="myNotes">
	</p>
</div>

</body>
</html>