package
{
	import com.salesxpo.caboodle.model.ApplicationModelLocator;
	
	import flash.events.AsyncErrorEvent;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.media.Video;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.net.URLRequest;
	import flash.net.sendToURL;
	
	import mx.controls.Alert;
	import mx.core.Application;
	import mx.core.FlexGlobals;
     
	public class VideoChatSubscriber
	{
		[Bindable]
		private var _appModel:ApplicationModelLocator = ApplicationModelLocator.getInstance();
		private var userStreamName:String;
		public var nsPlay:NetStream =null ;
		private var video:Video ;
		
		public function VideoChatSubscriber(streamName:String)
		{
			userStreamName= streamName;
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
			return "ViedoChatSubscriber";
		}
		
		
		public function subscribeStream():Video
		{
			if (_appModel.ncConnection == null)
			{
				// create a connection to the wowza media server
				_appModel.ncConnection = new NetConnection();
				var ncClient = new Object();
				_appModel.ncConnection.client = ncClient;
				ncClient.asyncServerCall = showHideLiveStream;
				
				_appModel.ncConnection.connect(_appModel.mediaServerEndPoint, "record");
				
				// get status information from the NetConnection object
				//_appModel.ncConnection.removeEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
				_appModel.ncConnection.addEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
				_appModel.ncConnection.removeEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
				_appModel.ncConnection.addEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
				//video = new Video(100,80);
				video = new Video(320,240);
				return video;
		
			}
			else
			{
				if(!_appModel.ncConnection.connected)
				{
					_appModel.ncConnection.close() ;
					_appModel.ncConnection = null;
				    this.subscribeStream();
					return null;
				}
				//_appModel.ncConnection.removeEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
				_appModel.ncConnection.addEventListener(NetStatusEvent.NET_STATUS, ncOnStatus);
				_appModel.ncConnection.removeEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
				_appModel.ncConnection.addEventListener(AsyncErrorEvent.ASYNC_ERROR, function doError(evt : Event){});
				return doSubscribe();
				
			}
			 
			
		}
		
		
		public function unSubscribeStream():void
		{
			// here we are shutting down the connection to the server // destructor
			if(this.nsPlay !=null)
			{
				nsPlay.attachCamera(null);
				nsPlay.attachAudio(null);
				nsPlay.close();				 
				nsPlay = null;
				//FlexGlobals.topLevelApplication.subscriberCom.video=null;
				FlexGlobals.topLevelApplication.subscriberCom.uservideoStream.removeChildAt(0);
				ApplicationModelLocator.getInstance().busyflag=false;
				
			}
		}
		
		private function ncOnStatus(infoObject:NetStatusEvent):void
		{
			//trace("nc: "+infoObject.info.code+" ("+infoObject.info.description+")")
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
			else if (infoObject.info.code == "NetConnection.Connect.Failed"||infoObject.info.code == "NetConnection.Connect.Closed")
			{
				//Alert.show("Internet got disconnected.");
			}
			else if (infoObject.info.code == "NetConnection.Connect.Success")
			{
				doSubscribe();
			}
		}
		private function nsPlayOnStatus(infoObject:NetStatusEvent):void
		{
			trace("nsPlay: "+infoObject.info.code+" ("+infoObject.info.description+")");
			if (infoObject.info.code == "NetStream.Play.StreamNotFound" || infoObject.info.code == "NetStream.Play.Failed")
			{
			    Alert.show(infoObject.info.description);
			}
		}
		
		private function doSubscribe():Video
		{
			// create a new NetStream object for video playback
			 
		//	ApplicationModelLocator.getInstance().busyflag=true;
			
			if (_appModel.ncConnection == null)
			{
				//Alert.show("Internet got disconnected.");
				throw new Error("Unable to connect to server. Please check your internet connection.");
				
			}
				if(nsPlay == null)
				{
					nsPlay = new NetStream(_appModel.ncConnection);
					
					// trace the NetStream status information
					nsPlay.addEventListener(NetStatusEvent.NET_STATUS, nsPlayOnStatus);
					
					var nsPlayClientObj:Object = new Object();
					nsPlay.client = nsPlayClientObj;
					nsPlayClientObj.onMetaData = function(infoObject:Object):void
					{
						//Alert.show("onMetaData");
						
						// print debug information about the metaData
						for (var propName:String in infoObject)
						{
							//Alert.show("  "+propName + " = " + infoObject[propName]);
						}
					};		
					
					// set the buffer time to zero since it is chat
					nsPlay.bufferTime = 0;
					
					// subscribe to the named stream
					nsPlay.play(this.userStreamName);
					video = null;
					
					//video = new Video(100,80)
					video = new Video(320,240)
					video.clear();
					// attach to the stream
						
					video.attachNetStream(nsPlay);
					
					var connectActivity:String=_appModel.activityURL+"9&user1="+FlexGlobals.topLevelApplication.localSname+"&user2="+FlexGlobals.topLevelApplication.liveSname;
					//Alert.show(connectActivity);
					sendToURL(new URLRequest(connectActivity));
					
					
					return video;
				}
				else
				{		
					// here we are shutting down the connection to the server
					return video;
				}
			
			 
		}	
	}
}