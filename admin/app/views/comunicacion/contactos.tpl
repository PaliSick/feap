{include="templates/head"}

	<script type="text/javascript">

		$(document).ready(function(e) {
			$('.editar').click(function(e) {
				e.preventDefault();
				$l = $(this);
				$.get($l.attr('href'), function(data) {
					if (data.id > 0) {
						$('#nuevo').show(500);
						var index = data.id_grupo;
						$('#grupo option').eq(index).prop('selected',true);
						$('#nombre').val(data.nombre);
						$('#email').val(data.email);
						$('#id').val(data.id);
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

			$('#filter_emails').submit(function(e) {
				e.preventDefault();
				var q = $('#filter_query').val() || '*';
				var email=$('#filter_email').val() || '*';
				var grupo=$('#filter_grupo').val() || 0;
				window.location = '{$base_path}/comunicacion/contactos/q/'+q+'/email/'+email+'/grupo/'+grupo;
				return true;
			});

			$('#contacto-nuevo').submit(function(e) {
				var $this = $(this);
				e.preventDefault();

				var errors = $this.validationEngine('validate'),
					params = $this.serialize();

				if (!errors) return false;
				$.post($this.attr('action'), params, function(data){
					
					if (data.status == 'ok') {
						window.location = "{$base_path}/comunicacion/contactos/alert/success/"+data.info;
						
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

						<input type="submit" value="Nuevo Contacto"  onclick="$('#nuevo').slideToggle(500); return false;" style="background-color:#CCC;">
					<br><br>
						<div id="nuevo" style="display: none">
							<form action="comunicacion/contacto-submit" name="contacto-nuevo" id="contacto-nuevo" method="get">
								<table class="idp-table-mid">
									<tr>
										<th>Nombre</th>
										<th>Email</th>
										<th>Grupo</th>
										<th></th>
									</tr>
									<tr>
										<td><input type="text" name="nombre" id="nombre" size="30" value=""></td>
										<td><input type="text" name="email" id="email" size="30" value=""></td>
										<td>
											<select name="grupo" id="grupo" style="width:110px">	
												<option value="0"></option>		
												{loop="grupos"}
												<option value="{$value.id}" {if="$search_params.grupo==$value.id"}selected{/if}>{$value.grupo}</option>
												{/loop}			
											</select></td>
										<td><input type="hidden" id="id" name="id" value="{$id}">			
									<input type="submit" id="submit" value="Guardar"></td>
									</tr>
								</table>
								<div class="center">			

									</div>
							</form>
						</div>
						<br><br><br>
					<h2>Listado de Contactos</h2>	
						<h3>Busqueda</h3>
					<form action="factura/lista" method="get" id="filter_emails">
						<div class="fil">
							<label for="filter_query">Nombre y/o Apellido:</label>
							<input type="text" name="filter_query" id="filter_query" value="{$search_params.q}" style="width:140px">
						</div>
						<div class="fil">
							<label for="filter_query">Email:</label>
							<input type="text" name="filter_email" id="filter_email" value="{$search_params.email}" style="width:140px">
						</div>
						<div class="fil">
							<label for="filter_estado">Grupo:</label>
							<select name="filter_grupo" id="filter_grupo" style="width:110px">	
								<option value="0"></option>		
								{loop="grupos"}
								<option value="{$value.id}" {if="$search_params.grupo==$value.id"}selected{/if}>{$value.grupo}</option>
								{/loop}			
							</select>						
						</div>

						<input type="submit" value="Buscar" style="margin-top: 13px">
					</form>
					<table  class="idp-table">
						<tr>
							<th >Nombre</th>
							<th>Email</th>
							<th>Grupos</th>
							<th style="width: 40px;">Editar</th>
							<th style="width: 40px;">Eliminar</th>
						</tr>
						{loop="contactos"}
						<tr>
							<td>{$value.nombre}</td>
							<td>{$value.email}</td>
							<td>{$value.grupo}</td>
							<td><a {$value.url} >Edit</a></td>
							<td><a href="comunicacion/delete-grupo/{$value.id}" class="delete">Eliminar</a></td>
						</tr>						
						{/loop}
						<tr>
							<td colspan="5">{$paginado}</td>
						</tr>
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