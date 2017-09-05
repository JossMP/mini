<?php
	class Fail extends Controller
	{
		function __construct()
		{
			parent::__construct();
		}
		function index()
		{
			require URI_THEME.'404.php';
		}
	}
?>

