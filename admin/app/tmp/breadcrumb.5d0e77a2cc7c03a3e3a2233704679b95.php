<?php if(!class_exists('raintpl')){exit;}?>				<div id="idp-top-bar">
					<div id="breadcumb">
						<span class="button first-button"><a href="http://idirecto.es/decoexsa/<?php echo $controller;?>" style="z-index:2"><span class="breadcrumb-home"></span><?php echo $bread_section;?><span class="breadcumb-arrow"></span></a></span>
						<?php if( $action != 'index' ){ ?>

						<span class="button"><a href="http://idirecto.es/decoexsa/<?php echo $controller;?>/<?php echo $action;?>" style="z-index:1"><?php echo $bread_action;?><span class="breadcumb-arrow"></span></a></span>';
						<?php } ?>

					</div>
					<div id="logout-holder"><a class="logout-link" href="http://idirecto.es/decoexsa/#">Salir</a></div>
				</div>