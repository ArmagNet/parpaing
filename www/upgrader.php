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

?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_upgrader"); ?></li>
	</ol>

	<div class="col-md-6">
		<div id="currentVersionPanel" class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang("upgrader_actualVersion"); ?></h3>
			</div>
			<div class="panel-body">
				<div class="clearfix">
					<div class="col-md-3 text-right"><label><?php echo lang("upgrader_version_version"); ?> :</label></div>
					<div class="col-md-9"><label class="versionLabel"></label></div>
				</div class="clearfix">
				<div>
					<div class="col-md-3 text-right"><label><?php echo lang("upgrader_version_description"); ?> :</label></div>
					<div class="col-md-9"><label class="descriptionLabel"></label></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div id="lastVersionPanel" class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang("upgrader_newVersion"); ?></h3>
			</div>
			<div class="panel-body">
				<div class="clearfix">
					<div class="col-md-3 text-right"><label><?php echo lang("upgrader_version_version"); ?> :</label></div>
					<div class="col-md-9"><label class="versionLabel"></label></div>
				</div class="clearfix">
				<div>
					<div class="col-md-3 text-right"><label><?php echo lang("upgrader_version_description"); ?> :</label></div>
					<div class="col-md-9"><label class="descriptionLabel"></label></div>
				</div>
			</div>
		</div>
	</div>

	<div id="upgradeDiv" class="col-md-12 text-center hidden">
		<button class="btn btn-primary"><?php echo lang("upgrader_startUpgrade_button"); ?></button>
	</div>
	<div id="upgradingDiv" class="col-md-12 text-center hidden">
		<div class="progress">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
				<span class="sr-only">In progress</span>
			</div>
		</div>
	</div>
	<div id="rebootDiv" class="col-md-12 text-center hidden">
		<button class="btn btn-success"><?php echo lang("upgrader_rebootInterface_button");?></button>
	</div>
</div>

<script type="text/javascript">
var version = <?php include("version.json"); ?>;
var newVersion = null;
var versionUrl = "<?php echo $config["parpaing"]["version_url"]; ?>";
</script>
<?php include("footer.php");?>
</body>
</html>