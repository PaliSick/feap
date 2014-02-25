
//KNF disable / enable implementation for jQuery
(function($){
	var to = 0;
	$.fn.disable = function() {
		$(this).find("input, textarea, select").attr("disabled", "disabled").attr("readonly","readonly");
		$(this).find("input[type=submit], input[type=button]").addClass("disabled-button");

		return this;
	}

	$.fn.enable = function() {
		$(this).find("input, textarea, select").removeAttr("disabled").removeAttr("readonly");
		$(this).find("input[type=submit], input[type=button]").removeClass("disabled-button");
		return this;
	}

	$.fn.echomsg = function(msg, type) {
		var $this = $(this);
		$this.hide().html('<div class="msg-'+ type +'"><div class="icon"><div></div></div><div class="txt">'+ msg +'</div></div>').slideDown();
		clearTimeout(to);
		to = setTimeout(function() {
			$this.slideUp('fast');
		}, 3000);
		return this;
	}
})(jQuery);

function fmtMoney(n, c, d, t){
var m = (c = Math.abs(c) + 1 ? c : 2, d = d || ",", t = t || ".",
        /(\d+)(?:(\.\d+)|)/.exec(n + "")), x = m[1].length > 3 ? m[1].length % 3 : 0;
    return (x ? m[1].substr(0, x) + t : "") + m[1].substr(x).replace(/(\d{3})(?=\d)/g,
        "$1" + t) + (c ? d + (+m[2] || 0).toFixed(c).substr(2) : "");
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

var loaderTimer = null,
	requestsCount = 0;

$(document).ready(function() {

	$.ajaxSetup({
		beforeSend:	function(jqXHR, settings) {
			requestsCount++;
			loaderTimer = setTimeout(function() {
				$('#busy-indicator').fadeIn();
			}, 1000);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert("Ocurrio un error en la solicitud al servidor.\nErrorCode: "+textStatus+" ["+errorThrown+"]");
		},
		complete: function(jqXHR, textStatus){
			requestsCount--;
			clearTimeout(loaderTimer);
			$('#busy-indicator').fadeOut();
		}
	});

	$('#salir').click(function(ev) {

		ev.preventDefault();
		$.get('/admin/index/salir', function(data) {
			if (data.status == 'ok') {
				window.location = "";
			} 
		}, 'json');
	});
	$('#idp-menu-holder ul').hide();
	$('#idp-menu-holder ul li.selected').parent('ul').show().prev('.sub-menu-head').children('.compact-button').addClass('toggled');

	$('.sub-menu-head').click(
		function (ev) {

			if ($(this).children('.compact-button').hasClass('toggled'))
				$(this).children('.compact-button').removeClass("toggled").end().next('ul').slideUp();
			else
				$(this).children('.compact-button').addClass("toggled").end().next('ul').slideDown();
			ev.preventDefault();
		}
	).disableSelection();

});