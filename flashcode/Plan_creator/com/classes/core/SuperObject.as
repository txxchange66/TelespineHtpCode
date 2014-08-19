/*
* @Date : 1 sep. 2007.
* @class: com.classes.core.SuperObject
* @discription : For declaring, initilazing event related vars and methods
* @author : E5149
*/
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class com.classes.core.SuperObject extends MovieClip
{
	var addEventListener:Function;
	var removeEventListener:Function;
	var dispatchEvent:Function;
	
    function SuperObject()
    {
        mx.events.EventDispatcher.initialize(this);
    } // End of the function
    function toString(Void)
    {
        return ("SuperObject class");
    } // End of the function
} // End of Class
