<?php
ob_start();
session_start();
if(!$_SESSION['logged_in']){
	header('location: index.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="ltr">
	<head>
		<meta charset="utf-8">
		<style type="text/css">
			html, body { font-family:helvetica,arial,sans-serif; font-size:90%; width: 100%; height: 100%; margin: 0; overflow:hidden; }
			#borderContainer { width: 100%; height: 100%; }
			.login {
				float:right;
			}
			.logo {
				float:left;
			}
		</style>
	        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dijit/themes/claro/claro.css" media="screen">
		<script src="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojo/dojo.js" data-dojo-config="parseOnLoad: true"></script>
		<script type="text/javascript">
			dojo.require('dojo.parser');
			dojo.require('dijit.layout.ContentPane');
			dojo.require('dijit.layout.BorderContainer');
			dojo.require('dijit.form.DropDownButton');
			dojo.require('dijit.Menu');
			dojo.require('dijit.MenuBar');
			dojo.require("dijit.MenuBarItem");
			dojo.require("dijit.MenuItem");
			dojo.require('dijit.layout.TabContainer');
			dojo.require('dijit.form.TextBox');
			dojo.require('dijit.form.Button');
			function gotolink(enlace){
                        	//console.log('el link:'+enlace);
	                        window.location.href=enlace;
			}
		</script>
	</head>
    
	<body class="claro">
		<div dojoType="dijit.layout.BorderContainer" design="sidebar" gutters="true" liveSplitters="true" id="borderContainer">
			<div dojoType="dijit.layout.ContentPane" splitter="false" region="left" style="width:150px;border:none;">
				<img src="images/openrarp_logo.png" alt="logo" />
			</div>
			<div dojoType="dijit.layout.ContentPane" splitter="false" region="top" style="border:none">
				<div id="menubar" data-dojo-type="dijit.MenuBar">
                                	<div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('home.php');}">
                                        	Inicio
	                                </div>
        	                        <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('intranet/socios.php');}">
                	                        Socios 
                        	        </div>
                                	<div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('intranet/agua.php');}">
                                        	Agua
	                                </div>
        	                        <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('intranet/cuotas.php');}">
                	                        Cuotas
	                                </div>
        	                        <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('intranet/remesas.php');}">
                	                        Remesas
                        	        </div>
					<div class="login" dojoType="dijit.form.DropDownButton" >
						<span><?=$_SESSION['name']?> </span>
						<div dojoType="dijit.Menu">
			       	    		     	<div dojoType="dijit.MenuItem" data-dojo-props="onClick:function(){gotolink('logout.php');}">
			                   			Salir 
							</div>
						</div>
					</div>
				</div>
				<div style="margin-top:50px;margin-left:100px">
					<img src="images/erp-1.png" alt="logo" />
					<h1>¡Bienvenido a openrarp!</h1>
				</div>
			</div>
			<div dojoType="dijit.layout.ContentPane" splitter="false" region="bottom">
				<div style="text-align:center;"><a href="https://github.com/juankymoral/openrarp" title="openrarp" target="_blank">Powered by OpenRARP</a></div>
			</div>
		</div>
	</body>
</html>
