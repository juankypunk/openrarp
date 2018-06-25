<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="ltr">
	<head>
		<style type="text/css">
	            body, html { font-family:helvetica,arial,sans-serif; font-size:90%; }
	        </style>
		<script src="../js/dojo-release/dojo/dojo.js" djConfig="parseOnLoad: true"></script>
		<script type="text/javascript">
			dojo.require("dijit.layout.ContentPane");
			dojo.require("dijit.layout.BorderContainer");
			dojo.require("dijit.form.DropDownButton");
			dojo.require("dijit.TooltipDialog");
			dojo.require("dijit.form.TextBox");
			dojo.require("dijit.form.Button");
			require([
				 // Require the basic chart class
				"dojox/charting/Chart",
				// Require the theme of our choosing
				"dojox/charting/themes/MiamiNice",
				// Charting plugins: 
				// 	We want to plot Columns 
				"dojox/charting/plot2d/Columns",
				//	We want to use Markers
				"dojox/charting/plot2d/Markers",
				//	We'll use default x/y axes
				"dojox/charting/axis2d/Default",
				// Wait until the DOM is ready
				"dojo/domReady!"
				], function(Chart, theme) {
				// Define the data
				var chartData = [58,44,45,170,64,43,51,105];
				// Create the chart within it's "holding" node
				var chart = new Chart("chartNode");
				// Set the theme
				chart.setTheme(theme);
				// Add the only/default plot 
				chart.addPlot("default", {
					type: "Columns",
					markers: true,
					gap: 5
				});
				// Add axes
				//chart.addAxis("x");
				chart.addAxis("x", {
				    labels: [{value: 1, text: "Dic.2011"}, {value: 2, text: "Mar.2012"},
					{value: 3, text: "Jun.2012"}, {value: 4, text: "Sep.2012"},
					{value: 5, text: "Dic.2012"}, {value: 6, text: "Mar.2013"},
					{value: 7, text: "Jun.2013"}, {value: 8, text: "Sep.2013"}]
				});
				chart.addAxis("y", { vertical: true, fixLower: "major", fixUpper: "major" });
				// Add the series of data
				chart.addSeries("Monthly Sales",chartData);
				// Render the chart!
				chart.render();
			});
		</script>
		<link rel="stylesheet" type="text/css" href="../js/dojo-release/dijit/themes/claro/claro.css" />
			<style type="text/css">
				html, body { width: 100%; height: 100%; margin: 0; overflow:hidden; }
				#borderContainer { width: 100%; height: 100%; }
				.login {
					float:right;
				}
				.logo {
					float:left;
				}
		
			</style>
	</head>
    
	<body class="claro">
	<script type="text/javascript">
		function autenticar() {
			//usuario=dijit.byId("usuario").value;
			//clave=dijit.byId("clave").value;
			//alert("hola:"+usuario+"contras.:"+clave);
			dojo.xhrPost({
				url: "xhr/checklogin.php",
				content: {'username':dijit.byId("usuario").value, 'passwd':dijit.byId("clave").value},
				load: function(response, ioArgs) {
					if(response=="fail"){
						dojo.byId("loginStat").innerHTML="Identif. incorrecta";
						return response;
					}else{
						window.location.href=response;
					}
				},
				error: function(response, ioArgs){
					var message="";
					switch(ioArgs.xhr.status){
						case 404:
							message="La página solicitada no se encuentra";
							break;
						case 404:
							message="Error en el servidor";
							break;
						default:
							message="Error desconocido";
							break;
					}
					dojo.byId("loginStat").innerHTML=message;
					return response;
				},
				handleAs: "text"
		});
	}
					
	</script>
	<div dojoType="dijit.layout.BorderContainer" design="sidebar" gutters="true" liveSplitters="true" id="borderContainer">
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="left" style="width:150px">
       			<div class="logo">
				<img src="../images/logo_sierramar.gif" alt="logo" />
			</div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="top">
			<div class="login" dojoType="dijit.form.DropDownButton" >
				<span> Login </span>
				<div dojoType="dijit.TooltipDialog">
					<p>
					<label style="display:inline-block;width:100px;" for="usuarsio">Usuario: </label>
			                <input dojoType="dijit.form.TextBox" id="usuario" name="usuario">
			                </p>
					<p>
			                <label style="display:inline-block;width:100px;"  for="clave">Clave: </label>
			                <input dojoType="dijit.form.TextBox" type="password" id="clave" name="clave">
			                </p>
					<p>
			                <button dojoType="dijit.form.Button" type="submit" onClick="autenticar">
			                    Enviar
					</button>
					</p>
				</div>
		        </div>
			<div class="login" id="loginStat" ></div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="true" region="center">
			<h1>Demo: Columns - Monthly Sales</h1>
			<div id="chartNode" style="width:800px;height:400px;"></div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="bottom">
		</div>
	</div>
    </body>
</html>
