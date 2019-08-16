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
<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
    <title>Club Sierramar</title>
    <!-- application stylesheet will go here -->
    <!-- dynamically apply native visual theme according to the browser user agent -->
    <script type="text/javascript" data-dojo-config="mblThemeFiles: ['base','TabBar','dojox/mobile/themes/common/domButtons.css']" 
		src="../js/dojo-release/dojox/mobile/deviceTheme.js"></script>
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
		"dojo/date",
		"dojo/date/locale",
		"dijit/registry",
		"dojox/mobile/compat",
		"dojox/mobile/deviceTheme",
		"dojox/mobile/View",
		"dojox/mobile/Heading",
		"dojox/mobile/ToolBarButton",
		"dojox/mobile/RoundRectList",
		"dojox/mobile/ListItem",
		"dojo/domReady!"
        ], function (parser, date, locale, registry) {
            	// now parse the page for widgets
            	parser.parse();
		var hoy = new Date();
		var mañana = date.add(hoy,"day",1);
		var fecha_actual  = locale.format(hoy, { locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
		var btn_siguiente = registry.byId("btn_siguiente");
		var btn_anterior = registry.byId("btn_anterior");
		myLabel=registry.byId("cabecera");
		myLabel.set("label",fecha_actual);
		muestra_turnos(fecha_actual);
		btn_siguiente.connect(btn_siguiente, "onClick", function(){
			console.log(this.label + " button was clicked");
			var fecha_actual=myLabel.get("label");
			var nueva_fecha = locale.parse(fecha_actual, {locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
			fecha_actual=date.add(nueva_fecha,"day",+1);
			//console.log('label: '+fecha_actual);
			//console.log('hola mundo, hoy es: '+nueva_fecha);
			var fecha_actual  = locale.format(fecha_actual, { locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
			myLabel.set("label",fecha_actual);
			muestra_turnos(fecha_actual);
		});
		btn_anterior.connect(btn_anterior, "onClick", function(){
			console.log(this.label + " button was clicked");
			var fecha_actual=myLabel.get("label");
			var nueva_fecha = locale.parse(fecha_actual, {locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
			fecha_actual=date.add(nueva_fecha,"day",-1);
			//console.log('label: '+fecha_actual);
			//console.log('hola mundo, hoy es: '+nueva_fecha);
			var fecha_actual  = locale.format(fecha_actual, { locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
			myLabel.set("label",fecha_actual);
			muestra_turnos(fecha_actual);
		});
        });
	function muestra_turnos(fecha_actual){
		require([
		    "dojo/ready",
		    "dojox/data/QueryReadStore",
		    "dojox/mobile/RoundRectDataList",
		    "dijit/registry",
	            "dojo/_base/connect",		
		    "dojox/mobile",
		    "dojox/mobile/parser"
		], function(ready, QueryReadStore, roundRectDataList, registry, connect){
		ready(function(){
			console.log('Mostramos turnos de fecha: '+fecha_actual);
	        	var sampleStore = new QueryReadStore({url:'xhr/consulta_reservas.php?fecha='+fecha_actual});
			if ( dataList && dataList instanceof roundRectDataList ) {
				dataList.store = sampleStore;
				//datalist.store.url="xhr/consulta_reservas,php?fecha="+fecha_actual;
				//datalist.store.fetch();
				dataList.refresh(); 
  			} else {
				dataList = new roundRectDataList({store:sampleStore,select:'single'}, "dataList" );
    				dataList.startup();
  			}
			connect.connect(registry.byId("dataList"), "onCheckStateChanged", null, muestra_turno);
	    	});
		});	
		
	}
	function muestra_turno(item,state) {
		console.log('dentro');
		myLabel=dijit.registry.byId("cabecera");
		var fecha_actual=myLabel.get("label");
		var turno = item.labelNode.innerHTML;
		var ocupado = item.rightTextNode.innerHTML;
		console.log(fecha_actual);
		console.log(turno);
		console.log(ocupado);
		var respuesta=confirm('¿Confirmas la reserva para el '+fecha_actual+' a las '+turno+' horas?');
	}
    </script>
</head>
<body style="visibility:hidden;">
<!-- application will go here -->
		<div id="reservas" data-dojo-type="dojox.mobile.View" data-dojo-props="selected: true">
			<div id="cabecera" data-dojo-type="dojox.mobile.Heading" data-dojo-props="back:'Inicio', label:'La fecha'">
				<span id="btn_siguiente" data-dojo-type="dojox.mobile.ToolBarButton" 
					data-dojo-props='icon:"mblDomButtonWhiteUpArrow"' style="float:right;"></span>
				<span id="btn_anterior" data-dojo-type="dojox.mobile.ToolBarButton" 
					data-dojo-props='icon:"mblDomButtonWhiteDownArrow"' style="float:left;"></span>
			</div>
			<ul id="dataList"></ul>
		</div>
</body>
</html>
