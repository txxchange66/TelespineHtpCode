import mx.utils.Delegate;
import mx.managers.DepthManager;
import mx.controls.ComboBox;
import mx.controls.TextInput;
import mx.controls.List;
import flash.external.ExternalInterface;
import Treatment;
class Main
{
	private static var requestId : Number = 0;
	private var lastprocessed_no : Number = 0;
	private var searchString : String = "";
	private	var textvalue : String;
	//public var server_url = "http://htpdev.txxchange.com";
	public var server_url = "";
	//private var server_url ="http://192.18.0.115";
	// url to get treatment category tree
	private var _xml_cat_url = server_url + '/asset/flash/plan_create.php?act=getCats';
	private var _treatement_source_url = server_url + '/asset/flash/plan_create.php?act=getTreatments';
	private var _save_url = server_url + '/asset/flash/plan_create.php';
	private var _plan_treatment_url = server_url + '/asset/flash/plan_create.php?act=getPlan';
	private var page_url = server_url + '/asset/flash/plan_create.php?act=getTags';
	public var searchbar_mc : MovieClip;
	public var locker_mc : MovieClip;
	/* searchbar_mc contains:
	public var cat1_top_cb:ComboBox;
	public var cat1_sub_cb:ComboBox;
	public var cat2_top_cb:ComboBox;
	public var cat2_sub_cb:ComboBox;
	public var btn_mc:MovieClip;
	/* searchbar_mc.btn_mc contains:
	public var search_btn_mc:MovieClip;
	public var clear_btn_mc:MovieClip;
	*/
	private var _mode : Number;
	private var _cat : Object;
	private var _top1_listener : Object;
	private var _sub1_listener : Object;
	private var _top2_listener : Object;
	private var _sub2_listener : Object;
	private var _ti_listener : Object;
	private var _ac_listener : Object;
	public var drag_mc : MovieClip;
	public var drop_mc : MovieClip;
	public var active_holder_mc : MovieClip;
	private var _plan_id : Number;
	private var _plan_xml : XML;
	private var _cat_xml : XML;
	private var _treatment_xml : XML;
	private var _drag_treatments : Object;
	private var _page_current : Number;
	private var _page_total : Number;
	private var _active_drag_mc : MovieClip;
	private var _active_load_listener : Object;
	private var _scroll_to : Number;
	// scroll button timeout
	private var _drops : Array;
	private var _inPlan : Array;
	private var _drop_load_listener : Object;
	private var reply_xml : XML;
	private var mylv : XML;
	private var sa ;
	public function Main ()
	{
		_root.mainRef = this;
		// locate items on timeline
		//_root.debug.txt.text = "\n debug window..........";
		this.searchbar_mc = _root.searchbar_mc;
		this.drag_mc = _root.drag_mc;
		this.drop_mc = _root.drop_mc;
		this.mylv = new XML ();
		this.active_holder_mc = _root.active_holder_mc;
		// lookup plan details if id provided
		//_root.plan_id = 119;
		if (_root.plan_id)
		{
			this._plan_id = int (_root.plan_id);
			doStatus (1, "Found plan id " + this._plan_id);
			this.getPlanTreatments ();
		}
		
		searchbar_mc.searchtreatment_txt.text=String(_root.SEARCH_TREATMENT);//"SEARCH TREATMENTS";
		drag_mc.treatmentresult_txt.text=String(_root.TREATMENT_RESULTS);//"TREATMENT RESULTS";		
		drop_mc.treatmentplan_txt.text=String(_root.SELECTED_TREATMENTS);//"SELECTED TREATMENTS"
		
		// plan data container
		this._inPlan = new Array (undefined);
		// setup category selection comboboxes
		// top1
		this._top1_listener = new Object ();
		this._top1_listener.targ = this;
		this._top1_listener.id = 1;
		this._top1_listener.change = function ()
		{
			this.targ.doTopChange (this.id)
		}
		this.searchbar_mc.cat1_top_cb.addEventListener ("change", this._top1_listener);
		// sub1
		this._sub1_listener = new Object ();
		this._sub1_listener.targ = this;
		this._sub1_listener.id = 1;
		this._sub1_listener.change = function ()
		{
			this.targ.doSubChange (this.id)
		}
		this.searchbar_mc.cat1_sub_cb.addEventListener ("change", this._sub1_listener);
		// top2
		this._top2_listener = new Object ();
		this._top2_listener.targ = this;
		this._top2_listener.id = 2;
		this._top2_listener.change = function ()
		{
			this.targ.doTopChange (this.id)
		}
		this.searchbar_mc.cat2_top_cb.addEventListener ("change", this._top2_listener);
		// sub2
		this._sub2_listener = new Object ();
		this._sub2_listener.targ = this;
		this._sub2_listener.id = 2;
		this._sub2_listener.change = function ()
		{
			this.targ.doSubChange (this.id)
		}
		this.searchbar_mc.cat2_sub_cb.addEventListener ("change", this._sub2_listener);
		// text input
		this._ti_listener = new Object ();
		this._ti_listener.targ = this;
		this._ac_listener = new Object ();
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		_root.scrollCap._visible = false;
		_root.AutoComplete.setStyle ("themeColor", "0xA5C4EA");
		_root.preloader._visible = false;
		_root.leftcap._visible = true;
		_root.AutoComplete.swapDepths (_root.getNextHighestDepth ());
		_root.AutoComplete.setStyle ("fontSize", 11);
		_root.AutoComplete.rowHeight = 17;
		Selection.setFocus (this.searchbar_mc.filter_ti);
		this.searchbar_mc.filter_ti.maxChars = 38;
		// _root.AutoComplete.setStyle("fontFamily", "Monotype Corsiva");
		// handle text input events
		this._ti_listener.handleEvent = function (evt_obj : Object)
		{
			if (evt_obj.type == 'change')
			{
				_root.AutoComplete.removeAll ()
				this.targ.doTextChange ();
			}
		}
		this._ac_listener.handleEvent = function (evt_obj : Object)
		{
			if (evt_obj.type == "change")
			{
				_root.searchbar_mc.filter_ti.text = _root.AutoComplete.selectedItem.label;
				_root.AutoComplete._visible = _root.locker_mc._visible = false;
				_root.AutoComplete.vScrollPolicy = "off";
			}
		}
		this.searchbar_mc.filter_ti.addEventListener ("change", this._ti_listener);
		this.searchbar_mc.filter_ti.addEventListener ("enter", this._ti_listener);
		this.searchbar_mc.myFav.onRelease = Delegate.create (this, doTextFilter3);
		_root.AutoComplete.addEventListener ("change", this._ac_listener);
		// treatment xml container
		this._treatment_xml = new XML ();
		this._treatment_xml.ignoreWhite = true;
		this._treatment_xml.onLoad = Delegate.create (this, handleTreatmentXml);
		// get treatment category tree
		this._cat = new Object ();
		//this.populateFilterCats();
		// init drag
		this._drag_treatments = new Array ();
		// init paging
		this._page_current = 1;
		this._page_total = 0;
		// add drop targets
		this._drops = new Array ();
		for (var i = 1; i < 6; i ++)
		{
			this.addDropTarget ();
		}
		// init scrollbar actions
		this.drop_mc.scroll_l_mc.onPress = Delegate.create (this, startScrollLeft);
		this.drop_mc.scroll_l_mc.onRelease = Delegate.create (this, endScroll);
		this.drop_mc.scroll_l_mc.onReleaseOutside = Delegate.create (this, endScroll);
		this.drop_mc.scroll_r_mc.onPress = Delegate.create (this, startScrollRight);
		this.drop_mc.scroll_r_mc.onRelease = Delegate.create (this, endScroll);
		this.drop_mc.scroll_r_mc.onReleaseOutside = Delegate.create (this, endScroll);
		this.drop_mc.scroll_r_mc.useHandCursor = true;
		this.drop_mc.scroll_l_mc.useHandCursor = true;
		this.drop_mc.scrollbar_mc.onPress = Delegate.create (this, startScrollDrag);
		this.drop_mc.scrollbar_mc.onRelease = Delegate.create (this, endScrollDrag);
		this.drop_mc.scrollbar_mc.onReleaseOutside = Delegate.create (this, endScrollDrag);
		//this.disableScrollBtns ();
		this.drag_mc.paging_mc._visible = this.drag_mc.paging_mc.enabled = false;
		// init seach
		this.disableSearchBtn ();
		this.searchbar_mc.search_btn_mc.gotoAndStop ('search');
		this.searchbar_mc.search_btn_mc.btn_mc.gotoAndStop ('off');
		this.searchbar_mc.searchMc.gotoAndStop (3);
		this.searchbar_mc.searchMyFav.gotoAndStop (3);
		this.setPage (0);
		this.setPages (0);
		// search text input
		//this.searchbar_mc.filter_ti.editable = true;
		ExternalInterface.addCallback ("doSave", this, doSave);
		this.drag_mc.paging_mc.removeEventListener ("onPageClick", mx.utils.Delegate.create (this, onPageClick));
		this.drag_mc.paging_mc.addEventListener ("onPageClick", mx.utils.Delegate.create (this, onPageClick));
	}
	private function dohideAutoSuggest () : Void
	{
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		//	this.save_mc.onRelease = undefined;
		//	this.save_mc.gotoAndStop('off');
	}
	private function disableSave () : Void
	{
		this.doStatus (0, 'disabling save?');
		//	this.save_mc.onRelease = undefined;
		//	this.save_mc.gotoAndStop('off');
	}
	private function enableSave () : Void
	{
		this.doStatus (0, 'enabling save btn?');
		//	this.save_mc.gotoAndStop('up');
		//	this.save_mc.onRelease = Delegate.create(this, doSave);
	}
	/**
	* Turn off scrolling controls
	*/
	private function disableScrollBtns () : Void
	{
		this.drop_mc.scroll_l_mc.enabled = false;
		this.drop_mc.scroll_r_mc.enabled = false;
		this.drop_mc.scroll_delete_mc.enabled = false;
		this.drop_mc.scrollbar_mc.enabled = false;
	}
	/**
	* Turn on scrolling controls
	*/
	private function enableScrollBtns () : Void
	{
		this.drop_mc.scroll_l_mc.enabled = true;
		this.drop_mc.scroll_r_mc.enabled = true;
		this.drop_mc.scroll_delete_mc.enabled = true;
		this.drop_mc.scrollbar_mc.enabled = true;
	}
	public function startScrollLeft () : Void
	{
		delete _root.onEnterFrame;
		_root.onEnterFrame = function ()
		{
			_root.mainRef.doScroll ('left');
		}
	}
	public function startScrollRight () : Void
	{
		delete _root.onEnterFrame;
		_root.onEnterFrame = function ()
		{
			_root.mainRef.doScroll ('right');
		}
	}
	public function endScroll () : Void
	{
		//old //new trace(" end scroll called");
		delete _root.onEnterFrame;
		_root.onEnterFrame = undefined;
		Selection.setFocus (_root.tempButton);
		this.drop_mc.scroll_delete_mc.gotoAndStop (1);
	}
	public function doScrollDeleteOver () : Void
	{
		this.drop_mc.scroll_delete_mc.gotoAndStop (2);
	}
	public function doScrollDeleteOut () : Void
	{
		this.drop_mc.scroll_delete_mc.gotoAndStop (1);
	}
	/**
	* Handle scrollbar press - start drag
	*/
	private function startScrollDrag () : Void
	{
		// get bounds
		var xmin = this.drop_mc.scroll_bg_mc._x + 2;
		var xmax = (this.drop_mc.scroll_bg_mc._x + this.drop_mc.scroll_bg_mc._width) - this.drop_mc.scrollbar_mc._width - 2;
		var olnY = this.drop_mc.scrollbar_mc._y;
		this.drop_mc.scrollbar_mc.startDrag (false, xmin, olnY, xmax, olnY);
		this.drop_mc.onMouseMove = Delegate.create (this, doScroll);
	}
	/**
	* Handle scrollbar release - end drag
	*/
	private function endScrollDrag () : Void
	{
		// get bounds
		this.drop_mc.scrollbar_mc.stopDrag (false);
		delete this.drop_mc.onMouseMove;
	}
	/**
	* Update scrollbar size and position.
	*/
	private function updateScrollbar () : Void
	{
		// determine size, scale and apply
		var backgroundWidth : Number = this.drop_mc.drop_bg_mc._width;
		var inPlanWidth : Number = this.drop_mc.holder_mc._width;
		// scale = (backgroundWidth / inPlanWidth) * 100
		var size_pct = Math.ceil ((backgroundWidth / inPlanWidth) * 100);
		// 658 = maximum this.drop_mc.scroll_bg_mc._width
		if (size_pct > 100) size_pct = 100;
		this.drop_mc.scrollbar_mc._xscale = size_pct;
		// enable buttons if necessary
		if (size_pct < 100) this.enableScrollBtns ();
		this.doStatus (0, 'Set scrollbar xscale to ' + size_pct + ' =(' + backgroundWidth + ' / ' + inPlanWidth + ')*100');
	}
	public function doScroll (dir) : Void
	{
		//new trace ("doscroll fun" + this.drop_mc.holder_mc._x);
		//new trace ("left : " + ((dir == 'left') && (this.drop_mc.holder_mc._x < 18)));
		//new trace ("right : " + ((dir == 'right') && ((this.drop_mc.holder_mc._width ) > (Math.abs (this.drop_mc.holder_mc._x) + 650))));
		////old //new trace("doScroll called " + dir );
		////old //new trace("this.drop_mc.holder_mc._width :: " + this.drop_mc.holder_mc._width);
		////old //new trace("this.drop_mc.holder_mc._x :: " + this.drop_mc.holder_mc._x)
		//var newX = this.drop_mc.holder_mc._x;
		//var doScrollUpdate = true;
		if ((dir == 'left') && (this.drop_mc.holder_mc._x < 18))
		{
			// scroll left
			//newX = this.drop_mc.holder_mc._x + 115;
			this.drop_mc.holder_mc._x = this.drop_mc.holder_mc._x + 115;
			this.drop_mc.holder2_mc._x = this.drop_mc.holder2_mc._x + 115;
			this.drop_mc.scroll_r_mc.gotoAndStop (1);
			if (this.drop_mc.holder_mc._x >= 18)
			{
				this.drop_mc.holder_mc._x = 18;
				this.drop_mc.holder2_mc._x = 18;
				this.drop_mc.scroll_l_mc.gotoAndStop (2);
			}
		} 
		else if ((dir == 'right') && ((this.drop_mc.holder_mc._width ) > (Math.abs (this.drop_mc.holder_mc._x) + 650)))
		{
			// scroll right
			//newX = this.drop_mc.holder_mc._x - 115;
			this.drop_mc.holder_mc._x = this.drop_mc.holder_mc._x - 115;
			this.drop_mc.holder2_mc._x = this.drop_mc.holder2_mc._x - 115;
			this.drop_mc.scroll_l_mc.gotoAndStop (1);
			if (this.drop_mc.holder_mc._width < (Math.abs (this.drop_mc.holder_mc._x) + 650))
			{
				this.drop_mc.holder_mc._x = - (this.drop_mc.holder_mc._width - 674);
				this.drop_mc.holder2_mc._x = - (this.drop_mc.holder_mc._width - 674);
				this.drop_mc.scroll_r_mc.gotoAndStop (2);
			}
		}
		if (this._active_drag_mc != undefined) this._active_drag_mc._x = this._active_drag_mc._parent._xmouse - 50;
		//this.drop_mc.holder_mc._x  = newX;
		/*
		else
		{
		// called from scrollbar move
		doScrollUpdate = false;
		var minScrollX = this.drop_mc.scroll_bg_mc._x + 2;
		var currentScrollX = this.drop_mc.scrollbar_mc._x - minScrollX;
		var maxScrollX = this.drop_mc.scroll_bg_mc._width - this.drop_mc.scrollbar_mc._width + 4;
		var r = currentScrollX / maxScrollX;
		//this.doStatus(1,'r='+r);
		var minPlanX = this.drop_mc.drop_bg_mc._width - this.drop_mc.holder_mc._width;
		var maxPlanX = 2;
		newX = - ((maxPlanX - minPlanX) * r);
		}
		//this.doStatus(0, 'moving drop holder to '+newX);
		if (newX != this.drop_mc.holder_mc._x)
		{
		if (this._active_drag_mc != undefined)
		{
		// get position from parent scope
		var old_dragX = new Object ();
		old_dragX.x = this._active_drag_mc._x;
		old_dragX.y = this._active_drag_mc._y;
		this._active_drag_mc.localToGlobal (old_dragX);
		}
		// move drop holder
		this.drop_mc.holder_mc._x = newX;
		// also move treatment clips in plan
		this.drop_mc.holder2_mc._x = newX;
		// if dragging from plan move active clip back
		
		if (doScrollUpdate)
		{
		// update scrollbar position
		var newScrollX = this.drop_mc.scroll_bg_mc._x + ((0 - (this.drop_mc.holder_mc._x / this.drop_mc.holder_mc._width)) * (this.drop_mc.drop_bg_mc._width));
		if (newScrollX == this.drop_mc.scroll_bg_mc._x) newScrollX += 2;
		if ((newScrollX + this.drop_mc.scrollbar_mc._width) > (this.drop_mc.scroll_bg_mc._x + this.drop_mc.scroll_bg_mc._width)) newScrollX = this.drop_mc.scroll_bg_mc._x + this.drop_mc.scroll_bg_mc._width - this.drop_mc.scrollbar_mc._width - 2;
		this.drop_mc.scrollbar_mc._x = newScrollX;
		}
		}
		*/
	}
	public function populateFilterCats () : Void
	{
		this.searchbar_mc.cat1_top_cb.addItem ( 
		{
			data : 0, label : ' None'
		});
		searchbar_mc.cat1_top_cb.setStyle ("selectionColor", "0xffffff");
		this.searchbar_mc.cat2_top_cb.addItem ( 
		{
			data : 0, label : ' None'
		});
		this.searchbar_mc.cat1_sub_cb.addItem ( 
		{
			data : 0, label : '<<< choose a top category'
		});
		this.searchbar_mc.cat2_sub_cb.addItem ( 
		{
			data : 0, label : '<<< choose a top category'
		});
		this._cat_xml = new XML ();
		this._cat_xml.ignoreWhite = true;
		this._cat_xml.onLoad = Delegate.create (this, handleCatXml);
		/*
		* @Modify by :E5149, Hytech Professionals.
		* @Date : 27-jun-08
		* old code : this._cat_xml.load(this._xml_cat_url);
		*/
		////old //new trace("\n this._xml_cat_url  :: " + this._xml_cat_url +'&r='+Math.floor(Math.random()*10000));
		this._cat_xml.load (this._xml_cat_url + '&r=' + Math.floor (Math.random () * 10000));
		//-------End--of--
		
	}
	function handleCatXml (ok : Boolean) : Void
	{
		////old //new trace("\n handleCatXml :: " + this._cat_xml);
		if (ok && this._cat_xml.firstChild.nodeName == 'cats') // && this._cat_xml.status != undefined && this._cat_xml.status == 0 )
		{
			var populated_sub = false;
			//this.doStatus(0,this._cat_xml.toString());
			for (var i = 0; i < this._cat_xml.firstChild.childNodes.length; i ++) // loop through top cats
			{
				var curnode = this._cat_xml.firstChild.childNodes [i];
				if (curnode.attributes.id && curnode.attributes.cname)
				{
					// add to top level combo box
					this.doStatus (0, 'adding ' + curnode.attributes.cname + 'to top CBs');
					this.searchbar_mc.cat1_top_cb.addItem ( 
					{
						data : curnode.attributes.id, label : curnode.attributes.cname
					});
					this.searchbar_mc.cat2_top_cb.addItem ( 
					{
						data : curnode.attributes.id, label : curnode.attributes.cname
					});
					this._cat [curnode.attributes.id] = {
						cname : curnode.attributes.cname, children : new Object ()
					};
					// loop through children
					for (var j = 0; j < curnode.childNodes.length; j ++)
					{
						var subnode = curnode.childNodes [j];
						if (subnode.attributes.id && subnode.attributes.cname)
						{
							this._cat [curnode.attributes.id].children [subnode.attributes.id] = subnode.attributes.cname;
						}
					} // end children
					populated_sub = true;
				}
			} // end top
			// sort tops
			this.searchbar_mc.cat1_top_cb.sortItemsBy ("label", Array.CASEINSENSITIVE);
			this.searchbar_mc.cat2_sub_cb.sortItemsBy ("label", Array.CASEINSENSITIVE);
			// enable CB's
			this.searchbar_mc.cat1_top_cb.enabled = true;
			this.searchbar_mc.cat1_sub_cb.enabled = true;
			this.searchbar_mc.cat2_top_cb.enabled = true;
			this.searchbar_mc.cat2_sub_cb.enabled = true;
			this.doSubChange ();
		} 
		else
		{
			this.doError (3, 'Failed Loading Categories');
			this.doError (0, 'from ' + this._xml_cat_url);
		}
	}
	/**
	* Handle top category combobox change
	*/
	private function doTopChange (id : Number) : Void
	{
		// locate target combobox
		//_root.debug.txt.text += "\n doTopChange :: ";
		var cb = eval ('this.searchbar_mc.cat' + id + '_top_cb');
		// create container for top category id
		var top_id : Number = 0;
		// locate child combobox
		var sub_cb = eval ('this.searchbar_mc.cat' + id + '_sub_cb');
		if (cb != undefined && sub_cb != undefined)
		{
			// get selected data
			var top_id = cb.selectedItem ['data'];
			this.doStatus (1, 'Changed Top CB ' + id + ' to ' + top_id);
			// clear items in sub combobox
			sub_cb.removeAll ();
			// add "none" item
			sub_cb.addItem ( 
			{
				data : 0, label : ' None'
			});
			// look for sub-categories
			if (this._cat [top_id].children)
			{
				for (var i in this._cat [top_id].children)
				{
					// add sub-categories to sub combobox
					sub_cb.addItem ( 
					{
						data : i, label : this._cat [top_id].children [i]
					});
				}
				// sort items in sub
				sub_cb.sortItemsBy ("label", Array.CASEINSENSITIVE);
			}
		} 
		else this.doError (2, 'Failed handling category change.');
		// if changed to "None"
		if (cb.selectedIndex == 0)
		{
			// change sub to more informative title
			sub_cb.removeAll ();
			sub_cb.addItem ( 
			{
				data : 0, label : '<<< choose a top category'
			});
			// disable item in other top combobox
			var other_id = (id == 1) ? 2 : 1;
			var other_cb = eval ('this.searchbar_mc.cat' + other_id + '_top_cb');
			//_root.debug.txt.text += "\n cb.selectedIndex != 0 ";
			this.doTopUpdate (other_cb, top_id);
			//this.drag_mc.total_txt.text=0;
			// get new treatments
			this.getTreatments ();
		} 
		else
		{
			// disable item in other top combobox
			var other_id = (id == 1) ? 2 : 1;
			var other_cb = eval ('this.searchbar_mc.cat' + other_id + '_top_cb');
			//_root.debug.txt.text += "\n cb.selectedIndex != 0 ";
			this.doTopUpdate (other_cb, top_id);
		}
	}
	private function doTopUpdate (cb : ComboBox, id : Number)
	{
		//_root.debug.txt.text += "\n cb "+cb;
		//_root.debug.txt.text += "\n id "+id;
		if (cb)
		{
			////old //new trace('Updating top '+cb);
			// save selected data if not "None"
			var old_selection = false;
			if (cb.selectedItem.data)
			{
				// get old selectionn
				////old //new trace('found old selection '+cb.selectedItem.data);
				old_selection = cb.selectedItem.data;
			}
			// remove items
			cb.removeAll ();
			cb.addItem ( 
			{
				data : 0, label : ' None'
			});
			// loop through top cats
			for (var i in this._cat)
			{
				// add cats not selected in top
				if (i != id)
				{
					cb.addItem ( 
					{
						data : i, label : this._cat [i].cname
					});
				}
			}
			// sort items
			cb.sortItemsBy ("label", Array.CASEINSENSITIVE);
			// loop through new ordered items looking for old selection
			for (var i = 1; i < cb.length; i ++)
			{
				if (cb.getItemAt (i).data == old_selection)
				{
					// restore old selection
					cb.selectedIndex = int (i);
				}
			}
			this.doSubChange ();
		}
	}
	/**
	* Look at the filter selections and load treatmets for the drag area (if necessary).
	* Called on ComboBox changes.
	*/
	private function getTreatments () : Void
	{
		// create shortcuts
		var top1_cb = this.searchbar_mc.cat1_top_cb;
		var top2_cb = this.searchbar_mc.cat2_top_cb;
		var sub1_cb = this.searchbar_mc.cat1_sub_cb;
		var sub2_cb = this.searchbar_mc.cat2_sub_cb;
		//clear drag area
		this.clearDragArea ();
		// check for 2 empty tops and empty text input
		//if(top1_cb.selectedItem['data'] == 0 && top2_cb.selectedItem['data'] == 0 && this.searchbar_mc.filter_ti.length == 0)
		if (top1_cb.selectedItem ['data'] == 0 && top2_cb.selectedItem ['data'] == 0)
		{
			// do nothing
			this.doStatus (2, 'not getting treatments (tops empty) (' + top1_cb.selectedItem ['data'] + ')(' + top2_cb.selectedItem ['data'] + ')');
			// reset paging
			this.resetPage ();
		}
		// check for 2 empty subs and empty text input
		//else if (sub1_cb.selectedItem['data'] == 0 && sub2_cb.selectedItem['data'] == 0 && this.searchbar_mc.filter_ti.length == 0)
		else if (sub1_cb.selectedItem ['data'] == 0 && sub2_cb.selectedItem ['data'] == 0)
		{
			// do nothing
			this.doStatus (2, 'not getting treatments (subs empty)');
			// reset paging
			this.resetPage ();
		} 
		else
		{
			// otherwise start building request URL
			//_root.debug.txt.text+="\n this._page_current"+ this._page_current
			var treatment_url = this._treatement_source_url + '&p=' + this._page_current;
			if (top1_cb.selectedItem ['data'] > 0 && sub1_cb.selectedItem ['data'] > 0)
			{
				// add sub category 1
				treatment_url += '&sub_id[]=' + sub1_cb.selectedItem ['data'];
			}
			if (top2_cb.selectedItem ['data'] > 0 && sub2_cb.selectedItem ['data'] > 0)
			{
				// add sub category 2
				treatment_url += '&sub_id[]=' + sub2_cb.selectedItem ['data'];
			}
			if (this.searchString.length >= 0)
			{
				// add test filter
				//treatment_url += '&s=' + escape(this.searchbar_mc.filter_ti.text);
				treatment_url += '&s=' + escape (this.searchString) + '&mode=' + _mode;
			}
			// update feedback
			this.searchbar_mc.feedback_mc.gotoAndStop ('off');
			this.searchbar_mc.feedback_mc.gotoAndStop ('on');
			// reset paging
			this.resetPage ();
			// load treatments from modified _treatement_source_url
			this.doStatus (3, 'Loading treatments...');
			this.doStatus (0, 'from ' + treatment_url);
			////old //new trace("\n treatment_url  :: " + treatment_url +'&r='+Math.floor(Math.random()*10000));
			this._treatment_xml.load (treatment_url + '&r=' + Math.floor (Math.random () * 10000));
			//-------End--of--
			
		}
		//if(this.searchbar_mc.filter_ti.length > 0)
		//{
		//this.enableClearBtn();
		//}
	}
	private function doSubChange () : Void
	{
		// get new items
		this.getTreatments ();
	}
	private function resetPage () : Void
	{
		this.setPage (0);
		this.setPages (0);
	}
	private var myArray : Array = new Array ();
	private function ltrim (matter)
	{
		if ((matter.length > 1) || (matter.length == 1 && matter.charCodeAt (0) > 32 && matter.charCodeAt (0) < 255))
		{
			var i = 0;
			while (i < matter.length && (matter.charCodeAt (i) <= 32 || matter.charCodeAt (i) >= 255))
			{
				i ++;
			}
			matter = matter.substring (i);
		} 
		else
		{
			matter = "";
		}
		return matter;
	}
	/****************************************************************************************************************/
	/*
	/ @Modification Date : 3-feb-2009
	/ @Reasone : Issue fixing with auto suggection feature
	/ @author : E5149,  HyTech Professionals.
	*/
	/****************************************************************************************************************/
	private function doTextChange () : Void
	{
		_root.searchbar_mc.filter_ti.text = com.classes.core.Utility.leftTrim (_root.searchbar_mc.filter_ti.text);
		textvalue = com.classes.core.Utility.rightTrim (this.searchbar_mc.filter_ti.text);
		var min_search_length = 2;
		if ((textvalue.length > min_search_length ) && (this.searchbar_mc.filter_ti.text != ""))
		{
			_root.AutoComplete.removeAll ()
			this.mylv.ignoreWhite = true;
			this.mylv.onLoad = function ()
			{
				var sa : Array = [];
				var attr = this.firstChild.attributes.r;
				//old //new trace("\n\n " + attr +" attr =================== requestId" + requestId);
				//old //new trace("\n mylv.firstChild.childNodes ==>" + this);
				if (com.classes.core.Utility.rightTrim (this.searchbar_mc.filter_ti.text).length < 3)
				{
					this.disableSearchBtn ();
				} 
				else
				{
					this.enableSearchBtn ();
				}
				if (requestId == - 1)
				{
					_root.AutoComplete.removeAll ()
					_root.AutoComplete.setSize (0, 0);
					_root.preloader._visible = false;
					_root.leftcap._visible = true;
					_root.AutoComplete._visible = _root.locker_mc._visible = false;
					_root.AutoComplete.vScrollPolicy = "off";
					return;
				}
				if (attr < requestId)
				{
					_root.AutoComplete.removeAll ()
					_root.AutoComplete.setSize (0, 0);
					_root.preloader._visible = true;
					_root.leftcap._visible = false;
					_root.AutoComplete._visible = _root.locker_mc._visible = false;
					_root.AutoComplete.vScrollPolicy = "off";
					return;
				}
				if (attr == requestId || attr > requestId)
				{
					this.onLoad = undefined;
					_root.AutoComplete.removeAll ()
					_root.AutoComplete.setSize (0, 0);
					_root.preloader._visible = false;
					_root.leftcap._visible = true;
					_root.AutoComplete._visible = _root.locker_mc._visible = false;
					_root.AutoComplete.vScrollPolicy = "off";
					sa = this.firstChild.childNodes;
					var height_slider : Number = (Number (sa.length) == 1) ? 22 : Number (sa.length) * 18.2;
					if (sa.length > 10)
					{
						_root.AutoComplete.removeAll ()
						_root.AutoComplete.setSize (302, 178);
						_root.AutoComplete.vScrollPolicy = "on";
					} 
					else if ((sa.length == 0) || (sa.length == undefined))
					{
						_root.AutoComplete.removeAll ()
						_root.AutoComplete.setSize (0, 0);
						_root.preloader._visible = false;
						_root.leftcap._visible = true;
						_root.AutoComplete._visible = _root.locker_mc._visible = false;
						_root.AutoComplete.vScrollPolicy = "off";
					} 
					else if ((sa.length != 0) && (sa.length <= 10))
					{
						_root.AutoComplete.removeAll ()
						_root.AutoComplete.setSize (302, height_slider);
						_root.AutoComplete.vScrollPolicy = "off";
					}
					var l : Number;
					l = (com.classes.core.Utility.rightTrim (_root.searchbar_mc.filter_ti.text)).length;
					if (l < 3)
					{
						_root.AutoComplete.removeAll ();
						_root.AutoComplete._visible = _root.locker_mc._visible = false;
						_root.AutoComplete.vScrollPolicy = "off";
					} 
					else
					{
						_root.AutoComplete._visible = _root.locker_mc._visible = true;
						for (var i = 0; i < sa.length; i ++)
						{
							_root.AutoComplete.addItem (sa [i].firstChild.nodeValue);
						}
					}
					//old //new trace(" mylv.onLoad :: " +  this.onLoad)
					delete this.onLoad;
					this.onLoad = undefined;
					delete this;
					_root.preloader._visible = false;
					_root.leftcap._visible = true;
					//old //new trace("delete  mylv.onLoad :: " +  this.onLoad)
				}
				/*else
				{
				_root.preloader._visible = false;
				_root.leftcap._visible = true;
				_root.AutoComplete._visible = _root.locker_mc._visible = false;
				_root.AutoComplete.vScrollPolicy = "off";
				
				}
				*/
				//End if if (attr == requestId)
				
			}
			page_url + '&s=' + escape (ltrim (this.searchbar_mc.filter_ti.text));
			requestId ++;
			/****************************************************************************************************************/
			/*
			/ @Modification Date : 4-feb-2009
			/ @Reasone : to make auto suggection feature disable
			/ @author : E5149,  HyTech Professionals.
			/ @Note : uncomment below commentd lines to make auto suggection feature enable
			
			/*
			this.mylv.load (page_url + '&s=' + escape (ltrim (this.searchbar_mc.filter_ti.text)) + '&r=' + requestId);
			_root.preloader._visible = true;
			_root.leftcap._visible = false;
			*/
			/****************************************************************************************************************/
			this.enableSearchBtn ();
		} 
		else if (this.searchbar_mc.filter_ti.text.length <= min_search_length)
		{
			_root.AutoComplete._visible = _root.locker_mc._visible = false;
			_root.AutoComplete.vScrollPolicy = "off";
			this.disableSearchBtn ();
		}
	}
	/****************************************************************************************************************/
	/****************************************************************************************************************/
	private function handleTagXml ()
	{
		//old //new trace ("hello");
		
	}
	//--------------------------------------------------------------------
	private function enableSearchBtn () : Void
	{
		this.doStatus (0, 'enabling search btn');
		//this.searchbar_mc.search_btn_mc.gotoAndStop('search');
		//this.searchbar_mc.search_btn_mc.btn1.enabled = true;
		this.searchbar_mc.searchMc.enabled = true;
		this.searchbar_mc.searchMyFav.enabled = true;
		this.searchbar_mc.searchMc.gotoAndStop (1);
		this.searchbar_mc.searchMyFav.gotoAndStop (1);
		this.searchbar_mc.searchMc.onRelease = Delegate.create (this, doTextFilter1);
		this.searchbar_mc.searchMyFav.onRelease = Delegate.create (this, doTextFilter2);
	}
	private function disableSearchBtn () : Void
	{
		//this.searchbar_mc.search_btn_mc.gotoAndStop('search');
		//this.searchbar_mc.search_btn_mc.btn_mc.gotoAndStop('off');
		_root.AutoComplete.removeAll ()
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		_root.AutoComplete.vScrollPolicy = "off";
		this.searchbar_mc.searchMc.gotoAndStop (3);
		this.searchbar_mc.searchMyFav.gotoAndStop (3);
		this.searchbar_mc.searchMc.enabled = false;
		this.searchbar_mc.searchMyFav.enabled = false;
		_root.preloader._visible = false;
		_root.leftcap._visible = true;
		//this.searchbar_mc.search_btn_mc.btn_mc.enabled = false;
		
	}
	private function enableClearBtn () : Void
	{
		this.searchbar_mc.search_btn_mc.gotoAndStop ('clear');
		this.searchbar_mc.search_btn_mc.btn_mc.enabled = true;
		this.searchbar_mc.search_btn_mc.btn_mc.onRelease = Delegate.create (this, undoTextFilter);
	}
	private function doTextFilter1 () : Void
	{
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		this.searchString = this.searchbar_mc.filter_ti.text;
		_mode = 1;
		if (this.searchbar_mc.filter_ti.text.length >= 3)
		{
			this._page_current = 0;
			this.getTreatments ();
		}
		requestId = - 1;
		_root.preloader._visible = false;
		_root.leftcap._visible = true;
		//this.drag_mc.paging_mc["page"+1+"_mc"]["togal_mc"].gotoAndStop(2);
		//this.drag_mc.paging_mc["page"+1+"_mc"]["togal_mc"].onRelease();
		this.drag_mc.paging_mc.setPage1on ();
	}
	private function doTextFilter2 () : Void
	{
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		this.searchString = this.searchbar_mc.filter_ti.text;
		_mode = 2;
		if (this.searchbar_mc.filter_ti.text.length >= 3)
		{
			this._page_current = 0;
			this.getTreatments ();
		}
		requestId = - 1;
		_root.preloader._visible = false;
		_root.leftcap._visible = true;
		//this.drag_mc.paging_mc["page"+1+"_mc"]["togal_mc"].gotoAndStop(2);
		//this.drag_mc.paging_mc["page"+1+"_mc"]["togal_mc"].onRelease();
		this.drag_mc.paging_mc.setPage1on ();
	}
	private function doTextFilter3 () : Void
	{
		requestId = - 1;
		_root.preloader._visible = false;
		_root.leftcap._visible = true;
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		this.searchbar_mc.filter_ti.text = "";
		this.disableSearchBtn ();
		this.searchString = "";
		_mode = 3;
		/*if(this.searchbar_mc.filter_ti.text.length > 3)
		{*/
		this._page_current = 0;
		this.getTreatments ();
		//}
		//this.drag_mc.paging_mc["page"+1+"_mc"]["togal_mc"].gotoAndStop(2);
		//this.drag_mc.paging_mc["page"+1+"_mc"]["togal_mc"].onRelease();
		this.drag_mc.paging_mc.setPage1on ();
	}
	private function undoTextFilter () : Void
	{
		//this.searchbar_mc.filter_ti.text = '';
		this.disableSearchBtn ();
		this.resetPage ();
		this.getTreatments ();
	}
	private function clearDragArea () : Void
	{
		for (var i = 0; i < this._drag_treatments.length; i ++)
		{
			for (var j = 0; j < 6; j ++)
			{
				if (this._drag_treatments [i][j])
				{
					//this.doStatus(0,'removing '+this._drag_treatments[i][j]._name);
					this._drag_treatments [i][j].removeMovieClip ();
					this.drag_mc.total_txt.text = 0;
				}
			}
		}
		this._drag_treatments = new Array ();
	}
	private function getCurrentSubcat () : Number
	{
		return this.searchbar_mc.cat1_sub_cb.selectedItem ['data'];
	}
	private function getPlanTreatments () : Void
	{
		this._plan_xml = new XML ();
		this._plan_xml.ignoreWhite = true;
		this._plan_xml.onLoad = Delegate.create (this, handlePlanXml);
		//old //new trace ("\n this._plan_treatment_url :: " + this._plan_treatment_url + '&id=' + this._plan_id + '&r=' + Math.floor (Math.random () * 10000));
		this._plan_xml.load (this._plan_treatment_url + '&id=' + this._plan_id + '&r=' + Math.floor (Math.random () * 10000));
	}
	private function getNextPage () : Void
	{
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		Selection.setFocus (_root.dummy_btn);
		//_root.debug.txt.text+="this._page_currentgetNextPage"+this._page_current;
		//_root.debug.txt.text+="this._page_current"+this._page_total;
		if (Number (this._page_current) < Number (this._page_total))
		{
			this.setPage (1 + Number (this._page_current));
			//_root.debug.txt.text+="this._page_current";
		} 
		else this.setPage (Number (this._page_total));
		clearDragArea ();
		this.getTreatments ();
	}
	private function getPrevPage () : Void
	{
		_root.AutoComplete._visible = _root.locker_mc._visible = false;
		Selection.setFocus (_root.dummy_btn);
		if (Number (this._page_current) > 1)
		{
			this.setPage (Number (this._page_current) - 1);
		} 
		else this.setPage (1);
		clearDragArea ();
		this.getTreatments ();
	}
	private function setPage (newPage : Number) : Void
	{
		var page : Number = 1;
		if (newPage != undefined) page = newPage;
		this._page_current = Number (page);
		this.drag_mc.page_current_txt.text = this._page_current.toString ();
	}
	function onPageClick (evtObj : Object)
	{
		//old //new trace("function onPageClick execute :" + evtObj.data);
		this._page_current = evtObj.data;
		this.getTreatments ();
	}
	private function setPages (newPages : Number) : Void
	{
		this._page_total = newPages;
		this.drag_mc.page_total_txt.text = this._page_total.toString ();
		/*
		//this.paging_mc.page_next_mc.n_btn.onRelease = Delegate.create (this, getNextPage);
		*/
		if (this._page_total == 0)
		{
			// disable previous page button
			this.drag_mc.page_prev_mc.n_btn.onRelease = undefined;
			this.drag_mc.page_prev_mc.n_btn.enabled = false;
			this.drag_mc.page_prev_mc.gotoAndStop ('off');
			// disable next page button
			this.drag_mc.page_next_mc.n_btn.onRelease = undefined;
			this.drag_mc.page_next_mc.n_btn.enabled = false;
			//_root.debug.txt.text += "\n 749  this.drag_mc.page_next_mc.n_btn.enabled = false :: " + this.drag_mc.page_next_mc.n_btn.enabled;
			this.drag_mc.page_next_mc.gotoAndStop ('off');
		}
	}
	private function setTotal (newTotal : Number) : Void
	{
		var testTotal = 0;
		this.drag_mc.total_text.text = 0;
		if (newTotal)
		{
			testTotal = newTotal;
		}
		this.drag_mc.total_txt.text = testTotal;
		this.drag_mc.paging_mc.init (testTotal);
		//this.drag_mc.paging_mc.removeEventListener("onPageClick", mx.utils.Delegate.create(this,onPageClick));
		
	}
	private function getCurrentPage () : Number
	{
		return this._page_current;
	}
	private function handleTreatmentXml (ok : Boolean) : Void
	{
		//new trace ("\n handleTreatmentXml :: " + this._treatment_xml);
		this.clearDragArea ();
		//_root.//old //new trace_text.text += "\n handleTreatmentXml :: ";
		//_root.debug.txt.text += "\n handleTreatmentXml :: " + this._treatment_xml;
		if (ok && this._treatment_xml.firstChild.nodeName == 'treatments')
		{
			// hanle paging setup
			this.setPage (this._treatment_xml.firstChild.attributes.page);
			this.setPages (this._treatment_xml.firstChild.attributes.of);
			this.setTotal (this._treatment_xml.firstChild.attributes.total);
			//_root.//old //new tracebox_text.text = "\n LINE1-->total :: " + this._treatment_xml.firstChild.attributes.total;
			//_root.//old //new tracebox_text.text += "\n LINE2:: "+ this._treatment_xml;
			this.updateScrollbar ();
			if (this._page_current > 1)
			{
				// enable previous page button
				this.drag_mc.page_prev_mc.n_btn.onRelease = Delegate.create (this, getPrevPage);
				this.drag_mc.page_prev_mc.n_btn.enabled = true;
				this.drag_mc.page_prev_mc.gotoAndStop ('on');
			} 
			else
			{
				// disable previous page button
				this.drag_mc.page_prev_mc.n_btn.onRelease = undefined;
				this.drag_mc.page_prev_mc.n_btn.enabled = false;
				this.drag_mc.page_prev_mc.gotoAndStop ('off');
			}
			//_root.debug.txt.text += "\n Number(this._page_current) < Number(this._page_total) :: " + Number(this._page_current) +" ----"+ Number(this._page_total);
			if (Number (this._page_current) < Number (this._page_total))
			{
				// enable next page button
				this.drag_mc.page_next_mc.n_btn.onRelease = Delegate.create (this, getNextPage);
				//drag_mc.page_next_mc.n_btn.selection.setFocus();
				this.drag_mc.page_next_mc.n_btn.enabled = true;
				//_root.debug.txt.text += "\n 802  this.drag_mc.page_next_mc.n_btn.enabled = true :: " + this.drag_mc.page_next_mc.n_btn.enabled.toString();
				this.drag_mc.page_next_mc.gotoAndStop ('on');
			} 
			else
			{
				// disable next page button
				this.drag_mc.page_next_mc.n_btn.onRelease = undefined;
				this.drag_mc.page_next_mc.n_btn.enabled = false;
				//_root.debug.txt.text += "\n 811  this.drag_mc.page_next_mc.n_btn.enabled = false :: " + this.drag_mc.page_next_mc.n_btn.enabled;
				this.drag_mc.page_next_mc.gotoAndStop ('off');
			}
			this.doStatus (1, 'got treatment page: ' + this._treatment_xml.toString ());
			for (var i = 0; i < this._treatment_xml.firstChild.childNodes.length; i ++) // loop through treatments
			{
				var curnode = this._treatment_xml.firstChild.childNodes [i];
				if (curnode.nodeName == 'treatment')
				{
					//this.doStatus(0,'found node: '+curnode.attributes.tname);
					var t = this.addTreatment (curnode.attributes.id, curnode.attributes.tname, curnode.attributes.pic);
					if (t)
					{
						this.doStatus (0, 'added treatment ' + t._name);
					} 
					else
					{
						this.doError (3, 'Failed adding treatment ' + curnode.attributes.tname);
						this.doError (0, 'Failed adding treatment ' + curnode.attributes.tname + '(' + t + ')');
					}
				}
			}
		} 
		else
		{
			this.doError (3, 'Search Failed');
			this.doError (0, 'Failed loading treatment XML. ' + this._treatment_xml.status + 'reply - ' + this._treatment_xml.toString ());
		}
		// update feedback
		this.searchbar_mc.feedback_mc.gotoAndStop ('off');
	}
	private function handlePlanXml (ok : Boolean) : Void
	{
		//old //new trace ("\n handlePlanXml :: " + this._plan_xml);
		if (ok && this._plan_xml.firstChild.nodeName == 'plan')
		{
			// make sure we have enough drop targets
			var max_order = this._plan_xml.firstChild.childNodes.length;
			if (this._drops.length < max_order)
			{
				var addOrders = max_order - this._drops.length;
				for (var i = 0; i < addOrders + 2; i ++) this.addDropTarget (); 
			}
			for (var i = 0; i < max_order; i ++)
			{
				// get treatment parameters
				var p = new Object ();
				p._id = this._plan_xml.firstChild.childNodes [i].attributes.id;
				p._tname = this._plan_xml.firstChild.childNodes [i].attributes.tname;
				p._main = this;
				p._name = "drop" + this._plan_xml.firstChild.childNodes [i].attributes.order + "_mc";
				if (this._plan_xml.firstChild.childNodes [i].attributes.pic) p._image_url = this._plan_xml.firstChild.childNodes [i].attributes.pic;
				// create a new treatment outside paging area
				// old way - use attachMovie
				//var t = this.drop_mc.holder2_mc.attachMovie("Treatment.mc", p._name, this.drop_mc.holder2_mc.getNextHighestDepth(), p);
				// new way - manage depths with DepthManager
				var t = this.drop_mc.holder2_mc.createChildAtDepth ("Treatment.mc", DepthManager.kTop, p);
				// validate and continue
				if (t != undefined)
				{
					// add it the the plan
					this.addTreatment2Plan (t, this._plan_xml.firstChild.childNodes [i].attributes.order, true);
					this.drop_mc.holder_mc._x = 18;
					this.drop_mc.holder2_mc._x = 18;
					this.drop_mc.scroll_l_mc.gotoAndStop (2);
					if (this.drop_mc.holder_mc._width > 600)
					{
						this.drop_mc.scroll_r_mc.gotoAndStop (1);
					} 
					else
					{
						this.drop_mc.scroll_r_mc.gotoAndStop (2);
					}
				} 
				else
				{
					// DepthManager is screwy
					this.doError (3, 'Failed adding treatment from loaded plan.');
				}
			}
			this.doStatus (2, 'Loaded Plan Info.');
		} 
		else this.doStatus (3, 'Failed Loading Plan Info.');
	}
	/**
	* Create drop target at next order.
	*/
	private function addDropTarget () : Void
	{
		//old //new trace("@@@@@@@@@@@@@@@ addDropTarget :: " + addDropTarget);
		// find next order
		var order = 1;
		if (this._drops.length) order = this._drops.length;
		// postition new clip
		var xpos = ((order - 1) * 115) + 5;
		var p = new Object ();
		p._x = xpos;
		p._y = 5;
		// old way - use attachMovie
		//var dt = this.drop_mc.holder_mc.attachMovie("dropTarg", 'dt'+order+'_mc', this.drop_mc.holder_mc.getNextHighestDepth(), p);
		// new way - manage depths with DepthManager
		var dt = this.drop_mc.holder_mc.createChildAtDepth ("dropTarg", DepthManager.kTop, p);
		// validate object
		if (dt)
		{
			dt.addProperty ('order');
			dt.order = order;
			dt.order_txt.text = order;
			this._drops [order] = dt;
			this.doStatus (1, 'added droptarget ' + dt + ' @ (' + xpos + ', 5) making ' + (this._drops.length - 1) + ' drops total');
			//this.updateScrollbar ();
			
		} 
		else
		{
			this.doError (1, 'Failed adding drop target. (' + order + ')');
		}
		if (order > 5)
		{
			this.drop_mc.holder_mc._x = - (this.drop_mc.holder_mc._width - 674);
			this.drop_mc.holder2_mc._x = - (this.drop_mc.holder_mc._width - 674);
		}
		this.drop_mc.scroll_r_mc.gotoAndStop (2);
		if (this.drop_mc.holder_mc._width > 600) this.drop_mc.scroll_l_mc.gotoAndStop (1);
		else this.drop_mc.scroll_l_mc.gotoAndStop (2);
	}
	public function addTreatment (id : Number, tname : String, image_url : String) : Treatment
	{
		if (id && tname)
		{
			var p = new Object ();
			p._id = id;
			p._tname = tname;
			p._main = this;
			if (image_url)
			{
				//this.doStatus(0,'found pic '+image_url+' for '+tname);
				p._image_url = image_url;
			}
			// find position for new treatment
			var padding : Number = 5;
			// find correct row
			var row : Number = this._drag_treatments.length;
			if (row > 1) row = row - 1;
			if ( ! row || this._drag_treatments [row][5])
			{
				row += 1;
				//this.doStatus(0,'new row '+row);
				this._drag_treatments [row] = new Object ();
			}
			/* Modify Date : 19-Feb-2009 */
			//p._y = (padding * row) + ((row - 1) * 75);
			p._y = (padding * row) + ((row - 1) * 120);
			// find column
			var col = 0;
			for (var c : Number = 0; c < 6; c ++)
			{
				if ( ! this._drag_treatments [row][c]) // == undefined || this._drag_treatments[row][col] == 'undefined')
				{
					col = c;
					p._x = ((padding * 3) * (col + 1)) + ((col) * 100);
					break;
				}
				//else this.doStatus(0,'found previous item @ '+row+', '+col+' ('+this._drag_treatments[row][col]+')');
			}
			p._row = row;
			p._col = col;
			//this.doStatus(0,'adding item @ '+row+', '+col);
			// old way
			//this._drag_treatments[row][col] = this.drag_mc.holder_mc.attachMovie("Treatment.mc", "treatment"+p._id+"_mc", this.drag_mc.holder_mc.getNextHighestDepth(), p);
			// new way
			this._drag_treatments [row][col] = this.drag_mc.holder_mc.createChildAtDepth ("Treatment.mc", DepthManager.kTop, p);
			return this._drag_treatments [row][col];
		} 
		else this.doError (2, 'Failed adding treatment id: ' + id + ' named: ' + tname);
	}
	private function deleteTreatment (t : Treatment) : Void
	{
		//new trace ("deleteTreatment fun called " + t);
		if (t != undefined)
		{
			t.removeMovieClip ();
		}
		//new trace ("After deleteTreatment fun called " + t);
	}
	public function copyTreatment (t : Treatment) : Treatment
	{
		var p = new Object ();
		p._id = t.getId ();
		p._tname = t.getName ();
		p._main = this;
		p._image_url = t.getImageUrl ();
		p._coper = t;
		p._x = t._x;
		p._y = t._y;
		var row = t.getRow ();
		var col = t.getCol ();
		p._row = row;
		p._col = col;
		// old way
		//this._drag_treatments[row][col] = this.drag_mc.holder_mc.attachMovie("Treatment.mc", "treatment"+p._id+"_copy_mc", this.drag_mc.holder_mc.getNextHighestDepth(), p);
		// new way
		this._drag_treatments [row][col] = this.drag_mc.holder_mc.createChildAtDepth ("Treatment.mc", DepthManager.kTop, p);
		return this._drag_treatments [row][col];
	}
	// treatment button handlers - moved to Treatment class
	public function doTreatmentPress (t : Treatment, m : String) : Void
	{
		if (m == 'plan')
		{
			this._active_drag_mc = t;
			this._active_drag_mc.setDepthTo (DepthManager.kTop);
		}
	}
	public function doTreatmentRelease (t : Treatment, m : String) : Void
	{
		this._active_drag_mc = undefined;
	}
	/**
	* See where dragged Treatment falls
	*/
	public function evalDrop (drag_mc : Treatment) : Void
	{
		this.endScroll();
		if (drag_mc)
		{
			// clear last overs
			this.endDropHilite ();
			drag_mc.last_over_mc.endDragOver ();
			drag_mc.last_over_other_mc.endDragOver ();
		}
		var dt : String = drag_mc._droptarget;
		if (dt.indexOf ('/drop_mc') == - 1)
		{
			// invalid drop - outside drop clip
			// delete
			//new trace ("drag_mc._is_in_plan :: " + drag_mc._is_in_plan);
			//@e5149/this.deleteTreatment (drag_mc);
			if ( ! drag_mc._is_in_plan)
			{
				//new trace ("delete it");
				this.deleteTreatment (drag_mc);
			} 
			else
			{
				//new trace ("reset pos");
				drag_mc._x = drag_mc._oldX;
				drag_mc._y = drag_mc._oldY;
			}
		} 
		else
		{
			// drop on dynamic plan drop targets
			// find order
			if (dt.indexOf ('holder2_mc') != - 1)
			{
				this.doStatus (2, 'Drop on full target ');
				var trigger_full = '/drop_mc/holder2_mc/depthChild';
				var start_plan_targ_full = trigger_full.length;
				var end_plan_targ_full = dt.indexOf ('/', start_plan_targ_full - 1);
				if (end_plan_targ_full == - 1) end_plan_targ_full = dt.length;
				var plan_order = dt.substring (start_plan_targ_full, end_plan_targ_full);
				var plan_dir = dt.substr (end_plan_targ_full + 1, 1);
				if (plan_order)
				{
					// found order, get target clip
					var targ_mc = eval (trigger_full + plan_order);
					if (targ_mc)
					{
						// over valid plan, get real plan order
						if (targ_mc.getPlanOrder ()) plan_order = targ_mc.getPlanOrder ();
						// other drop
						var first_reordered_mc = false;
						if (plan_dir == 'R')
						{
							plan_order ++;
							first_reordered_mc = this.getTreatmentByOrder (int (plan_order));
						} 
						else if (plan_dir == 'L')
						{
							first_reordered_mc = this.getTreatmentByOrder (int (plan_order));
						}
						if (first_reordered_mc)
						{
							this.doStatus (2, 'Over full plan ' + plan_dir + ' ' + plan_order + '(' + start_plan_targ_full + ',' + end_plan_targ_full + ')');
							// found drop and other
							// cascade orders
							this.cascadeOrders (first_reordered_mc);
							this.addTreatment2Plan (drag_mc, plan_order);
						}
						// this part should be checked
						else
						{
							//new trace ("plan_order :: " + plan_order);
							//new trace ("plan_dir :: " + plan_dir);
							//new trace ("fun evaldrop drop on holder2 " + drag_mc);
							//new trace ("dt :: " + dt );
							if (plan_dir != 'l') this.addTreatment2Plan (drag_mc, plan_order);
						}
					} 
					else this.doError (2, 'Failed making dragover target: ' + trigger_full + plan_order);
				} 
				else this.doError (2, 'Failed getting dropped plan order ' + dt + ' (' + start_plan_targ_full + ', ' + end_plan_targ_full + ')');
			} 
			else if (dt.indexOf ('holder_mc') != - 1)
			{
				// dropped on blank drop order clip
				var trig : String = '/depthChild';
				// text in name triggering valid drop
				// look for trigger
				var string_start : Number = dt.indexOf (trig);
				if (string_start > - 1)
				{
					// set start after trigger
					string_start += trig.length;
					// look at next 4 characters
					var char0 : String = dt.charAt (string_start);
					var char1 : String = dt.charAt (string_start + 1);
					var char2 : String = dt.charAt (string_start + 2);
					var char3 : String = dt.charAt (string_start + 3);
					// found trigger - look for end of order
					if (char1 == '/' || char1 == '')
					{
						// one digit
						var string_end : Number = string_start + 1;
					} 
					else if (char2 == '/' || char2 == '')
					{
						// two digits
						var string_end : Number = string_start + 2;
					} 
					else if (char3 == '/' || char3 == '')
					{
						// that alot of treatments
						var string_end : Number = string_start + 3;
					} 
					else
					{
						this.doError (2, 'couldn\'t find end of droptarget order');
					}
					this.doStatus (0, 'Next DT chars=' + char0 + char1 + char2 + char3);
				} 
				else
				{
					// dropped in holder, outside targets?
					this.doError (2, 'dropped in holder, outside targets?');
				}
			}
			/************************************************************************/
			/************************************************************************/
			if (dt.indexOf ('scroll_delete_mc') != - 1)
			{
				//new trace ("inside eval drop evalDrop fun 1472 this treatment is going to delete : " + dt);
				// drop on delete button
				this.deleteTreatment (drag_mc);
				this.drop_mc.scroll_delete_mc.gotoAndStop (1);
			}
			if (string_start > - 1 && string_end > - 1)
			{
				// valid drop - add to plan
				var test_order = Number (dt.substring (string_start, string_end)) + 1;
				this.doStatus (2, 'Valid drop in plan at order ' + test_order);
				this.addTreatment2Plan (drag_mc, test_order);
			} 
			else
			{
				//new trace ("ELSE where delete all drag clip");
				//new trace ("drag_mc._is_in_plan :: " + drag_mc._is_in_plan);
				if ( ! drag_mc._is_in_plan)
				{
					//new trace ("delete it");
					this.deleteTreatment (drag_mc);
					drag_mc.removeMovieClip ();
				} 
				else
				{
					//new trace ("reset pos");
					drag_mc._x = drag_mc._oldX;
					drag_mc._y = drag_mc._oldY;
				}
			}
		}
	}
	private function addTreatment2Plan (t : Treatment, order : Number, noSave : Boolean) : Void
	{
		//new trace ("addTreatment2Plan fun (" + t + "   " + order + "    " + noSave + ")");
		var treatment_id = t.getId ();
		this.doStatus (0, 'adding treatment ' + treatment_id + ' to plan @ ' + order);
		if ( ! treatment_id || ! order) return;
		if ( ! this._inPlan [order])
		{
			// add treatment to drop tile
			var d = eval ('this.drop_mc.holder_mc.dt' + order + '_mc');
			// drop target
			var p = new Object ();
			p._id = treatment_id;
			p._tname = t.getName ();
			p._image_url = t.getImageUrl ();
			p._main = this;
			p._x = 10 + (115 * (order - 1));
			p._y = 10;
			// old way
			//var level = this.drop_mc.holder2_mc.getNextHighestDepth();
			//this._inPlan[order] = this.drop_mc.holder2_mc.attachMovie("Treatment.mc", "drop"+order+"_mc", level, p);
			// new way
			this._inPlan [order] = this.drop_mc.holder2_mc.createChildAtDepth ("Treatment.mc", DepthManager.kTop, p);
			this._inPlan [order].isInPlan (order);
			t.removeMovieClip ();
			// add new drop target if needed
			/*
			* @Modify by :E5149
			* @Date : 29-09-07
			* old code	//if(order >= (this._drops.length - 1)) this.addDropTarget();
			*/
			while ((this._drops.length - 1) <= Number (order)) this.addDropTarget ();
			//-------End--of--
		} 
		else
		{
			// treatment exists at this order - replace
			this.doStatus (0, 'replacing existing treatment at order ' + order + '(' + this._inPlan [order] + ')');
			this.clearOrder (order);
			this.addTreatment2Plan (t, order);
		}
		if ( ! noSave)
		{
			// enable save button
			this.enableSave ();
		}
	}
	private function cascadeOrders (start_mc : Treatment)
	{
		if (start_mc.getPlanOrder ())
		{
			var first_order = start_mc.getPlanOrder ();
			var first_empty = 0;
			//old //new trace ('starting cascade from ' + first_order);
			// find first empty higher order
			for (var i = first_order; i < this._inPlan.length + 1; i ++)
			{
				//old //new trace ('lookin in plan @ ' + i);
				if ( ! this._inPlan [i])
				{
					//old //new trace ('found first empty @ ' + i);
					first_empty = i;
					i = Number.MAX_VALUE;
				}
				//else //new trace ('full of ' + this._inPlan [i].getName ());
				
			}
			this.doStatus (2, 'Starting cascade from ' + first_order + ' to ' + first_empty);
			if (first_order < first_empty)
			{
				// start with highest order and move down
				for (var i = first_empty - 1; i > first_order - 1; i --)
				{
					if (this._inPlan [i])
					{
						//old //new trace ('Cascade clear ' + this._inPlan [i + 1].getName () + ' move ' + this._inPlan [i].getName () + ' from ' + i + ' to ' + i + 1);
						this.clearOrder (i + 1);
					} 
					else this.doStatus (3, 'Cascade empty from ' + i + ' to ' + i + 1);
					this.addTreatment2Plan (this._inPlan [i] , i + 1);
				}
			}
			//else //new trace ('no cascade needed');
			
		}
	}
	public function startDropHilite (order : Number) : Void
	{
		if (this._drops [order] && this._drops [order]._currentframe == 1)
		{
			//old //new trace ('hiliting drop ' + order);
			this._drops [order].gotoAndStop (2);
		} 
		else this.doError (2, 'Failed hiliting drop ' + order);
	}
	public function endDropHilite () : Void
	{
		//old //new trace ('ending drop hilites');
		for (var i = 1; i < this._drops.length; i ++)
		{
			if (this._drops [i]._currentframe != 1)
			{
				//old //new trace ('ended ' + i);
				this._drops [i].gotoAndStop (1);
			}
		}
	}
	public function clearDropOrder (t : Treatment) : Void
	{
		this.doStatus (0, 'clearing drop order for ' + t.getName ());
		for (var i = 1; i < this._inPlan.length; i ++)
		{
			if (this._inPlan [i] == t)
			{
				this.doStatus (0, 'clearing treatment from order ' + i);
				this._inPlan [i] = undefined;
			}
		}
	}
	private function clearOrder (n : Number)
	{
		if (this._inPlan [n]) this._inPlan [n].removeMovieClip ();
		this._inPlan [n] = undefined;
	}
	private function getTreatmentById (id : Number) : Treatment
	{
		if ( ! id) return;
		for (var r = 1; r <= this._drag_treatments.length; r ++)
		{
			for (var c : Number = 0; c < 6; c ++)
			{
				if (this._drag_treatments [r][c].getId () == id) return this._drag_treatments [r][c];
			}
		}
	}
	public function getTreatmentByOrder (order : Number) : Treatment
	{
		return this._inPlan [order];
	}
	public function doSave () : Void
	{
		var save_xml = new XML ();
		save_xml.docTypeDecl = "<?xml version='1.0' standalone='yes'?>";
		var top_node = save_xml.createElement ('plan');
		var info_node = save_xml.createElement ('info');
		save_xml.appendChild (top_node);
		info_node.attributes.id = this._plan_id;
		top_node.appendChild (info_node);
		for (var i in this._inPlan)
		{
			if (i && this._inPlan [i])
			{
				this.doStatus (0, 'found order ' + i);
				var t_node = save_xml.createElement ('treatment');
				t_node.attributes.order = i;
				t_node.attributes.id = this._inPlan [i].getId ();
				top_node.appendChild (t_node);
			}
		}
		// update feedback
		this.searchbar_mc.feedback_mc.gotoAndStop ('on_save');
		this.doStatus (2, 'Saving Plan Data...');
		//this.doStatus(0, 'XML='+save_xml);
		this.reply_xml = new XML ();
		this.reply_xml.ignoreWhite = true;
		this.reply_xml.onLoad = Delegate.create (this, didSave);
		//old //new trace ("\n this._save_url :: " + this._save_url + '?r=' + Math.floor (Math.random () * 10000));
		save_xml.sendAndLoad (this._save_url + '?r=' + Math.floor (Math.random () * 10000) , this.reply_xml);
		this.disableSave ();
	}
	private function didSave (success : Boolean) : Void
	{
		if (success && this.reply_xml.firstChild.nodeName == "reply" && this.reply_xml.firstChild.attributes.ok == 'true')
		{
			this.doStatus (2, 'Data Saved.');
			ExternalInterface.call ("didSave");
		} 
		else this.doStatus (3, 'Failed saving data. (' + this.reply_xml + ')');
		// update feedback
		this.searchbar_mc.feedback_mc.gotoAndStop ('off');
	}
	public function doStatus (priority : Number, msg : String)
	{
		////old //new trace('MSG (' + priority + ') - ' + msg);
		switch (priority)
		{
			case 3 :
			//old //new trace ('IMPORTANT STATUS: ' + msg);
			case 2 :
			this.clearStatus ();
			//this.viewer_mc.feedback_txt.htmlText = '<font color="#66FF66">'+msg+'</font>';
			//this._feedback_interval = setInterval(this, 'clearStatus', 2500);
			case 1 :
			if (priority >= _global.debug_level && priority != 3)
			{
				//old //new trace ('STATUS(' + priority + '): ' + msg);
				
			}
			case 0 :
			// ignore
			default :
			break;
		}
	}
	public function doError (priority : Number, msg : String)
	{
		////old //new trace('ERROR (' + priority + ') - ' + msg);
		switch (priority)
		{
			case 3 :
			//old //new trace ('IMPORTANT ERROR: ' + msg);
			//this.disableButtons();
			//	this.clearStatus();
			//	this.viewer_mc.feedback_txt.htmlText = '<font color="#FF6666"><b>'+msg+'<b></font>';
			//this._feedback_interval = setInterval(this, 'clearStatus', 5000);
			break;
			case 2 :
			this.clearStatus ();
			//	this.viewer_mc.feedback_txt.htmlText = '<font color="#FF6666">'+msg+'</font>';
			//	this._feedback_interval = setInterval(this, 'clearStatus', 2500);
			case 1 :
			if (priority >= _global.debug_level && priority != 3)
			{
				//old //new trace ('ERROR(' + priority + '): ' + msg);
				
			}
			case 0 :
			// ignore
			default :
			break;
		}
	}
	public function doNothing () : Void
	{
		return;
	}
	public function clearStatus () : Void
	{
		//this.doStatus(1, 'Cleared Status');
		//this.viewer_mc.feedback_txt.htmlText = '';
		//clearInterval(this._feedback_interval);
	}
}
