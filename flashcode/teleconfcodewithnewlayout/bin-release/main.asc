var clientObjList = new Object();
var liveStreams = new Object();
var chatCounter = 0;
var cClient,pClient,msg,tempStream;

application.onConnect = function(clientObj,sn1,sn2)
{
	//trace("application.onConnect--> clientObj" +clientObj +"cam:"+sn1+"live:"+sn2);	
	
	sn2 = (sn2 == "" || sn2 == "null" || sn2 == undefined || sn2 == "undefined")? null:sn2;
	
	//trace("checking  in Chat Collection ::"+liveStreams);
	for(var sn in liveStreams)
	{ 
		//trace("exist chat sn ::"+sn+" :: "+liveStreams[sn]);
		if(liveStreams[sn] == sn2)
		{
			var error = new Object();error.message = "USER IS BUSY";
			application.rejectConnection(clientObj, error);
			return false;
		}
	}
	
	if(clientObj.protocol == "rtmpe" || clientObj.protocol == "rtmpte")
	{
		application.acceptConnection(clientObj);
		clientObj.localSName = sn1;
		clientObj.liveSName = sn2;
		if(clientObjList[clientObj.localSName] == undefined)
		{
			if((clientObj.liveSName != null) && (clientObjList[clientObj.liveSName] != undefined))
			{
				tempStream = Stream.get(clientObj.liveSName);
				pClient = clientObjList[clientObj.liveSName];
				pClient.liveSName = clientObj.localSName;
				clientObj.call("asyncServerCall",null,("VideoRefresh:"+clientObj.liveSName));
				pClient.call("asyncServerCall",null,("VideoRefresh:"+pClient.liveSName));
				//trace("Client function VideoRefresh Called from server for clientObj.liveSName : "+ clientObj.liveSName);
				//trace("Client function VideoRefresh Called from server for pClient.liveSName : "+ pClient.liveSName);
				//trace("pushing in Chat Collection chatCollection.length ::"+chatCollection.length);
				
				liveStreams[clientObj.liveSName] = clientObj.liveSName;
				liveStreams[pClient.liveSName] = pClient.liveSName;
				
				//trace("pushing in Chat Collection : chatCollection.length" + chatCollection.length);
			}
			else if(clientObj.liveSName == null)
			{
				for(var p in clientObjList)
				{
					if(clientObjList[p] != undefined)
					{
						if(clientObjList[p].liveSName == clientObj.localSName)
						{
							pClient = clientObjList[p];
							clientObj.liveSName = pClient.localSName;
							msg = "VideoRefresh:"+clientObj.liveSName;
							clientObj.call("asyncServerCall",null,msg);
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
	//trace("application.onDisconnect--> clientObj" +clientObj +"clientObjList"+clientObjList);	
	//trace("Removing from collection : chatCollection.length" + chatCollection.length);
	//trace("remove Stream from liveStreams : "+clientObj.liveSName);
	//trace("remove Stream from webcamStreams : "+clientObj.localSName);
	liveStreams[clientObj.liveSName] = null;
	liveStreams[clientObj.liveSName] = undefined;
	liveStreams[clientObj.localSName] = null;
	liveStreams[clientObj.localSName] = undefined;
		
	if(clientObjList[clientObjList[clientObj.localSName].liveSName]!= undefined)
	{
		if(clientObjList[clientObjList[clientObj.localSName].liveSName])
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