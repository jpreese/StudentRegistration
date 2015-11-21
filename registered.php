<?php

require_once("common.php");

function render_registered_students_rows()
{
	$query = "SELECT * FROM students AS S";
	$rslt = mysql_query($query);
	
	while($row = mysql_fetch_array($rslt))
	{
		echo "<tr>";
		echo "<td>" . $row['umid'] . "</td>";
		echo "<td>" . $row['firstname'] . "</td>";
		echo "<td>" . $row['lastname'] . "</td>";
		echo "<td>" . $row['email'] . "</td>";
		echo "<td>" . $row['phone'] . "</td>";
		echo "<td>" . $row['projectname'] . "</td>";
		echo "<td>" . get_time_from_id($row['timeslotid']) . "</td>";
		echo "</tr>";
	}
}

function get_time_from_id($timeslotid)
{
	$query = "SELECT start_time, end_time FROM timeslots AS T WHERE T.timeslotid = '$timeslotid'";
	$rslt = mysql_fetch_array(mysql_query($query));
	
	return $rslt['start_time'] . " - " . $rslt['end_time'];
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
                <a href="index.php" class="navbar-brand">Student Registration</a>
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
                        <a href="https://github.com/jpreese/StudentRegistration" target="_blank">fork on github</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
			<table class="table table-striped table-hover ">
				<thead>
					<tr>
						<th>UMID</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Project Name</th>
						<th>Time</th>
					</tr>
				</thead>
				<tbody>
					<?php render_registered_students_rows() ?>
				</tbody>
			</table>	
        </div>
    </div>

</body>
</html>