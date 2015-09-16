<?php
class Cl_DBclass
{
	/**
	 * @var $con will hold database connection
	 */
	public $con;
	
	/**
	 * This will create Database connection
	 */
	public function __construct()
	{
		$this->con = pg_connect('host='.DB_HOST.' dbname='.DB_NAME.' user='.DB_USERNAME) or die('Fallo en la conexión');
	}
}
