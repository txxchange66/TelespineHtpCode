package 
{
	import com.salesxpo.caboodle.events.VideoChatEvents;
	import com.salesxpo.caboodle.model.ApplicationModelLocator;
	
	import flash.events.AsyncErrorEvent;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.events.SyncEvent;
	import flash.media.Camera;
	import flash.media.Microphone;
	import flash.media.Video;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.net.SharedObject;
	import flash.net.URLRequest;
	import flash.net.sendToURL;
	
	import mx.controls.Alert;
	import mx.core.Application;
	import mx.core.FlexGlobals;
	
	
	public class VideoChatPublisher
	{
		[Bindable]
		private var _appModel:ApplicationModelLocator = ApplicationModelLocator.getInstance();
		private var camera:Camera;
		private var microphone:Microphone;
		private var userStreamName:String;
		private var videoCamera :Video;
		public var test_so : SharedObject;
		
		public function VideoChatPublisher(streamName:String, _camera:Camera, _microphone:Microphone)
		{

			//_microphone.setLoopBack(false);			
			//_microphone.setUseEchoSuppression(true);
			
		    /* var strCamname:String =  _camera.name;
			_camera =null;
			
			var indexCam:int  = 0;
		
			for(;indexCam < Camera.names.length ; indexCam++ )
			{
			  if(strCamname==  Camera.getCamera(indexCam.toString()).name)
			  {
			     break;
			  }
			} 
			
			camera= Camera.getCamera(indexCam.toString());*/
			camera = _camera;
			if(camera == null)
			{
				var cameraUnavailable:String=_appModel.activityURL+"5";
				sendToURL(new URLRequest(cameraUnavailable));
				
				Alert.show("Camera may not attached or in use by another application .Please check your Camera.");
				return  ;
			}
		 	//camera.setLoopback(true);
		 	//camera.setMode(160, 120, 15,false);
			
			
			// params (bandwidth, quality) passing 0 in both values keep the quality and bandwidth optimal
		 	//camera.setQuality(0, 0);
			
			
			//key frame intervals it takes keyframe as param 1 key frame will come after 13500 frames 
			// max the keyframeinvterval lower the bandwidth will use.			
			//camera.setKeyFrameInterval(13500); 
		
			microphone = _microphone;
			
			if(microphone == null)
			{
				Alert.show("You do not have microphone attached to your system.");
				return  ;
			} 
			
			userStreamName= streamName;
			
			connectandpublish();
			
		}
		public function getVideoObject():Video
		{
		   videoCamera = new Video(320,240);
	 
		  return videoCamera;
		}
		
		public function getCameraObejct():Camera
		{
			
			//camera = camera
		  if(camera == null)
		  {
			// Alert.show("You do not have camera attached to your system.");
			 return null;
		  }
		    //camera.setMode(320,240, 15,false);
			//camera.setQuality(0, 0);
			//camera.setKeyFrameInterval(13500);
			return camera;
		}
		
		
		public function getMicrophoneObject():Microphone
		{
			//microphone = Microphone.getMicrophone();
			if(microphone == null)
			{
				Alert.show("You do not have microphone attached to your system.");
				return null;
			}
		/*	microphone.rate = 11;
			microphone.setSilenceLevel(0);*/
			return microphone;
		}
		
		public function showHideLiveStream(msg:String):String{
			//Alert.show("showHideLiveStream live viedo msg->: "+msg);
			if(msg.indexOf('VideoRefresh') != -1)
			{
				FlexGlobals.topLevelApplication.ShowLiveStreaming();
			}
			else
			{
				FlexGlobals.topLevelApplication.HideLiveStreaming();
			}
			//FlexGlobals.topLevelApplication.subscriberCom.initUserProfile(FlexGlobals.topLevelApplication.liveSname);
			return "ViedoChatPublisher";
		}
		
		public function connectandpublish():void
		{
			// connect to the Wowza Media Server
			
			try
			{
				//_appModel.ncConnection.addEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
				if (_appModel.ncConnection == null)
				{
					// create a connection to the wowza media server
					_appModel.ncConnection = new NetConnection();
					var ncClient = new Object();
					_appModel.ncConnection.client = ncClient;
					ncClient.asyncServerCall = showHideLiveStream;
					_appModel.ncConnection.connect(_appModel.mediaServerEndPoint, FlexGlobals.topLevelApplication.localSname,FlexGlobals.topLevelApplication.liveSname,FlexGlobals.topLevelApplication.userType);
					
					if(!test_so)
					{
						test_so = SharedObject.getRemote("gInfo", _appModel.ncConnection.uri, false);
						test_so.addEventListener(SyncEvent.SYNC,syncEventHandler);
						test_so.connect(_appModel.ncConnection);
					}
					
					// get status information from the NetConnection object
					//_appModel.ncConnection.removeEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
					_appModel.ncConnection.addEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
					_appModel.ncConnection.removeEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
					_appModel.ncConnection.addEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
					//publish();
					 
				}
				else
				{
					//_appModel.ncConnection.connect(_appModel.mediaServerEndPoint, FlexGlobals.topLevelApplication.localSname,FlexGlobals.topLevelApplication.liveSname);
					/*test_so = SharedObject.getRemote("gInfo", _appModel.ncConnection.uri, false);
					test_so.addEventListener(SyncEvent.SYNC,syncEventHandler);
					test_so.connect(_appModel.ncConnection);*/
					_appModel.ncConnection.addEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
					_appModel.ncConnection.removeEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
					_appModel.ncConnection.addEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});				
						publish();
				
				}
			}
			catch(e : Error)
			{
				//Alert.show(e.message);
			}
		}
		
		private function ncOnStatus(infoObject:NetStatusEvent):void
		{
			trace("nc: "+infoObject.info.code+" ("+infoObject.info.description+")")
			if (infoObject.info.code == "NetConnection.Connect.Rejected")
			{
				try
				{
					Alert.show(infoObject.info.application.message);
				}
				catch(e:Error)
				{
					Alert.show(infoObject.info.description);
				}
			}
			else if (infoObject.info.code == "NetConnection.Connect.Closed" ||infoObject.info.code == "NetConnection.Connect.Failed")
			{
				//Alert.show("Internet disconnected.");
				var internetActivity:String=_appModel.activityURL+"7";
				sendToURL(new URLRequest(internetActivity));
			}
			else if (infoObject.info.code == "NetConnection.Connect.Success")
			{//FlexGlobals.topLevelApplication.btnShareVideo.enabled=true;
				if(!test_so)
				{   //FlexGlobals.topLevelApplication.btnShareVideo.enabled=true;
					//Alert.show("Connecion done");
					test_so = SharedObject.getRemote("gInfo", _appModel.ncConnection.uri, false);
					test_so.addEventListener(SyncEvent.SYNC,syncEventHandler);
					test_so.connect(_appModel.ncConnection);
				}
				//publish();
			}
				//
		}
		
		private function syncEventHandler(event:SyncEvent):void
		{
			//Alert.show("call so: "+FlexGlobals.topLevelApplication.liveSname);
			try
			{
				if(FlexGlobals.topLevelApplication.liveSname != null && FlexGlobals.topLevelApplication.liveSname != "null")
				{
					for (var att in test_so.data) {
						//Alert.show("call : "+att);
						if ((FlexGlobals.topLevelApplication.liveSname == att) && (test_so.data[FlexGlobals.topLevelApplication.liveSname] == "true")) {
							//asyncServerCall("VideoRefresh:"+this.liveSname);
							/*Alert.show(ApplicationModelLocator.getInstance().busyflag.toString());
							if(ApplicationModelLocator.getInstance().busyflag==false){
								FlexGlobals.topLevelApplication.subscriberCom.initUserProfile(FlexGlobals.topLevelApplication.liveSname);
							}else{
								FlexGlobals.topLevelApplication.subscriberCom.unSubscribeStream();
							}*/
							
						} else if ((FlexGlobals.topLevelApplication.liveSname == att) && (test_so.data[FlexGlobals.topLevelApplication.liveSname] == "false")) {
							FlexGlobals.topLevelApplication.subscriberCom.unSubscribeStream();
						}
					}
				}
				else
				{
					
					//Alert.show("call 1 : "+test_so.data[FlexGlobals.topLevelApplication.localSname]);
					/*for (var att in test_so.data) {
						Alert.show("call: "+test_so.data[FlexGlobals.topLevelApplication.localSname]);
					}*/
					//FlexGlobals.topLevelApplication.liveSname = FlexGlobals.topLevelApplication.localSname;
					//FlexGlobals.topLevelApplication.subscriberCom.initUserProfile(FlexGlobals.topLevelApplication.localSname);
				}
			}
			catch(e : Error)
			{
				//Alert.show(e.message);
			}
		}
		
		
		private function publish():void
		{
			
			if (_appModel.ncConnection == null)
			{
				//Alert.show("Internet got disconnected.");
				throw new Error("Unable to connect to server. Please check your internet connection.");
				
			}
			 
				// create a new NetStream object for video publishing			
			_appModel.nsPublisher = new NetStream(_appModel.ncConnection);
			
			_appModel.nsPublisher.addEventListener(NetStatusEvent.NET_STATUS, nsPublishOnStatus);
				
				// set the buffer time to zero since it is chat
			_appModel.nsPublisher.bufferTime = 0;
				
				// publish the stream by name
			_appModel.nsPublisher.publish(userStreamName, "live");
				
				// add custom metadata to the stream
																			/*	var metaData:Object = new Object();
																				metaData["description"] = "Chat using VideoChat example."
																				nsPublish.send("@setDataFrame", "onMetaData", metaData);
																				*/
				// attach the camera and microphone to the server
						
			_appModel.nsPublisher.attachCamera(camera);
			_appModel.nsPublisher.attachAudio(microphone);
			
			//Alert.show("localSname: "+FlexGlobals.topLevelApplication.localSname+"liveSname: "+FlexGlobals.topLevelApplication.liveSname);
			//var publishActivity:String=_appModel.activityURL+"8&user1="+FlexGlobals.topLevelApplication.localSname+"&user2="+FlexGlobals.topLevelApplication.liveSname;
			var publishActivity:String=_appModel.activityURL+"8";
			sendToURL(new URLRequest(publishActivity));
			 
		}
		
		private function nsPublishOnStatus(infoObject:NetStatusEvent):void
		{
			trace("nsPublish: "+infoObject.info.code+" ("+infoObject.info.description+")");
			if (infoObject.info.code == "NetStream.Play.StreamNotFound" || infoObject.info.code == "NetStream.Play.Failed")
				Alert.show( infoObject.info.description);
			else
			{
				//notify users that my cam has published
				FlexGlobals.topLevelApplication.dispatchEvent(new VideoChatEvents(VideoChatEvents.VIDEO_CAM_EVENT, true) );
			}
		}
		
		public function disconnectandunpublish():void
		{
			
			// here we are shutting down the connection to the server // destructor
			if(_appModel.nsPublisher!=null)
			{
				/*_appModel.nsPublisher.attachCamera(null);
				_appModel.nsPublisher.attachAudio(null);*/
				_appModel.nsPublisher.publish("null");
				_appModel.nsPublisher.close();
				_appModel.nsPublisher = null;
				
			}
			////notify users that my cam has stoped
			FlexGlobals.topLevelApplication.dispatchEvent(new VideoChatEvents(VideoChatEvents.VIDEO_CAM_EVENT, false) );
				
		}
		
	}
}