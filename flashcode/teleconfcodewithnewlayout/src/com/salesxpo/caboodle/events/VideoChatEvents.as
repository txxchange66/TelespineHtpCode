package com.salesxpo.caboodle.events
{
	import flash.events.Event;

	public class VideoChatEvents extends Event
	{
		// Public constructor.
		
		public var result : Object; 
		
		public static const VIDEO_CAM_EVENT	: String	 	= "VIDEO_CAM_EVENT";
		
		public override function clone():Event
		{
			return new VideoChatEvents(type, result, bubbles, cancelable);
		}
		
		
		public function VideoChatEvents(type:String, result : Object,  bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
			this.result = result;
		}
	}
}