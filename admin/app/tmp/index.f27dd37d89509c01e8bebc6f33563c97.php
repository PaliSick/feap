<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/head") . ( substr("templates/head",-1,1) != "/" ? "/" : "" ) . basename("templates/head") );?>

<body>
	<div id="mainHolder">
		<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/menu") . ( substr("templates/menu",-1,1) != "/" ? "/" : "" ) . basename("templates/menu") );?>


		<div id="idp-panel-body">

			<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("templates/breadcrumb") . ( substr("templates/breadcrumb",-1,1) != "/" ? "/" : "" ) . basename("templates/breadcrumb") );?>


			<div id="idp-cfgpanel">

				<div id="idp-cfgpanel-canvas">
					<!-- Aqui comienza el body del form -->
					<h1>Bienvenido!</h1>
					<p>Utilice el menú de la izquierda para navegar por las distintas configuraciones que la aplicación le ofrece</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>