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
*/ ?>
<li role="presentation" class="active" id="vpns_tab"><a href="#vpns" aria-controls="vpns" role="tab" data-toggle="tab"><?php echo lang("vpn_0_tab_legend");?></a></li>

<div role="tabpanel" class="tab-pane fade in active" id="vpns">
	</br>

	<ul class="list-group">
	</ul>

	<div class="text-right">
		<button type="button" id="vpns_activateButton" class="btn btn-success"><?php echo lang("vpn_engageVpn_button"); ?></button>
		<button type="button" id="vpns_deactivateButton" class="btn btn-danger"><?php echo lang("vpn_disengageVpn_button"); ?></button>
		<button type="button" id="vpns_addVpnButton" class="btn btn-primary"><?php echo lang("vpn_addVpn_button"); ?></button>
	</div>

	<templates>
		<li aria-template-id="template-vpn-listitem" class="template list-group-item ${active}">
			<span class="vpn-name">${label}</span>
			<span class="pull-right">
				<a href="#" data-configuration-id="${configurationId}" class="activate text-success"><span
					title="<?php echo lang("common_activate"); ?>"
					class="glyphicon glyphicon-ok" data-toggle="tooltip" data-placement="bottom"></span></a>
				<a href="#" data-configuration-id="${configurationId}" class="modify"><span
					title="<?php echo lang("common_modify"); ?>"
					class="glyphicon glyphicon-cog" data-toggle="tooltip" data-placement="bottom"></span></a>
				<a href="#" data-configuration-id="${configurationId}" class="delete text-danger"><span
					title="<?php echo lang("common_delete"); ?>"
					class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="bottom"></span></a>
			</span>
			<span title="<?php echo lang("vpn_has_key"); ?>"
				class="octicon octicon-key pull-right text-success"
				data-toggle="tooltip" data-placement="bottom"></span>
		</li>
		<span aria-template-id="template-vpn-activate" class="template"><?php echo lang("vpn_activateVpn_question"); ?></span>
		<span aria-template-id="template-vpn-delete" class="template"><?php echo lang("vpn_deleteVpn_question"); ?></span>
</templates>

<script type="text/javascript">

var configurations = <?php echo json_encode($configurationMap); ?>;
var activeStatus = <?php echo $vpnBo->isActive() ? 'true' : 'false'; ?>;
$(function() {
	updateAvailableConfigurations(configurations);
	updateStatus();
});

</script>

<script>

function updateStatus() {
	if (activeStatus) {
		$("#vpns_deactivateButton").removeClass("disabled");
		$("#vpns_activateButton").addClass("disabled");
	}
	else {
		$("#vpns_deactivateButton").addClass("disabled");
		$("#vpns_activateButton").removeClass("disabled");
	}
}

function deleteVpn(configurationId) {
	var myForm = { "vpn_id" : configurationId };
	$.post("vpn/actions/do_delete_vpn.php", myForm, function(data) {
		updateAvailableConfigurations(data.configurations);
	}, "json");
}

function activateVpn(configurationId) {
	$("#vpns_deactivateButton").addClass("disabled");
	$("#vpns_activateButton").addClass("disabled");

	var myForm = { "vpn_id" : configurationId };
	$.post("vpn/actions/do_activate_vpn.php", myForm, function(data) {
		updateAvailableConfigurations(data.configurations);

		activeStatus = "" + data.isActive == "true" ? true : false;
		updateStatus();
	}, "json");
}

