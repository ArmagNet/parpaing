<?php /*
	Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of Parpaing.

    Parpaing is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Parpaing is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Parpaing.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("header.php");
require_once 'engine/bo/UshareBo.'.$config["parpaing"]["dialect"].'.php';

$ushareBo = UshareBo::newInstance($config);

$isActive = $ushareBo->isActive();

$isWan = ($ushareBo->getInterface() == "eth0");

?>
<div class="container theme-showcase" role="main">
	<div>
		<div class="pull-right breadcrumb">
			<input id="upnp-active-button" type="checkbox" <?php if ($isActive) { echo "checked"; } ?> data-handle-width="38" data-size="mini">
		</div>
		<ol class="breadcrumb">
			<li><?php echo lang("breadcrumb_index"); ?></li>
			<li class="active"><?php echo lang("breadcrumb_upnp"); ?></li>
		</ol>
	</div>

	<div>
		<div class="pull-right breadcrumb">
			<input id="upnp-interface-button" type="checkbox" <?php if ($isWan) { echo "checked"; } ?> data-handle-width="38" data-on-text="WAN" data-off-text="LAN" data-size="mini">
		</div>
		<ol class="breadcrumb">
			<li><?php echo lang("upnp_interface"); ?></li>
		</ol>
	</div>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
</body>
</html>