function retrieveWifiInformationHandler(wifiInfos) {
	$("#ssidInput").val(wifiInfos.ssid);
	$("#passphraseInput").val(wifiInfos.key);
	$("#channelInput").val(wifiInfos.channel);
	$("#encryptionInput").val(wifiInfos.encryption);

	if (wifiInfos.disabled == 0) {
		$("#enableButton").addClass("disabled");
		$("#disableButton").removeClass("disabled");
	}
	else {
		$("#disableButton").addClass("disabled");
		$("#enableButton").removeClass("disabled");
	}
	$("#commitButton").removeClass("disabled");
	$("#encryptionInput").change();
}

$(function() {

	$("#encryptionInput").change(function() {
		if ($("#encryptionInput").val() == "none") {
			$("#passphraseInput").attr("disabled", "disabled");
		}
		else {
			$("#passphraseInput").removeAttr("disabled");
		}
	});

	retrieveWifiInformationHandler(wifiInfos);

	$("#enableButton").click(function(event) {
		event.preventDefault();
		$("#enableButton").addClass("disabled");
		$("#disableButton").addClass("disabled");
		$("#commitButton").addClass("disabled");
		$.post("wifi/actions/do_activate_wifi.php", {}, function(data) {
			retrieveWifiInformationHandler(data.wifiInfos);
		}, "json");
	});

	$("#disableButton").click(function(event) {
		event.preventDefault();
		$("#enableButton").addClass("disabled");
		$("#disableButton").addClass("disabled");
		$("#commitButton").addClass("disabled");
		$.post("wifi/actions/do_deactivate_wifi.php", {}, function(data) {
			retrieveWifiInformationHandler(data.wifiInfos);
		}, "json");
	});

	$("#commitButton").click(function(event) {
		event.preventDefault();

		var passwordInput = $("#wifiForm #passphraseInput:enabled");
		if (passwordInput.length > 0 && (passwordInput.val().length < 8 || passwordInput.val().length > 63)) {
			$("#badWifiPasswordAlert").parent(".container").removeClass("hidden");
			$("#badWifiPasswordAlert").removeClass("hidden").show().delay(2000).fadeOut(1000, function() {
				$(this).parent(".container").addClass("hidden");
			});
			return;
		}

		$("#enableButton").addClass("disabled");
		$("#disableButton").addClass("disabled");
		$("#commitButton").addClass("disabled");
		$.post("wifi/actions/do_commit_wifi.php", $("form").serialize(), function(data) {
			retrieveWifiInformationHandler(data.wifiInfos);
		}, "json");
	});
});