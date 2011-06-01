<?php
class OJ_OBJECT{
	static $fields =	array(
			'problem' =>	array(
				'input' => 			array('name' =>'input' ,'title' =>'Input', 'type' =>'tinymce'),
				'output' => 		array('name' =>'output' ,'title' =>'Output', 'type' =>'tinymce'),
				'hint' =>			array('name' =>'hint' ,'title' =>'Hint', 'type' =>'tinymce'),
				'sample_input' =>	array("name" =>'sample_input' ,'title' =>'Sample Input', 'type' =>'textarea','positon'=>'left'),
				'sample_output' =>	array("name" =>'sample_output' ,'title' =>'Sample Output', 'type' =>'textarea','position'=>'right'),
				'test_input' =>		array("name" =>'test_input' ,'title' =>'Test Input', 'type' =>'textarea','positon'=>'left'),
				'test_output' => 	array("name" =>'test_output' ,'title' =>'Test Output', 'type' =>'textarea','position'=>'right'),
				'time_limit' => 	array('name'=> 'time_limit' ,'title'=> 'Time Limit(s):', 'type'=> 'text'),
				'memory_limit' => 	array('name'=>'memory_limit','title'=> 'Memory Limit(M)','type'=>'text'),
				'spj' =>			array('name'=> 'spj','title'=> 'Secial Judge' , 'type' => 'radio',
											'options'=>array(
													'default' => 'N', 
													'values'=>array("Y","N"))
												),
				'source' => 		array('name' => 'source', 'title'=>'Source', 'type' => 'text'),
			),
			'contest' =>	array(
				'start_time'=>		array('name'=>'start_time', 'title'=>'Start Time', 'type' =>'datetime',
											'yy'=>"start_yy",'mm'=>'start_mm','dd'=>'start_dd','hh'=>'start_hh','mn'=>'start_mn'),
				'end_time' =>		array('name'=>'end_time' , 'title' =>'End Time', 'type'=>'datetime',
											'yy'=>"end_yy",'mm'=>'end_mm','dd'=>'end_dd','hh'=>'end_hh','mn'=>'end_mn'),
				'private' => 		array('name'=>'private' , 'title' => 'Public' ,'type' => 'select',
											'raw_options' =>array('public' => FALSE,'private' =>FALSE),
											'options' =>array('public' => TRUE,'private' =>FALSE), 'multiple'=>false),
				'language'=>		array('name'=>'language' , 'title'=> 'Language', 'type'=> 'select', 
											'raw_options' =>array('C'=>FALSE,'C++'=>FALSE,'Pascal'=>FALSE,'JAVA'=>FALSE,'Ruby'=>FALSE,'Bash'=>FALSE,'Python'=>FALSE), 
											'options' => array('C'=>true,'C++'=>true,'Pascal'=>true,'JAVA'=>true,'Ruby'=>true,'Bash'=>true,'Python'=>true), 'multiple'=>true),
			),
		);
}