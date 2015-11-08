function addTorrentHandler() {
	var url = $("#torrent-input").val();
	$("#add-torrent-button").attr("disabled", "disabled");

	$.post("bittorrent/actions/do_add_torrent.php", {torrent : url}, function(data) {
		$("#torrent-input").val("");
		$("#add-torrent-button").removeAttr("disabled");
	}, "json");
}

function setActiveStatus(isActive) {
	$("#bittorrent-active-button").bootstrapSwitch("disabled", true);

	var action = "bittorrent/actions/" + (isActive ? "do_enable_bittorrent.php" : "do_disable_bittorrent.php");

	$.get(action, {}, function(data) {
		$("#bittorrent-active-button").bootstrapSwitch("disabled", false);
		updateActiveStatus(data.isActive);
	}, "json");
}

function updateActiveStatus(isActive) {
	if (!$("#bittorrent-active-button").bootstrapSwitch("disabled")) {
		$("#bittorrent-active-button").bootstrapSwitch("state", isActive);
		$("#torrent")[isActive ? "show" : "hide"]();
	}
}

function updateTorrentHandler(torrents) {
	$("#bittorrent .list-group a").hide();

	for(var index = 0; index < torrents.length; ++index) {
		var torrent = torrents[index];

		var item = $("#bittorrent .list-group *[id='" + torrent.name + "']");
		if (item.length == 0) {
			item = $("*[aria-template-id=template-torrent]").template(
					"use", {
						data: {}
				});

			item.attr("id", torrent.name);

			$("#bittorrent .list-group").append(item);
		}

		item.find(".list-group-item-heading").text(torrent.name);
		if (torrent.done == "100%") {
			item.find(".progress-bar").addClass("progress-bar-success").removeClass("progress-bar-info").removeClass("progress-bar-disabled");
		}
		else {
			item.find(".progress-bar").addClass("progress-bar-info").removeClass("progress-bar-success").removeClass("progress-bar-disabled");
		}
		item.find(".progress-bar").css({width: torrent.done});
		item.find(".progress-bar span").text(torrent.done);

		item.find(".download-rate").text(humanFileSize(torrent.down, false) + "/s");
		item.find(".upload-rate").text(humanFileSize(torrent.up, false) + "/s");

		item.find(".status").text(torrent.status);
		if (torrent.status == "Stopped") {
			item.find(".progress-bar").removeClass("progress-bar-info").removeClass("progress-bar-success").addClass("progress-bar-disabled");
			item.find(".glyphicon-pause").addClass("disabled");
			item.find(".glyphicon-play").removeClass("disabled");
		}
		else {
			item.find(".glyphicon-pause").removeClass("disabled");
			item.find(".glyphicon-play").addClass("disabled");
		}

		item.find(".downloaded").text(torrent.have + (torrent.have_unit ? torrent.have_unit : ""));
		item.find(".eta").text(torrent.eta + (torrent.eta_unit ? torrent.eta_unit : ""));

		item.data("torrentId", torrent.id);

		item.show();
	}
}

function updateTorrents() {
	$.get("bittorrent/actions/do_get_torrents.php", {}, function(data) {
		updateActiveStatus(data.isActive);
		updateTorrentHandler(data.torrents);
	}, "json");
}

function pauseTorrent(torrents) {
	actionTorrent("pause", torrents);
}

function resumeTorrent(torrents) {
	actionTorrent("resume", torrents);
}

function removeTorrent(torrents) {
	bootbox.setLocale("fr");
	var question = $("*[aria-template-id=template-remove-question]").template("use", {
				data: {}
		}).text();
	bootbox.confirm(question, function(result) {
		if (result) {
			actionTorrent("remove", torrents);
		}
	});
	var zIndex = $(".modal-backdrop").css("z-index");
	$(".modal-dialog").css({"z-index": zIndex});
}

function trashTorrent(torrents) {
	bootbox.setLocale("fr");
	var question = $("*[aria-template-id=template-trash-question]").template("use", {
		data: {}
	}).text();
	bootbox.confirm(question, function(result) {
		if (result) {
			actionTorrent("trash", torrents);
		}
	});
	var zIndex = $(".modal-backdrop").css("z-index");
	$(".modal-dialog").css({"z-index": zIndex});
}

function actionTorrent(action, torrents) {
	$.post("bittorrent/actions/do_action_torrents.php", {"torrents[]" : torrents, action: action}, function(data) {
	}, "json");
}

$(function() {
	$('input[type="checkbox"], input[type="radio"]').not("[data-switch-no-init]").bootstrapSwitch();

	$("#bittorrent-active-button").on('switchChange.bootstrapSwitch', function(event, state) {
		event.stopPropagation();
		event.preventDefault();

		setActiveStatus(state);
	});

	$("#add-torrent-button").click(addTorrentHandler);

	var bittorrentTimer = $.timer(updateTorrents);
	bittorrentTimer.set({ time : 2000, autostart : true });

	updateTorrents();

	$("#bittorrent .list-group").on("click", ".torrent-item .glyphicon-pause", function(event) {
		event.stopPropagation();
		event.preventDefault();

		if (!$(this).attr("disabled")) {
			var item = $(this).parents(".torrent-item");
			var torrentId = item.data("torrentId");

			pauseTorrent([torrentId]);
		}
	});

	$("#bittorrent .list-group").on("click", ".torrent-item .glyphicon-play", function(event) {
		event.stopPropagation();
		event.preventDefault();

		if (!$(this).attr("disabled")) {
			var item = $(this).parents(".torrent-item");
			var torrentId = item.data("torrentId");

			resumeTorrent([torrentId]);
		}
	});

	$("#bittorrent .list-group").on("click", ".torrent-item .glyphicon-remove", function(event) {
		event.stopPropagation();
		event.preventDefault();

		var item = $(this).parents(".torrent-item");
		var torrentId = item.data("torrentId");

		removeTorrent([torrentId]);
	});

	$("#bittorrent .list-group").on("click", ".torrent-item .glyphicon-trash", function(event) {
		event.stopPropagation();
		event.preventDefault();

		var item = $(this).parents(".torrent-item");
		var torrentId = item.data("torrentId");

		trashTorrent([torrentId]);
	});

	$("#bittorrent .list-group").on("click", ".torrent-item .glyphicon-eye-open", function(event) {
		event.stopPropagation();
		event.preventDefault();

		var item = $(this).parents(".torrent-item");

		window.location.assign("explorer.php?highlight=" + item.attr("id"));
	});
});