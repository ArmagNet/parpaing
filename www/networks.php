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

require_once 'engine/bo/NetworkBo.'.$config["parpaing"]["dialect"].'.php';

$networkBo = NetworkBo::newInstance($config);

$unsafes = $networkBo->scan("192.168.0.1", true);
$safes = $networkBo->scan("192.168.1.1", true);

?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_networks"); ?></li>
	</ol>

	<div class="col-md-6 unsafe-network">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Unsafe network</h3>
			</div>
			<div class="panel-body">
<?php 	if (count($unsafes)) {
			foreach($unsafes as $ip) {?>

<div class="ip">
				<?php echo $ip["ip"]; ?><br />
</div>

<?php 		}
		}?>
			</div>
		</div>
	</div>
	<div class="col-md-6 safe-network">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Safe network</h3>
			</div>
			<div class="panel-body">
<?php 	if (count($safes)) {
			foreach($safes as $ip) {?>
				<?php echo $ip["ip"]; ?><br />
<?php 		}
		}?>
			</div>
		</div>
	</div>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<script type="text/javascript">
$(function() {
	//get the ips from somewhere

	// Get the unsafe network
	$.get("do_getNetwork.php", {ip: "192.168.0.1"}, function(data) {
		console.log(data);

		// Get the safe network
		$.get("do_getNetwork.php", {ip: "192.168.1.1"}, function(data) {
			console.log(data);
		}, "json");
}, "json");
});
</script>
</body>
</html>