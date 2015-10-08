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
					<legend>ArmagNet</legend>

					<div class="form-group">
						<label class="col-md-4 control-label" for="isMember"></label>
						<div class="col-md-4">
							<label class="checkbox-inline" for="isMember"> <input type="checkbox" name="isMember" id="isMember" value="1">Je suis membre</label>
						</div>
					</div>

					<div class="form-group toBeMember">
						<label class="col-md-4 control-label" for="wishToBeMember"></label>
						<div class="col-md-8">
							<label class="checkbox-inline" for="wishToBeMember"> <input type="checkbox" name="wishToBeMember" id="wishToBeMember" value="1">Je souhaite devenir membre</label>
							<span class="help-block">Vous permettra de participer à la construction de l'association</span>
							<span class="help-block">Vous avez lu et acceptez les statuts et le règlement intérieur d'ArmagNet</span>
						</div>
					</div>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="loginInput">Identifiant</label>
						<div class="col-md-5">
							<input id="loginInput" name="loginInput" type="text" placeholder="votre login" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-4 control-label" for="passwordInput">Mot de passe</label>
						<div class="col-md-5">
							<input id="passwordInput" name="passwordInput" type="password" placeholder="votre mot de passe" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Text input-->
					<div class="form-group toBeMember">
						<label class="col-md-4 control-label" for="passwordInput">Confirmation</label>
						<div class="col-md-5">
							<input id="confirmInput" name="confirmInput" type="password" placeholder="confirmation de votre mot de passe" class="form-control input-md" required="">
						</div>
					</div>

				</fieldset>
				<fieldset class="toBeMember">
					<legend>Identité</legend>

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
					<legend>OpenVPN</legend>

					<!-- Button (Double) -->
					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="orderButton" type="submit" name="orderButton" class="btn btn-primary">Commander</button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse">Reset</button>
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

function setMemberStatus() {
	var statusFunction = ($("#isMember").prop("checked")) ? "hide" : "show";
	$(".toBeMember")[statusFunction]();
}

$(function() {

	$("#isMember").click(function() {
		setMemberStatus();
	});

	$("#orderButton").click(function(event) {
		event.preventDefault();
		event.stopPropagation();

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
			}
		}, "json");
	});

	setMemberStatus();
});
</script>

</div>