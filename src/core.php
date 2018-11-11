<?php
session_start();
require_once('app/autoload.php');


//echo "<br /><br />123<br />";
if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/config/tellstick_conf.json')) {
	//echo "config/tellstick_conf.json does not exist. Creating file...<br />";

	$myfile = fopen($_SERVER['DOCUMENT_ROOT']."/config/tellstick_conf.json", "w");
	fwrite($myfile, '');
	fclose($myfile);

	//touch("config/tellstick_conf.json");
} else {
	//echo "FOUND config/tellstick_conf.json<br />";
}


if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/config/token.json')) {
	//echo "config/token.json does not exist. Creating file...<br />";

	$myfile2 = fopen($_SERVER['DOCUMENT_ROOT'].'/config/token.json', "w");
	fwrite($myfile2, '');
	fclose($myfile2);

	//touch($_SERVER['DOCUMENT_ROOT'].'/config/token.json');
} else {
	//echo "FOUND config/token.json<br />";
}



if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/config/mqtt_conf.json')) {
	//echo "config/token.json does not exist. Creating file...<br />";

	$myfile3 = fopen($_SERVER['DOCUMENT_ROOT'].'/config/mqtt_conf.json', "w");
	fwrite($myfile3, '');
	fclose($myfile3);

	//touch($_SERVER['DOCUMENT_ROOT'].'/config/token.json');
} else {
	//echo "FOUND config/token.json<br />";
}




/* if (!file_exists($_SERVER['DOCUMENT_ROOT'].'config/tellstick_conf.json')) {
	//die('tellstick_conf.json does not exist');
}

if (!file_exists($_SERVER['DOCUMENT_ROOT'].'config/tellstick_conf.json')) {
	touch("config/token.json");

	//die('token.json does not exist');
	
} */



function chmod_r($dir, $dirPermissions, $filePermissions) {
      $dp = opendir($dir);
       while($file = readdir($dp)) {
         if (($file == ".") || ($file == ".."))
            continue;

        $fullPath = $dir."/".$file;

         if(is_dir($fullPath)) {
            //echo('DIR:' . $fullPath . "\n");
            chmod($fullPath, $dirPermissions);
            chmod_r($fullPath, $dirPermissions, $filePermissions);
         } else {
            //echo('FILE:' . $fullPath . "\n");
            chmod($fullPath, $filePermissions);
         }

       }
     closedir($dp);
  }

chmod_r($_SERVER['DOCUMENT_ROOT'].'/config', 0777, 0777);