<?php /*
	Copyright 2014-2017 Cédric Levieux, Jérémy Collot, ArmagNet

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
require_once 'engine/bo/NetworkBo.'.$config["parpaing"]["dialect"].'.php';

$networkBo = NetworkBo::newInstance($config);

$ipInterfaces = $networkBo->getInterfaces();

function humanFileSize($bytes, $si, $decimals = 1, $scale = 1) {
	$thresh = $si ? 1000: 1024;
	if(abs($bytes) < $thresh * $scale) {
		return number_format($bytes, $decimals) . ' B';
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

// Disk

$df = disk_free_space("/");
$ds = disk_total_space("/");

// Memory

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

// CPU
if (false && function_exists("sys_getloadavg")) {
	$load = sys_getloadavg();
}
else {
	$load = substr(strrchr(shell_exec("uptime"), ":"), 1);

	$load = array_map("trim", explode(",", $load));

	if (count($load) == 6) {
		$newLoad = array();
		for($i = 0; $i < 3; $i++) {
			$newLoad[$i] = $load[$i * 2] . "." . $load[$i * 2 + 1];
			$newLoad[$i] *= 1.;
		}

		$load = $newLoad;
	}
}

$stat1 = file('/proc/stat');
//sleep(1);
time_nanosleep(0, 500000000);
$stat2 = file('/proc/stat');
$cpus = array();

foreach($stat1 as $index => $line)
{
//	echo "$line <br/>\n";
	if (strpos($line, "cpu") === false) break;

	$offset = "";
	if ($index > 0) {
		$offset = $index - 1;
	}

	$info1 = explode(" ", preg_replace("!cpu$offset +!", "", $stat1[$index]));
	$info2 = explode(" ", preg_replace("!cpu$offset +!", "", $stat2[$index]));
	$dif = array();
	$dif['user'] = $info2[0] - $info1[0];
	$dif['nice'] = $info2[1] - $info1[1];
	$dif['sys'] = $info2[2] - $info1[2];
	$dif['idle'] = $info2[3] - $info1[3];
	$dif['iowait'] = $info2[4] - $info1[4];
	$dif['irq'] = $info2[5] - $info1[5];
	$dif['softirq'] = $info2[6] - $info1[6];
	$dif['steal'] = $info2[7] - $info1[7];
	$total = array_sum($dif);
	$cpu = array();
	foreach($dif as $x=>$y) {
		$cpu[$x] = round($y / $total * 100, 1);
	}
	$cpu['usage'] = $cpu['user'] + $cpu['nice'] + $cpu['sys'] + $cpu['irq'] + $cpu['softirq'] + $cpu['steal'];
	if ($cpu['usage'] > 100) $cpu['usage'] = 100; // Normalization
//	print_r($cpu);
//	echo "<br />\n";

	$cpus[] = $cpu;
}

// Network

define('IFSTAT', '/usr/bin/ifstat');
$output = shell_exec(IFSTAT . '  0.1 1');
$output = preg_split("/\n/", $output);

$interfaces = preg_split("/[\s]+/", trim($output[0]));
$consumptions = preg_split("/[\s]+/", trim($output[2]));

foreach($interfaces as $index => $interface) {
	$interfaces[$index] = array("name" => $interface,
								"in" => $consumptions[$index * 2] * 1024,
								"out" => $consumptions[$index * 2 + 1] * 1024);

	if (isset($ipInterfaces[$interface]["ip_v4"])) {
		$interfaces[$index]["ip_v4"] = $ipInterfaces[$interface]["ip_v4"];
	}
}

?>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_statistics"); ?></li>
	</ol>
	<div class="well well-sm">
		<p><?php echo lang("statistics_guide"); ?></p>
	</div>

	<div class="panel panel-default" id="statusPanel">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("index_status_panel"); ?></h3>
		</div>
		<div class="panel-body">
			<fieldset>
				<legend><?php echo lang("index_memory_legend"); ?></legend>

				<div class="col-md-12" id="memory">
					<div class="col-md-5 total" data-size="<?php echo $totalMemory; ?>"><label class="col-md-7"><?php echo lang("index_memory_total_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($totalMemory, false, 0, 10); ?></span></div>
					<div class="col-md-5 free" data-size="<?php echo $freeMemory; ?>"><label class="col-md-7"><?php echo lang("index_memory_free_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($freeMemory, false, 0, 10); ?></span></div>
					<div class="col-md-2">
						<?php
							$availableMemory = $freeMemory / $totalMemory;
							if ($availableMemory > .2) {
								$memoryBarClass = "progress-bar-success";
							}
							else if ($availableMemory > .1) {
								$memoryBarClass = "progress-bar-warning";
							}
							else {
								$memoryBarClass = "progress-bar-danger";
							}
						?>
						<div class="progress" data-class="<?php echo $memoryBarClass; ?>" data-available="<?php echo $availableMemory * 100; ?>">
							<div class="progress-bar <?php echo $memoryBarClass; ?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo number_format($availableMemory * 100, 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo number_format($availableMemory * 100, 1) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12" id="swap">
					<div class="col-md-5 total" data-size="<?php echo $totalSwap; ?>"><label class="col-md-7"><?php echo lang("index_swap_total_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($totalSwap, false, 0, 10); ?></span></div>
					<div class="col-md-5 used" data-size="<?php echo $totalSwap - $freeSwap; ?>"><label class="col-md-7"><?php echo lang("index_swap_used_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($totalSwap - $freeSwap, false, 0, 10); ?></span></div>
					<div class="col-md-2">
						<div class="progress" data-used="<?php echo ($totalSwap ? ($totalSwap - $freeSwap) / $totalSwap * 100 : 0); ?>">
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo ($totalSwap ? number_format(($totalSwap - $freeSwap) / $totalSwap * 100, 0) : 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo ($totalSwap ? number_format(($totalSwap - $freeSwap) / $totalSwap * 100, 1) : 0) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>

				<legend><?php echo lang("index_disk_legend"); ?></legend>

				<div class="col-md-12" id="disk">
					<div class="col-md-5 total" data-size="<?php echo $ds; ?>"><label class="col-md-7"><?php echo lang("index_disk_total_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($ds, false, 2); ?></span></div>
					<div class="col-md-5 free" data-size="<?php echo $df; ?>"><label class="col-md-7"><?php echo lang("index_disk_free_label"); ?></label><span class="col-md-5"><?php echo humanFileSize($df, false, 2); ?></span></div>
					<div class="col-md-2">
						<div class="progress" data-available="<?php echo $df / $ds * 100; ?>">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo number_format($df / $ds * 100, 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo number_format($df / $ds * 100, 1) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>

				<legend><?php echo lang("index_cpu_legend"); ?></legend>

				<div class="col-md-12">
					<label class="col-md-3"><?php echo lang("index_cpu_load_label"); ?></label>
					<span class="col-md-3 load"><?php echo $load[0]; ?></span>
					<span class="col-md-3 load"><?php echo $load[1]; ?></span>
					<span class="col-md-3 load"><?php echo $load[2]; ?></span>
				</div>
				<div class="col-md-12" id="number-of-cpus" data-cpus="<?php echo count($cpus) - 1; ?>">
					<label class="col-md-3"><?php echo lang("index_cpu_nb_label"); ?></label>
					<span class="col-md-3"><?php echo count($cpus) - 1; ?></span>
				</div>
				<?php foreach($cpus as $cpuIndex => $cpu) {
						if (count($cpus) == 2 && $cpuIndex == 1) continue;
					?>
				<div class="col-md-12" id="cpu<?php echo $cpuIndex; ?>">
					<label class="col-md-3"><?php
						if ($cpuIndex) {
							echo str_replace("{x}", $cpuIndex, lang("index_cpu_x_label"));
						}
						else {
							echo lang("index_cpu_0_label");
						}
					?></label>
					<div class="col-md-9">
						<?php
							$cpuBarClass = "";

							if ($cpu["usage"] < 50) {
								$cpuBarClass = "progress-bar-success";
							}
							else if ($cpu["usage"] < 75) {
								$cpuBarClass = "progress-bar-warning";
							}
							else {
								$cpuBarClass = "progress-bar-danger";
							}
						?>
						<div class="progress" data-class="<?php echo $cpuBarClass; ?>" data-usage="<?php echo number_format($cpu["usage"], 1); ?>">
							<div class="progress-bar <?php echo $cpuBarClass; ?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
								style="width: <?php echo number_format($cpu["usage"], 0) . "%"; ?>; text-shadow: -1px -1px 0 #888, 1px -1px 0 #888, -1px 1px 0 #888, 1px 1px 0 #888;">
								<span style="position: relative; left: 2px;"><?php echo number_format($cpu["usage"], 1) . "%"; ?></span>
							</div>
						</div>
					</div>
				</div>
				<?php }?>

				<?php if (count($interfaces)) {?>
				<legend><?php echo lang("index_network_legend"); ?></legend>
				<?php 	foreach($interfaces as $interface) {?>
				<div class="col-md-12 interface" id="interface-<?php echo $interface["name"]; ?>">
					<label class="col-md-3"><?php echo $interface["name"]; ?>
					(<?php echo isset($interface["ip_v4"]) ? $interface["ip_v4"] : lang("index_network_no_ip"); ?>)
					 :</label>
					<label class="col-md-2"><?php echo lang("index_network_download_label"); ?></label>
					<span class="col-md-2 download" data-size="<?php echo $interface["in"]; ?>"><?php echo humanFileSize($interface["in"], false); ?>/s</span>
					<label class="col-md-2"><?php echo lang("index_network_upload_label"); ?></label>
					<span class="col-md-2 upload" data-size="<?php echo $interface["out"]; ?>"><?php echo humanFileSize($interface["out"], false); ?>/s</span>
				</div>

				<?php 	}?>
				<?php }?>
			</fieldset>
		</div>
	</div>

</div>

<div class="lastDiv"></div>

<script type="text/javascript">
var userLanguage = '<?php echo SessionUtils::getLanguage($_SESSION); ?>';
</script>
<?php include("footer.php");?>
</body>
</html>