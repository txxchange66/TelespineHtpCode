<?php

/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 *
 * // necessary classes
 * require_once("module/application.class.php");
 *
 */
require_once("module/application.class.php");

class patientarticles extends application
{
    // class variable declaration section

    /**
     * Get action from header and calling function
     * The variable defines the action request
     *
     * @var string
     * @access private
     */
    private $action;

    /**
     * Populate html form elements as an array
     * The variable defines all the fields present in the form
     *
     * @var array
     * @access private
     */
    private $form_array;

    /**
     * The variable defines the error message(if any) against the action request
     * It could be an array if more than one error messages are there else a simple variable
     *
     * @var string
     * @access private
     */
    private $error;

    /**
     * Pass as parameter in display() function which shows output in browser.
     *
     * The variable is used for getting final output template or string message to be displayed to the user
     * This function of statement(s) are to handle all the actions supported by this Login class
     * that is it could be the case that more then one action are handled by login
     * for example at first the action is "login" then after submit say action is submit
     * so if login is explicitly called we have the login action set (which is also our default action)
     * else whatever action is it is set in $str.
     *
     * @var string
     * @access private
     */
    private $output;

    /**
     *  constructor
     *  set action variable from url string, if not found action in url, call default action from config.php
     *
     */
    function __construct()
    {
        parent::__construct();
        if($this->value('action'))
        {
            /*
              This block of statement(s) are to handle all the actions supported by this Login class
              that is it could be the case that more then one action are handled by login
              for example at first the action is "login" then after submit say action is submit
              so if login is explicitly called we have the login action set (which is also our default action)
              else whatever action is it is set in $str.
             */
            $str = $this->value('action');
        }
        else
        {
            $str = "patientarticles"; //default if no action is specified
        }
        $this->action = $str;
        if($this->get_checkLogin($this->action) == "true")
        {

            if(isset($_SESSION['username']) && isset($_SESSION['password']))
            {

                if(!$this->chk_login($_SESSION['username'], $_SESSION['password']))
                {

                    header("location:index.php?action=patientlogin");
                }
            }
            else
            {

                header("location:index.php");
            }
        }

        if($this->userAccess($str))
        {
            // Code To Call Personalized GUI
            $this->call_patient_gui();
            //$this->call_gui;
            $str = $str . "()";
            eval("\$this->$str;");
        }
        else
        {
            $this->output = $this->config['error_message'];
        }

        $this->display();
        /*
          $str = $str."()";
          eval("\$this->$str;");
          $this->display();
         */
    }

    /**
     * Renders the patient videos page
     */
    function patientarticles()
    {
		$replace['currentDate']= date("l, F d, Y");
		$replace['startdate'] = $this->formatDate($userInfo['creation_date'], 'Y-m-d');
		$replace['currentday'] = $this->getPaitentCurrentDay($this->userInfo('user_id'));
		$currentday=$replace['currentday'];
		$curr_week=floor(($replace['currentday']-1)/7)+1;
		$replace['currentweek'] =$curr_week;
		
		$replace['profile_picture'] = 'assets/img/avatar.jpg';
		$pimage = $this->userInfo('profile_picture');
		if(isset($pimage) && !empty($pimage)){
		$replace['profile_picture'] = 'asset/images/profilepictures/'.$this->userInfo('user_id').'/'.$this->userInfo('profile_picture');
		}
		
		
		
		
		$replace['theme'] ="default";
		 $sql="SELECT * FROM patient_application_preferences WHERE user_id='".$this->userInfo('user_id')."'";
		 $rs = $this->execute_query($sql);
		$num_rows = $this->num_rows($rs);
		
		if($num_rows > 0){
		$row = $this->populate_array($rs);
		$replace['theme'] = $row['theme'];
		
		}
		
		
		$allarticle = $this->getAllDayPaitentArticle($this->userInfo('user_id'));
		
		
		/*
		*  Insert Data for  page_visits_report
		*/
			$ip='';
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
			$ip = $_SERVER['REMOTE_ADDR'];
			}
		
		$user_id=$this->userInfo('user_id');
		$clinic_id = $this->get_clinic_info($user_id);
		  
		$report_query ="INSERT INTO page_visits_report(page,ip,user_id,clinic_id,created) VALUES('article','".$ip."','".$user_id."','".$clinic_id."',now())";
	
		$result1 = @mysql_query($report_query);
		
		/*
		*  End 
		*/
		
		
		
		
		
		
	
