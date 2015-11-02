function humanFileSize(bytes, si, decimals, scale) {
    var thresh = si ? 1000 : 1024;
    if (!decimals && decimals != 0) decimals = 1;
    if (!scale) scale = 1;

    if(Math.abs(bytes) < thresh * scale) {
        return bytes.toFixed(decimals) + ' B';
    }
    var units = si
        ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
        : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
    var u = -1;
    do {
        bytes /= thresh;
        ++u;
    }
    while(Math.abs(bytes) >= (thresh * scale) && u < units.length - 1);
    return bytes.toFixed(decimals)+' '+units[u];
}

function updateInformations() {
	$.get("index.php", {}, function(data) {
		var html = $(data);
		var panel = html.find("#statusPanel");

		// Bourrinus modus
		var pagePanel = $("#statusPanel");
		if (pagePanel.length) {
			pagePanel.after(panel);
			pagePanel.remove();
		}

		doUpdateInformations(panel);
	}, "html");
}

function doUpdateInformations(panel) {
	if (panel.length == 0) {
		// hide badges
		$(".rate").hide();

		return;
	}

	$(".rate").show();

	var usage = panel.find("#cpu0").data("usage");
	$("#cpu-rate .badge span").text(usage);

	var freeDisk = panel.find("#disk .free").data("size");
	$("#disk-rate .badge span").text(humanFileSize(freeDisk, false, 2));

	var freeMemory = panel.find("#memory .free").data("size");
	$("#memory-rate .badge span").text(humanFileSize(freeMemory, false, 0, 10));

	var upload = panel.find("#interface-eth0 .upload, #interface-wlan0 .upload").eq(0).data("size");
	$("#upload-rate").text(humanFileSize(upload, false) + "/s");

	var download = panel.find("#interface-eth0 .download, #interface-wlan0 .download").eq(0).data("size");
	$("#download-rate").text(humanFileSize(download, false) + "/s");
}

$(function() {
	var cronedTimer = $.timer(updateInformations);
	cronedTimer.set({ time : 10000, autostart : true });

	if ($("#statusPanel").length) {
		doUpdateInformations($("#statusPanel"));
	}
	else {
		updateInformations();
	}

});