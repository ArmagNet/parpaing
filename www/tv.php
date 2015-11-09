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
		<li class="active"><?php echo lang("breadcrumb_tv"); ?></li>
	</ol>

	<div class="col-md-12">

		<!-- FT Group -->

		<a href="http://www.france2.fr/direct" data-frame="francetv" title="France 2"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_france2.png" />
		</div></a>
		<a href="http://www.france3.fr/direct" data-frame="francetv" title="France 3"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_france3.png" />
		</div></a>
		<a href="http://www.france4.fr/direct" data-frame="francetv" title="France 4"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_france4.png" />
		</div></a>
		<a href="http://www.france5.fr/direct" data-frame="francetv" title="France 5"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_france5.png" />
		</div></a>
		<a href="http://www.franceo.fr/direct" data-frame="francetv" title="France Ô"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_franceo.png" />
		</div></a>

		<!-- M6 Group -->

		<div class="col-md-3 channel-tv">
			<a href="http://www.6play.fr/m6/direct#/m6/direct" target="_blank"><img src="images/logos/logo_m6.png" />
		</div></a>

		<div class="col-md-3 channel-tv">
			<a href="http://www.6play.fr/w9/direct#/w9/direct" target="_blank"><img src="images/logos/logo_w9.png" />
		</div></a>

		<div class="col-md-3 channel-tv">
			<a href="http://www.6play.fr/6ter/direct#/6ter/direct" target="_blank"><img src="images/logos/logo_6ter.png" />
		</div></a>

		<!-- BFM Group -->

		<a href="tv/bfmtv.php?channel=bfmtv" data-frame="bfmtv" title="BFM TV"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_bfmtv.png" />
		</div></a>

		<a href="tv/bfmtv.php?channel=bfmbusiness" data-frame="bfmbusiness" title="BFM Business"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_bfmbusiness.png" />
		</div></a>

		<a href="http://rmc.bfmtv.com/mediaplayer/live-audio/" target="_blank"><div class="col-md-3 channel-tv audio">
			<img src="images/logos/logo_rmc.png" />
		</div></a>

		<a href="http://rmcdecouverte.bfmtv.com/mediaplayer-direct/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_rmcdecouverte.png" />
		</div></a>

		<a href="http://rmcsport.bfmtv.com/mediaplayer/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_rmcsport.png" />
		</div></a>

		<a href="http://www.01net.com/mediaplayer/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_01net.png" />
		</div></a>

		<!-- TF1 Group -->

		<a href="http://www.tf1.fr/tf1/direct" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_tf1.png" />
		</div></a>

		<a href="http://www.tf1.fr/nt1/direct" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_nt1.png" />
		</div></a>

		<a href="http://www.tf1.fr/hd1/direct" target="_blank"><div class="col-md-3 channel-tv audio">
			<img src="images/logos/logo_hd1.png" />
		</div></a>

		<a href="http://www.tf1.fr/tmc/direct" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_tmc.png" />
		</div></a>

		<a href="http://www.numero23.fr/direct/" target="_blank"><div class="col-md-3 channel-tv">
			<img src="images/logos/logo_numero23.png" />
		</div></a>

	</div>
</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>
<style>
.francetv .modal-dialog {
	width: 612px !important;
}

.bfmtv .modal-dialog {
	width: 660px !important;
}

.bfmbusiness .modal-dialog {
	width: 981px !important;
}

</style>
<script>

function francetv(channelUrl, channelTitle) {

	var framebox = "<iframe id='francetv' src='"+channelUrl+"' style='width: 580px; height: 755px'></iframe>";
	framebox = $(framebox);

	bootbox.dialog({
		className: "francetv",
        title: channelTitle,
        message: framebox
    });
	var zIndex = $(".modal-backdrop").css("z-index");
	$(".modal-dialog").css({"z-index": zIndex});
}

function bfmtv(channelUrl, channelTitle) {

	var framebox = "<iframe id='bfmtv' src='"+channelUrl+"' style='width: 628px; height: 354px; overflow: hidden; '></iframe>";
	framebox = $(framebox);

	bootbox.dialog({
		className: "bfmtv",
        title: channelTitle,
        message: framebox
    });

	var clearout = function() {
		var body = $("#bfmtv").contents();

		body.find("body .header-fixed").hide();
		body.find("body #wrap center").hide();
		body.find("body #conteneurCookies").hide();
		body.find("body #conteneurTextCookies").hide();
		body.find("body footer").hide();
		body.find("body").css({"overflow": "hidden"});
		body.find("body").find(".bloc").css({"margin": "0"});
		body.find("#bb5-site-wrapper").css({"margin-top": "-43px"});

		var divs = body.find("body #wrap > div");
		divs.hide();
		var firstDiv = divs.eq(0);
		firstDiv.show();
		firstDiv.css({padding : "0"});

		divs = firstDiv.find(".row > div");
		divs.each(function(index) {
			if (index != 1) {
				$(this).remove();
			}
			else {
				$(this).css({"margin-left": "-10px"});
			}
		});
	};
	var zIndex = $(".modal-backdrop").css("z-index");
	$(".modal-dialog").css({"z-index": zIndex});

	setTimeout(clearout, 2000);
}

function bfmbusiness(channelUrl, channelTitle) {

	var framebox = "<iframe id='bfmtv' src='"+channelUrl+"' style='width: 949px; height: 537px; overflow: hidden; '></iframe>";
	framebox = $(framebox);

	bootbox.dialog({
		className: "bfmbusiness",
        title: channelTitle,
        message: framebox
    });

	var clearout = function() {
		var body = $("#bfmtv").contents();

		body.find("body .header-fixed").hide();
		body.find("body #wrap center").hide();
		body.find("body #conteneurCookies").hide();
		body.find("body #conteneurTextCookies").hide();
		body.find("body footer").hide();
		body.find("body").css({"overflow": "hidden"});
		body.find("#bb5-site-wrapper").css({"margin-top": "-45px"});
		//		body.find("body").find("*").css({"margin": "0", "padding": 0});

		var divs = body.find("body #wrap > div");
		divs.hide();
		var firstDiv = divs.eq(0);
		firstDiv.show();
		firstDiv.css({padding : "0"});

		divs = firstDiv.find(".row > div");
		divs.each(function(index) {
			if (index != 0) {
				$(this).remove();
			}
			else {
				$(this).css({"margin": "0", "padding": "0"});
			}
		});
	};
	var zIndex = $(".modal-backdrop").css("z-index");
	$(".modal-dialog").css({"z-index": zIndex});

	setTimeout(clearout, 2000);
}

$(function() {

	$("a").each(function() {
		var frameFunction = $(this).data("frame");
		if (frameFunction) {

			frameFunction = eval(frameFunction);

			$(this).click(function(event) {
				event.stopPropagation();
				event.preventDefault();

				frameFunction($(this).attr("href"), $(this).attr("title"));
			});
		}
	});

});
</script>
</body>
</html>