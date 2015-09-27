<li role="presentation" class="active" id="vpns_tab"><a href="#vpns" aria-controls="vpns" role="tab" data-toggle="tab">VPNs</a>
</li>

<div role="tabpanel" class="tab-pane fade in active" id="vpns">
	</br>

	<ul class="list-group">
	</ul>

	<div class="text-right">
		<button type="button" id="vpns_activateButton" class="btn btn-success">Engager le VPN</button>
		<button type="button" id="vpns_deactivateButton" class="btn btn-danger">Desengager le VPN</button>
		<button type="button" id="vpns_addVpnButton" class="btn btn-primary">Ajouter un VPN</button>
	</div>

	<templates>
		<li aria-template-id="template-vpn-listitem" class="template list-group-item ${active}">
			<span class="vpn-name">${label}</span>
			<span class="pull-right">
				<a href="#" data-configuration-id="${configurationId}" class="activate">Activer</a>
				<a href="#" data-configuration-id="${configurationId}" class="modify">Modifier</a>
				<a href="#" data-configuration-id="${configurationId}" class="delete">Supprimer</a>
			</span>
		</li>
	</templates>

<script type="text/javascript">

var configurations = <?php echo json_encode($configurationMap); ?>;
$(function() {
	updateAvailableConfigurations(configurations);
});

</script>

<script>

function deleteVpn(configurationId) {
	var myForm = { "vpn_id" : configurationId };
	$.post("vpn/actions/do_delete_vpn.php", myForm, function(data) {
		updateAvailableConfigurations(data.configurations);
	}, "json");
}

function activateVpn(configurationId) {
	var myForm = { "vpn_id" : configurationId };
	$.post("vpn/actions/do_activate_vpn.php", myForm, function(data) {
		updateAvailableConfigurations(data.configurations);
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

			var remote = data.configuration.json.remote;
			remote = remote.split(" ");

			form.find("#remoteIpInput").val(remote[0]);
			form.find("#remotePortInput").val(remote[1]);

			$("#vpnTabs #raw_vpn_tab a").tab("show");
		}
	}, "json");
}

function updateAvailableConfigurations(configurations) {
	var list = $("#vpns .list-group");

	list.children().remove();

	for(var configurationId in configurations) {
		var li = $("*[aria-template-id=template-vpn-listitem]").template(
			"use", {
				data: {	"label" : configurations[configurationId].label,
						"configurationId" : configurationId,
						"active" : configurations[configurationId].active ? "list-group-item-success" : ""}
		});
		list.append(li);
	}

	list.find("a.activate").click(function() {
		if ($(this).hasClass("active")) {
			return;
		}
		var configurationId = $(this).data("configuration-id");
		bootbox.setLocale("fr");
		bootbox.confirm("Activer le VPN \"" + $(this).parents("li").find(".vpn-name").text() + "\" ?", function(result) {
			if (result) {
				activateVpn(configurationId);
			}
		});
	});

	list.find("a.delete").click(function() {
		var configurationId = $(this).data("configuration-id");
		bootbox.setLocale("fr");
		bootbox.confirm("Supprimer le VPN \"" + $(this).parents("li").find(".vpn-name").text() + "\" ?", function(result) {
			if (result) {
				deleteVpn(configurationId);
			}
		});
	});

	list.find("a.modify").click(function() {
		var configurationId = $(this).data("configuration-id");
		modifyVpn(configurationId);
	});
}

$(function() {
	$("#vpns_addVpnButton").click(function() {
		$("#vpnTabs #raw_vpn_tab a").tab("show");
	});

	$("#vpns_activateButton").click(function() {
		$.post("vpn/actions/do_enable_vpn.php", {}, function(data) {
			$("#vpns_activateButton").addClass("disabled");
			$("#vpns_deactivateButton").removeClass("disabled");
		});
	});
	$("#vpns_deactivateButton").click(function() {
		$.post("vpn/actions/do_disable_vpn.php", {}, function(data) {
			$("#vpns_deactivateButton").addClass("disabled");
			$("#vpns_activateButton").removeClass("disabled");
		});
	});

	$("templates *.template").template();
});
</script>

</div>