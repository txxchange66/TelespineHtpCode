<?php
/*

*********************
*  Bar Chart Class  *
*    Version 1.0    *
* February 09, 2011 *
*********************

This is the 3rd class I've ever made. 
You can find me at: moarte2[at]yahoo[dot]com

====================================================================================
The class generates a horizontal bar chart using html table and divs
Tested with Internet Explorer 8 and Firefox 3.6
====================================================================================

This is how it works:

chart_settings($title, $title_style, $info, $info_style, $number_of_gridlines)
== use this function to customize your chart (optional)

set_label_style($style)	
== use this function to customize the labels style (vertical axis labels) (optional)

set_bar($bars, $space_b_bars, $height, $units)
== use this function to set the bars names and colors, height, units, space between bars... 
The class will NOT work if you don't use this function. 

graph($data, $reverse, $show_legend, $show_percentage, $show_h_grid, $show_scale, $max_width, $scale)
== this generates the chart
these parameters can be TRUE or FALSE:
$reverse, $show_legend, $show_percentage, $show_h_grid, $show_scale

output($style)
== this outputs the chart 
$style = the style of the div where the chart will be included



=========
example1=
=========

$grafic 		= new bar_chart();

$bars			= array("Google Chrome"	=> "#2BAA33");
$height			= "40px"; 	//the height of the bars. must include "px"
$units			= "&#37;";	//can be: kg, miles, km, etc. in this example this is the code for %  
$space_b_bars 	= ""; //can be 2px, ... 5px, 6px, 7px etc. the default value 1px
					
$grafic -> set_bar($bars,  $space_b_bars, $height, $units);


$title 			= "Google Chrome - Market Share"; //the title of the chart. will be displayed at the top
$title_style	= ""; // I use the default value for style title
$info 			= "*source:<br/> W3 Schools, Click <a href ='http://www.w3schools.com/browsers/browsers_stats.asp' style = 'color: #069' target='_blank' >here</a> to visit the page."; //chart info
$info_style		= ""; // I use the default value for style title
$number_of_gridlines = 10; // the scale divided in 10 segments


$grafic -> chart_settings($title, $title_style, $info, $info_style, $number_of_gridlines);

$data = array(	"January 2011"	=> array("Google Chrome"	=> "23.80"),
				"December 2010" => array("Google Chrome"	=> "22.40"),
				"November 2010" => array("Google Chrome"	=> "20.50"),
				"October 2010"  => array("Google Chrome"	=> "19.20"),
				"September 2010"=> array("Google Chrome"	=> "17.30"),
				"August 2010"	=> array("Google Chrome"	=> "17.00"),			
				"July 2010"		=> array("Google Chrome"	=> "16.70"),			
				"June 2010"		=> array("Google Chrome"	=> "15.90"),			
				"May 2010"		=> array("Google Chrome"	=> "14.50"),			
				"April 2010"	=> array("Google Chrome"	=> "13.60"),			
				"March 2010"	=> array("Google Chrome"	=> "12.30"),			
				"February 2010"	=> array("Google Chrome"	=> "11.60"),			
				"January 2010"	=> array("Google Chrome"	=> "10.80"));
				

$reverse		= FALSE;	//show the data in reverse order
$show_legend	= False;	//show the legend for this chart
$show_percentage= FALSE;	//show percentage for every item
$show_h_grid	= FALSE;	//show horizontal grid
$show_scale		= TRUE;		//show scale at the bottom of the chart
$max_width		= 1200; 	//the width in pixels of the right side of the chart for the value of $scale 
$scale			= 100;		//maximum value on the scale

$grafic -> graph($data, $reverse, $show_legend, $show_percentage, $show_h_grid, $show_scale, $max_width, $scale);
$style = array("style" => "border: 5px solid #CA0533;");
echo $grafic -> output($style);


=========
example2=
=========

$grafic = new bar_chart();

$bars	= array(	"Firefox"			=> "#FF4200" ,
					"Internet Explorer"	=> "blue" ,
					"Opera"				=> "red",
					"Safari"			=> "#C4C4C4",
					"Google Chrome"		=> "#2BAA33"
					);
$height			= "14px"; 	//the height of the bars. must include "px"
$units			= "&#37;";	//can be: kg, miles, km, etc. in this example this is the code for %  
$space_b_bars 	= "";	
	
$grafic -> set_bar($bars,  $space_b_bars, $height, $units);

$title 			= "Browser Statistics for the last six months"; //the title of the chart. will be displayed at the top
$title_style	= array("style" => "padding: 5px; font-family : Arial; font-size : 28px; font-weight : bold; color : #dd1212;	text-align : center; text-transform: uppercase; margin-bottom: 10px;");
$info 			= "*source:<br/> W3 Schools, Click <a href ='http://www.w3schools.com/browsers/browsers_stats.asp' style = 'color: #069' target='_blank' >here</a> to visit the page.";
$info_style		= "";
$number_of_gridlines = 10;


$grafic -> chart_settings($title, $title_style, $info, $info_style, $number_of_gridlines);


$data = array(	"January 2011" => array("Internet Explorer"	=> "26.60" ,
										"Firefox"			=> "42.80" ,
										"Google Chrome"		=> "23.80",
										"Safari"			=> "4.00",
										"Opera"				=> "2.50"
									),
				"December 2010" => array("Internet Explorer"=> "27.50" ,
										"Firefox"			=> "43.50" ,
										"Google Chrome"		=> "22.40",
										"Safari"			=> "3.80",
										"Opera"				=> "2.20"
									),
				"November 2010" => array("Internet Explorer"=> "28.60" ,
										"Firefox"			=> "44.00" ,
										"Google Chrome"		=> "20.50",
										"Safari"			=> "4.00",
										"Opera"				=> "2.30"
									),
									
				"October 2010"  => array("Internet Explorer"=> "29.70" ,
										"Firefox"			=> "44.10" ,
										"Google Chrome"		=> "19.20",
										"Safari"			=> "3.90",
										"Opera"				=> "2.20"
									),
									
				"September 2010"=> array("Internet Explorer"=> "31.10" ,
										"Firefox"			=> "45.10" ,
										"Google Chrome"		=> "17.30",
										"Safari"			=> "3.70",
										"Opera"				=> "2.20"
									),

				"August 2010"  => array("Internet Explorer"	=> "30.70" ,
										"Firefox"			=> "45.80" ,
										"Google Chrome"		=> "17.00",
										"Safari"			=> "3.50",
										"Opera"				=> "2.30"
									)			
									);

$reverse		= FALSE;	//show the data in reverse order
$show_legend	= TRUE;		//show the legend for this chart
$show_percentage= FALSE;	//show percentage for every item
$show_h_grid	= FALSE;	//show horizontal grid
$show_scale		= TRUE;		//show scale at the bottom of the chart
$max_width		= 1200; 	//the width in pixels of the right side of the chart for the value of $scale 
$scale			= 100;		//maximum value on the scale


$grafic -> graph($data, $reverse, $show_legend, $show_percentage, $show_h_grid, $show_scale, $max_width, $scale);
$style="";
echo $grafic -> output($style);


*/


