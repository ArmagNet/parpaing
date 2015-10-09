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
<li role="presentation" class=""><a href="#retrieve_armagnet_vpn" aria-controls="retrieve_armagnet_vpn" role="tab" data-toggle="tab"><?php echo lang("vpn_2_tab_legend");?></a></li>

<div role="tabpanel" class="tab-pane fade" id="retrieve_armagnet_vpn">
	</br>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("vpn_retrieveArmagnet_panel");?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="retrieveArmagnetVpnForm">
				<fieldset>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="loginInput"><?php echo lang("vpn_retrieveArmagnet_login"); ?></label>
						<div class="col-md-5">
							<input id="loginInput" name="loginInput" type="text" placeholder="<?php echo lang("vpn_retrieveArmagnet_login_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="passwordInput"><?php echo lang("vpn_retrieveArmagnet_password"); ?></label>
						<div class="col-md-5">
							<input id="passwordInput" name="passwordInput" type="password" placeholder="<?php echo lang("vpn_retrieveArmagnet_password_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Button (Double) -->
					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="retrieveArmagnetVpnButton" type="submit" name="retrieveArmagnetVpnButton" class="btn btn-primary"><?php echo lang("common_connect");?></button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse"><?php echo lang("common_reset");?></button>
						</div>
					</div>

				</fieldset>
			</form>

		</div>
	</div>

	<div class="panel panel-default hidden" id="configurationsPanel">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("vpn_retrieveArmagnetConfiguration_panel"); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="commitArmagnetVpnsForm">

				<ul class="list-group">
				</ul>

				<fieldset>

					<!-- Button (Double) -->
					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="commitArmagnetVpnsButton" type="submit" name="commitArmagnetVpnsButton" class="btn btn-success"><?php echo lang("common_add");?></button>
						</div>
					</div>

				</fieldset>
			</form>
		</div>
	</div>


	<templates>
		<li aria-template-id="template-armagnet-vpn-listitem" class="template list-group-item">
			<input type="checkbox" value="${configurationId}" name="configuration_ids[]">${label}
		</li>
	</templates>

	<script type="text/javascript">

	function retrieveArmagnetVpnsHandler(data) {
		$("#configurationsPanel .list-group").children().remove();

		for(var index = 0; index < data.configurations.length; index++) {
			var configuration = data.configurations[index];

			var configurationLi = $("*[aria-template-id=template-armagnet-vpn-listitem]").template(
					"use", {
						data: {	"label" : configuration.label,
								"configurationId" : configuration.id}
			});

			$("#configurationsPanel .list-group").append($(configurationLi));
		}

		$("#configurationsPanel").removeClass("hidden");
		$('html, body').animate({
	        scrollTop: $("#configurationsPanel").offset().top
	    }, 400);
	}

	function commitArmagnetVpnsHandler(data) {

		if (data.ok) {
			$("#configurationsPanel").addClass("hidden");

			updateAvailableConfigurations(data.configurations);

			$("#vpnTabs #vpns_tab a").tab("show");
		}
	}

	function retrieveArmagnetVpns() {
		var myForm = $("#retrieveArmagnetVpnForm");
		$.post("vpn/actions/do_retrieve_armagnet_vpn.php", myForm.serialize(), retrieveArmagnetVpnsHandler, "json");
	}

	function commitArmagnetVpns() {
		var myForm = $("#commitArmagnetVpnsForm");
		$.post("vpn/actions/do_commit_armagnet_vpns.php", myForm.serialize(), commitArmagnetVpnsHandler, "json");
	}

	$(function() {

		$("#retrieveArmagnetVpnButton").click(function(event) {
			event.preventDefault();
			event.stopPropagation();

			retrieveArmagnetVpns();
		});

		$("#commitArmagnetVpnsButton").click(function(event) {
			event.preventDefault();
			event.stopPropagation();

			commitArmagnetVpns();
		});

	});

	</script>

</div>