function modifyVpn(configurationId) {
	var myForm = { "vpn_id" : configurationId };
	$.post("vpn/actions/do_retrieve_vpn_configuration.php", myForm, function(data) {
//		updateAvailableConfigurations(data.configurations);

		if (data.ok) {
			var form = $("#raw_vpn form");
			form.find("#idInput").val(configurationId);
			form.find("#labelInput").val(data.configuration.label);
			form.find("#devInput").val(data.configuration.json.dev);
			form.find("#protoInput").val(data.configuration.json.proto);
			form.find("#compLzoInput").val(data.configuration.json.comp_lzo);
			form.find("#cipherInput").val(data.configuration.json.cipher);
			form.find("#remoteCertTlsInput").val(data.configuration.json.remote_cert_tls);

			var keyButtonFunction = data.configuration.hasKey ? "show" : "hide";
			form.find("#keyButton + a")[keyButtonFunction]();
			form.find("#keyButton + a").data("configuration-id", data.configuration.id);

			var certButtonFunction = data.configuration.hasCert ? "show" : "hide";
			form.find("#certButton + a")[certButtonFunction]();
			form.find("#certButton + a").data("configuration-id", data.configuration.id);

			var caButtonFunction = data.configuration.hasCacert ? "show" : "hide";
			form.find("#caButton + a")[caButtonFunction]();
			form.find("#caButton + a").data("configuration-id", data.configuration.id);

			form.find("#opvnFieldset").show();
			form.find("a#ovpnLink").data("configuration-id", data.configuration.id);

			var remote = data.configuration.json.remote;
			remote = remote.split(" ");

			form.find("#remoteIpInput").val(remote[0]);
			form.find("#remotePortInput").val(remote[1]);

			if (!data.configuration.json.type && data.configuration.hasCert) {
				data.configuration.json.type = "certificate";
			}

			// Handling the different types of connections
			form.find("input[name=vpnTypeInput]").each(function() {
				if ($(this).val() == data.configuration.json.type) {
					$(this).attr("checked", "checked");
				}
				else {
					$(this).removeAttr("checked");
				}
			});
			vpnTypeHandler();

			$("#vpnTabs #raw_vpn_tab a").tab("show");
		}
	}, "json");
}

function updateAvailableConfigurations(configurations) {
	var list = $("#vpns .list-group");

	list.children().remove();

	for(var configurationId in configurations) {
		var configuration =  configurations[configurationId];
		var li = $("*[aria-template-id=template-vpn-listitem]").template(
			"use", {
				data: {	"label" : configuration.label,
						"configurationId" : configurationId,
						"active" : configuration.active ? "list-group-item-success" : ""}
		});

		if (!configuration.hasKey) {
			li.find(".octicon-key").remove();
		}

		list.append(li);
	}

	list.find("a.activate").click(function() {
		if ($(this).hasClass("active")) {
			return;
		}
		var configurationId = $(this).data("configuration-id");
		bootbox.setLocale("fr");
		var question = $("*[aria-template-id=template-vpn-activate]").template("use", {
					data: {	"vpnLabel" : $(this).parents("li").find(".vpn-name").text()}
			}).text();
		bootbox.confirm(question, function(result) {
			if (result) {
				activateVpn(configurationId);
			}
		});
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});
	});

	list.find("a.delete").click(function() {
		var configurationId = $(this).data("configuration-id");
		bootbox.setLocale("fr");
		var question = $("*[aria-template-id=template-vpn-delete]").template("use", {
			data: {	"vpnLabel" : $(this).parents("li").find(".vpn-name").text()}
		}).text();
		bootbox.confirm(question, function(result) {
			if (result) {
				deleteVpn(configurationId);
			}
		});
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});
	});

	list.find("a.modify").click(function() {
		var configurationId = $(this).data("configuration-id");
		modifyVpn(configurationId);
	});
}

$(function() {
	$("#vpns_addVpnButton").click(function() {
		$("#raw_vpn #resetButton").click();
		$("#vpnTabs #raw_vpn_tab a").tab("show");
	});

	$("#vpns_activateButton").click(function() {
		$("#vpns_deactivateButton").addClass("disabled");
		$("#vpns_activateButton").addClass("disabled");

		$.post("vpn/actions/do_enable_vpn.php", {}, function(data) {
			activeStatus = "" + data.isActive == "true" ? true : false;
			updateStatus();
		}, "json");
	});
	$("#vpns_deactivateButton").click(function() {
		$("#vpns_deactivateButton").addClass("disabled");
		$("#vpns_activateButton").addClass("disabled");

		$.post("vpn/actions/do_disable_vpn.php", {}, function(data) {
			activeStatus = "" + data.isActive == "true" ? true : false;
			updateStatus();
		}, "json");
	});
});
</script>

</div>