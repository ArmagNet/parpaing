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

// Get the ips from somewhere

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
			</div>
		</div>
	</div>
	<div class="col-md-6 safe-network">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Safe network</h3>
			</div>
			<div class="panel-body">
			</div>
		</div>
	</div>

</div>

<div class="lastDiv"></div>

<templates>
	<span aria-template-id="template-modify"><?php echo lang("common_modify"); ?></span>
	<span aria-template-id="template-cancel"><?php echo lang("common_cancel"); ?></span>
	<span aria-template-id="template-close"><?php echo lang("common_close"); ?></span>
	<div aria-template-id="template-ip" class="ip"
		data-mac=''
		data-ip=''>
		<a href="#"><span class="material-icons">${icon_type}</span></a>
		<a href="#">${ip}</a>
		<br/>
		<a href="#"><span class="ip-label">${label}</span></a>
	</div>
	<div aria-template-id="template-ip-form" class="ip-form">
		<span class="material-icons pull-left ip-type-icon" style="height: 120px; font-size: 80px;">${icon_type}</span>
		<div>
			<label class="col-md-3 text-right">IP :</label>
			<label class="col-md-7 text-left">${ip}</label>
		</div>
		<div>
			<label class="col-md-3 text-right">Adresse MAC :</label>
			<label class="col-md-7 text-left">${mac_address}</label>
		</div>
		<div>
			<label class="col-md-3 text-right">Constructeur :</label>
			<label class="col-md-7 text-left">${card_name}</label>
		</div>
		<div>
			<label class="col-md-3 text-right">Type :</label>
			<div class="col-md-7 ip-type">
				<div class="dropdown">
					<button class="btn btn-default dropdown-toggle"
						type="button" id="dropdown-type"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<span class="type-label">${type}</span><span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdown-type">
						<li class="dropdown-header"><?php echo lang("networks_mac_type_static"); ?></li>
						<li><a href="#" class="ip-type" data-type="desktop"><span class="material-icons md-18 pull-left">desktop_windows</span> <span class="type-label"><?php echo lang("networks_mac_type_desktop"); ?></span></a></li>
						<li><a href="#" class="ip-type" data-type="tv"><span class="material-icons md-18 pull-left">tv</span> <span class="type-label"><?php echo lang("networks_mac_type_tv"); ?></span></a></li>
						<li><a href="#" class="ip-type" data-type="print"><span class="material-icons md-18 pull-left">print</span> <span class="type-label"><?php echo lang("networks_mac_type_print"); ?></span></a></li>
						<li class="dropdown-header"><?php echo lang("networks_mac_type_mobile"); ?></li>
						<li><a href="#" class="ip-type" data-type="laptop"><span class="material-icons md-18 pull-left">laptop</span> <span class="type-label"><?php echo lang("networks_mac_type_laptop"); ?></span></a></li>
						<li><a href="#" class="ip-type" data-type="phone"><span class="material-icons md-18 pull-left">stay_primary_portrait</span> <span class="type-label"><?php echo lang("networks_mac_type_phone"); ?></span></a></li>
						<li><a href="#" class="ip-type" data-type="tablet"><span class="material-icons md-18 pull-left">tablet</span> <span class="type-label"><?php echo lang("networks_mac_type_tablet"); ?></span></a></li>
						<li class="dropdown-header"><?php echo lang("networks_mac_type_network"); ?></li>
						<li><a href="#" class="ip-type" data-type="server"><span class="material-icons md-18 pull-left">storage</span> <span class="type-label"><?php echo lang("networks_mac_type_server"); ?></span></a></li>
						<li><a href="#" class="ip-type" data-type="switch"><span class="material-icons md-18 pull-left">device_hub</span> <span class="type-label"><?php echo lang("networks_mac_type_switch"); ?></span></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div>
			<label class="col-md-3 text-right">Libellé :</label>
			<div class="col-md-7">
				<label class="ip-label text-left" style="width: 100%; height: 20px;">${label}</label>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</templates>

<script type="text/javascript">

//get the ips from somewhere
var safeIp = "192.168.1.1";
var unsafeIp = "192.168.0.1";

var safeIps = <?php echo json_encode($safes); ?>;
var unsafeIps = <?php echo json_encode($unsafes); ?>;

</script>
<?php include("footer.php");?>
</body>
</html>