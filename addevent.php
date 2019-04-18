<html>
<body>
<?php
session_start();

$title= $_POST['etitle'];
$loc = $_POST['elocation'];
$strtdate =$_POST['startdate'];
$strttime =$_POST['starttime'];
$enddate =$_POST['enddate'];
$endtime =$_POST['endtime'];

$_SESSION['etitle']=$title;
$_SESSION['elocation']=$loc;
$_SESSION['startdate']=$strtdate;
$_SESSION['starttime']=$strttime;
$_SESSION['enddate']=$enddate;
$_SESSION['endtime']=$endtime;
echo "DTSTART:{$strtdate}\n";
echo "<br>";
echo "TSTART:{$strttime}\n"; 
echo "<br>";
echo "DTEND:{$enddate}\n";
echo "<br>";
echo "TEND:{$endtime}\n";
echo "<br>";
echo "LOCATION:{$loc}\n";
echo "<br>";
echo "SUMMARY:{$title}\n";
echo "<br>";
$con=mysqli_connect("localhost","root","");
	if(!$con){
		echo "UNABLE TO CONNECT";
	}
	if(!mysqli_select_db($con,'events')){
			echo "INVALID DATABASE";
			}
	$sql = "insert into calevents values('$strtdate','$title')";
	if($con->query($sql)){
		echo 'Event created';
	}
	else{
		echo "Event can't be created";
	}
?>
<br>
<a href='see_event.php'>Download .ics<br></a>
<a href='cal.html'>View Calender</a>
</body>
</html>