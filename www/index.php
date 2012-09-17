<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Nova Labs Open/Closed Widget</title>
<style type="text/css">html { overflow:hidden; }</style>
<script type="text/javascript">
var lastText="";
function showStatus() {
    if(window.XMLHttpRequest) {
      xmlhttp=new XMLHttpRequest();
    } else {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		var newText = xmlhttp.responseText;
		if(newText != lastText) {
			document.getElementById("status").innerHTML=xmlhttp.responseText;
		}

	}
    }

    xmlhttp.open("GET","CheckStatus.php", true);
    xmlhttp.send();
}

	showStatus();
    var refreshId = setInterval( "showStatus()", 2000);


</script>
</head>
<body style="text-align: center; background: white; font-size: 16px;">
The door to <a target="_blank" href="http://nova-labs.org/blog/">Nova Labs</a> is <br /><br />
<div id="status">Checking status...</div>
</body>
</html>