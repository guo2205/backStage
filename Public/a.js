function changeBody(a)
{
	switch(a)
	{
		case 1:
			document.getElementById('c1').style.color="#FF0000";
			document.getElementById('c2').style.color="#000000";
			document.getElementById('c3').style.color="#000000";
			document.getElementById('c4').style.color="#000000";
			document.getElementById('c5').style.color="#000000";
			document.getElementById('jbxx').style.display = "";
			document.getElementById('xqhj').style.display = "none";
			document.getElementById('zbpt').style.display =	"none";
			document.getElementById('zshx').style.display = "none";
			document.getElementById('lpdt').style.display = "none";
			break;
		case 2:
			document.getElementById('c1').style.color="#000000";
			document.getElementById('c2').style.color="#FF0000";
			document.getElementById('c3').style.color="#000000";
			document.getElementById('c4').style.color="#000000";
			document.getElementById('c5').style.color="#000000";
			
			document.getElementById('jbxx').style.display = "none";
			document.getElementById('xqhj').style.display = "";
			document.getElementById('zbpt').style.display =	"none";
			document.getElementById('zshx').style.display = "none";
			document.getElementById('lpdt').style.display = "none";
			break;
		case 3:
			document.getElementById('c1').style.color="#000000";
			document.getElementById('c2').style.color="#000000";
			document.getElementById('c3').style.color="#FF0000";
			document.getElementById('c4').style.color="#000000";
			document.getElementById('c5').style.color="#000000";
			
			document.getElementById('jbxx').style.display = "none";
			document.getElementById('xqhj').style.display = "none";
			document.getElementById('zbpt').style.display =	"";
			document.getElementById('zshx').style.display = "none";
			document.getElementById('lpdt').style.display = "none";
			break;
		case 4:
			document.getElementById('c1').style.color="#000000";
			document.getElementById('c2').style.color="#000000";
			document.getElementById('c3').style.color="#000000";
			document.getElementById('c4').style.color="#FF0000";
			document.getElementById('c5').style.color="#000000";
			
			document.getElementById('jbxx').style.display = "none";
			document.getElementById('xqhj').style.display = "none";
			document.getElementById('zbpt').style.display =	"none";
			document.getElementById('zshx').style.display = "";
			document.getElementById('lpdt').style.display = "none";
			break;
		case 5:
			document.getElementById('c1').style.color="#000000";
			document.getElementById('c2').style.color="#000000";
			document.getElementById('c3').style.color="#000000";
			document.getElementById('c4').style.color="#000000";
			document.getElementById('c5').style.color="#FF0000";
			
			document.getElementById('jbxx').style.display = "none";
			document.getElementById('xqhj').style.display = "none";
			document.getElementById('zbpt').style.display =	"none";
			document.getElementById('zshx').style.display = "none";
			document.getElementById('lpdt').style.display = "";
			break;
	}
}