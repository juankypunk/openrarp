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
		<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.1/dojo/dojo.js" data-dojo-config="parseOnLoad: true"></script>
		<!--<script src="../js/dojo-release/dojo/dojo.js" djConfig="parseOnLoad: true"></script>-->
		<script type="text/javascript">
			dojo.require('dojo.parser');
			dojo.require('dojox.validate');
			dojo.require('dojox.validate.web');
        		dojo.require("dojox.grid.DataGrid");
			dojo.require("dijit.form.FilteringSelect");
			dojo.require("dojox.data.QueryReadStore");
			dojo.require("dijit.layout.ContentPane");
			dojo.require("dijit.layout.BorderContainer");
			dojo.require("dijit.form.Form");
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
				function formatEstado(value){
				    switch(value){
					case "R":
					    return "<img src=\'images/luz_naranja.gif\' alt=\'En curso\' >";
					case "C":
					    return "<img src=\'images/luz_roja.gif\' alt=\'Cerrada\' >";
				    }
				}
				var grid, store, fechasStore;
				store = new dojox.data.QueryReadStore({
					url: "xhr/cuotas.php"
				});
				fechasStore = new dojox.data.QueryReadStore({
					url: "xhr/fechas_cuota.php"
				});
				var fechaFilteringSelect = new dijit.form.FilteringSelect({
			            id: "fechaCuota",
			            name: "fecha",
			            value: "",
				    placeHolder: 'Selecciona una fecha...',
			            store: fechasStore,
			            searchAttr: "name",
				    onChange: function(fechaCuota){
						actualiza_listado();
					}
			        }, "fechaSelect");
				var layoutCuotas = [
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
						width: "auto"
					},
					{
						field: "fecha",
						name: "Fecha",
						width: "auto"
					},
					{
						field: "cuota",
						cellStyles: "text-align:right;",
						name: "Cuota (€)",
						width: "auto"
					},
					{
						field: "dto",
						cellStyles: "text-align:right;",
						name: "Descuento (%)",
						width: "auto"
					},
					{
						field: "domiciliado",
						cellStyles: "text-align:right;",
						name: "Domiciliado (€)",
						width: "auto"
			    		}]];
				grid = new dojox.grid.DataGrid({
					"class": "grid",
					store: store,
					rowsPerPage: "350",
					query: { id_parcela: '*' },
					structure: layoutCuotas
				}, "grid");
				grid.startup();
				dojo.connect(grid, "onRowDblClick", grid, function(evt){
					var idx = evt.rowIndex,
					item = this.getItem(idx);
					id_parcela=this.store.getValue(item, "id_parcela");
					estado=this.store.getValue(item,"estado");
					dojo.byId("resultado").innerHTML = "Has hecho click en " 
						+ this.store.getValue(item, "id_parcela") + ", "
						+ this.store.getValue(item, "titular") + ".";
					if(estado != 'C'){
						muestra_dialogo(id_parcela);
					}
				});	
				actualiza_estadisticas();
				actualiza_filas();
				var utils_menu = new dijit.Menu({
					id: "accionesMenu"
				});
				var utils_menuItem0 = new dijit.MenuItem({
					label: "Descargar hoja de c&aacute;lculo",
					iconClass: "",
					onClick: genera_csv
				});
				var utils_menuItem1 = new dijit.MenuItem({
					label: "Generar remesa SEPA",
					iconClass: "dijitEditorIcon dijitEditorIconSave",
					onClick: muestra_dialogo_remesa
				});
				var utils_menuItem2 = new dijit.MenuItem({
					label: "Nueva cuota",
					iconClass: "dijitIconFile",
					onClick: muestra_dialogo_cuota
				});
				utils_menu.addChild(utils_menuItem0);
				utils_menu.addChild(utils_menuItem1);
				utils_menu.addChild(utils_menuItem2);
				var utilsButton = new dijit.form.DropDownButton({
					optionsTitle: "Opciones",
					label: "Utilidades",
					style: "padding-left: 1.2em",
					dropDown: utils_menu,
					onClick:function(){ console.log("Clicked ComboButton"); }
				}, "acciones");
				//utilsButton.startup();
				//utils_menu.startup(); // this also starts up its child MenuItems
			}); // fin de dojo.ready()
			function muestra_dialogo_remesa(){
				var myDialogRemesa = new dijit.Dialog({
					// The dialog's title
					title: "Generar remesa SEPA",
					// The dialog's content
					href: "xhr/formulario_remesa_cuotas.php",
					// Hard-code the dialog width
					style: "width:400px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialogRemesa.show();
			}
			function muestra_dialogo_cuota(){
				var myDialogCuota = new dijit.Dialog({
					// The dialog's title
					title: "Nueva cuota",
					// The dialog's content
					href: "xhr/formulario_nueva_cuota.php",
					// Hard-code the dialog width
					style: "width:400px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialogCuota.show();
			}
			function muestra_dialogo(id_parcela){
				var myDialog = new dijit.Dialog({
					// The dialog's title
					title: "Cuotas parcela "+id_parcela,
					// The dialog's content
					href: "xhr/formulario_cuotas.php?id_parcela="+id_parcela,
					// Hard-code the dialog width
					style: "width:500px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialog.show();
			}
			function actualiza_datos() {
				var miForm=dijit.byId('formulario_cuotas');
				var nodoResultado=dojo.byId('resultado');
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/actualiza_cuota.php",
						form: dojo.byId('formulario_cuotas'),
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
							var clave=dijit.byId('filtroSelect');
							actualiza_listado(clave);
						}
					});
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="por aqui vamos mal";
				}
			}
			function actualiza_listado(){
				fecha=dijit.byId('fechaCuota').get('value');
				filtro=dijit.byId('filtroSelect').get('value');
				console.log('fecha: '+fecha+' filtro: '+filtro);
				grid=dijit.byId('grid');
				grid.store.close();
				grid.store.url="xhr/cuotas.php?fecha="+fecha+"&filtro="+filtro;
				grid.store.fetch();
				grid.sort();
				actualiza_filas();
				actualiza_estadisticas();
			}
			function actualiza_filas(){
				grid=dijit.byId('grid');
				var filas=0;
				var nodoResultado=dojo.byId('resultado');
				grid.store.fetch({
					onComplete: function(items) {
						dojo.forEach(items, function(){
							filas++;
						});	
						console.log('filas: '+filas);
						nodoResultado.innerHTML=filas+' socios';
					}
				});
			}
			function actualiza_estadisticas(){
				fecha=dijit.byId('fechaCuota').get('value');
				dojo.xhrGet({
				 // The URL of the request
				url: "xhr/estadisticas_cuotas.php?fecha="+fecha,
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
			function genera_csv() {
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				var clave=dijit.byId("filtroSelect");
				var fecha=dijit.byId('fechaCuota').get('value');
				dojo.xhrGet({
					url: "xhr/cuotas2csv.php?filtro="+clave+"&fecha="+fecha,
					handleAs: "text",
					headers: {
						"Content-Type": "text/csv",
						"Content-Encoding": "ISO-8859-1",
						"X-Method-Override": "FANCY-GET"
 					},
					load: function (resultado) {
						console.log('se ejecuta load');
						//console.log(resultado);
					},
					error: function() {
						//console.log('error');
					},
					handle: function(data,args) {
						//console.log(args);
						console.log('desde handle');
						console.log(data);
						window.open('temp/cuotas.csv');
						nodoResultado.innerHTML='<a href="temp/cuotas.csv" title="cuotas.csv">Descargar fichero</a>';
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
						url: "xhr/escribe_remesa_cuotas_sepa.php",
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
			function genera_cuotas() {
				var miForm=dijit.byId('form_cuota');
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					var respuesta=confirm("Crear una nueva fecha de cuota?");
					if(respuesta){
					dojo.xhrPost({
						url: "xhr/crea_cuotas.php",
						form: dojo.byId('form_cuota'),
						handleAs: "text",
						load: function (resultado) {
							console.log('se ejecuta load');
							console.log(resultado);
							nodoResultado.innerHTML='Nueva fecha de cuota creada';
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							//console.log(args);
							var clave=dijit.byId("filtroSelect");
							actualiza_listado(clave);
							actualiza_estadisticas();
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
		<div dojoType="dijit.layout.ContentPane" splitter="true" region="center" style="border:none">
			<div>
				<label for="fechaSelect" style="color:gray">
					Fecha cuota:
				</label>
				<span id="fechaSelect"></span>
				<label for="filtroSelect" style="color:gray">
					Mostrar: 
				</label>
				<select dojoType="dijit.form.FilteringSelect" id="filtroSelect" promptMessage="Selecciona..." onChange="actualiza_listado(this)">
					<option value="T" selected>Todos</option>
					<option value="D">Domiciliados</option>
					<option value="S">Sin domiciliar</option>
				</select>
				<div style="float:right" id="acciones"></div>
			</div>
			<div id="grid"></div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="bottom">
			<div id="resultado" class="results"></div>
		</div>
	</div>
    </body>
</html>
