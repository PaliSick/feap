	{include="templates/head"}
	<script type="text/javascript">
		$(document).ready(function(e) {
			$('#filter_psico').submit(function(e) {
				e.preventDefault();
				var q = $('#filter_query').val() || '*'

				window.location = '{$base_path}/factura/lista/q/'+q+'/from/'+from+'/to/'+to;
				return true;
			});
			$('.confirm').click(function(e) {
				e.preventDefault();
				$l = $(this);

				$.get($l.attr('href'), function(data) {
					if (data.status == 'ok') {
						$l.parents('tr').fadeOut('slow', function(e) {
							$(this).remove();
							$('#info').echomsg(data.info, 'success');
						});
					} else alert(data.info);
				}, 'json');

			});

		});
	</script>
</head>
<body>
	<div id="mainHolder">
		{include="templates/menu"}

		<div id="idp-panel-body">
			{include="templates/breadcrumb"}
			<div id="idp-cfgpanel">
				{if="$msg"}
				{$msg|echomsg:$msgType}
				{/if}
				<div id="info"></div>
				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->
					<h1>Listado de Pedidos</h1>
					<form action="factura/lista" method="get" id="filter_psico">
						<div class="fil">
							<label for="filter_query">Nombre y/o Apellido:</label>
							<input type="text" name="filter_query" id="filter_query" value="{$search_params.q}" style="width:140px">
						</div>
						<div class="fil">
							<label for="filter_estado">Estado:</label>
							<select name="filter_estado" id="filter_estado" style="width:110px">							
								<option value="1" {if="$estado==1"}selected{/if}>Activo</option>
								<option value="0" {if="$estado==0"}selected{/if}>Inactivo</option>			
							</select>						
						</div>
						<div class="fil">
							<label for="filter_estado">Miembro:</label>
							<select name="filter_miembro" id="filter_miembro" style="width:140px">
								<option value="0" {if="$value.id==0"}selected{/if}></option>
								{loop="$miembros"}
								<option value="{$value.id}" {if="$value.id==$miembro"}selected{/if}>{$value.nombre}</option>
								{/loop}			
							</select>						
						</div>		
						<div class="fil">
							<label for="filter_estado">Sección:</label>
							<select name="filter_miembro" id="filter_miembro" style="width:140px">
								<option value="0" {if="$value.id==0"}selected{/if}></option>
								{loop="$secciones"}
								<option value="{$value.id}" {if="$value.id==$seccion"}selected{/if}>{$value.seccion}</option>	
								{/loop}			
							</select>						
						</div>
						<div class="fil">
							<label for="filter_estado">Provincia:</label>
							<select name="filter_miembro" id="filter_miembro" style="width:140px">
								{loop="$provincias"}
								<option value="{$value.id}" {if="$value.id==$provincia"}selected{/if}>{$value.provincia}</option>
								{/loop}		
							</select>						
						</div>
						<input type="submit" value="Buscar" style="margin-top: 13px">
					</form>
					<table class="idp-table">
						<tr>
							<th style="width: 80px;">Nº</th>
							<th style="width: 120px;">Psicoterapeutas</th>
							<th >Opciones</th>
							<th style="width: 100px;">Provincia</th>
							<th style="width: 100px;">Localidad</th>
							<th>Funciones</th>
							
						</tr>
						{loop="psicoterapeutas"}
						<tr class="{if="$key%2 == 0"}even{else}odd{/if}">
							<td>{$value.id}</td>
							<td>{$value.apellido}, {$value.nombre}</td>
							<td>{$value.opciones} </td>
							<td>{$value.provincia}</td>
							<td>{$value.localidad}</td>
							<td><a href="psicoterapeutas/nuevo/{$value.id}" class="details">Ver</a></td>
						</tr>
						{else}
						<tr class="even" >
							<td colspan="6" style="text-align: center; font-weight:bold;">No se produjeron resultados</td>
						</tr>
						{/loop}
						<tr>
							<td colspan="6">{$paginado}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>