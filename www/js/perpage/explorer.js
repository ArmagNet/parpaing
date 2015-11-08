function normalizeButtonsState() {
	if ($("#explorer").hasClass("items")) {
		$("#explorer button#to-item-button").addClass("active");
		$("#explorer button#to-list-button").removeClass("active");
	}
	else {
		$("#explorer button#to-list-button").addClass("active");
		$("#explorer button#to-item-button").removeClass("active");
	}
}

function setVolumeWorkaround(volumeCapable) {
	var volume = $.cookie('explorer-media-volume');
	if (volume || volume == "0") {
		volumeCapable.volume = volume;
	}
	volumeCapable.onvolumechange = function() {
		var volume = this.volume;
		$.cookie('explorer-media-volume', volume, { expires: 365, path: '/' });
	};
}


$(function() {

	$("#explorer").on("click", "button#to-list-button", function(event) {
		$("#explorer").removeClass("items");
		$(this).addClass("active");
		$("#explorer button#to-item-button").removeClass("active");
	});

	$("#explorer").on("click", "button#to-item-button", function(event) {
		$("#explorer").addClass("items");
		$(this).addClass("active");
		$("#explorer button#to-list-button").removeClass("active");
	});

	$("#explorer").on("click", "button#add-file-button", function(event) {
		alert("add-file-button");
	});
	$("#explorer").on("click", "button#add-directory-button", function(event) {
		var prompt = $("*[aria-template-id=template-createFolder-prompt]").template("use", {
			data: {}
		}).text();

		bootbox.prompt(prompt, function(result) {
			if (result !== null) {

				if (result.substr(result.length - 1, 1) != "/") {
					result += "/";
				}

				var path = $("#explorer ul#files").data("path");

				$.post("do_createFolder.php", {path: path + result}, function(data) {
					$.get("explorer.php", {path: path + result}, function(data) {
						$("#explorer").children().remove();
						$("#explorer").append($(data).find("#explorer").children());
						normalizeButtonsState();
						history.pushState('', document.title, '?path=' + path + result);
					}, "html");
				}, "json");
			}
		});
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});
	});

	$("#explorer").on("click", "li.directory a", function(event) {
		event.preventDefault();
		var url = $(this).attr("href");
		$.get(url, {}, function(data) {
			$("#explorer").children().remove();
			$("#explorer").append($(data).find("#explorer").children());
			normalizeButtonsState();
			history.pushState('', document.title, url);
		}, "html");
	});

	$("#explorer").on("click", "li[data-mimetype*=video] a[data-external!=true]", function(event) {
		event.preventDefault();
		var li = $(this).parents("li");

		var url = li.data("url");
		var mimetype = li.data("mimetype");
		var name = li.find(".file-name").text();

		var videoPlayer = 	$("*[aria-template-id=template-video]").template(
								"use", {
								data: {video_url: url, video_type: mimetype, video_name: name}
							});

		bootbox.dialog({
            title: name,
            message: videoPlayer,
            buttons: {
            }
        });
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});

		setVolumeWorkaround(videoPlayer.find(".explorer-media").get(0));
	});

	$("#explorer").on("click", "li[data-mimetype*=audio] a[data-external!=true]", function(event) {
		event.preventDefault();
		var li = $(this).parents("li");

		var url = li.data("url");
		var mimetype = li.data("mimetype");
		var name = li.find(".file-name").text();

		var audioPlayer = 	$("*[aria-template-id=template-audio]").template(
								"use", {
								data: {audio_url: url, audio_type: mimetype, audio_name: name}
							});

		bootbox.dialog({
            title: name,
            message: audioPlayer,
            buttons: {
            }
        });
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});

		setVolumeWorkaround(audioPlayer.find(".explorer-media").get(0));
	});

	$("#explorer").on("click", "li[data-mimetype*=image] a[data-external!=true]", function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});
	});

});