class bar_chart {


private $chart_title		= "Bar Chart"; //default title
private $chart_info			= ""; //more info about the chart (source etc.) displayed at the bottom of the chart 
private $title_style		= "style = \"padding: 5px; font-family : Geneva,Verdana,san-serif; font-size : 18px; font-weight : bold; color : #444;	text-align : center;\""; //default style of title
private $info_style			= "style = \"margin: 15px 0px; font-weight:bold; text-align:center; padding: 5px; font-family : Geneva,Verdana,san-serif; font-size : 12px; color : #298901;\""; //default style of chart info
private $grid_divisions		= 5; //default number of scale segments
private $chart_bars;
private $number_of_bars;
private $bar_height			= "14px"; //default height of horizontal bars
private $space_b_bars		= "1px";  //default distance between horizontal bars
private $horizontal_grid	= "border-bottom: 1px solid #444;"; //default style of the horizontal grid
private $bar_units			= "units"; //can be: kg, miles, km, etc. this is the default value
private $label_style		= "style = \"font-family: Geneva,Verdana,san-serif; font-size: 12px; color: #000; text-align: right;\""; //default style for labels (vertical axis)
private $bar_values_style	= "style = \"padding-left: 5px; font-family: Geneva,Verdana,san-serif; font-size: 12px;	color: #444; float: left;\""; //default style of bar values (displayed at the end of each bar) 
private $html; //the html code






