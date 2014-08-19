package com.classes.view{
	import flash.display.*;
	import flash.text.*;
	import flash.events.*;
	public class controlsbar extends flash.display.MovieClip {
		private var _targetLocalVideo:MovieClip;
		private var _targetLiveVideo:MovieClip;
		private var lastPos:Number;
		function get targetLocalVideo():MovieClip {
			return this._targetLocalVideo;
		}
		function set targetLocalVideo(mc:MovieClip) {
			this._targetLocalVideo=mc;
		}
		function set targetLiveVideo(mc:MovieClip) {
			this._targetLiveVideo=mc;
		}
		function get targetLiveVideo():MovieClip {
			return this._targetLiveVideo;
		}
		function controlsbar() {
			btnShare.addEventListener(MouseEvent.CLICK, videoShare_onClick);
			btnStopShare.addEventListener(MouseEvent.CLICK, videoStopShare_onClick);
			btnSpeeker.addEventListener(MouseEvent.CLICK, speeker_onClick);
		}
		function videoShare_onClick(evt:MouseEvent) {
			if (btnShare.mouseEnabled) {
				(this.parent as MovieClip).shareVideo();
				(this.parent as MovieClip).startTimer();
			}
		}
		function videoStopShare_onClick(evt:MouseEvent) {
			if (btnStopShare.mouseEnabled) {
				(this.parent as MovieClip).stopShareVideo();
				(this.parent as MovieClip).stopTimer();
			}
		}
		function speeker_onClick(evt:MouseEvent) {
			var _mute:Boolean;
			if (btnSpeeker.currentFrame==1) {
				(this.parent as MovieClip).onVolumeChange(0);
				btnSpeeker.gotoAndStop(2);
				_mute=true;
				lastPos=vSlider.slider_mc.x;
				vSlider.slider_mc.x=0;
			} else {
				(this.parent as MovieClip).onVolumeChange((lastPos*2));
				btnSpeeker.gotoAndStop(1);
				_mute=false;
				vSlider.slider_mc.x=lastPos;
			}
		}
		public function onSharing() {
			btnShare.gotoAndStop(2);
			btnStopShare.gotoAndStop(1);
			btnShare.mouseEnabled=false;
			btnStopShare.mouseEnabled=true;
		}
		public function onStopSharing() {
			btnShare.gotoAndStop(1);
			btnStopShare.gotoAndStop(2);
			btnShare.mouseEnabled=true;
			btnStopShare.mouseEnabled=false;
		}
		public function disableControls() {
			btnShare.gotoAndStop(2);
			btnStopShare.gotoAndStop(2);
			btnShare.mouseEnabled=false;
			btnStopShare.mouseEnabled=false;
		}
	}
}