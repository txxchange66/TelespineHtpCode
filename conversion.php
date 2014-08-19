<?php


 require_once("config.php");

	$host = $txxchange_config['dbconfig']['db_host_name'];
	$user = $txxchange_config['dbconfig']['db_user_name'];
	$pass = $txxchange_config['dbconfig']['db_password'];
	$db   = $txxchange_config['dbconfig']['db_name'];
	$application_path   = $txxchange_config['application_path'];
    $templatePath = $application_path."mail_content/remindermail.php";
    $templatePath_plpto = $application_path."mail_content/plpto/video_conversion.php";
    $templatePath_wx= $application_path."mail_content/wx/video_conversion.php";
    $imagePath = $txxchange_config['images_url'];
    $from_email_address = $txxchange_config['from_email_address'];
	$privatekey = $txxchange_config['private_key'];
	
	$tid = $argv[1]; //$_REQUEST['tid'];
     $link = mysql_connect($host,$user,$pass);
    // select database.
     mysql_select_db($db,$link);
   echo  $resname="select original_video from treatment where treatment_id ={$tid}";
	$videoname=mysql_query($resname);
	$videon=mysql_fetch_array($videoname);
	mysql_close($link);
	echo  $file = $videon[0]; //$_REQUEST['file'];
    echo $file1 =$videon[0]; //$_REQUEST['file'];
    $len=strlen($file1);
    $lpart = substr($file, 9, $len);
    $mailFile = $lpart;
    $fileNameParts = explode( ".", $file ); // seperate the name from the ext
   	$fileExtension = end( $fileNameParts ); // part behind last dot
   	$fileExtension = strtolower( $fileExtension );
	
	
	/*-----------------------------------------------------------------------------------------------
	*---------- New Code Added By Rohit Mishra for ISSUE TXM-48--------------------------------------
	*---------- Mobile Video display in HTML5--------------------------------------------------------
	*/
	
	$filename = substr($file, 0, -4); 
			
	$newfile = "video.mp4";
	$newfile1 = "video.webm";
	$newfile2 = "video.ogv";
	$targetfile= $application_path."asset/images/treatment/".$tid."/".$newfile;
	$targetfile1= $application_path."asset/images/treatment/".$tid."/".$newfile1;
	$targetfile2= $application_path."asset/images/treatment/".$tid."/".$newfile2;
	$sourcefile= $application_path."asset/images/treatment/".$tid."/video.flv";
	/*------------------------------------------------------------------------------------------------*/
	
	
	
    /*if($fileExtension=='flv')
   	{
		   		//echo 'flv';
		   	$pathforRename = 'asset/images/treatment/'.$tid.'/video.flv';
		   	chmod($pathforRename,'777');
		   	rename('asset/images/treatment/'.$tid.'/video.flv', 'asset/images/treatment/'.$tid.'/originalFlv.flv');
		   	$file1='originalFlv.flv';
   	}
   	else if($fileExtension!='flv')
    {
            $file1=$file;
    }*/
	
    define('FFMPEG_LIBRARY', '/usr/local/bin/ffmpeg ');
	echo $exec_string = FFMPEG_LIBRARY." -i 'asset/images/treatment/".$tid."/".$file1."' -y -vcodec libx264 -s 640x360 -flags +loop -cmp +chroma -deblockalpha 0 -deblockbeta 0 -crf 24 -bt 256k -refs 1 -coder 0 -subq 5 -partitions +parti4x4+parti8x8+partp8x8 -g 250 -keyint_min 25 -level 30 -qmin 10 -qmax 51 -trellis 2 -sc_threshold 40 -i_qfactor 0.71 -acodec libfaac -ab 96k -ar 44100 -ac 2 asset/images/treatment/".$tid."/video.flv";
    exec($exec_string);
	
	
	/*-----------------------------------------------------------------------------------------------
	*---------- New Code Added By Rohit Mishra for ISSUE TXM-48--------------------------------------
	*---------- Mobile Video display in HTML5--------------------------------------------------------
	*/
	
	$exec_string = FFMPEG_LIBRARY." -i '".$sourcefile."'  -vcodec libx264 -vpre ipod640 -b 250k -bt 50k -acodec libfaac -ab 56k -ac 2 -s 480x320   ".$targetfile;  
				exec($exec_string);

	$exec_string = FFMPEG_LIBRARY." -i '".$sourcefile."'  -ar 22050 ".$targetfile2;  
				exec($exec_string);
	
	
	
    ###################################
    
    echo '<br>';
    echo '<br>';
   echo  $exec_string_thumbnail = FFMPEG_LIBRARY." -ss 02 -i asset/images/treatment/".$tid."/video.flv -y -f image2 -vframes 1 asset/images/treatment/".$tid."/videolarge.jpg";
    exec($exec_string_thumbnail);
    $targetFile = 'asset/images/treatment/'.$tid.'/videolarge.jpg';
    $imgsize = getimagesize($targetFile);
        $widthth = 100;
        $heightth = 75;
        $src_wth = $imgsize[0];
        $src_hth = $imgsize[1];
         $image = imagecreatefromjpeg($targetFile);
        $pictureth = imagecreatetruecolor($widthth, $heightth);
        imagealphablending($pictureth, false);
        imagesavealpha($pictureth, true);
        $boolth = imagecopyresampled($pictureth, $image, 0, 0, 0, 0, $widthth, $heightth, $src_wth, $src_hth);
        $targetPath='asset/images/treatment/'.$tid.'/';
        if($boolth)
        {

        header("Content-Type: image/jpeg");
        $bool2th = imagejpeg($pictureth,$targetPath."video.jpg",80);
        }
    ###############
    
    
    $link = mysql_connect($host,$user,$pass);
    // select database.
    mysql_select_db($db,$link);
    
   echo  $querya = "update treatment set vconversion_status='1', video='video.flv', status= '1' WHERE treatment_id ='". $tid."'";
    $resulta = mysql_query($querya) ;
	
   echo   $query = "SELECT user_id,username,usertype_id from user where user_id = (SELECT user_id from treatment WHERE treatment_id = '$tid')";
	$result = mysql_query($query) ;
	$id = mysql_fetch_array($result);
    
	$data['mailFile']= $mailFile;
	if($id['usertype_id']!=4){
        $clinic_channel=getchannel(get_clinic_info( $id['user_id'],'clinic_id'));
	}
    else{
        $clinic_channel=1;
    }
    if($clinic_channel==2){
        $business_url=$txxchange_config['business_wx']; 
        $support_email=$txxchange_config['email_wx'];  
    	
    }else{
        $business_url=$txxchange_config['business_tx']; 
        $support_email=$txxchange_config['email_tx'];
    }
       $data['business_url'] = $business_url;
       $data['support_email'] = $support_email;
    if( $clinic_channel == 2){
        $message = build_template($templatePath_wx,$data);
        }
    else{
        $message = build_template($templatePath_plpto,$data);
    	
     }
