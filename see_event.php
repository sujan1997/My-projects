<?php
session_start();
$title= $_SESSION['etitle'];
$loc = $_SESSION['elocation'];
$strtdate =$_SESSION['startdate'];
$strttime =$_SESSION['starttime'];
$enddate =$_SESSION['enddate'];
$endtime =$_SESSION['endtime'];
$cal = file_get_contents('eventsdup.ics');
$convert = explode("\n", $cal);
header("Content-Type: text/Calendar; charset=utf-8");
header("Content-Disposition: inline; filename=eventsdup2.ics");
echo implode("\n",$convert);
echo "\nBEGIN:VEVENT\n";
echo "UID:".date('Ymd').'T'.date('His')."-".rand()."-learnphp.co\n"; // required by Outlok
echo "DTSTART:{$strtdate}\n";
echo "TSTART:{$strttime}\n"; 
echo "DTEND:{$enddate}\n";
echo "TEND:{$endtime}\n";
echo "LOCATION:{$loc}\n";
echo "SUMMARY:{$title}\n";
echo "END:VEVENT\n";
echo "END:VCALENDAR\n";
?>