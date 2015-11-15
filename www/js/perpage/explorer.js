function filesProgressHandlingFunction(e) {
    if (e.lengthComputable){
        var percent = Math.floor(e.loaded / e.total * 100);

        $("#filesProgress .progress-bar").attr("aria-valuenow", percent);
        $("#filesProgress .progress-bar").css({width: percent + "%"});
    }
}

function sendFiles(form) {
    var formData = new FormData(form.find("form").get(0));

    form.find("#filesProgress .progress-bar").attr("aria-valuenow", 0);
    form.find("#filesProgress .progress-bar").css({width: "0"});
    form.find("#filesProgress").show();
	form.find("#filesInput").hide();

    $.ajax({
        url: 'do_addFiles.php',  //Server script to process data
        type: 'POST',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress', filesProgressHandlingFunction, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        //Ajax events
        success: function(data) {
//    		data = JSON.parse(data);
    		// Close the dialog
        	$(".bootbox button[data-bb-handler=cancel]").click();

			var path = $("#explorer ul#files").data("path");

			$.get("explorer.php", {path: path}, function(data) {
				$("#explorer").children().remove();
				$("#explorer").append($(data).find("#explorer").children());
				normalizeButtonsState();
//				history.pushState('', document.title, '?path=' + path + result);
			}, "html");

        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });

}

function normalizeButtonsState() {
	if ($("#explorer").hasClass("items")) {
		$("#explorer button#to-item-button").addClass("active");
		$("#explorer button#to-list-button").removeClass("active");
	}
	else {
		$("#explorer button#to-list-button").addClass("active");
		$("#explorer button#to-item-button").removeClass("active");
	}

	 $('#explorer [data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 0}});
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
//		alert("add-file-button");
		var prompt = $("*[aria-template-id=template-addFile-prompt]").template("use", {
			data: {}
		}).text();

		var path = $("#explorer ul#files").data("path");
		var form = 	$("*[aria-template-id=template-addFile-form]").template(
				"use", {
				data: {
					path: path
				}
			});

		bootbox.dialog({
			title: prompt,
			message: form,
			buttons: {
				cancel: {
				      label: $("*[aria-template-id=template-cancel]").text(),
				      className: "btn-default",
				      callback: function() {
				      }
				},
				success: {
				      label: $("*[aria-template-id=template-send]").text(),
				      className: "btn-primary",
				      callback: function() {
				    	  if (!form.find("#filesInput").val()) {
				    		  return false;
				    	  }

				    	  sendFiles(form);

				    	  return false;
				      }
				}
			}
		});
		var zIndex = $(".modal-backdrop").css("z-index");
		$(".modal-dialog").css({"z-index": zIndex});
	});

	$("#explorer").on("click", "button#add-directory-button", function(event) {
		var prompt = $("*[aria-template-id=template-createFolder-prompt]").template("use", {
			data: {}
		}).text();

		bootbox.setLocale("fr");
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

	$("#explorer").on("click", "li[data-mimetype*=audio] a[data-external!=true],li[data-mimetype*=ogg] a[data-external!=true]", function(event) {
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