	private function show_error($err){
	/*
	== this function outputs error messages
	*/
		$errors = array("0" => "Wrong bar format! <br/>It should be like graph = array( 'name1' => 'color1')",
						"1" => "Wrong data format! Array required!",
						"2" => "The number of data you've entered it's different from the number of bars.",
						"3" => "Bar data must be an array 'name_of_bar' => 'numeric_value'",
						"4" => "The values of bars must be numeric! ex. bar = array('name_of_bar' => 'numeric_value')",
						"5" => "Tha chart could not be displayed. Make sure you've set the chart correctly"
						); 

		if (array_key_exists($err,$errors)){
			$show = $errors[$err];
			}else{
			$show = "Unknown error."; //Unknown error.
		}
				
		$err = '<div style = "color: #000000;font-weight:bold; background-color: #EBEBEB;font-family:Geneva,Verdana,san-serif; font-size:10px; border: 4px solid;margin: 10px 0px; padding:10px;">'.$show."</div>\n";

		return $err;
	}

	
	
	public function chart_settings($title, $title_style, $info, $info_style, $number_of_gridlines){
	/*
	== this are the chart settings ==
	The class will work with the defaults values if you don't use this function
	
	
	$title					= the title of the chart (will be displayed at the top)
							= default value "Bar Chart"
	
	$title_style			= the style of the title ( array("style" => "color: #000") or array("class" => "someclass") )
							= default style "padding: 5px; font-family : Arial; font-size : 18px; font-weight : bold; color : #444;	text-align : center;"
	
	$info					= more info about the chart (will be displayed at the top)
							= default value ""
							
	$info_style				= the style of the info ( array("style" => "color: #000") or array("class" => "someclass") )
							= default style "margin: 15px 0px; font-style: italic; padding: 5px; font-family : Arial; font-size : 14px; color : #444;"
	
	$number_of_gridlines	= this generates the scale (example: if the chart scale is 0 to 300, and you set this to 10, the
							  scale will have 10 segments 
							= default value "5"
	*/
		
		
		if(is_array($title_style)){
			$titlestyle="";
			foreach ($title_style as $attr => $value){
				$titlestyle .=" ".$attr." = ".'"'.$value.'"';}
				
			}else{
			$titlestyle="";
		}
		
		if(is_array($info_style)){
			$infostyle="";
			foreach ($info_style as $attr => $value){
				$infostyle .=" ".$attr." = ".'"'.$value.'"';}
				
			}else{
			$infostyle="";
		}
		
		$title				!= "" ? $this -> chart_title 	= $title 				: "";
		$titlestyle			!= "" ? $this -> title_style 	= $titlestyle			: "";
		$info				!= "" ? $this -> chart_info  	= $info					: "";
		$infostyle			!= "" ? $this -> info_style  	= $infostyle			: "";
		$number_of_gridlines!= "" ? $this -> grid_divisions	= $number_of_gridlines	: ""; 
	}

	
	
	
	public function set_label_style($style){
	/*
	== this sets the style of labels (vertical axis labels) ==
	The class will work with the defaults values if you don't use this function
	
	$style must be an array
	$style = array( "style" => "color: #000; ...." );
	$style = array( "class" => "someclass" );
	
	the default style is: 
		"font-family: Arial; font-size: 14px; font-weight: bold; color: #000; text-align: right;"
	
	*/
		if(is_array($style)){
			$label_style="";
			foreach ($style as $attr => $value){
				$label_style .=" ".$attr." = ".'"'.$value.'"';}
				
			}else{
			$label_style="";
		}
		
		$label_style != "" ? $this -> label_style = $label_style : "";
	}
	
	
	
	
	public function set_bar($bars, $space_b_bars, $height, $units){
	/*
	== this sets the name, color, height and distance between bar(s) ==
	The class will NOT work if you don't use this function.
		
	
	$bars must be an array
	$bars = array( "name1" => "color1" ); - if you have one bar
	
	the color can be "#ffffff",  "white" or "rgb(255,255,255)"
	
	$bars = array(	"name1" => "color1",
					"name2" => "color2",
					"name3" => "color3"); - if you have a group of bars
	
	The next parameters are optionals.
	
	$height = "11px"; 
	if $height is "" the default value will be used (14px);
	
	$units = measuring unit (ex. kilos, meters etc)
	if $units = "" the default value will be used (units)
	
	$space_b_bars = space between horizontal bars
	if $space_b_bars = "" the default value will be used (1px)
	
	*/
		if(is_array($bars)){
				$this -> chart_bars		= $bars;
				$this -> number_of_bars = count($bars);
				$this -> bar_units 		= $units;
									
				if (preg_match( "#^[0-9]+[px]#", $height)){
					$this -> bar_height = $height;
				}
				
				if (preg_match( "#^[0-9]+[px]#", $space_b_bars)){
					$this -> space_b_bars = $space_b_bars;
				}
							
				
			} else {
		
				return $this -> show_error("0") ;
		
		}
		
	
	}


