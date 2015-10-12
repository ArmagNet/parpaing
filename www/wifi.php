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
require_once 'engine/bo/WifiBo.'.$config["parpaing"]["dialect"].'.php';

$wifiBo = WifiBo::newInstance($config);
$info = $wifiBo->getInfo();

?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_wifi"); ?></li>
	</ol>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("wifi_panel_label"); ?>Paramètres WIFI</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="wifiForm">
				<fieldset>

					<div class="form-group">
						<label class="col-md-4 control-label" for="ssidInput"><?php echo lang("wifi_wifi_label"); ?></label>
						<div class="col-md-6">
							<input id="ssidInput" name="ssidInput" type="text" placeholder="<?php echo lang("wifi_wifi_placeholder"); ?>SSID" class="form-control input-md" required="">
						</div>
						<div class="col-md-2">
							<select id="channelInput" name="channelInput" class="form-control">
								<option value="auto">auto</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="passphraseInput"><?php echo lang("wifi_encryption_label"); ?>Chiffrement</label>
						<div class="col-md-2">
							<select id="encryptionInput" name="encryptionInput" class="form-control">
								<option value="none"><?php echo lang("wifi_encryption_select_none"); ?></option>
								<!--
								<option value="wep">WEP</option>
								 -->
								<option value="psk">WPA-PSK</option>
								<option value="psk2">WPA2-PSK</option>
								<option value="psk-mixed">WPA/WPA2 PSK</option>
							</select>
						</div>
						<div class="col-md-6">
							<input id="passphraseInput" name="passphraseInput" type="text" placeholder="<?php echo lang("wifi_encryption_passphrase_placeholder"); ?>la phrase de passe du wifi" class="form-control input-md" required="">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="commitButton"></label>
						<div class="col-md-8">
							<button id="enableButton" type="submit" name="enableButton" class="btn btn-success disabled"><?php echo lang("common_activate"); ?></button>
							<button id="disableButton" type="submit" name="disableButton" class="btn btn-danger disabled"><?php echo lang("common_deactivate"); ?></button>
							<button id="commitButton" type="submit" name="commitButton" class="btn btn-primary disabled"><?php echo lang("common_update"); ?></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<div class="container hidden padding-left-0" style="padding-right: 30px;">
		<?php echo addAlertDialog("badWifiPasswordAlert", lang("wifi_badWifiPasswordAlert"), "danger"); ?>
	</div>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<script>

var wifiInfos = <?php echo json_encode($info); ?>

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
</script>
</body>
</html>