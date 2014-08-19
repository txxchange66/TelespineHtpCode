import mx.utils.Delegate;
import mx.controls.ComboBox;
import mx.controls.UIScrollBar;
import fl.data.DataProvider;


import mx.managers.DepthManager;
import mx.video.*;
import TreatmentThumb;
import TextField.StyleSheet;
//import flash.display.Sprite;

class Viewer extends MovieClip
{
	public var nav_t_mc:MovieClip; // treatment navigation clip
	public var instruction_txt;
	public var __instruction_txt:TextField;
	public var benefits_txt;
	public var __benefits_txt:TextField;
	public var final_txt:TextField;
	public var indexValue:Number=0;
	public var zxc_txt:TextField;
	public var side_txt:TextField;
	
	public var treatment_txt:TextField;
	public var sets_txt:TextField;
	public var reps_txt:TextField;
	public var hold_txt:TextField;
	
	public var article_txt;
	public var __article_txt:TextField;
	public var viewer_mc:MovieClip;
	public var nav_t_prev_mc:MovieClip;
	public var nav_t_next_mc:MovieClip;
	public var nav_t_print_mc:MovieClip;
	public var preloader_mc:MovieClip;
	public var view_cb:ComboBox;
	public var view_cb1:ComboBox;
	public var sb:UIScrollBar;
	public var sb1:UIScrollBar;
	public var control_holder_mc:MovieClip;
	public var main_view_mc:MovieClip;
	
	private var plan_id:Number;
	private var plan_xml:XML;
	private var _xml_url:String = '/asset/flash/plan_view.php';
	
	private var _order_current:Number;
	private var _treatments:Array; 
	
	private var _pic_load_listener:Object;
	private var _vid_load_listener:Object;
	private var _feedback_interval:Number;
	private var _ss_interval:Number;
	private var _pic_current:Number;
	public var CountForComboValue:Number=0;
	private var isBuff:Boolean = false;
	public function Viewer ()
	{
		this.nav_t_mc = _root.nav_t_mc;
		this.instruction_txt = _root.instruction_txt;
		this.__instruction_txt = _root.__instruction_txt;
		
		this.benefits_txt = _root.benifits_txt;
		this.benefits_txt.text = "test b";
		this.__benefits_txt = _root.__benifits_txt;
		
		this.side_txt = _root.side_txt;
		
		
		this.sets_txt = _root.sets_txt;
		this.reps_txt = _root.reps_txt;
		this.hold_txt = _root.hold_txt;
		this.treatment_txt = _root.treatment_txt;
						
		this.article_txt = _root.article_txt;
		this.__article_txt = _root.__article_txt;
		
		this.viewer_mc = _root.viewer_mc;
		this.nav_t_prev_mc = _root.nav_t_prev_mc;
		this.nav_t_next_mc = _root.nav_t_next_mc;
		this.nav_t_print_mc = _root.nav_t_print_mc;
		this.view_cb = _root.view_cb;
		this.view_cb1 = _root.view_cb1;
		this.control_holder_mc = control_holder_mc;
		this.main_view_mc = main_view_mc;
		this.sb=_root.sb;
		this.sb1=_root.sb1;
		
		_root.IsRewindPlay = false;
		
		
		
		
		
		
		
		
		
		
		disablePrevBtn();
		disableNextBtn();
		if(this.benefits_txt.maxscroll>1)
			{
				sb._visible=true;
			}
			else if(this.benefits_txt.maxscroll<1 ||this.benefits_txt.text=="")
			{
				sb._visible=false;
			}
			
		if(_root.plan_id)
		{
			this.plan_id = _root.plan_id;
			this.getPlan();
		}
		else
		{
			this.doError(3, 'No Plan ID');
		this.plan_id = 204542;
			this.getPlan();
		}
		this.viewer_mc.main_view_mc.preloader_mc._visible = false;
		// set up main pic load listeners
		this._pic_load_listener = new Object();
		this._pic_load_listener.pic1 = new Object();
		this._pic_load_listener.pic2 = new Object();
		this._pic_load_listener.pic3 = new Object();
		// and video
		this._vid_load_listener = new Object();
		
		
	}
	
	private function getPlan ():Void
	{
		//trace("\n ****************getPlan Fun*******************");
		this._treatments = new Array();
		this._order_current = 0;
		
		this.plan_xml = new XML();
		this.plan_xml.ignoreWhite = true;
		this.plan_xml.onLoad = Delegate.create(this, handlePlanXml);
		var xml_url = this._xml_url + '?id=' + this.plan_id; // + '&r=' + Math.floor(Math.random()*10000)
		//var xml_url = 'test.xml';
    	trace( xml_url)
		//xml_url = 'http://txdev.here:8888' + xml_url;
		this.plan_xml.load(xml_url);
		this.doStatus(2,'Loading data..');
		this.doStatus(1,'From '+xml_url);
		var styles:TextField.StyleSheet = new TextField.StyleSheet();
		//styles.onLoad = function(success:Boolean):Void;
		styles.load("example.css");
	}
	
