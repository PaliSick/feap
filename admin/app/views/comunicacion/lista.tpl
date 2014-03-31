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
					<h1>Listado de Comunicados</h1>

					<table class="idp-table">
						<tr>
							<th >Nombre</th>
							<th >Asunto</th>
							<th style="width: 120px;">Fecha Envío</th>
							<th style="width: 120px;">Último Envío</th>
							<th style="width: 100px;">Estado</th>
							<th style="width: 100px;">Enviar</th>
							<th colspan="3">Funciones<tr></tr></th>
							
						</tr>
						{loop="letters"}
						<tr class="{if="$key%2 == 0"}even{else}odd{/if}">
							<td>{$value.name}</td>
							<td>{$value.subject}</td>
							<td>{$value.fecha_ini} </td>
							<td>{$value.fecha_fin}</td>
							<td>{$value.estado}</td>
							<td><a href="comunicacion/enviar/{$value.id}" class="email">Enviar</a></td>
							<td><a href="comunicacion/news/{$value.id}" class="details">Ver</a></td>
							<td><a href="comunicacion/deleted/{$value.id}" class="delete">Borrar</a></td>
						</tr>
						{else}
						<tr class="even" >
							<td colspan="8" style="text-align: center; font-weight:bold;">No se produjeron resultados</td>
						</tr>
						{/loop}
						<tr>
							<td colspan="8">{$paginado}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>