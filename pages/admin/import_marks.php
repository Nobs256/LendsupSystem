<?php
if($_FILES['myfile']['type']=="application/vnd.ms-excel" || $_FILES['myfile']['type']=="text/comma-separated-values" || $_FILES['myfile']['type']=="text/csv")
{
$my_file=1;
$target_path = "csv_marks/";	
$target_path = $target_path . basename( $_FILES['myfile']['name']); 

if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) 
{
$filePath = "csv_marks/";
$filePath = $filePath . basename( $_FILES['myfile']['name']); 
$count1=0;
if(($handle12 = @fopen("$filePath", "r")) !== FALSE) 
{
while (($data = @fgetcsv($handle12))!== FALSE) 
{
if($count1>=1)
{
$names=$data[1];	
$regno=$data[2];
$cw=$data[3];
$mid=$data[4];
$eot=$data[5];

if(strlen($regno)>=1)
{
echo $names.", REGNO: ".$regno.", CW: ".$cw.", MID: ".$mid.", EOT: ".$eot."<hr>";
//---Execute queries

}		
}
$count1++;
}
	
}
}
}

else
{
$my_file=0;
}