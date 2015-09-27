<?php /*
	Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of OpenTweetBar.

    OpenTweetBar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    OpenTweetBar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with OpenTweetBar.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("header.php");
?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><?php echo lang("breadcrumb_index"); ?></li>
		<li class="active"><?php echo lang("breadcrumb_tv"); ?></li>
	</ol>

	<div class="col-md-12">

	SOON !

	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<script>
$(function() {
	$(".panel").hover(function() {
					$(this).removeClass("panel-default");
					$(this).addClass("panel-success");
					$(this).find(".panel-body").addClass("text-success");
				}, function() {
					$(this).addClass("panel-default");
					$(this).removeClass("panel-success");
					$(this).find(".panel-body").removeClass("text-success");
				});
});
</script>
</body>
</html>