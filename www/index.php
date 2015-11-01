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

$df = disk_free_space("/");
$ds = disk_total_space("/");

function humanFileSize($bytes, $si, $decimals = 0, $scale = 1) {
	$thresh = $si ? 1000: 1024;
	if(abs($bytes) < $thresh * $scale) {
		return number_format($bytes, $decimals) + ' B';
	}

	$units = $si ?
	array('kB','MB','GB','TB','PB','EB','ZB','YB')
	: array('KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB');
	$u = -1;
	do {
		$bytes /= $thresh;
		$u++;
	}
	while(abs($bytes) >= ($thresh * $scale) && $u < count($units) - 1);

	return number_format($bytes, $decimals, ".", " ") . ' ' . $units[$u];
}

?>
<?php
/*
MemTotal: 3986168 kB
MemFree: 118788 kB
SwapTotal: 5839112 kB
SwapFree: 2960728 kB
*/

$totalMemory = 0;
$freeMemory = 0;
$totalSwap = 0;
$freeSwap = 0;

$fh = fopen('/proc/meminfo','r');
while ($line = fgets($fh)) {

//	echo "$line <br>\n";

	$pieces = array();

	if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
		$totalMemory = $pieces[1] * 1024;
	}
	else if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
		$freeMemory = $pieces[1] * 1024;
	}
	else if (preg_match('/^SwapTotal:\s+(\d+)\skB$/', $line, $pieces)) {
		$totalSwap = $pieces[1] * 1024;
	}
	else if (preg_match('/^SwapFree:\s+(\d+)\skB$/', $line, $pieces)) {
		$freeSwap = $pieces[1] * 1024;
	}
}
fclose($fh);

?>

	<?php if ($isConnected) {?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li class="active"><?php echo lang("breadcrumb_index"); ?></li>
	</ol>
	<div class="well well-sm">
		<p><?php echo lang("index_guide"); ?></p>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("index_status_panel"); ?></h3>
		</div>
		<div class="panel-body">
			<fieldset>
				<legend><?php echo lang("index_memory_legend"); ?></legend>

				<div class="col-md-12">
					<div class="col-md-5"><label class="col-md-7"><?php echo lang("index_memory_total_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($totalMemory, false, 0, 10); ?></span></div>
					<div class="col-md-5"><label class="col-md-7"><?php echo lang("index_memory_free_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($freeMemory, false, 0, 10); ?></span></div>
					<div class="col-md-2">
						<?php
							$availableMemory = $freeMemory / $totalMemory;
							if ($availableMemory > .2) {
								$classBarMemory = "progress-bar-success";
							}
							else if ($availableMemory > .1) {
								$classBarMemory = "progress-bar-warning";
							}
							else {
								$classBarMemory = "progress-bar-danger";
							}
						?>
						<div class="progress">
							<div class="progress-bar <?php echo $classBarMemory; ?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo number_format($availableMemory * 100, 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo number_format($availableMemory * 100, 1) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="col-md-5"><label class="col-md-7"><?php echo lang("index_swap_total_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($totalSwap, false, 0, 10); ?></span></div>
					<div class="col-md-5"><label class="col-md-7"><?php echo lang("index_swap_free_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($freeSwap, false, 0, 10); ?></span></div>
					<div class="col-md-2">
						<div class="progress">
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo number_format($freeSwap / $totalSwap * 100, 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo number_format($freeSwap / $totalSwap * 100, 1) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>

				<legend><?php echo lang("index_disk_legend"); ?></legend>

				<div class="col-md-12">
					<div class="col-md-5"><label class="col-md-7"><?php echo lang("index_disk_total_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($ds, false, 2); ?></span></div>
					<div class="col-md-5"><label class="col-md-7"><?php echo lang("index_disk_free_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($df, false, 2); ?></span></div>
					<div class="col-md-2">
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo number_format($df / $ds * 100, 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo number_format($df / $ds * 100, 1) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>

			</fieldset>
		</div>
	</div>

</div>
	<?php } else {?>

<div class="container theme-showcase" role="main">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("index_authentication_panel"); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="loginForm">
				<fieldset>
					<div class="form-group">
						<label class="col-md-2 control-label" for="passwordInput"><?php echo lang("index_authentication_password"); ?></label>
						<div class="col-md-10">
							<input id="passwordInput" name="passwordInput" type="password" placeholder="<?php echo lang("index_authentication_password_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>
					<div class="form-group hidden renew-password">
						<label class="col-md-2 control-label" for="newPasswordInput"><?php echo lang("index_authentication_newpassword"); ?></label>
						<div class="col-md-10">
							<input id="newPasswordInput" name="newPasswordInput" type="password" placeholder="<?php echo lang("index_authentication_newpassword_placeholder"); ?>" class="form-control input-md">
						</div>
					</div>
					<div class="form-group hidden renew-password">
						<label class="col-md-2 control-label" for="confirmNewPasswordInput"><?php echo lang("index_authentication_confirm"); ?></label>
						<div class="col-md-10">
							<input id="confirmNewPasswordInput" name="confirmNewPasswordInput" type="password" placeholder="<?php echo lang("index_authentication_confirm_placeholder"); ?>" class="form-control input-md">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="loginButton" type="submit" name="loginButton" class="btn btn-primary"><?php echo lang("common_authenticate"); ?></button>
							<button id="renewButton" type="button" name="renewButton" class="btn btn-default"><?php echo lang("common_change"); ?></button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse"><?php echo lang("common_reset"); ?></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<div class="container hidden padding-left-0" style="padding-right: 30px;">
		<?php echo addAlertDialog("defaultPasswordAlert", lang("defaultPasswordAlert"), "warning"); ?>
		<?php echo addAlertDialog("notSameNewPasswordAlert", lang("notSameNewPasswordAlert"), "warning"); ?>
		<?php echo addAlertDialog("badPasswordAlert", lang("badPasswordAlert"), "danger"); ?>
	</div>

</div>

	<?php }?>

<script type="text/javascript">
var userLanguage = '<?php echo SessionUtils::getLanguage($_SESSION); ?>';
</script>
<?php include("footer.php");?>
</body>
</html>