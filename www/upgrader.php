<?php /*
	Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of OpenTweetBar.

    OpenTweetBar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    OpenTweetBar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with OpenTweetBar.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("header.php");

?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_upgrader"); ?></li>
	</ol>

	<div class="col-md-6">
		<div id="currentVersionPanel" class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Version actuelle</h3>
			</div>
			<div class="panel-body">
				<div class="clearfix">
					<div class="col-md-3 text-right"><label>Version :</label></div>
					<div class="col-md-9"><label class="versionLabel"></label></div>
				</div class="clearfix">
				<div>
					<div class="col-md-3 text-right"><label>Description :</label></div>
					<div class="col-md-9"><label class="descriptionLabel"></label></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div id="lastVersionPanel" class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Version disponible</h3>
			</div>
			<div class="panel-body">
				<div class="clearfix">
					<div class="col-md-3 text-right"><label>Version :</label></div>
					<div class="col-md-9"><label class="versionLabel"></label></div>
				</div>
				<div class="clearfix">
					<div class="col-md-3 text-right"><label>Description :</label></div>
					<div class="col-md-9"><label class="descriptionLabel"></label></div>
				</div>
			</div>
		</div>
	</div>

	<div id="upgradeDiv" class="col-md-12 text-center hidden">
		<button class="btn btn-primary">Faire la mise à jour</button>
	</div>
	<div id="upgradingDiv" class="col-md-12 text-center hidden">
		<div class="progress">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
				<span class="sr-only">In progress</span>
			</div>
		</div>
	</div>
	<div id="rebootDiv" class="col-md-12 text-center hidden">
		<button class="btn btn-success">Relancer l'interface</button>
	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<script>
var version = <?php include("version.json"); ?>;
var newVersion = null;
var versionUrl = "<?php echo $config["parpaing"]["version_url"]; ?>";

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
//			spreadUpgrade();
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

</script>
</body>
</html>