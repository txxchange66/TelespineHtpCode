/*
Treatment Class for TX plan_viewer.fla
*/
import mx.utils.Delegate;
import Viewer;

class TreatmentThumb extends MovieClip
{
	public var holder_mc:MovieClip;
	
	private var _image_src:String;
	private var _viewer:Viewer;
	private var _order:Number;
	private var _load_listener:Object;
	
	
	public function TreatmentThumb ()
	{	
		if(this._image_src && this._viewer && this._order)
		{
			this._viewer.doStatus(0,'created thumb with src=' +this._image_src+ ' in position ' +this._order+ '.');
			this.doLoadImage();
		}
		else if (this._viewer)
		{
			this._viewer.doError(2,'Failed creating treatment thumb.');
		}
		else trace('ERROR - Failed creating treatment thumb with no viewer.');
	}
	
	private function doLoadImage ():Void
	{
		this._load_listener = new Object();
		this._load_listener.onLoadInit = Delegate.create(this, doImageLoaded);
		
		var mcl = new MovieClipLoader();
		mcl.addListener(this._load_listener);
		mcl.loadClip(this._image_src, this.holder_mc);
	}
	
	private function doImageLoaded ():Void
	{
		
		// resize pic
		var scale_pct = 1;
		if(this.holder_mc._width > this.holder_mc._height)
		{     
		 scale_pct = 100 / this.holder_mc._width;
		}
		else
		{
			scale_pct = 75 / this.holder_mc._height;
		}
		// scale if needed and round to whole pixels
		if(scale_pct != 1 && scale_pct > 0)
		{
			this.holder_mc._xscale = this.holder_mc._yscale =scale_pct * 100;
			this.holder_mc._width = Math.floor(this.holder_mc._width);
			this.holder_mc._height = Math.floor(this.holder_mc._height);
		}
		
		// center pic horz
		var newX = Math.floor((100 - this.holder_mc._width)/2);
		this.holder_mc._x = (newX < 0) ? 0 : newX;
		// center pic vert
		var newY = Math.floor((75 - this.holder_mc._height)/2);
		this.holder_mc._y = (newY < 0) ? 0 : newY;
		
		// exit load screen
		this.play();
		
		// enable click
		this.onRelease = Delegate.create(this, handleClick);
	}
	
	private function handleClick():Void
	{
		this._viewer.showPic(this._order);
	}
}