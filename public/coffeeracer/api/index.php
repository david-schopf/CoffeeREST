<?php

require_once '../../../vendor/restler.php';
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Functions.php";
use Luracast\Restler\Restler;
use Luracast\Restler\Format\UploadFormat;

error_reporting(E_ALL^E_NOTICE^E_WARNING^E_STRICT);
ini_set("display_errors",0);

$r = new Restler();
$r->addAPIClass('User');
$r->addAPIClass('Coffee');
$r->addAPIClass('Reinigung');
$r->addAPIClass('Alarm');
$r->addAPIClass('Bild');
$r->setSupportedFormats('JsonFormat', 'UploadFormat');
$r->setAPIVersion(1);
$r->addAuthenticationClass('CoffeeAuth');
$r->handle();



