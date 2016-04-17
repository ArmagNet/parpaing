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
		<li class="active"><?php echo lang("breadcrumb_piraxplorer"); ?></li>
	</ol>

	<div class="col-md-12 items" id="explorer">

	<div>
		<div class="pull-left breadcrumb">
			<span class="glyphicon glyphicon-folder-open active"></span>
		</div>
		<div class="pull-right breadcrumb">
			<button id="add-file-button" type="button"
				data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("explorer_tooltip_addFiles"); ?>"
				class="btn btn-default btn-xs"><span class="glyphicon glyphicon-open-file" style="margin-right: -2px;"></span></button>
			<button id="add-directory-button" type="button"
				data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("explorer_tooltip_createFolder"); ?>"
				class="btn btn-default btn-xs"><span class="glyphicon glyphicon-folder-close" style="margin-right: -1px; margin-left: -1px;"></span></button>
		</div>
		<div class="pull-right breadcrumb">
			<div class="btn-group" role="group">
				<button id="to-list-button" type="button"
					data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("explorer_tooltip_list"); ?>"
					class="btn btn-default btn-xs active"><span class="glyphicon glyphicon-th-list"></span></button>
				<button id="to-item-button" type="button"
					data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("explorer_tooltip_icons"); ?>"
					class="btn btn-default btn-xs"><span class="glyphicon glyphicon-th-large"></span></button>
			</div>
		</div>
		<ol class="breadcrumb">
			<?php
				$path = "/";
				$previous = null;

				if (isset($_REQUEST["path"])) {
					$path = $_REQUEST["path"];

					if (strpos($path, "..") !== false) {
						$path = "/";
					}
				}

				$highlight = "";
				if (isset($_REQUEST["highlight"])) {
					$highlight = $_REQUEST["highlight"];
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
			<li class="active"><?php echo $pathPart["label"]; ?></li>
			<?php 	} else {
						$previous = $config["parpaing"]["root_directory"] . $pathPart["path"]; ?>
			<li class="directory"><a href="?path=<?php echo $pathPart["path"]; ?>"><?php echo $pathPart["label"]; ?></a></li>
			<?php 	}
				}?>
		</ol>
	</div>

	<ul id="files" data-path="<?php echo $path; ?>">
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

		if ($previous) {
			$previous = substr($previous, 0, strlen($previous) - 1);
			$files = array_reverse($files);
			array_push($files, $previous);
			$files = array_reverse($files);
		}

		$finfo = finfo_open(FILEINFO_MIME_TYPE);

		foreach($files as $index => $file) {

			$isHighlited = false;

			if ($highlight) {
				$matches = array();
				$regexp = $highlight;
				$regexp = str_replace("\\", "\\\\", $regexp);
				$regexp = str_replace(".", "\.", $regexp);
				$regexp = str_replace("[", "\[", $regexp);
				$regexp = str_replace("]", "\]", $regexp);
				$regexp = str_replace("(", "\(", $regexp);
				$regexp = str_replace(")", "\)", $regexp);

				preg_match_all("($regexp)", $file, $matches);

				if (count($matches[0])) {
					$isHighlited = true;
				}
			}

			$filepath = str_replace($config["parpaing"]["root_directory"], "", $file);

			$label = substr($file, strrpos($file, "/") + 1);
			if ($previous && $index == 0) $label = "..";

			$mimetype = finfo_file($finfo, $file);
			$external = false;
			switch($mimetype) {
				case "directory":
					$type = "directory";
					if ($label != "..") {
						$icon = "glyphicon-folder-close";
					}
					else {
						$icon = "glyphicon-level-up";
					}
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
					$external = true;
					break;
				case "video/mp4":
				case "video/x-matroska":
					$mimetype = "default";
					$type = "file";
					$icon = "glyphicon-film";
//					$toolpath = "viewFilm.php";
					$toolpath = "do_downloadFile.php";
					$external = false;
					break;
				case "application/ogg":
				case "audio/mpeg":
					$type = "file";
					$toolpath = "do_downloadFile.php";
					$icon = "glyphicon-music";
					$external = true;
					break;
				case "application/octet-stream":
					$type = "file";
					$toolpath = "do_downloadFile.php";
					if (strpos($file, ".mp3") !== false) {
						$icon = "glyphicon-music";
						$external = true;
						$mimetype = "audio/mp3";
					}
					else {
						$icon = "glyphicon-file";
					}
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
			data-url="do_downloadFile.php?path=<?php echo $filepath; ?>"
			class="<?php echo $type; ?> <?php if ($isHighlited) { echo "highlight"; }?>">
				<span class="file-icon" data-toggle="tooltip" data-placement="bottom" title="<?php echo $label; ?>">
					<a href="<?php echo $toolpath; ?>?path=<?php echo $filepath; ?>"><span class="glyphicon <?php echo $icon; ?>"></span></a>
				</span>

				<!--
				<code class="file-permissions"><?php echo $permissions; ?></code>
 				-->

				<span class="file-name <?php if ($external) {?>with-external<?php }?>"
					data-toggle="tooltip" data-placement="bottom" title="<?php echo $label; ?>">
					<a href="<?php echo $toolpath; ?>?path=<?php echo $filepath; ?>"><?php echo $label; ?></a>
				</span>
				<?php if ($external) {?>
				<span class="file-external"
					data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("explorer_tooltip_download"); ?>">
					<a data-external="true" href="<?php echo $toolpath; ?>?path=<?php echo $filepath; ?>"><span class="glyphicon glyphicon-save-file"></span></a>
				</span>
				<?php }?>

				<!--
				<code class="file-owner"><?php echo $owner["name"]; ?></code>
				<code class="file-group"><?php echo $group["name"]; ?></code>
				 -->
				<code class="file-creation"><?php echo $creationDate->format("Y-m-d H:i:s"); ?></code>
				<!--
				<code class="file-modification"><?php echo $modificationDate->format("Y-m-d H:i:s"); ?></code>
 				-->
 				<code class="file-size"><?php echo humanFileSize($filesize, false); ?></code>

		</li>
	<?php
		}
	?>
	</ul>

	</div>
</div>

<div class="lastDiv"></div>

<templates>
	<span aria-template-id="template-cancel"><?php echo lang("common_cancel"); ?></span>
	<span aria-template-id="template-send"><?php echo lang("common_send"); ?></span>
	<span aria-template-id="template-createFolder-prompt"><?php echo lang("explorer_prompt_createFolder"); ?></span>
	<span aria-template-id="template-addFile-prompt"><?php echo lang("explorer_prompt_addFile"); ?></span>
	<div aria-template-id="template-addFile-form" class="">
		<form action="do_addFiles.php" method="post" enctype="multipart/form-data">
			<input id="filesInput" name="files[]" multiple="multiple" type="file" />
			<input id="pathInput" name="path" type="hidden" value="${path}"/>
		</form>
		<div class="progress" id="filesProgress" style="display: none;">
			<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
		    	<span class="sr-only"></span>
		  	</div>
		</div>
	</div>
	<div aria-template-id="template-video" class="embed-responsive embed-responsive-16by9 text-center">
	    <video controls class="embed-responsive-item explorer-media">
	        <source src="${video_url}&streaming=" type="${video_type}">
	        <a href="${video_url}">${video_name}</a>
	    </video>
	</div>
	<div aria-template-id="template-audio" class="embed-responsive embed-responsive-audio text-center">
	    <audio controls class="embed-responsive-item explorer-media">
	        <source src="${audio_url}&streaming=" type="${audio_type}">
	        <a href="${audio_url}">${audio_name}</a>
	    </audio>
	</div>
</templates>

<?php include("footer.php");?>
</body>
</html>