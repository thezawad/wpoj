<?php
class OJ_URL{
	var $menu;
	function OJ_URL(){
		$this->register_menu('main', oj_get_menu_main());
		$this->register_menu('contest', oj_get_menu_contest());
	}
	function url($page){
		global $ojp;
		if($page=='home') return site_url();
		else{
			$the_url=site_url().'?oj='.$page;
			if(isset($_GET['cid'])){
				if(in_array('cid',$ojp[$page]['args'])) $the_url.='&cid='.$_GET['cid'];
				if(isset($_GET['ctitle']) && in_array('ctitle',$ojp[$page]['args']) ) $the_url.='&ctitle='.$_GET['ctitle'];
				if(isset($_GET['pid']) && in_array('pid',$ojp[$page]['args']) ) $the_url.='&pid='.$_GET['pid'];
				if(isset($_GET['cpid']) && in_array('cpid',$ojp[$page]['args'])) $the_url.='&cpid='.$_GET['cpid'];
			}
			return $the_url;
		}
	}
	function link($page){
		$link=sprintf('<a href="%s">%s</a>',$this->url($page),$this->label($page));
		return $link;
	}
	function label($page){
		global $ojp;
		if(!empty($ojp[$page]['label'])){
			return $ojp[$page]['label'];
		}else{
			return ucfirst($page);
		}
	}
	function register_menu($pos,$pages){
		$this->menu[$pos]=$pages;
	}
	function get_menu($pos){
		return $this->menu[$pos];
	}
}
?>