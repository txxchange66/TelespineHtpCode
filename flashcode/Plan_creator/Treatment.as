/*
Treatment Class for TX plan_creator.fla
*/
import mx.utils.Delegate;
import mx.managers.DepthManager;
import flash.geom. *;
import flash.display.BitmapData;
import flash.filters. *;
class Treatment extends MovieClip 
{
	private var _id : Number;
	// treatment id
	private var _tname : String;
	// treatment name
	private var _image_url : String;
	private var _has_image : Boolean;
	private var holder_mc : MovieClip;
	// holder for image
	private var label_txt : TextField;
	// text field label
	private var _main : Main;
	private var _load_listener : Object;
	private var _row : Number;
	private var _col : Number;
	private var _bounds : Array;
	private var _copy : Treatment;
	// reference to copy created on drag
	private var _coper : Treatment;
	// reference to Treament creator
	private var bg_mc : MovieClip;
	private var _last_over : String;
	private var _plan_order : Number;
	private var _dragoverL_mc : MovieClip;
	private var _dragoverR_mc : MovieClip;
	public var last_over_mc : MovieClip;
	public var last_over_other_mc : MovieClip;
	private var _treatment_detail_xml : XML;
	private var _showVideoIcon : Boolean;
	private var _benefitText : String;
	public var _is_in_plan : Boolean;
	private var _popup_mc : MovieClip;
	private var _images_mc : Array;
	private var _images_url : Array;
	public var isCopy : Boolean;
	public var _oldX, _oldY;
	private var _treatment_detail_url = '/asset/flash/plan_create.php?act=getTreatment&id=';
	public function Treatment ()
	{
		if (this._tname)
		{
			this.label_txt.text = this._tname;
		} else 
		{
			this.label_txt.text = 'no name';
		}
		this._has_image = false;
		if (this._image_url)
		{
			this._has_image = true;
			//this._main.doStatus (0, 'loading image ' + this._image_url + ' for ' + this._tname);
			this._load_listener = new Object ();
			this._load_listener.onLoadInit = Delegate.create (this, doImageLoaded);
			var mcl = new MovieClipLoader ();
			mcl.addListener (this._load_listener);
			mcl.loadClip (this._main.server_url + this._image_url, this.holder_mc);
		}
		this._last_over = '';
		// setup bounds for drags
		// left top right bottom
		this._bounds ['result'] = new Array (0, 0, 600, 480);
		this._bounds ['plan'] = new Array (0, 10, 0, 10);
		this.init ();
		this._benefitText = "";
		this._treatment_detail_xml = new XML ();
		this._is_in_plan = false;
		this._popup_mc = _root._popup_mc;
		this._treatment_detail_xml.ignoreWhite = true;
		this.isCopy = false;
		this._treatment_detail_xml.onLoad = Delegate.create (this, handleTreatmentDetailXml);
		//old //new trace("\n this._treatment_detail_url :: "+this._treatment_detail_url + this._id);
		this._treatment_detail_xml.load (this._main.server_url + this._treatment_detail_url + this._id);
		//this._treatment_detail_xml.load(this._treatment_detail_url);
		
	}
	public function getCoper () : Treatment 
	{
		return this._coper;
	}
	public function init () : Void 
	{
		this.onPress = Delegate.create (this, doPress);
		this.onRelease = Delegate.create (this, doRelease);
		this.onReleaseOutside = Delegate.create (this, doReleaseOut);
		this.onRollOver = Delegate.create (this, doRollOver);
		this.onRollOut = Delegate.create (this, doRollOut);
	}
	public function getRow () : Number 
	{
		return this._row;
	}
	public function getImagesMC () : Array
	{
		return _images_mc;
	}
	public function getCol () : Number 
	{
		return this._col;
	}
	public function getId () : Number 
	{
		return this._id;
	}
	public function getName () : String 
	{
		return this._tname;
	}
	public function getImageUrl () : String 
	{
		return this._image_url;
	}
	public function hasImage () : Boolean 
	{
		return this._has_image;
	}
	public function setPlanOrder (newOrder : Number) : Void 
	{
		if (newOrder > 0)
		{
			this._plan_order = newOrder;
		}
	}
	public function getPlanOrder () : Number 
	{
		if (this._plan_order)
		{
			return this._plan_order;
		}
		return 0;
	}
	public function doImageLoaded (obj)
	{
		// resize pic
		var scale_pct = 1;
		if (this.holder_mc._width > this.holder_mc._height)
		{
			scale_pct = 100 / this.holder_mc._width;
		} else 
		{
			scale_pct = 75 / this.holder_mc._height;
		} // scale if needed and round to whole pixels
		if (scale_pct != 1 && scale_pct > 0)
		{
			this.holder_mc._xscale = this.holder_mc._yscale = scale_pct * 100;
			this.holder_mc._width = Math.floor (this.holder_mc._width);
			this.holder_mc._height = Math.floor (this.holder_mc._height);
		}
		// center pic horz
		var newX = Math.floor ((100 - this.holder_mc._width) / 2);
		this.holder_mc._x = (newX < 0) ? 0 : newX;
		// center pic vert
		var newY = Math.floor ((75 - this.holder_mc._height) / 2);
		this.holder_mc._y = (newY < 0) ? 0 : newY;
		//this._main.doStatus (2, 'Pic ' + this._tname + ' resize ratio = ' + scale_pct + ' resized to (' + this.holder_mc._width + ',' + this.holder_mc._height + ') @ (' + this.holder_mc._x + ',' + this.holder_mc._y + ')');
		// hide loading
		this.bg_mc._visible = false;
	}
	public function doPress () : Void 
	{
		this._alpha = 75;
		this._popup_mc._visible = false;
		//old //new trace('drag press');
		this._main.doTreatmentPress (this, 'result');
		this._copy = this._main.copyTreatment (this);
		this._copy.isCopy = true;
		////old //new trace('swapping '+this.getDepth()+' to '+this._copy.getDepth());
		if (this._copy.getDepth () > this.getDepth ())
		{
			this.swapDepths (this._copy);
		}
		// start drag - from result
		this.startDrag (false, this._bounds ['result'][0] , this._bounds ['result'][1] , this._bounds ['result'][2] , this._bounds ['result'][3]);
		//this.startDrag(false);
		// start watch for drag over scroll or delete btns
		this.onMouseMove = Delegate.create (this, doWatch);
		removeImages ();
	}
	public function doRelease () : Void 
	{
		delete this.onMouseMove;
		this._main.doTreatmentRelease (this, 'result');
		this._alpha = 100;
		this.stopDrag ();
		this._main.evalDrop (this);
	}
	public function doReleaseOut () : Void 
	{
		delete this.onMouseMove;
		this._main.doTreatmentRelease (this, 'result');
		this._alpha = 100;
		this.stopDrag ();
		this._main.evalDrop (this);
	}
	/************************************************************************************************************
	* Changes for the enhancement to show popup with images
	* Get the treatment details from the server using treatment id
	* Show the treatment images, video icon and benefits using the above info
	* Adjust the popup according to content and position
	*************************************************************************************************************/
	public function handleTreatmentDetailXml (ok : Boolean) : Void 
	{
		// make the popup initially just to be careful
		this._popup_mc._visible = false;
		this._popup_mc.stop ();
		// get treatment images, benefit and video icon
		var max_order = this._treatment_detail_xml.firstChild.childNodes.length;
		var i = 0;
		_images_url = new Array ();
		_images_mc = new Array ();
		// iterate over the xml child nodes of the treatment xml
		for (i = 0; i < max_order; i ++)
		{
			if (this._treatment_detail_xml.firstChild.childNodes [i].nodeName == 'image')
			{
				_images_url.push (_treatment_detail_xml.firstChild.childNodes [i].attributes.src);
			} else if (this._treatment_detail_xml.firstChild.childNodes [i].nodeName == 'video')
			{
				// set the flag to show the video icon
				this._showVideoIcon = true;
			} else 
			{
				// set the benefit text
				this._benefitText = this._treatment_detail_xml.firstChild.childNodes [i].firstChild.nodeValue;
			}
		}
		//if(this._benefitText == undefined)
		if (this._benefitText == "")
		this._benefitText = "N/A";
	}
	