	private function handlePlanXml (ok:Boolean):Void
	{
		//trace("\n ****************Plan Loaded******************* \n XML :: " +this.plan_xml );
		var article_txt:String = '';
		    //_root.debug.txt.text="hello"+ this.plan_xml.firstChild.childNodes[1].firstChild.nodeValue;
			//_root.debug.txt.text="hello"+ this.plan_xml.firstChild;//.childNodes[1].firstChild.nodeValue;
         var img_url:String;
		 //= this.plan_xml.firstChild.childNodes[1].firstChild.nodeValue;
		   //var mcLoader:MovieClipLoader = new MovieClipLoader();
        //trace("img_url : "+img_url);
		//_root.debug.txt.text+="img_url : "+img_url;
		//_root.imgldr.loadMovie(img_url);
		var str_article : String = "";
		if(ok && this.plan_xml.firstChild.nodeName == 'plan' && this.plan_xml.firstChild.attributes.id == this.plan_id)
		{					
			
		for(var i = 0; i < this.plan_xml.firstChild.childNodes.length; i++)
			{
				var t = this.plan_xml.firstChild.childNodes[i];
				
				if(t.nodeName == 'clinic_logo')
				{
					img_url = t.firstChild.nodeValue;
					_root.debug.txt.text+="t.nodeName == clinic_logo";
				}
				else if(t.nodeName == 'treatment')
				{
					if(t.attributes.order)
					{
						var Tname = t.attributes.name;
						var instruction = new Array();
						var benefits = new Array();
						var pic = new Array();
						var video = '';
						var CountForComboValue=new Array();
						var lengthofNodes:Number=t.childNodes.length;
						for(var j = 0; j < t.childNodes.length; j++)
						{
							var t2 = t.childNodes[j];
							switch(t2.nodeName)
							{
								case 'image':
									if(t2.attributes.src && t2.attributes.order > -1)
									{
										pic[t2.attributes.order] = t2.attributes.src;
										
										}
									break;
								case 'video':
									video = t2.attributes.src;																									
									break;
								case 'instruction':
									instruction = new Array(t2.childNodes[0].nodeValue, t2.attributes.sets, t2.attributes.reps, t2.attributes.hold, t2.attributes.lrb);
									
									break;
								case 'benefit':
									benefits = new Array(t2.childNodes[0].nodeValue);
									break;
								
								default:
									this.doError(0, 'Found unknown treatment child '+t2.nodeName);
									break;
							}
						}
						var trmnt = this.addTreatment(Tname, instruction, pic, video,benefits,CountForComboValue);
					}
					else this.doError(2,'Skipped treatment with no order.');
				}
				else if(t.nodeName == 'article' && t.attributes.name)
				{
					//Modify Date 2-07-08 -----Applying Hyperlink style using CSS
					/*
					if(t.attributes.href && t.attributes.href != 'http://') article_txt += '<a href="'+t.attributes.href+'" target="_blank">'+t.attributes.name+'</a><br>';
					else article_txt += t.attributes.name+'<br>';
					*/
					if(t.attributes.href && t.attributes.href != 'http://') 
					{
						//this.__article_txt.htmlText =  "<a class='a' href='"+t.attributes.href+"' target='_blank'>"+t.attributes.name+"</a><br>";
						str_article +="<a class='a' href='"+t.attributes.href+"' target='_blank'>"+t.attributes.name+"</a><br>";;
					this.__article_txt.htmlText = str_article;//"<a class='a' href='"+t.attributes.href+"' target='_blank'>"+t.attributes.name+"</a><br>";
					this.article_txt.text = this.__article_txt.htmlText;
					}
					else 
					{
						str_article +=t.attributes.name+"<br>";
						this.__article_txt.htmlText = str_article;//t.attributes.name+"<br>";
						this.article_txt.text = this.__article_txt.htmlText;
						
					//article_txt += t.attributes.name+"<br>";
					}
					
				}
				
				
			}
			
			trace("img_url : "+img_url);
			_root.debug.txt.text+="img_url : "+img_url;
			//_root.imgldr.loadMovie(img_url);
			this.loadClinicLogo(_root.clinicLogoHolder_mc.logoHolder, img_url);
			this.doStatus(2, 'Finished loading.');
			this.doStatus(0, 'Loaded XML: '+this.plan_xml.toString());
			this.article_txt.html = true;
			var my_styleSheet:StyleSheet = new StyleSheet();
			my_styleSheet.parseCSS(".a{text-decoration:none;color:#08518C;}a:hover{text-decoration:underline;color:#08518C;}");
			this.article_txt.styleSheet = my_styleSheet;
			this.article_txt.htmlText = article_txt;
			
			this.doStart();
		}
		else
		{
			// bad reply
			this.doError(3, 'Failed loading plan data.'); //' from '+this._xml_url + '?id=' + this.plan_id+'. ' + ok + ' - ' + this.plan_xml.firstChild.nodeName + ' - ' + this.plan_xml.firstChild.attributes.id);
		}
	}
	
