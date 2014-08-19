<?php
     require_once("module/application.class.php");   
             
/*
  Class name : ConfigData
  Desc : this class is used to keep functions to make a interface to read the data present in config file.
  Date : 07/06/2011.
*/     

class ConfigData extends application
{  

/*
  Desc  : Constructor(for future enhancements).
  Date : 07/06/2011.
*/     

function __construct(){ }


/*
  @Function name : readConfig
  @Desc : this function is used to make a interface to read the data present in config file.
  @Param : void.
  @Return : void.
  @Access : Public.
  @Date : 07/06/2011.
  
*/     



    public function readConfig()
        {           

            if($_SERVER['REMOTE_ADDR']=='210.7.64.98'){ 
           
           echo"IP CONFIG DATA";    
            echo"<pre>";
            include('./config.php');

            $a=array_merge_recursive($txxchange_config);   
            //print_r($a);              
            foreach($a as $key=>$value)
            {
                
              //print_r($key);echo"</br>";echo"</br>";
            //   print_r($value);                          
              if($key=='tx' || $key=='module')
              {     
                echo"&nbsp;";echo "<b>"; echo"$key";   echo"</b>";
                foreach($value as $a=>$b){
                
                echo"</br>";echo"&nbsp;";echo"&nbsp;";print_r($a);echo":";
                foreach($b as $c=>$d){echo"&nbsp;";print_r($d);echo",";}
                } ;echo"<br>";
              } 
              elseif($key=='from_email_address' || $key=='release_version' || $key=='url' || $key=='images_url' || $key=='freetrial' || $key=='application_path' || $key=='private_key')
              {                           
                                           
                 echo"&nbsp;";  echo "<b>";print_r($key);  echo "</b>";echo"&nbsp;";echo":";print_r($value); echo"<br>";echo"<br>";   
              
              }

                                        
            else
              {      echo"</br>";echo"&nbsp;";echo "<b>";print_r($key);echo"</b>";echo"<br>";  
                   foreach($value as $t=>$v)
                   {
                      
                  echo"&nbsp;"; echo"&nbsp;";print_r($t);echo"&nbsp;";echo":";print_r($v); echo"<br>";    
                   }
              
              
              
              
              }
                 
               
              
            }
        }
        else{
        
            echo"Sorry!! You do not have permission to access this page." ;
        
        }
        
        
        
        }
}


$obj = new ConfigData(); 
$obj->readConfig();


   
?>
