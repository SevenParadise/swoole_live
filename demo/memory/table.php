<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/31
 * Time: 9:43 AM
 */
// 创建内存表
$table = new Swoole\Table(1024);

// 内存表增加一列
$table->column('id', \Swoole\Table::TYPE_INT, 4);
$table->column('name', Swoole\Table::TYPE_STRING, 64);
$table->column('age', Swoole\Table::TYPE_INT, 3);
$table->create();

$table->set("zhangcs", ['id' => 1, 'name' => 'zhangcs', 'age' => 26]);

print_r($table->get('zhangcs'));