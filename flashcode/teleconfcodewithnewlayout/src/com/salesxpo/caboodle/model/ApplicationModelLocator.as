package com.salesxpo.caboodle.model
{
	import com.adobe.cairngorm.model.IModelLocator;
	
	import flash.net.NetConnection;
	import flash.net.NetStream;
	
	import mx.collections.ArrayCollection;
	
	[Bindable]
	public class ApplicationModelLocator implements IModelLocator
	{
		//single instance of HighwindsModelLocator;
		private static var _instance:ApplicationModelLocator;
		//return single instance of OpenFireModellocator
		
		public static function getInstance():ApplicationModelLocator
		{
			
			if(_instance==null)
				_instance = new ApplicationModelLocator(new SingletonEnforcer());
			
			return _instance;
		}
		
		public function ApplicationModelLocator(enforcer:SingletonEnforcer)
		{
			if(enforcer==null)
			{
				throw new Error("Multiple instances of Application Model locator is  not allowed." );
			}
		}
		
		/****************************************
		 * DECLARE CONSTANTS VARIABLES HERE*
		 ****************************************/
		
		
		/******************************
		 * DECLARE YOUR VARIABLES HERE*
		 ******************************/		
		
		public var loggedInUserList:ArrayCollection  = new ArrayCollection();
		public var loggedInUserID:Number =0;
		
		public var busyflag:Boolean= false;
		//application endpoints
		public var mediaServerEndPoint:String = "rtmpe://zi0hdi07fe.rtmphost.com/telecon";//"rtmp://192.168.0.28/VideoChat";//"rtmp://192.168.1.2/videochat";
		public var sharedObjectEndPoint:String ="";//"rtmp://192.168.1.2/rso"
		public var caboodalWebServiceURL:String ="";//"http://192.168.1.14/TangoFx_wcf/Login.svc?wsdl"	;
		public function facebookGraphAPIPictureURL(socialNetworkid:String):String
		{
			return  "http://graph.facebook.com/"+socialNetworkid+"/picture";
		}
		
		public var userImageURL:String = "";
		
		// video publisher
		public var nsPublisher:NetStream =null;
		
		//VIDEO connection to wowza server
		public var ncConnection:NetConnection=null;
		
		//SHARED OBJECT connection to wowza server
		public var ncSharedConnection:NetConnection = null;
		
		public var activityURL:String="";
		
		
		public function ConfigureAppSettings(settings1:String):void
		{
			var settings:Array = settings1.split(",");
		   for each (var str:String in settings)	
		   {
			   var keyvalue:Array = str.split("=");
				if(keyvalue[0].toString() == "WowzaServerChatEndPointURL")// rtmp://servername/videochat
				{
			      mediaServerEndPoint = keyvalue[1];			     
				}
				if(keyvalue[0].toString()=="SilverlightUserImagesURL")
				{
				  userImageURL=keyvalue[1];
				}
		   }
		}
		
		
	}
}
//PREVENTS DIRECT MODELLOCATOR INSTANCES
class SingletonEnforcer {}