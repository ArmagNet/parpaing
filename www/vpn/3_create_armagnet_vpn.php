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
<li role="presentation" class=""><a href="#create_armagnet_vpn" aria-controls="create_armagnet_vpn" role="tab" data-toggle="tab"><?php echo lang("vpn_3_tab_legend");?></a></li>

<div role="tabpanel" class="tab-pane fade" id="create_armagnet_vpn">

	</br>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Ouvrir un compte ArmagNet avec un accès VPN</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="createArmagnetVpnForm">
				<fieldset>
					<legend><?php echo lang("vpn_create_armagnet_account_label"); ?></legend>

					<div class="form-group">
						<label class="col-md-4 control-label" for="hasAccount"></label>
						<div class="col-md-4">
							<label class="checkbox-inline" for="hasAccount"> <input type="checkbox" name="hasAccount" id="hasAccount" value="1"><?php echo lang("vpn_create_armagnet_account_hasOne"); ?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="loginInput"><?php echo lang("vpn_create_armagnet_account_login"); ?></label>
						<div class="col-md-5">
							<input id="loginInput" name="loginInput" type="text" placeholder="<?php echo lang("vpn_create_armagnet_account_login_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="passwordInput"><?php echo lang("vpn_create_armagnet_account_password"); ?></label>
						<div class="col-md-5">
							<input id="passwordInput" name="passwordInput" type="password" placeholder="<?php echo lang("vpn_create_armagnet_account_password_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>

					<div class="form-group toBeMember">
						<label class="col-md-4 control-label" for="passwordInput"><?php echo lang("vpn_create_armagnet_account_confirmation"); ?></label>
						<div class="col-md-5">
							<input id="confirmInput" name="confirmInput" type="password" placeholder="<?php echo lang("vpn_create_armagnet_account_confirmation_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>

				</fieldset>
				<fieldset class="toBeMember">
					<legend><?php echo lang("vpn_create_armagnet_identity_label"); ?></legend>

					<!-- Identité input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="lastnameInput">Identité</label>
						<div class="col-md-4">
							<input id="lastnameInput" name="lastnameInput" type="text" placeholder="votre nom" class="form-control input-md" required="">
						</div>
						<div class="col-md-4">
							<input id="firstnameInput" name="firstnameInput" type="text" placeholder="votre prénom" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Email input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="emailInput">Adresse mail</label>
						<div class="col-md-4">
							<input id="emailInput" name="emailInput" type="text" placeholder="votre email" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Address input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="addressInput">Adresse</label>
						<div class="col-md-8">
							<input id="addressInput" name="addressInput" type="text" placeholder="votre adresse" class="form-control input-md" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="address2Input"></label>
						<div class="col-md-8">
							<input id="address2Input" name="address2Input" type="text" placeholder="... suite ..." class="form-control input-md" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="zipcodeInput"></label>
						<div class="col-md-3">
							<input id="zipcodeInput" name="zipcodeInput" type="text" placeholder="votre code postal" class="form-control input-md" required="">
						</div>
						<div class="col-md-5">
							<input id="cityInput" name="cityInput" type="text" placeholder="votre ville" class="form-control input-md" required="">
						</div>
					</div>

				</fieldset>
				<fieldset>
					<legend>Commande</legend>

					<div id="products" class="list-group">
						<button id="membership-button"
							data-price="24"
							type="button" style="width: 100%;" class="list-group-item text-left">
							Adhésion annuelle
							<span class="badge">24&euro;</span>

							<ul>
								<li><span class="small">Vous permettra de participer à la construction de l'association</span></li>
								<li><span class="small">Vous avez lu et acceptez les statuts et le règlement intérieur d'ArmagNet</span></li>
							</ul>

						</button>
						<button id="vpn-membership-button"
							data-price="12"
							type="button" style="width: 100%;" class="list-group-item text-left ">
							VPN pour un an (à partir de 12€, vous êtes libre de mettre plus)<span class="badge"><input id="vpn-member-price"
								class="text-right" style="width: 25px; color: black;"
								value="12">&euro;</span>
						</button>
						<button id="vpn-year-button"
							data-price="60"
							type="button" style="width: 100%;" class="list-group-item text-left ">
							VPN pour un an <span class="badge">60&euro;</span>
						</button>
						<button id="vpn-6months-button"
							data-price="33"
							type="button" style="width: 100%;" class="list-group-item text-left ">
							VPN pour 6 mois <span class="badge">33&euro;</span>
						</button>
					</div>

					<div class="form-group">
						<label class="col-md-6 control-label">Total :</label>
						<label class="col-md-6 control-label" style="text-align: left;"
							data-price="0" id="totalPrice">0€</label>
					</div>

					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="orderButton" type="submit" name="orderButton" class="btn btn-primary disabled"><?php echo lang("common_order"); ?></button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse"><?php echo lang("common_reset"); ?></button>
						</div>
					</div>

				</fieldset>
			</form>
		</div>
	</div>

	<div class="container hidden padding-left-0" style="padding-right: 30px;">
		<?php echo addAlertDialog("alreadyExistsAlert", lang("vpn_create_armagnet_alreadyExistsAlert"), "danger"); ?>
		<?php echo addAlertDialog("badCredentialsAlert", lang("vpn_create_armagnet_badCredentialsAlert"), "danger"); ?>
		<?php echo addAlertDialog("notSamePasswordsAlert", lang("vpn_create_armagnet_notSamePasswordsAlert"), "danger"); ?>
		<?php echo addAlertDialog("mailMandatoryAlert", lang("vpn_create_armagnet_mailMandatoryAlert"), "danger"); ?>
		<?php echo addAlertDialog("firstnameMandatoryAlert", lang("vpn_create_armagnet_firstnameMandatoryAlert"), "danger"); ?>
		<?php echo addAlertDialog("lastnameMandatoryAlert", lang("vpn_create_armagnet_lastnameMandatoryAlert"), "danger"); ?>
	</div>

