<?php

require_once '../../../vendor/restler.php';
use Luracast\Restler\Restler;

error_reporting(E_ALL^E_NOTICE^E_WARNING);
ini_set("display_errors",1);

$r = new Restler();
$r->addAPIClass('User');
$r->addAPIClass('Coffee');
$r->addAPIClass('Reinigung');
$r->addAPIClass('Alarm');
$r->handle();

