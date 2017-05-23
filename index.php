<?php
require './validate.class.php';
require './sanitize.class.php';
require './simpledb.class.php';


$dbconfig['name']='test';
$dbconfig['username']='homestead';
$dbconfig['password']='secret';

$db=new simpledb($dbconfig,new sanitize(),new validate());


$rows=$db->query('SHOW DATABASES;');
$db->printRows($rows);

$rows=$db->query('SHOW TABLES;');
$db->printRows($rows);

$rows=$db->exec("
CREATE TABLE IF NOT EXISTS `customers` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '',
  `phone` varchar(200) DEFAULT '',
  `email` varchar(132) DEFAULT '',
  `address` varchar(200) DEFAULT '',
  `nick` varchar(200) DEFAULT '',
  `name` TEXT,
  `description` TEXT,
  `status` varchar(1) DEFAULT '',
  `created_at` datetime DEFAULT now(),
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
");
$db->printRows($rows);

$rows=$db->query('DESCRIBE customers;');
$db->printRows($rows);


$rows=$db->query('select id,name,phone,email,created_at from customers;');
$db->printRows($rows);

$rows=$db->exec('delete from customers;');
$db->printRows($rows);

$rows=$db->query('select id,name,phone,email,created_at from customers;');
$db->printRows($rows);

$datums=[

[1,'lasellers','111 222 333','lasellers@gmail.com'],
[2,'dev.lasellers','123 456 7890','dev.lasellers@gmail.com'],
[3,'dev2.lasellers','123 456','dev2.lasellers@gmail.com'],
[4,'sdfsdfdsf','sdfsdfsdf','lasellers@gmail.com'],
[5,'','',''],
[6,'6','6','6'],
[7,'<script>alert("7");</script>','<script>alert("7");</script>','<script>alert("7");</script>'],
[8,'888','888','888'],

];

$customersTypes=['integer','string','phone','email'];

foreach($datums as $datum) {
	list($id,$name,$phone,$email)=$datum;
	//v	ar_dump($datum);
	$rows=$db->exec('insert into customers (id,name,phone,email) values (?,?,?,?);',[$id,$name,$phone,$email],$customersTypes);
	$db->printRows($rows);
}


$rows=$db->query('select id,name,phone,email from customers where id=?;',[1]);
$db->printRows($rows);

$rows=$db->query('select id,name,phone,email,created_at from customers;');
$db->printRows($rows);

