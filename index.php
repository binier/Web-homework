<?php
session_start();
if(isset($_SESSION) && isset($_SESSION['userId'])){
	header('Location: ./homepage.php', TRUE, 302);
	die();
}
?>
<html>
<head>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="./style.css" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script>
var error_passwords_dont_match = "entered passwords do not match!";
var error_empty_field = (field) => `<strong>${field}<\/strong> is empty!`;

var reg_errors = [
	"invalid <strong>email</strong>!",
	"invalid <strong>username</strong>! It should be at least 6 symbols long. only latin symbols and numbers allowed.",
	"invalid <strong>password</strong>! Password need to be at least 8 symbols long.",
	"invalid <strong>username</strong>! Such username already exists."
];
var login_errors = [
	"authentication failed!"
];


$(function() {
	function clear_msgs(){
		$('#success_msg').fadeOut();
		$('#error_msg').fadeOut();
		$('#success_msg').html('');
		$('#error_msg').html('');
	}
	function putError($msg){
		$('#success_msg').fadeOut();
		$('#error_msg').html($msg);
		$('#error_msg').fadeIn();
	}
	function putSuccess($msg){
		$('#error_msg').fadeOut();
		$('#success_msg').html($msg);
		$('#success_msg').fadeIn();
	}
	$('#login-submit').on('click', function () {
		clear_msgs();
		
		let parent = $('#login-form');
		
		let username = parent.find('#username').val();
		let password = parent.find('#password').val();
		
		$.post("./action.php", {
			action: 1,
			username: username,
			password: password
		}, function(resp){
			if(resp != 1)
				return putError(login_errors[Math.abs(resp)-1]);
			
			window.location.replace("./homepage.php");
		});
	});
	$('#register-submit').on('click', function () {
		clear_msgs();
		
		let parent = $('#register-form');
		
		let email = parent.find('#email').val();
		let username = parent.find('#username').val();
		let password = parent.find('#password').val();
		let password_confirm = parent.find('#confirm-password').val();
		
		if(!email)
			return putError(error_empty_field("email"));
		if(!username)
			return putError(error_empty_field("username"));
		if(!password)
			return putError(error_empty_field("password"));
		if(password != password_confirm)
			return putError(error_passwords_dont_match);
		
		$.post("./action.php", {
			action: 2,
			email: email,
			username: username,
			password: password
		}, function(resp){
			if(resp != 1)
				return putError(reg_errors[Math.abs(resp)-1]);
			putSuccess("User created successfully! Feel free to login.");
		});
	});
    $('#login-form-link').click(function(e) {
		clear_msgs();
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		clear_msgs();
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	
});

</script>
</head>
<body>
<div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" />
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" />
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<button type="button" id="login-submit" tabindex="4" class="form-control btn btn-login" >Log In</button>
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" style="display: none;">
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="" />
									</div>
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" />
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" />
									</div>
									<div class="form-group">
										<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" />
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<button type="button" id="register-submit" tabindex="4" class="form-control btn btn-register" >Register Now</button>
											</div>
										</div>
									</div>
								</form>
							</div>
							
						</div>
						<div id="success_msg" class="alert alert-success" style="display:none;"></div>
						<div id="error_msg" class="alert alert-danger" style="display:none;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<html>