<script>

function setAccountStatus() {
	var statusFunction = ($("#hasAccount").prop("checked")) ? "hide" : "show";
	$(".toBeMember")[statusFunction]();
}

function computeTotal() {
	var total = 0;
	$("button.list-group-item-success").each(function() {
		total -= -$(this).data("price");
	});

	$("#totalPrice").html(total + "&euro;");
	$("#totalPrice").data("price", total);

	testOrderButton();
}

function testOrderButton() {
	$("#orderButton").removeClass("disabled");

	if ($("#createArmagnetVpnForm #totalPrice").data("price") == 0) {
		$("#orderButton").addClass("disabled");
		return;
	}

	if (!$("#createArmagnetVpnForm #loginInput").val() || !$("#createArmagnetVpnForm #passwordInput").val()) {
		$("#orderButton").addClass("disabled");
		return;
	}

	if (!$("#hasAccount").prop("checked") &&
			(!$("#createArmagnetVpnForm #confirmInput").val() || ($("#createArmagnetVpnForm #confirmInput").val() == $("#createArmagnetVpnForm #passwordInput").val()))) {
		$("#orderButton").addClass("disabled");
		return;
	}
}

function showOrderForm(vpnHash) {
	var form = $("<form method='post' ></form>");
	form.attr("action", "<?php echo $config["armagnet"]["order_url"]; ?>");

	form.append("<input type='hidden' name='vpnId' value='" + vpnHash + "'>");

	if ($("#products #membership-button.list-group-item-success").length) {
		form.append("<input type='hidden' name='askMembership' value='1'>");
		form.append("<input type='hidden' name='vpnMemberPrice' value='" + $("#vpn-member-price").val() + "'>");
	}
	else if ($("#products #vpn-year-button.list-group-item-success").length) {
		form.append("<input type='hidden' name='vpnCode' value='vpn_year'>");
	}
	else if ($("#products #vpn-6months-button.list-group-item-success").length) {
		form.append("<input type='hidden' name='vpnCode' value='vpn_6months'>");
	}

	form.submit();
}

$(function() {

	$("#hasAccount").click(function() {
		setAccountStatus();
	});

	$("#orderButton").click(function(event) {
		event.preventDefault();
		event.stopPropagation();

		$("#orderButton").addClass("disabled");

		var myForm = $("#createArmagnetVpnForm");
		$.post("vpn/actions/do_create_armagnet_vpn.php", myForm.serialize(), function(data) {
			if (data.ko) {
				var cAlert = $("#create_armagnet_vpn #" + data.message + "Alert");
				cAlert.parent(".container").removeClass("hidden");
				cAlert.removeClass("hidden").show().delay(2000).fadeOut(1000, function() {
					$(this).parent(".container").addClass("hidden");
				});
				if (data.focus) {
					$("#" + data.focus).focus();
				}
				testOrderButton();
			}
			else {
				showOrderForm(data.vpnHash);
			}
		}, "json");
	});

	$("#createArmagnetVpnForm input").change(function() {
		testOrderButton();
	});

	$("#vpn-member-price").click(function(event) {
//		event.stopPropagation();
	});

	$("#vpn-member-price").change(function(event) {
		var memberPrice = $(this).val();

		if (memberPrice < 12) {
			memberPrice = 12;
			$(this).val(memberPrice);
		}

		$(this).parents("button").data("price", memberPrice);
		computeTotal();
	});

	$("#products #membership-button, #products #vpn-membership-button").click(function(event) {
		$("#products button").removeClass("list-group-item-success");
		$("#products #membership-button, #products #vpn-membership-button").addClass("list-group-item-success");
		computeTotal();
	});

	$("#products #vpn-year-button").click(function(event) {
		$("#products button").removeClass("list-group-item-success");
		$("#products #vpn-year-button").addClass("list-group-item-success");
		computeTotal();
	});

	$("#products #vpn-6months-button").click(function(event) {
		$("#products button").removeClass("list-group-item-success");
		$("#products #vpn-6months-button").addClass("list-group-item-success");
		computeTotal();
	});

	setAccountStatus();
});
</script>

</div>