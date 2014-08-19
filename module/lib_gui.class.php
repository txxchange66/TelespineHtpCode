<?php

    /**
     * 
     * Copyright (c) 2011 Tx Xchange.
     * 
     * This class library is created for Personalized GUI and check Features display.
     * 
     * // necessary classes 
     * require_once("module/application.class.php");
     * 
     */
    
    // HTML Required: 
    // IMAGES Required: Create Health Video Series,Create New Exercise Plan,Assign Health Video Series,Assign New Exercise Plan
    
    // including files
    require_once("module/application.class.php");
    

    // class declaration starts    
    class lib_gui extends application {
 
       // class variable declaration section
      
           /**
           * The variable defines the action request
           *
           * @var string
           * @access private
           */
        public $providerTypeId;
           /**
           * The variable defines the action request
           *
           * @var string
           * @access private
           */          
        public $clinicTypeId;
           /**
           * The variable defines the action request
           *
           * @var string
           * @access private
           */          
        public $clinicId;
           /**
           * The variable defines the action request
           *
           * @var string
           * @access private
           */          
        public $providerId;
           /**
           * The variable defines the action request
           *
           * @var string
           * @access private
           */          
        public $userId;
        
        // Constructor         
        public function __construct($userIdInfo){
                //Tables to be used. user , clinic_user,clinic_type,clinic               
               //$userIdInfo=$this->userInfo();

               // User Information
               $this->userId=$userIdInfo['user_id'];
               
               // Provider Type Of Provider / AA
               $this->providerTypeId=$userIdInfo['practitioner_type'];
               
               // Account Type 
               $clinicId=$this->clinicInfo("clinic_id",$this->userId);
               $clinicDetails=$this->getClinicDetails($this->userId);
               $this->clinicTypeId=$clinicDetails['clinic_type'];
               
        }
         /**
         * This function returns the label  on the basis of provider type
         * @param String $labelValue
         * @return String
         * @access public
         */
        public function getLabelValueProviders($labelValue='') {
               //Tables to be used. labels,labels_providertype_value,practitioner
               //$this->providerTypeId;
               
               if($labelValue!='')
                 $conditionValue=" AND labels.label_name='{$labelValue}' ";
               
               // Query To Get the desired Lable value based on Provider Type
               $SQLquery="select labels_providertype_value.label_value AS NEW_LABEL,labels.label_name as OLD_LABEL,practitioner.name AS PROVIDER_TYPE from labels LEFT JOIN labels_providertype_value ON labels.label_id=labels_providertype_value.label_id  RIGHT JOIN practitioner ON practitioner.practitioner_id=labels_providertype_value.practitioner_id  where labels_providertype_value.practitioner_id = '{$this->providerTypeId}' $conditionValue ";
                $resultQuery=$this->execute_query($SQLquery);
                $resultArray=array();
                while($result=$this->fetch_array($resultQuery)){
                    $oldLabel=$result['OLD_LABEL'];
                    $newLabel=$result['NEW_LABEL'];
                    
                    if($newLabel!=''){
                        // Value is returned
                        $resultArray[$oldLabel]=$newLabel;                       
                    }else {
                        // Unfortunately No value found. Degrade gracefully
                         $resultArray[$oldLabel]=$oldLabel;  
                    }

                      
                }
                
                
                
                return $resultArray; 
        }

         /**
         * This function returns the label on the basis of clinic type
         * @param String $labelValue
         * @return String
         * @access public
         */
        public function getLabelValueClinic($labelValue='') {
               //Tables to be used.   labels,labels_clinictype_value,clinic_type
               //$this->clinicTypeId;
               
               if($labelValue!=''){
                    $conditionlabel=" AND labels.label_name= '{$labelValue}'";
               }
               
               // Query To Get the desired Lable value based on Account Type
               $SQLquery="select labels_clinictype_value.label_value AS NEW_LABEL,labels.label_name as OLD_LABEL from labels , labels_clinictype_value , clinic_type where clinic_type.clinic_type_id = labels_clinictype_value.clinic_type_id 
AND labels.label_id=labels_clinictype_value.label_id AND labels_clinictype_value.clinic_type_id = '{$this->clinicTypeId}' $conditionlabel ";
                $resultQuery=$this->execute_query($SQLquery);
                $resultArray=array();
                while($result=$this->fetch_array($resultQuery)){
                    $oldLabel=$result['OLD_LABEL'];
                    $newLabel=$result['NEW_LABEL'];
                    
                    if($newLabel!=''){
                        // Value is returned
                        $resultArray[$oldLabel]=$newLabel;                       
                    }else {
                        // Unfortunately No value found. Degrade gracefully
                         $resultArray[$oldLabel]=$oldLabel;  
                    }

                      
                }                

                return $resultArray;
        }
        
         /**
         * This function returns the Field display true/False on the basis of provider type
         * @param String $fieldName
         * @return String
         * @access public
         */
        public function getFieldProvider($fieldId='') {
               //Tables to be used.      display_fields,display_fields_provider,practitioner
               //$this->providerTypeId;
               
               if($fieldId!=''){
                   $condition=" AND display_fields.display_field_id='{$fieldId}' ";
               }
               
               // Query To Find If the Field is to be displayed OR NOT Based on Provider Type
               $SQLquery="select field_name as FIELD_NAME , practitioner.practitioner_id as PRVD_ID,display_fields_provider.display_status  AS DISP_STATUS from display_fields , display_fields_provider , practitioner where display_fields.display_field_id=display_fields_provider.display_field_id AND display_fields_provider.practitioner_id = practitioner.practitioner_id AND display_fields_provider.practitioner_id='{$this->providerTypeId}' $condition ";
                $resultQuery=$this->execute_query($SQLquery);

                $resultArray=array();
                while($result=$this->fetch_array($resultQuery)){
                    $dispStatus=$result['DISP_STATUS'];
                    $fieldName=$result['FIELD_NAME'];
                    if($dispStatus!=''){
                        // Value is returned
                        $resultArray[$fieldName]=$dispStatus;                       
                    }else {
                        // Unfortunately No value found. Degrade gracefully
                         $resultArray[$fieldName]='1';  
                    }

                      
                }
                
                
                
                return $resultArray;  
                
                
                
                             

        }

         /**
         * This function returns the Field display true/False on the basis of provider type
         * @param String $fieldName
         * @return String
         * @access public
         */
        public function getFieldClinic($fieldId) {
               //Tables to be used.     display_fields,display_fields_clinic,clinic_type
               //$this->clinicTypeId;   
               
               // Query To Find If the Field is to be displayed OR NOT Based on Clinic Type
               $SQLquery="select field_name as FIELD_NAME from display_fields , display_fields_clinic , clinic_type where display_fields.display_field_id =display_fields_clinic.display_field_id AND display_fields_clinic.clinic_type_id = clinic_type.clinic_type_id AND display_fields.display_field_id='{$fieldId}'";
                $resultQuery=$this->execute_query($SQLquery);
                $result=$this->fetch_array($resultQuery);
                
                if($result['FIELD_NAME']!=''){
                    // Field Supported
                    $returnValue='true';
                }else {
                    // Field Not Supported
                     $returnValue='false';  
                }
                
                return $returnValue;                          
        }
        
        
         /**
         * This function returns status of the feature supported by Clinic Type
         * @param String $featureName
         * @return Boolean
         * @access public
         */
        public function getFeatureClinicType($featureId='') {
               //Tables to be used.    feature_category,features,assignment_clinic,clinic_type
               //$this->clinicTypeId;  
               
               if($featureId!='')
                    $conditionQuery=" AND features.features_id='{$featureId}' ";
               
                // Query To Find If the Feature is to allowed OR NOT Based on Clinic Type
                $SQLquery="select features_title as FEATURE_NAME,feature_status from feature_category,features,assignment_clinic,clinic_type where feature_category.feature_cat_id=features.feature_cat_id AND features.features_id=assignment_clinic.features_id AND assignment_clinic.clinic_type_id=clinic_type.clinic_type_id and clinic_type.clinic_type_id = '{$this->clinicTypeId}' AND feature_category.feature_cat_id='2' $conditionQuery ";
                $resultQuery=$this->execute_query($SQLquery);
                $returnValueArray=array();                
                while($result=$this->fetch_array($resultQuery)){
                      if($result['feature_status']=='1'){
                        // Feature Supported
                        $FeatureName=$result['FEATURE_NAME'];
                        $returnValueArray[$FeatureName]=$result['feature_status'];
                    }else {
                        // Feature Not Supported
                        $returnValueArray[$FeatureName]='0';
                     }
                }
                
                
                return $returnValueArray;                               
        }

         /**
         * This function returns status of the feature supported by Provider Type
         * @param String $featureName
         * @return Boolean
         * @access public
         */
        public function getFeatureProviderType($featureId) {
                //Tables to be used.   features,assignment_provider,practitioner
               //$this->providerTypeId;
               
               if($featureId!='')
                    $conditionQuery=" AND features.features_id='{$featureId}' ";               
               
                // Query To Find If the Feature is to allowed OR NOT Based on Provider Type
                $SQLquery="select features_title as FEATURE_NAME,feature_status from feature_category,features,assignment_provider,practitioner where feature_category.feature_cat_id=features.feature_cat_id  AND features.features_id=assignment_provider.features_id AND assignment_provider.practitioner_id=practitioner.practitioner_id and practitioner.practitioner_id = '{$this->providerTypeId}'  AND feature_category.feature_cat_id='1' $conditionQuery ";
                $resultQuery=$this->execute_query($SQLquery);

                $returnValueArray=array();                
                while($result=$this->fetch_array($resultQuery)){
                      if($result['feature_status']=='1'){
                        // Feature Supported
                        $FeatureName=$result['FEATURE_NAME'];
                        $returnValueArray[$FeatureName]=$result['feature_status'];
                    }else {
                        // Feature Not Supported
                        $returnValueArray[$FeatureName]='0';
                     }
                }                
                
                return $returnValueArray;                 
        }
  }  
