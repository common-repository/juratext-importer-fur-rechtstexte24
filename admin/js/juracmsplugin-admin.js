(function( $ ) {
	'use strict';

	$(document).ready(function () {

		$.fn.fade = function () {
			var _this = this;
			this.fadeOut(500);
			setTimeout(function () {$(_this).fadeIn(500);},500);
			return this;
		};

		/* Navigation */

		$('.nav-container ul li').hover(function () {
			$(this).addClass("hovered");
		}, function () {
			$(this).removeClass("hovered");
		});


		/* AJAX */

		var dirName=JSParams.pluginDir+"admin/partials/ajax/", c=$("#content");

		var opts = {duration: 500, start: function () {$(this).css({"display": "inline-block"});}};

		$(c).fadeIn(opts).load(dirName+"juracmsplugin-admin-standardview.html");

		$(".start").click(function () {
			if ($(this).hasClass("start")) {
				$(".navigator").each(function () {$(this).removeClass("highlighted");});
				$(this).addClass("highlighted");
			}
			$(c).fade();
			$("#credentials .question").fadeOut(500);
			setTimeout(function () {$(c).load(dirName+"juracmsplugin-admin-standardview.html");$("#credentials").fadeIn(500);$("#credentials .description").fadeIn(500);$("#credentials .question").remove()},500);
		});

		$(".leistungen").click(function () {
			$(".navigator").each(function () {$(this).removeClass("highlighted");});
			$(this).addClass("highlighted");
			$("#credentials").fadeOut(500);
			$(c).fade();
			setTimeout(function () {$(c).load(dirName+"juracmsplugin-admin-leistungen.html");if ($("#credentials .question").length) $("#credentials .question").remove();},500);
		});

		$(".einstellungen").click(function () {
			if ($(this).hasClass("einstellungen")) {
				$(".navigator").each(function () {$(this).removeClass("highlighted");});
				$(this).addClass("highlighted");
			}
			$(c).fade();
			$("#credentials .description").fadeOut(500);
			if ($("#credentials .question").length) $("#credentials .question").remove();
			var appender = '<div class="question" style="display: none; margin-bottom: 2em; padding: .3em .5em;">' +
								'<form id="raus" action="' + window.location.href + '" method="post">' +
									'<input type="hidden" name="sent">' +
									'<button class="" onclick="this.form.submit()"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Texte anfordern <i class="fa fa-hand-o-left" aria-hidden="true"></i></button>' +
								'</form>' +
							'</div>';
			setTimeout(function () {$(c).load(dirName+"juracmsplugin-admin-getsettings.html");$("#credentials").fadeIn(500).append(appender);$(".question").fadeIn(500);},500);
		});

		$("table tbody tr").hover(function () {
			$(this).css({"background": "lightgrey"});
		}, function () {
			$(this).css({"background": "white"});
		});

	});

})( jQuery );
