<?php

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

?>