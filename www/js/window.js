 /*
    Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

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

function resizeWindow() {
	$(".theme-showcase").css("min-height", ($(window).height() - 94) + "px");
}

$(function() {
	resizeWindow();

	 $(window).resize(function() {
			resizeWindow();
     });

	 $('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 0}});

	 $('ul.nav li.dropdown').hover(function() {
		  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(300);
		}, function() {
		  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(300);
		});
});
