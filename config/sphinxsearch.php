<?php

return array (
    'host'    => '127.0.0.1', #sphinx服务器IP
    'port'    => 1234, #端口号
    'timeout' => 5,
    'indexes' => array (
        'test1' => array ( 'table' => 'articles', 'column' => 'id' ),
        #索引的名称           索引所在表的名称         主键id
    ),
    // 'mysql_server' => array(
    //     'host' => '127.0.0.1',
    //     'port' => 3306,
    //     // 'user' => 'root',
    //     // 'password' => 123456,
    // ),
);