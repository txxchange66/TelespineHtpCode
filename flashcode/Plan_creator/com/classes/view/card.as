import com.classes.view.area;
class com.classes.view.card extends com.classes.core.SuperObject {
	private var _rank:String;
	private var _suit:String;
	private var _index:Number;
	private var _isFrontView:Boolean;
	private var _isActive:Boolean;
	private var _removed:Boolean;
	private var _isSelected:Boolean;
	private var _btn:Button;
	private var rank_txt:String;
	private var suit_txt:TextField;
	private var _areaRef:area;
	private var backview_mc:MovieClip;
	private var bottom_rank_mc:MovieClip;
	private var card_selected_mc:MovieClip;
	private var card_rollover_mc:MovieClip;
	private var frontFaceHolder_mc:MovieClip;
	private var __x:Number;
	private var __y:Number;
	public function get index():Number {
		return _index;
	}
	public function set index(val:Number) {
		_index = val;
	}
	public function get areaRef():area {
		return _areaRef;
	}
	public function set areaRef(areaObj:area) {
		_areaRef = areaObj;
	}
	
	public function get removed():Boolean {
		return this._removed;
	}
	public function set removed(val:Boolean) {
		this._removed = val;
		this._visible = !this._removed;
	}
	
	public function get isActive():Boolean {
		return _isActive;
	}
	public function set isActive(val:Boolean) {
		this._isActive = val;
		if (this.areaRef.Type == "temp")
			{
				if(this.isFrontView != this._isActive)this.flip();
				//this.useHandCursor = (this._isActive)?true:false;
			}
			//if(this.areaRef.Type == "restOpenCards")this.useHandCursor = true;
			//trace(this._isActive + " | isActive :: " + this.useHandCursor);
	}
	public function get isSelected():Boolean {
		return this._isSelected;
	}
	public function set isSelected(val:Boolean) {
		if (val) {
			this.selectMe();
			this.areaRef.selectedCard = this;
		} else {
			this.deSelectMe();
		}
	}
	public function selectMe() {
		this._isSelected = true;
		this.card_selected_mc._visible = true;
	}
	public function deSelectMe() {
		this._isSelected = false;
		this.card_selected_mc._visible = false;
	}
	public function set isFrontView(val:Boolean) {
		this._isFrontView = val;
		this.backview_mc._visible = !_isFrontView;
	}
	public function get isFrontView():Boolean {
		return _isFrontView;
	}
	public function get rank():String {
		return _rank;
	}
	public function set rank(val:String) {
		_rank = val;
	}
	public function get rankNum():Number {
		if (this._rank == "A") {
			return 1;
		}
		if (this._rank == "J") {
			return 11;
		}
		if (this._rank == "Q") {
			return 12;
		}
		if (this._rank == "K") {
			return 13;
		}
		return Number(this._rank);
	}
	public function get suit():String {
		return _suit;
	}
	public function set suit(val:String) {
		this._suit = val;
	}
	//"Card object consractor!"
	public function card() {
		init();
	}
	public function _onPress() {
		if (this._isActive)
			if (this.isFrontView)
			{
				this.startDrag();
				this._parent._parent.bringToFront(this);
				this.__x = this._x;
				this.__y = this._y;
			}
		}
	public function _onRollOver() {
		if (this._isActive) {
			this.card_rollover_mc._visible = true;
		}
	}
	public function _onRollOut() {
		this.card_rollover_mc._visible = false;
	}
	public function _onRelease() {
		//trace("_onRelease :: this._isActive ::" + this._isActive);
		this.stopDrag();
		this._x = this.__x;
		this._y = this.__y;
	}
	public function _onReleaseOutside() {
		this._onRelease();
	}
	public function loadfrontImage() {
		var attacedface = this.frontFaceHolder_mc.attachMovie(this._name, this._name, 0);
	}
	public function init() {
		this._isActive = false;
		this.loadfrontImage();
		this.isFrontView = false;
		this._removed = false;
		this.useHandCursor = false;
		this.isSelected = false;
		this.card_rollover_mc._visible = false;
		/*
		this._btn.onPress = mx.utils.Delegate.create(this, this._onPress);
		this._btn.onRelease = mx.utils.Delegate.create(this, this._onRelease);
		this._btn.onReleaseOutside = mx.utils.Delegate.create(this, this._onReleaseOutside);
		this._btn.onRollOver = mx.utils.Delegate.create(this, this._onRollOver);
		this._btn.onRollOut = mx.utils.Delegate.create(this, this._onRollOut);
		*/
		this.onPress = mx.utils.Delegate.create(this, this._onPress);
		this.onRelease = mx.utils.Delegate.create(this, this._onRelease);
		this.onReleaseOutside = mx.utils.Delegate.create(this, this._onReleaseOutside);
		this.onRollOver = mx.utils.Delegate.create(this, this._onRollOver);
		this.onRollOut = mx.utils.Delegate.create(this, this._onRollOut);
	}
	public function flip() {
		trace("card class flip function ---->"+this.areaRef.Type);
		if (this.areaRef.Type == "restCloseCards") {
			var c_array:Array = new Array();
			c_array.push(this);
			this.areaRef.removecards(c_array);
			this._parent._parent["rest_open_cards_pile_mc"].addcards(c_array);
			this._parent._parent["rest_close_cards_pile_mc"].Enabled = true;
		}
		this.backview_mc._visible = !this.backview_mc._visible;
		this.isFrontView = !this.backview_mc._visible;
		this._parent._parent.bringToFront(this);
	}
}