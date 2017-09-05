<?php
	date_default_timezone_set('America/Lima');

	define('ENVIRONMENT', 'development');

	if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
	define('THEME_NAME', 'admin');

	define('URI', __DIR__ . DIRECTORY_SEPARATOR );
	define('URI_MOD', URI . 'modulo' . DIRECTORY_SEPARATOR);
	define('URI_THEME', URI . 'template' . DIRECTORY_SEPARATOR . THEME_NAME . DIRECTORY_SEPARATOR);
	define('URL_PROTOCOL', 'http://'); // Protocolo
	define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
	define('URL_SUB_FOLDER', str_replace(URI_MOD, '', dirname($_SERVER['SCRIPT_NAME'])) . DIRECTORY_SEPARATOR);
	define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
	define('URL_THEME', URL . 'template' . DIRECTORY_SEPARATOR . THEME_NAME . DIRECTORY_SEPARATOR);

	define('TITLE', 'Inicio | SAE');

	/*
		Configuracion de Base de Datos
	*/
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'mini');
	define('DB_USER', 'root');
	define('DB_PASS', '1234');
	define('DB_CHARSET', 'utf8');
	define('DB_TIME_ZONE', '-05:00');

	// twilio
	define('SMS_NRO', '5117083769');
	define('SMS_SID', 'AC78dbb144384b8cdbf2e824ecd15b90cf');
	define('SMS_TOKEN', '8575abf91fc69d055caf7b68d7a6b849');

	// API FACEBOOK

	define('APP_ID', '1038519456243014');
	define('APP_SECRET', '5f4b48e62270683560ff65d6d50e953b');

	// API MAIL
	define('MAIL_HOST', 'localhost');
	define('MAIL_USER', 'admin');
	define('MAIL_PASW', 'admin');
	define('MAIL_PORT', '25');