	private function legend(){
	/*
	== this generates the legend ==
	*/
	
		$legend = "\n<!-- -=START=- generate legend -->\n".'<div style = "background-color: #efefef; border: 1px solid #8F8F8F; margin: 5px; padding: 4px 10px 4px 4px; color: #666; font-family: Geneva,Verdana,san-serif;">'."\n".'<div style = "text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #2F2F2F;">Legend:</div>'."\n";
	
	
		foreach ($this -> chart_bars as $bar_name => $bar_color){
		
		$legend .='<div style="padding: 1px;height: 22px;">						
						<div style = "background-color: '.$bar_color.'; width: 20px; height: 20px; float: left; margin: 0px 5px;" ></div>
						<span style = "font-size: 12px; font-weight: bold; text-transform: uppercase;">'.$bar_name."</span>\n</div>\n";
	
	
		}
	
		$legend .="</div>\n<!-- -=END=- generate legend -->\n";
		
		return $legend;
	}	


	
	private function grids($max, $max_width){
	/*
	== this divides the horizontal scale in segments and generates the html code ==
	*/
		
		$i=0;
			$grid ="\n <!-- -=START=- generate grids -->\n";
			while($i<($this -> grid_divisions +1)){
			
				$width_value = $this -> scale_this($max/$this -> grid_divisions , $max, $max_width);
				$scale_value = $i * ($max/ $this -> grid_divisions);
			
				$show_units = ($i == $this -> grid_divisions ? " ".$this -> bar_units .""  : "");
				$i == $this -> grid_divisions ? $width = ''  : $width = 'width: '.$width_value.'px;' ;
			
			
				$grid.='<div style = "'.$width.' float: left; font-family:Geneva,Verdana,san-serif; font-size:12px;">'.$scale_value.$show_units."</div>\n";
			 
				$i++;
			 }
			$grid .=" <!-- -=END=- generate grids -->\n";		
		
		return $grid;
	}
	
		
		
		
	private function get_max($array){
	/*
	== this returns the maximum value from the data array ==
	*/
	
		$numbers = array();
		foreach($array as $item => $bar_group)
		{
		
			foreach($bar_group as $bar_name => $bar_value){
			$numbers[] = $bar_value;}
		}
		
		return max($numbers);

	}
	
	
	
