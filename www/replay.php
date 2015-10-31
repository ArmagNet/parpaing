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
		<li class="active"><?php echo lang("breadcrumb_replay"); ?></li>
	</ol>

	<div class="col-md-12">

		<a href="http://www.tf1.fr/programmes-tv" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_mytf1.png" />
		</div></a>

		<a href="http://pluzz.francetv.fr/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_francepluzz.png" />
		</div></a>

		<a href="http://www.6play.fr/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_6play.png" />
		</div></a>

		<a href="http://www.nrj12.fr/replay-4203/collectionvideo/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_nrj12.png" />
		</div></a>


	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<script>
</script>
</body>
</html>