<?php
 
//Reduce errors
error_reporting(~E_WARNING);

// Connect to the database
$servername = "localhost";
$username   = "tilt";
$password   = "tilt";
$database   = "tilt";

$link = mysqli_connect($servername,$username,$password,$database);
if (!$link)
  die("Connection failed: " . mysqli_connect_error());

$sql = "select abv from device";
$rowVal = mysqli_query($link, $sql);
echo "rows = ",mysqli_num_rows($rowVal)."\n";
if(mysqli_num_rows($rowVal) > 0)
{
  $firstRow = FALSE;
  $sql = "select sg from device order by time asc limit 1;";
  $rowVal = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($rowVal);
  $ogStr = (float) $row["sg"];
  echo "\$ogStr = $ogStr\n";
}else{
  $firstRow = TRUE;
}

$bmFile = fopen("/var/log/tilt.log", "a+") or die("Unable to open file!");

// $tiltStr = "XX:XX:XX:XX:XX:XX"; // put your tilt MAC address here
$tiltStr = "88:C2:55:AC:33:AA";
$tilt = "/$tiltStr/i"; // put your tilt MAC address here
echo "\$tiltStr = $tiltStr, \$tilt = $tilt\n";
$command = escapeshellcmd('sudo python ./tiltblescan.py');
$output = shell_exec($command);
$tiltArray = explode("\n", $output);

foreach($tiltArray as $value)
{
  if(preg_match($tilt, $value))
  {
    $result = explode(",", $value);
    break;
  }
}
// echo "$result[2],$result[3]\n";

  $now = time();
  $sgStr = (float) ($result[3] / 1000);
  $tempStr = $result[2];
  $dateStr = date("Y-m-d H:i:s", $now);
  if($firstRow == TRUE)
  {
    $abvStr = 0.0;
  }else{
    $abvStr = (float) (((float) $ogStr - (float) $sgStr) * (float) 131.25);
  }
  $abvStr = round($abvStr, 2);
  echo "\$sgStr = $sgStr, \$tempStr = $tempStr, \$abvStr=$abvStr \n";

  $bmStr = "name - Red, MAC = $tiltStr, Time - $now - $dateStr, SG - $sgStr, Temp - $tempStr, degrees, ABV - $abvStr percent\n"; 
  fwrite ($bmFile, $bmStr);
  if($tempStr > 0)
  {
    $sql= "INSERT INTO `device`(`name`,`mac`,`time`, `sg`, `temp`, `abv`) VALUES('Red','$tiltStr','$now','$sgStr','$tempStr','$abvStr')";
    if (!mysqli_query($link, $sql))
    {
      $bmErrStr= "Error: " . $sql . "<br>" . mysqli_error($link);
      fwrite ($bmFile, $bmErrStr);
    }
  }

  fclose($bmFile);
?>

