<li role="presentation" class=""><a href="#retrieve_armagnet_vpn" aria-controls="retrieve_armagnet_vpn" role="tab" data-toggle="tab"><?php echo lang("vpn_2_tab_legend");?></a></li>

<div role="tabpanel" class="tab-pane fade" id="retrieve_armagnet_vpn">
	</br>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Récuperation des configurations OpenVPN de votre compte ArmagNet</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="retrieveArmagnetVpnForm">
				<fieldset>

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

					<!-- Button (Double) -->
					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="retrieveArmagnetVpnButton" type="submit" name="retrieveArmagnetVpnButton" class="btn btn-primary">Connecter</button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse">Reset</button>
						</div>
					</div>

				</fieldset>
			</form>

		</div>
	</div>

	<div class="panel panel-default hidden" id="configurationsPanel">
		<div class="panel-heading">
			<h3 class="panel-title">Configurations à ajouter</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="commitArmagnetVpnsForm">

				<ul class="list-group">
				</ul>

				<fieldset>

					<!-- Button (Double) -->
					<div class="form-group">
						<label class="col-md-4 control-label" for="commitArmagnetVpnsButton"></label>
						<div class="col-md-8">
							<button id="commitArmagnetVpnsButton" type="submit" name="commitArmagnetVpnsButton" class="btn btn-success">Ajouter</button>
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