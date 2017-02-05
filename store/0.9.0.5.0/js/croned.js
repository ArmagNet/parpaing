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

function copyInformations(fromPanel, toPanel) {
	// Memory
	var fromMemoryPanel = fromPanel.find("#memory");
	var toMemoryPanel = toPanel.find("#memory");

	toMemoryPanel.find(".total span").text(humanFileSize(fromMemoryPanel.find(".total").data("size"), false, 0, 10));
	toMemoryPanel.find(".free span").text(humanFileSize(fromMemoryPanel.find(".free").data("size"), false, 0, 10));

	var progressBarClass = fromMemoryPanel.find(".progress").data("class");
	var progressSize = fromMemoryPanel.find(".progress").data("available") * 1.;
	toMemoryPanel.find(".progress-bar").removeClass("progress-bar-success").removeClass("progress-bar-warning").removeClass("progress-bar-danger").addClass(progressBarClass);
	toMemoryPanel.find(".progress-bar").css({width: Math.round(progressSize) + "%"});
	toMemoryPanel.find(".progress-bar span").text(progressSize.toFixed(1) + "%");

	// Swap
	var fromSwapPanel = fromPanel.find("#swap");
	var toSwapPanel = toPanel.find("#swap");

	toSwapPanel.find(".total span").text(humanFileSize(fromSwapPanel.find(".total").data("size"), false, 0, 10));
	toSwapPanel.find(".used span").text(humanFileSize(fromSwapPanel.find(".used").data("size"), false, 0, 10));

	var progressSize = fromSwapPanel.find(".progress").data("used") * 1.;
	toSwapPanel.find(".progress-bar").css({width: Math.round(progressSize) + "%"});
	toSwapPanel.find(".progress-bar span").text(progressSize.toFixed(1) + "%");

	// Disk
	var fromDiskPanel = fromPanel.find("#disk");
	var toDiskPanel = toPanel.find("#disk");

	toDiskPanel.find(".total span").text(humanFileSize(fromDiskPanel.find(".total").data("size"), false, 2));
	toDiskPanel.find(".free span").text(humanFileSize(fromDiskPanel.find(".free").data("size"), false, 2));

	var progressSize = fromDiskPanel.find(".progress").data("available") * 1.;
	toDiskPanel.find(".progress-bar").css({width: Math.round(progressSize) + "%"});
	toDiskPanel.find(".progress-bar span").text(progressSize.toFixed(1) + "%");

	// Load
	var fromLoadSpan = fromPanel.find(".load");
	var toLoadSpan = toPanel.find(".load");

	for(var index = 0; index < 3; ++index) {
		toLoadSpan.eq(index).text(fromLoadSpan.eq(index).text());
	}

	var numberOfCpus = $("#number-of-cpus").data("cpus");
	for(var index = 0; index < numberOfCpus + 1; ++index) {
		var fromCpuPanel = fromPanel.find("#cpu" + index);
		var toCpuPanel = toPanel.find("#cpu" + index);

		var progressBarClass = fromCpuPanel.find(".progress").data("class");
		var progressSize = fromCpuPanel.find(".progress").data("usage") * 1.;
		toCpuPanel.find(".progress-bar").removeClass("progress-bar-success").removeClass("progress-bar-warning").removeClass("progress-bar-danger").addClass(progressBarClass);
		toCpuPanel.find(".progress-bar").css({width: Math.round(progressSize) + "%"});
		toCpuPanel.find(".progress-bar span").text(progressSize.toFixed(1) + "%");
	}

	// Load
	var fromLoadSpan = fromPanel.find(".interface");

	fromLoadSpan.each(function() {
		var id = $(this).attr("id");
		toPanel.find("#" + id + " .download").text($(this).find(".download").text());
		toPanel.find("#" + id + " .upload").text($(this).find(".upload").text());
	});
}

function updateInformations() {
	$.get("statistics.php", {}, function(data) {
		var html = $(data);
		var panel = html.find("#statusPanel");

		var pagePanel = $("#statusPanel");
		if (pagePanel.length) {
			copyInformations(panel, pagePanel);
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

	var usage = panel.find("#cpu0 .progress").data("usage");
	$("#cpu-rate .badge span").text(usage + "%");

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