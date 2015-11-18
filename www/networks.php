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

function getIconType($type) {
	switch($type) {
		case "tv":
			return "tv";
		case "print":
			return "print";
		case "laptop":
			return "laptop";
		case "phone":
			return "stay_primary_portrait";
		case "tablet":
			return "tablet";
		case "server":
			return "storage";
		case "switch":
			return "device_hub";
		case "desktop":
		default:
			return "desktop_windows";
	}
}

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

<div class="ip"
	data-mac='<?php echo $ip["mac_address"]; ?>'
	data-ip='<?php echo str_replace("\'", "\\'", json_encode($ip));?>'>
	<a href="#"><span class="material-icons"><?php
		echo getIconType($ip["type"]);
	?></span></a>
	<a href="#"><?php echo $ip["ip"]; ?></a>
	<br/>
	<a href="#"><span class="ip-label"><?php echo $ip["label"] ? $ip["label"] : $ip["netbios"]; ?></span></a>
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
<div class="ip"
	data-mac='<?php echo $ip["mac_address"]; ?>'
	data-ip='<?php echo str_replace("\'", "\\'", json_encode($ip));?>'>
	<a href="#"><span class="material-icons"><?php
		echo getIconType($ip["type"]);
	?></span></a>
	<a href="#"><?php echo $ip["ip"]; ?></a>
	<br/>
	<a href="#"><span class="ip-label"><?php echo $ip["label"] ? $ip["label"] : $ip["netbios"]; ?></span></a>
</div>
<?php 		}
		}?>
			</div>
		</div>
	</div>

</div>

<div class="lastDiv"></div>

<templates>
	<span aria-template-id="template-modify"><?php echo lang("common_modify"); ?></span>
	<span aria-template-id="template-cancel"><?php echo lang("common_cancel"); ?></span>
	<span aria-template-id="template-close"><?php echo lang("common_close"); ?></span>
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

<?php include("footer.php");?>
<script type="text/javascript">

function getIconType(type) {
	switch(type) {
		case "tv":
			return "tv";
		case "print":
			return "print";
		case "laptop":
			return "laptop";
		case "phone":
			return "stay_primary_portrait";
		case "tablet":
			return "tablet";
		case "server":
			return "storage";
		case "switch":
			return "device_hub";
		case "desktop":
		default:
			return "desktop_windows";
	}
}

// I18N this function
function getType(type) {
	switch(type) {
		case "tv":
			return "TV";
		case "print":
			return "Imprimante";
		case "laptop":
			return "Portable";
		case "phone":
			return "Mobile";
		case "tablet":
			return "Tablette";
		case "server":
			return "Serveur";
		case "switch":
			return "Réseau";
		case "desktop":
		default:
			return "Ordinateur";
	}
}

function showIpBox(ip) {
	var form = 	$("*[aria-template-id=template-ip-form]").template(
			"use", {
			data: {
				icon_type: getIconType(ip.type),
				ip: ip.ip,
				mac_address: ip.mac_address ? ip.mac_address : "-",
				card_name: ip.card_name ? ip.card_name : "-",
				type: getType(ip.type),
				label: ip.label ? ip.label : (ip.netbios ? ip.netbios : "")
			}
		});

	bootbox.dialog({
		title: ip.ip,
		message: form,
		buttons: {
			close: {
			      label: $("*[aria-template-id=template-close]").text(),
			      className: "btn-default",
			      callback: function() {
			      }
			}
/*			cancel: {
			      label: $("*[aria-template-id=template-cancel]").text(),
			      className: "btn-default",
			      callback: function() {
			      }
			}
			,
			success: {
			      label: $("*[aria-template-id=template-modify]").text(),
			      className: "btn-primary",
			      callback: function() {
			      }
			}*/
		}
	});
	var zIndex = $(".modal-backdrop").css("z-index");
	$(".modal-dialog").css({"z-index": zIndex});

	$(form).find("a.ip-type").click(function(event) {
		event.preventDefault();
		var link = $(this);
		var type = $(this).data("type");

		link.parents(".ip-form").find(".ip-type-icon").text(getIconType(type));
		link.parents("div.ip-type").find(".type-label").text(getType(type));

		$.post("do_setMacAddress_info.php", {type: type, macAddress: ip.mac_address}, function(data) {
			if (data.ok) {
				// Update data
				ip.type = type;

				$(".ip").each(function() {
					if ($(this).data("mac") == ip.mac_address) {
						$(this).data("ip", ip);
						$(this).find(".material-icons").text(getIconType(ip.type));
					}
				});
			}

		}, "json");

	});

	$(form).find(".ip-label").click(function() {
		var content = $(this);
		var input = $("<input style=\"width: 110px; margin-right: 10px;\"></input>");
		input.val(content.text().trim());

		content.before(input);
		input.focus();

		var buttons = "<div class=\"text-right\" style=\"display: inline-block;\">";
		buttons += "<button class=\"btn btn-primary btn-xs modify-button\" type=\"button\"><span class=\"glyphicon glyphicon-ok\"></span></button>";
		buttons += " <button class=\"btn btn-default btn-xs cancel-button\" type=\"button\"><span class=\"glyphicon glyphicon-remove\"></span></button>";
		buttons += "</div>";
		buttons = $(buttons);
		content.before(buttons);

		content.hide();

		input.blur(function() {
			if (input.val() == content.text().trim()) {
				content.show();
				input.remove();
				buttons.remove();
			}
		});

		var modifyButton = buttons.find(".modify-button");
		modifyButton.click(function() {

			var macLabel = input.val();
			$.post("do_setMacAddress_info.php", {label: macLabel, macAddress: ip.mac_address}, function(data) {
				if (data.ok) {
					// Update data
					ip.label = input.val();

					content.text(ip.label);
					$(".ip").each(function() {
						if ($(this).data("mac") == ip.mac_address) {
							$(this).data("ip", ip);
							$(this).find("span.ip-label").text(ip.label);
						}
					});
				}

				content.show();
				input.remove();
				buttons.remove();

			}, "json");

		});

		var cancelButton = buttons.find(".cancel-button");
		cancelButton.click(function() {
			content.show();
			input.remove();
			buttons.remove();
		});
	});
}

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

	$(".container").on("click", "div.ip a", function(event) {
		event.preventDefault();

		var ip = $(this).parents(".ip").data("ip");
//		console.log(ip);

		showIpBox(ip);
	});

});
</script>
</body>
</html>