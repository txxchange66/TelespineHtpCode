//application.allowDebug = false;
var clientObjList = new Object();
var cClient,pClient,msg,tempStream;
var chatCollection:Array = new Array();
//var chatObj:Object;
application.onConnect = function(clientObj,sn1,sn2)
{
	trace("application.onConnect : chatCollection.length" + chatCollection.length);
	trace("application.onConnect for --> s1 : "+s1+"S2:" + S2);
	for(var i:int=0; i<chatCollection.length; i++)
	{ 
		trace("chatCollection[i].['s1'] "+chatCollection[i].["s1"]+" < -Caht- > chatCollection[i].['s2']" + chatCollection[i].["s1"]);
		if(chatCollection[i].["s1"] == sn2) || chatCollection[i].["s2"] == sn2)
		{
			var error = new Object();
			error.message = "User Is Busy";
			trace("application.rejectConnection -- > error.message = User Is Busy");
			application.rejectConnection(clientObj, error);
			return false;
		}
		
	}
	trace("application.onConnect --End");	
	//trace("clientObj" +clientObj+"clientObjList"+clientObjList);
	sn2 = (sn2 == "null" || sn2 == undefined || sn2 == "undefined")? null:sn2;
	
	if(clientObj.protocol == "rtmpe" || clientObj.protocol == "rtmpte")
	{
		application.acceptConnection(clientObj);
		clientObj.localSName = sn1;
		clientObj.liveSName = sn2;
		if(clientObjList[clientObj.localSName] == undefined)
		{
			if((clientObj.liveSName != null) && (clientObjList[clientObj.liveSName] != undefined))
			{
				//trace("inside if((clientObj.liveSName != null) && (clientObjList[clientObj.liveSName] != undefined))");
				tempStream = Stream.get(clientObj.liveSName);
				pClient = clientObjList[clientObj.liveSName];
				pClient.liveSName = clientObj.localSName;
				clientObj.call("asyncServerCall",null,("VideoRefresh:"+clientObj.liveSName));
				pClient.call("asyncServerCall",null,("VideoRefresh:"+pClient.liveSName));
				trace("Client function VideoRefresh Called from server for clientObj.liveSName : "+ clientObj.liveSName);
				trace("Client function VideoRefresh Called from server for pClient.liveSName : "+ pClient.liveSName);
				trace("pushing in Chat Collection");
				chatCollection.push({"s1":clientObj.liveSName,"s2":pClient.liveSName});
				trace("pushing in Chat Collection : chatCollection.length" + chatCollection.length);
			}
			else if(clientObj.liveSName == null)
			{
				//trace("inside else if else if(clientObj.liveSName == null)");
				//trace("for loop for all objects in clientObjList ::-->");
				for(var p in clientObjList)
				{
					//trace("p "+ p +"clientObjList[p]"+clientObjList[p]);
					if(clientObjList[p] != undefined)
					{
						if(clientObjList[p].liveSName == clientObj.localSName)
						{
							pClient = clientObjList[p];
							clientObj.liveSName = pClient.localSName;
							msg = "VideoRefresh:"+clientObj.liveSName;
							clientObj.call("asyncServerCall",null,msg);
							//trace("Called   --- >clientObj.call(asyncServerCall,null,"+msg+");");  
							break;
						}
					}
				}
			}
			clientObjList[clientObj.localSName] = clientObj;
		}
		return true;
	}
	return false
}
application.onDisconnect = function(clientObj)
{
	
	for(var i:int=0; i<chatCollection.length; i++)
	{ 
		
		if(chatCollection[i].["s1"] == clientObj.localSName) || chatCollection[i].["s2"] == clientObj.localSName)
		{
			chatCollection.splice(i,1);
			
		}
		
	}
	
	
	//trace("application.onDisconnect--> clientObj" +clientObj +"clientObjList"+clientObjList);		
	if(clientObjList[clientObjList[clientObj.localSName].liveSName]!= undefined)
	{
		//trace("Asynchronous server call:  application.onDisconnect  ");
		clientObjList[clientObjList[clientObj.localSName].liveSName].call("asyncServerCall",null,("stopShare:"+clientObj.localSName));
	}
	removeStream(clientObj.localSName);
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
			msg = "VideoRefresh:"+pClient.liveSName;
			pClient.call("asyncServerCall",null,msg);
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