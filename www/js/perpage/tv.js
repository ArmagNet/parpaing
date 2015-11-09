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