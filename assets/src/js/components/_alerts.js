/**
 * ALERTS
 */

/**
 * Remove alert when x clicked.
 *
 * @see https://codepen.io/MattIn4D/pen/jDvLl
 */
var alert, alert_close;
alert_close = document.querySelectorAll( '.c-alert__close' );
for ( var i = 0, length = alert_close.length; i < length; i++ ) {
	alert_close[i].onclick = function() {
		alert = this.parentNode;
		alert.parentNode.removeChild( alert );
	}
}
