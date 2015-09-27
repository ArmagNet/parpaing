<?php
session_start();

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'engine/bo/VpnBo.php';

$vpnBo = VpnBo::newInstance($config);

// Giving no configuration
$vpnBo->activate();

echo json_encode(array("ok" => "ok"));

?>