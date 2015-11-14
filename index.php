<?php

// setup server and client error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);
function print_error_if_exists($error)
{
	if($error)
	{
		echo "<i>Invalid format.</i>";
	}
}

// establish database connection
$conn = mysql_connect("localhost", "root", "");
mysql_select_db("main", $conn);

if(isset($_POST['submit']))
{
	// initialize and escape post data
	$umid = mysql_real_escape_string($_POST['inputUMID']);
	$firstName = mysql_real_escape_string($_POST['inputFirstName']);
	$lastName = mysql_real_escape_string($_POST['inputLastName']);
	$projectTitle = mysql_real_escape_string($_POST['inputProjectTitle']);
	$email = mysql_real_escape_string($_POST['inputEmail']);
	$phone = mysql_real_escape_string($_POST['inputPhone']);
	$timeslot = mysql_real_escape_string($_POST['inputTimeSlot']);

	// validate umid
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $umid) == false)
	{
		$umidError = true;
	}
	
	// validate first name
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $firstName) == false)
	{
		$firstNameError = true;
	}
	
	// validate last name
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $lastName) == false)
	{
		$lastNameError = true;
	}
	
	// validate email
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $email) == false)
	{
		$emailError = true;
	}
	
	// validate phone number
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $phone) == false)
	{
		$phoneError = true;
	}
}

?>

<!DOCTYPE html>

<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://bootswatch.com/flatly/bootstrap.min.css">
</head>
<body>

    <div class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a href="" class="navbar-brand">Student Registration</a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="">View Students</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="http://www.github.com" target="_blank">fork on github</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">

            <!-- registration form -->
            <div class="col-lg-7">
                <form class="form-horizontal" method="post" action="index.php">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputUMID" class="col-lg-2 control-label">UMID</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputUMID" maxlength="8"><!-- pattern="[0-9]{8}" required> --> <?php print_error_if_exists($umidError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFirstName" class="col-lg-2 control-label">First Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputFirstName"><!-- pattern="[a-zA-Z]" required> --> <?php print_error_if_exists($firstNameError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLastName" class="col-lg-2 control-label">Last Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputLastName"><!-- pattern="[a-zA-Z]" required> --> <?php print_error_if_exists($lastNameError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputProjectTitle" class="col-lg-2 control-label">Project Title</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputProjectTitle"><!-- required> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputEmail"><!-- pattern="[a-z0-9]+@([a-z0-9]+)(.[a-z0-9])+." required> --> <?php print_error_if_exists($emailError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone" class="col-lg-2 control-label">Phone</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputPhone"><!-- pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required> --> <?php print_error_if_exists($phoneError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Time Slot</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="inputTimeSlot">
								<?php
									$query = mysql_query("select * from timeslots");
									while($row = mysql_fetch_array($query))
									{
										echo "<option id=\"" . $row['timeslotid'] . "\">" . $row['start_time'] . " - " . $row['end_time'] . "</option>";
									}
								?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="reset" class="btn btn-default">Cancel</button>
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</body>
</html>