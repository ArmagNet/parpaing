<?php /*
	Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of OpenTweetBar.

    OpenTweetBar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    OpenTweetBar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with OpenTweetBar.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("header.php");
require_once 'engine/bo/VpnConfigurationBo.php';

$vpnConfigurationBo = VpnConfigurationBo::newInstance($config);
$configurations = $vpnConfigurationBo->getConfigurations();
$configurationMap = array();

foreach($configurations as $configuration) {
	$configurationMap[$configuration["id"]] = array("label" => $configuration["label"], "active" => $configuration["active"]);
}

$vpnFiles = array();
$vpnLis = array();
$vpnDivs = array();

if ($handle = opendir('vpn')) {

	while (false !== ($entry = readdir($handle))) {
		if (strpos($entry, ".php") != false) {
			$vpnFiles[] = $entry;
		}
	}

	closedir($handle);

	sort($vpnFiles);
}

foreach($vpnFiles as $vpnFile) {

	ob_start();

	include("vpn/" . $vpnFile);

	$content = ob_get_clean();

	$separation = strpos($content, "</li>") + 5;

	$vpnLis[] = trim(substr($content, 0, $separation));
	$vpnDivs[] = trim(substr($content, $separation));

}

?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_vpn"); ?></li>
	</ol>

	<div class="col-md-12">

		<ul class="nav nav-tabs" role="tablist" id="vpnTabs">
<?php
foreach($vpnLis as $vpnLi) {
	echo $vpnLi;
}
?>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
<?php
foreach($vpnDivs as $vpnDiv) {
	echo $vpnDiv;
}
?>
		</div>

	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
</body>
</html>