	function loadClinicLogo(holder, url)
    {
      var  _load_listener = new Object();
        _load_listener.onLoadInit = mx.utils.Delegate.create(this, doImageLoaded);
        _load_listener.onLoadError = function (target_mc, errorCode, httpStatus)
        {
            trace (">> loadListener.onLoadError()");
            trace (">> ==========================");
            trace (">> errorCode: " + errorCode);
            trace (">> httpStatus: " + httpStatus);
        };
        var _loc2 = new MovieClipLoader();
        _loc2.addListener(_load_listener);
        _loc2.loadClip(url, holder);
    } // End of the function
	
	 function doImageLoaded()
    {
        trace ("Image Loaded done");
        var _loc3 = Math.floor((300 - _root.clinicLogoHolder_mc.logoHolder._width) / 2);
        _root.clinicLogoHolder_mc.logoHolder._x = _loc3;
        var _loc2 = Math.floor((100 - _root.clinicLogoHolder_mc.logoHolder._height) / 2);
        _root.clinicLogoHolder_mc.logoHolder._y = _loc2;
    } // End of the function
	/*
	* add treatment to array and return detail sub-array
	*/
	private function addTreatment (Tname:String, instruction:Array, pic:Array, video:String,benefits:Array,CountForComboValue:Array):Array
	{
		// re-order without gaps
		var t = new Array(Tname, instruction, pic[1], pic[2], pic[3], video,benefits,CountForComboValue);
		this._treatments.push(t);
		return this._treatments[this._treatments.length-1];
	}
	
	/*
	Start Initial Playback
	*/
	private function doStart ():Void
	{
		if(this._treatments.length > 0)
		{
			// setup treatment nav
			this.initNav();
			
			// load first treatment
			this.loadTreatment(0);
			
			// enable print button
			this.nav_t_print_mc.enabled = true;
			this.nav_t_print_mc.onRelease = Delegate.create(this, doPrint);
		}
		else
		{
			this.doError(3, 'No Treatments in this Plan');
			this.main_view_mc.play();
		}
	}
	
	private function initNav ():Void
	{
		// create treatment icons
		var _w = 0;
		for(var i=-3; i<this._treatments.length; i++)
		{
			var p:Object = new Object();
			
			if(i < 0)
			{
				if(i == -3)
				{
					p._x = -2 * 37;
				}
				else if(i == -2)
				{
					p._x = (-1 * 37) - 10;
				}
				else if(i == -1)
				{
					p._x = (-1 * 37) + 10;
				}
			}
			else
			{
				p._x = i * 30;
			}
			
			var new_mc = this.nav_t_mc.attachMovie('nav_t_btn.id', 'btn' + i + '_mc', i+1, p);			
			var callit = i+1;
			_w = p._x; 
			if(i > 0)
			{       
				new_mc.n_txt.text = callit;
				enableTrmntBtn(new_mc);
			}
			else if(i == 0)
			{
				new_mc.n_txt.text = callit;
				disableTrmntBtn(new_mc);
			}
			else
			{
				new_mc.n_txt.text = "";
				new_mc.enabled = false;
				new_mc.gotoAndStop('_up');
			}
			this.doStatus(1, 'Added new treatment nav button ' + typeof new_mc +'  y='+p._y);
		}
		
		
		
		// move next btn
		this.nav_t_next_mc._x = this.nav_t_mc._x + (this.nav_t_mc._width - 70) + 5;
		
		// move print button
		this.nav_t_print_mc._x = this.nav_t_next_mc._x + this.nav_t_next_mc._width + 5;
		
		if(_w >= 0)
		{
			var j=0;
			for(var i=-5; i<-3; i++)
			{
				var p:Object = new Object();
				if(i == -5)
				{
					p._x = _w + (j * 37)+25;
				}
				else
				{
					p._x = _w + (j * 37)+17;
				}
				//_root.mytxt.text=p+"p"+"L"+_w
				
				var new_mc = this.nav_t_mc.attachMovie('nav_t_btn.id', 'btn' + i + '_mc', i+1, p);			
				
				new_mc.n_txt.text ="";
				new_mc.enabled = false;
				new_mc.gotoAndStop('_up');
				this.doStatus(1, 'Added new treatment nav button ' + typeof new_mc +'  y='+p._y);
				j++;
			}
		}
		
		// init view select
		var t = new Object();
		
		t.change = Delegate.create(this, doViewChange);
		this.view_cb.addEventListener("change", t);
		this.view_cb.enabled = true;
	}
	
