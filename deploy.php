<?php
// Forked from https://gist.github.com/1809044
// Available from https://gist.github.com/nichtich/5290675#file-deploy-php

require_once '/var/secure/convertapi.php';
$convertUrl = "https://v2.convertapi.com/web/to/pdf?Secret=$convertapiKey&download=inline&url=http://resume.anthonymandra.com";
$convertCmd = "wget \"$convertUrl\" -O resumeMandra.pdf";

// Actually run the update
$commands = array(
	'echo $PWD',
	'whoami',
	'git pull',
	'git status',
	"$convertCmd"
);
$output = "\n";
$log = "####### ".date('Y-m-d H:i:s'). " #######\n";
foreach($commands AS $command){
    // Run it
    $tmp = shell_exec("$command 2>&1");
    // Output
    $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
    $output .= htmlentities(trim($tmp)) . "\n";
    $log  .= "\$ $command\n".trim($tmp)."\n";
}
$log .= "\n";
file_put_contents ('deploy-log.txt',$log,FILE_APPEND);
echo $output; 
?>
