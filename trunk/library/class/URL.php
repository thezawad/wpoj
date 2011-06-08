<?php
class OJ_URL{
	var $menu;
	var $pages;
	function OJ_URL(){
		$this->pages = oj_get_pages();
		$this->register_menu('main', oj_get_menu_main());
	}
	function url($page){
		if($page=='home') return site_url();
		else{
			return site_url().'?oj='.$page;
		}
	}
	function link($page){
		$link=sprintf('<a href="%s">%s</a>',$this->url($page),$this->label($page));
		return $link;
	}
	function label($page){
		if(!empty($this->pages[$page]['label'])){
			return $this->pages[$page]['label'];
		}else{
			return strtoupper($page);
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