	function onStateChange(eventObject:Object):Void
	{      
	     //_root.my_txt.text="on state chaneg";
	 
		this.main_view_mc.vid_mc.video_FLVPlybk._x = 0;
		this.main_view_mc.vid_mc.video_FLVPlybk._y = 0;
		//alignVideoCenterMiddle(610,457);
		 this.alignVideoCenterMiddle(640, 480);
		//trace(" onStateChange eventObject :: "+ eventObject.toString());
		//_root.my_txt.text=this.main_view_mc.vid_mc.video_FLVPlybk+"";
          if((this.main_view_mc.vid_mc.video_FLVPlybk.buffering) && (!this.main_view_mc.vid_mc.Loaded))
		{      
		//  _root.my_txt.text=this.main_view_mc.vid_mc.video_FLVPlybk+"if";
			if(!_root.IsRewindPlay)this.viewer_mc.main_view_mc.preloader_mc._visible = true;
			//this.main_view_mc.vid_mc.video_FLVPlybk.pause();
			isBuff = true;
		}
		else 
		{   
			//trace("isBuff :: " + isBuff);
			//_root.my_txt.text=this.main_view_mc.vid_mc.video_FLVPlybk+"else";
			this.main_view_mc.vid_mc.video_FLVPlybk._visible = true;
			if(isBuff)
			{     
			     var _buffTime = Math.floor(Math.round(this.main_view_mc.vid_mc.video_FLVPlybk.totalTime)/4);
				 this.main_view_mc.vid_mc.video_FLVPlybk.bufferTime = ((_buffTime + 1) > 10)?10:(_buffTime + 1);
				//trace("applied bufftime ::" + this.main_view_mc.vid_mc.video_FLVPlybk.bufferTime);
			}
			this.viewer_mc.main_view_mc.preloader_mc._visible = false;
			//this.main_view_mc.vid_mc.video_FLVPlybk.play();
		}
		//Edited on 24-Sep-2010-4:15PM @Reson:Align Vedio to Center and Middle
		
	}
	
function alignVideoCenterMiddle(_W:Number,_H:Number)
{
	trace("AlignCenterMiddle");
	this.main_view_mc.vid_mc.video_FLVPlybk._x = (_W - this.main_view_mc.vid_mc.video_FLVPlybk._width)/2;
	this.main_view_mc.vid_mc.video_FLVPlybk._y = (_H - this.main_view_mc.vid_mc.video_FLVPlybk._height)/2;
}
 function showVideo()
    {   
	     
		// _root.my_txt.text="show video";
        indexValue = view_cb.selectedIndex;
        if (_treatments[_order_current][5])
        {
            this.stopSlideShow();
            control_holder_mc._visible = false;
            main_view_mc.vid_mc._visible = true;
            if (main_view_mc.vid_mc.video_FLVPlybk != null)
            {
                main_view_mc.vid_mc.Loaded = false;
                var _loc2 = new Object();
                _loc2.stateChange = mx.utils.Delegate.create(this, onStateChange);
                main_view_mc.vid_mc.video_FLVPlybk.addEventListener("stateChange", _loc2);
                delete this.onEnterFrame;
                function onEnterFrame()
                {
                    this.alignVideoCenterMiddle(640, 480);
                } // End of the function
                main_view_mc.vid_mc.video_FLVPlybk.playheadUpdateInterval = 10;
                main_view_mc.vid_mc.video_FLVPlybk.progressInterval = 10;
                main_view_mc.vid_mc.video_FLVPlybk.bufferTime = 1.000000E-001;
                this.gotoAndStop("vid");
            } // end if
        }
        else
        {
            this.doError(2, "No video for this treatment.");
            view_cb.selectedIndex = 0;
            this.showSlideShow();
        } // end else if
    } // End of the function
	private function showSlideShow():Void
	{	indexValue = this.view_cb.selectedIndex
		this.control_holder_mc._visible = true;
		this.main_view_mc.vid_mc._visible = false;
		this.gotoAndStop('pic');
		this.startSlideShow();
	}
	
	public function doViewChange ():Void
	{
		trace("his.view_cb.selectedItem['data']"+this.view_cb.selectedItem['data']);
		this.viewer_mc.main_view_mc.preloader_mc._visible = false;
		
		var m = this.view_cb.selectedItem['data'];
		
		if(m == 's') this.showSlideShow();
		else if(m == 'v') this.showVideo();
		else if(m == 'p') this.doPrint();
		indexValue = this.view_cb.selectedIndex
	}
	
	public function doViewReset ():Void
	{
		this.view_cb.selectedIndex = 0;		
	}
	
