/* ++++++++++++++++++++++++++++++++++++++
+	Create 	: 	Josue Mazco Puma		+
+	e-mail 	: 	JossMP@gmail.com		+
+	twitter : 	@JossMP777				+
++++++++++++++++++++++++++++++++++++++ */

(function($){
$.fn.serializefiles = function() {
	var obj = $(this);
	/* ADD FILE TO PARAM AJAX */
	var formData = new FormData();
	$.each($(obj).find("input[type='file']"), function(i, tag) {
		$.each($(tag)[0].files, function(i, file) {
			formData.append(tag.name, file);
		});
	});
	
	var params = $(obj).serializeArray();
	$.each(params, function (i, val){
		formData.append(val.name, val.value);
	});
	return formData;
};
})(jQuery);

//////////////////////////////////////////////////////////////

(function($){
	$.ajaxblock 	= function(){
		$("body").prepend("<div id='ajax-overlay'><div id='ajax-overlay-body' class='center'><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i><span class='sr-only'>Loading...</span></div></div>");
		$("#ajax-overlay").css({
			position: 'absolute',
			color: '#FFFFFF',
			top: '0',
			left: '0',
			width: '100%',
			height: '100%',
			position: 'fixed',
			background: 'rgba(39, 38, 46, 0.67)',
			'text-align': 'center',
			'z-index': '9999'
		});
		$("#ajax-overlay-body").css({
			position: 'absolute',
			top: '40%',
			left: '50%',
			width: '120px',
			height: '48px',
			'margin-top': '-12px',
			'margin-left': '-60px',
			//background: 'rgba(39, 38, 46, 0.1)',
			'-webkit-border-radius':	'10px',
			'-moz-border-radius':	 	'10px',
			'border-radius': 		 	'10px'
		});
		$("#ajax-overlay").fadeIn(50);
	};
	$.ajaxunblock 	= function(){
		$("#ajax-overlay").fadeOut(100, function()
		{
			$("#ajax-overlay").remove();
		});
	};
	$.fn.extend({
		ajaxview: function(options){
			var defaults={
				ajaxserialize		: "serialize",		// ID Formulario
				ajaxdata			: "data",			// Datos extras a Enviar
				ajaxdestine			: "destine",		// URL
				ajaxconfirm			: "confirm",		// Mensaje de Confirmacion
				GetData				: function(){},		// Funccion
				complete			: function(){},		// Funccion
				success				: function(){}		// Funccion
			};
			var opts=$.extend(defaults,options);
			this.each(function(){
				var $this=$(this);
				var $serialize		= $this.data(opts.ajaxserialize);		// ID de Form para Serializar sin incluir #
				var $destine		= $this.data(opts.ajaxdestine);			// Requerido: url, si no existe usa el 'href'
				var $data			= $this.data(opts.ajaxdata);			// Datos extra en json (Ejem: "	{'a':'b','b':'c'}	")
				var $confirm		= $this.data(opts.ajaxconfirm);			// Mensaje de confirmacion antes de ejecutar el evento

				var $contentType = 'application/x-www-form-urlencoded';
				var $formData = new FormData();
				if(typeof($confirm)!='undefined')
				{
					if(!confirm($confirm))
					{
						return false;
					}
				}
				if(typeof($serialize)!='undefined')
				{
					$formData = $("#"+$serialize).serializefiles();
					$contentType='multipart/form-data';
				}
				if(typeof($data)!='undefined')
				{
					$json=$data;
					//console.log($data);
					$.each($json, function (i, val)
					{
						$formData.append(i, val);
						//console.log(i+"=>"+val);
					});
				}
				opts.GetData($formData);
				//console.log($formData);
				if(typeof($destine)=='undefined')
				{
					$destine=$this.attr("href");
				}
				$.ajax({
					url: $destine,
					data: $formData,
					cache: false,
					contentType: false,
					processData: false,
					type: "POST",
					//contentType: $contentType,
					//mimeType: $contentType,
					async: true,
					dataType: "json",
					beforeSend: function()
					{
						//opts.ajaxfuncini();
						$.ajaxblock();
					},
					complete: function(x,s)
					{
						// Complete
						$.ajaxunblock();
						opts.complete();
					},
					error: function()
					{
						// si Error
						/*$.gritter.add({
							title: 'ERROR!',
							text: 'Parece que el servidor no responde correctamente, <strong>Presione F5</strong>',
							image: 'images/error.png',
							sticky: true,//Statico
							time: '6'
						});*/
						alert("ERROR: Parece que el servidor no responde...");
					},
					success: function(respuesta)
					{
						if(respuesta['success']!="false" && respuesta['success']!=false)
						{
							if(typeof($hide)!='undefined')
							{
								$($hide).addClass('hide');
							}
							if(respuesta['update'] != undefined && respuesta['update'] != '')
							{
								for(var i=0; i<respuesta['update'].length; i++)
								{
									if(respuesta['update'][i]['action']=="prepend")
									{
										$("#"+respuesta['update'][i]['id']).prepend(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="append")
									{
										$("#"+respuesta['update'][i]['id']).append(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="replaceWith")
									{
										$("#"+respuesta['update'][i]['id']).replaceWith(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="html")
									{
										$("#"+respuesta['update'][i]['id']).html(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="val")
									{
										$("#"+respuesta['update'][i]['id']).val(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="hide")
									{
										$("#"+respuesta['update'][i]['id']).hide();
									}
									else if(respuesta['update'][i]['action']=="show")
									{
										$("#"+respuesta['update'][i]['id']).show();
									}
									else if(respuesta['update'][i]['action']=="remove")
									{
										$("#"+respuesta['update'][i]['id']).remove();
									}
									else if(respuesta['update'][i]['action']=="addClass")
									{
										$("#"+respuesta['update'][i]['id']).addClass(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="removeClass")
									{
										$("#"+respuesta['update'][i]['id']).removeClass(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="toggleClass")
									{
										$("#"+respuesta['update'][i]['id']).toggleClass(respuesta['update'][i]['value']);
									}
								}
							}

							if((respuesta['remove']!='' || typeof(respuesta['remove'])!='undefined'))
							{
								$("#"+respuesta['remove']).remove();
							}

							if(typeof(respuesta['notification'])!='undefined')
							{
								/*$.gritter.add({
									title: 'Notificacion!',
									text: respuesta['notification'],
									image: 'images/success.png',
									sticky: false,
									time: ''
								});*/
								alert(respuesta['notification']);
							}

							if(typeof(respuesta['redirection'])!='undefined')
							{
								top.location.href = respuesta['redirection'];
							}
							// Funcction
							opts.success();
						}
						else
						{
							if(typeof(respuesta['notification'])!='undefined')
							{
								/*$.gritter.add({
									title: 'Advertencia!',
									text: respuesta['notification'],
									image: 'images/warning.png',
									sticky: false,
									time: ''
								});*/
								alert(respuesta['notification']);
							}
							if(typeof(respuesta['redirection'])!='undefined')
							{
								top.location.href = respuesta['redirection'];
							}
						}
					}
				});
			});
		}
	});
})(jQuery);
