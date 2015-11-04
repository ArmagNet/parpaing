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
require_once 'engine/bo/BittorrentBo.'.$config["parpaing"]["dialect"].'.php';

$bittorrentBo = BittorrentBo::newInstance($config);

$isActive = $bittorrentBo->isActive();

?>
<div class="container theme-showcase" role="main">
	<div>
		<div class="pull-right breadcrumb">
			<input id="bittorrent-active-button" type="checkbox" <?php if ($isActive) { echo "checked"; } ?> data-size="mini">
		</div>
		<ol class="breadcrumb">
			<li><?php echo lang("breadcrumb_index"); ?></li>
			<li class="active"><?php echo lang("breadcrumb_bittorrent"); ?></li>
		</ol>
	</div>

	<div class="col-md-12" id="bittorrent">

		<div id="torrent" class="input-group" style="display: none;">
			<div class="input-group-btn">
				<span class="btn btn-default disabled" style="color: black !important;">
	 				<?php echo lang("bittorrent_url_label"); ?>
				</span>
			</div>
			<input type="text" id="torrent-input" class="form-control" placeholder="" />
			<span class="input-group-btn">
				<button class="btn btn-default" type="button" id="add-torrent-button"><span class="glyphicon glyphicon-plus"></span></button>
			</span>
		</div>

		<div class="list-group">
		</div>

	</div>
</div>

<div class="lastDiv"></div>

<templates>
	<template aria-template-id="template-remove-question"><?php echo lang("bittorrent_question_remove"); ?></template>
	<template aria-template-id="template-trash-question"><?php echo lang("bittorrent_question_trash"); ?></template>
	<a href="#" aria-template-id="template-torrent" class="template list-group-item torrent-item">
		<h4 class="list-group-item-heading"></h4>
		<div class="list-group-item-text">
			<div class="progress">
				<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
					style="width: 0%; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
					<span style="position: relative; left: 2px;">0%</span>
				</div>
			</div>
			<div>
				<small>
					<span class="glyphicon glyphicon-cog"></span>
					<span class="status"></span>
					<span class="glyphicon glyphicon-file"></span>
					<span class="downloaded"></span>
					<span class="glyphicon glyphicon-time"></span>
					<span class="eta"></span>
					<span class="glyphicon glyphicon-arrow-down"></span>
					<span class="download-rate"></span>
					<span class="glyphicon glyphicon-arrow-up"></span>
					<span class="upload-rate"></span>

					<span class="glyphicon glyphicon-play text-success"></span>
					<span class="glyphicon glyphicon-pause text-info"></span>
					<span class="glyphicon glyphicon-remove text-danger"></span>
					<span class="glyphicon glyphicon-trash text-danger"></span>
					<span class="glyphicon glyphicon-eye-open text-success"></span>

				</small>
			</div>
		</div>
	</a>
</templates>

<?php include("footer.php");?>
<script>

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
});
</script>
</body>
</html>