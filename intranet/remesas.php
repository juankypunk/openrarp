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
			.dojoxGrid table {font-size: 80%;}
			.grid { width: 100%;}
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
		</style>
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
			require(["../js/counterTextArea.js", "dojo/domReady!" ], function (counterTextArea) {
	    		});
			dojo.ready(function(){
				var grid, store, fechasStore;
				store = new dojox.data.QueryReadStore({
					url: "xhr/remesas.php"
				});
				var layoutRemesas = [
				{
					type: "dojox.grid._CheckBoxSelector"
				},
				[[
					{
						field: "id_parcela",
						name: "Parcela",
						width: "10"
					},
					{
						field: "titular",
						name: "Nombre",
						width: "auto"
					},
					{
						field: "bic",
						name: "BIC",
						width: "auto"
					},
					{
						field: "iban",
						name: "IBAN",
						width: "auto"
					},
					{
						field: "importe",
						cellStyles: "text-align:right;",
						name: "Domiciliado (€)",
						width: "auto"
					},
					{
						field: "concepto",
						name: "Concepto",
						width: "auto"
			    		}]]];
				grid = new dojox.grid.DataGrid({
					"class": "grid",
					store: store,
					noDataMessage: "No se han encontrado registros",
					autoHeight: false,
					autoRender: true,
					rowsPerPage: "50",
					onRowClick: function(e){
				           this.edit.rowClick(e);
				           //this.selection.clickSelectEvent(e);
				        },
					query: { id_parcela: '*' },
					
					selectionMode: "multiple",
					structure: layoutRemesas
				}, "grid");
				grid.startup();
				dojo.connect(grid, "onRowDblClick", grid, function(evt){
					var idx = evt.rowIndex,
					item = this.getItem(idx);
					id_parcela=this.store.getValue(item, "id_parcela");
					dojo.byId("resultado").innerHTML = "Has hecho click en " 
						+ this.store.getValue(item, "id_parcela") + ", "
						+ this.store.getValue(item, "titular_cc") + ".";
						muestra_dialogo_act_adeudo(id_parcela);
				});	
				actualiza_filas();
				var utils_menu = new dijit.Menu({
					id: "accionesMenu"
				});
				var utils_menuItem0 = new dijit.MenuItem({
					label: "Generar remesa especial SEPA",
					iconClass: "dijitEditorIcon dijitEditorIconSave",
					onClick: muestra_dialogo_remesa
				});
				var utils_menuItem1 = new dijit.MenuItem({
					label: "Eliminar adeudos seleccionados",
					iconClass: "dijitEditorIcon dijitEditorIconDelete",
					onClick: elimina_adeudos
				});
				utils_menu.addChild(utils_menuItem0);
				utils_menu.addChild(utils_menuItem1);
				var utilsButton = new dijit.form.DropDownButton({
					optionsTitle: "Opciones",
					label: "Acciones",
					style: "padding-left: 1.2em",
					dropDown: utils_menu,
					onClick:function(){ console.log("Clicked ComboButton"); }
				}, "acciones");
				//utilsButton.startup();
				//utils_menu.startup(); // this also starts up its child MenuItems
			}); // fin de dojo.ready()
			function muestra_dialogo_nuevo_adeudo(){
				var myDialogCuota = new dijit.Dialog({
					// The dialog's title
					title: "Nuevo adeudo",
					id: "dialogo_nuevo_adeudo",
					// The dialog's content
					href: "xhr/formulario_nuevo_adeudo.php",
					// Hard-code the dialog width
					style: "width:400px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialogCuota.show();

			}
			function muestra_dialogo_act_adeudo(id_parcela){
				var myDialog = new dijit.Dialog({
					// The dialog's title
					title: "Adeudo "+id_parcela,
					id: "dialogo_act_adeudo",
					// The dialog's content
					href: "xhr/formulario_actualiza_adeudo.php?id_parcela="+id_parcela,
					// Hard-code the dialog width
					style: "width:400px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialog.show();
			}
			function muestra_dialogo_remesa(){
				var myDialogRemesa = new dijit.Dialog({
					// The dialog's title
					title: "Generar remesa",
					// The dialog's content
					href: "xhr/formulario_remesa_cuotas.php",
					// Hard-code the dialog width
					style: "width:600px;",
					onCancel: function(){
						this.destroyRecursive();
						console.log('dialogo cancelado');
					}
				});
				myDialogRemesa.show();
			}
			function escribe_adeudo() {
				var miForm=dijit.byId('form_nuevo_adeudo');
				//console.log(miForm);
				if(miForm.isValid()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/escribe_nuevo_adeudo.php",
						form: dojo.byId('form_nuevo_adeudo'),
						handleAs: "text",
						load: function (resultado) {
							console.log('se ejecuta load');
							console.log(resultado);
						},
						error: function() {
							//console.log('error');
						},
						handle: function(data,args) {
							//console.log(args);
							console.log('desde handle');
							console.log(data);
							var dialogo=dijit.byId("dialogo_nuevo_adeudo");
							actualiza_listado();
							dialogo.destroyRecursive();
						}
					});
				}else{
					console.log('form con errores');
				}
			}
			function actualiza_adeudo() {
				var miForm=dijit.byId('form_actualiza_adeudo');
				var nodoResultado=dojo.byId('resultado');
				//console.log(miForm);
				if(miForm.validate()){
					console.log('contenido del form OK');
					dojo.xhrPost({
						url: "xhr/actualiza_adeudo.php",
						form: dojo.byId('form_actualiza_adeudo'),
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
							var dialogo=dijit.byId("dialogo_act_adeudo");
							actualiza_listado();
							dialogo.destroyRecursive();
						}
					});
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="por aqui vamos mal";
				}
			}
			function elimina_adeudos() {
				var seleccion=seleccionMultiple();
				console.log(seleccion);
				var nodoResultado=dojo.byId('resultado');
				//console.log(miForm);
				dojo.xhrPost({
					url: "xhr/elimina_adeudos.php?seleccion="+seleccion,
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
						actualiza_listado();
					}
				});
			}
			function actualiza_listado(){
				grid=dijit.byId('grid');
				grid.store.close();
				grid.store.url="xhr/remesas.php";
				grid.store.fetch();
				grid.sort();
				//actualiza_filas();
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
						nodoResultado.innerHTML=filas+' adeudos';
					}
				});
			}
			function valida_iban(value,constraints){
				console.log(value);
				if(IBAN.isValid(value)){
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
			function genera_remesa() {
				var seleccion=seleccionMultiple();
				//console.log(seleccion);
				var miForm=dijit.byId('form_remesa');
				resultadoRemesa=dojo.byId('resultado_remesa');
				nodoResultado=dojo.byId("resultado");
				if(seleccion==''){
					resultadoRemesa.innerHTML="<span style='color:red'>Atención: no se han seleccionado adeudos</span>";
				}
				//console.log(miForm);
				if(miForm.isValid() && seleccion!=''){
					console.log('contenido del form OK');
					var respuesta=confirm("Ha solicitado generar una remesa");
					if(respuesta){
					dojo.xhrPost({
						url: "xhr/escribe_remesa_especial_sepa.php?seleccion="+seleccion,
						form: dojo.byId('form_remesa'),
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
							resultadoRemesa.innerHTML=data;
						}
					});
					}
				}else{
					console.log('form con errores');
					nodoResultado.innerHTML="Atención: no se han seleccionado adeudos";

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
			<span dojoType="dijit.form.Button" onClick="muestra_dialogo_nuevo_adeudo();">
                                         Nuevo adeudo
                        </span>
			<span style="float:right" id="acciones"></span>
			<div id="grid"/>
		</div>
		<div dojoType="dijit.layout.ContentPane" splitter="false" region="bottom">
			<div id="resultado" class="results"></div>
		</div>
	</div>
    </body>
</html>
