/**
 * TextAreaExpander plugin for jQuery
 * v1.0
 * Expands or contracts a textarea height depending on the
 * quatity of content entered by the user in the box.
 *
 * By Craig Buckler, Optimalworks.net
 *
 * As featured on SitePoint.com:
 * http://www.sitepoint.com/blogs/2009/07/29/build-auto-expanding-textarea-1/
 *
 * Please use as you wish at your own risk.
 */

/**
 * Usage:
 *
 * From JavaScript, use:
 *     $(<node>).TextAreaExpander(<minHeight>, <maxHeight>);
 *     where:
 *       <node> is the DOM node selector, e.g. "textarea"
 *       <minHeight> is the minimum textarea height in pixels (optional)
 *       <maxHeight> is the maximum textarea height in pixels (optional)
 *
 * Alternatively, in you HTML:
 *     Assign a class of "expand" to any <textarea> tag.
 *     e.g. <textarea name="textarea1" rows="3" cols="40" class="expand"></textarea>
 *
 *     Or assign a class of "expandMIN-MAX" to set the <textarea> minimum and maximum height.
 *     e.g. <textarea name="textarea1" rows="3" cols="40" class="expand50-200"></textarea>
 *     The textarea will use an appropriate height between 50 and 200 pixels.
 */

(function($) {
	
	

	// jQuery plugin definition
	$.fn.TextAreaExpander = function(minHeight, maxHeight) {

		//jQuery("textarea.expand").css("height","20px");
		
		var hCheck = !($.browser.msie || $.browser.opera);
		var h = 20;
		// resize a textarea
		function ResizeTextarea(e) {
			
			
			

			// event or initialize element?
			e = e.target || e;

			// find content length and box width
			var vlen = e.value.length, ewidth = e.offsetWidth;
			
			//e.valLength=0;
			if (vlen != e.valLength && ($(".mydiv textarea.expand").length>0)) {

				//if (hCheck && (vlen < e.valLength || ewidth != e.boxWidth)) e.style.height = "20px";
				//alert(e.scrollHeight);
				e.style.overflow = (e.scrollHeight !=0 && vlen!=0 ? "hidden" : "");

				
				var userAgent = navigator.userAgent.toLowerCase(); 
				$.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase()); 

				// Is this a version of Chrome?
				if($.browser.chrome){
				  userAgent = userAgent.substring(userAgent.indexOf('chrome/') +7);
				  userAgent = userAgent.substring(0,userAgent.indexOf('.'));
				  $.browser.version = userAgent;
				  // If it is chrome then jQuery thinks it's safari so we have to tell it it isn't
				  $.browser.safari = false;
				  e.style.height = (e.scrollHeight !=0 && vlen!=0 ? e.scrollHeight-4 + "px" : 20 + "px");
				//alert("chrome");
                }
                 if($.browser.msie)
                {
                       //alert("ie");
                 e.style.height = (e.scrollHeight !=0 && vlen!=0 ? e.scrollHeight+2 + "px" : 20 + "px");
                
                }
				if(!$.browser.chrome && !$.browser.msie){
                //alert("others");
					
					e.style.height = (e.scrollHeight !=0 && vlen!=0 ? e.scrollHeight + "px" : 20 + "px");
				}
				
				if($.browser.safari)
				{
					  userAgent = userAgent.substring(userAgent.indexOf('safari/') +7);
					  userAgent = userAgent.substring(0,userAgent.indexOf('.'));
					  $.browser.version = userAgent;
					  					  
					 // if(userAgent == 533)
					//  {						  
						e.style.height = (e.scrollHeight !=0 && vlen!=0 ? e.scrollHeight-8 + "px" : 20 + "px");							
					 // }
				}
				
				//alert(e.scrollHeight);
				//e.valLength = vlen;
				//e.boxWidth = ewidth;
			}


			//return true;
		};

		// initialize
		this.each(function() {

			// is a textarea?
			//if (this.nodeName.toLowerCase() != "textarea") return;

			// set height restrictions
			//var p = this.className.match(/expand/gi);
			//this.expandMin = minHeight || (p ? parseInt('0'+p[1], 10) : 20);
			//this.expandMax = maxHeight || (p ? parseInt('0'+p[2], 10) : 99999);

			// initial resize
			ResizeTextarea(this);
			
			//alert(this.maxHeight);
			
			// zero vertical padding and add events
			if (!this.Initialized) {
				this.Initialized = true;
				//$(this).css("padding-top", 0).css("padding-bottom", 0).css("height",20);
				$(this).bind("keyup", ResizeTextarea).bind("focus", ResizeTextarea);
			}
		});

		return this;
	};

})(jQuery);


// initialize all expanding textareas
jQuery(document).ready(function() {
	//jQuery("textarea.expand").css("height","20px");
	jQuery("textarea.expand").TextAreaExpander();
});
