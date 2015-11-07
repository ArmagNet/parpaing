$(function() {

	$("#explorer").on("click", "#add-file-button", function(event) {
		alert("add-file-button");
	});
	$("#explorer").on("click", "#add-directory-button", function(event) {
		alert("add-directory-button");
	});

	$("#explorer").on("click", "li.directory a", function(event) {
		event.preventDefault();
		$.get($(this).attr("href"), {}, function(data) {
			$("#explorer").children().remove();
			$("#explorer").append($(data).find("#explorer").children());
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
	});

	$("#explorer").on("click", "li[data-mimetype*=image] a[data-external!=true]", function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});
	});
});