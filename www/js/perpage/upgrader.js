function updateVersionPanel(panel, version) {
	panel.find(".versionLabel").text(version.version);
	panel.find(".descriptionLabel").text(version.description);
}

function testUpgradability(currentVersion, lastVersion) {
	if (currentVersion.version < lastVersion.version) {
		$("#upgradeDiv").removeClass("hidden");
	}
}

function setErrorUpgrade() {
	$("#upgradingDiv .progress-bar").attr("aria-valuenow", "100");
	$("#upgradingDiv .progress-bar").css({"width": "100%"});
	$("#upgradingDiv .progress-bar").addClass("progress-bar-danger");
}

function startUpgrade() {
	$.post("do_startUpgrade.php", {}, function(data) {
		$("#upgradingDiv .progress-bar").attr("aria-valuenow", "40");
		$("#upgradingDiv .progress-bar").css({"width": "40%"});

		verifyUpgrade();
	}, "json");
}

function verifyUpgrade() {
	$.post("do_verifyUpgrade.php", {}, function(data) {
		if (data.ok && data.version.version == newVersion.version) {
			$("#upgradingDiv .progress-bar").attr("aria-valuenow", "60");
			$("#upgradingDiv .progress-bar").css({"width": "60%"});

			extractUpgrade();
		}
		else {
			setErrorUpgrade();
		}
	}, "json");
}

function extractUpgrade() {
	$.post("do_extractUpgrade.php", {}, function(data) {
		if (data.ok) {
			$("#upgradingDiv .progress-bar").attr("aria-valuenow", "80");
			$("#upgradingDiv .progress-bar").css({"width": "80%"});

			spreadUpgrade();
		}
		else {
			setErrorUpgrade();
		}
	}, "json");
}

function spreadUpgrade() {
	$.post("do_spreadUpgrade.php", {}, function(data) {
		if (data.ok) {
			$("#upgradingDiv .progress-bar").attr("aria-valuenow", "100");
			$("#upgradingDiv .progress-bar").css({"width": "100%"});

			setTimeout(finishUpgrade, 2000);
		}
		else {
			setErrorUpgrade();
		}
	}, "json");
}

function finishUpgrade() {
	$("#upgradingDiv").addClass("hidden");
	$("#rebootDiv").removeClass("hidden");
}

$(function() {
	updateVersionPanel($("#currentVersionPanel"), version);

	$.post(versionUrl + "version.json", {}, function(data) {
		newVersion = data;
		updateVersionPanel($("#lastVersionPanel"), newVersion);
		testUpgradability(version, newVersion);
	}, "json");

	$("#upgradeDiv button").click(function() {
		$("#upgradingDiv").removeClass("hidden");
		$("#upgradeDiv").addClass("hidden");
		startUpgrade();
	});

	$("#rebootDiv button").click(function() {
		window.location.reload(true);
	});
});