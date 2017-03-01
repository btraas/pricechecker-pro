// http://stackoverflow.com/questions/15191058/css-rotation-cross-browser-with-jquery-animate

$.fn.animateRotate = function(angle, duration, easing, complete) {
  var args = $.speed(duration, easing, complete);
  var step = args.step;
  return this.each(function(i, e) {
    args.complete = $.proxy(args.complete, e);
    args.step = function(now) {
      $.style(e, 'transform', 'rotate(' + now + 'deg)');
      if (step) return step.apply(e, arguments);
    };

    $({deg: 0}).animate({deg: angle}, args);
  });
};


var animateSettings = {};


function animateLogo(elem, duration) {
    animateSettings.duration = duration;
	animateSettings.complete = function() { animateLogo(elem, duration); };
    elem.animateRotate(360, animateSettings);
}


animateSettings =
{
    duration: 10000,
    easing: 'linear',
    complete: function() {},
    step: function() {}
}


empty = function( v ) { var t = typeof v; return t === 'undefined' || ( t === 'object' ? ( v === null || Object.keys( v ).length === 0 ) : [false, 0, "", "0"].indexOf( v ) >= 0 ); };