	public function removeImages () : Void
	{
		
		
		for(var cl1 in this._popup_mc.timage1_mc.holder_mc)
		{
			this._popup_mc.timage1_mc.holder_mc[cl1]._visible = false;
			
			_root.debug.txt.text += "\n befour this._popup_mc.timage1_mc.holder_mc[cl1] : " + this._popup_mc.timage1_mc.holder_mc[cl1] + "\n";
			this._popup_mc.timage1_mc.holder_mc[cl1].removeMovieClip();
			_root.debug.txt.text += "\n delete this._popup_mc.timage1_mc.holder_mc[cl1] : " + this._popup_mc.timage1_mc.holder_mc[cl1] + "\n";
		}
		
		for(var cl2 in this._popup_mc.timage2_mc.holder_mc)
		{
			this._popup_mc.timage2_mc.holder_mc[cl2]._visible = false;
			_root.debug.txt.text += "\n befour this._popup_mc.timage2_mc.holder_mc[cl1] : " + this._popup_mc.timage2_mc.holder_mc[cl1] + "\n";
			this._popup_mc.timage2_mc.holder_mc[cl2].removeMovieClip();
			_root.debug.txt.text += "\n delete this._popup_mc.timage2_mc.holder_mc[cl1] : " + this._popup_mc.timage2_mc.holder_mc[cl1] + "\n";
		}
		
		for(var cl3 in this._popup_mc.timage3_mc.holder_mc)
		{
			this._popup_mc.timage3_mc.holder_mc[cl3]._visible = false;
			_root.debug.txt.text += "\n befour this._popup_mc.timage3_mc.holder_mc[cl1] : " + this._popup_mc.timage3_mc.holder_mc[cl1] + "\n";
			this._popup_mc.timage3_mc.holder_mc[cl3].removeMovieClip();
			_root.debug.txt.text += "\n delete this._popup_mc.timage3_mc.holder_mc[cl1] : " + this._popup_mc.timage3_mc.holder_mc[cl1] + "\n";
		}
		
			
		// make the images corresponding to this treatment invisible
		//trace("Before ________________________________________ this._images_mc.length  : " + this._images_mc.length + "\n");
//_root.debug.txt.text += "\nBefore ____ this._images_mc.length  : " + this._images_mc.length + "\n";
		
		for(var prop in _images_mc)
		{
			var mclip = this._images_mc.pop();
			//trace("mclip  : " + mclip);
			//trace("mclip._visible  : " + mclip._visible);
			//_root.debug.txt.text += "\n befour mclip._visible  : " + mclip._visible + "\n";
			mclip._visible = false;
			//_root.debug.txt.text += "\n after mclip._visible  : " + mclip._visible + "\n";
			//trace(" after mclip._visible  : " + mclip._visible);
			mclip.removeMovieClip ();
			//trace("mclip  : " + mclip);
		}
		
		//_root.debug.txt.text += "\nAfter __ this._images_mc.length  : " + this._images_mc.length + "\n";
		//trace("\nAfter _____________________ this._images_mc.length  : " + this._images_mc.length);
	}
	// handler for roll over event
	public function doRollOver () : Void 
	{
		// bring the popup to the center of this treatment
		this._popup_mc._x = this._x;
		this._popup_mc._y = this._y + 145;
		if (this._is_in_plan) this._popup_mc._x = this._x + this._parent._x;
		// place the popup correctly after deciding which popup to show
		if (this._popup_mc._x - this._width / 2 - this._popup_mc._width / 2 > 0)
		{
			if (this._popup_mc._y - (75) / 2 - this._popup_mc._height > 0)
			{
				this._popup_mc._x -= this._popup_mc._width / 2;
				this._popup_mc._y -= 10 + this._popup_mc._height / 2;
				this._popup_mc.gotoAndStop (3);
			} else 
			{
				this._popup_mc._x -= this._popup_mc._width / 2;
				this._popup_mc._y += (75) / 2 + this._popup_mc._height / 2;
				this._popup_mc.gotoAndStop (4);
			}
		} else 
		{
			if (this._popup_mc._y - (75) / 2 - this._popup_mc._height > 0)
			{
				this._popup_mc._x += this._popup_mc._width / 2 + 20;
				this._popup_mc._y -= 20 + this._popup_mc._height / 2;
				this._popup_mc.gotoAndStop (1);
			} else 
			{
				this._popup_mc._x += this._popup_mc._width / 2 + 20;
				this._popup_mc._y += (75) / 2 + this._popup_mc._height / 2;
				this._popup_mc.gotoAndStop (2);
			}
		}
		if (this._is_in_plan)
		{
			this._popup_mc._y = 425;
			this._popup_mc.prevFrame ();
			this._popup_mc._x = (this._popup_mc._x <= 142) ?144 : this._popup_mc._x;
		}
		this.removeImages ();
		// load the image into movieClip and store the reference into _images_mc
		var movieClip : MovieClip;
		if (_images_url.length >= 1)
		{
			movieClip = this._popup_mc.timage1_mc.holder_mc.createChildAtDepth ("empty.mc", DepthManager.kTop, new Object ());
			loadBitmapSmoothed (_images_url [0] , movieClip, this._popup_mc.timage1_mc.bg_mc);
			//this._images_mc [0] = movieClip;
			this._images_mc.push(movieClip);
		}
		else
		{
			this._popup_mc.timage1_mc._visible = false;
			this._popup_mc.timage2_mc._visible = false;
			this._popup_mc.timage3_mc._visible = false;
		}
		if (_images_url.length >= 2)
		{
			movieClip = this._popup_mc.timage2_mc.holder_mc.createChildAtDepth ("empty.mc", DepthManager.kTop, new Object ());
			loadBitmapSmoothed (_images_url [1] , movieClip, this._popup_mc.timage2_mc.bg_mc);
			//this._images_mc [1] = movieClip;
			this._images_mc.push(movieClip);
		}
		else
		{
			this._popup_mc.timage2_mc._visible = false;
			this._popup_mc.timage3_mc._visible = false;
		}
		if (_images_url.length >= 3)
		{
			movieClip = this._popup_mc.timage3_mc.holder_mc.createChildAtDepth ("empty.mc", DepthManager.kTop, new Object ());
			loadBitmapSmoothed (_images_url [2] , movieClip, this._popup_mc.timage3_mc.bg_mc);
			//this._images_mc [2] = movieClip;
			this._images_mc.push(movieClip);
		}
		else
		{
			this._popup_mc.timage3_mc._visible = false;
		}
		// put the name and benefit of this treatment in justified manner into the html text
		this._popup_mc.benefit_text.html = true;
		this._popup_mc.benefit_text.wordWrap = true;
		this._popup_mc.benefit_text.htmlText = "<p align='justify'>" + _benefitText + "</p>";
		// show the video icon depending on whether video is present in this treatment or not
		if ( ! _showVideoIcon)
		{
			this._popup_mc.videoicon_mc._visible = false;
		} else 
		{
			this._popup_mc.videoicon_mc._visible = true;
		}
		// make the popup visible
		/*
		this._popup_mc.label_txt.html = true;
		this._popup_mc.label_txt.wordWrap = true;
		this._popup_mc.label_txt.htmlText = "<p align='justify'>"+this._tname+"</p>";
		*/
		this._popup_mc.label_txt.text = this._tname;
		this._popup_mc._visible = true;
	}
	// handler for roll out event
	public function doRollOut () : Void 
	{
		// make the popup invisible
		this._popup_mc._visible = false;
		// make the images corresponding to this treatment invisible
		removeImages ();
		this._popup_mc.timage1_mc.bg_mc._visible = true;
		this._popup_mc.timage1_mc._visible = true;
		this._popup_mc.timage2_mc.bg_mc._visible = true;
		this._popup_mc.timage2_mc._visible = true;
		this._popup_mc.timage3_mc.bg_mc._visible = true;
		this._popup_mc.timage3_mc._visible = true;
	}
	// load the smoothed image to the movie clip
	function loadBitmapSmoothed (url : String, target : MovieClip, backgr : MovieClip)
	{
		var bmc : MovieClip = target.createEmptyMovieClip ("bmc", target.getNextHighestDepth ());
		var listener : Object = new Object ();
		listener.tmc = target;
		listener.bgmc = backgr;
		listener.onLoadInit = function (obj : MovieClip)
		{
			// resize pic
			obj._visible = false;
			var myMatrix : Matrix = new Matrix ();
			var scale_pct = 1;
			if (obj._width > obj._height)
			{
				scale_pct = 100 / obj._width;
			} else 
			{
				scale_pct = 75 / obj._height;
			}
			// create new bitmapdata  according to the size of loaded image and scale calculated above
			var bitmap : BitmapData = new BitmapData (obj._width * scale_pct, obj._height * scale_pct, true, 0);
			// attach to the movie clip
			this.tmc.attachBitmap (bitmap, this.tmc.getNextHighestDepth () , "auto", true);
			// draw the loaded clip to bitmap with scale matrix
			myMatrix.scale (scale_pct, scale_pct);
			bitmap.draw (obj, myMatrix, null, null, null, true);
			// apply the averaging filter to bitmap
			/*var arr:Array = [1,4,7,4,1,
			4,16,26,16,4,
			7,26,41,26,7,
			4,16,26,16,4,
			1,4,7,4,1];
			bitmap.applyFilter(bitmap,bitmap.rectangle,new Point(0,0), new ConvolutionFilter(5,5,arr,273));
			// apply the laplacian filter to bitmap
			var bitLap:BitmapData = bitmap.clone();
			arr = [-1,-1,-1,-1,8,-1,-1,-1,-1];
			bitLap.applyFilter(bitmap,bitmap.rectangle,new Point(0,0), new ConvolutionFilter(3,3,arr,1,16));
			// merge the laplacian with averaging to get sharpened image
			bitmap.merge(bitLap,bitLap.rectangle,new Point(0,0),32,32,32,0);*/
			// remove the orginally loader clip
			obj.removeMovieClip ();
			// center pic horz
			var newX = Math.floor ((100 - this.tmc._width) / 2);
			this.tmc._x = (newX < 0) ? 0 : newX;
			// center pic vert
			var newY = Math.floor ((75 - this.tmc._height) / 2);
			this.tmc._y = (newY < 0) ? 0 : newY;
			this.bgmc._visible = false;
		};
		var loader : MovieClipLoader = new MovieClipLoader ();
		loader.addListener (listener);
		loader.loadClip (this._main.server_url + url, bmc);
	}
	/*************************************End of change on 27/08/2008******************************************/
	public function isInPlan (planOrder) : Void 
	{
		this.setPlanOrder (planOrder);
		this.onPress = Delegate.create (this, doPlanPress);
		this.onRelease = Delegate.create (this, doPlanRelease);
		this.onReleaseOutside = Delegate.create (this, doPlanReleaseOut);
		this._is_in_plan = true;
		// attach hidden clips on top to catch dragovers
		// one one left and right half to catch order direction
		this._dragoverL_mc = super.createChildAtDepth ('droptarget.id', DepthManager.kTop,{_name:'L',_x:-5,_y:-5});
		this._dragoverR_mc = super.createChildAtDepth ('droptarget.id', DepthManager.kTop, {_name:'R',_rotation:180,_x:105,_y:80});
		
		/*
		var p = new Object ();
		p._name = 'L';
		// left half
		p._x = - 5;
		p._y = - 5;
		*/
		
		/*
		p._name = 'R';
		// right half
		p._rotation = 180;
		p._x = 105;
		p._y = 80;
		*/
		
	}
	/**
	* Handle press when in plan.
	* Start drag.
	*/
	public function doPlanPress () : Void 
	{
		this._alpha = 65;
		this._popup_mc._visible = false;
		this._main.doTreatmentPress (this, 'plan');
		//this.startDrag(false,this._bounds['plan'][0],this._bounds['plan'][1],this._bounds['plan'][2],this._bounds['plan'][3]);
		this._oldX = this._x;
		this._oldY = this._y;
		this.startDrag (false, 0, 0, this._parent._parent._width, 100);
		this._main.clearDropOrder (this);
		this.onMouseMove = Delegate.create (this, doWatch);
		//_root.popup
	}
	public function doPlanRelease () : Void 
	{
		//new trace ("R in side " + this._droptarget);
		this._alpha = 100;
		this._main.doTreatmentRelease (this, 'plan');
		this.stopDrag ();
		delete this.onMouseMove;
		//new trace ("R inside side " + this._droptarget);
		this._main.evalDrop (this);
	}
	public function doPlanReleaseOut () : Void 
	{
		this._alpha = 100;
		this._main.doTreatmentRelease (this, 'plan');
		this.stopDrag ();
		delete this.onMouseMove;
		if ((this._droptarget).indexOf ('scroll_delete_mc') != - 1)
		{
			this._main.evalDrop (this);
		} 
		else
		{
			this._x = this._oldX;
			this._y = this._oldY;

		}
		//new trace ("R out side " + this._droptarget);
	}
	/**
	* While dragging, watch clip's _droptarget to tell what we enter and exit.
	* Handle whatever needs to happen on real dragover.
	*/
	public function doWatch () : Void 
	{
		
		//new trace(" @@fun do watch on ln508 ----------------------------------->");
			
		
		var dt = this._droptarget;
		
		if(dt.indexOf("/drop_mc/scroll_delete_mc") != -1)
		{
			
			
			new_last_over = dt;
			this._main.doScrollDeleteOver();
			
		}
		else
		{
			this._main.doScrollDeleteOut();
		}
		
		// only continue if over something new
		if (this._last_over != dt)
		{
			var new_last_over = '';
			this._main.endDropHilite ();
			if (this.last_over_mc)
			{
				this.last_over_mc.endDragOver ();
				this.last_over_other_mc.endDragOver ();
			}
			
						
			// activate new over
			//old //new trace ("dt :: " + dt);
			new_last_over = dt;
			switch (dt)
			{
				case '/drop_mc/scroll_l_mc' :this._main.startScrollLeft ();break;
				case '/drop_mc/scroll_r_mc' :this._main.startScrollRight ();break;
				
				default :
								// look for drag over dynamic plan drop targets
								this._main.doScrollDeleteOut();
								var start_plan_targ_empty = dt.indexOf ('/drop_mc/holder_mc/depthChild');
								var start_plan_targ_full = dt.indexOf ('/drop_mc/holder2_mc/depthChild');
								if (start_plan_targ_empty != - 1)
								{
									//new trace("Target is Empty :");
									start_plan_targ_empty = ('/drop_mc/holder_mc/depthChild').length;
									// over empty plan drop target
									var end_plan_targ_empty = dt.indexOf ('/', ('/drop_mc/holder_mc/depthChild').length - 1);
									if (end_plan_targ_empty == - 1)
									{
										end_plan_targ_empty = dt.length;
									}
									var plan_order = dt.substring (start_plan_targ_empty, end_plan_targ_empty);
									if (plan_order)
									{
										this._main.startDropHilite (int (plan_order) + 1);
										//this._main.doStatus (2, 'Over empty plan ' + (int (plan_order) + 1) + '(' + start_plan_targ_empty + ',' + end_plan_targ_empty + ')');
									}
									new_last_over = dt;
								} 
								else if (start_plan_targ_full != - 1)
								{
									//new trace("Target is Full :");
									
									start_plan_targ_full = ('/drop_mc/holder2_mc/depthChild').length;
									// over full plan drop target
									var end_plan_targ_full = dt.indexOf ('/', ('/drop_mc/holder2_mc/depthChild').length - 1);
									if (end_plan_targ_full == - 1)
									{
										end_plan_targ_full = dt.length;
									}
									var plan_order = dt.substring (start_plan_targ_full, end_plan_targ_full);
									var plan_dir = dt.substr (end_plan_targ_full + 1, 1);
									if (plan_order)
									{
										var targ_mc = eval (('/drop_mc/holder2_mc/depthChild') + plan_order);
										if (targ_mc)
										{
											// over valid plan
											this.last_over_mc = targ_mc;
											plan_order = this.last_over_mc.getPlanOrder ();
											this.last_over_mc.startDragOver (plan_dir);
											// other drop
											var test_other_mc = false;
											var other_dir = 'R';
											
											
											
											if (plan_dir == 'R')
											{
												other_dir = 'L';
												test_other_mc = this._main.getTreatmentByOrder (int (plan_order) + 1);
											} else if (plan_dir == 'L')
											{
												test_other_mc = this._main.getTreatmentByOrder (int (plan_order) - 1);
											}
											
											//new trace("test_other_mc :: " + test_other_mc);
											//new trace("plan_dir :: " + plan_dir);
											//new trace("other_dir :: " + other_dir);
											//new trace("dt :: " + dt);
											
											
											if (test_other_mc)
											{
												this.last_over_other_mc = test_other_mc;
												this.last_over_other_mc.startDragOver (other_dir);
												
												
												
											} 
										} 
										else 
										{
											this._main.doError (2, 'Failed making dragover target: ' + ('/drop_mc/holder2_mc/depthChild') + plan_order);
										}
									}
									new_last_over = dt;
								} else 
								{
									// over nothing important
									new_last_over = dt;
								}
				break;
			}
			this._last_over = new_last_over;
		}
	
	//new trace("<------------ @@fun do watch on ln628");
	}
	public function startDragOver (dir : String) : Void 
	{
		this["_dragover"+dir+"_mc"].gotoAndStop (2);
	}
	public function endDragOver () : Void 
	{
		this._dragoverR_mc.gotoAndStop (1);
		this._dragoverL_mc.gotoAndStop (1);
		
	}
}