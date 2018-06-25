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
		</style>
                <!--<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.1/dojo/dojo.js" data-dojo-config="parseOnLoad: true"></script>-->
		<script src="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojo/dojo.js" data-dojo-config="parseOnLoad: true"></script>
		<!--<script src="../js/dojo-release/dojo/dojo.js" djConfig="parseOnLoad: true"></script>-->
		<script src="../js/iban.js"></script>
		<script type="text/javascript">
			dojo.require('dojo.parser');
			dojo.require('dojox.validate');
			dojo.require('dojox.validate.web');
        		dojo.require("dojox.grid.DataGrid");
        		dojo.require("dojo._base.array");
        		dojo.require("dojo._base.lang");
			dojo.require("dojox.grid._CheckBoxSelector");
			dojo.require("dojo.data.ItemFileReadStore");
			dojo.require("dojox.data.QueryReadStore");
			dojo.require("dijit.layout.ContentPane");
			dojo.require("dijit.layout.BorderContainer");
			dojo.require("dijit.form.Form");
			dojo.require("dijit.form.FilteringSelect");
			dojo.require("dijit.form.DropDownButton");
			dojo.require("dijit.Menu");
			dojo.require("dijit.MenuBar");
			dojo.require("dijit.MenuBarItem");
			dojo.require("dijit.MenuItem");
			dojo.require("dijit.layout.TabContainer");
			dojo.require("dijit.form.TextBox");
			dojo.require("dijit.form.Textarea");
			dojo.require("dijit.form.ValidationTextBox");
        		dojo.require("dijit.Editor");
			dojo.require("dijit.form.Button");
			// Require the Dialog class
			dojo.require("dijit.Dialog");
			dojo.ready(function(){
				var socioStore = new dojo.data.ItemFileReadStore({
				    	url: "xhr/socioStore.php"
       			     	});
		            	var filteringSelect = new dijit.form.FilteringSelect({
                		id: "socioSelect",
              		  	name: "id_parcela",
              		  	value: "",
				hasDownArrow: false,
				required: false,
				style:"position:fixed;left:550px;top:17px;width:300px;",
               		 	store: socioStore,
				queryExpr: "*${0}*",
				searchDelay: 1000,
				placeHolder: "(teclea un nombre...)",
                		searchAttr: "name",
				onChange: function(socioSelect) {
					var id_parcela=socioSelect;
					console.log('id_parcela: '+socioSelect);
					if(id_parcela){
						muestra_dialogo(id_parcela);
					}
				}}, "socioSelect");
				var grid, store;
				store = new dojox.data.QueryReadStore({
					url: "xhr/socios.php"
				});
				function formatTitular(fields){
					if (fields[1]=== null){
						return fields[0];
					}else{
						return fields[0]+'<br>'+fields[1];
					}
				}
				var layoutSocios = [
					{
					type: "dojox.grid._CheckBoxSelector"
					},
					[[
					{
					field: "id_parcela",
					name: "Parcela",
					width: "5"
					},
					{
					fields: ["titular","titular2"],
					name: "Nombre",
					width: "auto",
					formatter: formatTitular
					},
					{
					field: "email",
					name: "email",
					width: "auto"
					},
					{
					field: "domicilio",
					name: "Domicilio",
					width: "auto"
					},
					{
					field: "localidad",
					name: "Localidad",
					width: "10"
					},
					{
					field: "telef1",
					name: "Tel. 1",
					width: "10"
					},
					{
					field: "telef2",
					name: "Tel. 2",
					width: "10"
			 	   }]]];

				grid = new dojox.grid.DataGrid({
					"class": "grid",
					store: store,
					rowsPerPage: "300",
					onRowClick: function(e){
				           this.edit.rowClick(e);
				           //this.selection.clickSelectEvent(e);
				        },
					query: { id: "*" },
					selectionMode: "multiple",
					structure: layoutSocios
				}, "grid");
				grid.startup();
				dojo.connect(grid, "onRowDblClick", grid, function(evt){
					var idx = evt.rowIndex,
					item = this.getItem(idx);
					id_parcela=this.store.getValue(item, "id_parcela");
					dojo.byId("resultado").innerHTML = "Has hecho click en " 
						+ this.store.getValue(item, "id_parcela") + ", "
						+ this.store.getValue(item, "titular") + ".";
					muestra_dialogo(id_parcela);
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
					label: "Enviar mail",
					iconClass: "",
					onClick: muestra_dialogo_mail
				});
				utils_menu.addChild(utils_menuItem0);
				utils_menu.addChild(utils_menuItem1);
				var utilsButton = new dijit.form.DropDownButton({
					optionsTitle: "Opciones",
					label: "Utilidades",
					style:"position:fixed;right:150px;top:12px;",
					dropDown: utils_menu,
					onClick:function(){ console.log("Clicked ComboButton"); }
				}, "acciones");
				utilsButton.startup();
				utils_menu.startup(); // this also starts up its child MenuItems
			}); // fin de dojo.ready()
			function muestra_dialogo(id_parcela){
				var myDialog = new dijit.Dialog({
					// The dialog's title
					title: "Datos del socio",
					// The dialog's content
					href: "xhr/formulario_socios.php?id_parcela="+id_parcela,
					// Hard-code the dialog width
					style: "width:850px;"
				});
				myDialog.show();
				formulario=dijit.byId('formulario');
				boton=dijit.byId('submitButton');
				if(formulario){
					formulario.destroyRecursive();
					boton.destroy();
				}
			}
			function muestra_dialogo_mail(){
				seleccion=seleccionMultiple();
				console.log('selecc.:'+seleccion);
				dialogo=dijit.byId('dialogo_mail');
				if(seleccion==''){
					alert('Atención: no hay parcelas seleccionadas');
				}else{
					dialogo=dijit.byId('dialogo_mail');
					if(dialogo){
						dialogo.destroyRecursive();
					}
					var myDialog = new dijit.Dialog({
						// The dialog's title
						title: "Enviar correo",
						id: "dialogo_mail",
						// The dialog's content
						href: "xhr/formulario_socios_mail.php?seleccion="+seleccion,
						// Hard-code the dialog width
						style: "width:700px;"
					});
					myDialog.show();
					formulario=dijit.byId('form_correo');
					boton=dijit.byId('submitCorreo');
					if(formulario){
						formulario.destroyRecursive();
						boton.destroy();
					}
				}	
			}
			function valida_iban(value,constraints){
				//console.log(value);
				if(IBAN.isValid(value) || value==''){
					return true;
				}else{
					return false;
				}
			}
			function seleccionMultiple(){
				var items = grid.selection.getSelected(),
				msg = "You have selected rows ";
				var tmp = dojo.map(items, function(item){
					return grid.store.getValue(item, "id_parcela");
				}, this);
				//node.innerHTML = msg + tmp.join(", ");
				//var num_reg=this.store.getValue(item,"num_reg");
				console.log('seleccionado: '+tmp);
				return tmp;
			}
			function envia_correo() {
				//console.log(seleccion);
				var miForm=dijit.byId('form_correo');
				nodoResultado=dojo.byId('resultado');
				//console.log(miForm);
				if(miForm.isValid()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/envia_correo.php",
						form: dojo.byId('form_correo'),
						handleAs: "text",
						load: function (resultado) {
							console.log('se ejecuta load');
							console.log(resultado);
						},
						error: function() {
							console.log('error');
						},
						handle: function(data,args) {
							console.log(args);
							console.log('desde handle');
							console.log(data);
							nodoResultado.innerHTML=data;
							dialogo=dijit.byId('dialogo_mail');
							formulario=dijit.byId('form_correo');
							boton=dijit.byId('submitCorreo');
							if(formulario){
								formulario.destroyRecursive();
								boton.destroy();
							}
							dialogo.destroyRecursive();
						}
					});
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="Atención: no se ha enviado el correo";
				}
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
						nodoResultado.innerHTML=filas+' parcelas';
					}
				});
			}
			function genera_csv() {
				nodoResultado=dojo.byId("resultado");
				nodoResultado.innerHTML="";
				var clave=dijit.byId("filtroSelect");
				dojo.xhrGet({
					url: "xhr/socios2csv.php",
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
						console.log('error');
					},
					handle: function(data,args) {
						//console.log(args);
						console.log('desde handle');
						console.log(data);
						//window.open('temp/socios.csv');
						nodoResultado.innerHTML='<a href="temp/socios.csv" title="socios.csv">Descargar fichero</a>';
					}
				});
			}
			function actualiza_estadisticas(){
				dojo.xhrGet({
				 // The URL of the request
				url: "xhr/estadisticas_socios.php",
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
			function actualiza_datos() {
				var miForm=dijit.byId('formulario');
				var nodoResultado=dojo.byId('resultado');
				console.log(miForm);
				if(miForm.isValid()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/actualiza_socio.php",
						form: dojo.byId('formulario'),
						handleAs: "text",
						load: function (result) {
							console.log('se ejecuta load');
							console.log(result);
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							confirm("Datos actualizados");
							//nodoResultado=dojo.byId('resultado');
							//nodoResultado.innerHTML="por aqui vamos bien";
							//console.log(args);
							grid=dijit.byId('grid');
							grid.store.close();
							grid.store.url="xhr/socios.php";
							grid.store.fetch();
							grid.sort();
						}	
					});
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
	<body class=" claro ">
	<div dojoType="dijit.layout.BorderContainer" design="sidebar" gutters="true" liveSplitters="true" id="borderContainer">
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="left" style="width:150px;border:none;">
			<div class="logo">
				<img src="../images/openrarp_logo.png" alt="logo" />
				<div id="estadisticas"></div>
			</div>
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
			<div id="socioSelect"> </div>
			<div id="acciones"></div>
		        <div id="grid"></div>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="bottom">
			<div id="resultado" class="results"></div>
		</div>
	</div>
    </body>
</html>
