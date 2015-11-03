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


	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<script>

function setActiveStatus() {
	var isActive = $("#bittorrent-active-button").bootstrapSwitch("state");

	// Call update;
}

function updateActiveStatus(isActive) {
	$("#bittorrent-active-button").bootstrapSwitch("state", isActive);
}

function updateTorrents() {
	$.get("bittorrent/actions/do_get_torrents.php", {}, function(data) {
		updateActiveStatus(data.isActive);
	}, "json");
}

$(function() {
	$('input[type="checkbox"], input[type="radio"]').not("[data-switch-no-init]").bootstrapSwitch();

	var bittorrentTimer = $.timer(updateTorrents);
	bittorrentTimer.set({ time : 10000, autostart : true });

	updateTorrents();
	// Add click on bittorrent-active-button
});
</script>
</body>
</html>