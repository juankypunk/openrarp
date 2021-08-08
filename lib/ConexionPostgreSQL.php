<?
/*
 * OPENRARP: Software de Gestión para Areas Residenciales
 * Copyright (c) 2021 Juan Carlos Moral
 *
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published 
 * by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty 
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 */

// La clase "plantilla" de la que heredamos
require ("lib/ConexionGenerica.php");

/**
 * CLASE ConexionPostgreSQL
 * Métodos para conectar e interactuar con una BD PosgreSQL.
 */
class ConexionPostgreSQL extends ConexionGenerica {

	/**
	 * Constructor. No hace nada. Se podría quitar ...
	 */
	function ConexionPostgreSQL() {}

	/**
	 * FUNCIÓN connect( bd )
	 * Conecta con la base de datos en el servidor.
	 */
	function connect($bd) {
//		return $this->link = pg_connect("localhost","5432","", $bd);
		return $this->link = pg_connect("host=localhost port=5432 dbname=$bd user=postgres");
	}

	/**
	 * FUNCIÓN close()
	 * Finaliza la conexion con el servidor.
	 */
	function close() {
		return pg_close($this->link);
	}


	/**
	 * FUNCIÓN query( query )
	 * Realiza una consulta o actualización en la BD.
	 */
	function query($query) {
		return pg_query($this->link, $query);
	}
	
	function queryExec($query){
		$result = pg_exec($this->link, $query);
		if (pg_errormessage($this->link)){
			trigger_error('Error en INSERT/UPDATE. '.pg_errormessage($this->link).' Query '.$query,E_USER_WARNING);
		}
		return true;
	}

	/**
	 * FUNCIÓN fetch_row(resultado)
	 */
	function fetch_row($resultado){
		return pg_fetch_row($resultado);	
	}
	
	/**
	 * FUNCIÓN fetch_array( resultado )
	 * Obtiene una fila en forma de un array unidimensional
	 */
	function fetch_array($resultado,$fila) {
		return pg_fetch_array($resultado,$fila);
	}
	
	/**
	 * FUNCIÓN fetch_assoc( resultado )
	 * Obtiene una fila en forma de un array unidimensional
	 */
	function fetch_assoc($resultado) {
		return pg_fetch_assoc($resultado);
	}
	
	/**
	 * FUNCIÓN fetch_all( resultado )
	 * Obtiene todas las filas de una consulta en forma de un array bi-dimensional
	 */
	function fetch_all($resultado) {
		return pg_fetch_all($resultado);
	}


	/**
	 * FUNCIÓN free_result( resultado )
	 * Libera la memoria ocupada por un resultado.
	 */
	function free_result($resultado) {
		return pg_free_result($resultado);
	}

	/**
	 * FUNCIÓN num_rows( resultado )
	 * Devuelve el número de filas de un resultado de consulta.
	 */
	function num_rows($resultado) {
		return pg_numrows($resultado);
	}
 
    /**
	 * FUNCIÓN rows_affected( resultado )
	 * Devuelve el número de filas de un resultado de Insert, Update o Delete.
	 */
	function rows_affected($resultado) {
		return pg_cmdtuples($resultado);
	}
	
	/**
	* FUNCION err_pgsql( )
	* Devuelve el error producido
	*/
	function err_pgsql() {
		return pg_errormessage($this->link);
	}
}
?>
