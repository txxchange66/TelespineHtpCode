<?php
    require_once("include/fileupload/class.upload.php");    
    require_once("module/application.class.php");
    require_once("include/validation/_includes/classes/validation/ValidationSet.php");    
    // Define Constants
    // --IMAGE CONSTANTS
    define('RESTRICT_WIDTH', 1);
    define('RESTRICT_HEIGHT', 2);

    if(!defined('IMAGETYPE_GIF')) define('IMAGETYPE_GIF', 1);
    if(!defined('IMAGETYPE_JPEG')) define('IMAGETYPE_JPEG', 2);
    if(!defined('IMAGETYPE_PNG')) define('IMAGETYPE_PNG', 3);

    // class declaration
      class treatmentUpload extends application{
        private $action;
        private $arr;
        private $error;
        private $output;
        private $invalid;
        private $uploaded;
        function __construct(){
            parent::__construct();
            if($this->value('action')){
                $str = $this->value('action');
            }else{
                header("location:index.php?sysAdmin");//default if no action is specified
            }
            $this->action = $str;
            $str = $str."()";
            eval("\$this->$str;"); 
            $this->display();
        }
        function uploadTreatment()
        {
            include_once("template/treatmentManager/createTreatmentArray.php");
            //First know about user type systemadmin or (A/c admin / therapist)
            $userId = 52;        
            if( $this->value('formSubmit') == "submit" )
            {                
                $this->validateFormTreatment();
                $replace['error'] = $this->error;
                if($replace['error'] == "")
                {
                    //execute insert process
                    //First insert record in treatment table and get the treatment_id 
                    $treatment_id = $this->insertTreatmentRecord();

                    //check for fileupload
                    $arrFileContent = $this->handleFileUpload($treatment_id);    
                    if (!empty($this->invalid))
                    {
                        //error
                        $searchArray = array('pic1','pic2','pic3');
                        for ($i=0;$i<count($searchArray);++$i)
                        {
                            if (array_key_exists($searchArray[$i],$this->invalid))
                            {
                                $replace['error'] = 'Invalid image type for'.$searchArray[$i].', please make sure the image extension is of type jpeg/jpg/png/bmp/gif';                                
                                break;
                            }
                        }
                        if ($replace['error'] == "")
                        {
                            if (array_key_exists('video',$this->invalid))
                            {
                                $replace['error'] = 'Invalid video type, please specify that the video file is of extension .flv';    
                            }
                        }

                        // Delete the treatment record
                        $this->deleteBadTreatmentRecord($treatment_id);
                        $showForm = 1;        
                    }                        
                    else 
                    {
                        //everything goes well
                        //insert the rest of the record fields
                        //also check if usertype is sysadmin then clinic_id = null else clinicid 
                        $clinic_id = null;
                        $condition = " treatment_id = ".$treatment_id;
                        $updateFieldsArray = array(
                                                    'benefit' => $this->value('benefit'),
                                                    'instruction' => $this->value('instruction'),
                                                    'sets' => $this->value('sets'),
                                                    'reps' => $this->value('reps'),
                                                    'hold' => $this->value('hold'),
                                                    'lrb' => $this->value('lrb'),
                                                    'status' => $this->value('status'),
                                                    'user_id' => $userId,
                                                    'clinic_id' => $clinic_id
                                                    );

                        $tableName = 'treatment';                                                                    
                        if ($arrFileContent != null)
                        {
                            foreach ($arrFileContent as $key => $value )
                            {        
                                if (!empty($value)) 
                                {                                        
                                    $updateFieldsArray[$key] =     $value;                                    
                                }
                            }                            
                        }
                        $result = $this->update($tableName,$updateFieldsArray,$condition);
                        // Add tags insertTag
                        if( $result ){
                            $tag = $this->value('tag');
                            $tag_treatment_id = $treatment_id;
                            if( strlen($tag) > 0 && is_numeric($treatment_id) && $treatment_id > 0 ){
                                $this->insertTag($tag,$tag_treatment_id);
                            }
                        }
                        if($result){
                            echo $this->get_treatment_record($treatment_id);
                            return;
                        }
                    }
                }    
            }
            echo "failed";
        }
        function get_treatment_record($treatment_id){
            if($treatment_id != "" ){
                $treatment_name = $this->get_field($treatment_id,'treatment','treatment_name');
                $treatment_instruction = $this->get_field($treatment_id,'treatment','instruction');
                $treatment_tag = $this->get_field($treatment_id,'treatment','treatment_tag');
                return "<tr>
                            <td>$treatment_id</td>
                            <td>$treatment_name</td>
                            <td>$treatment_instruction</td>
                            <td>$treatment_tag</td>
                            <td><img src='https://app.txxchange.com/asset/images/treatment/{$treatment_id}/thumb.jpg' /></td>
                            <td>Success</td>
                     </tr>";
            }
            return "";
        }
        /**
         * Helper function used while creating treatment record, here it is the db insertion
         *
         * @access public
         */

        function insertTreatmentRecord()
        {
            $treatment_id = 0;                            
            //$userInfo = $this->userInfo();
            $insertArr = array(
                                'treatment_name' => $this->value('treatment_name'),
                                'treatment_tag' => $this->value('treatment_name'),
                                'creation_date' => date('Y-m-d H:i:s',time()),
                                'status'=>    $this->value('status')            
                                );
            $result = $this->insert('treatment',$insertArr);    
            $treatment_id = $this->insert_id();                            
            if (!$result)
            {
                $treatment_id = 0;    
            }
            return $treatment_id;            
        }
        /**
         * Helper function used for db record insertion execution.
         * This function is the customized version exclusively for treatment records insertion
         *
         * @access public
         */

        function executeInsertQuery($tableName,$fieldArray,$flagAllFields = true)
        {
            $sql = NULL;
            if ($flagAllFields == true)        
            {
                $sql = "INSERT INTO ".$tableName." VALUES (".$fieldArray[0];
                for ($i = 1; $i<count($fieldArray);++$i)
                {
                    $sql = $sql.",".$fieldArray[$i];
                }            
                $sql = $sql.")";
            }
            else 
            {
                $keys    = array();
                $keys = array_keys($fieldArray);
                $sql = "INSERT INTO ".$tableName."(".$keys[0];
                for ($i = 1; $i < count($fieldArray); ++$i)
                {
                    $sql = $sql.",".$keys[$i];
                }
                $sql = $sql.") VALUES (".addslashes($fieldArray[$keys[0]]);
                for ($i = 1; $i<count($fieldArray);++$i)
                {
                    $sql = $sql.",".addslashes($fieldArray[$keys[$i]]);
                }
                $sql = $sql.")";
              }    
            $result = $this->execute_query($sql);    
            return $result;
        }
        /**
         * Helper function used for db record updation execution.
         * This function is the customized version exclusively for treatment records updation
         *
         * @access public
         */

        function updateTreatmentRecord($tableName,$updateFieldsArray,$condition = "")
        {
            $sql = NULL;
            $keys    = array();        
            $keys = array_keys($updateFieldsArray);            
            $sql = "UPDATE ".$tableName." SET ".$keys[0]." = ".addslashes($updateFieldsArray[$keys[0]]);
            for ($i=1;$i<count($updateFieldsArray);++$i)
            {
                $sql = $sql.",".$keys[$i]." = ".addslashes($updateFieldsArray[$keys[$i]]);
            }
            // also the conditionif any                
            $sql = $sql.$condition;        
            $result = $this->execute_query($sql);
            return $result;
        }
        /**
         * Helper function used for treatment deletion.
         * This function is different from normal deletion functionality in the sense
         * that its a conditional deletion and not the application functionality
         * This is executed if the program fails in insert/edit treatment record, due to file upload error
         *
         * @access public
         */
        function deleteBadTreatmentRecord($treatment_id)
        {        
            $sql = NULL;
            $sql = "DELETE FROM treatment WHERE treatment_id = ".$treatment_id;        
            $result = $this->execute_query($sql);        
        }
        /**
         * Helper function for file upload (treatment images/videos files)
         *
         * @access public
         */
        function handleFileUpload($treatment_id)
        {
            $ins2 = null;
            // check file upload
            $this->uploaded = false;
            $this->is_valid_file('pic1');
            $this->is_valid_file('pic2');
            $this->is_valid_file('pic3');
            $this->is_valid_file('video');
        
            if (!empty($this->invalid))
            {
                $msg = $this->invalid;
            }
            else 
            {
                if($this->uploaded)
                {    
                    $pic1 = $this->do_file_upload('pic1', $treatment_id);
                    $pic2 = $this->do_file_upload('pic2', $treatment_id);
                    $pic3 = $this->do_file_upload('pic3', $treatment_id);    
                    $video = $this->do_file_upload('video', $treatment_id);                
                    $ins2 = array(
                        //'treatment_id' => $treatment_id,
                        'pic1' => ($pic1 == false)? "":$pic1,
                        'pic2' =>  ($pic2 == false)? "":$pic2,
                        'pic3' =>  ($pic3 == false)? "":$pic3,
                        'video' => ($video == false)? "":$video
                    );                    
                }
                else 
                {
                    //error redirect to create treatment form page
                    $ins2 = null;
                }
            }
            return $ins2;
        }
        function validateCreateTreatement(){
            $error = array();            
            if(trim($this->value('treatment_name')) == "" ){
                $error[] = "Please Enter Your Treatment Name.";
            }
            if(count($error) > 0 ){
                $error = $this->show_error($error);
            }
            else{
                $error = "";
            }
            return $error;    
        }        
        /**
         * Customized function for creating HTML input checkbox in HTML form
         *
         * @access public
         */
        function buildInputCheckBox($arrCheckBox,$inputName, $value)
        {
            $noElements = count($arrCheckBox); 
            $inputCheckBox = "";
            for($i=0;$i<$noElements;++$i)
            {
                $inputCheckBox.= "<input type='checkbox' name='".$inputName."[]' " .$arrCheckBox[$i]['extra']. " value='".$arrCheckBox[$i]['value']."'";
                if (is_array($value))
                {
                    $inputCheckBox.= in_array($arrCheckBox[$i]['value'],$value) ? "checked='checked'":"";    
                }                
                $inputCheckBox.= " /> &nbsp;<label>". $arrCheckBox[$i]['lblName']."</label>&nbsp";        
           }
            return $inputCheckBox;
        }
        /**
         * Customized function for creating HTML input radiobox in HTML form
         *
         * @access public
         */
        function buildInputRadioBox($arrRadioBox,$inputName,$value)
        {
            $noElements = count($arrRadioBox); 
            $inputRadioBox = "";
            for($i=0;$i<$noElements;++$i)
            {
                $inputRadioBox.= "<input type='radio' name='".$inputName."' " .$arrRadioBox[$i]['extra']. " value='".$arrRadioBox[$i]['value']."'";
                if ($arrRadioBox[$i]['value'] == $value)
                {
                    $inputRadioBox.= "checked='checked'";    
                }                
                $inputRadioBox.= " /> &nbsp;<label>". $arrRadioBox[$i]['lblName']."</label>&nbsp";        
            }
            return $inputRadioBox;
        }

        /**
         * Validates the complete treatment form fields filled in by user while create/edit treatment
         *
         * @access public
         */
        function validateFormTreatment($uniqueId = null)
        {
            
            $objValidationSet = new ValidationSet();                    
            $objValidationSet->addValidator(new  StringMinLengthValidator('treatment_name', 1, "Treatment Name cannot be empty",$this->value('treatment_name')));    
            $objValidationSet->addValidator(new AlphanumericOnlyValidator('treatment_name','@',"Please enter valid characters in treatment name",$this->value('treatment_name')));
            $objValidationSet->addValidator(new AlphanumericOnlyValidator('instruction','@',"Please enter valid characters in instruction",$this->value('instruction')));
            $objValidationSet->addValidator(new AlphanumericOnlyValidator('benefit',null,"Please enter valid characters in benefit",$this->value('benefit')));
            $objValidationSet->addValidator(new AlphanumericOnlyValidator('sets',null,"Please enter valid characters in sets",$this->value('sets')));
            $objValidationSet->addValidator(new AlphanumericOnlyValidator('reps',null,"Please enter valid characters in reps",$this->value('reps')));
            $objValidationSet->addValidator(new AlphanumericOnlyValidator('hold',null,"Please enter valid characters in hold",$this->value('hold')));

            $objValidationSet->validate();            
            if ($objValidationSet->hasErrors())
            {
                $arrayFields = array("treatment_name","instruction","benefit","sets","reps","hold");
                for($i=0;$i<count($arrayFields);++$i)
                {
                    $errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
                    if ($errorMsg != "")
                    {
                        $this->error = $errorMsg."<br>";
                        break;
                    }
                }            
            }    
            else 
            {
                $this->error = "";    
            }
        }
        function is_valid_file($fieldname)
        {
            $imageTypeAllowed = array(
                                       "image/bmp",
                                       "image/gif",
                                       "image/jpeg",
                                       "image/pjpeg",
                                       "image/png",
                                       "image/x-png",
                                       "image/tiff",
                                       "image/x-tiff",
                                       "image/x-windows-bmp"            
                                        );
            if($this->have_uploaded_file($fieldname))
            {
                if($_FILES[$fieldname]['size'])
                {
                    if($_FILES[$fieldname]['name'])
                    {
                        if ($fieldname != 'video') 
                        {                        
                            if (in_array($_FILES[$fieldname]['type'],$imageTypeAllowed))
                            {
                                $this->uploaded = true;
                            }
                            else 
                            {
                                $this->invalid[$fieldname] = 'invalid image type';
                            }
                        }
                        else
                        {

                            if(strripos($_FILES[$fieldname]['name'], '.flv') !== (strlen($_FILES[$fieldname]['name']) - 4)) $this->invalid[$fieldname] = 'invalid video type ('.$_FILES[$fieldname]['name'].') ('.strripos($_FILES[$fieldname]['name'], '.flv').')  ('.(strlen($_FILES[$fieldname]['name']) - 4).')';

                            else $this->uploaded = true;

                        }

                    }

                    else $this->invalid[$fieldname] = 'invalid file name';

                }

                else

                {

                    $this->invalid[$fieldname] = 'invalid file size';

                }

            }

            

        }
        /**
         * Helper function for file upload.
         * This function checks if file has been uploaded at server or some error has occured
         *
         * @access public
         */
        function have_uploaded_file($fieldName)
        {
            if (!isset($_FILES[$fieldName])) 
            {
                //echo 'no file called '.$fieldName."<br>";
                return false;
            }
            if (!is_uploaded_file($_FILES[$fieldName]['tmp_name']))
            {
                //echo 'no uploaded file '.$fieldName."<br>";
                return false;
            }
            return true;
        }
        /**
         * Helper function for image file upload.
         * This function resize the image file to the desired image dimensions specs
         *
         * @access public

         */
        function resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)
        {
            $imgInfo = getimagesize($imagePath.$imageName);
            $imgType = $imgInfo[2];
            $imageX = $imgInfo[0];
            $imageY = $imgInfo[1];
            // resize the image based on restrictions, otherwise we leave it alone
            if ($dimX != 0) $imageX = $dimX;
            if ($dimY != 0) $imageY = $dimY;
            if ($dimRestrict == RESTRICT_WIDTH){
                $imageY = $imgInfo[1] * ($imageX / $imgInfo[0]);
                 if( $imageY > 480 && ($fileName == "pic1.jpg" || $fileName == "pic2.jpg" || $fileName == "pic3.jpg") ){
                     $imageY = 480;
                 }
            }
            elseif ($dimRestrict == RESTRICT_HEIGHT){
                $imageX = $imgInfo[0] * ($imageY / $imgInfo[1]);
                if( $imageX > 640 && ($fileName == "pic1.jpg" || $fileName == "pic2.jpg" || $fileName == "pic3.jpg") ){
                     $imageX = 640;
                }
            }
            // create the destination image resource
            if (function_exists('imagecreatetruecolor'))
                $imageDest = imagecreatetruecolor($imageX, $imageY);
            else
                $imageDest = imagecreate($imageX, $imageY);
            // read the source image and use the correct function depending on format
            if ($imgType == IMAGETYPE_JPEG) $imageType = imagecreatefromjpeg($imagePath.$imageName);
            elseif ($imgType == IMAGETYPE_PNG) $imageType = imagecreatefrompng($imagePath.$imageName);
            elseif ($imgType == IMAGETYPE_GIF) $imageType = imagecreatefromgif($imagePath.$imageName);

        

            // gd 2.0 is needed for clean resampling, but sloppy resizing is always available

            if (function_exists('imagecopyresampled'))

                imagecopyresampled($imageDest, $imageType, 0,0,0,0, $imageX, $imageY, $imgInfo[0], $imgInfo[1]);

            else

                imagecopyresized($imageDest, $imageType, 0,0,0,0, $imageX, $imageY, $imgInfo[0], $imgInfo[1]);

        

            // make the image path and image name, strip file extension and change to .jpg

            $imageFileName = $imagePath.substr($fileName, 0, strlen($fileName) - 4).'.jpg';

        

            // check to see if the file is being overwritten

            $imgUpdated = false;

            if (file_exists($imageFileName)) $imgUpdated = true;

        

            // save the image to the image path

            if (!imageJPEG($imageDest, $imageFileName, 80)) return false;

        

            // return in an array: the filename, if the file was overwritten

            return array(substr($fileName, 0, strlen($fileName) - 4).'.jpg', $imgUpdated);

        }

        /**
         * Helper function for file upload.
         * This function actually uploads the file and stores it to the server dir
         *
         * @access public
         */
        function do_file_upload($fieldname, $tid)
        {
           //file path
            $fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['treatment']['media_url']. $tid . '/';            
            if(!file_exists($fileDest))
            {
                mkdir($fileDest);
                chmod($fileDest, 0777);
            }
            $foo = new Upload($_FILES[$fieldname]); 
            $foo->file_overwrite = true;
            $newName = false;
            if ($foo->uploaded) 
            {
              // save uploaded image with no changes              
              $foo->Process($fileDest);
              if ($foo->processed) 
              {                  
                  $newName = $foo->file_dst_name;
              }
              else 
              {
                  $newName = false;
              }
            }
            else 
            {
                $newName = false;                
            }            
            if(!$newName)
            {
             return false;
            }
            $newFile = $fileDest . $newName;
            //thumbnail support
            if($fieldname != 'video')
            {
                $imgInfo = getimagesize($newFile);
                $imgType = $imgInfo[2];
                $imageX = $imgInfo[0];
                $imageY = $imgInfo[1];
            }
            // 640x480 or 360x480
            $restrictY = 80;
            $restrictX = 100;
            if($fieldname == 'pic1')
            {//Create thumbnails for pic1
                if($imageY > $imageX)
                { // Portrait
                    $aspect = RESTRICT_HEIGHT;
                    //          resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)
                    $imageDetails = $this->resize_image('thumb.jpg', $newName, $fileDest, 0, $restrictY, $aspect);
                }
                if($imageY < $imageX)
                { // Landscape
                    $aspect = RESTRICT_WIDTH;
                    $imageDetails = $this->resize_image('thumb.jpg',  $newName, $fileDest, $restrictX, 0, $aspect);
                }
            }
            //Create resized images for the rest of the pics
            if($fieldname != 'video')
            {
                if($imageY > $imageX)
                {// Portrait
                    $restrictY = 480;
                    $aspect = RESTRICT_HEIGHT;
                    //          resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)
                    $imageDetails = $this->resize_image($fieldname.'.jpg', $newName, $fileDest,360, $restrictY, $aspect);
                }
                if($imageY < $imageX)
                {// Landscape
                    $restrictX = 640;
                    $aspect = RESTRICT_WIDTH;
                    $imageDetails = $this->resize_image($fieldname.'.jpg',  $newName, $fileDest, $restrictX,480, $aspect);
                }
            }
            //end thumbnail support
            if($newName) return $newName;
            return false;
        }
        /**
         * Helper function for file upload.
         * This function verifies the image type (JPEG/PNG/GIF)
         *
         * @access public
         */
        function verify_image($imageName)
        {

            $imgInfo = getImageSize($imageName);

            $imgType = $imgInfo[2];

        

            if ($imgType == IMAGETYPE_JPEG) return true;

            elseif ($imgType == IMAGETYPE_PNG) return true;

            elseif ($imgType == IMAGETYPE_GIF) return true;
            return false;
        }
        /**
         * Helper function for file upload.
         * This function return the server dir address where the uploaded file will be stored
         *
         * @access public
         */
        function canonicalize($address)
        {
            $address = explode('/', $address);
            $keys = array_keys($address, '..');
            foreach($keys as $keypos => $key)
            {
                array_splice($address, $key - ($keypos * 2 + 1), 2);
            }
            $address = implode('/', $address);
            $address = str_replace('./', '', $address);
            return $address;
        }
        /**
        * @desc This function will show tags.
        */
        function showTag(){
            $replace['id'] = $this->value('id');
            $replace['reload'] = $this->reloadTag();
            $this->output =  $this->build_template($this->get_template('showTag'),$replace);
        }
        /**
        * @desc This function adds Tags in Tag table.
        * 
        */
         function insertTag($tag="",$id=""){
             
             if( $tag == ""){
                $tag = $this->value('tag');
             }
             if($id == ""){
                $id = $this->value('id');
             }
             $tag_array = explode(",",$tag);
             $user_id = 52;
             
             if( strlen($tag) > 0 && ( is_numeric($id) && $id > 0) ){
                if( is_array($tag_array) && count($tag_array) > 0 ){
                 
                 foreach( $tag_array as $value ){
                     $query = "insert into tag (tag_name,treatment_id,user_id) values";
                     $value = trim($value);
                     if($value == ''){
                         continue;
                     }
                     if( $this->duplicate_tag($value,$id) === true ){
                         continue;
                     }
                     
                     $query .= "('{$value}','{$id}','{$user_id}');";
                    if(@mysql_query($query)){
                        //echo "success";
                    }
                    else{
                        //echo "failed";
                    }
                 }
                 if( is_numeric($id) && $id > 0 ){
                     $query = "update treatment as a inner join (SELECT GROUP_CONCAT( `tag_name` SEPARATOR ' ' ) as c_tag, `treatment_id`
                                FROM `tag` GROUP BY `treatment_id` having treatment_id = '{$id}' ) as b on b.treatment_id = a.treatment_id 
                                set a.treatment_tag = concat(a.treatment_name,' ',b.c_tag) where a.treatment_id = '{$id}'";
                     @mysql_query($query);
                 }
                }    
             }
             else{
                    //echo "failed";
             }
             
         }
         /**
         * @desc validate tags.
         */
         function duplicate_tag($tag,$id){
             $query = "select count(*) as tag_count from tag where tag_name = '{$tag}' and treatment_id = '{$id}' ";
             $result = @mysql_query($query);
             $row = @mysql_fetch_array($result);
             if($row['tag_count'] > 0){
                 return true;
             }
             else{
                 return false;
             }
         }
         /**
         * @desc Returns Tag list.
         * 
         */
         function reloadTag(){
             $id = $this->value('id');
             $user_type = $this->userInfo('usertype_id');
             if( is_numeric($id) && $id > 0 && $user_type == '4' ){
                 $query = "select tag_id,tag_name from tag where treatment_id = '{$id}' order by tag_name ";
                 $result = @mysql_query($query);
                 $num = @mysql_num_rows($result);
                 
                 if( $num > 0 ){
                        //$height = $num <= 10?($num * 20):(11 * 20);
                        $overflow = $num <= 10?"":"height:200px;overflow-y:auto";
                        $this->output = "<div style='width:330px;margin:0px;' >
                                            <table border='0'>
                                            <tr>
                                                <td style='width:50px;' ><strong>#</strong></td>
                                                <td style='width:450px;' ><strong>Tag Name</strong></td>
                                                <td style='width:20px;' >&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                          </table>
                                          </div>
                                          <div style='width:330px;margin-left:0px;{$overflow}' >
                                          <table align='left' width='90%' border='0' >
                                          ";
                     $cnt = 1;
                     while( $row = @mysql_fetch_array($result) ){
                        $row['cnt'] = $cnt++;
                        $this->output .= $this->build_template($this->get_template('tagList'),$row); 
                     }
                     $this->output .= "</table></div>";
                     return $this->output;
                 }
             }
             
             return "";
             
         }
         /**
         * @desc Delete tag
         */
         function removeTag(){
             $id = $this->value('id');
             if( is_numeric($id) && $id > 0 ){
                $treatment_id = $this->get_field($id,'tag','treatment_id');
                $query = "DELETE FROM tag WHERE `tag_id` = '{$id}' LIMIT 1";
                @mysql_query($query);

                $query = "update treatment as a left join (SELECT GROUP_CONCAT( `tag_name` SEPARATOR ' ' ) as c_tag, `treatment_id`
                                FROM `tag` GROUP BY `treatment_id` having  treatment_id = '{$treatment_id}' ) as b on b.treatment_id = a.treatment_id 
                                set a.treatment_tag = CONCAT_WS(' ',a.treatment_name,b.c_tag)
                                where a.treatment_id = '{$treatment_id}' ";
                
                @mysql_query($query);
                 
             }  
         }
         /**
         * @desc Tag popup
         */
         function tagPopup(){
             $treatment_id = $this->value('id');
             $user_id = $this->userInfo('user_id');
             $replace['checked'] = '';
             if( is_numeric($treatment_id) && is_numeric($user_id) ){
                $query = "select count(*) as cnt from treatment_favorite where treatment_id = '{$treatment_id}' and user_id = '{$user_id}' ";
                $result = @mysql_query($query);
                if( $row = @mysql_fetch_array($result) ){
                    if($row['cnt'] > 0 ){
                        $replace['checked'] = 'checked';
                    }
                }
             }
             if($this->value('save') == 'save' ){
                 $favorite = $this->value('favorite');
                 $treatment_id = $this->value('id');
                 $tag = $this->value('tag');
                 if( strlen($tag) > 0 && is_numeric($treatment_id)){
                     $this->insertTag($tag,$treatment_id);
                 }
                 if( $this->userInfo('usertype_id') == 2 ){
                     if(is_numeric($favorite) && $favorite == '1' ){
                         $this->insertFavorite($treatment_id,$user_id);
                     }
                     else{
                         $this->deleteFavorite($treatment_id,$user_id);
                     }
                 }
             }
             $replace['id'] = $treatment_id;
             if( $this->userInfo('usertype_id') == 4 ){
                 $this->output = $this->build_template($this->get_template("tagPopupSystem"),$replace);
             }
             else{
                 $this->output = $this->build_template($this->get_template("tagPopup"),$replace);
             }
             
         }
         /**
         * @desc This function insert favorite treatment in treatment_favorite table.
         */
         function insertFavorite($treatment_id="",$user_id=""){
             if( is_numeric($treatment_id) && is_numeric($user_id) ){
                 $query = "select count(*) as cnt from treatment_favorite where treatment_id = '{$treatment_id}' and user_id = '{$user_id}' ";
                 $result = @mysql_query($query);
                 if($row = @mysql_fetch_array($result) ){
                     if( $row['cnt'] == 0 ){
                         $favorite_treatment_arr = array(
                                'treatment_id' => $treatment_id,
                                'user_id' => $user_id
                         );
                         $this->insert('treatment_favorite',$favorite_treatment_arr);
                     }
                 }
             }
             return "";
             
         }
         /**
         * @desc This function deletes favorite treatment from treatment_favorite table.
         */
         function deleteFavorite($treatment_id="",$user_id=""){
             if( is_numeric($treatment_id) && is_numeric($user_id) ){
                 $query = "delete from treatment_favorite where treatment_id = '{$treatment_id}' and user_id = '{$user_id}'  limit 1";
                 $result = @mysql_query($query);
             }
             return "";
             
         }
        /**
         * Function to populate side bar
         *
         * @return side bar menu template
          * @access public            
         */    
        function sidebar(){

            

            $userInfo = $this->userInfo();            

            

            if ($userInfo['usertype_id'] == 4) 

            {            

                $data = array(

                    'name_first' => $this->userInfo('name_first'),

                    'name_last' =>  $this->userInfo('name_last')

                );

                

                return $this->build_template($this->get_template("sidebar"),$data);

                

            }

            else if ($userInfo['usertype_id'] == 2) {

                
				//code for checking the trial period days left for Provider/AA
				$freetrialstr=$this->getFreeTrialDaysLeft($this->userInfo('user_id'));
                $data = array(

                    'name_first' => $this->userInfo('name_first'),

                    'name_last' =>  $this->userInfo('name_last'),

                    'sysadmin_link' => $this->sysadmin_link(),

                    'therapist_link' => $this->therapist_link(),

					'freetrial_link' => $freetrialstr

                );



            return $this->build_template($this->get_template("sidebarThpst"),$data);

            }

            

        }
        /**
         * This is used to get the name of the template file from the config_txxchange.xml
         * for the action request
         *
         * @param String $template
         * @return template page info
         * @access public
         */    
        function get_template($template){
            $login_arr = $this->action_parser($this->action,'template') ;
            $pos =  array_search($template, $login_arr['template']['name']); 
            return $login_arr['template']['path'][$pos];
        }
        function display(){
            view::$output =  $this->output;
        }
    }
    // creating object of this class
    $obj = new treatmentUpload();

?>

