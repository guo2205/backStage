<?php
return array(
    'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  'localhost',
    'DB_NAME'                   =>  'hy',
    'DB_USER'                   =>  'root',
    'DB_PWD'                    =>  '',
    'DB_PORT'                   =>  '',
   	'DB_PREFIX'                 =>  '',
    
    /*
    'DEFAULT_CONTROLLER'=>'Index',
    'DEFAULT_ACTION'   =>  'login',
    */
    'SESSION_AUTO_START' =>true,
    //'SHOW_PAGE_TRACE'           =>  1,
	'URL_MODEL'				    => 	'3',

	//默认错误跳转对应的模板文件
	 'TMPL_ACTION_ERROR' 		=> 	'Public:error',
 	//默认成功跳转对应的模板文件
	 'TMPL_ACTION_SUCCESS' 		=> 	'Public:error'
);


// USER_AUTH_ON 是否需要认证
// USER_AUTH_TYPE 认证类型
// USER_AUTH_KEY 认证识别号
// REQUIRE_AUTH_MODULE  需要认证模块
// NOT_AUTH_MODULE 无需认证模块
// USER_AUTH_GATEWAY 认证网关
// RBAC_DB_DSN  数据库连接DSN
// RBAC_ROLE_TABLE 角色表名称
// RBAC_USER_TABLE 用户表名称
// RBAC_ACCESS_TABLE 权限表名称
// RBAC_NODE_TABLE 节点表名称