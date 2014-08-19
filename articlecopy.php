<?php

require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        

$sql="select *,date_format(creation_date,'%Y') as y1,date_format(creation_date,'%c') as m1,date_format(creation_date,'%e') as d1 from article where article_type='2'";
$query=mysql_query($sql);
$i=0;
if(mysql_num_rows($query)> 0){
	while($row=mysql_fetch_array($query)){
	//	if($i<200){
		//echo $row['creation_date'];
		 $year=$row['y1'];
		//echo '<br>';
		 $month=$row['m1'];
		//echo '<br>';
		 $date=$row['d1'];
		//echo '<br>';
        $source=$application_path.'asset/images/article/'.$row['article_id'].'/'.$row['file_url'];
       // echo '<br>';
        $destination=$application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date.'/'.$row['article_id'].'/'.$row['file_url'];
        //echo '<br>';
        $fileurl=$year.'/'.$month.'/'.$date.'/'.$row['article_id'].'/'.$row['file_url'];
        echo $row['article_id'];
        echo '<br>';
        
       if(!is_dir($application_path.'asset/images/articlenew/')){
        	mkdir($application_path.'asset/images/articlenew/');
        	chmod($application_path.'asset/images/articlenew/',0777);
        }		
	   if(!is_dir($application_path.'asset/images/articlenew/'.$year)){
            mkdir($application_path.'asset/images/articlenew/'.$year);
            chmod($application_path.'asset/images/articlenew/'.$year,0777);
        }        
	   if(!is_dir($application_path.'asset/images/articlenew/'.$year.'/'.$month)){
            mkdir($application_path.'asset/images/articlenew/'.$year.'/'.$month);
            chmod($application_path.'asset/images/articlenew/'.$year.'/'.$month,0777);
        }
	   if(!is_dir($application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date)){
            mkdir($application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date);
            chmod($application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date,0777);
        }
	   if(!is_dir($application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date.'/'.$row['article_id'])){
            mkdir($application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date.'/'.$row['article_id']);
            chmod($application_path.'asset/images/articlenew/'.$year.'/'.$month.'/'.$date.'/'.$row['article_id'],0777);
        }
        copy($source,$destination);
        $update="update article set file_path='".$fileurl."' , copystatus='1' where article_id='".$row['article_id']."'";
        mysql_query($update);
        $i++;
//	}
	}
	
}      





?>