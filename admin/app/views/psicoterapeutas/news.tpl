{include="templates/head"}

	<script type="text/javascript">
		$(document).ready(function(e) {

			$('#new-comercial').submit(function(e) {
				var $this = $(this);
				e.preventDefault();

				var errors = $this.validationEngine('validate'),
					params = $this.serialize();

				if (!errors) return false;

				$.post($this.attr('action'), params, function(data){
					if (data.status == 'ok') {
						window.location = "{$base_path}/comercial/lists/alert/success/"+data.info;
						return;
					} else {
						$('#info').echomsg(data.info, 'warn');
					}

				}, 'json');
			}).validationEngine('attach');

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
			$( "#fecha_alta" ).datepicker(options);
			$( "#fecha_baja" ).datepicker(options);

			$('#addOpciones').click(function(ev) {
				ev.preventDefault();
				var tr = $('#opciones').append('<br><label for="id_opcion"></label><select id="id_opcion" name="id_opcion[]">\
					<option value="0"> Opciones</option>\
					{loop="opciones"}\
						<option value="{$value.id}"> {$value.opcion}</option>\
					{/loop}\
				</select>');
				
			});	

		});

	</script>

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
					<h1>Psicoterapeutas</h1>

					<form action="comercial/submit" name="new-comercial" id="new-comercial" method="post">
						
						<label for="nombre">Nombre:</label><input type="text" name="nombre" id="nombre" class="validate[required]" value="{$nombre}"><br>
						<label for="apellido">Apellidos:</label><input type="text" name="apellido" id="apellido" class="validate[required]" value="{$apellido}"><br>
						<label for="nif">NIF:</label><input type="text" name="nif" id="nif" class="validate[required]" value="{$nif}"><br>
						<label for="telefono">Teléfono:</label><input type="text" name="telefono" id="telefono"  value="{$telefono}"><br>
						<label for="movil">Móvil:</label><input type="text" name="movil" id="movil"  value="{$movil}"><br>
						<label for="direccion">Dirección:</label><input type="text" name="direccion" id="direccion" class="validate[required]" value="{$direccion}"><br>
						<label for="cpostal">C. Postal:</label><input type="text" name="cp" id="cp" class="validate[required]" value="{$cp}"><br>
						<label for="provincia">Provincia:</label>
						<select id="id_provincia" name="id_provincia" class="validate[required]">
						<option value="0">Seleccione un provincia</option>
						{loop="provincias"}
						<option value="{$value.id}" {if="$id_provincia==$value.id"} selected {/if}>{$value.provincia}</option>
						{/loop}							
						</select>
						<br>
						<label for="localidad">Localidad:</label><input type="text" name="localidad" id="localidad"  value="{$localidad}"><br>
						<h2>Datos profesionales</h2>
						<label for="fecha_alta">Fecha acreditación:</label><input type="text" name="fecha_alta" id="fecha_alta"  value="{$fecha_alta}"><br>	
						<label for="fecha_baja">Fecha de baja:</label><input type="text" name="fecha_baja" id="fecha_baja"  value="{$fecha_baja}"><br>						
						<div id="opciones">
						{if="$id>0"}							
							<br><select id="id_opcion0" name="id_opcion[]" >
								<option value="0"> Opciones</option>
								{loop="opciones"}
								<option value="{$value.id}" {if="$prioridad.0==$value.id"} echo selected {/if} > {$value.opcion}</option>
								{/loop}
							</select><a href="#" class="button" id="addOpciones">+</a>
							
							{if="$prioridad.1>0"}
								<br><select id="id_opcion1" name="id_opcion[]" >
									<option value="0"> Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$prioridad.1==$value.id"} echo selected {/if} > {$value.opcion}</option>
									{/loop}
								</select>
							{/if}
							{if="$prioridad.2>0"}
								<br><select id="id_opcion2" name="id_opcion[]" >
									<option value="0"> Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$prioridad.2==$value.id"} echo selected {/if} > {$value.opcion}</option>
									{/loop}
								</select>
							{/if}
							{if="$prioridad.3>0"}
								<br><select id="id_opcion3" name="id_opcion[]" >
									<option value="0"> Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$prioridad.3==$value.id"} echo selected {/if} > {$value.opcion}</option>
									{/loop}
								</select>
							{/if}
							{if="$prioridad.4>0"}
								<br><select id="id_opcion4" name="id_opcion[]" >
									<option value="0"> Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$prioridad.4==$value.id"} echo selected {/if} > {$value.opcion}</option>
									{/loop}
								</select>
							{/if}
						{else}
							<label for="id_opcion">Opciones:</label>
							<select id="id_opcion" name="id_opcion[]" >
							<option value="0">Opciones</option>
							{loop="opciones"}
							<option value="{$value.id}" {if="$id_opcion==$value.id"} selected {/if}>{$value.opcion}</option>
							{/loop}							
							</select><a href="#" class="button" id="addOpciones">+</a>
						{/if}
						</div>
						<br>						
						<label for="email">Email:</label><input type="text" name="email" id="email"  value="{$email}"><br>
						<label for="telefonoC">Teléfono:</label><input type="text" name="telefonoC" id="telefonoC"  value="{$telefonoC}"><br>
						<label for="telefono1C">Teléfono 2:</label><input type="text" name="telefono1C" id="telefono1C"  value="{$telefono1C}"><br>
						<label for="telefono2C">Teléfono 3:</label><input type="text" name="telefono2C" id="telefono2C"  value="{$telefono2C}"><br>
						<label for="direccionC">Dirección:</label><input type="text" name="direccionC" id="direccionC"  value="{$direccionC}"><br>
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