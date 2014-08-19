<SCRIPT type=text/javascript>
			var delayb4scroll=2000 //Specify initial delay before marquee starts to scroll on page (2000=2 seconds)
			var marqueespeed=1//Specify marquee scroll speed (larger is faster 1-10)
			var pauseit=1 //Pause marquee onMousever (0=no. 1=yes)?

			////NO NEED TO EDIT BELOW THIS LINE////////////

			var copyspeed=marqueespeed
			var pausespeed=(pauseit==0)? copyspeed: 0
			var actualheight=''
			function scrollmarquee(){
				if (parseInt(cross_marquee.style.top)>(actualheight*(-1)+8))
				cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
				else
				cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
			}

			function initializemarquee(){
				cross_marquee=document.getElementById("vmarquee")
				cross_marquee.style.top=0
				marqueeheight=document.getElementById("marqueecontainer").offsetHeight
				actualheight=cross_marquee.offsetHeight
				if (window.opera || navigator.userAgent.indexOf("Netscape/7")!=-1){ //if Opera or Netscape 7x, add scrollbars to scroll and 
					exit
					cross_marquee.style.height=marqueeheight+"px"
					cross_marquee.style.overflow="scroll"
					return
			   }
					setTimeout('lefttime=setInterval("scrollmarquee()",60)', delayb4scroll)
		   }

			if (window.addEventListener)
			window.addEventListener("load", initializemarquee, false)
			else if (window.attachEvent)
			window.attachEvent("onload", initializemarquee)
			else if (document.getElementById)
			window.onload=initializemarquee

</SCRIPT>
<style type="text/css">
#marqueecontainer{
position: relative;
width: 130px; /*marquee width */
height: 300px; /*marquee height */
overflow: hidden;
padding: 2px;
padding-left: 4px;
}
</style>

			
			<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:80px;">
      <tr>
      <td valign="bottom"  style="vertical-align:bottom;"><img src="images/img-bulletinboard-top.gif" alt="" width="148"height="23" align="bottom"  style="margin:0px; padding:0px; vertical-align:bottom;" /></td>
      </tr>
      <tr>
        <td height="250" valign="top" class="bulletin" >
             
        <div id="marqueecontainer" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">
		<div id="vmarquee" style="position: absolute; width: 98%;word-wrap:break-word;">
					<!message>
		</div>
		</div>
        
        
        </td>
      </tr>
      <tr>
        <td><img src="images/img-bulletinboard-bottom.gif" alt="" width="148" height="23" /></td>
      </tr>
      </table>      
      