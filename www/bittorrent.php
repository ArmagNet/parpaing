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

		<div class="list-group">
		</div>

	</div>
</div>

<div class="lastDiv"></div>

<templates>
	<a href="#" aria-template-id="template-torrent" class="template list-group-item">
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
					<span class="status"></span>
					<span class="downloaded"></span>
					<span class="eta"></span>
					<span class="glyphicon glyphicon-arrow-down"></span>
					<span class="download-rate"></span>
					<span class="glyphicon glyphicon-arrow-up"></span>
					<span class="upload-rate"></span>
				</small>
			</div>
		</div>
	</a>
</templates>

<?php include("footer.php");?>
<script>

function setActiveStatus(isActive) {
	//	var isActive = $("#bittorrent-active-button").bootstrapSwitch("state");

	// Call update;

	var action = "bittorrent/actions/" + (isActive ? "do_enable_bittorrent.php" : "do_disable_bittorrent.php");

	$.get(action, {}, function(data) {
		updateActiveStatus(data.isActive);
	}, "json");
}

function updateActiveStatus(isActive) {
	$("#bittorrent-active-button").bootstrapSwitch("state", isActive);
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
			item.find(".progress-bar").addClass("progress-bar-success").removeClass("progress-bar-info");
		}
		else {
			item.find(".progress-bar").addClass("progress-bar-info").removeClass("progress-bar-success");
		}
		item.find(".progress-bar").css({width: torrent.done});
		item.find(".progress-bar span").text(torrent.done);

		item.find(".download-rate").text(humanFileSize(torrent.down, false) + "/s");
		item.find(".upload-rate").text(humanFileSize(torrent.up, false) + "/s");

		item.find(".status").text(torrent.status);
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

$(function() {
	$('input[type="checkbox"], input[type="radio"]').not("[data-switch-no-init]").bootstrapSwitch();

	$("#bittorrent-active-button").on('switchChange.bootstrapSwitch', function(event, state) {
		  console.log(this); // DOM element
		  console.log(event); // jQuery event
		  console.log(state); // true | false
		  setActiveStatus(state);
	});

	var bittorrentTimer = $.timer(updateTorrents);
	bittorrentTimer.set({ time : 10000, autostart : true });

	updateTorrents();
	// Add click on bittorrent-active-button
});
</script>
</body>
</html>