/**
* @Queries Used
* 
* select * from labels , labels_clinictype_value , clinic_type where clinic_type.clinic_type_id = labels_clinictype_value.clinic_type_id 
AND labels.label_id=labels_clinictype_value.label_id AND labels.label_name= 'abc';

select labels_providertype_value.label_value AS NEW_LABEL from labels,labels_providertype_value,practitioner where labels.label_id=labels_providertype_value.label_id AND
 practitioner.practitioner_id=labels_providertype_value.practitioner_id AND labels.label_name='abc';

select * from display_fields , display_fields_clinic , clinic_type where display_fields.display_field_id =display_fields_clinic.display_field_id AND display_fields_clinic.clinic_type_id = clinic_type.clinic_type_id AND display_fields.display_field_id=2;

select * from display_fields , display_fields_provider , practitioner where display_fields.display_field_id=display_fields_provider.display_field_id AND display_fields_provider.practitioner_id = practitioner.practitioner_id  AND display_fields.display_field_id=2;

select * from feature_category,features,assignment_clinic,clinic_type where feature_category.feature_cat_id=features.feature_cat_id 
AND features.features_id=assignment_clinic.features_id AND assignment_clinic.clinic_type_id=clinic_type.clinic_type_id AND features.features_id=2;

select * from feature_category,features,assignment_provider,practitioner where feature_category.feature_cat_id=features.feature_cat_id  AND features.features_id=
assignment_provider.features_id AND assignment_provider.practitioner_id=practitioner.practitioner_id AND features.features_id=2;
* 
* 
*   
*/
  
?>