<?php

// setup server and client error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// max student capacity per group
define("MAX_STUDENT_CAPACITY", 6);

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
	
	public function update_student_info()
	{
		$query = "UPDATE students SET firstname = '$this->firstName', lastname = '$this->lastName', email = '$this->email', phone = '$this->phone', projectname = '$this->projectName' WHERE umid = '$this->umid'";
		$rslt = mysql_query($query);
	}
	
	public function student_already_signed_up()
	{
		$query = "SELECT * FROM students AS S WHERE S.umid = '$this->umid' AND S.timeslotid != 0";
		$rslt = mysql_query($query);
		
		return mysql_num_rows($rslt) > 0;
	}
	
	public function signup_for_timeslot($id)
	{
		$query = "UPDATE students SET timeslotid = '$id' WHERE umid = '$this->umid'";
		$rslt = mysql_query($query);
	}
}

function print_error_if_exists($error)
{
	if($error)
	{
		echo "<i>Invalid format.</i>";
	}
}

function render_timeslot_dropdown_options($selected)
{
	$query = "SELECT * FROM timeslots";
	$rslt = mysql_query($query);
	
	while($row = mysql_fetch_array($rslt))
	{
		if(get_spots_remaining($row['timeslotid']) != 0)
		{
			if($row['timeslotid'] == $selected)
			{
				echo "<option value=\"" . $row['timeslotid'] . "\" selected>" . $row['start_time'] . " - " . $row['end_time'] . "</option>";
			}
			else
			{
				echo "<option value=\"" . $row['timeslotid'] . "\">" . $row['start_time'] . " - " . $row['end_time'] . "</option>";
			}
		}
	}
}

function render_availability_table_rows()
{
	$query = "SELECT * FROM timeslots";
	$rslt = mysql_query($query);
	
	while($row = mysql_fetch_array($rslt))
	{
		$spotsRemaining = get_spots_remaining($row['timeslotid']);
		
		$rowClass = $spotsRemaining == 0 ? "danger" : "";
		
		echo "<tr class='$rowClass'>";
		echo "<td>" . $row['timeslotid'] . "</td>";
		echo "<td>" . $row['start_time'] . " - " . $row['end_time'] . "</td>";
		echo "<td>" . $spotsRemaining . "</td>";
		echo "</tr>";
	}
}

function render_confirm_message()
{
	echo "<div class='form-group'>";
	echo "<label for='confirmUpdate' class='col-lg-2 control-label'>Confirm</label>";
	echo "<div class='col-lg-10'>";
	echo "<input type='checkbox' name='confirmUpdate'> <span class='text-danger'>You have already registered. Update information?</span>";
	echo "</div>";
	echo "</div>";
}

function get_spots_remaining($id)
{
	$query = "SELECT * FROM students WHERE timeslotid = '$id'";
	$rslt = mysql_query($query);
	
	return MAX_STUDENT_CAPACITY - mysql_num_rows($rslt);
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
	$timeslotid = mysql_real_escape_string($_POST['inputTimeSlot']);
    
    // assume no errors by default
    $error = false;
	$confirm = false;

	// validate umid
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $umid) == false)
	{
		$umidError = true;
	}
	
	// validate first name
	$pattern = "/^[a-zA-Z]+$/";
	if(preg_match($pattern, $firstName) == false)
	{
		$firstNameError = true;
	}
	
	// validate last name
	$pattern = "/^[a-zA-Z]+$/";
	if(preg_match($pattern, $lastName) == false)
	{
		$lastNameError = true;
	}
	
	// validate email
	$pattern = "/^[0-9]{8}$/";
	if(preg_match($pattern, $email) == false)
	{
		$emailError = false;
	}
	
	// validate phone number
	$pattern = "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";
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
	
	// add student if does not exist
	if($student->student_exists() == false)
	{
		$student->add_student_to_db();
	}

	// update timeslot
	if($student->student_already_signed_up() == false)
	{
		$student->signup_for_timeslot($timeslotid);
	}
	else
	{
		$confirm = true;
	}
	
	// previously submitted form and potentially clicked confirm
	if(isset($_POST['confirmUpdate']))
	{
		$student->update_student_info();
		$student->signup_for_timeslot($timeslotid);
		header("Location: index.php");
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
            <div class="col-lg-6">
                <form class="form-horizontal" method="post" action="index.php">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputUMID" class="col-lg-2 control-label">UMID</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputUMID" maxlength="8" value="<?=$umid?>"><!-- pattern="[0-9]{8}" required> --> <?php print_error_if_exists($umidError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFirstName" class="col-lg-2 control-label">First Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputFirstName" value="<?=$firstName?>"><!-- pattern="[a-zA-Z]" required> --> <?php print_error_if_exists($firstNameError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLastName" class="col-lg-2 control-label">Last Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputLastName" value="<?=$lastName?>"><!-- pattern="[a-zA-Z]" required> --> <?php print_error_if_exists($lastNameError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputProjectName" class="col-lg-2 control-label">Project Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputProjectName" value="<?=$projectName?>"><!-- required> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputEmail" value="<?=$email?>"><!-- pattern="[a-z0-9]+@([a-z0-9]+)(.[a-z0-9])+." required> --> <?php print_error_if_exists($emailError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone" class="col-lg-2 control-label">Phone</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="inputPhone" value="<?=$phone?>"><!-- pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required> --> <?php print_error_if_exists($phoneError) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Time Slot</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="inputTimeSlot">
								<?php render_timeslot_dropdown_options($timeslotid); ?>
                                </select>
                            </div>
                        </div>
						<?php if($confirm) { render_confirm_message(); } ?>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="reset" class="btn btn-default">Cancel</button>
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
			
			<!-- availability -->
			<div class="col-lg-6">
				<legend>Availability</legend>
				
				<table class="table table-striped table-hover ">
					<thead>
						<tr>
							<th>#</th>
							<th>Time</th>
							<th>Spots Remaining</th>
						</tr>
					</thead>
					<tbody>
						<?php render_availability_table_rows(); ?>
					</tbody>
				</table>
				
			</div>
			
        </div>
    </div>

</body>
</html>