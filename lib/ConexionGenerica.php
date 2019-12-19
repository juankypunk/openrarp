<?
/*
 * Copyright (c) 2004 Juan Carlos Moral
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

/**
 * CLASE ConexionGenerica
 *
 * Esta clase es una plantilla para definir la estructura básica (variables
 * y métodos) de todas las clases para conectar a distintas BBDD que más
 * tarde heredarán de esta, redefiniendo los métodos.
 */

class ConexionGenerica {

	 /* El enlace con la BD */
	 var $link;
	 function ConexionGenerica() {}


	 /*
	 * FUNCIÓN connect( servidor, usuario, clave )
     * Conecta con el servidor, identificándose como usuario y clave.
	 * No hace nada, ya que será redefinida por las clases que hereden de esta.
	 */
	 function connect($servidor, $usuario, $clave) {
		echo "<h1>El método <i>connect</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	 }


	/*
	 * FUNCIÓN close()
	 * Finaliza la conexion con el servidor.
	 * No hace nada, debe ser redefinida por las clases que hereden de esta.
	 */
	 function close() {
		echo "<h1>El método <i>close</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}

	/**
	 * FUNCIÓN select_db( base_datos )
	 * Elige la base de datos con la que trabajar.
	 * No hace nada, puede ser redefinida por las clases que hereden de esta.
	 */
	function select_db($base_datos) {
		echo "<h1>El método <i>select_db</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}

	/**
	 * FUNCIÓN query( query )
	 * Realiza una consulta o actualización en la BD.
	 * No hace nada, debe ser redefinida por las clases que hereden de esta.
	 */
	function query($query) {
		echo "<h1>El método <i>query</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}

	/**
	 * FUNCIÓN fetch_array( resultado )
	 * Realiza una consulta o actualización en la BD.
	 * No hace nada, debe ser redefinida por las clases que hereden de esta.
	 */
	function fetch_array($resultado,$fila) {
		echo "<h1>El método <i>query</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}

	/**
	 * FUNCIÓN free_result( resultado )
	 * Libera la memoria ocupada por un resultado.
	 * No hace nada, debe ser redefinida por las clases que hereden de esta.
	 */
	function free_result($resultado) {
		echo "<h1>El método <i>free_result</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}

	/**
	 * FUNCIÓN num_rows( resultado )
	 * Devuelve el número de filas de un resultado.
	 * No hace nada, debe ser redefinida por las clases que hereden de esta.
	 */
	function num_rows($resultado) {
		echo "<h1>El método <i>num_rows</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}

    /**
	 * FUNCIÓN rows_affected( resultado )
	 * Devuelve el número de filas afectadas por un Insert, Update o Delete.
	 * No hace nada, debe ser redefinida por las clases que hereden de esta.
	 */
	function rows_affected($resultado) {
		echo "<h1>El método <i>rows_affected</i> no está " .
		     "implementado en la clase <i>" . get_class($this) . "</i></h1>";
		return FALSE;
	}
}
?>
