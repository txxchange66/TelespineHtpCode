class com.classes.view.wildcards extends com.classes.core.SuperObject {
	var count_txt:TextField;
	var IsWildCardActive:Boolean;
	public function wildcards() {
		this.cardscount = 1;
		this.onRollOver = function() {
			this.gotoAndPlay(2);
		};
		this.onRollOut = function() {
			this.gotoAndStop(1);
		};
		this.onRelease = this.onReleaseOutside=function () {
			this.gotoAndStop(1);
			this.enabled = false;
			this.IsWildCardActive = true;
			this._parent.current_cur_mc._visible = false;
			this._parent.current_cur_mc = this._parent.wild_cur_mc;
			this._parent.current_cur_mc._visible = true;
			this._parent.onMouseMove = this._parent._onMouseMove;
		};
	}
	public function get cardscount():Number {
		return Number(count_txt.text);
	}
	public function set cardscount(val:Number) {
		if(val > 0)
		{
			this.enabled = true;
		}
		this._parent.wildcards_mc.IsWildCardActive = false;
		count_txt.text = val.toString();
		this._parent.current_cur_mc._visible = false;
		this._parent.current_cur_mc = this._parent.hand_cur_mc;
		this._parent.current_cur_mc._visible = true;
		delete this._parent.onMouseMove;
		Mouse.show();
	}
}