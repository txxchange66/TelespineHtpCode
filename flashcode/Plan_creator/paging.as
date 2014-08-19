class paging extends com.classes.core.SuperObject {
	var page1_mc:Button;
	var page2_mc:Button;
	var page3_mc:Button;
	var page4_mc:Button;
	var page5_mc:Button;
	var total_txt:TextField;
	var page_next_mc:MovieClip;
	var page_prev_mc:MovieClip;
	var totalResults_txt:TextField;
	var counter:Number;
	var pagecount:Number;
	var currentActivePage:MovieClip;
	var pagingButtonClicked:Boolean;
	public var _result:Number;
	public function paging() {
		page_next_mc._visible = page_next_mc.enabled=false;
		page_prev_mc._visible = page_prev_mc.enabled=false;
		for (var i = 1; i<=5; i++) {
			this["page"+i+"_mc"]["togal_mc"]["pageno"] = "";
			this["page"+i+"_mc"]["togal_mc"].enabled = false;
		}
		this.pagingButtonClicked = false;
	}
	function init(val) {
		// first time //

		if (this.pagingButtonClicked == true) {
			return;
		}
		this._visible = this.enabled=true;
		_result = val;
		total_txt.text = _result.toString();

		for (var i = 1; i<=5; i++) {
			this["page"+i+"_mc"]["togal_mc"]["pageno"] = "";
			this["page"+i+"_mc"]["togal_mc"].enabled = false;
		}
		pagecount = _result/18;

		if (pagecount>4) {
			counter = 5;
		} else {
			counter = pagecount;

		}
		if (pagecount-(Math.floor(pagecount))>0) {
			counter = counter+1;
			pagecount = Math.floor(pagecount)+1;
		}
		for (var i = 1; i<=counter; i++) {
			this["page"+i+"_mc"]["togal_mc"]["pageno"] = i;
			this["page"+i+"_mc"]["togal_mc"].enabled = true;
			this["page"+i+"_mc"]["togal_mc"].onRelease = function() {
				for (var j = 1; j<=5; j++) {
					this._parent._parent["page"+j+"_mc"]["togal_mc"].gotoAndStop(1);
				}
				this.gotoAndStop(2);
				this._parent._parent.currentActivePage = this._parent;
				this._parent._parent.dispatchEvent({type:"onPageClick", target:this, identifier:"PAGING", data:this["pageno"]});
				this._parent._parent.pagingButtonClicked = true;


				/************************************************************************************/
				if (Number(this._parent._parent.currentActivePage["togal_mc"]["pageno"])>1) {
					this._parent._parent.page_prev_mc._visible = this._parent._parent.page_prev_mc.enabled=true;

				}
				if ((Number(this._parent._parent.currentActivePage["togal_mc"]["pageno"]))<this._parent._parent.pagecount) {
					this._parent._parent.page_next_mc._visible = this._parent._parent.page_next_mc.enabled=true;

				}
				if (Number(this._parent._parent.currentActivePage["togal_mc"]["pageno"])<2) {
					this._parent._parent.page_prev_mc._visible = this._parent._parent.page_prev_mc.enabled=false;

				}
				if (Number(this._parent._parent.currentActivePage["togal_mc"]["pageno"])>=this._parent._parent.pagecount) {
					this._parent._parent.page_next_mc._visible = this._parent._parent.page_next_mc.enabled=false;

				}
				/************************************************************************************/ 
			};
		}
		// on next click //

		page_next_mc.onRelease = mx.utils.Delegate.create(this, getNextPage);
		page_prev_mc.onRelease = mx.utils.Delegate.create(this, getPrevPage);

		page_prev_mc._visible = page_prev_mc.enabled=false;
		if (pagecount<2) {
			page_next_mc._visible = page_next_mc.enabled=false;
		} else {
			page_next_mc._visible = page_next_mc.enabled=true;
		}
	}
	function setPage1on() {
		this._visible = this.enabled=false;
		for (var j = 1; j<=5; j++) {
			this["page"+j+"_mc"]["togal_mc"].gotoAndStop(1);
		}
		this["page"+1+"_mc"]["togal_mc"].gotoAndStop(2);
		Selection.setFocus(_root.tempButton);
		this.pagingButtonClicked = false;
		this.currentActivePage = this["page"+1+"_mc"];
	}
	function getNextPage() {
		if (this["page"+5+"_mc"]["togal_mc"]["pageno"]<pagecount) {
			for (var i = 1; i<=5; i++) {
				this["page"+i+"_mc"]["togal_mc"]["pageno"] = Number(this["page"+i+"_mc"]["togal_mc"]["pageno"])+1;
			}
			this.currentActivePage["togal_mc"].onRelease();
		} else {
			this["page"+((Number((currentActivePage._name).substr(4, 1)))+1)+"_mc"]["togal_mc"].onRelease();

		}
		if (this.currentActivePage["togal_mc"]["pageno"]>=pagecount) {
			page_next_mc._visible = page_next_mc.enabled=false;
		}
		if (currentActivePage["togal_mc"]["pageno"]>1) {
			page_prev_mc._visible = page_prev_mc.enabled=true;
		}

	}
	function getPrevPage() {
		if (this["page"+1+"_mc"]["togal_mc"]["pageno"]>1) {
			for (var i = 1; i<=5; i++) {
				this["page"+i+"_mc"]["togal_mc"]["pageno"] = Number(this["page"+i+"_mc"]["togal_mc"]["pageno"])-1;
			}
			this.currentActivePage["togal_mc"].onRelease();
		} else {
			this["page"+((Number((currentActivePage._name).substr(4, 1)))-1)+"_mc"]["togal_mc"].onRelease();
		}
		if (this.currentActivePage["togal_mc"]["pageno"]<pagecount) {
			page_next_mc._visible = page_next_mc.enabled=true;
		}
		if (currentActivePage["togal_mc"]["pageno"] == 1) {
			page_prev_mc._visible = page_prev_mc.enabled=false;
		}

	}
}