//$to = 'sanjay.gairola@hytechpro.com';                           	
	$to = $id['username'];
	$subject = "Your Video has Finished Converting";
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
	
	// Additional headers
	 if( $clinic_channel == 2){
	   $headers .= "From: Wholemedx Support<noreply@txxchange.com>" . "\n";
	   }else{
	   $headers .= "From: Tx Xchange Support<noreply@txxchange.com>" . "\n";
	   }
	$returnpath = '-fnoreply@txxchange.com';
	//$message = "your video has been converted";
	// Mail it
	mail($to, $subject, $message, $headers, $returnpath);
			
    /**
         * This function get the channel type.
         * @param numeric $user_id
         * @return channel type
         * @access public
         */
        function getchannel($clinic_id){
           $sql="SELECT clinic_channel FROM clinic where clinic_id=".$clinic_id;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['clinic_channel'];   
        }
/**
         * This functio get clinic Id or clinic name of a user from clinic_user table.
         *
         * @param string $user_id:: user id of which details to be fetched.
         * @param integer $field:: which field value to get.
         * @return mixed
         * @access public
         */
        function get_clinic_info($user_id,$field = "clinic_id" ){
            if( is_numeric($user_id) && $user_id >0 ){
                $sql = "select clinic_id from clinic_user where user_id = '".$user_id."'";
                $result = mysql_query($sql);
                while( $row = mysql_fetch_array($result) ){
                    $clinic_id = $row["clinic_id"];
                    if(is_numeric($clinic_id) && $clinic_id > 0  &&  $field == "clinic_id" ) {
                        return $row[$field];
                    }
                    if( is_numeric($clinic_id) && $clinic_id > 0 &&  $field == "clinic_name" ){
                        $clinic_name = get_clinic_name($clinic_id,"clinic_name");                      return $clinic_name;
                    }
                }
            }
            return "";
        }
        /**
        * Get clinic name of a user from the clinic table.
        * @param integer $clinic_id
        * @return string $clinic_name
        * @access public
        */
        function get_clinic_name($clinic_id,$field = "clinic_name" ){
            if( is_numeric($clinic_id) && $clinic_id >0 ){
                $sql = "select clinic_name from clinic where clinic_id = '{$clinic_id}'";
                $result = mysql_query($sql);
                $row = mysql_fetch_array($result); 
                $clinic_name = $row["clinic_name"];
                return $clinic_name;
           }
                
        
            return "";
        }
        /**
            * Template parsing function.
        */
        function build_template($template_path, $replace="") {
            $content = file_get_contents($template_path);
            while( is_array($replace) && list($key,$value) = each($replace) ){
                $patterns = '/<!' . $key . '>/';
                $value = (string)$value;
                if (empty($value) === false) {
                    $content = preg_replace($patterns, $value, $content);
                }else{
                    $content = preg_replace($patterns, $value, $content);
                }
            }
            return $content;
        }

?>
