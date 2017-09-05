<?php
	class fnLicencia
	{
		function VerLicencia()
		{
			$rtn = '<div class="row">
						<div class="col-md-12">
							<div class="cPanel text-center">
								<img src="'.URL.'images/logo.png">
								<h2>
									Panel de Administracion de Envios
								</h2>

								<div class="col-sm-offset-3 col-sm-6">
									<blockquote>
									<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Licencia Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">SAE</span> está distribuido bajo una <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Licencia Creative Commons Atribución-NoComercial-SinDerivar 4.0 Internacional</a>.
									</blockquote>
								</div>
								<div class="clearfix space"></div>
								<a href="'.URL.'" class="btn btn-success"> Volver al Inicio </a>
								<div class="clearfix space"></div>
							</div>
						</div>
					</div>';
			return $rtn;
		}
	}
?>
