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
			<h3 class="panel-title"><?php echo lang("wifi_panel_label"); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="wifiForm">
				<fieldset>

					<div class="form-group">
						<label class="col-md-4 control-label" for="ssidInput"><?php echo lang("wifi_wifi_label"); ?></label>
						<div class="col-md-6">
							<input id="ssidInput" name="ssidInput" type="text" placeholder="<?php echo lang("wifi_wifi_placeholder"); ?>" class="form-control input-md" required="">
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
						<label class="col-md-4 control-label" for="passphraseInput"><?php echo lang("wifi_encryption_label"); ?></label>
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
							<input id="passphraseInput" name="passphraseInput" type="text" placeholder="<?php echo lang("wifi_encryption_passphrase_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="txpowerInput"><?php echo lang("wifi_power_label"); ?></label>
						<div class="col-md-3">
							<select id="txpowerInput" name="txpowerInput" class="form-control">

								<option id="txpower-0" value="0">0 dBm (1 mW)</option>
								<option id="txpower-4" value="4">4 dBm (2 mW)</option>
								<option id="txpower-5" value="5">5 dBm (3 mW)</option>
								<option id="txpower-7" value="7">7 dBm (5 mW)</option>
								<option id="txpower-8" value="8">8 dBm (6 mW)</option>
								<option id="txpower-9" value="9">9 dBm (7 mW)</option>
								<option id="txpower-10" value="10">10 dBm (10 mW)</option>
								<option id="txpower-11" value="11">11 dBm (12 mW)</option>
								<option id="txpower-12" value="12">12 dBm (15 mW)</option>
								<option id="txpower-13" value="13">13 dBm (19 mW)</option>
								<option id="txpower-14" value="14">14 dBm (25 mW)</option>
								<option id="txpower-15" value="15">15 dBm (31 mW)</option>
								<option id="txpower-16" value="16">16 dBm (39 mW)</option>
								<option id="txpower-17" value="17">17 dBm (50 mW)</option>
								<option id="txpower-18" value="18">18 dBm (63 mW)</option>
								<option id="txpower-19" value="19">19 dBm (79 mW)</option>
								<option id="txpower-20" value="20" selected="selected">20 dBm (100 mW)</option>
<!--
								<option id="txpower-21" value="21">21 dBm (125 mW)</option>
								<option id="txpower-22" value="22">22 dBm (158 mW)</option>
								<option id="txpower-23" value="23">23 dBm (199 mW)</option>
								<option id="txpower-24" value="24">24 dBm (251 mW)</option>
								<option id="txpower-25" value="25">25 dBm (316 mW)</option>
								<option id="txpower-26" value="26">26 dBm (398 mW)</option>
								<option id="txpower-27" value="27">27 dBm (501 mW)</option>
								<option id="txpower-28" value="28">28 dBm (630 mW)</option>
								<option id="txpower-29" value="29">29 dBm (794 mW)</option>
								<option id="txpower-30" value="30">30 dBm (1000 mW)</option>
 -->
							</select>
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

<script type="text/javascript">
var wifiInfos = <?php echo json_encode($info); ?>
</script>
<?php include("footer.php");?>
</body>
</html>