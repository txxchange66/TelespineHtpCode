var clientObjList = new Object();
var liveStreams = new Object();
var connectedDocters = new Object();
var chatCounter = 0;
var cClient,pClient,msg,tempStream;


application.onConnect = function(clientObj,sn1,sn2,_userType)
{
	// userType  == doc || pai
	trace("\n\n************new main fmc application.onConnect--> clientObj" +clientObj +"cam:"+sn1+"live:"+sn2+"_userType"+_userType);	
	sn2 = (sn2 == "" || sn2 == "null" || sn2 == undefined || sn2 == "undefined")? null:sn2;
	
	
	
	if(_userType == 'pai') //on Paicent Connect Checking and and rejecting connection if Doctor is busy //
	{	
		//trace("checking  in liveStreams Collection ::"+liveStreams);
		for(var sn in liveStreams)
		{ 
			//trace("exist chat sn ::"+sn+" :: "+liveStreams[sn]);
			if(liveStreams[sn] == sn2)
			{
				var error = new Object();error.message = "Provider is currently unavailable.Please try after sometime.";
				application.rejectConnection(clientObj, error);
				return false;
			}
		}
	}
	
	if(clientObj.protocol == "rtmpe" || clientObj.protocol == "rtmpte")
	{
		application.acceptConnection(clientObj);
		clientObj.localSName = sn1;
		clientObj.liveSName = sn2;
		clientObj.userType = _userType;
		// Extra checkes for Docter Types //
		if(clientObj.userType == 'doc') connectedDocters[sn1] = clientObj;
		// ------------------------------ //
		if(clientObjList[clientObj.localSName] == undefined)
		{
			if((clientObj.liveSName != null) && (clientObjList[clientObj.liveSName] != undefined))
			{
				tempStream = Stream.get(clientObj.liveSName);
				pClient = clientObjList[clientObj.liveSName];
				pClient.liveSName = clientObj.localSName;
				
				for(var cl in connectedDocters)
				
				
				if(clientObj.userType == 'pai')
				{
					if(connectedDocters[clientObj.liveSName].liveSName == clientObj.localSName)
					{
						trace(' allow view Doc to pai has rights -- > if if 1');
						clientObj.call("asyncServerCall",null,("VideoRefresh:"+clientObj.liveSName));
					}
					else
					{
						trace(' Not Allowed doc view to pai has rights -- > if else 1');
						clientObj.call("asyncServerCall",null,("stopShare:"+clientObj.liveSName));
					}
				}
				else
				{
					trace(' Allow doc view to pai has rights -- > else 1');
					clientObj.call("asyncServerCall",null,("VideoRefresh:"+clientObj.liveSName));
				}
				
				if(pClient.userType == 'pai')
				{
					if(connectedDocters[pClient.liveSName].liveSName == pClient.localSName)
					{
						trace(' allow view Doc to pai has rights -- > if if 2');
						pClient.call("asyncServerCall",null,("VideoRefresh:"+pClient.liveSName));
					}
					else
					{
						trace(' Not Allowed doc view to pai has rights -- > if else 2');
						pClient.call("asyncServerCall",null,("stopShare:"+pClient.liveSName));
					}
				}
				else
				{
					trace(' Allow doc view to pai has rights -- > else 2');
					pClient.call("asyncServerCall",null,("VideoRefresh:"+pClient.liveSName));
				}
				
				trace("Chat Strat Between User1 and User2");
				trace("Client function VideoRefresh Called from server for clientObj.liveSName : "+ clientObj.liveSName);
				trace("Client function VideoRefresh Called from server for pClient.liveSName : "+ pClient.liveSName);
				
				liveStreams[clientObj.liveSName] = clientObj.liveSName;
				liveStreams[pClient.liveSName] = pClient.liveSName;
				
				trace("liveStreams[clientObj.liveSName] : "+ liveStreams[clientObj.liveSName]);
				trace("liveStreams[pClient.liveSName] : "+ liveStreams[pClient.liveSName]);
			}
			clientObjList[clientObj.localSName] = clientObj;
		}
		return true;
	}
	return false
}
application.onDisconnect = function(clientObj)
{
	trace("\napplication.onDisconnect--> clientObj" +clientObj +"clientObjList"+clientObjList);	
	removeStream(clientObj.localSName);
	
	trace('clientObj.localSName :: ' + clientObj.localSName);
	trace('clientObj.liveSName :: ' + clientObj.liveSName);
	
	if(clientObj.userType == 'pai')
	{	
		trace('connectedDocters[clientObj.liveSName].liveSName :: ' + connectedDocters[clientObj.liveSName].liveSName);
		trace('clientObj.localSName :: ' + clientObj.localSName);
		
		if(connectedDocters[clientObj.liveSName].liveSName == clientObj.localSName)
		{
			trace('free live ')
			liveStreams[clientObj.liveSName] = null;
			liveStreams[clientObj.liveSName] = undefined;
		}
	
	}
	else
	{   // stop sharing video from pai on doc exit.
		if(clientObjList[clientObjList[clientObj.localSName].liveSName]!= undefined)
		{
			if(clientObjList[clientObjList[clientObj.localSName].liveSName])
				clientObjList[clientObjList[clientObj.localSName].liveSName].call("asyncServerCall",null,("stopShare:"+clientObj.localSName));
		}
	}
	
	if(clientObj.userType == 'doc')
	{
		trace('free local ')
		liveStreams[clientObj.localSName] = null;
		liveStreams[clientObj.localSName] = undefined;
		liveStreams[clientObj.liveSName] = null;
		liveStreams[clientObj.liveSName] = undefined;
	}
	
	clientObjList[clientObj.localSName] = null;
	clientObjList[clientObj.localSName] = undefined;
}

Client.prototype.stopShare = function (streamName){
	cClient = clientObjList[streamName];
	if(cClient.liveSName != null && cClient.liveSName != "null")
	{
		pClient = clientObjList[cClient.liveSName];
		if(clientObjList[cClient.liveSName]!= undefined)
		{
			msg = "stopShare:"+pClient.liveSName;
			pClient.call("asyncServerCall",null,msg);
		}
	}
}
Client.prototype.shareVedio = function (streamName){
	cClient = clientObjList[streamName];
	if(cClient.liveSName != null && cClient.liveSName != "null")
	{
		if(clientObjList[cClient.liveSName]!= undefined)
		{
			pClient = clientObjList[cClient.liveSName];
			
			
			if(pClient.userType == 'pai')
			{
				if(connectedDocters[pClient.liveSName].liveSName == pClient.localSName)
				{
					trace(' Allow doc view to pai has rights -- > Client.prototype.shareVedio if if line 100');
					msg = "VideoRefresh:"+pClient.liveSName;
					pClient.call("asyncServerCall",null,msg);
				}
				else
				{
					trace(' Not Allow doc view to pai has rights -- > Client.prototype.shareVedio if else line 101');
					msg = "stopShare:"+pClient.liveSName;
					pClient.call("asyncServerCall",null,msg);
				}
			}
			else
			{
				trace(' Allow doc view to pai has rights -- > Client.prototype.shareVedio else line 110');
				msg = "VideoRefresh:"+pClient.liveSName;
				pClient.call("asyncServerCall",null,msg);
			}
			
			
			//trace("Asynchronous server call Client.prototype.shareVedio");
			
		}
	}
}
Client.prototype.delStream = function (selectedName){
	removeStream(selectedName);
}
function removeStream(streamName)
{
	tempStream = Stream.get(streamName);
	if(tempStream != undefined && tempStream != null)
	{
		tempStream.clear();
		tempStream = null;
	}
}