<li role="presentation" id="raw_vpn_tab" class=""><a href="#raw_vpn" aria-controls="raw_vpn" role="tab" data-toggle="tab"><?php echo lang("vpn_1_tab_legend");?></a>
</li>

<div role="tabpanel" class="tab-pane fade" id="raw_vpn">
	</br>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Ajouter une configuration OpenVPN</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="commitVpnForm">

				<input id="idInput" name="idInput" type="hidden">

				<fieldset>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-2 control-label" for="labelInput">Libellé</label>
						<div class="col-md-10">
							<input id="labelInput" name="labelInput" type="text" placeholder="nom de la configuration" class="form-control input-md" required="">
						</div>
					</div>

					<!-- File Button -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="keyButton">Key</label>
						<div class="col-md-4">
							<input id="keyButton" name="keyButton" class="input-file" type="file">
						</div>
						<label class="col-md-2 control-label" for="certButton">Cert</label>
						<div class="col-md-4">
							<input id="certButton" name="certButton" class="input-file" type="file">
						</div>
					</div>

					<!-- File Button -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="caButton">CA</label>
						<div class="col-md-4">
							<input id="caButton" name="caButton" class="input-file" type="file">
						</div>
					</div>

					<!-- File Button -->
					<!--
					<div class="form-group">
						<label class="col-md-2 control-label" for="openvpnButton">OpenVPN configuration</label>
						<div class="col-md-4">
							<input id="openvpnButton" name="openvpnButton" class="input-file" type="file">
						</div>
					</div>
					-->

					<!-- Select -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="devInput">dev</label>
						<div class="col-md-4">
							<select id="devInput" name="devInput" class="form-control">
								<option value="tun">tun</option>
								<option value="tap">tap</option>
							</select>
						</div>
						<label class="col-md-2 control-label" for="protoInput">proto</label>
						<div class="col-md-4">
							<select id="protoInput" name="protoInput" class="form-control">
								<option value="udp">udp</option>
								<option value="tcp">tcp</option>
							</select>
						</div>
					</div>

					<!-- Select -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="cipherInput">cipher</label>
						<div class="col-md-4">
							<select id="cipherInput" name="cipherInput" class="form-control">
								<option value="BF-CBC">BF-CBC</option>
								<option value="DES-CBC">DES-CBC</option>
								<option value="RC2-CBC">RC2-CBC</option>
								<option value="DES-EDE-CBC">DES-EDE-CBC</option>
								<option value="DESX-CBC">DESX-CBC</option>
								<option value="RC2-40-CBC">RC2-40-CBC</option>
								<option value="CAST5-CBC">CAST5-CBC</option>
								<option value="RC2-64-CBC">RC2-64-CBC</option>
								<option value="AES-128-CBC">AES-128-CBC</option>
								<option value="AES-192-CBC">AES-192-CBC</option>
								<option value="AES-256-CBC">AES-256-CBC</option>
							</select>
						</div>
						<label class="col-md-2 control-label" for="compLzoInput">comp-lzo</label>
						<div class="col-md-4">
							<select id="compLzoInput" name="compLzoInput" class="form-control">
								<option value="adaptive">adaptive</option>
								<option value="no">no</option>
								<option value="yes">yes</option>
							</select>
						</div>
					</div>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-2 control-label" for="remoteIpInput">remote ip</label>
						<div class="col-md-4">
							<input id="remoteIpInput" name="remoteIpInput" type="text" placeholder="ip du server vpn" class="form-control input-md" required="">
						</div>
						<label class="col-md-2 control-label" for="remoteIpInput">remote port</label>
						<div class="col-md-4">
							<input id="remotePortInput" name="remotePortInput" type="text" placeholder="port du server vpn" value="1194" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Text input-->
					<div class="form-group">
						<label class="col-md-2 control-label" for="remoteCertTlsInput">remote_cert_tls</label>
						<div class="col-md-4">
							<input id="remoteCertTlsInput" name="remoteCertTlsInput" type="text" placeholder="nom du certificat" value="server" class="form-control input-md" required="">
						</div>
					</div>

					<!-- Button (Double) -->
					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="commitVpnButton" type="submit" name="commitVpnButton" class="btn btn-primary">Ajouter</button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse">Reset</button>
						</div>
					</div>

				</fieldset>
			</form>

		</div>
	</div>

	<script type="text/javascript">


	function commitVpnHandler(data) {

		if (data.ok) {
			updateAvailableConfigurations(data.configurations);

			$("#vpnTabs #vpns_tab a").tab("show");
		}
	}

	function commitVpn() {
		var myForm = $("#commitVpnForm");
		var formData = new FormData(myForm[0]);

		var progressHandlingFunction = function (e) {
			if (e.lengthComputable) {
//	        	$('progress').attr({value:e.loaded, max:e.total});
//	        	console.log(e.loaded / e.total);
			}
		};

		$.ajax({
			url: "vpn/actions/do_commit_vpn.php",  //Server script to process data
			type: 'POST',
			xhr: function () {  // Custom XMLHttpRequest
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) { // Check if upload property exists
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
				}
				return myXhr;
			},
			//Ajax events
			success: function (data) {
				data = JSON.parse(data);
				commitVpnHandler(data);
			},
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		});


//		$.post("vpn/actions/do_commit_vpn.php", myForm.serialize(), commitVpnHandler, "json");
	}

	$(function() {

		$("#commitVpnButton").click(function(event) {
			event.preventDefault();
			event.stopPropagation();

			commitVpn();
		});

		$("#raw_vpn_tab a").click(function(event) {
			$("#raw_vpn #resetButton").click();
		});

	});

	</script>

</div>
