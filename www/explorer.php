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

function getPermString($perms) {
	if (($perms & 0xC000) == 0xC000) {
		// socket
		$info = 's';
	}
	elseif (($perms & 0xA000) == 0xA000) {
		// symbolic link
		$info = 'l';
	}
	elseif (($perms & 0x8000) == 0x8000) {
		// regular
		$info = '-';
	}
	elseif (($perms & 0x6000) == 0x6000) {
		// special block
		$info = 'b';
	}
	elseif (($perms & 0x4000) == 0x4000) {
		// directory
		$info = 'd';
	}
	elseif (($perms & 0x2000) == 0x2000) {
		// special char
		$info = 'c';
	}
	elseif (($perms & 0x1000) == 0x1000) {
		// FIFO
		$info = 'p';
	}
	else {
		// Unknown
		$info = 'u';
	}

	// Other
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
			(($perms & 0x0800) ? 's' : 'x' ) :
			(($perms & 0x0800) ? 'S' : '-'));

	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
			(($perms & 0x0400) ? 's' : 'x' ) :
			(($perms & 0x0400) ? 'S' : '-'));

	// Everybody
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
			(($perms & 0x0200) ? 't' : 'x' ) :
			(($perms & 0x0200) ? 'T' : '-'));

	return $info;
}

?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_explorer"); ?></li>
	</ol>

	<div class="col-md-12" id="explorer">

	<ol class="breadcrumb">
		<?php
			$path = "/";

			if (isset($_REQUEST["path"])) {
				$path = $_REQUEST["path"];

				if (strpos($path, "..") !== false) {
					$path = "/";
				}
			}

			$index = -1;
			$previousIndex = -1;
			$paths = array();
			while(($index = strpos($path, "/", $index + 1)) !== false) {
				$pathPart = array("path" => substr($path, 0, $index + 1));
				$pathPart["label"] = substr($path, $previousIndex + 1, $index - $previousIndex - 1);
				if (!$pathPart["label"]) {
					$pathPart["label"] = "ROOT";
				}

				$paths[] = $pathPart;

				$previousIndex = $index;
			}
			foreach($paths as $index => $pathPart) {
				if ($index == count($paths) - 1) {?>
		<li class="active"><?php echo $pathPart["label"] ?></li>
		<?php 	} else {?>
		<li><a href="?path=<?php echo $pathPart["path"] ?>" class="directory"><?php echo $pathPart["label"] ?></a></li>
		<?php 	}
			}?>
	</ol>

	<ul>
	<?php
		$files = glob($config["parpaing"]["root_directory"] . str_replace("[", "\\[", str_replace("]", "\\]", $path)) . "*");

		function orderFiles($a, $b) {
			if ($a == $b) return 0;

			$dirA = is_dir($a) ? 1 : 0;
			$dirB = is_dir($b) ? 1 : 0;

			if ($dirA != $dirB) return $dirB - $dirA;

			return $a < $b ? -1 : 1;
		}

		usort($files, "orderFiles");

//		print_r($files);

		$finfo = finfo_open(FILEINFO_MIME_TYPE);

		foreach($files as $file) {
			$filepath = str_replace($config["parpaing"]["root_directory"], "", $file);

			$label = substr($file, strrpos($file, "/") + 1);

			$mimetype = finfo_file($finfo, $file);
			switch($mimetype) {
				case "directory":
					$type = "directory";
					$icon = "glyphicon-folder-close";
					$filepath .= "/";
					$toolpath = "";
					break;
				case "image/bmp":
				case "image/tif":
				case "image/png":
				case "image/gif":
				case "image/jpeg":
					$type = "file";
					$icon = "glyphicon-picture";
//					$toolpath = "viewImage.php";
					$toolpath = "do_downloadFile.php";
					break;
				default:
					$type = "file";
					$icon = "glyphicon-file";
					$toolpath = "do_downloadFile.php";
					break;
				// book, film, music
			}

			$filesize = filesize($file);
			$group = posix_getgrgid(filegroup($file));
			$owner = posix_getpwuid(fileowner($file));

			$creationDate = date("Y-m-d H:i:s", filectime($file));
			$creationDate = new DateTime($creationDate);
			$modificationDate = date("Y-m-d H:i:s", filemtime($file));
			$modificationDate = new DateTime($modificationDate);

			$permissions = getPermString(fileperms($file));
	?>
		<li
			data-size="<?php echo $filesize; ?>"
			data-mimetype="<?php echo $mimetype; ?>"
			class="<?php echo $type; ?>">
				<span class="glyphicon <?php echo $icon; ?>"></span>

				<code class="file-permissions"><?php echo $permissions; ?></code>

				<span class="file-name"><a href="<?php echo $toolpath; ?>?path=<?php echo $filepath; ?>"><?php echo $label; ?></a></span>

				<code class="file-owner"><?php echo $owner["name"]; ?></code>
				<code class="file-group"><?php echo $group["name"]; ?></code>
				<code class="file-creation"><?php echo $creationDate->format("Y-m-d H:i:s"); ?></code>
				<code class="file-modification"><?php echo $modificationDate->format("Y-m-d H:i:s"); ?></code>
				<code class="file-size"><?php echo humanFileSize($filesize, false); ?></code>

		</li>
	<?php
		}
	?>
	</ul>

	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
</body>
</html>