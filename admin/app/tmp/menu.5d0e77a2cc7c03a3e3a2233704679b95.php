<?php if(!class_exists('raintpl')){exit;}?><div id="idp-panel-menu">
	<div id="idp-logo">
		<img src="http://idirecto.es/decoexsa/img/vinooferta-logo-admin.png" alt="Logo">
	</div>

	<div id="idp-menu-holder">
		<!-- Gobal Menu -->
		<div class="sub-menu-head">Pedidos<div class="compact-button"></div></div>
		<ul>
			<li<?php echo $menu["1"];?>><a href="http://idirecto.es/decoexsa/pedidos/listado/1">Pendientes</a></li>
			<li<?php echo $menu["2"];?>><a href="http://idirecto.es/decoexsa/pedidos/listado/2">Entregados</a></li>
		</ul>
	</div>
</div>