	private function doPrint():Void
	{
		//trace("indexValue::"+indexValue);
		
		//Modify Date 2-07-08 ---------new link 
		//super.getURL('/patient/plan_print.php?id=' + this.plan_id, '_blank');
		super.getURL('/index.php?action=planPrint&id=' + this.plan_id, '_blank');
		// endof--------Modify Date 2-07-08 ---------new link 
			
		this.view_cb.selectedIndex = indexValue;
		//this.showSlideShow();
	}
	
	private function setTrmntNav (new_order:Number):Void
	{
		this.nav_t_prev_mc = _root.nav_t_prev_mc;
		//this.nav_t_prev_mc.alpha = 0;
		for(var i = 0; i < this._treatments.length; i++)
		{
			var new_mc = eval('this.nav_t_mc.btn'+i+'_mc');
			if(new_mc != undefined)
			{
				if(i != new_order)
				{
					enableTrmntBtn(new_mc);
				}
				else
				{
					disableTrmntBtn(new_mc);
					if(this._treatments.length == 1)
					{
						this.doStatus(1,'Viewing only treatment.');
						//trace("i:::"+this._treatments.length);
						this.disableNextBtn();
						this.disablePrevBtn();
					}
					else if(i == 0)
					{
						this.doStatus(1,'Viewing 1st treatment.');
						trace("i ::::::::::::;:::"+this._treatments.length);
						this.enableNextBtn();
						this.disablePrevBtn();
					}
					else if(i == (this._treatments.length - 1))
					{
						this.doStatus(1,'Viewing last treatment.');
						this.disableNextBtn();
						this.enablePrevBtn();
					}
					else
					{
						this.doStatus(1,'Viewing middle treatment.');
						this.enableNextBtn();
						this.enablePrevBtn();
					}
				}
			}
			else this.doError(0,'couldnt find btn '+i);
		}
	}
	
	/**
	 * Set treatment sub-navigation icon to active (unselected) state
	*/
	private function enableTrmntBtn(btn_mc:MovieClip):Void
	{
		btn_mc.enabled = true;
		btn_mc.gotoAndStop('_up');
		// change # text to blue color
		btn_mc.n_txt.htmlText = '<font color="#0A558F">'+btn_mc.n_txt.text+'</font>';
		btn_mc.onRelease = function ()
		{
			// magically get treatment order
			_root.viewer_mc.loadTreatment(int(this.n_txt.text) - 1);
		}
	}
	
