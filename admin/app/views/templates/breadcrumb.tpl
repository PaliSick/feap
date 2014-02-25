				<div id="idp-top-bar">
					<div id="breadcumb">
						<span class="button first-button"><a href="{$controller}" style="z-index:2"><span class="breadcrumb-home"></span>{$bread_section}<span class="breadcumb-arrow"></span></a></span>
						{if="$action != 'index'"}
						<span class="button"><a href="{$controller}/{$action}" style="z-index:1">{$bread_action}<span class="breadcumb-arrow"></span></a></span>';
						{/if}
					</div>
					<div id="logout-holder"><a class="logout-link" href="#"  id="salir"  >Salir</a></div>
				</div>