	private function scale_this($number, $max, $scale){
	/*
	== this transforms the value of an element in pixels (width) ==
	*/
		
		$new_number = $number * $scale / $max;
		return floor($new_number);
	}
	
	
	
	
	private function percentage($number, $array){
	
	
		foreach ($array as $key =>$value){
		
			if(is_array($value)){
			//case: multiple items, one bar / item
			//we need the bars from all items to calculate the percentage (the data of all items is analized)
					foreach($value as $bar_name => $bar_value){
						
							$new_array[]= $bar_value; 
					}	
			
				} else {
			//case: multiple items, multiple bars / item 
			//we need only the bars from one item to calculate the percentage (only the data of one item is analized)	
					$new_array[]= $value;
				
			}
		
		}
		
		$max			= array_sum($new_array);
		$percentage		= $number * 100 / $max;
		$percentage		= number_format($percentage, 2, '.', '');
		
		return $percentage."&#37;";
	
	}
	
	
	
	
	
	
	

	public function graph($data, $reverse, $show_legend, $show_percentage, $show_h_grid, $show_scale, $max_width, $scale){
	
	/*
	== this will generate the chart
	$data is an array
	ex.
	=========================================================================
	$data = array(	"January 2011" => array("Internet Explorer"	=> "26.60" ,
										"Firefox"			=> "42.80" ,
										"Google Chrome"		=> "23.80",
										"Safari"			=> "4.00",
										"Opera"				=> "2.50"
									),
				"December 2010" => array("Internet Explorer"=> "27.50" ,
										"Firefox"			=> "43.50" ,
										"Google Chrome"		=> "22.40",
										"Safari"			=> "3.80",
										"Opera"				=> "2.20"
									));
	=========================================================================
	$data = array(	"Year 2010" => array("Facebook"	=> "590",
									"Live.com"	=> "330", 
									"msn.com" 	=> "250", 
									"Yahoo"		=> "400",
									"Youtube"	=> "490",
									"Wikipedia"	=> "260")
									);
	=========================================================================
	$data = array(	"January 2011"	=> array("Google Chrome"	=> "23.80"),
					"December 2010" => array("Google Chrome"	=> "22.40"));
	=========================================================================
		
	$reverse , $show_legend , $show_percentage , $show_h_grid $show_scale  => TRUE or FALSE
	
	$scale 		= highest value on the chart
	$max_width	= the equivalent of $scale in pixels
	*/
	
			
		if(is_array($data)){
		
		
		$max = ( $scale > 0 ? $scale : $this->get_max($data));
			
		if($show_h_grid == FALSE){ $this -> horizontal_grid = "" ; }

		$div_width= $max_width * 1.1 + 200;
		//the beginning of the html code
		$item_html=<<<start
		<table border = "0" cellpadding = "0" cellspacing = "0" align = "center" >
				<tr>
					<td style = "padding: 5px 0px; {$this -> horizontal_grid}" colspan="2"><div {$this -> title_style}>{$this -> chart_title}</div></td>
				</tr>

start;
		
				if($reverse){$data = array_reverse($data, TRUE);} //reverse order
		
		
				$i = 0; $count_items = count($data);
				foreach ($data as $item => $bar_group){
					$i++ ;
					$i == $count_items ? $this -> horizontal_grid = "border-bottom: 2px solid #466D8E;" : "";
					$item_html .=<<<start
					<tr>
					<td style = "padding: 5px 5px; " valign = "middle">
						<div {$this -> label_style}>$item: </div>
					</td>
					
					<td style = "padding: 5px 0px; border-left: 2px solid #466D8E; {$this -> horizontal_grid}" valign="middle">
					<!-- -=START=- Bars for $item -->
					
start;
										
					
					if(is_array($bar_group)){
					
						$countbars = count($bar_group);
						
						if($countbars <= $this -> number_of_bars){
						
						
							foreach($bar_group as $bar_name => $bar_value){
							
								if(is_numeric($bar_value)){
										
										if($show_percentage){
												$percent = "<span>(".$this -> percentage($bar_value, $bar_group).")</span>";
												} else {
												$percent = "";
											}
																				
										$bar_color = " background-color: ".$this -> chart_bars[$bar_name];
										if(count($bar_group) > 1){
										
											
											
											$item_html .=<<<start
																				
										<div style = "$bar_color; height : {$this->bar_height}; float : left; width: {$this -> scale_this($bar_value, $max, $max_width)}px; font-size: 1px;"><img src="images/onePixelIntakeGraph.jpg" width="{$this -> scale_this($bar_value, $max, $max_width)}px"></div> 
										<div {$this -> bar_values_style}>$bar_value {$this -> bar_units} $percent</div>
										<div style = "font-size: 1px;clear:both;margin-bottom:{$this -> space_b_bars};">&nbsp;</div>
start;
											} else {
											
											if($show_percentage){
												$percent = "<span>(".$this -> percentage($bar_value, $data).")</span>";
												} else {
												$percent = "";
											}
											
											
											$item_html .=<<<start
										
										<div style = "$bar_color; height : {$this -> bar_height}; float : left; width: {$this -> scale_this($bar_value, $max, $max_width)}px; font-size: 1px;"><img src="images/onePixelIntakeGraph.jpg" width="{$this -> scale_this($bar_value, $max, $max_width)}px" height="10px"></div> 
										<div {$this -> bar_values_style}> {$this -> bar_units} $percent</div>
start;

										}
										
											

											
							
									} else {
									//bar values are not numerical
									$this -> html = $this -> show_error("4") ;
								
								}

								
							}//end - foreach bar	
							$item_html .= "\n					<!-- -=END=- Bars for $item --> \n</td>\n</tr>\n";
							
							} else {
							//number of bars set <> number of bar data 
							$this -> html = $this -> show_error("2") ;
						}
					
					
						
						} else {
						//bar_group not an array
						$this -> html = $this -> show_error("3") ;
					}
					
								
				}//end foreach data
		
			//add source and legend
			
			
			// -=START=- generate legend
			if($show_legend){ $legend = $this -> legend(); } else {	$legend = "&nbsp;"; }
			$item_html .= '<tr><td style = "border: 0px;">'.$legend.'</td><td valign = "top" style = "border: 0px; padding: 0px;">';
			// -=END=- generate legend
			
			// -=START=- generate scale
			if($show_scale){ $item_html .= $this -> grids($max, $max_width); }
			// -=END=- generate scale
			
			
			//add the final part of the html code
			$item_html .='<div style = "clear: both;"></div>'."\n<div {$this -> info_style}>".$this -> chart_info .'</div></td></tr>'."</table>\n";
			$this -> html = $item_html;
		
			} else {
			
			$this -> html = $this -> show_error("1") ;
		}
	
	}


	public function output($style){
	/*
	== this outputs the html code
	$style = the style of the div. 
			 can be: array("style" => "....") or string "style='....'" 	
	*/
		if(is_array($style)){
				$div_style="";
				foreach ($style as $attr => $value){
					$div_style .=" ".$attr." = ".'"'.$value.'"';}
					
				}else{
				$div_style=$style;
		}

			
		if($this -> html !=""){
		
			return "<!-- -=START=- Bar Chart =".$this -> chart_title ."= -->
			<div $div_style>\n". $this -> html . "</div>\n<!-- -=END=- Bar Chart ={$this -> chart_title}= -->";
			
			} else {
		
		return $this -> show_error("5") ;
			
		}
	
	}
	
}

?>