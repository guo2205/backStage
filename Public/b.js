function changeBody(a)
{
	switch(a)
	{
		case 1:
			document.getElementById('c1').style.color="#FF0000";
			document.getElementById('c2').style.color="#000000";
			document.getElementById('c3').style.color="#000000";
			
			document.getElementById('jsfz').style.display = "";
			document.getElementById('jsyh').style.display = "none";
			document.getElementById('jsqx').style.display =	"none";
			break;
		case 2:
			document.getElementById('c1').style.color="#000000";
			document.getElementById('c2').style.color="#FF0000";
			document.getElementById('c3').style.color="#000000";
			
			document.getElementById('jsfz').style.display = "none";
			document.getElementById('jsyh').style.display = "";
			document.getElementById('jsqx').style.display =	"none";
			break;
		case 3:
			document.getElementById('c1').style.color="#000000";
			document.getElementById('c2').style.color="#000000";
			document.getElementById('c3').style.color="#FF0000";
			
			document.getElementById('jsfz').style.display = "none";
			document.getElementById('jsyh').style.display = "none";
			document.getElementById('jsqx').style.display =	"";
			break;
	}
}