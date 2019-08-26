<?
ob_start();
session_start();
if(!isset($_SESSION['logged_in'])){
	header('Location: index.php');
}
$id_pista=$_REQUEST['id_pista'];
$id_usuario=$_SESSION['user_id'];
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
    <script type="text/javascript" data-dojo-config="mblThemeFiles: ['base','SimpleDialog']" 
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
		"dojox/data/QueryReadStore",
		"dojox/mobile/compat",
		"dojox/mobile/deviceTheme",
		"dojox/mobile/ScrollableView",
		"dojox/mobile/Heading",
		"dojox/mobile/ToolBarButton",
		"dojox/mobile/ListItem",
		"dojox/mobile/SimpleDialog",
		"dojox/mobile/Button",
		"dojo/domReady!"
        ], function (parser, date, locale, registry) {
            	// now parse the page for widgets
            	parser.parse();
		show = function(dlg){
			registry.byId(dlg).show();
		}
		hide = function(dlg){
			registry.byId(dlg).hide();
		}
		var hoy = new Date();
		var mañana = date.add(hoy,"day",1);
		var fecha_actual  = locale.format(hoy, { locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
		var btn_siguiente = registry.byId("btn_siguiente");
		var btn_anterior = registry.byId("btn_anterior");
		var btn_misreservas = registry.byId("btn_misreservas");
		myLabel=registry.byId("cabecera");
		myLabel.set("label",fecha_actual);
		muestra_turnos(fecha_actual);
		btn_siguiente.connect(btn_siguiente, "onClick", function(){
			//console.log(this.label + " button was clicked");
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
			//console.log(this.label + " button was clicked");
			var fecha_actual=myLabel.get("label");
			var nueva_fecha = locale.parse(fecha_actual, {locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
			fecha_actual=date.add(nueva_fecha,"day",-1);
			//console.log('label: '+fecha_actual);
			//console.log('hola mundo, hoy es: '+nueva_fecha);
			var fecha_actual  = locale.format(fecha_actual, { locale: "es", selector: "date", datePattern: "dd-MM-yyyy"});
			myLabel.set("label",fecha_actual);
			muestra_turnos(fecha_actual);
		});
		btn_misreservas.connect(btn_misreservas, "onClick", function(){
			//console.log('Clicked on mis reservas');
			muestra_misreservas();	
		});
		function muestra_turnos(fecha_actual){
		require([
		    "dojo/ready",
		    "dojox/data/QueryReadStore",
		    "dojox/mobile/RoundRectDataList",
	            "dojo/_base/connect"		
		], function(ready, QueryReadStore, roundRectDataList, connect){
		ready(function(){
			console.log('Mostramos turnos de fecha: '+fecha_actual);
			var id_pista=<?=$id_pista?>;
	        		var sampleStore = new QueryReadStore({
					url:'xhr/consulta_reservas.php?fecha='+fecha_actual+'&id_pista='+id_pista
				});
			if ( dataList && dataList instanceof roundRectDataList ) {
				console.log('dataList ya existe -> refresh()');
				dataList.store = sampleStore;
				dataList.refresh(); 
  			} else {
				console.log('creando roundrectdatalist...');
				dataList = new roundRectDataList(
					{store:sampleStore,select:'single'}
				, "dataList" );
    				dataList.startup();
  			}
			connect.connect(registry.byId("dataList"), "onCheckStateChanged", null, datos_turno);
	    	});
		});	
		
	}
        });
	function datos_turno(item,state) {
		myLabel=dijit.registry.byId("cabecera");
		var seleccionado=state;
		var id_pista=<?=$id_pista?>;
		var id_usuario=<?=$id_usuario?>;
		var fecha_actual=myLabel.get("label");
		//var turno = item.get("label");;
		var turno = item.labelNode.innerHTML;
		var accion = item.rightTextNode.innerHTML;
		//var accion = item.get("rightText");
		var marcado = item.get("checked");
		var dataList = dijit.registry.byId("dataList");
		//console.log(fecha_actual);
		//console.log('turno: '+turno);
		//console.log('accion: '+accion);
		//console.log(id_pista);
		//console.log(item);
		if(seleccionado==true){
			if(accion=='RESERVAR'){
				//console.log('dentro de RESERVAR');
				//console.log(fecha_actual,turno,accion,seleccionado, marcado);
				escribe_reserva(fecha_actual,turno,id_pista,id_usuario);
			}else if (accion=='CANCELAR'){
				//console.log('dentro de CANCELAR');
				//console.log(fecha_actual,turno,accion,seleccionado, marcado);
				borra_reserva(fecha_actual,turno,id_pista,id_usuario);
			}else if (accion=='INFO'){
				muestra_quien_reserva(fecha_actual,turno,id_pista,id_usuario);
			}else{
				console.log('condición rara');
			}
		}
	}
	function escribe_reserva(fecha_actual,turno,id_pista,id_usuario){
		//console.log('guardamos reserva con estos datos:');
		//console.log(fecha_actual);
		//console.log(turno);
		//console.log(id_pista);
		//console.log(id_usuario);
		var dataList = dijit.registry.byId("dataList");
		var num_reservas=0;
		require(["dojo/request/xhr"], function(xhr) {
			xhr.get("xhr/escribe_reserva.php",{
			query: {
				id_pista:id_pista,
				id_usuario:id_usuario,
				fecha:fecha_actual,
				turno:turno
				},
			preventCache: false,
			sync: false,
			handleAs:"text"}).then(function(data){
				// Do something with the handled data
				//console.log('dentro de xhrget');
				console.log('Reserva guardada, num. reservas: '+data);
				//console.log(data);
				//num_reservas=data;
				//if(num_reservas > 3){
				//	dojo.byId("titulo_mensaje").innerHTML="RESERVA PADEL";
				//	dojo.byId("texto_mensaje").innerHTML='Límite máximo reservado <br> (1,5 horas)';
				//	show('dlg_message');
				//}
			},
			function(error){
            			// Display the error returned
				console.log('Hay error:'+ error);
			},
			function(evt){
				//console.log(evt);
				dataList.refresh();
			});
		})
	}
	function borra_reserva(fecha_actual,turno,id_pista,id_usuario){
		//console.log('eliminamos la reserva con estos datos:');
		//console.log(fecha_actual);
		//console.log(turno);
		//console.log(id_pista);
		//console.log(id_usuario);
		var dataList=dijit.registry.byId("dataList");
		require(["dojo/request/xhr"], function(xhr) {
			xhr.get("xhr/borra_reserva.php",{
			query: {
				id_pista:id_pista,
				id_usuario:id_usuario,
				fecha:fecha_actual,
				turno:turno
				},
			preventCache: false,
			sync: false,
			handleAs:"text"}).then(function(data){
				// Do something with the handled data
				//console.log(data);
				},
			function(error){
            			// Display the error returned
				console.log('Hay error:'+ error);
			},
			function(evt){
				//console.log(evt);
				dataList.refresh();
			});
		})
	}
	function muestra_quien_reserva(fecha_actual,turno,id_pista,id_usuario){
		console.log('mostramos reserva con estos datos:');
		console.log(fecha_actual);
		console.log(turno);
		console.log(id_pista);
		require(["dojo/request/xhr"], function(xhr) {
			xhr.get("xhr/quien_reserva.php",{
			query: {
				id_pista:id_pista,
				fecha:fecha_actual,
				turno:turno
				},
			preventCache: false,
			sync: false,
			handleAs:"text"}).then(function(data){
				// Do something with the handled data
				//console.log(data);
				dojo.byId("titulo_mensaje").innerHTML="INFO RESERVA";
				dojo.byId("texto_mensaje").innerHTML=data;
				},
			function(error){
            			// Display the error returned
				console.log('Hay error:'+ error);
			},
			function(evt){
				//muestra_turnos(fecha_actual);
				show('dlg_message');
			});
		})
		dojo.byId("titulo_mensaje").innerHTML="RESERVA PADEL";
		dojo.byId("texto_mensaje").innerHTML='Reservado por usuario '+id_usuario;
		show('dlg_message');
	}
	function muestra_misreservas(){
		id_usuario=<?=$id_usuario?>;
		id_pista=<?=$id_pista?>;
		console.log('mostramos la reserva con estos datos:');
		console.log('usuario: '+id_usuario, 'id_pista: '+id_pista);
		require(["dojo/request/xhr"], function(xhr) {
			xhr.get("xhr/mis_reservas.php",{
			query: {
				id_usuario:id_usuario,
				id_pista:id_pista
				},
			preventCache: false,
			sync: false,
			handleAs:"text"}).then(function(data){
				// Do something with the handled data
				//console.log(data);
				dojo.byId("titulo_mensaje").innerHTML="MIS RESERVAS";
				dojo.byId("texto_mensaje").innerHTML=data;
				},
			function(error){
          			// Display the error returned
				console.log('Hay error:'+ error);
			},
			function(evt){
				//muestra_turnos(fecha_actual);
				show('dlg_message');
			});
		})
	}
    </script>
<style>
		.mblSimpleDialogButton {
			margin: 7px 0 0;
			width: 262px;
			font-size: 17px;
			font-weight: bold;
			opacity: 0.95;
		}
		.mblSimpleDialogButton2l {
			float: left;
			width: 127px;
			margin: 7px 0 0;
			font-size: 17px;
			font-weight: bold;
			opacity: 0.95;
		}
		.mblSimpleDialogButton2r {
			float: right;
			width: 127px;
			margin: 7px 0 0;
			font-size: 17px;
			font-weight: bold;
			opacity: 0.95;
		}
	</style>
</head>
<body style="visibility:hidden;">
<!-- application will go here -->
	<div id="cabecera" data-dojo-type="dojox.mobile.Heading" data-dojo-props="back:'Inicio',fixed:'top', label:'La fecha'">
		<span id="btn_misreservas" data-dojo-type="dojox.mobile.ToolBarButton" data-dojo-props='label:"Mis reservas"' defaultColor="mblColorBlue" style="float:right;"></span>
		<span id="btn_siguiente" data-dojo-type="dojox.mobile.ToolBarButton" 
			data-dojo-props='icon:"mblDomButtonWhiteUpArrow"' style="float:right;"></span>
		<span id="btn_anterior" data-dojo-type="dojox.mobile.ToolBarButton" 
			data-dojo-props='icon:"mblDomButtonWhiteDownArrow"' style="float:left;"></span>
	</div>
	<div id="reservas" data-dojo-type="dojox.mobile.ScrollableView" data-dojo-props="selected: true">
		<ul id="dataList"></ul>
	</div>
	<div id="dlg_message" data-dojo-type="dojox.mobile.SimpleDialog">
		<div id="titulo_mensaje" class="mblSimpleDialogTitle"></div>
		<div id="texto_mensaje" class="mblSimpleDialogText"></div>
		<button data-dojo-type="dojox/mobile/Button" class="mblSimpleDialogButton"
          	style="width:100px;" onclick="hide('dlg_message')">OK</button>
	</div>
</body>
</html>