	/**
	 * Set treatment sub-navigation icon to inactive (selected) state
	*/
	private function disableTrmntBtn(btn_mc:MovieClip):Void
	{
		//trace('disabling btn '+btn_mc._name);
		btn_mc.gotoAndStop('off');
		// change # text to green color
		btn_mc.n_txt.htmlText = '<font color="#2C8B04">'+btn_mc.n_txt.text+'</font>';
		btn_mc.enabled = false;
		btn_mc.onRelease = undefined;
	}
	
	
	private function enablePrevBtn():Void
	{
		this.nav_t_prev_mc.enabled = true;
		//this.nav_t_prev_mc._alpha = 100;
		this.nav_t_prev_mc.onRelease = Delegate.create(this, doPrev);
		if(this.nav_t_prev_mc._currentframe == 5) this.nav_t_prev_mc.gotoAndStop('_up');
	}
	private function disablePrevBtn():Void
	{
		trace("call");
		//this.nav_t_prev_mc._alpha = 30;//50
		this.nav_t_prev_mc.enabled = false;
		this.nav_t_prev_mc.gotoAndStop('off');
		this.nav_t_prev_mc.onRelease = undefined;
		
		
	}
	private function enableNextBtn():Void
	{
		this.nav_t_next_mc.enabled = true;
		//this.nav_t_next_mc._alpha = 100;
		this.nav_t_next_mc.onRelease = Delegate.create(this, doNext);
		if(this.nav_t_next_mc._currentframe == 5) this.nav_t_next_mc.gotoAndStop('_up');
	}
	private function disableNextBtn():Void
	{
		this.nav_t_next_mc.enabled = false;
		//this.nav_t_next_mc._alpha = 30;//50
		this.nav_t_next_mc.onRelease = undefined;
		this.nav_t_next_mc.gotoAndStop('off');
	}
	private function doNext ():Void
	{
		//Added Data : 27 Jun 08 By @5149 ----- to Fix Clicking Isuue
		Selection.setFocus(_root.temp_btn);
		//End of-----Added Data : 27 Jun 08 By @5149 ----- to Fix Clicking Isuue
		this.loadTreatment(this._order_current + 1);
		
	}
	private function doPrev ():Void
	{
		//Added Data : 27 Jun 08 By @5149 ----- to Fix Clicking Isuue
		Selection.setFocus(_root.temp_btn);
		//End of-----Added Data : 27 Jun 08 By @5149 ----- to Fix Clicking Isuue
		this.loadTreatment(this._order_current - 1);
	}
	
	
	private function doFillCombo (checkValue):Void
	{
		this.control_holder_mc._visible = false;
		this.main_view_mc.vid_mc._visible = false;
		//trace("Me Called doFillCombo"+checkValue);
		 this.view_cb.removeAll();
		 if(checkValue==0)
		   {
			this.view_cb.addItemAt(0, {data:"s", label:"Slideshow"});
			this.view_cb.addItemAt(1, {data:"p", label:"Print"});

		   }
		   else if(checkValue==1)
		   {
			   
			 this.view_cb.addItemAt(0, {data:"v", label:"Video"});
			  this.view_cb.addItemAt(1, {data:"s", label:"Slideshow"});
			  this.view_cb.addItemAt(2, {data:"p", label:"Print"});

				
		   }
		    else if(checkValue==2)
		   {
			   
			 this.view_cb.addItemAt(0, {data:"v", label:"Video"});
			  this.view_cb.addItemAt(1, {data:"p", label:"Print"});

				
		   }
		  
	}

		
	private function loadTreatment (order:Number):Void
	{ 
		//trace("loadTreatment :: fun" );
		this.viewer_mc.main_view_mc.preloader_mc._visible = false;
		if(order > -1)
		{
			this.control_holder_mc._visible = false;
			this.main_view_mc.vid_mc._visible = false;
			this._order_current = order;
			this.doStatus(1, 'Loading treatment '+order+ this._treatments[order].toString());
			
			this.control_holder_mc.getInstanceAtDepth(1).removeMovieClip();
			this.control_holder_mc.getInstanceAtDepth(2).removeMovieClip();
			this.control_holder_mc.getInstanceAtDepth(3).removeMovieClip();
							
			var firstSrc:String;
			var firstOrder:Number = 0;
			var totalPics:Number = 0;
			
			// load all pics into main holders
			for(var i = 2; i < 6; i++)
			{
				if(i != 5)
				{
					// load pic
					this.loadPic(i-1, this._treatments[order][i]);
					totalPics++;
				}
				else
				{
					// load video
					
					//this.main_view_mc.vid_mc.video_FLVPlybk.contentPath = null;
					
					this.main_view_mc.vid_mc.video_FLVPlybk._x = 0;
					this.main_view_mc.vid_mc.video_FLVPlybk._x = 0;
					this.main_view_mc.vid_mc.video_FLVPlybk.autoSize = true;
					this.loadVid(this._treatments[order][5]);
				}
			}
	
			//trace("\n **********Actual Instruction TXT********** :: " + this._treatments[order][1][0]);
			this.__instruction_txt.htmlText = this._treatments[order][1][0];
			this.instruction_txt.text = this.__instruction_txt.htmlText;
						
			this.sets_txt.text = (this._treatments[order][1][1] != undefined) ? this._treatments[order][1][1].toString() : '';
			this.reps_txt.text = (this._treatments[order][1][2] != undefined) ? this._treatments[order][1][2].toString() : '';
			this.hold_txt.text = (this._treatments[order][1][3] != undefined) ? this._treatments[order][1][3].toString() : '';
			this.side_txt.text = (this._treatments[order][1][4] != undefined) ? this._treatments[order][1][4].toString() : '';
			//_root.benefits_txt.text = (this._treatments[order][6][0]!= undefined) ? this._treatments[order][6][0].toString() : '';
	     	_root.__benefits_txt.htmlText =(this. _treatments[order][6][0] != undefined) ?_treatments[order][6][0].toString(): '';
           _root. benefits_txt.text = _root.__benefits_txt.htmlText;
			//_root.my_txt.text=_root. benefits_txt.text +"::"+this. benefits_txt ;
			if(this.benefits_txt.maxscroll>1)
			{
				sb._visible=true;
			}
			else if(this.benefits_txt.maxscroll<1 ||this.benefits_txt.text=="")
			{
				sb._visible=false;
			}
			
			//trace("this._treatments[order][2]::::"+this._treatments[order][2]);
			if(this._treatments[order][5]=='' ||this._treatments[order][5]==undefined && this._treatments[order][2]!=undefined)
			{
				doFillCombo(0);
				trace("if")
			}
			else if((this._treatments[order][5]!='' ||this._treatments[order][5]!=undefined) && (this._treatments[order][2]!=undefined))
			{
				doFillCombo(1);
				trace("else if")
			}
			else if((this._treatments[order][5]!='' ||this._treatments[order][5]!=undefined) && (this._treatments[order][2]==undefined || this._treatments[order][2]==''))
			
			{
				doFillCombo(2);
				trace("else if1111")
			}
			else
			{
				doFillCombo(2);
			}
	
			this.treatment_txt.text = this._treatments[order][0];
			
			this.setTrmntNav(order);
			
			this.doViewReset();
			
			this.doViewChange();
		}
		else
		{
			this.doError(2, 'Failed loading treatment with no order.');
		}
	}
	
