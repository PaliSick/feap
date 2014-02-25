	{include="templates/head"}
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
		{include="templates/menu"}

		<div id="idp-panel-body">
			{include="templates/breadcrumb"}
			<div id="idp-cfgpanel">
				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->
					<h1>Detalles de factura nº: {$ped.fac.id}</h1>
					<div class="clearfix">
						<div class="idp-panel-col">
							<p><strong>Fecha:</strong> {$ped.fac.fecha}</p>
							<p><strong>Factura:</strong> {$ped.fac.id}</p>
						</div>
					</div>
					<div class="clearfix">

						<div class="idp-panel-col">
							<h2>Datos de facturación</h2>
							<p><span>Nombre Y Apellido:</span> {$ped.cliente.nombre}</p>
							<p><span>NIF / CIF:</span> {$ped.cliente.nif_cif}</p>
							<p><span>Dirección:</span> {$ped.cliente.direccion}</p>
							<p><span>C.P.:</span> {$ped.cliente.cp}</p>
							<p><span>Localidad:</span> {$ped.cliente.localidad}</p>
							<p><span>Provincia:</span> {$ped.cliente.provincia}</p>
							<p><span>Teléfono:</span> {$ped.cliente.telefono}</p>
						</div>
						<div class="idp-panel-col">
							<h2>Datos de Envío</h2>
							<p><span>Nombre Y Apellido:</span> {$ped.envio.nombre}</p>
							<p><span>NIF / CIF:</span> {$ped.envio.nif_cif}</p>
							<p><span>Dirección:</span> {$ped.envio.direccion}</p>
							<p><span>C.P.:</span> {$ped.envio.cp}</p>
							<p><span>Localidad:</span> {$ped.envio.localidad}</p>
							<p><span>Provincia:</span> {$ped.envio.provincia}</p>
							<p><span>Teléfono:</span> {$ped.envio.telefono}</p>
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
						{loop="$ped.productos"}
						<tr>
							<td style="vertical-align:top">
							{if="$value.id_tienda>0"}
							<img src="../img_products/comodin.jpg" alt="{$value.nombre}" title="{$value.nombre}"> {$value.nombre}
							{else}
							<img src="../img_products/{$value.img_name}" alt="{$value.nombre}" title="{$value.nombre}"> {$value.nombre}
							{/if}
							</td>
							<td>{$value.ean}</td>
							<td>{$value.part_number}</td>
							<td>{$value.idirecto}</td>
							<td>{$value.uds}</td>
							<td>{$value.precio|number_format:2,',','.'}€</td>
							<td>{$value.impuestos|number_format:2,',','.'}€</td>
							<td>{$value.subtotal|number_format:2,',','.'}€</td>
						</tr>
						{/loop}
						
					</table>
					
					<div class="totals">
						<div id="subtotal"><span>Subtotal:</span>{$ped.fac.subtotal|number_format:2,',','.'} €</div>
						<div id="impuestos"><span>Impuestos(*):</span> {$ped.fac.impuestos|number_format:2,',','.'} €</div>
						<div id="impuestos"><span>Recargo Equivalencia:</span> {$ped.fac.equivalencia|number_format:2,',','.'} €</div>
						<div id="impuestos"><span>Gastos de Envio:</span> {$ped.fac.envio|number_format:2,',','.'} €</div>
						<div id="total"><span>Total:</span>{$ped.fac.total|number_format:2,',','.'} €</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>