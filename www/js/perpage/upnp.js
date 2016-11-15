function setActiveStatus(isActive) {
	$("#upnp-active-button").bootstrapSwitch("disabled", true);

	var action = "upnp/actions/" + (isActive ? "do_enable_upnp.php" : "do_disable_upnp.php");

	$.post(action, {}, function(data) {
		$("#upnp-active-button").bootstrapSwitch("disabled", false);
		updateActiveStatus(data.isActive);
	}, "json");
}

function setInterface(networkInterface) {
	$("#upnp-active-button").bootstrapSwitch("disabled", true);
	$("#upnp-interface-button").bootstrapSwitch("disabled", true);

	var action = "upnp/actions/do_change_interface.php";

	$.post(action, {"interface": networkInterface}, function(data) {
		$("#upnp-active-button").bootstrapSwitch("disabled", false);
		$("#upnp-interface-button").bootstrapSwitch("disabled", false);
		updateActiveStatus(data.isActive);
	}, "json");
}

function updateActiveStatus(isActive) {
	if (!$("#upnp-active-button").bootstrapSwitch("disabled")) {
		$("#upnp-active-button").bootstrapSwitch("state", isActive);
	}
}

$(function() {
	$('input[type="checkbox"], input[type="radio"]').not("[data-switch-no-init]").bootstrapSwitch();

	$("#upnp-active-button").on('switchChange.bootstrapSwitch', function(event, state) {
		event.stopPropagation();
		event.preventDefault();

		setActiveStatus(state);
	});

	$("#upnp-interface-button").on('switchChange.bootstrapSwitch', function(event, state) {
		event.stopPropagation();
		event.preventDefault();

		setInterface(state ? "eth0" : "br-lan");
	});
});