	private function loadPic (order:Number, src:String):Void
	{
		if(order)
		{
			var picTarg = eval('this.viewer_mc.main_view_mc.holder'+order.toString()+'_mc');
			if(!picTarg) picTarg = this.viewer_mc.main_view_mc.createEmptyMovieClip('holder'+order.toString()+'_mc', this.viewer_mc.main_view_mc.getNextHighestDepth());
			var listener = 'this._pic_load_listener.pic'+order.toString()+'_mc';
			if(picTarg && listener)
			{
				listener = new Object();
				if(src)
				{
					// load pic into main 
					//listener.onLoadInit = Delegate.create(this, doMainImageLoaded);	
					listener.targ = this;
					listener.id = order;
					listener.holder_mc = picTarg;
					listener.onLoadInit = function() { this.targ.doMainPicLoaded(this.id, this.holder_mc) }

					var mcl = new MovieClipLoader();
					mcl.addListener(listener);
					mcl.loadClip(src, picTarg);
					this.doStatus(1,'Loading pic'+order+' from '+src);
					
					// create new treatment thumb object
					var p = new Object();
					p['_viewer'] = this;
					p['_order'] = order;
					p['_image_src'] = src;
					p['_y'] = -10;
					p['_x'] =  95 + ((order-1) * 140);

					var new_mc = this.control_holder_mc.attachMovie('TreatmentThumb.id', 'thumb' + order + '_mc', order, p);
					this.doStatus(1,'Created new thumb for pic '+order+' '+new_mc._name);
					
				}
				else
				{
					// delete pic in holder
					picTarg.removeMovieClip();
					this.doStatus(1,'No src at order '+order);
				}	
			}
			else
			{
				this.doError(2,'Couldn\'t find holder '+
					picTarg.toString()+
					' or listener '+
					listener.toString());
			}
		}
		else 
		{
			//trace('no pic at '+order);
		}
	}
	
	private function doMainPicLoaded(order:Number, holder_mc:MovieClip):Void
	{
				
		this.showPic(1);
		this.doStatus(1,'Loaded pic'+order+' into '+holder_mc._name);
		// center pic horz
		//-------holder_mc._x = Math.floor((640 - holder_mc._width)/2);
		
		//Modifyed
		// center pic horz
		        
		             if(holder_mc._height>480)
					 {  
					  holder_mc._height=480;
				      holder_mc.scaleX=holder_mc.scaleY;
					 
					 }
					 if(holder_mc._width>640)
					 {
						holder_mc._width = 640;
						holder_mc.scaleY=holder_mc.scaleX;
						
						 
					 }
		             /*if(holder_mc._width>640)||(holder_mc._height>480))
					 {
					    holder_mc._width = holder_mc._height / holder_mc._width;
					    holder_mc._height= holder_mc._width / holder_mc._height;
						if ((640 / 480) < holder_mc._height) {
						holder_mc._width = 640;
						holder_mc._height = holder_mc._height * holder_mc._width;
						} else {
						holder_mc._height = 480;
						holder_mc._width = holder_mc._width* holder_mc._height;
						}
					 }*/
					holder_mc.forceSmoothing = true;
			   //  holder_mc.cacheAsBitmap= true;
				//if(holder_mc._height>480)
				// {  
				  // _root.trace1_txt.text="inheighht";
				  //holder_mc._height=480;
				  //holder_mc.scaleX=holder_mc.scaleY;
				 //}
				 //else
				 //{
				 // holder_mc._height=holder_mc._height;	 
				 //}
			  // if(holder_mc._width>640)
			   //{     
			     //_root.trace_txt.text="inwidth";
				//holder_mc._width=640;
				// holder_mc.scaleY=holder_mc.scaleX;
			  // }
			   //else
			   //{
				  //holder_mc._width= holder_mc._width;   
			   //}
			
		//_root.trace_txt.text=" afeter target"+  holder_mc._width +"::"+ holder_mc._height;
		
		var newX = Math.floor((640 - holder_mc._width)/2);
		holder_mc._x = newX;
		// center pic vert
		var newY = Math.floor((482 - holder_mc._height)/2);//482
		holder_mc._y =newY;
		if(order == 1 && this.view_cb.selectedItem['data']!='v')
		{
			this.viewer_mc.main_view_mc.play();
			this.showPic(1);
		}
	}
	