		$left_menu_week1='<div  class="menu-week-item" id="week-1" style="display:none">';
		$left_menu_week2='<div  class="menu-week-item" id="week-2" style="display:none">';
		$left_menu_week3='<div  class="menu-week-item" id="week-3" style="display:none">';
		$left_menu_week4='<div  class="menu-week-item" id="week-4" style="display:none">';
		$left_menu_week5='<div  class="menu-week-item" id="week-5" style="display:none">';
		$left_menu_week6='<div class="menu-week-item" id="week-6" style="display:none">';
		$left_menu_week7='<div class="menu-week-item" id="week-7" style="display:none">';
		$left_menu_week8='<div class="menu-week-item" id="week-8" style="display:none">';
		$week1=0;
		$week2=0;
		$week3=0;
		$week4=0;
		$week5=0;
		$week6=0;
		$week7=0;
		$week8=0;
		
		if($curr_week==1){
		
		$left_menu_week1='<div  class="menu-week-item" id="week-1" style="display:block">';
		}
		
		if($curr_week==2){
		
		$left_menu_week2='<div  class="menu-week-item" id="week-2" style="display:block">';
		}
		
		if($curr_week==3){
		
		$left_menu_week3='<div class="menu-week-item" id="week-3" style="display:block">';
		}
		
		if($curr_week==4){
		
		$left_menu_week4='<div  class="menu-week-item" id="week-4" style="display:block">';
		}
		
		if($curr_week==5){
		
		$left_menu_week5='<div class="menu-week-item" id="week-5" style="display:block">';
		}
		if($curr_week==6){
		
		$left_menu_week6='<div class="menu-week-item" id="week-6" style="display:block">';
		}
		if($curr_week==8){
		
		$left_menu_week8='<div  class="menu-week-item" id="week-8" style="display:block">';
		}
		
		$right_section="";
		
		$cc=0;
		
		
		$right_section="";
		
		$cc=0;
		$previoues_day = 0;
		$start_new = 1;
		
		$left_menu_day = array();
		
		$last_day=0;
		
		for($i=0;$i<count($allarticle);$i++){
		
		$row = $allarticle[$i];
		$url = $this->config['telespine_login'].$this->config['tx']['article']['media_url']. $row['path'] ;
		
		//echo $row['week1'];
		
		
		if($row['plan_id'] >0){		
		$plan_id = $row['plan_id'];
		}else{
		$plan_id =0;
		
		}
		
		$article_id = $row['articleID'];
		
		if($currentday >=$row['assignday']){
		
		// Creating left menu 

		$last_day =$row['assignday'];
		switch ($row['week1'])
			{
			case '1':		
			
			 	//$left_menu_week1 .='<li> <span class="todo-actions">';
				//$left_menu_week1 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;				 
				//$left_menu_week1 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
				$week1=1;
				
			  break;
			case '2':
				//$left_menu_week2 .='<li> <span class="todo-actions">';
				//$left_menu_week2 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;				 
				//$left_menu_week2 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
				$week2=1;
			 
			  break;
			case '3':
					//$left_menu_week3 .='<li> <span class="todo-actions">';
					//$left_menu_week3 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
				//$left_menu_week3 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
			  $week3=1;
			  
			  break;
			case '4':
					//$left_menu_week4 .='<li> <span class="todo-actions">';
					//$left_menu_week4 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
				//$left_menu_week4 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
			  $week4=1;
			  
			  break;
			case '5':
			 
				//$left_menu_week5 .='<li> <span class="todo-actions">';
				//$left_menu_week5 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
				//$left_menu_week5 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
			 
			 $week5=1;
			 
			  break;
			case '6':
			
			
				//$left_menu_week6 .='<li> <span class="todo-actions">';
				//$left_menu_week6 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
				//$left_menu_week6 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
			  $week6=1;
			  break;
			case '7':
			 
				//$left_menu_week7 .='<li> <span class="todo-actions">';
				//$left_menu_week7 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
				//$left_menu_week7 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
			 
			 $week7=1;
			  break;
			case '8':
				//$left_menu_week8 .='<li> <span class="todo-actions">';
				//$left_menu_week8 .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
				//$left_menu_week8 .=' <i class="fa  fa-play-circle"></i> </span><span class="desc">Day: '.$row['assignday'].'- Read Article '.$row['article_name'].'</span></a> </li>';
				$week8=1;
			  break;
			
			}
			// Creating right section content 
			
			
			
			
			
			if(($previoues_day != $row['assignday']) && $previoues_day!=0){
				$left_menu_day[$previoues_day] .='</div>';
				$start_new =1;
			}
			if($start_new == 1)
			{
				$a =$row['assignday'];
				$left_menu_day[$row['assignday']] .='<h4 class="menu-day" onClick="$(\'#day-'.$a.'\').slideToggle();App.displaydayarticle('.$a.')">Day '.$a.'</h4>';
			
				if($currentday!=$row['assignday'])
				{
				
					$left_menu_day[$row['assignday']] .='<div class="menu-day-item" id="day-'.$a.'" style="display:none">';
					
				}
				else
				{
					$left_menu_day[$row['assignday']] .='<div class="menu-day-item" id="day-'.$a.'" style="display:block">';
				}
					
			}
			$start_new =0;	
			$left_menu_day[$row['assignday']] .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			 
			$left_menu_day[$row['assignday']] .=' <i class="fa fa-external-link"></i><span class="desc">Read Article '.$row['article_name'].'</span></a>';
			
			//if($curr_week==$row['week1']){
			
			
			if($currentday==$row['assignday']){
			
			$right_section .='<li> <span class="todo-actions"> <i class="fa fa-external-link"></i> </span><span class="desc"><strong>';
			$right_section .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			
			$right_section .='Day: '.$row['assignday'].' - '.$row['article_name'].'  PDF</a></strong></span></li><div class="clearfix"></div>';
			
			
			$cc++;
			
			}
			
			$previoues_day = $row['assignday'];
		
		}
		
		}
	if($last_day!=0){
	
		$left_menu_day[$last_day] .='</div>';
	}
	for($j=1;$j<=56;$j++){
	
		if($j<=7)
		{
			$left_menu_week1 .= $left_menu_day[$j];
		}
		elseif($j>7 && $j<=14)
		{
			$left_menu_week2 .= $left_menu_day[$j];
		}
		
		elseif($j>14 && $j<=21)
		{
			$left_menu_week3 .= $left_menu_day[$j];
		}
		
		elseif($j>21 && $j<=28)
		{
			$left_menu_week4 .= $left_menu_day[$j];
		}
		elseif($j>28 && $j<=35)
		{
			$left_menu_week5 .= $left_menu_day[$j];
		}
		elseif($j>35 && $j<=42)
		{
			$left_menu_week6 .= $left_menu_day[$j];
		}
		elseif($j>42 && $j<=49)
		{
			$left_menu_week7 .= $left_menu_day[$j];
		}
		elseif($j>49 && $j<=56)
		{
			$left_menu_week8 .= $left_menu_day[$j];
		}
		
		
	
	}
		
