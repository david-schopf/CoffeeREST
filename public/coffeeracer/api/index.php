<?php

require_once '../../../vendor/restler.php';
use Luracast\Restler\Restler;
use Luracast\Restler\Format\UploadFormat;

error_reporting(E_ALL^E_NOTICE^E_WARNING^E_STRICT);
ini_set("display_errors",1);

$r = new Restler();
$r->addAPIClass('User');
$r->addAPIClass('Coffee');
$r->addAPIClass('Reinigung');
$r->addAPIClass('Alarm');
$r->addAPIClass('Bild');
$r->setSupportedFormats('JsonFormat', 'UploadFormat');
$r->handle();