	public function showPic(order:Number)
	{
		//trace("\n showPic function"
		this.doStatus(1,'Showing pic '+order);
		
		if(this._treatments[this._order_current][order+1])
		{      
		
			for(var i = 2; i < 5; i++)
			{     
			  
				var picTarg = eval('this.viewer_mc.main_view_mc.holder'+(i-1)+'_mc');
				picTarg.forceSmoothing = true;
			if(picTarg)
				{
					if(i != (order+1))
					{
						picTarg._visible = false;
						//_root.trace_txt.text= "after pic target"+ picTarg._width +"::"+picTarg._height;
						//this.doStatus(1, 'Hiding '+picTarg._name);
					}
					else
					{
						picTarg._visible = true;
						this._pic_current = order;
						//this.doStatus(1, 'SHOWING '+picTarg._name);
					}
				}
				else this.doError(2,'Failed changing pic to '+order);
			}
			
		}
		else
		{
			this.doError(1, 'No pic to show at order '+order);
		}
	}
	
	public function startSlideShow():Void
	{
		if(this._ss_interval) 
		this.stopSlideShow();
		// count pics
		var c = 0;
		for(var i = 2; i < 5; i++)
		{
			if(this._treatments[this._order_current][i]) c++;
		}
		if(c > 1)
		{
			this.doStatus(3,'Starting SlideShow...');
			this._ss_interval = setInterval(this, 'doSlideChange', 2000);
		}
	}
	
	public function stopSlideShow():Void
	{
		clearInterval(this._ss_interval);
	}
	
	private function doSlideChange()
	{
		var nextOrder = 0;
		if(this._pic_current == 3){
			nextOrder = 1;
		} 
		else if(this._pic_current == 2)
		{
			if(this._treatments[this._order_current][4]) nextOrder = 3;
			else nextOrder = 1;
		}
		else if(this._pic_current == 1)
		{
			if(this._treatments[this._order_current][3]) nextOrder = 2;
			else nextOrder = 3;
		}
		if(nextOrder)
		{
			this.showPic(nextOrder);
		}
	}
	
	public function loadMainPic (order:Number, src:String):Void
	{    
	 //  _root.trace_txt.text="loadmain"
		if(src != undefined)
		{
			this._pic_load_listener.onLoadInit = Delegate.create(this, doMainImageLoaded);

			var mcl = new MovieClipLoader();
			mcl.addListener(this._pic_load_listener);
			mcl.loadClip(src, this.main_view_mc.holder_mc);  _root.trace_txt.text="src"+src
		}
		
	}
	
	private function doMainImageLoaded ():Void
	{
		this.doStatus(1,'Loaded pic at x='+this.main_view_mc.holder_mc._x);
	}
	
	
	
	
	private function loadVid (src:String):Void
	{
		if(src)
		{
			this.main_view_mc.vid_mc.video_FLVPlybk.contentPath = src;
			_root.IsRewindPlay = false;
			//on Error in FLV file loading *************
			this.main_view_mc.vid_mc.video_FLVPlybk.error = function() {				
				this.main_view_mc.vid_mc.video_FLVPlybk.stop();
				this.main_view_mc.vid_mc.video_FLVPlybk = null;
			}
			this.main_view_mc.vid_mc.video_FLVPlybk.complete = function() {
			  	//this.seek(0);
			  	//trace("on FLV complete event handler:: " + this);
				this.play();
				_root.IsRewindPlay = true;
			 };
		}// else fail w/o notice
	}
	
	public function doStatus(priority:Number, msg:String)
	{
		switch(priority)
		{
			case 3:
			case 2:
				this.clearStatus();
				this.viewer_mc.feedback_txt.htmlText = '<font color="#66FF66">'+msg+'</font>';
				this._feedback_interval = setInterval(this, 'clearStatus', 2500);
			case 1:
				if(priority != 3)
				{
					//trace('\n STATUS: '+msg);
				}
				break;
			case 0:
				// ignore
			default:
				
				break;
		}
	}
	
	public function doError(priority:Number, msg:String)
	{
		//trace('\n doError fun :: ERROR (' + priority + ') - ' + msg);
		switch(priority)
		{
			case 3:
				this.clearStatus();
				this.viewer_mc.feedback_txt.htmlText = '<font color="#FF6666"><b>'+msg+'<b></font>';
				break;
			case 2:
				this.clearStatus();
				this.viewer_mc.feedback_txt.htmlText = '<font color="#FF6666">'+msg+'</font>';
				this._feedback_interval = setInterval(this, 'clearStatus', 2500);
			case 1:
				if(priority != 3)
				{
					//trace('\n ERROR: '+msg);
				}
				break;
			case 0:
			default:
				
				break;
		}
	}
	
	public function clearStatus ():Void
	{
		this.doStatus(1, 'Cleared Status');
		this.viewer_mc.feedback_txt.htmlText = '';
		clearInterval(this._feedback_interval);
	}
}