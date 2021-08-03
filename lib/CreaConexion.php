<?
/*
 * EPVNET: Software de Gestión para Centros Penitenciarios
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

/*
 * Habrá que cambiar estas dos líneas según la BD a la que se quiera
 * acceder y la clase que se quiera utilizar para ello.
 */
require ("lib/ConexionPostgreSQL.php");
$conexion = new ConexionPostgreSQL();

?>
