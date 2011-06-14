<?php
class OJ_URL{
	var $menu;
	var $pages;
	function OJ_URL(){
		$this->pages = oj_get_pages();
		$this->register_menu('main', oj_get_menu_main());
		$this->register_menu('contest', oj_get_menu_contest());
	}
	function url($page){
		if($page=='home') return site_url();
		else{
			$the_url=site_url().'?oj='.$page;
			if(isset($_GET['cid'])){
				$the_url.='&cid='.$_GET['cid'];
				$the_url.='&ctitle='.$_GET['ctitle'];
			}elseif(isset($_GET['pid'])){
				$the_url.='&pid='.$_GET['pid'];
			}
			return $the_url;
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