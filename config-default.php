<?php
require_once 'messages.php';

//site specific configuration declaration
define( 'BASE_PATH', 'http://localhost/openrarp/');
define( 'DB_HOST', 'localhost' );
define( 'DB_USERNAME', 'postgres');
define( 'DB_PASSWORD', '');
define( 'DB_NAME', 'openrarp');

########## Google Settings.. Client ID, Client Secret from https://cloud.google.com/console #############
define('GOOGLE_APP_NAME', '');
define('GOOGLE_OAUTH_CLIENT_ID', '');
define('GOOGLE_OAUTH_CLIENT_SECRET', '');
define('GOOGLE_OAUTH_REDIRECT_URI', '');
define('GOOGLE_SITE_NAME', ''); 

########## Email settings ###################
define('EMAIL_FROM','email1@yoursite.yourdomain');
define('EMAIL_CC','email2@yoursite.yourdomain');

function __autoload($class)
{
	$parts = explode('_', $class);
	$path = implode(DIRECTORY_SEPARATOR,$parts);
	require_once $path . '.php';
}
