<?php if(!class_exists('raintpl')){exit;}?>	<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/head") . ( substr("templates/head",-1,1) != "/" ? "/" : "" ) . basename("templates/head") );?>

	<script type="text/javascript">
		$(document).ready(function(e) {
			$('#filter_products').submit(function(e) {
				e.preventDefault();
				var q = $('#filter_query').val() || '*'
				from = $('#filter_from').val() || 0;
				to = $('#filter_to').val() || 0;

				window.location = '<?php echo $base_path;?>/factura/lista/q/'+q+'/from/'+from+'/to/'+to;
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
			$( "#filter_from" ).datepicker(options);
			$( "#filter_to" ).datepicker(options);

		});
	</script>
</head>
<body>
	<div id="mainHolder">
		<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/menu") . ( substr("templates/menu",-1,1) != "/" ? "/" : "" ) . basename("templates/menu") );?>


		<div id="idp-panel-body">
			<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/breadcrumb") . ( substr("templates/breadcrumb",-1,1) != "/" ? "/" : "" ) . basename("templates/breadcrumb") );?>

			<div id="idp-cfgpanel">
				<?php if( $msg ){ ?>

				<?php echo ( echomsg( $msg, $msgType ) );?>

				<?php } ?>

				<div id="info"></div>
				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->
					<h1>Listado de Pedidos</h1>
					<form action="http://www.idirecto.es/decoexsa/factura/lista" method="get" id="filter_products">
						<label for="filter_query">Busqueda:</label>
						<input type="text" name="filter_query" id="filter_query" value="<?php echo $search_params["q"];?>">
						<div class="fil">
						<label for="filter_from">Desde:</label>
						<input type="text" name="filter_from" id="filter_from" value="<?php echo $from;?>">
						</div>	
						<div class="fil">
							<label for="filter_to">Hasta:</label>
							<input type="text" name="filter_to" id="filter_to" value="<?php echo $to;?>">
						</div>
						<input type="submit" value="Buscar" style="margin-top: 13px">
					</form>
					<table class="idp-table">
						<tr>
							<th style="width: 80px;">Nº de Pedido</th>
							<th style="width: 120px;">Fecha</th>
							<th style="width: 320px;">Usuario</th>
							<th style="width: 100px;">Ganancia</th>
							<th style="width: 100px;">Total</th>
							<?php if( $aprobar ){ ?>

							<th>Aprobar</th>
							<?php } ?>	
							<th>Ver</th>
							
						</tr>
						<?php $counter1=-1; if( isset($orders) && is_array($orders) && sizeof($orders) ) foreach( $orders as $key1 => $value1 ){ $counter1++; ?>

						<tr class="<?php if( $key1%2 == 0 ){ ?>even<?php }else{ ?>odd<?php } ?>">
							<td><?php echo $value1["id"];?></td>
							<td><?php echo $value1["fecha"];?></td>
							<td><?php echo $value1["nombre_sociedad"];?></td>
							<td><?php echo $value1["ganancia"];?> €</td>
							<td><?php echo $value1["total"];?> €</td>
							<?php if( $aprobar ){ ?>

							<td><a href="http://www.idirecto.es/decoexsa/pedidos/confirm/<?php echo $value1["id"];?>" class="confirm">Confirmar</a></td>
							<?php } ?>								
							<td><a href="http://www.idirecto.es/decoexsa/pedidos/details/<?php echo $value1["id"];?>" class="details">Ver</a></td>
						</tr>
						<?php }else{ ?>

						<tr class="even" >
							<td colspan="11" style="text-align: center; font-weight:bold;">No se produjeron resultados</td>
						</tr>
						<?php } ?>

						<tr>
							<td colspan="7"><?php echo $paginado;?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>