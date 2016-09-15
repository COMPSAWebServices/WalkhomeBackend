<?php
ini_set('display_errors', 1);
define("MAX_FILESIZE", 4194304);    // 4 MB

// define("RELATIVE_PATH_TO_FRONTEND", "../");
define("RELATIVE_PATH_TO_FRONTEND", "../../sites/cesa/frosh/");
define("FRONTEND_URL", "http://queenscesa.com/frosh/");

mb_internal_encoding("UTF-8");

define("DB_HOST", "localhost");
define("DB_NAME", "cesafrosh");
define("DB_USER", "readwrite");
define("DB_PASS", "tf3a64af518ex");

spl_autoload_register(function($class) {
	include_once RELATIVE_PATH_TO_FRONTEND . "classes/$class.php";
});

define("MARKDOWN_STYLE_GUIDE", "http://backstage.compsawebservices.com/markdown/");


  // include required dependencies from Backstage
  require_once $_SERVER["DOCUMENT_ROOT"] . "/php/constants.php";
  require_once $_SERVER["DOCUMENT_ROOT"] . "/php/classes/Auth.php";
  require_once $_SERVER["DOCUMENT_ROOT"] . "/php/classes/User.php";
  require_once $_SERVER["DOCUMENT_ROOT"] . "/php/classes/BackEnd.php";
  // authenticate the session with Backstage before allowing access
	if (!Auth::authenticate()) {
		Auth::redirect("/");
	} else {
		$site = BackEnd::withDirectoryName("walkhome");
		if (!User::current()->hasPermissionForSite($site->getPID())) {
			Auth::redirect("/");
		}
	}
  // if the user has not been redirected to Backstage, they are authorized

/*echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
*/
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Walkhome Backend</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body onload="main()">
		<div class="container">
			<div class="col-lg-12 text-center">
				<h1>WALKHOME CENTRAL</h1>
			</div>
		</div>

		<div class="container">
			<h2>Add Walk</h2>
			<form class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-2" for="pick-up-input">Pick Up:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="pick-up-input" placeholder="Leggett Hall">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="drop-off-input">Drop Off:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="drop-off-input" placeholder="The Spot">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="phone-number-input">Phome Number:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="phone-number-input" placeholder="6131234567">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="time-input">Time:</label>
					<div class="col-sm-4">
						<input type="time" name="time" class="form-control" id="time-input" onClick="stopTime()">
					</div>
					<label class="control-label col-sm-2" for="walkers-select">Walking Team:</label>
					<div class="col-sm-4">
						<select name="walkers" class="form-control" id="walkers-select">
							<option>W1</option>
							<option>W2</option>
							<option>W3</option>
							<option>W4</option>
							<option>W5</option>
							<option>W6</option>
							<option>W7</option>
							<option>W8</option>
							<option>W9</option>
							<option>W10</option>
							<option>W11</option>
							<option>W12</option>
							<option>W13</option>
							<option>W14</option>
							<option>W15</option>
							<option>W16</option>
							<option>W17</option>
							<option>W18</option>
							<option>W19</option>
							<option>W20</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<a class="btn btn-info btn-lg" onclick="addWalk()" id="create-walk">CREATE</a>
						<p id="create-walk-error"></p>
					</div>
				</div>
			</form>
		</div>
		<div class="container">
			<h2>Current Walks</h2>
			<div id='walk-table-holder'>
			    <table id="walk-table" class="table">
			    	<tr id='table-title-row'><th>Status</th><th>Pick Up</th><th>Drop Off</th><th>Phone Number</th><th>Request Time</th><th>Walking Team</th></tr>
			    </table>
			</div>
		</div>
		<div class="container">
			<h2>Edit Walk</h2>
			<form class="form-horizontal">
				<div class="form-group">
					<fieldset>
						<legend class="control-legend col-sm-2">Status:</legend>
						<div class="col-sm-8">
							<label class="control-label" for="update-radio-recived">Recieved:</label>
							<input type="radio" id="update-radio-recived" value="recieved" name="status">
							<label class="control-label" for="update-radio-out">Walkers Out:</label>
							<input type="radio" id="update-radio-out" value="out" name="status">
							<label class="control-label" for="update-radio-walking">Walking:</label>
							<input type="radio" id="update-radio-walking" value="walking" name="status">
							<label class="control-label" for="update-radio-completed">Completed:</label>
							<input type="radio" id="update-radio-completed" value="completed" name="status">
							<label class="control-label" for="update-radio-in">Walkers In:</label>
							<input type="radio" id="update-radio-in" value="in" name="status">
						</div>
					</fieldset>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="update-pick-input">Pick Up:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="pick_up" id="update-pick-input">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="update-drop-input">Drop Off:</label>
					<div class="col-sm-8">	
						<input type="text" class="form-control" name="drop_off" id="update-drop-input">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="update-time-input">Pickup Time:</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="time" id="update-time-input">
					</div>
					<label class="control-label col-sm-2" for="update-walkers-select">Walking Team:</label>
					<div class="col-sm-4">
						<select name="walkers" class="form-control" id="update-walkers-select">
							<option>W1</option>
							<option>W2</option>
							<option>W3</option>
							<option>W4</option>
							<option>W5</option>
							<option>W6</option>
							<option>W7</option>
							<option>W8</option>
							<option>W9</option>
							<option>W10</option>
							<option>W11</option>
							<option>W12</option>
							<option>W13</option>
							<option>W14</option>
							<option>W15</option>
							<option>W16</option>
							<option>W17</option>
							<option>W18</option>
							<option>W19</option>
							<option>W20</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="update-phone-input">Phone Number:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control"  name="phone_number" id="update-phone-input">
					</div>
				</div>
			</form>
			<div class="form-group">
				<a class="btn btn-danger btn-lg" value="submit" onclick="updateWalk(0)" id="update-walk">Submit</a>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	    <script type="text/javascript" src="main.js"></script>
	</body>
</html>