package com.classes.view{
	import com.classes.view.*;
	import flash.events.*;
	import flash.net.*;
	import flash.display.*;
	//import fl.controls.Button;
	import flash.geom.*;
	import flash.media.*;
	import flash.utils.*;
	import fl.motion.MatrixTransformer;
	import flash.external.ExternalInterface;
	public class telecon extends MovieClip {
		private var nc:NetConnection;
		private var good:Boolean;
		//private var rtmpURI:String = "rtmpe://192.168.0.195/telecon/";
		private var rtmpURI:String="rtmpe://zi0hdi07fe.rtmphost.com/telecon/";
		private var nsIn:NetStream;
		private var nsOut:NetStream;
		private var cam:Camera;
		private var mic:Microphone;
		private var liveVideo:Video;
		//private var alert_mc:MovieClip;
		private var pbsns:Array;
		public var localSname;
		public var liveSname;
		private var my_so:SharedObject;
		var minuteTimer:Timer;
		var isHardwareError:Boolean = false;
		var ss:Number;
		var mm:Number;
		var _SS:String;
		var _MM:String;
		public function telecon() {
			controlsbar_mc.disableControls();
			alert_mc.visible = false;
			alert_mc.ok_btn.addEventListener(MouseEvent.CLICK, onOkClick);
			alert_mc._btn.useHandCursor = false;
			setCam();
			this.localSname=loaderInfo.parameters.localSname;
			this.liveSname=loaderInfo.parameters.liveSname;
			makeConnection();
			minuteTimer=new Timer(1000);
			minuteTimer.addEventListener(TimerEvent.TIMER, onTick);
		}
		function onOkClick(evt)
		{
			alert_mc.visible = false;
		}
		function onTick(event:TimerEvent):void {
			if (ss>59) {
				ss=0;
				mm++;
			}
			_SS = (((ss.toString()).length)< 2)?('0'+ss.toString()):ss.toString();
			_MM = (((mm.toString()).length)< 2)?('0'+mm.toString()):mm.toString();
			controlsbar_mc._txt.text = (_MM+":"+_SS);
			ss++;
		}
		public function onSpeekerClick(_mute) {
		}
		public function shareVideo() {
			try {
				controlsbar_mc.onStopSharing();
				if (! nsOut) {
					nsOut=new NetStream(nc);
					nsOut.attachAudio(mic);
					nsOut.attachCamera(cam);
					nsOut.publish(("mp4:"+this.localSname),"live");
				} else {
					nsOut.resume();
				}
				if (my_so) {
					my_so.setProperty(this.localSname,"true");
				}
				nc.call("shareVedio", null, this.localSname);

			} catch (serr) {
			}
			controlsbar_mc.onSharing();
		}
		public function isPublished(_sn):String {
			if (my_so) {
				for (var p in my_so.data) {
					if (_sn==p) {
						return (my_so.data[_sn]);
					}
				}
			}
			return "false";
		}
		private function syncHandler(event:SyncEvent):void {
			for (var att in my_so.data) {
				if ((this.liveSname == att) && (my_so.data[this.liveSname] == "true")) {
					asyncServerCall("VideoRefresh:"+this.liveSname);
				} else if ((this.liveSname == att) && (my_so.data[this.liveSname] == "false")) {
					asyncServerCall("stopShare:"+this.liveSname);
				}
			}
		}
		public function stopShareVideo() {
			if (my_so) {
				my_so.setProperty(this.localSname,"false");
			}
			if (nsOut) {
				nsOut.pause();
			}
			if (nc) {
				nc.call("stopShare", null, this.localSname);
			}
			controlsbar_mc.onStopSharing();
		}
		public function makeConnection() {
			try {
				nc = new NetConnection();
				nc.client=this;
				nc.addEventListener( NetStatusEvent.NET_STATUS, handlerOnNetStatus);
				nc.connect(rtmpURI,this.localSname,this.liveSname);
			} catch (connectErr) {
			}
		}
		public function startTimer() {
			ss=0;
			mm=0;
			_SS='00';
			_MM='00';
			minuteTimer.start();
		}
		public function stopTimer() {
			ss=0;
			mm=0;
			_SS='00';
			_MM='00';
			minuteTimer.stop();
			controlsbar_mc._txt.text = ("00:00");
		}
		private function doSend(e):void {
			var myResponder:Responder=new Responder(handlerResultAsyncClientCall,null);
			var myMsg:String;
			nc.call( "asyncClientCall", myResponder, myMsg );
		}
		public function onVideoStatusChange(evt) {
		}
		public function asyncServerCall(msg:String):String {
			trace("asyncServerCall : ",msg);
			
			if (! my_so) {
				my_so=SharedObject.getRemote("gInfo",nc.uri,false);
				my_so.connect(nc);
				my_so.addEventListener(SyncEvent.SYNC, syncHandler);
			}
			trace("SharedObject : ",my_so.data);
			for (var prp in my_so.data)trace(prp, " :: " ,my_so.data[prp]);
			trace("**************************");
			var arr=msg.split(':');
			if (arr[0]=="VideoRefresh") {
				if ((((arr[1]!= null) && (arr[1] != "null")) && ((arr[1] != undefined) && (arr[1] != "undefined")))) {
					if (this.liveSname==""||this.liveSname==null||this.liveSname=="null"||this.liveSname==undefined||this.liveSname=="undefined") {
						if (this.localSname!=arr[1]) {
							this.liveSname=arr[1];
						}
					}
					if (arr[1]!=this.localSname&&this.liveSname==arr[1]) {
						if (! liveVideo) {
							liveVideo=new Video(320,240);
							liveVideo.smoothing=true;
							liveVideo.x=13;
							liveVideo.y=28;
							var m:Matrix=liveVideo.transform.matrix;
							MatrixTransformer.setSkewY(m, 180);
							liveVideo.transform.matrix=m;
							liveVideo.x=liveVideo.x+liveVideo.width;
							addChild(liveVideo);
						}
						if (!nsIn && (isPublished(arr[1]) == "true")) {
							nsIn=new NetStream(nc);
							nsIn.play("mp4:"+arr[1]);
							liveVideo.attachNetStream(nsIn);
						} else if (isPublished(arr[1]) == "true") {
							liveVideo.clear();
							nsIn.play("mp4:"+arr[1]);
							liveVideo.attachNetStream(nsIn);
						}
					}
				}
			}
			if (arr[0]=="stopShare") {
				if (nsIn) {
					nsIn.close();
				}
				if (nsIn) {
					nsIn=null;
				}
				if (liveVideo) {
					liveVideo.clear();
				}
				if (liveVideo) {
					removeChild(liveVideo);
				}
				if (liveVideo) {
					liveVideo=null;
				}
			}
			return "Hey server, I got your message... Thanks!";
		}
		public function onVolumeChange(val) {
			if (val!=0) {
				controlsbar_mc.btnSpeeker.gotoAndStop(1);
			}
			if (val==0) {
				controlsbar_mc.btnSpeeker.gotoAndStop(2);
			}
			var sT = new SoundTransform((val/100),0);
			if (nsIn!=null) {
				nsIn.soundTransform=sT;
			}
		}
		public function handlerResultAsyncClientCall( result:String ):void {
		}
		private function handlerFaultAsyncClientCall( info:Object ):void {
		}
		public function close():void {
			if (my_so) {
				my_so.setProperty(this.localSname,"false");
			}
			if (nc) {
				nc.call("delStream", null, this.localSname);
			}
		}
		private function handlerOnNetStatus( event:NetStatusEvent ):void {
			var info:Object=event.info;
			trace("NetConnection :: ",info.code);
			switch (info.code) {
				case "NetConnection.Connect.Success" :
					try {
						if(!this.isHardwareError)controlsbar_mc.onStopSharing();
						trace("this.isHardwareError :: ", this.isHardwareError);
						if (! my_so) {
							my_so=SharedObject.getRemote("gInfo",nc.uri,false);
							my_so.connect(nc);
							my_so.addEventListener(SyncEvent.SYNC, syncHandler);
						}
					} catch (err) {
					}
					break;
				case "NetConnection.Connect.Rejected" :
					break;
				case "NetConnection.Connect.Failed" :
					break;
				case "NetConnection.Connect.Closed" :
					break;
			}
		}
		private function setCam() {
			try {
				cam=Camera.getCamera();
				cam.setKeyFrameInterval(9);
				cam.setMode(320,240,15);
				cam.setQuality(7000,80);
				//cam.setLoopback(true);
				setMic();
			} catch (camError) {
				this.isHardwareError = true;
				//ExternalInterface.call("checkCamJs");
				alert_mc._txt.text = "No Camera Installed!!";
				alert_mc.visible = true;
				
			}
		}
		private function setMic() {
			try {
				mic=Microphone.getMicrophone();
				//mic.setLoopBack(true);
				mic.setUseEchoSuppression(true);
				mic.rate=11;
				mic.gain=30;
				mic.setSilenceLevel(15,1000);
				//mic.encodeQuality=6;
				setVideo();
			} catch (merror) {
				this.isHardwareError = true;
				alert_mc._txt.text = "No Mike Installed!!";
				alert_mc.visible = true;
				
				//ExternalInterface.call("checkMikeJs");
			}
		}
		private function setVideo() {
			try {
				if (! liveVideo) {
					liveVideo=new Video(320,240);
					liveVideo.smoothing=true;
					liveVideo.x=13;
					liveVideo.y=28;
					var m:Matrix=liveVideo.transform.matrix;
					MatrixTransformer.setSkewY(m, 180);
					liveVideo.transform.matrix=m;
					liveVideo.x=liveVideo.x+liveVideo.width;
					addChild(liveVideo);
				}
				camVideo.attachCamera(cam);
			}catch(viderror){
				//isHardwareError = true;
			}
		}
	}
}