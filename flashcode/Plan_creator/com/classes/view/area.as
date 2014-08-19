class com.classes.view.area extends com.classes.core.SuperObject {
	private var __type:String;
	private var __name:String;
	private var __cards:Array;
	private var __topx:Number;
	private var __topy:Number;
	private var __Enabled:Boolean;
	private var __SelectedCard:MovieClip;
	private var bottom_mc:MovieClip;

	public function get cards():Array {
		return __cards;
	}
	public function set cards(val:Array) {
		__cards = val;
	}
	[Inspectable(defaultValue="temp")]
	public function get Type():String
	{
		return __type;
	}
	public function set Type(val:String) {
		__type = val;
	}
	[Inspectable(defaultValue="area_mc")]
	public function get Name () : String
	{
		return __name;
	}
	public function set Name(val:String) {
		__name = val;
	}
	[Inspectable(defaultValue="false")]
	public function get Enabled () : Boolean
	{
		return __Enabled;
	}
	public function set Enabled(val:Boolean) {
		this.__cards[this.__cards.length-1].isActive = val;
		this.__Enabled = val;
	}
	public function get selectedCard():MovieClip {
		return this.__SelectedCard;
	}
	public function set selectedCard(cardObj:MovieClip) {
		// pop this card form game selected cards
		for (var indx = 0; indx<this._parent.selectedCards.length; indx++) {
			if (this._parent.selectedCards[indx]._name == this.selectedCard._name) {
				this._parent.selectedCards[indx].deSelectMe();
				this._parent.selectedCards.splice(indx,1);
			}
		}
		this.__SelectedCard = cardObj;
		this.__SelectedCard.selectMe();
		//push this card form game selected cards
		this._parent.selectedCards.push(this.selectedCard);
		//this._parent.checkIsMakingTotal13();
		this._parent.oncardSelect();
	}
	public function area() {
		this.Refresh();
		__cards = new Array();
		if (this.Type == "restCloseCards") {
			//this.bottom_mc.useHandCursor = false;
			this.bottom_mc.onRelease = function() {
				this._parent._parent.swapAreaOfCards(this._parent._parent.rest_open_cards_pile_mc,this._parent);
			};
		}
	}
	public function Refresh() {
		this.__topx = this._x;
		this.__topy = this._y;
	}
	public function addcards(cardsArray:Array) {
		this.selectedCard = undefined;
		for (var i = 0; i<cardsArray.length; i++) {
			if (this.Type == "temp") {
				cardsArray[i]._x = __topx;
				cardsArray[i]._y = __topy;
				__topy = __topy+2;
			} else {
				cardsArray[i]._x = __topx;
				cardsArray[i]._y = __topy;
				//__topx = __topx + 0;
				//__topy = __topy + 0;
			}
			if (this.Type == "restCloseCards") {
				cardsArray[i].isFrontView = false;
			}
			this.cards.push(cardsArray[i]);
			cardsArray[i].areaRef = this;
			cardsArray[i]._visible = true;
		}
	}
	public function deselectAllCards() {
		trace(" deselect all cards "+this.cards.length);
		for (var ac = 0; ac<this.cards.length; ac++) {
			if (this.cards[ac].isSelected) {
				this.cards[ac].isSelected = false;
			}
		}
	}
	public function removecards(cardsArray:Array) {
		for (var c = 0; c<cardsArray.length; c++) {
			for (var ac = 0; ac<this.cards.length; ac++) {
				if (this.cards[ac]._name == cardsArray[c]._name) {
					this.cards.splice(ac,1);
				}
			}
		}
	}
	public function clearAllCards(cardsArray:Array) {
		this.Refresh();
		this.cards = new Array();
	}
}