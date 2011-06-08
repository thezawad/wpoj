<?php
function oj_list_menu($pos,$menu_class="",$item_class="menu-item"){
	global $oju;
	$menu_id='oj-menu-'.$pos;
	$items=$oju->get_menu($pos);
	printf('<ul id="%s" class="%s">',$menu_id,$menu_class);
	foreach ($items as $page){
		$item_id='page-'.$page;
		$item_url=$oju->url($page);
		$item_label=$oju->label($page);
		printf('<li id="%s" class="%s"> <a href="%s">%s</a></li>',$item_id,$item_class,$item_url,$item_label);
	}
	echo '</ul>';
}
?>