<?
ob_start();
session_start();
if(!isset($_SESSION['logged_in'])){
	header('Location: index.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<!-- prevent cache -->
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">

<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
    <title>Club Sierramar</title>
    <!-- application stylesheet will go here -->
    <!-- dynamically apply native visual theme according to the browser user agent -->
	<link href="../js/dojo-release/dojox/mobile/themes/common/domButtons.css" rel="stylesheet"/>
    <script type="text/javascript" data-dojo-config="mblThemeFiles: ['base']" src="../js/dojo-release/dojox/mobile/deviceTheme.js"></script>
    <!-- dojo configuration options -->
    <script type="text/javascript">
        dojoConfig = {
            async: true,
            parseOnLoad: false
        };
    </script>
    <!-- dojo bootstrap -->
    <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/dojo/1.14.1/dojo/dojo.js"></script>-->
    <script src="../js/dojo-release/dojo/dojo.js"></script>
    <!-- dojo application code -->
    <script type="text/javascript">
        require([
		"dojox/mobile/parser",
		"dijit/registry",
		"dojox/mobile/compat",
		"dojox/mobile/deviceTheme",
		"dojox/mobile/View",
		"dojox/mobile/Heading",
		"dojox/mobile/ToolBarButton",
		"dojox/mobile/RoundRectList",
		"dojox/mobile/ListItem",
		"dojo/domReady!"
        ], function (parser, registry) {
            	// now parse the page for widgets
		console.log('hola mundo');
            	parser.parse();
		var btn_anterior = registry.byId("btn_anterior");
		btn_anterior.connect(btn_anterior, "onClick", function(){
			console.log(this.label + " button was clicked");
			window.location.href="../logout.php";
		});
        });
    </script>
</head>
<body style="visibility:hidden;">
<!-- application will go here -->
		<div id="micabecera" data-dojo-type="dojox.mobile.Heading" 
			data-dojo-props="fixed:'top', label:'CLUB SOCIAL SIERRAMAR'">
			<span id="btn_anterior" data-dojo-type="dojox.mobile.ToolBarButton" 
			 arrow="left" defaultColor="mblColorBlue" selColor="mblColorPink" style="float:'left'">Salir</span>
		</div>
		<div id="miMenu" data-dojo-type="dojox.mobile.View" data-dojo-props="selected: true">
			<ul data-dojo-type="dojox.mobile.RoundRectList">
				<li data-dojo-type="dojox.mobile.ListItem" data-dojo-props="icon:'images/i-icon-3.png', rightText:'', href:'./reserva_pistas.php?id_pista=1'">
					Reserva de padel (P1)
				</li>
			</ul>
		</div>
</body>
</html>
