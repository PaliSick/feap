	{include="templates/head"}
	<script type="text/javascript">
		$(document).ready(function(e) {
			$('#filter_psico').submit(function(e) {
				e.preventDefault();
				var q = $('#filter_query').val() || '*';
				var estado=$('#filter_estado').val() || 2;
				var miembro=$('#filter_miembro').val() || 0;
				var seccion=$('#filter_seccion').val() || 0;
				var prov=$('#filter_prov').val() || 0;
				window.location = '{$base_path}/psicoterapeutas/listado/q/'+q+'/estado/'+estado+'/miembro/'+miembro+'/seccion/'+seccion+'/prov/'+prov;
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
								<option value="0" {if="$estado==0"}selected{/if}></option>		
								<option value="1" {if="$estado==1"}selected{/if}>Activo</option>
								<option value="2" {if="$estado==2"}selected{/if}>Inactivo</option>			
							</select>						
						</div>
						<div class="fil">
							<label for="filter_miembro">Miembro:</label>
							<select name="filter_miembro" id="filter_miembro" style="width:140px">
								<option value="0" {if="$value.id==0"}selected{/if}></option>
								{loop="miembros"}
								<option value="{$value.id}" {if="$value.id==$miembro"}selected{/if}>{$value.nombre}</option>
								{/loop}			
							</select>						
						</div>		
						<div class="fil">
							<label for="filter_seccion">Sección:</label>
							<select name="filter_seccion" id="filter_seccion" style="width:140px">
								<option value="0" {if="$value.id==0"}selected{/if}></option>
								{loop="secciones"}
								<option value="{$value.id}" {if="$value.id==$seccion"}selected{/if}>{$value.seccion}</option>	
								{/loop}			
							</select>						
						</div>
						<div class="fil">
							<label for="filter_prov">Provincia:</label>
							<select name="filter_prov" id="filter_prov" style="width:140px">
								<option value="0" {if="$value.id==0"}selected{/if}></option>
								{loop="provincias"}
								<option value="{$value.id}" {if="$value.id==$provincia"}selected{/if}>{$value.provincia}</option>
								{/loop}		
							</select>						
						</div>
						<input type="submit" value="Buscar" style="margin-top: 13px">
					</form>
					<h2>Exportar a excel</h2>
					<a href="{$path}/excel/1">Exportar a excel con estos mismos parámetros</a>
					<table class="idp-table">
						<tr>
							<th style="width: 80px;">Nº</th>
							<th style="width: 120px;">Psicoterapeutas</th>
							<th >Opciones</th>
							<th style="width: 100px;">Provincia</th>
							<th style="width: 100px;">Localidad</th>
							<th colspan="3">Funciones<tr></tr></th>
							
						</tr>
						{loop="psicoterapeutas"}
						<tr class="{if="$key%2 == 0"}even{else}odd{/if}">
							<td>{$value.id}</td>
							<td>{$value.apellido}, {$value.nombre}</td>
							<td>{$value.opciones} </td>
							<td>{$value.provincia}</td>
							<td>{$value.localidad}</td>
							<td><a href="psicoterapeutas/descarga/{$value.id}" class="descarga">Ver</a></td>
							<td><a href="psicoterapeutas/nuevo/{$value.id}" class="details">Ver</a></td>
							<td><a href="psicoterapeutas/deleted/{$value.id}" class="delete">Borrar</a></td>
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