<?php
 
//Reduce errors
  error_reporting(~E_WARNING);

  $command = escapeshellcmd('sudo python tiltblescan.py');
  $output = shell_exec($command);
  $tiltArray = explode("\n", $output);

  $stopLoop = FALSE;
  foreach($tiltArray as $value)
  {
    $result = explode(",", $value);
    if(($result[3] >= 900) && ($result[3] <= 1200))
    {
      $sgStr = (float) ($result[3] / 1000);
      echo "MAC = $result[0], SG = $sgStr, Temp = $result[2]\n";
      $stopLoop = TRUE;
    }
    if($stopLoop == TRUE) break;
  }

?>

