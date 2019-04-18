<?php
session_start();
if(isset($_SESSION['user'])) {
  echo "Your session is running " . $_SESSION['user'];
  header('Location: chatroom.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat Thessaloniki</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script
  src="https://code.jquery.com/jquery-3.4.0.slim.min.js"
  integrity="sha256-ZaXnYkHGqIhqTbJ6MB4l9Frs/r7U4jlx7ir8PJYBqbI="
  crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">Welcome to Chat Thessaloniki</h2>
		<hr>
		<?php 
			if(isset($_POST['join'])) {
				session_start();
				require("db/users.php");
				$objUser = new users;
				$objUser->setEmail($_POST['email']);
				$objUser->setName($_POST['uname']);
				$objUser->setLoginStatus(1);
			 	$objUser->setLastLogin(date('Y-m-d h:i:s'));
			 	$userData = $objUser->getUserByEmail();
			 	if(is_array($userData) && count($userData)>0) {
			 		$objUser->setId($userData['id']);
			 		if($objUser->updateLoginStatus()) {
			 			echo "User login..";
			 			$_SESSION['user'][$userData['id']] = $userData;
			 			header("location: chatroom.php");
			 		} else {
			 			echo "Failed to login.";
			 		}
			 	} else {
				 	if($objUser->save()) {
				 		$lastId = $objUser->dbConn->lastInsertId();
				 		$objUser->setId($lastId);
						$_SESSION['user'][$lastId] = [ 
							'id' => $objUser->getId(), 
							'name' => $objUser->getName(), 
							'email'=> $objUser->getEmail(), 
							'login_status'=>$objUser->getLoginStatus(), 
							'last_login'=> $objUser->getLastLogin() 
						];

				 		echo "User Registred..";
				 		header("location: chatroom.php");
				 	} else {
				 		echo "Failed..";
				 	}
				 }
			}
		 ?>
		<div class="row join-room">
			<div class="col-md-6 col-md-offset-3">
				<form id="join-room-frm" role="form" method="post" action="" class="form-horizontal">
					<div class="form-group">
	                  	<div class="input-group">
	                        <div class="input-group-addon addon-diff-color">
	                            <span class="glyphicon glyphicon-user"></span>
	                        </div>
	                        <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter Name">
	                  	</div>
	                </div>
					<div class="form-group">
	                	<div class="input-group">
	                        <div class="input-group-addon addon-diff-color">
	                            <span class="glyphicon glyphicon-envelope"></span>
	                        </div>
	                    	<input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" value="">
	                	</div>
	                </div>
	                <div class="form-group">
	                    <input type="submit" value="JOIN CHATROOM" class="btn btn-success btn-block" id="join" name="join">
	                </div>
			    </form>
			</div>
		</div>
	</div>
</body>
</html>
