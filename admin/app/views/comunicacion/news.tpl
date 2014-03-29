{include="templates/head"}
	<link rel="stylesheet" type="text/css" href="css/token-input.css" media="all" />

	<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.tokeninput.js"></script>
	<script type="text/javascript">


		$(document).ready(function(e) {

			$('#new-letters').submit(function(e) {
				var $this = $(this);
				e.preventDefault();

				var errors = $this.validationEngine('validate'),
					params = $this.serialize();

				if (!errors) return false;

				$.post($this.attr('action'), params, function(data){
					if (data.status == 'ok') {
						window.location = "{$base_path}/comunicacion/distribuir/alert/success/"+data.info;
						return;
					} else {
						$('#info').echomsg(data.info, 'warn');
					}

				}, 'json');
			}).validationEngine('attach');

		$('textarea#ebody').tinymce({
			script_url : '{$base_path}/js/tiny_mce/tiny_mce_gzip.php',

			// General options
			theme : "advanced",
			plugins : "contextmenu,fullscreen,lists,paste,tabfocus,autolink,style,inlinepopups,noneditable",
			width: '605',
			height: '300',

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,sub,sup,|,link,unlink,image,|,forecolor,backcolor,styleprops,removeformat,|,fullscreen,cleanup,code",
			theme_advanced_buttons2 : "undo,redo,|,cut,copy,paste,pastetext,pasteword,|,outdent,indent,blockquote,|,charmap,hr,|,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons3 : "",
			theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true
		});
			jQuery(function($){
				$.datepicker.regional['es'] = {
					closeText: 'Cerrar',
					prevText: '&#x3c;Ant',
					nextText: 'Sig&#x3e;',
					currentText: 'Hoy',
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
					'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
					monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
					'Jul','Ago','Sep','Oct','Nov','Dic'],
					dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
					dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
					dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
					weekHeader: 'Sm',
					dateFormat: 'dd/mm/yy',
					firstDay: 1,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''};
				$.datepicker.setDefaults($.datepicker.regional['es']);
			});
			var options = {
				dateFormat: 'yy-mm-dd'
			};
			$( "#fecha_envio" ).datepicker(options);


		});

	</script>
<style type="text/css">
	#mapa{float: left; width: 350px; height: 350px;}
	#idp-cfgpanel-left{float: left;width: 500px;}
	#idp-cfgpanel-mitad{float: left;width: 50%;}
	.area{width: 360px !important;}
</style>
</head>
<body>
	<div id="mainHolder">
		<!-- menu -->
		{include="templates/menu"}
		<!-- contenido -->
		<div id="idp-panel-body">
			{include="templates/breadcrumb"}

			<div id="idp-cfgpanel">
				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->
					<h1>Nuevo Boletin</h1>

					<form action="comunicacion/submit-letters" name="new-letters" id="new-letters" method="post">
						<label for="name">Nombre:</label><input type="text" class="validate[required]" name="name" id="name"  value="{$name}"><br>
						<label for="subject">Asunto:</label><input type="text" name="subject" id="subject"  value="{$subject}" class="validate[required]"><br>
						<label for="fecha_envio">Fecha de envio:</label><input type="text" name="fecha_envio" id="fecha_envio"  value="{$fecha_envio}" class="validate[required]"><br>
						<label class="">Descripción: </label>
							<div class="field-holder-wide">
							<textarea class="validate[required]" style="width:595px;" name="ebody" id="ebody" cols="40" rows="15">{$body}</textarea><br>
							</div>
						<div class="center">
							<input type="hidden" name="id" id="id" value="{$id}">
							<input type="submit" id="submit" value="Guardar">
						</div>
						<div id="info"></div>

					</form>


					<!-- aqui termina -->
				</div>
			</div>
		</div>
	</div>
{include="templates/busy"}
</body>
</html>