		if($week1==0){
		
		if($curr_week>=1){
		
		$left_menu_week1 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week1 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week2==0){
		
		if($curr_week>=2){
		
		$left_menu_week2 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week2 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week3==0){
		
		if($curr_week>=3){
		
		$left_menu_week3 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week3 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week4==0){
		
		if($curr_week>=4){
		
		$left_menu_week4 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week4 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week5==0){
		
		if($curr_week>=5){
		
		$left_menu_week5 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week5 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week6==0){
		
		if($curr_week>=6){
		
		$left_menu_week6 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week6 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week7==0){
		
		if($curr_week>=7){
		
		$left_menu_week7 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week7 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		if($week8==0){
		
		if($curr_week>=8){
		
		$left_menu_week8 .='<div> <span class="todo-actions"> </span><span class="desc">No articles are available.</span></div>';
		}else{
		
		$left_menu_week8 .='<div> <span class="todo-actions"> </span><span class="desc">Future articles will be displayed here.</span></div>';
		}
		
		}
		
		
		
		
		 $left_menu_week1 .='</div>';
		 $left_menu_week2 .='</div>';
		 $left_menu_week3 .='</div>';
		 $left_menu_week4 .='</div>';
		 $left_menu_week5 .='</div>';
		 $left_menu_week6 .='</div>';
		 $left_menu_week7 .='</div>';
		 $left_menu_week8 .='</div>';
		
		$replace['left_menu_week1'] = $left_menu_week1;
		$replace['left_menu_week2'] = $left_menu_week2;
		$replace['left_menu_week3'] = $left_menu_week3;
		$replace['left_menu_week4'] = $left_menu_week4;
		$replace['left_menu_week5'] = $left_menu_week5;
		$replace['left_menu_week6'] = $left_menu_week6;
		$replace['left_menu_week7'] = $left_menu_week7;
		$replace['left_menu_week8'] = $left_menu_week8;
		$replace['right_section']=$right_section;
        
        $replace['meta_head'] = $this->build_template($this->get_template("meta_head"), $replace);
        $replace['header'] = $this->build_template($this->get_template("patient_dashboard_header"), $replace);
        $replace['footer'] = $this->build_template($this->get_template("patient_dashboard_footer"));

        $this->output = $this->build_template($this->get_template("patient_articles"), $replace);
    }

	
	function getdaywisearticlerightsection(){
	
	
	
		$userInfo = $this->userInfo();
		$day =$_REQUEST['day'];
		$allarticle = $this->getPaitentArticleByDay($userInfo['user_id'],$day);
		
		
		
		for($i=0;$i<count($allarticle);$i++){
		
		$row = $allarticle[$i];
		$url = $this->config['telespine_login'].$this->config['tx']['article']['media_url']. $row['path'] ;
		
		//echo $row['week1'];
		
		
		if($row['plan_id'] >0){		
		$plan_id = $row['plan_id'];
		}else{
		$plan_id =0;
		
		}
		
		$article_id = $row['articleID'];
			
		
		
			$right_section .='<li> <span class="todo-actions"> <i class="fa fa-external-link"></i> </span><span class="desc"><strong>';
			$right_section .='<a href="#" title="'.$row['article_name'].'"  onClick="App.openArticleOverlay(this.title, $(this).attr(\'data-url\'), $(this).attr(\'data-id\'), $(this).attr(\'data-planid\'),\'articlepage\')" data-url="'.$url.'" data-id="'.$article_id .'" data-planid="'.$plan_id .'" >' ;			
			$right_section .='Day: '.$row['assignday'].' - '.$row['article_name'].'  PDF</a></strong></span></li><div class="clearfix"></div>';
			
			
		
		
		}
		
		echo $right_section;
	
	}	
	
	
	
	
	
	
	
	
    /**
     * This function gets the template path from xml file.
     *
     * @param string $template - pass template file name as defined in xml file for that template file.
     * @return string - template file
     * @access private
     */
    function get_template($template)
    {
        $login_arr = $this->action_parser($this->action, 'template');
        $pos = array_search($template, $login_arr['template']['name']);
        return $login_arr['template']['path'][$pos];
    }

    /**
     * This function sends the output to browser.
     *
     * @access public
     */
    function display()
    {
        view::$output = $this->output;
    }

    /**
     * This function to assign persoanlized GUI for Patient based on Account Type into Session Variable
     * @access public
     * @return void
     */
    public function call_patient_gui()
    {
        //Check to load the Session Once
        //print_r($_SESSION['patientLabel']);
        if(count($_SESSION['patientLabel']) > 0)
        {
            
        }
        else
        {
            $userInfoValue = $this->userInfo();
            $persoanlizedPatientGUI = new lib_gui($userInfoValue);
            // Label v/s Provider Types
            $AccountTypeLabelsArray = $persoanlizedPatientGUI->getLabelValueClinic();
            // print_r($AccountTypeLabelsArray);
            foreach($AccountTypeLabelsArray as $key => $value)
            {
                $_SESSION['patientLabel'][$key] = $value;
            }
            $clinicfeaturelist = $persoanlizedPatientGUI->getFeatureClinicType();
            foreach($clinicfeaturelist as $key => $value)
            {
                $_SESSION['clinicfeature'][$key] = $value;
            }
        }
        //print_r( $_SESSION);
    }
	
	
	function markreadarticle(){
	
	
		 $aid = $_REQUEST['aid'];
		$plan_id = $_REQUEST['plan_id'];
		$userInfo = $this->userInfo();
		$from =$_REQUEST['from'];
		
		  if($plan_id==0){
		   $sql="select * from patient_article where article_id= '{$aid}' and patient_id = '{$this->userInfo('user_id')}' ";
		   $res=$this->execute_query($sql);
		   //echo $this->num_rows($res);
		   if($this->num_rows($res)>0)
		   {
			$data = array(
			  'read_article' => 1
			);
			$this->update("patient_article",$data, " article_id = '{$aid}' and patient_id = '{$this->userInfo('user_id')}' ");
			 
		   }
		  }
		  
		  else{
		   $data = array(
			 'read_article' => 1
		   );
		   $this->update("plan_article",$data, " article_id = '{$aid}'  and plan_id   = '{$plan_id}'");
		  }
		  
		  $user_id=$userInfo['user_id'];
		  $clinic_id = $this->get_clinic_info($user_id);
		  $report_query ="INSERT INTO article_report(plan_id,article_id,user_id,viewed_from,clinic_id,created_date) VALUES('".$plan_id."','".$aid."','".$user_id."','".$from."','".$clinic_id."',now())";
	
		$result1 = @mysql_query($report_query);
		  
		  
		  
		  echo 'done';
		  
		  
		  
		  
		  
	
	
	
	}
	

}

$obj = new patientarticles();
?>
