<?php 
function oj_load_part($part){
	switch($part){
		case 'fusioncharts':
			define(FC_DIR, OJ_LIBRARY.'/FusionCharts/Class');
			define(FC_URL, OJ_URL.'/library/FusionCharts/Charts');
			require_once(FC_DIR.'/FusionCharts_Gen.php');
			break;
	}
}
?>