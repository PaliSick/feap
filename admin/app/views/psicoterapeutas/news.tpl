{include="templates/head"}
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript">

		function initialize(){
			//MAP
			
			{if="$latitud>0"}
			  var latlng = new google.maps.LatLng({$latitud},{$longitud});
			{else}
			  var latlng = new google.maps.LatLng(40.41573004568194, -3.68425733786625);
			{/if}
			  var options = {
			    zoom: 10,
			    center: latlng,
			    mapTypeId: google.maps.MapTypeId.ROADMAP
			  };
			        
			  map = new google.maps.Map(document.getElementById("mapa"), options);
			        
			  //GEOCODER
			  geocoder = new google.maps.Geocoder();
			        
			  marker = new google.maps.Marker({
			  position: latlng,  
			    map: map,
			    scrollwheel:false,
			    draggable: true
			  });
		        
		}
		$(document).ready(function(e) {
			initialize();
			  //Add listener to marker for reverse geocoding
			google.maps.event.addListener(marker, 'drag', function() {
				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) {
				    if (results[0]) {
				      $('#latitud').val(marker.getPosition().lat());
				      $('#longitud').val(marker.getPosition().lng());
				    }
				  }
				});
			});

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
						<div id="idp-cfgpanel-left">
							<label for="fecha_alta">Fecha acreditación:</label><input type="text" name="fecha_alta" id="fecha_alta"  value="{$fecha_alta}"><br>	
							<label for="fecha_baja">Fecha de baja:</label><input type="text" name="fecha_baja" id="fecha_baja"  value="{$fecha_baja}"><br>						
							<div id="opciones">
							{loop="cantOpciones"}
								<br><select id="id_opcion4" name="id_opcion[]" >
									<option value="0"> Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$miembro.4==$value.id"} echo selected {/if} > {$value.opcion}</option>
									{/loop}
								</select>{if="$key==0"}<a href="#" class="button" id="addOpciones">+</a>{/if}								
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
							<label for="cpC">C.P.:</label><input type="text" name="cpC" id="cpC"  value="{$cpC}"><br>
							<label for="id_provinciaC">Provincia:</label>
							<select id="id_provinciaC" name="id_provinciaC">
							<option value="0">Seleccione un provincia</option>
							{loop="provincias"}
							<option value="{$value.id}" {if="$id_provinciaC==$value.id"} selected {/if}>{$value.provincia}</option>
							{/loop}							
							</select>
							<br>
							<label for="localidadC">Localidad:</label><input type="text" name="localidadC" id="localidadC"  value="{$localidadC}"><br>	
						</div>
						<div id="mapa"></div>
						<input type="hidden" name="latitud" id="latitud" value="{$latitud}">	
						<input type="hidden" name="longitud" id="longitud" value="{$longitud}">	
						<br>		
						<h2>Diplomas</h2>
						<div id="idp-cfgpanel-mitad">
							<h2>Titulación</h2>
							<textarea id="titulo" class="area" rows="8" name="titulo">{$titulo}</textarea>
							<h2>Idiomas</h2>
							<textarea id="idioma" class="area" rows="8" name="idioma">{$idioma}</textarea>
						</div>
						<div id="idp-cfgpanel-mitad">
							<h2>Especialidades</h2>
							<textarea id="especialidades" class="area" rows="8" name="especialidades">{$especialidades}</textarea>
							<h2>Observaciones</h2>
							<textarea id="observaciones" class="area" rows="8" name="observaciones">{$observaciones}</textarea>
						</div>
						<h2>Es miembro de: </h2>
							<div id="miembro">
							
								{loop="cantMiembros"}
									{$aux=$key}
									<label for="id_opcion">Opciones:</label>
									<select id="id_opcion" name="id_opcion[]" >
									<option value="0">Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$cantMiembros[$aux][id_opcion]==$value.id"} selected {/if}>{$value.opcion} | {$cantMiembros[$aux][id_opcion]}</option>
									{/loop}
									</select>{if="$key==0"}<a href="#" class="button" id="addMiembros">+</a>{/if}
									
									{$cantMiembros[$aux][id_opcion]} o {$value.id_opcion} y {$as}
								{else}
									<label for="id_opcion">Opciones:</label>
									<select id="id_opcion" name="id_opcion[]" >
									<option value="0">Opciones</option>
									{loop="opciones"}
									<option value="{$value.id}" {if="$id_opcion==$value.id"} selected {/if}>{$value.opcion}</option>
									{/loop}
									</select><a href="#" class="button" id="addMiembros">+</a>
								{/loop}						
								
							
							</div>
							<div id="grupos">
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