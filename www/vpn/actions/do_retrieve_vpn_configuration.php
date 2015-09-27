<?php
session_start();

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'engine/bo/VpnConfigurationBo.php';

$vpnConfigurationBo = VpnConfigurationBo::newInstance($config);
$vpnId = $_REQUEST["vpn_id"];

$configuration = $vpnConfigurationBo->getConfigurationById($vpnId);

unset($configuration["key"]);
unset($configuration["crt"]);
unset($configuration["cacrt"]);

echo json_encode(array("ok" => "ok", "configuration" => $configuration));

?>