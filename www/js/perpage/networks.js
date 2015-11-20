if (!safeIp) {
	var safeIp = "192.168.1.1";
}

if (!unsafeIp) {
	var unsafeIp = "192.168.0.1";
}

if (!safeIps) {
	var safeIps = [];
}

if (!unsafeIps) {
	var unsafeIps = [];
}

function getIconType(type) {
	if (!type) type = "desktop";

	var link = $("templates a.ip-type[data-type="+type+"]");

	if (link.length == 1) {
		return link.find("span.material-icons").text();
	}

	return "";
}

// I18N this function
function getType(type) {
	if (!type) type = "desktop";

	var link = $("templates a.ip-type[data-type="+type+"]");

	if (link.length == 1) {
		return link.find("span.type-label").text();
	}

	return "";
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

function updateNetwork(ips, networkPart) {
	var ipList = $("div." + networkPart + "-network .panel-body");

	ipList.children().remove();
	for(var index = 0; index < ips.length; ++index) {
		var ip = ips[index];

		var ipDiv = 	$("*[aria-template-id=template-ip]").template(
				"use", {
				data: {
					icon_type: getIconType(ip.type),
					ip: ip.ip,
					label: ip.label ? ip.label : (ip.netbios ? ip.netbios : "")
				}
			});

		ipDiv.data("mac", ip.mac_address);
		ipDiv.data("ip", ip);

		ipList.append(ipDiv);
	}
}

$(function() {
	updateNetwork(unsafeIps, "unsafe");
	updateNetwork(safeIps, "safe");

	// Get the unsafe network
	$.get("do_getNetwork.php", {ip: unsafeIp}, function(data) {
		if (data.ok) {
			updateNetwork(data.ips, "unsafe");
		}

		// Get the safe network
		$.get("do_getNetwork.php", {ip: safeIp}, function(data) {
			if (data.ok) {
				updateNetwork(data.ips, "safe");
			}
		}, "json");
	}, "json");

	$(".container").on("click", "div.ip a", function(event) {
		event.preventDefault();

		var ip = $(this).parents(".ip").data("ip");

		showIpBox(ip);
	});
});