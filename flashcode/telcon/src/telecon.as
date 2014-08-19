package {
	
	//import model.ApplicationModel;
	//import mx.utils.UIDUtil;
	import flash.display.MovieClip;
	import flash.net.Responder;
	import flash.net.NetConnection;
	import flash.events.NetStatusEvent;
	import flash.events.MouseEvent;
	import flash.display.Sprite;
    //import flash.events.NetStatusEvent;
	//import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.media.Camera;
	import flash.media.Microphone;
	import flash.media.Video;
	public class telecon extends MovieClip {
		
		//private var nc:NetConnection;
		private var nc:NetConnection;
        private var good:Boolean;
        private var rtmpNow:String;
        private var nsIn:NetStream;
        private var nsOut:NetStream;
        private var cam:Camera;
        private var mic:Microphone;
        private var vidLocal:Video;
        private var vidStream:Video;
		
		public function telecon()
		{
			setCam ();
            setMic ();
            setVideo ();
			btnSend.enabled = true;
			btnConnect.selected = true;
			btnSend.label = "send msg";
			btnConnect.label = "Share Video";
			
			btnConnect.addEventListener(MouseEvent.CLICK,doAction);
			btnSend.addEventListener(MouseEvent.CLICK,doSend);
			ctracer.text = "\n\n  cons";
			cvScrollBar.update();
			svScrollBar.update();

		}
		
		//private var userUID:String = UIDUtil.createUID();
		
   		private function doConnect() : void
		{
   			//A NetConnection is the pipe between Flex/Air and Flash Media Server
			
			
			nc = new NetConnection();
			
			//The NetConnection has a client property to refer to an object or class 
			//which you assign all of the server callbacks to.
			nc.client = this;
			
			//The NetStatusEvent is a dispatched status event for 
			//NetConnection, NetStream, or SharedObject.
			//We want to know if the NetConnection has properly connected
			nc.addEventListener( NetStatusEvent.NET_STATUS, handlerOnNetStatus );
			
			//Connect to the location of your flash media server for example:
			//nc.connect("rtmp://fmsexamples.adobe.com/applicationName/instance");
			nc.connect( "rtmp://192.168.0.195/telecon/");
			ctracer.text += "\n\n  connecting.......";
			cvScrollBar.update();
			svScrollBar.update();
		}
   		
    	//
    	 // 	This is the handler function of the NetStatusEvent for the NetConnection.  
    	 // 	Here is where we can find out the connection status and execute actions 
    	 // 	based on each result. 
    	 //
		private function handlerOnNetStatus( event:NetStatusEvent ) : void 
		{
			ctracer.text += "\n\n  handlerOnNetStatus Event ----> event" + event.info.toString();
			var info:Object = event.info;
			
			//Checking the event.info.code for the current NetConnection status string
			switch(info.code) 
            {
            	//code == NetConnection.Connect.Success when Netconnection has successfully
            	//connected
            	case "NetConnection.Connect.Success":	
					ctracer.text += "\n\n  Connection Successful";
					cvScrollBar.update();
					svScrollBar.update();
					//tracePanel.writeln( "Connection Successful" );
					//btnSend.enabled = true;
					//btnConnect.selected = true;
					
					
				try
				 {
					 
				   ctracer.text += "\n\n  inputSname : " + inputSname.text; 
				   ctracer.text += "\n\n  outputSname : " + outputSname.text; 
				   cvScrollBar.update();
				   svScrollBar.update();
                   nsOut=new NetStream(nc);
                   nsOut.attachAudio (mic);
                   nsOut.attachCamera (cam);
				   var iSname:String = loaderInfo.parameters.fv_iSname;
				   var oSname:String = loaderInfo.parameters.fv_oSname;
				   
				   /*
				    this.fv_iSname = fv_iSname;
					this.fv_oSname = "fv_oSname";
				   */
				   iSnametxt.text = loaderInfo.parameters.fv_iSname;
				   oSnametxt.text = loaderInfo.parameters.fv_oSname;
				   
                   nsOut.publish(iSname,"live");
					//patient
				   nsIn=new NetStream(nc);
                  // nsIn.play("patient001LiveStream");
				   nsIn.play(oSname);
                   //vidStream.attachNetStream(nsIn);
				   liveVideo.attachNetStream(nsIn);
				   
				 }
				 catch(err)
				 {
				 trace("OnError : ", err.toString());	
				 }
					
					
        			break;
        		
        		//code == NetConnection.Connect.Rejected when Netconnection did
        		//not have permission to access the application.	
				case "NetConnection.Connect.Rejected":
				ctracer.text += "\n\n  Connection Rejected";
				cvScrollBar.update();
				svScrollBar.update();
        			//tracePanel.writeln( "Connection Rejected" );	
        			//btnConnect.selected = false;
        			//btnSend.enabled = false;
        			break;
        		
        		//code == NetConnection.Connect.Failed when Netconnection has failed to connect
            	//either because your network connection is down or the server address doesn't exist.
        		case "NetConnection.Connect.Failed":
        			//tracePanel.writeln( "Connection Failed" );	
        			ctracer.text += "\n\n  Connection Failed";
					cvScrollBar.update();
					svScrollBar.update();
        			//btnConnect.selected = false;
        			//btnSend.enabled = false;
        			break;
        		
        		//code == NetConnection.Connect.Closed when Netconnection has been closed successfully.		
        		case "NetConnection.Connect.Closed":
        			//tracePanel.writeln( "Connection Closed" );
					ctracer.text += "\n\n  Connection Failed";
					cvScrollBar.update();
        			svScrollBar.update();
        			//btnConnect.selected = false;
        			//btnSend.enabled = false;
        			break;
            }
		}
		
		private function doDisconnect() : void
		{
			nc.close();
			
			//btnConnect.selected = false;
			//btnSend.enabled = false;
		}
		
		private function doAction(e)
		{
			ctracer.text += "\n\n  s doAction" + ( btnConnect.selected ).toString();
			cvScrollBar.update();
			svScrollBar.update();
			//Depending on the selected state of btnConnect either
			//connect to the video stream or disconnect from it.
			if ( btnConnect.selected )
			{
				doConnect();
			}
			else
			{
				//doDisconnect();
			}
			ctracer.text += "\n\n  e doAction" + ( btnConnect.selected ).toString();
			cvScrollBar.update();
			svScrollBar.update();
		}
		
		private function doSend(e) : void
		{
			//NetConnection.call() requires a responder object which should contain a 
			//result handler and a fault handler
			var myResponder:Responder = new Responder( handlerResultAsyncClientCall, 
															   handlerFaultAsyncClientCall );
															   
			var myMsg:String = msgtxt.text;
			
			//Invoke the method on the server called 'asyncClientCall'
			nc.call( "asyncClientCall", myResponder, myMsg );
			ctracer.text += "\n\n  'Sending message to FMS.'";
			cvScrollBar.update();
			svScrollBar.update();
			
		}
		
		//
		 // This is a method that the server invokes on the connected client
		 //
		public function asyncServerCall( msg:String ) : String 
   		{
   			ctracer.text += "\n\n  'message from FMS. -->" +  msg ;
			cvScrollBar.update();
			svScrollBar.update();
   			return "Hey server, I got your message... Thanks!";
   		}

		
		//
		 // This is the result handler of our NetConnection.call() the return 
		 // value is passed here and traced out to the console
		 //
		public function handlerResultAsyncClientCall( result:String ) : void
		{
			ctracer.text += "\n\n  'handlerResultAsyncClientCall. -->" +  result ;
			cvScrollBar.update();
			svScrollBar.update();
		}	
		private function handlerFaultAsyncClientCall( info:Object ) : void
		{
			ctracer.text += "\n\n  error: " + info.description ;
			ctracer.text += "\n\n  error: " + info.code ;
			cvScrollBar.update();
			svScrollBar.update();
		}
		public function close() : void
		{
			ctracer.text += "\n\n  'Connection closed.'";
			cvScrollBar.update();
			svScrollBar.update();
		}
		
		
		/****************************************************/
		 private function setCam()
        {
            try
			{
			 trace("setCam fun");
			 
			 cam=Camera.getCamera();
             cam.setKeyFrameInterval (9);
             cam.setMode (240,180,15);
             cam.setQuality (0,80);
			}
			 catch(camError)
			{
				ctracer.text += "\n\n  cam not found: " + camError;
			}
        }

        private function setMic()
        {
             trace("setMic fun");
			 mic=Microphone.getMicrophone();
             mic.gain=85;
             mic.rate=11;
             mic.setSilenceLevel(15,2000);
        }

        private function setVideo()
        {
			try
			{
			trace("setVideo fun");
             /*
			 vidLocal=new Video(cam.width,cam.height);
             addChild(vidLocal);
             vidLocal.x=15; vidLocal.y=30;
             vidLocal.attachCamera(cam);

             vidStream=new Video(cam.width,cam.height);
             addChild(vidStream);
             vidStream.x=(vidLocal.x+ cam.width +10); vidStream.y=vidLocal.y;
			 */
			 camVideo.attachCamera(cam);
			 }
			 catch(viderror)
			 {
				trace("on set vid Error : ", viderror);
			}
        }
		/****************************************************/
   }
}