$(function() {

	$("li[data-mimetype*=video]").on("click", "a[data-external!=true]", function(event) {
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
	});

	$("li[data-mimetype*=audio]").on("click", "a[data-external!=true]", function(event) {
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
	});

	$("li[data-mimetype*=image]").on("click", "a[data-external!=true]", function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
});