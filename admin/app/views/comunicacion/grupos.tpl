{include="templates/head"}

	<script type="text/javascript">

		$(document).ready(function(e) {
			$('.edit').click(function(e) {
				e.preventDefault();
				$l = $(this);
				$.get($l.attr('href'), function(data) {
					if (data.id > 0) {
						$('#nuevo').show(500);
						$('#grupo').val(data.grupo);
						$('#id_grupo').val(data.id);
					} else{ alert(data.info);$('#nuevo').hide(500); }
				}, 'json');

			});
			$('.delete').click(function(e) {
				e.preventDefault();
				$l = $(this);
				if (!confirm('Eliminar un Grupo es una acción que no puede deshaserce.\nEstá seguro que desea continuar?')) return false;

				$.get($l.attr('href'), function(data) {
					if (data.status == 'ok') {
						$l.parents('tr').fadeOut('slow', function(e) {
							$(this).remove();
						});
					} else $('#info').echomsg(data.info, 'warn').slideDown()
				}, 'json');

			});

			$('#grupo-nuevo').submit(function(e) {
				var $this = $(this);
				e.preventDefault();

				var errors = $this.validationEngine('validate'),
					params = $this.serialize();

				if (!errors) return false;
				$.post($this.attr('action'), params, function(data){
					
					if (data.status == 'ok') {
						window.location = "{$base_path}/comunicacion/grupos/alert/success/"+data.info;
						
						return;
					} else {
						$('#info').echomsg(data.info, 'warn').slideDown();
					}

				}, 'json');
			}).validationEngine('attach');

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
				<div id="info">	{if="$msg"}
				{$msg|echomsg:$msgType}
				{/if}</div><br>
				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->

						<input type="submit" value="Nuevo Grupo"  onclick="$('#nuevo').slideToggle(500); return false;" style="background-color:#CCC;">
					<br><br>
						<div id="nuevo" style="display: none">
							<form action="comunicacion/grupo-submit" name="grupo-nuevo" id="grupo-nuevo" method="get">
								<table class="idp-table-mid">
									<tr>
										<th>Grupo</th>
									</tr>
									<tr>
										<td><input type="text" name="grupo" id="grupo" size="30" value=""></td>
										<td><input type="hidden" id="id_grupo" name="id_grupo" value="{$id_grupo}">			
									<input type="submit" id="submit" value="Guardar"></td>
									</tr>
								</table>
								<div class="center">			

									</div>
							</form>
						</div>
						<br><br><br>
					<h2>Listado de Grupos</h2>	
					<table  class="idp-table-min">
						<tr>
							<th style="width: 600px;">Grupo</th>
							<th style="width: 80px;">Editar</th>
							<th style="width: 80px;">Eliminar</th>
						</tr>
						{loop="grupos"}
						<tr>
							<td>{$value.grupo}</td>
							<td><a href="comunicacion/edit-grupo/{$value.id}" class="edit">Edit</a></td>
							<td><a href="comunicacion/delete-grupo/{$value.id}" class="delete">Eliminar</a></td>
						</tr>
						<input type="hidden" name="id[]" value="{$value.id}">
						{/loop}
					</table>

					<br>
					


					<!-- aqui termina -->
				</div>
			</div>
		</div>
	</div>
{include="templates/busy"}
</body>
</html>