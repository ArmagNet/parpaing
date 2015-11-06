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
	<span aria-template-id="template-remove-question"><?php echo lang("bittorrent_question_remove"); ?></span>
	<span aria-template-id="template-trash-question"><?php echo lang("bittorrent_question_trash"); ?></span>
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
</body>
</html>