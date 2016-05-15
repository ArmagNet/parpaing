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

function actionUpgrade() {
	$.post("do_actionUpgrade.php", {}, function(data) {

		if (!data.ok) {
			setErrorUpgrade();
			return;
		}

		var progressBarValue = 10 + 90 * data.number_of_done_actions / data.number_of_actions;
		progressBarValue = Math.ceil(progressBarValue);

		$("#upgradingDiv .progress-bar").attr("aria-valuenow", "" + progressBarValue);
		$("#upgradingDiv .progress-bar").css({"width": progressBarValue + "%"});

		if (data.number_of_actions < data.number_of_done_actions) {
			actionUpgrade();
		}
		else {
			finishUpgrade();
		}
	}, "json");
}

function startUpgrade() {
	$.post("do_startUpgrade.php", {}, function(data) {
		$("#upgradingDiv .progress-bar").attr("aria-valuenow", "10");
		$("#upgradingDiv .progress-bar").css({"width": "10%"});

		if (data.number_of_actions) {
			actionUpgrade();
		}
		else {
			setErrorUpgrade();
//			finishUpgrade();
		}
	}, "json");
}

function finishUpgrade() {
	$("#upgradingDiv").addClass("hidden");
	$("#rebootDiv").removeClass("hidden");
}

$(function() {
	updateVersionPanel($("#currentVersionPanel"), version);

	$.post(versionUrl, {}, function(data) {
		newVersion = data.versions[data.versions.length - 1];
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