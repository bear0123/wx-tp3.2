<?php
return array(
	//'配置项'=>'配置值'	
	'MULTI_MODULE'			=>  false,									// false为不允许多模块
	'DEFAULT_MODULE'		=>	'Home',									// 默认模块
	'LOAD_EXT_CONFIG' 		=> 	'db,constant',							// 扩展配置文件
	'TMPL_ACTION_ERROR'     =>  THINK_PATH.'Tpl/dispatch_jump.tpl', 	// 默认错误跳转对应的模板文件
	'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'Tpl/think_exception.tpl',	// 异常页面的模板文件
);