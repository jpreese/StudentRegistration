﻿<?php

// setup server and client error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);

class Student
{
	public $umid;
	public $firstName;
	public $lastName;
	public $email;
	public $phone;
	public $projectName;
	
	public function __construct($umid, $firstName, $lastName, $email, $phone, $projectName)
	{
		$this->umid = $umid;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->phone = $phone;
		$this->projectName = $projectName;
	}
	
	public function student_exists()
	{
		$query = "SELECT * FROM students AS S WHERE S.umid = '$this->umid'";
		$rslt = mysql_query($query);
		
		return mysql_num_rows($rslt) > 0;
	}
	
	public function add_student_to_db()
	{
		$query = "INSERT students (umid, firstname, lastname, email, phone, projectname) VALUES ('$this->umid', '$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->projectName')";
		$rslt = mysql_query($query);
	}
	
	public function student_already_signed_up($student)
	{
		
		$studentAlreadySignedUpQuery = "SELECT * FROM students AS S JOIN students_timeslots AS ST ON ST.studentid = S.studentid WHERE S.umid = '$umid'";
		$rslt = mysql_query($studentAlreadySignedUpQuery);
		
		// user is already signed up for a timeslot
		if(mysql_num_rows($rslt) > 0)
		{
			return true;
		}
	}
}

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
	$projectName = mysql_real_escape_string($_POST['inputProjectName']);
	$email = mysql_real_escape_string($_POST['inputEmail']);
	$phone = mysql_real_escape_string($_POST['inputPhone']);
	$timeslot = mysql_real_escape_string($_POST['inputTimeSlot']);
    
    // assume no errors by default
    $error = false;

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
    
    if($error)
    {
        // stuff
        return;
    }
	
	$student = new Student($umid, $firstName, $lastName, $email, $phone, $projectName);
	
	if($student->student_exists() == false)
	{
		$student->add_student_to_db();
	}
	else
	{
		$student->update_student_info();
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
                            <label for="inputProjectName" class="col-lg-2 control-label">Project Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputProjectName"><!-- required> -->
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