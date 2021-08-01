<?
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
		<title>OpenRARP</title>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojo/resources/dojo.css">
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dijit/themes/claro/claro.css">
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojox/grid/resources/Grid.css">
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojox/grid/resources/claroGrid.css">
		<style type="text/css">
			html, body {  font-family:helvetica,arial,sans-serif; font-size:90%;width: 100%; height: 100%; margin: 0; overflow:hidden; }
			#borderContainer { width: 100%; height: 100%; }
			.dojoxGrid table { margin: 0;font-size: 80%;}
			.login {
				float:right;
			}
			.logo {
				float:left;
			}
			.menu {
				float:left;
			}
			.pie {
				float:left;
			}
			.estadistica {
				color: gray;
				list-style-type:none;
				font-size: 90%;
				margin-top:1.5em;
				padding-left: 1em;
			}
			.estado {
				text-align: center;
			}
		</style>
    		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/dojo/1.10.3/dojo/dojo.js" data-dojo-config="parseOnLoad: true"></script>
		<!--<script src="../js/dojo-release/dojo/dojo.js" djConfig="parseOnLoad: true"></script>-->
		<script type="text/javascript">
			dojo.require('dojo.parser');
			dojo.require('dojox.validate');
			dojo.require('dojox.validate.web');
        		dojo.require("dojox.grid.DataGrid");
			dojo.require("dijit.form.Select");
			dojo.require("dijit.form.FilteringSelect");
			dojo.require("dojox.data.QueryReadStore");
			dojo.require("dijit.layout.ContentPane");
			dojo.require("dijit.layout.BorderContainer");
			dojo.require("dijit.form.Form");
			dojo.require("dijit.form.RadioButton");
			dojo.require("dijit.form.CheckBox");
			dojo.require("dijit.form.Textarea");
			dojo.require("dijit.form.DateTextBox");
			dojo.require("dijit.form.DropDownButton");
			dojo.require("dijit.Menu");
			dojo.require("dijit.MenuBar");
			dojo.require("dijit.MenuBarItem");
			dojo.require("dijit.MenuItem");
			dojo.require("dijit.layout.TabContainer");
			dojo.require("dijit.form.TextBox");
			dojo.require("dijit.form.ValidationTextBox");
			dojo.require("dijit.form.Button");
			dojo.require("dijit.Dialog");
			dojo.ready(function(){
				myTabs=dijit.byId("myTabContainer");
				myTabs.watch("selectedChildWidget", function(name, oval, nval){
					//console.log("selected child changed from ", oval, " to ", nval);
					var indice=myTabs.getIndexOfChild(nval);
					console.log('indice tab:'+indice);
					switch(indice){
							case 0:	
								actualiza_filas_agua();
								actualiza_estadisticas_agua();
								break;
							case 1:
								nval.set("onDownloadEnd", function(){
								//console.log("Hola mundo!");
								});
								actualiza_filas_riego();
								actualiza_estadisticas_riego();
								break;
							default:
								console.log('nada que hacer :(');
						}
				});       
				var checkBox = new dijit.form.CheckBox({
				        name: "checkBox",
				        value: "true",
				        checked: false,
				        onChange: function(b){ 
						//alert('onChange called with parameter = ' + b + ', and widget value = ' + this.get('value') ); 
						//console.log(this.get('value'));
						estado=this.get('value');
						if(estado){
							console.log('estado verdadero');
							grid_agua.layout.setColumnVisibility('6',true);
							grid_agua.layout.setColumnVisibility('7',true);
						}else{	
							console.log('estado falso');
							grid_agua.layout.setColumnVisibility('6',false);
							grid_agua.layout.setColumnVisibility('7',false);
						}	
					}
				}, "checkBox").startup();
				function formatEstado(value){
				    switch(value){
					case "A":
					    return "<img src=\'images/luz_verde.gif\' alt=\'Abierta\' >";
					case "R":
					    return "<img src=\'images/luz_naranja.gif\' alt=\'En curso\' >";
					case "C":
					    return "<img src=\'images/luz_roja.gif\' alt=\'Cerrada\' >";
				    }
				}
				function formatM3(fields){
					if(fields[0]===null){
						return fields[0];
					}else{	
						var media=fields[1];
						var desvt=fields[2];
						var limitemaximo= +media + +desvt;
						var limiteminimo= media - desvt;
						var consumoactual=fields[0];
						if(consumoactual > limitemaximo){
							return "<span style='color:red'>"+consumoactual+"</span>";
						}else if(consumoactual < limiteminimo){
							return "<span style='color:orange'>"+consumoactual+"</span>";
						}else{
							//caso normal
							return fields[0];
						}
					}
				}

				var grid_agua, store_agua, store_riego;
				store_agua = new dojox.data.QueryReadStore({
					url: "xhr/agua.php"
				});
				store_riego= new dojox.data.QueryReadStore({
					url: "xhr/riego.php"
				});
				fechasStoreAgua = new dojox.data.QueryReadStore({
					url: "xhr/fechas_agua.php"
				});
				fechasStoreRiego = new dojox.data.QueryReadStore({
					url: "xhr/fechas_riego.php"
				});
				var fechaSelectAgua = new dijit.form.Select({
			            id: "fechaLecturaAgua",
			            name: "fecha",
				    sortByLabel:false,
			            value: "",
			            store: fechasStoreAgua,
			            searchAttr: "name",
				    onChange: function(fechaLecturaAgua){
						actualiza_listado_agua();
					}
			        }, "fechaSelectAgua");
				var fechaSelectRiego= new dijit.form.Select({
			            id: "fechaLecturaRiego",
			            name: "fecha",
				    placeHolder: 'Selecciona una fecha...',
				    sortByLabel:false,
			            store: fechasStoreRiego,
			            searchAttr: "name",
				    onChange: function(fechaLecturaRiego){
						actualiza_listado_riego();
					}
			        }, "fechaSelectRiego");
				var layoutAgua = [
				[
					{
						field: "estado",
						name: "Estado",
						width: "60px",
						classes: "estado",
						formatter: formatEstado
					},
					{
						field: "id_parcela",
						name: "Parcela",
						width: "5"
					},
					{
						field: "titular",
						name: "Nombre",
						width: "20"
					},
					{
						field: "l1",
						name: "Lect. ant.",
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "l2",
						name: "Lect. actal.",
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						fields: ["m3","avg","stddev"],
						name: "m3",
						cellStyles: "text-align:right;",
						formatter: formatM3,
						width: "auto"
					},
					{
						field: "avg",
						name: "Media",
						hidden: true,
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "stddev",
						name: "Desv.T",
						hidden: true,
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "pm3",
						name: "precio/m3",
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "importe",
						name: "Importe",
						cellStyles: "text-align:right;",
						width: "auto",
					},
					{
						field: "notas",
						name: "Notas",
						width: "auto"
			    		}]];
				var layoutRiego = [
				[
					{
						field: "estado",
						name: "Estado",
						width: "60px",
						classes: "estado",
						formatter: formatEstado
					},
					{
						field: "id_contador",
						name: "Contador",
						width: "5"
					},
					{
						field: "lugar",
						name: "Lugar",
						width: "20"
					},
					{
						field: "l1",
						name: "Lect. ant.",
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "l2",
						name: "Lect. actal.",
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "m3",
						name: "m3",
						cellStyles: "text-align:right;",
						width: "auto"
					},
					{
						field: "notas",
						name: "Notas",
						width: "auto"
			    		}]];
				grid_agua = new dojox.grid.DataGrid({
					"class": "grid",
					store: store_agua,
					rowsPerPage: "400",
					query: { id_parcela: '*' },
					structure: layoutAgua
				}, "grid_agua");
				grid_riego = new dojox.grid.DataGrid({
					"class": "grid",
					store: store_riego,
					rowsPerPage: "400",
					query: { id_contador: '*' },
					structure: layoutRiego
				}, "grid_riego");
				grid_agua.startup();
				grid_riego.startup();
				dojo.connect(grid_agua, "onRowDblClick", grid_agua, function(evt){
					var idx = evt.rowIndex,
					item = this.getItem(idx);
					id_parcela=this.store.getValue(item, "id_parcela");
					estado=this.store.getValue(item,"estado");
					console.log("estado: "+estado);
					if(estado != 'C'){
						muestra_dialogo_agua(id_parcela);
					}
				});	
				dojo.connect(grid_riego, "onRowDblClick", grid_riego, function(evt){
					var idx = evt.rowIndex,
					item = this.getItem(idx);
					id_contador=this.store.getValue(item, "id_contador");
					estado=this.store.getValue(item,"estado");
					console.log("estado: "+estado);
					if(estado != 'C'){
						muestra_dialogo_riego(id_contador);
					}
				});	
				actualiza_estadisticas_agua();
				actualiza_filas_agua();
				var utils_menu_agua = new dijit.Menu({
					id: "accionesMenuAgua"
				});
				var utils_menu_riego = new dijit.Menu({
					id: "accionesMenuRiego"
				});
				var utils_menuItem0 = new dijit.MenuItem({
					label: "Descargar hoja de c&aacute;lculo",
					iconClass: "",
					onClick: genera_csv_agua
				});
				var utils_menuItem1 = new dijit.MenuItem({
					label: "Generar remesa SEPA",
					iconClass: "dijitEditorIcon dijitEditorIconSave",
					onClick: muestra_dialogo_remesa
				});
				var utils_menuItem2 = new dijit.MenuItem({
					label: "Nueva lectura",
					iconClass: "dijitIconFile",
					onClick: muestra_dialogo_nuevalectura_agua
				});
				var utils_menuItem3 = new dijit.MenuItem({
					label: "Descargar hoja de c&aacute;lculo",
					iconClass: "",
					onClick: genera_csv_riego
				});
				var utils_menuItem4 = new dijit.MenuItem({
					label: "Informe IVA (.csv)",
					iconClass: "",
					onClick: genera_csv_iva
				});
				utils_menu_agua.addChild(utils_menuItem0);
				utils_menu_agua.addChild(utils_menuItem1);
				utils_menu_agua.addChild(utils_menuItem2);
				utils_menu_agua.addChild(utils_menuItem4);
				utils_menu_riego.addChild(utils_menuItem3);
				var utilsButtonAgua = new dijit.form.DropDownButton({
					optionsTitle: "Opciones",
					label: "Utilidades",
					style: "padding-left: 1.2em",
					dropDown: utils_menu_agua,
					onClick:function(){ console.log("Clicked ComboButton"); }
				}, "acciones_agua");
				var utilsButtonRiego = new dijit.form.DropDownButton({
					optionsTitle: "Opciones",
					label: "Utilidades",
					style: "padding-left: 1.2em",
					dropDown: utils_menu_riego,
					onClick:function(){ console.log("Clicked ComboButton"); }
				}, "acciones_riego");
				//utils_menu_agua.startup(); // this also starts up its child MenuItems
			}); // fin de dojo.ready()
			function muestra_dialogo_remesa(){
				var myDialogRemesa = new dijit.Dialog({
					// The dialog's title
					title: "Generar remesa SEPA",
					// The dialog's content
					href: "xhr/formulario_remesa_agua.php",
					// Hard-code the dialog width
					style: "width:400px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialogRemesa.show();
			}
			function muestra_dialogo_nuevalectura_agua(){
				var myDialogLectura = new dijit.Dialog({
					// The dialog's title
					title: "Nueva lectura",
					// The dialog's content
					href: "xhr/formulario_lectura.php",
					// Hard-code the dialog width
					style: "width:400px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialogLectura.show();
			}
			function muestra_dialogo_agua(id_parcela){
				var myDialog = new dijit.Dialog({
					// The dialog's title
					title: "Lectura de contadores: Parcela "+id_parcela,
					// The dialog's content
					href: "xhr/formulario_agua.php?id_parcela="+id_parcela,
					// Hard-code the dialog width
					style: "width:550px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialog.show();
			}
			function muestra_dialogo_riego(id_contador){
				var myDialog = new dijit.Dialog({
					// The dialog's title
					title: "Lectura de contadores: Contador "+id_contador,
					// The dialog's content
					href: "xhr/formulario_riego.php?id_contador="+id_contador,
					// Hard-code the dialog width
					style: "width:550px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialog.show();
			}
			function actualiza_datos_agua() {
				var miForm=dijit.byId('formulario_agua');
				var nodoResultado=dojo.byId('resultado');
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/actualiza_agua.php",
						form: dojo.byId('formulario_agua'),
						handleAs: "text",
						load: function (result) {
							console.log('se ejecuta load');
							console.log(result);
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							//nodoResultado=dojo.byId('resultado');
							nodoResultado.innerHTML=data;
							//console.log(args);
							var clave=dijit.byId('filtroSelectAgua');
							actualiza_listado_agua(clave);
						}
					});
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="por aqui vamos mal";
				}
			}
			function actualiza_datos_riego() {
				var miForm=dijit.byId('formulario_riego');
				var nodoResultado=dojo.byId('resultado');
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/actualiza_riego.php",
						form: dojo.byId('formulario_riego'),
						handleAs: "text",
						load: function (result) {
							console.log('se ejecuta load');
							console.log(result);
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							//nodoResultado=dojo.byId('resultado');
							nodoResultado.innerHTML=data;
							//console.log(args);
							var clave=dijit.byId('filtroSelectRiego');
							actualiza_listado_riego(clave);
						}
					});
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="por aqui vamos mal";
				}
			}
			function actualiza_listado_agua(){
				fecha=dijit.byId('fechaLecturaAgua').get('value');
				filtro=dijit.byId('filtroSelectAgua').get('value');
				console.log('fecha: '+fecha+' filtro: '+filtro);
				grid_agua=dijit.byId('grid_agua');
				grid_agua.store.close();
				grid_agua.store.url="xhr/agua.php?fecha="+fecha+"&filtro="+filtro;
				grid_agua.sort();
				actualiza_filas_agua();
				actualiza_estadisticas_agua();
			}
			function actualiza_listado_riego(){
				fecha=dijit.byId('fechaLecturaRiego').get('value');
				filtro=dijit.byId('filtroSelectRiego').get('value');
				console.log('fecha: '+fecha+' filtro: '+filtro);
				grid_riego=dijit.byId('grid_riego');
				grid_riego.store.close();
				grid_riego.store.url="xhr/riego.php?fecha="+fecha+"&filtro="+filtro;
				grid_riego.sort();
				actualiza_filas_riego();
				actualiza_estadisticas_riego();
			}
			function actualiza_filas_agua(){
				grid_agua=dijit.byId('grid_agua');
				var filas=0;
				var nodoResultado=dojo.byId('resultado');
				grid_agua.store.fetch({
					onComplete: function(items) {
						dojo.forEach(items, function(){
							filas++;
						});	
						console.log('filas: '+filas);
						nodoResultado.innerHTML=filas+' contadores';
					}
				});
			}
			function actualiza_filas_riego(){
				grid_riego=dijit.byId('grid_riego');
				var filas=0;
				var nodoResultado=dojo.byId('resultado');
				grid_riego.store.fetch({
					onComplete: function(items) {
						dojo.forEach(items, function(){
							filas++;
						});	
						console.log('filas: '+filas);
						nodoResultado.innerHTML=filas+' contadores';
					}
				});
			}
			function actualiza_estadisticas_agua(){
				fecha=dijit.byId('fechaLecturaAgua').get('value');
				console.log('fecha: '+fecha);
				dojo.xhrGet({
				 // The URL of the request
				url: "xhr/estadisticas_agua.php?fecha="+fecha,
				// The success callback with result from server
				load: function(newContent) {
					dojo.byId("estadisticas").innerHTML = newContent;
					//dojo.byId("mensaje").innerHTML = "Datos actualizados";
				},
				// The error handler
				error: function() {
				// Do nothing -- keep old content there
				}
				});
			}
			function actualiza_estadisticas_riego(){
				fecha=dijit.byId('fechaLecturaRiego').get('value');
				console.log('fecha: '+fecha);
				dojo.xhrGet({
				 // The URL of the request
				url: "xhr/estadisticas_riego.php?fecha="+fecha,
				// The success callback with result from server
				load: function(newContent) {
					dojo.byId("estadisticas").innerHTML = newContent;
					//dojo.byId("mensaje").innerHTML = "Datos actualizados";
				},
				// The error handler
				error: function() {
				// Do nothing -- keep old content there
				}
				});
			}
			function genera_csv_agua() {
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				var clave=dijit.byId("filtroSelectAgua");
				var fecha=dijit.byId('fechaLecturaAgua').get('value');
				dojo.xhrGet({
					url: "xhr/agua2csv.php?filtro="+clave+"&fecha="+fecha,
					handleAs: "text",
					headers: {
						"Content-Type": "text/csv",
						"Content-Encoding": "ISO-8859-1",
						"X-Method-Override": "FANCY-GET"
 					},
					load: function (resultado) {
						console.log('se ejecuta load');
						//console.log(resultado);
						//window.open('temp/agua.csv');
					},
					error: function() {
						//console.log('error');
					},
					handle: function(data,args) {
						//console.log(args);
						console.log('desde handle');
						console.log(data);
						window.open('temp/agua.csv');
						nodoResultado.innerHTML='<a href="temp/agua.csv" title="agua.csv">Descargar fichero</a>';
					}
				});
			}
			function genera_csv_iva() {
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				var clave=dijit.byId("filtroSelectAgua");
				var fecha=dijit.byId('fechaLecturaAgua').get('value');
				dojo.xhrGet({
					url: "xhr/iva2csv.php?filtro="+clave+"&fecha="+fecha,
					handleAs: "text",
					headers: {
						"Content-Type": "text/csv",
						"Content-Encoding": "ISO-8859-1",
						"X-Method-Override": "FANCY-GET"
 					},
					load: function (resultado) {
						console.log('se ejecuta load');
						//console.log(resultado);
						//window.open('temp/agua_iva.csv');
					},
					error: function() {
						//console.log('error');
					},
					handle: function(data,args) {
						//console.log(args);
						console.log('desde handle');
						console.log(data);
						window.open('temp/agua_iva.csv');
						nodoResultado.innerHTML='<a href="temp/agua_iva.csv" title="agua_iva.csv">Descargar fichero</a>';
					}
				});
			}
			function genera_csv_riego() {
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				var clave=dijit.byId("filtroSelectRiego");
				var fecha=dijit.byId('fechaLecturaRiego').get('value');
				dojo.xhrGet({
					url: "xhr/riego2csv.php?filtro="+clave+"&fecha="+fecha,
					handleAs: "text",
					headers: {
						"Content-Type": "text/csv",
						"Content-Encoding": "ISO-8859-1",
						"X-Method-Override": "FANCY-GET"
 					},
					load: function (resultado) {
						console.log('se ejecuta load');
						//console.log(resultado);
						//window.open('temp/riego.csv');
					},
					error: function() {
						//console.log('error');
					},
					handle: function(data,args) {
						//console.log(args);
						console.log('desde handle');
						console.log(data);
						window.open('temp/riego.csv');
						nodoResultado.innerHTML='<a href="temp/riego.csv" title="riego.csv">Descargar fichero</a>';
					}
				});
			}
			function genera_remesa() {
				var miForm=dijit.byId('form_remesa');
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				resultadoRemesa=dojo.byId('resultado_remesa');
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					var respuesta=confirm("Ha solicitado generar una remesa");
					if(respuesta){
					dojo.xhrPost({
						url: "xhr/escribe_remesa_agua_sepa.php",
						form: dojo.byId('form_remesa'),
						handleAs: "text",
						load: function (resultado) {
							console.log('se ejecuta load');
							console.log(resultado);
							nodoResultado.innerHTML='Remesa generada con éxito';
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							//console.log(args);
							console.log('desde handle');
							console.log(data);
							resultadoRemesa.innerHTML=data;
						}
					});
					}
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="por aqui vamos mal";
				}
			}
			function genera_lectura() {
				var miForm=dijit.byId('form_lectura');
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					var respuesta=confirm("Crear una nueva fecha de lectura?");
					if(respuesta){
					dojo.xhrPost({
						url: "xhr/crea_lectura_agua.php",
						form: dojo.byId('form_lectura'),
						handleAs: "text",
						load: function (resultado) {
							console.log('se ejecuta load');
							console.log(resultado);
							nodoResultado.innerHTML='Nueva fecha de lectura creada';
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							//console.log(args);
							var clave=dijit.byId("filtroSelectAgua");
							actualiza_listado_agua(clave);
							actualiza_estadisticas_agua();
							console.log('desde handle');
							console.log(data);
							dojo.byId("resultado_lectura").innerHTML=data;
						}
					});
					}
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="por aqui vamos mal";
				}
			}
			function gotolink(enlace){
                        	//console.log('el link:'+enlace);
	                        window.location.href=enlace;
			}
		</script>
	</head>
	<body class="claro">
	<div dojoType="dijit.layout.BorderContainer" design="sidebar" gutters="true" liveSplitters="true" id="borderContainer">
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="left" style="width:150px;border:none;">
			<img src="../images/openrarp_logo.png" alt="logo" />
			<div id="estadisticas"></div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="top" style="border:none">
			<div id="menubar" data-dojo-type="dijit.MenuBar">
                                <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('../home.php');}">
                                        Inicio
                                </div>
                                <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('socios.php');}">
                                        Socios 
                                </div>
                                <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('agua.php');}">
                                        Agua
                                </div>
                                <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('cuotas.php');}">
                                        Cuotas
                                </div>
                                <div data-dojo-type="dijit.MenuBarItem" data-dojo-props="onClick:function(){gotolink('remesas.php');}">
                                        Remesas
                                </div>
				<div class="login" dojoType="dijit.form.DropDownButton" >
					<span><?=$_SESSION['name']?> </span>
					<div dojoType="dijit.Menu">
		       	    		     	<div dojoType="dijit.MenuItem" data-dojo-props="onClick:function(){gotolink('../logout.php');}">
		                   			Salir 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="myTabContainer" data-dojo-type="dijit.layout.TabContainer" data-dojo-props="region:'center'">
			<div id="residenciales" data-dojo-type="dijit.layout.ContentPane" splitter="true" title="Residenciales" style="border:none">
				<div>
					<label for="fechaSelectAgua" style="color:gray">
						Fecha Lectura:
					</label>
					<span id="fechaSelectAgua"></span>
					<label for="filtroSelectAgua" style="padding-left:2em;color:gray">
						Mostrar: 
					</label>
					<select dojoType="dijit.form.FilteringSelect" id="filtroSelectAgua" promptMessage="Selecciona..." onChange="actualiza_listado_agua(this)">
						<option value="T" selected>Todos</option>
						<option value="D">Domiciliados</option>
						<option value="S">Sin domiciliar</option>
						<option value="A">Contadores inactivos</option>
						<option value="F">Consumo signif. alto</option>
						<option value="B">Consumo signif. bajo</option>
					</select>
					<span style="padding-left:3em">	
						<input id="checkBox" /> 	
						<label for="checkBox">Ver estadísticos</label>
					</span>
					<div style="float:right" id="acciones_agua"></div>
				</div>
				<div id="grid_agua"></div>
			</div>
			<div id="riego" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="style:'padding:1em'" title="Zonas verdes">
                                        <script type="dojo/method">
                                              console.log('dentro del riego');
                                        </script>
				<div>
					<label for="fechaSelectRiego" style="color:gray"> Fecha Lectura: </label>
					<span id="fechaSelectRiego"></span>
					<label for="filtroSelectRiego" style="padding-left:2em;color:gray"> Mostrar: </label>
					<select dojoType="dijit.form.FilteringSelect" id="filtroSelectRiego" promptMessage="Selecciona..." onChange="actualiza_listado(this)">
						<option value="T" selected>Todos</option>
						<option value="A">Contadores inactivos</option>
					</select>
					<div style="float:right" id="acciones_riego"></div>
				</div>
				<div id="grid_riego"></div>
			</div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="bottom">
			<div id="resultado" class="results"></div>
		</div>
	</div>
    </body>
</html>
