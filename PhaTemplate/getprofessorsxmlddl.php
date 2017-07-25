<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Professors</title>
<script language = "javascript">
      var PROFESSORS;
	  var PROFESSOR;
      var XMLHttpRequestObject = false;
		
      if (window.XMLHttpRequest) {
        XMLHttpRequestObject = new XMLHttpRequest();
        XMLHttpRequestObject.overrideMimeType("text/xml") ;
      } else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject ("Microsoft.XMLHTTP");
      }
	  
      function getData()
      {
        if(XMLHttpRequestObject) {
          XMLHttpRequestObject.open("GET", "professors.xml");
          var outputDiv = document.getElementById('targetDiv');
		outputDiv.innerHTML="";

          XMLHttpRequestObject.onreadystatechange = function()
          {
            if (XMLHttpRequestObject.readyState == 4 &&
              XMLHttpRequestObject.status == 200) {
              var xmlDocument = XMLHttpRequestObject.responseXML;
              PROFESSORS=xmlDocument.getElementsByTagName("PROFESSOR");
         		PROFESSOR=xmlDocument.getElementsByTagName("ID");
              for (row =0; row < PROFESSORS.length - 1; row++)
              {
                outputDiv.innerHTML +=   
				        PROFESSOR[row].firstChild.data + ", "
              }
			  
			  outputDiv.innerHTML +=   
				        PROFESSOR[row++].firstChild.data
			  
            }
          }

          XMLHttpRequestObject.send(null);
        }
      }

    </script>
</head>
 <body>

    <h1>Using Ajax with XML</h1>

    <form>
      <input type = "button" value = "Fetch the Students"
        onclick = "getData()"> 
</form>
<div id="targetDiv"></div>
    
<div id="messageDiv"></div>
  </body>

</html>