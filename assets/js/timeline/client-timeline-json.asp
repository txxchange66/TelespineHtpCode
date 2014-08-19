<!--#include virtual="/admin/inc/connection.asp" -->
<%
CLIENT_ID = Session("CLIENT_ID")
if CLIENT_ID = "" then
	CLIENT_ID = 0
end if
baseDirectory = "/account/assets/"& CLIENT_ID &"/"
				
%>
{
    "timeline":
    {
        "headline":"<%=Session("CLIENT_firstName")%>",
        "type":"default",
		"text":"",
		"startDate":"<%=Session("CLIENT_dateCreated")%>",
        "asset":
        {
            "media":"/images/tl-default.png",
            "credit":"Project Share: <%=Session("CLIENT_firstName")%>",
            "caption":""
        },
        "date": [
            { <% sNewDate = DateAdd("w", 12, CDate(Session("CLIENT_dateCreated")))  %>
                "startDate":"<%=Session("CLIENT_dateCreated")%>",
				"endDate":"<%=sNewDate%>",
                "headline":"Treatment Begins",
                "text":"<%=RemoveLines(Session("PROJECT_description"))%>",
                "asset":
                {
                    "media":"/images/tl-default.png",
                    "credit":"The Acne Lab - By Creative Slave",
                    "caption":""
                }
            }<%
getMyTasksSql = "SELECT * FROM clients_clientSchedule WHERE (clientId="& CLIENT_ID &")"
rst.open getMyTasksSql,cnn
taskCount = 0
if not rst.eof then
	do while not rst.eof 
		taskCount = taskCount +1
		%>,
    		{
                "startDate":"<%=rst("startDate")%>",
                "headline":"<%=rst("title")%> on <%=rst("startDate")%>",
                "text":"<%=RemoveLines(rst("description"))%>",
                "asset":
                {
                    "media":"/images/tl-appt.png",
                    "credit":"",
                    "caption":""
                }
            }<%
	rst.movenext
	loop
end if
rst.close

getMyNotificationsSql = "SELECT * FROM clients_clientLog WHERE (clientId="& CLIENT_ID &")"
rst.open getMyNotificationsSql,cnn
taskCount = 0
if not rst.eof then
	do while not rst.eof 
		taskCount = taskCount +1
		%>,
    		{
                "startDate":"<%=rst("entryDate")%>",
                "headline":"Progress: <%=rst("progress")%>",
                "text":"<%=RemoveLines(rst("logEntry"))%>",
                "asset":
                {
                    "media":"<%=baseDirectory%><%=rst("imageURL")%>",
                    "credit":"",
                    "caption":""
                }
            }<%
	rst.movenext
	loop
end if
rst.close 
%>
        ]
    }
}