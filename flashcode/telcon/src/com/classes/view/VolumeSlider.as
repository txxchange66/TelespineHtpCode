package com.classes.view{
	import flash.display.*;
	import flash.events.*;
	import flash.geom.*;
	import flash.display.Sprite;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;

	public class VolumeSlider extends MovieClip {
		private var moving:Boolean;
		private var rectangle:Rectangle;
		private var motion:Boolean;
		private var myField:TextField;


		private var _labelEnabled:Boolean;

		[Inspectable(name="labelEnabled",type=Boolean,defaultValue="true")]
		public function set labelEnabled(val:Boolean) {

			this._labelEnabled=val;
		}
		public function get labelEnabled():Boolean {

			return this._labelEnabled;
		}

		public function VolumeSlider() {

			moveSlider();
			slider_mc.buttonMode=true;
			myField = new TextField();
			myField.autoSize=TextFieldAutoSize.LEFT;
			myField.selectable=false;
			myField.x=70;
			myField.y=0;
			slider_mc.x=50;
			myField.text =(slider_mc.x).toString();
			this.addChild(myField);
			myField.visible=this.labelEnabled;
		}
		private function moveSlider() {
			slider_mc.addEventListener(MouseEvent.MOUSE_DOWN,startSliderMoving);
			stage.addEventListener(MouseEvent.MOUSE_UP, stopSliderMoving);
			stage.addEventListener(MouseEvent.MOUSE_OUT, stopSliderMoving);
			sliderbar_mc.addEventListener(MouseEvent.CLICK,onClick);
			motion=false;
			rectangle=new Rectangle(0,0,49,0);
		}
		private function startSliderMoving(e:Event):void {

			myField.visible=this.labelEnabled;

			slider_mc.startDrag(false, rectangle);
			motion=true;
			slider_mc.addEventListener(Event.ENTER_FRAME, changeVolume);
			return;
		}

		private function onClick(e:Event):void {
			slider_mc.x=sliderbar_mc.mouseX;
			changeVolume(e);
		}
		private function stopSliderMoving(e:Event):void {
			if (motion) {
				slider_mc.stopDrag();
				slider_mc.removeEventListener(Event.ENTER_FRAME, changeVolume);
				motion=false;
			}
		}
		private function changeVolume(e:Event):void {
			myField.text = (slider_mc.x).toString();
			MovieClip(this.parent.parent).onVolumeChange(Number(myField.text)*2);

		}
	}
}