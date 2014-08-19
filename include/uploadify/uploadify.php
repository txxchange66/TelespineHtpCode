<?php
ini_set('max_execution_time', -1);
ini_set('session.gc_maxlifetime', 5*60*60);
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/



 /*
         Work : implement uplodify in create treatment/edit treatment.
         Code Desc : code below will save the images and video in temporary folder.
                        it creates thumbnail of 640X480 for pic1,pic2 and pic 3,also
                        a small thumb.jpg or thumb.png or thumb.gif for pic 1 only
                        it does not change in video .
         Author : Abhishek Sharma
         Created Date : 9 May 2011
         Organization : Hytech Professionals
 */
         
 if($_GET['name']=='pic1' || $_GET['name']=='pic2' || $_GET['name']=='pic3' || $_GET['name']=='video')    
 {  
        if (!empty($_FILES))
        {   
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';    
        //$targetPathThumb =  $_SERVER['DOCUMENT_ROOT'] . $_Get['pathThumb'] . '/';       
                 if($_GET['name']=='pic1'){
                     $pict1='pict1';
                    $targetFile =  str_replace('//','/',$targetPath) . $pict1. 
                    substr($_FILES['Filedata']['name'],-4);}
             
                 if($_GET['name']=='pic2'){
                     $pict2='pict2';
                    $targetFile =  str_replace('//','/',$targetPath) . $pict2. 
                    substr($_FILES['Filedata']['name'],-4);}
             
                if($_GET['name']=='pic3'){                
                 $pict3='pict3';
                    $targetFile =  str_replace('//','/',$targetPath) . $pict3. 
                    substr($_FILES['Filedata']['name'],-4);}
             
                   if($_GET['name']=='video'){
                	/* $video='video';
                    $targetFile =  str_replace('//','/',$targetPath) . $video. 
                    substr($_FILES['Filedata']['name'],-4);*/

			$file1 =  $_FILES['Filedata']['name'];
		    	$file = "videosh16".$file1;
		    //$file = $file;		
                    	$targetFile =  str_replace('//','/',$targetPath) . $file;
		}
                    
                    
                    
                    


        move_uploaded_file($tempFile,$targetFile);   





        echo "1";
        }

         //code to cut thumbnail of image
        if($_GET['name']=='pic1'){
        $imgsize = getimagesize($targetFile);
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        $image = imagecreatefromjpeg($targetFile);
        break;
        case "png":
        $image = imagecreatefrompng($targetFile);
        break;
        case "gif":
        $image = imagecreatefromgif($targetFile);
        break;
        default:
        exit;
        break;
        }
             //thumbnail for pic 1 (640X480)
        $width = 640; 
        $height = 480; 

        $src_w = $imgsize[0];
        $src_h = $imgsize[1];

        $picture = imagecreatetruecolor($width, $height);
        imagealphablending($picture, false);
        imagesavealpha($picture, true);
        $bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

        if($bool)
        {
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        header("Content-Type: image/jpeg");



        $bool2 = imagejpeg($picture,$targetPath. $_GET['name'].".jpg",80);
        break;
        case "png":
        header("Content-Type: image/png");
        imagepng($picture,$targetPath . $_GET['name'].".png");
        break;
        case "gif":
        header("Content-Type: image/gif");
        imagegif($picture,$targetPath . $_GET['name'].".gif");
        break;
        }
        }

        imagedestroy($picture);
        //imagedestroy($image);

        echo '1';

        //thumbnail for pic1 100X75


        $widthth = 100; 
        $heightth = 75; 

        $src_wth = $imgsize[0];
        $src_hth = $imgsize[1];

        $pictureth = imagecreatetruecolor($widthth, $heightth);
        imagealphablending($pictureth, false);
        imagesavealpha($pictureth, true);
        $boolth = imagecopyresampled($pictureth, $image, 0, 0, 0, 0, $widthth, $heightth, $src_wth, $src_hth);

        if($bool)
        {
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        header("Content-Type: image/jpeg");



        $bool2th = imagejpeg($pictureth,$targetPath. thumb.".jpg",80);
        break;
        case "png":
        header("Content-Type: image/png");
        imagepng($pictureth,$targetPath .thumb.".png");
        break;
        case "gif":
        header("Content-Type: image/gif");
        imagegif($pictureth,$targetPath . thumb.".gif");
        break;
        }
        }

        imagedestroy($pictureth);
        imagedestroy($image);

        echo '1';



        }
        else
        {
        $imgsize = getimagesize($targetFile);
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        $image = imagecreatefromjpeg($targetFile);
        break;
        case "png":
        $image = imagecreatefrompng($targetFile);
        break;
        case "gif":
        $image = imagecreatefromgif($targetFile);
        break;
        default:
        exit;
        break;
        }

        $width = 640; //New width of image
        $height = 480; //This <span id="IL_AD10" class="IL_AD">maintains</span> <span id="IL_AD11" class="IL_AD">proportions</span>

        $src_w = $imgsize[0];
        $src_h = $imgsize[1];

        $picture = imagecreatetruecolor($width, $height);
        imagealphablending($picture, false);
        imagesavealpha($picture, true);
        $bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

        if($bool)
        {
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        header("Content-Type: image/jpeg");



        $bool2 = imagejpeg($picture,$targetPath. $_GET['name'].".jpg",80);
        break;
        case "png":
        header("Content-Type: image/png");
        imagepng($picture,$targetPath . $_GET['name'].".png");
        break;
        case "gif":
        header("Content-Type: image/gif");
        imagegif($picture,$targetPath . $_GET['name'].".gif");
        break;
        }
        }

        imagedestroy($picture);
        imagedestroy($image);

        echo '1';


        }
 }
 else
 {
         //this code make empty the profile image folder when user upload another image as profile picture  : code start.
      $cachedir = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
        if ($cachehandle = opendir($cachedir)) {
           while (false !== ($file = readdir($cachehandle))) {
            if ($file != "." && $file != "..") {
                $file2del = $cachedir."/".$file;
                unlink($file2del);     
               }
           }
           closedir($cachehandle);
        }
        
        //this code make empty the profile image folder when user upload another image as profile picture  : code ends.
     
     
   if (!empty($_FILES))
        {      
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';    
        $targetFile =  str_replace('//','/',$targetPath) .$_GET['name']. 
                    substr($_FILES['Filedata']['name'],-4);
                    
        move_uploaded_file($tempFile,$targetFile);  

       
         


        echo "1";
        $link = @mysql_connect('localhost', 'root', '');
        $dbsel = @mysql_select_db('mytxplandev',$link);
        $query = "update `user` set `profile_picture`='".$_GET['name']."' where `user_id`='".$_SESSION['username']."' ";
        $result = @mysql_query($query);
       
          
        }
        
          //code to cut thumbnail of image
          
        $imgsizepro = getimagesize($targetFile);
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        $imagepro = imagecreatefromjpeg($targetFile);
        break;
        case "png":
        $imagepro = imagecreatefrompng($targetFile);
        break;
        case "gif":
        $imagepro = imagecreatefromgif($targetFile);
        break;
        default:
        exit;
        break;
        }
             //thumbnail for pic 1 (640X480)
        $widthpro = 149; 
        $heightpro = 162; 

        $src_wpro = $imgsizepro[0];
        $src_hpro = $imgsizepro[1];

        $picturepro = imagecreatetruecolor($widthpro, $heightpro);
        imagealphablending($picture, false);
        imagesavealpha($picture, true);
        $bool = imagecopyresampled($picturepro, $imagepro, 0, 0, 0, 0, $widthpro, $heightpro, $src_wpro, $src_hpro);

        if($bool)
        {
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        header("Content-Type: image/jpeg");



        $bool2 = imagejpeg($picturepro,$targetPath. $_GET['name']."thumb1.jpg",80);
        break;
        case "png":
        header("Content-Type: image/png");
        imagepng($picturepro,$targetPath . $_GET['name']."thumb1.png");
        break;
        case "gif":
        header("Content-Type: image/gif");
        imagegif($picturepro,$targetPath . $_GET['name']."thumb1.gif");
        break;
        }
        }

        imagedestroy($picturepro);
        //imagedestroy($imagepro);

        echo '1';
         $widthpro = 91; 
        $heightpro = 85; 

        $src_wpro = $imgsizepro[0];
        $src_hpro = $imgsizepro[1];

        $picturepro = imagecreatetruecolor($widthpro, $heightpro);
        imagealphablending($picture, false);
        imagesavealpha($picture, true);
        $bool = imagecopyresampled($picturepro, $imagepro, 0, 0, 0, 0, $widthpro, $heightpro, $src_wpro, $src_hpro);

        if($bool)
        {
        switch(strtolower(substr($targetFile, -3)))
        {
        case "jpg":
        header("Content-Type: image/jpeg");



        $bool2 = imagejpeg($picturepro,$targetPath. $_GET['name']."thumb2.jpg",80);
        break;
        case "png":
        header("Content-Type: image/png");
        imagepng($picturepro,$targetPath . $_GET['name']."thumb2.png");
        break;
        case "gif":
        header("Content-Type: image/gif");
        imagegif($picturepro,$targetPath . $_GET['name']."thumb2.gif");
        break;
        }
        }

        imagedestroy($picturepro);       
        
        
        
        //imagedestroy($imagepro);

        echo '1';
        
       
      
       

          
 
 
 }




?>
