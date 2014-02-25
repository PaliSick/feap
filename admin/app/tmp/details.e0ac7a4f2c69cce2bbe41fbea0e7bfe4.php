<?php if(!class_exists('raintpl')){exit;}?>	<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/head") . ( substr("templates/head",-1,1) != "/" ? "/" : "" ) . basename("templates/head") );?>

	<style type="text/css">
		.totals {float:left;color:#2e2e2e;width:240px;}
		.totals div{margin-bottom:20px;}
		.totals div span{margin-right:10px;}

		#subtotal {font-weight:bold;}
		#descuentos{}
		#impuestos {}
		#total {font-size:18px; font-weight:bold;}
		#total span{color:#ba2d2d;}
	</style>
</head>
<body>
	<div id="mainHolder">
		<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/menu") . ( substr("templates/menu",-1,1) != "/" ? "/" : "" ) . basename("templates/menu") );?>


		<div id="idp-panel-body">
			<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/breadcrumb") . ( substr("templates/breadcrumb",-1,1) != "/" ? "/" : "" ) . basename("templates/breadcrumb") );?>

			<div id="idp-cfgpanel">
				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->
					<h1>Detalles de factura nº: <?php echo $ped["fac"]["id"];?></h1>
					<div class="clearfix">
						<div class="idp-panel-col">
							<p><strong>Fecha:</strong> <?php echo $ped["fac"]["fecha"];?></p>
							<p><strong>Factura:</strong> <?php echo $ped["fac"]["id"];?></p>
						</div>
					</div>
					<div class="clearfix">

						<div class="idp-panel-col">
							<h2>Datos de facturación</h2>
							<p><span>Nombre Y Apellido:</span> <?php echo $ped["cliente"]["nombre"];?></p>
							<p><span>NIF / CIF:</span> <?php echo $ped["cliente"]["nif_cif"];?></p>
							<p><span>Dirección:</span> <?php echo $ped["cliente"]["direccion"];?></p>
							<p><span>C.P.:</span> <?php echo $ped["cliente"]["cp"];?></p>
							<p><span>Localidad:</span> <?php echo $ped["cliente"]["localidad"];?></p>
							<p><span>Provincia:</span> <?php echo $ped["cliente"]["provincia"];?></p>
							<p><span>Teléfono:</span> <?php echo $ped["cliente"]["telefono"];?></p>
						</div>
						<div class="idp-panel-col">
							<h2>Datos de Envío</h2>
							<p><span>Nombre Y Apellido:</span> <?php echo $ped["envio"]["nombre"];?></p>
							<p><span>NIF / CIF:</span> <?php echo $ped["envio"]["nif_cif"];?></p>
							<p><span>Dirección:</span> <?php echo $ped["envio"]["direccion"];?></p>
							<p><span>C.P.:</span> <?php echo $ped["envio"]["cp"];?></p>
							<p><span>Localidad:</span> <?php echo $ped["envio"]["localidad"];?></p>
							<p><span>Provincia:</span> <?php echo $ped["envio"]["provincia"];?></p>
							<p><span>Teléfono:</span> <?php echo $ped["envio"]["telefono"];?></p>
						</div>
					</div>
					

					
					<h2>Productos</h2>
					<table class="idp-table">
						<tr>
							<th>Producto</th>
							<th>C. EAN</th>
							<th>Part Number</th>
							<th>C. Idirecto</th>
							<th>Cantidad</th>
							<th>Base Imponible</th>
							<th>IVA</th>
							<th>Subtotal</th>
						</tr>
						<?php $counter1=-1; if( isset($ped["productos"]) && is_array($ped["productos"]) && sizeof($ped["productos"]) ) foreach( $ped["productos"] as $key1 => $value1 ){ $counter1++; ?>

						<tr>
							<td style="vertical-align:top">
							<?php if( $value1["id_tienda"]>0 ){ ?>

							<img src="http://www.idirecto.es/decoexsa/../img_products/comodin.jpg" alt="<?php echo $value1["nombre"];?>" title="<?php echo $value1["nombre"];?>"> <?php echo $value1["nombre"];?>

							<?php }else{ ?>

							<img src="http://www.idirecto.es/decoexsa/../img_products/<?php echo $value1["img_name"];?>" alt="<?php echo $value1["nombre"];?>" title="<?php echo $value1["nombre"];?>"> <?php echo $value1["nombre"];?>

							<?php } ?>

							</td>
							<td><?php echo $value1["ean"];?></td>
							<td><?php echo $value1["part_number"];?></td>
							<td><?php echo $value1["idirecto"];?></td>
							<td><?php echo $value1["uds"];?></td>
							<td><?php echo ( number_format( $value1["precio"], 2,',','.' ) );?>€</td>
							<td><?php echo ( number_format( $value1["impuestos"], 2,',','.' ) );?>€</td>
							<td><?php echo ( number_format( $value1["subtotal"], 2,',','.' ) );?>€</td>
						</tr>
						<?php } ?>

						
					</table>
					
					<div class="totals">
						<div id="subtotal"><span>Subtotal:</span><?php echo ( number_format( $ped["fac"]["subtotal"], 2,',','.' ) );?> €</div>
						<div id="impuestos"><span>Impuestos(*):</span> <?php echo ( number_format( $ped["fac"]["impuestos"], 2,',','.' ) );?> €</div>
						<div id="impuestos"><span>Recargo Equivalencia:</span> <?php echo ( number_format( $ped["fac"]["equivalencia"], 2,',','.' ) );?> €</div>
						<div id="impuestos"><span>Gastos de Envio:</span> <?php echo ( number_format( $ped["fac"]["envio"], 2,',','.' ) );?> €</div>
						<div id="total"><span>Total:</span><?php echo ( number_format( $ped["fac"]["total"], 2,',','.' ) );?> €</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>