/*
 * jQuery UI 0.7.0a
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI
 */
;jQuery.ui || (function($) {

var _remove = $.fn.remove,
	isFF2 = $.browser.mozilla && (parseFloat($.browser.version) < 1.9);

//Helper functions and ui object
$.ui = {
	version: "0.7.0a",

	// $.ui.plugin is deprecated.  Use the proxy pattern instead.
	plugin: {
		add: function(module, option, set) {
			var proto = $.ui[module].prototype;
			for(var i in set) {
				proto.plugins[i] = proto.plugins[i] || [];
				proto.plugins[i].push([option, set[i]]);
			}
		},
		call: function(instance, name, args) {
			var set = instance.plugins[name];
			if(!set || !instance.element[0].parentNode) { return; }

			for (var i = 0; i < set.length; i++) {
				if (instance.options[set[i][0]]) {
					set[i][1].apply(instance.element, args);
				}
			}
		}
	},

	contains: function(a, b) {
		return document.compareDocumentPosition
			? a.compareDocumentPosition(b) & 16
			: a !== b && a.contains(b);
	},

	hasScroll: function(el, a) {

		//If overflow is hidden, the element might have extra content, but the user wants to hide it
		if (jQuery(el).css('overflow') == 'hidden') { return false; }

		var scroll = (a && a == 'left') ? 'scrollLeft' : 'scrollTop',
			has = false;

		if (el[scroll] > 0) { return true; }

		// TODO: determine which cases actually cause this to happen
		// if the element doesn't have the scroll set, see if it's possible to
		// set the scroll
		el[scroll] = 1;
		has = (el[scroll] > 0);
		el[scroll] = 0;
		return has;
	},

	isOverAxis: function(x, reference, size) {
		//Determines when x coordinate is over "b" element axis
		return (x > reference) && (x < (reference + size));
	},

	isOver: function(y, x, top, left, height, width) {
		//Determines when x, y coordinates is over "b" element
		return $.ui.isOverAxis(y, top, height) && $.ui.isOverAxis(x, left, width);
	},

	keyCode: {
		BACKSPACE: 8,
		CAPS_LOCK: 20,
		COMMA: 188,
		CONTROL: 17,
		DELETE: 46,
		DOWN: 40,
		END: 35,
		ENTER: 13,
		ESCAPE: 27,
		HOME: 36,
		INSERT: 45,
		LEFT: 37,
		NUMPAD_ADD: 107,
		NUMPAD_DECIMAL: 110,
		NUMPAD_DIVIDE: 111,
		NUMPAD_ENTER: 108,
		NUMPAD_MULTIPLY: 106,
		NUMPAD_SUBTRACT: 109,
		PAGE_DOWN: 34,
		PAGE_UP: 33,
		PERIOD: 190,
		RIGHT: 39,
		SHIFT: 16,
		SPACE: 32,
		TAB: 9,
		UP: 38
	}
};

// WAI-ARIA normalization
if (isFF2) {
	var attr = $.attr,
		removeAttr = $.fn.removeAttr,
		ariaNS = "http://www.w3.org/2005/07/aaa",
		ariaState = /^aria-/,
		ariaRole = /^wairole:/;

	$.attr = function(elem, name, value) {
		var set = value !== undefined;

		return (name == 'role'
			? (set
				? attr.call(this, elem, name, "wairole:" + value)
				: (attr.apply(this, arguments) || "").replace(ariaRole, ""))
			: (ariaState.test(name)
				? (set
					? elem.setAttributeNS(ariaNS,
						name.replace(ariaState, "aaa:"), value)
					: attr.call(this, elem, name.replace(ariaState, "aaa:")))
				: attr.apply(this, arguments)));
	};

	$.fn.removeAttr = function(name) {
		return (ariaState.test(name)
			? this.each(function() {
				this.removeAttributeNS(ariaNS, name.replace(ariaState, ""));
			}) : removeAttr.call(this, name));
	};
}

//jQuery plugins
$.fn.extend({
	remove: function() {
		// Safari has a native remove event which actually removes DOM elements,
		// so we have to use triggerHandler instead of trigger (#3037).
		jQuery("*", this).add(this).each(function() {
			jQuery(this).triggerHandler("remove");
		});
		return _remove.apply(this, arguments );
	},

	enableSelection: function() {
		return this
			.attr('unselectable', 'off')
			.css('MozUserSelect', '')
			.unbind('selectstart.ui');
	},

	disableSelection: function() {
		return this
			.attr('unselectable', 'on')
			.css('MozUserSelect', 'none')
			.bind('selectstart.ui', function() { return false; });
	},

	scrollParent: function() {
		var scrollParent;
		if(($.browser.msie && (/(static|relative)/).test(this.css('position'))) || (/absolute/).test(this.css('position'))) {
			scrollParent = this.parents().filter(function() {
				return (/(relative|absolute|fixed)/).test($.curCSS(this,'position',1)) && (/(auto|scroll)/).test($.curCSS(this,'overflow',1)+$.curCSS(this,'overflow-y',1)+$.curCSS(this,'overflow-x',1));
			}).eq(0);
		} else {
			scrollParent = this.parents().filter(function() {
				return (/(auto|scroll)/).test($.curCSS(this,'overflow',1)+$.curCSS(this,'overflow-y',1)+$.curCSS(this,'overflow-x',1));
			}).eq(0);
		}

		return (/fixed/).test(this.css('position')) || !scrollParent.length ? jQuery(document) : scrollParent;
	}
});


//Additional selectors
$.extend($.expr[':'], {
	data: function(elem, i, match) {
		return !!$.data(elem, match[3]);
	},

	focusable: function(element) {
		var nodeName = element.nodeName.toLowerCase(),
			tabIndex = $.attr(element, 'tabindex');
		return (/input|select|textarea|button|object/.test(nodeName)
			? !element.disabled
			: 'a' == nodeName || 'area' == nodeName
				? element.href || !isNaN(tabIndex)
				: !isNaN(tabIndex))
			// the element and all of its ancestors must be visible
			// the browser may report that the area is hidden
			&& !jQuery(element)['area' == nodeName ? 'parents' : 'closest'](':hidden').length;
	},

	tabbable: function(element) {
		var tabIndex = $.attr(element, 'tabindex');
		return (isNaN(tabIndex) || tabIndex >= 0) && jQuery(element).is(':focusable');
	}
});


// $.widget is a factory to create jQuery plugins
// taking some boilerplate code out of the plugin code
function getter(namespace, plugin, method, args) {
	function getMethods(type) {
		var methods = $[namespace][plugin][type] || [];
		return (typeof methods == 'string' ? methods.split(/,?\s+/) : methods);
	}

	var methods = getMethods('getter');
	if (args.length == 1 && typeof args[0] == 'string') {
		methods = methods.concat(getMethods('getterSetter'));
	}
	return ($.inArray(method, methods) != -1);
}

$.widget = function(name, prototype) {
	var namespace = name.split(".")[0];
	name = name.split(".")[1];

	// create plugin method
	$.fn[name] = function(options) {
		var isMethodCall = (typeof options == 'string'),
			args = Array.prototype.slice.call(arguments, 1);

		// prevent calls to internal methods
		if (isMethodCall && options.substring(0, 1) == '_') {
			return this;
		}

		// handle getter methods
		if (isMethodCall && getter(namespace, name, options, args)) {
			var instance = $.data(this[0], name);
			return (instance ? instance[options].apply(instance, args)
				: undefined);
		}

		// handle initialization and non-getter methods
		return this.each(function() {
			var instance = $.data(this, name);

			// constructor
			(!instance && !isMethodCall &&
				$.data(this, name, new $[namespace][name](this, options))._init());

			// method call
			(instance && isMethodCall && $.isFunction(instance[options]) &&
				instance[options].apply(instance, args));
		});
	};

	// create widget constructor
	$[namespace] = $[namespace] || {};
	$[namespace][name] = function(element, options) {
		var self = this;

		this.namespace = namespace;
		this.widgetName = name;
		this.widgetEventPrefix = $[namespace][name].eventPrefix || name;
		this.widgetBaseClass = namespace + '-' + name;

		this.options = $.extend({},
			$.widget.defaults,
			$[namespace][name].defaults,
			$.metadata && $.metadata.get(element)[name],
			options);

		this.element = jQuery(element)
			.bind('setData.' + name, function(event, key, value) {
				if (event.target == element) {
					return self._setData(key, value);
				}
			})
			.bind('getData.' + name, function(event, key) {
				if (event.target == element) {
					return self._getData(key);
				}
			})
			.bind('remove', function() {
				return self.destroy();
			});
	};

	// add widget prototype
	$[namespace][name].prototype = $.extend({}, $.widget.prototype, prototype);

	// TODO: merge getter and getterSetter properties from widget prototype
	// and plugin prototype
	$[namespace][name].getterSetter = 'option';
};

$.widget.prototype = {
	_init: function() {},
	destroy: function() {
		this.element.removeData(this.widgetName)
			.removeClass(this.widgetBaseClass + '-disabled' + ' ' + this.namespace + '-state-disabled')
			.removeAttr('aria-disabled');
	},

	option: function(key, value) {
		var options = key,
			self = this;

		if (typeof key == "string") {
			if (value === undefined) {
				return this._getData(key);
			}
			options = {};
			options[key] = value;
		}

		$.each(options, function(key, value) {
			self._setData(key, value);
		});
	},
	_getData: function(key) {
		return this.options[key];
	},
	_setData: function(key, value) {
		this.options[key] = value;

		if (key == 'disabled') {
			this.element
				[value ? 'addClass' : 'removeClass'](
					this.widgetBaseClass + '-disabled' + ' ' +
					this.namespace + '-state-disabled')
				.attr("aria-disabled", value);
		}
	},

	enable: function() {
		this._setData('disabled', false);
	},
	disable: function() {
		this._setData('disabled', true);
	},

	_trigger: function(type, event, data) {
		var callback = this.options[type],
			eventName = (type == this.widgetEventPrefix
				? type : this.widgetEventPrefix + type);

		event = $.Event(event);
		event.type = eventName;

		// copy original event properties over to the new event
		// this would happen if we could call $.event.fix instead of $.Event
		// but we don't have a way to force an event to be fixed multiple times
		if (event.originalEvent) {
			for (var i = $.event.props.length, prop; i;) {
				prop = $.event.props[--i];
				event[prop] = event.originalEvent[prop];
			}
		}

		this.element.trigger(event, data);

		return !($.isFunction(callback) && callback.call(this.element[0], event, data) === false
			|| event.isDefaultPrevented());
	}
};

$.widget.defaults = {
	disabled: false
};


/** Mouse Interaction Plugin **/

$.ui.mouse = {
	_mouseInit: function() {
		var self = this;

		this.element
			.bind('mousedown.'+this.widgetName, function(event) {
				return self._mouseDown(event);
			})
			.bind('click.'+this.widgetName, function(event) {
				if(self._preventClickEvent) {
					self._preventClickEvent = false;
					event.stopImmediatePropagation();
					return false;
				}
			});

		// Prevent text selection in IE
		if ($.browser.msie) {
			this._mouseUnselectable = this.element.attr('unselectable');
			this.element.attr('unselectable', 'on');
		}

		this.started = false;
	},

	// TODO: make sure destroying one instance of mouse doesn't mess with
	// other instances of mouse
	_mouseDestroy: function() {
		this.element.unbind('.'+this.widgetName);

		// Restore text selection in IE
		($.browser.msie
			&& this.element.attr('unselectable', this._mouseUnselectable));
	},

	_mouseDown: function(event) {
		// don't let more than one widget handle mouseStart
		// TODO: figure out why we have to use originalEvent
		event.originalEvent = event.originalEvent || {};
		if (event.originalEvent.mouseHandled) { return; }

		// we may have missed mouseup (out of window)
		(this._mouseStarted && this._mouseUp(event));

		this._mouseDownEvent = event;

		var self = this,
			btnIsLeft = (event.which == 1),
			elIsCancel = (typeof this.options.cancel == "string" ? jQuery(event.target).parents().add(event.target).filter(this.options.cancel).length : false);
		if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
			return true;
		}

		this.mouseDelayMet = !this.options.delay;
		if (!this.mouseDelayMet) {
			this._mouseDelayTimer = setTimeout(function() {
				self.mouseDelayMet = true;
			}, this.options.delay);
		}

		if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
			this._mouseStarted = (this._mouseStart(event) !== false);
			if (!this._mouseStarted) {
				event.preventDefault();
				return true;
			}
		}

		// these delegates are required to keep context
		this._mouseMoveDelegate = function(event) {
			return self._mouseMove(event);
		};
		this._mouseUpDelegate = function(event) {
			return self._mouseUp(event);
		};
		jQuery(document)
			.bind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
			.bind('mouseup.'+this.widgetName, this._mouseUpDelegate);

		// preventDefault() is used to prevent the selection of text here -
		// however, in Safari, this causes select boxes not to be selectable
		// anymore, so this fix is needed
		($.browser.safari || event.preventDefault());

		event.originalEvent.mouseHandled = true;
		return true;
	},

	_mouseMove: function(event) {
		// IE mouseup check - mouseup happened when mouse was out of window
		if ($.browser.msie && !event.button) {
			return this._mouseUp(event);
		}

		if (this._mouseStarted) {
			this._mouseDrag(event);
			return event.preventDefault();
		}

		if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
			this._mouseStarted =
				(this._mouseStart(this._mouseDownEvent, event) !== false);
			(this._mouseStarted ? this._mouseDrag(event) : this._mouseUp(event));
		}

		return !this._mouseStarted;
	},

	_mouseUp: function(event) {
		jQuery(document)
			.unbind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
			.unbind('mouseup.'+this.widgetName, this._mouseUpDelegate);

		if (this._mouseStarted) {
			this._mouseStarted = false;
			this._preventClickEvent = (event.target == this._mouseDownEvent.target);
			this._mouseStop(event);
		}

		return false;
	},

	_mouseDistanceMet: function(event) {
		return (Math.max(
				Math.abs(this._mouseDownEvent.pageX - event.pageX),
				Math.abs(this._mouseDownEvent.pageY - event.pageY)
			) >= this.options.distance
		);
	},

	_mouseDelayMet: function(event) {
		return this.mouseDelayMet;
	},

	// These are placeholder methods, to be overriden by extending plugin
	_mouseStart: function(event) {},
	_mouseDrag: function(event) {},
	_mouseStop: function(event) {},
	_mouseCapture: function(event) { return true; }
};

$.ui.mouse.defaults = {
	cancel: null,
	distance: 1,
	delay: 0
};

})(jQuery);
/*
  jQuery utils - 0.7.0a
  http://code.google.com/p/jquery-utils/

  (c) Maxime Haineault <haineault@gmail.com> 
  http://haineault.com

  MIT License (http://www.opensource.org/licenses/mit-license.php

*/

(function($){
     $.extend($.expr[':'], {
        // case insensitive version of :contains
        icontains: function(a,i,m){return (a.textContent||a.innerText||jQuery(a).text()||"").toLowerCase().indexOf(m[3].toLowerCase())>=0;}
    });

    $.iterators = {
        getText:  function() { return jQuery(this).text(); },
        parseInt: function(v){ return parseInt(v, 10); }
    };

	$.extend({ 

        // Returns a range object
        // Author: Matthias Miller
        // Site:   http://blog.outofhanwell.com/2006/03/29/javascript-range-function/
        range:  function() {
            if (!arguments.length) { return []; }
            var min, max, step;
            if (arguments.length == 1) {
                min  = 0;
                max  = arguments[0]-1;
                step = 1;
            }
            else {
                // default step to 1 if it's zero or undefined
                min  = arguments[0];
                max  = arguments[1]-1;
                step = arguments[2] || 1;
            }
            // convert negative steps to positive and reverse min/max
            if (step < 0 && min >= max) {
                step *= -1;
                var tmp = min;
                min = max;
                max = tmp;
                min += ((max-min) % step);
            }
            var a = [];
            for (var i = min; i <= max; i += step) { a.push(i); }
            return a;
        },

        // Taken from ui.core.js. 
        // Why are you keeping this gem for yourself guys ? :|
        keyCode: {
            BACKSPACE: 8, CAPS_LOCK: 20, COMMA: 188, CONTROL: 17, DELETE: 46, DOWN: 40,
            END: 35, ENTER: 13, ESCAPE: 27, HOME: 36, INSERT:  45, LEFT: 37,
            NUMPAD_ADD: 107, NUMPAD_DECIMAL: 110, NUMPAD_DIVIDE: 111, NUMPAD_ENTER: 108, 
            NUMPAD_MULTIPLY: 106, NUMPAD_SUBTRACT: 109, PAGE_DOWN: 34, PAGE_UP: 33, 
            PERIOD: 190, RIGHT: 39, SHIFT: 16, SPACE: 32, TAB: 9, UP: 38
        },
        
        // Takes a keyboard event and return true if the keycode match the specified keycode
        keyIs: function(k, e) {
            return parseInt($.keyCode[k.toUpperCase()], 10) == parseInt((typeof(e) == 'number' )? e: e.keyCode, 10);
        },
        
        // Returns the key of an array
        keys: function(arr) {
            var o = [];
            for (k in arr) { o.push(k); }
            return o;
        },

        // Redirect to a specified url
        redirect: function(url) {
            window.location.href = url;
            return url;
        },

        // Stop event shorthand
        stop: function(e, preventDefault, stopPropagation) {
            if (preventDefault)  { e.preventDefault(); }
            if (stopPropagation) { e.stopPropagation(); }
            return preventDefault && false || true;
        },

        // Returns the basename of a path
        basename: function(path) {
            var t = path.split('/');
            return t[t.length] === '' && s || t.slice(0, t.length).join('/');
        },

        // Returns the filename of a path
        filename: function(path) {
            return path.split('/').pop();
        }, 

        // Returns a formated file size
        filesizeformat: function(bytes, suffixes){
            var b = parseInt(bytes, 10);
            var s = suffixes || ['byte', 'bytes', 'KB', 'MB', 'GB'];
            if (isNaN(b) || b === 0) { return '0 ' + s[0]; }
            if (b == 1)              { return '1 ' + s[0]; }
            if (b < 1024)            { return  b.toFixed(2) + ' ' + s[1]; }
            if (b < 1048576)         { return (b / 1024).toFixed(2) + ' ' + s[2]; }
            if (b < 1073741824)      { return (b / 1048576).toFixed(2) + ' '+ s[3]; }
            else                     { return (b / 1073741824).toFixed(2) + ' '+ s[4]; }
        },

        fileExtension: function(s) {
            var tokens = s.split('.');
            return tokens[tokens.length-1] || false;
        },
        
        // Returns true if an object is a String
        isString: function(o) {
            return typeof(o) == 'string' && true || false;
        },
        
        // Returns true if an object is a RegExp
		isRegExp: function(o) {
			return o && o.constructor.toString().indexOf('RegExp()') != -1 || false;
		},

        isObject: function(o) {
            return (typeof(o) == 'object');
        },
        
        // Convert input to currency (two decimal fixed number)
		toCurrency: function(i) {
			i = parseFloat(i, 10).toFixed(2);
			return (i=='NaN') ? '0.00' : i;
		},

        /*-------------------------------------------------------------------- 
         * javascript method: "pxToEm"
         * by:
           Scott Jehl (scott@filamentgroup.com) 
           Maggie Wachs (maggie@filamentgroup.com)
           http://www.filamentgroup.com
         *
         * Copyright (c) 2008 Filament Group
         * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
         *
         * Description: pxToEm converts a pixel value to ems depending on inherited font size.  
         * Article: http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/
         * Demo: http://www.filamentgroup.com/examples/pxToEm/	 	
         *							
         * Options:  	 								
                scope: string or jQuery selector for font-size scoping
                reverse: Boolean, true reverses the conversion to em-px
         * Dependencies: jQuery library						  
         * Usage Example: myPixelValue.pxToEm(); or myPixelValue.pxToEm({'scope':'#navigation', reverse: true});
         *
         * Version: 2.1, 18.12.2008
         * Changelog:
         *		08.02.2007 initial Version 1.0
         *		08.01.2008 - fixed font-size calculation for IE
         *		18.12.2008 - removed native object prototyping to stay in jQuery's spirit, jsLinted (Maxime Haineault <haineault@gmail.com>)
        --------------------------------------------------------------------*/

        pxToEm: function(i, settings){
            //set defaults
            settings = jQuery.extend({
                scope: 'body',
                reverse: false
            }, settings);
            
            var pxVal = (i === '') ? 0 : parseFloat(i);
            var scopeVal;
            var getWindowWidth = function(){
                var de = document.documentElement;
                return self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
            };	
            
            /* When a percentage-based font-size is set on the body, IE returns that percent of the window width as the font-size. 
                For example, if the body font-size is 62.5% and the window width is 1000px, IE will return 625px as the font-size. 	
                When this happens, we calculate the correct body font-size (%) and multiply it by 16 (the standard browser font size) 
                to get an accurate em value. */
                        
            if (settings.scope == 'body' && $.browser.msie && (parseFloat(jQuery('body').css('font-size')) / getWindowWidth()).toFixed(1) > 0.0) {
                var calcFontSize = function(){		
                    return (parseFloat(jQuery('body').css('font-size'))/getWindowWidth()).toFixed(3) * 16;
                };
                scopeVal = calcFontSize();
            }
            else { scopeVal = parseFloat(jQuery(settings.scope).css("font-size")); }
                    
            var result = (settings.reverse === true) ? (pxVal * scopeVal).toFixed(2) + 'px' : (pxVal / scopeVal).toFixed(2) + 'em';
            return result;
        }
	});

	$.extend($.fn, { 
        type: function() {
            try { return jQuery(this).get(0).nodeName.toLowerCase(); }
            catch(e) { return false; }
        },
        // Select a text range in a textarea
        selectRange: function(start, end){
            // use only the first one since only one input can be focused
            if (jQuery(this).get(0).createTextRange) {
                var range = jQuery(this).get(0).createTextRange();
                range.collapse(true);
                range.moveEnd('character',   end);
                range.moveStart('character', start);
                range.select();
            }
            else if (jQuery(this).get(0).setSelectionRange) {
                jQuery(this).bind('focus', function(e){
                    e.preventDefault();
                }).get(0).setSelectionRange(start, end);
            }
            return jQuery(this);
        },

        /*-------------------------------------------------------------------- 
         * JQuery Plugin: "EqualHeights"
         * by:	Scott Jehl, Todd Parker, Maggie Costello Wachs (http://www.filamentgroup.com)
         *
         * Copyright (c) 2008 Filament Group
         * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
         *
         * Description: Compares the heights or widths of the top-level children of a provided element 
                and sets their min-height to the tallest height (or width to widest width). Sets in em units 
                by default if pxToEm() method is available.
         * Dependencies: jQuery library, pxToEm method	(article: 
                http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/)							  
         * Usage Example: jQuery(element).equalHeights();
                Optional: to set min-height in px, pass a true argument: jQuery(element).equalHeights(true);
         * Version: 2.1, 18.12.2008
         *
         * Note: Changed pxToEm call to call $.pxToEm instead, jsLinted (Maxime Haineault <haineault@gmail.com>)
        --------------------------------------------------------------------*/

        equalHeights: function(px){
            jQuery(this).each(function(){
                var currentTallest = 0;
                jQuery(this).children().each(function(i){
                    if (jQuery(this).height() > currentTallest) { currentTallest = jQuery(this).height(); }
                });
                if (!px || !$.pxToEm) { currentTallest = $.pxToEm(currentTallest); } //use ems unless px is specified
                // for ie6, set height since min-height isn't supported
                if ($.browser.msie && $.browser.version == 6.0) { jQuery(this).children().css({'height': currentTallest}); }
                jQuery(this).children().css({'min-height': currentTallest}); 
            });
            return this;
        },

        // Copyright (c) 2009 James Padolsey
        // http://james.padolsey.com/javascript/jquery-delay-plugin/
        delay: function(time, callback){
            jQuery.fx.step.delay = function(){};
            return this.animate({delay:1}, time, callback);
        }        
	});
})(jQuery);
/*
  jQuery strings - 0.3
  http://code.google.com/p/jquery-utils/
  
  (c) Maxime Haineault <haineault@gmail.com>
  http://haineault.com   

  MIT License (http://www.opensource.org/licenses/mit-license.php)

  Implementation of Python3K advanced string formatting
  http://www.python.org/dev/peps/pep-3101/

  Documentation: http://code.google.com/p/jquery-utils/wiki/StringFormat
  
*/
(function($){
    var strings = {
        strConversion: {
            // tries to translate any objects type into string gracefully
            __repr: function(i){
                switch(this.__getType(i)) {
                    case 'array':case 'date':case 'number':
                        return i.toString();
                    case 'object': 
                        var o = [];
                        for (x=0; x<i.length; i++) { o.push(i+': '+ this.__repr(i[x])); }
                        return o.join(', ');
                    case 'string': 
                        return i;
                    default: 
                        return i;
                }
            },
            // like typeof but less vague
            __getType: function(i) {
                if (!i || !i.constructor) { return typeof(i); }
                var match = i.constructor.toString().match(/Array|Number|String|Object|Date/);
                return match && match[0].toLowerCase() || typeof(i);
            },
            //+ Jonas Raoni Soares Silva
            //@ http://jsfromhell.com/string/pad [v1.0]
            __pad: function(str, l, s, t){
                var p = s || ' ';
                var o = str;
                if (l - str.length > 0) {
                    o = new Array(Math.ceil(l / p.length)).join(p).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2)) + str + p.substr(0, l - t);
                }
                return o;
            },
            __getInput: function(arg, args) {
                 var key = arg.getKey();
                switch(this.__getType(args)){
                    case 'object': // Thanks to Jonathan Works for the patch
                        var keys = key.split('.');
                        var obj = args;
                        for(var subkey = 0; subkey < keys.length; subkey++){
                            obj = obj[keys[subkey]];
                        }
                        if (typeof(obj) != 'undefined') {
                            if (strings.strConversion.__getType(obj) == 'array') {
                                return arg.getFormat().match(/\.\*/) && obj[1] || obj;
                            }
                            return obj;
                        }
                        else {
                            // TODO: try by numerical index                    
                        }
                    break;
                    case 'array': 
                        key = parseInt(key, 10);
                        if (arg.getFormat().match(/\.\*/) && typeof args[key+1] != 'undefined') { return args[key+1]; }
                        else if (typeof args[key] != 'undefined') { return args[key]; }
                        else { return key; }
                    break;
                }
                return '{'+key+'}';
            },
            __formatToken: function(token, args) {
                var arg   = new Argument(token, args);
                return strings.strConversion[arg.getFormat().slice(-1)](this.__getInput(arg, args), arg);
            },

            // Signed integer decimal.
            d: function(input, arg){
                var o = parseInt(input, 10); // enforce base 10
                var p = arg.getPaddingLength();
                if (p) { return this.__pad(o.toString(), p, arg.getPaddingString(), 0); }
                else   { return o; }
            },
            // Signed integer decimal.
            i: function(input, args){ 
                return this.d(input, args);
            },
            // Unsigned octal
            o: function(input, arg){ 
                var o = input.toString(8);
                if (arg.isAlternate()) { o = this.__pad(o, o.length+1, '0', 0); }
                return this.__pad(o, arg.getPaddingLength(), arg.getPaddingString(), 0);
            },
            // Unsigned decimal
            u: function(input, args) {
                return Math.abs(this.d(input, args));
            },
            // Unsigned hexadecimal (lowercase)
            x: function(input, arg){
                var o = parseInt(input, 10).toString(16);
                o = this.__pad(o, arg.getPaddingLength(), arg.getPaddingString(),0);
                return arg.isAlternate() ? '0x'+o : o;
            },
            // Unsigned hexadecimal (uppercase)
            X: function(input, arg){
                return this.x(input, arg).toUpperCase();
            },
            // Floating point exponential format (lowercase)
            e: function(input, arg){
                return parseFloat(input, 10).toExponential(arg.getPrecision());
            },
            // Floating point exponential format (uppercase)
            E: function(input, arg){
                return this.e(input, arg).toUpperCase();
            },
            // Floating point decimal format
            f: function(input, arg){
                return this.__pad(parseFloat(input, 10).toFixed(arg.getPrecision()), arg.getPaddingLength(), arg.getPaddingString(),0);
            },
            // Floating point decimal format (alias)
            F: function(input, args){
                return this.f(input, args);
            },
            // Floating point format. Uses exponential format if exponent is greater than -4 or less than precision, decimal format otherwise
            g: function(input, arg){
                var o = parseFloat(input, 10);
                return (o.toString().length > 6) ? Math.round(o.toExponential(arg.getPrecision())): o;
            },
            // Floating point format. Uses exponential format if exponent is greater than -4 or less than precision, decimal format otherwise
            G: function(input, args){
                return this.g(input, args);
            },
            // Single character (accepts integer or single character string). 	
            c: function(input, args) {
                var match = input.match(/\w|\d/);
                return match && match[0] || '';
            },
            // String (converts any JavaScript object to anotated format)
            r: function(input, args) {
                return this.__repr(input);
            },
            // String (converts any JavaScript object using object.toString())
            s: function(input, args) {
                return input.toString && input.toString() || ''+input;
            }
        },

        format: function(str, args) {
            var end    = 0;
            var start  = 0;
            var match  = false;
            var buffer = [];
            var token  = '';
            var tmp    = (str||'').split('');
            for(start=0; start < tmp.length; start++) {
                if (tmp[start] == '{' && tmp[start+1] !='{') {
                    end   = str.indexOf('}', start);
                    token = tmp.slice(start+1, end).join('');
                    if (tmp[start-1] != '{' && tmp[end+1] != '}') {
                        var tokenArgs = (typeof arguments[1] != 'object')? arguments2Array(arguments, 2): args || [];
                        buffer.push(strings.strConversion.__formatToken(token, tokenArgs));
                    }
                    else {
                        buffer.push(token);
                    }
                }
                else if (start > end || buffer.length < 1) { buffer.push(tmp[start]); }
            }
            return (buffer.length > 1)? buffer.join(''): buffer[0];
        },

        calc: function(str, args) {
            return eval(format(str, args));
        },

        repeat: function(s, n) { 
            return new Array(n+1).join(s); 
        },

        UTF8encode: function(s) { 
            return unescape(encodeURIComponent(s)); 
        },

        UTF8decode: function(s) { 
            return decodeURIComponent(escape(s)); 
        },

        tpl: function() {
            var out = '';
            var render = true;
            // Set
            // $.tpl('ui.test', ['<span>', helloWorld ,'</span>']);
            if (arguments.length == 2 && $.isArray(arguments[1])) {
                this[arguments[0]] = arguments[1].join('');
                return jQuery(this[arguments[0]]);
            }
            // $.tpl('ui.test', '<span>hello world</span>');
            if (arguments.length == 2 && $.isString(arguments[1])) {
                this[arguments[0]] = arguments[1];
                return jQuery(this[arguments[0]]);
            }
            // Call
            // $.tpl('ui.test');
            if (arguments.length == 1) {
                return jQuery(this[arguments[0]]);
            }
            // $.tpl('ui.test', false);
            if (arguments.length == 2 && arguments[1] == false) {
                return this[arguments[0]];
            }
            // $.tpl('ui.test', {value:blah});
            if (arguments.length == 2 && $.isObject(arguments[1])) {
                return jQuery($.format(this[arguments[0]], arguments[1]));
            }
            // $.tpl('ui.test', {value:blah}, false);
            if (arguments.length == 3 && $.isObject(arguments[1])) {
                return (arguments[2] == true) 
                    ? $.format(this[arguments[0]], arguments[1])
                    : jQuery($.format(this[arguments[0]], arguments[1]));
            }
        }
    };

    var Argument = function(arg, args) {
        this.__arg  = arg;
        this.__args = args;
        this.__max_precision = parseFloat('1.'+ (new Array(32)).join('1'), 10).toString().length-3;
        this.__def_precision = 6;
        this.getString = function(){
            return this.__arg;
        };
        this.getKey = function(){
            return this.__arg.split(':')[0];
        };
        this.getFormat = function(){
            var match = this.getString().split(':');
            return (match && match[1])? match[1]: 's';
        };
        this.getPrecision = function(){
            var match = this.getFormat().match(/\.(\d+|\*)/g);
            if (!match) { return this.__def_precision; }
            else {
                match = match[0].slice(1);
                if (match != '*') { return parseInt(match, 10); }
                else if(strings.strConversion.__getType(this.__args) == 'array') {
                    return this.__args[1] && this.__args[0] || this.__def_precision;
                }
                else if(strings.strConversion.__getType(this.__args) == 'object') {
                    return this.__args[this.getKey()] && this.__args[this.getKey()][0] || this.__def_precision;
                }
                else { return this.__def_precision; }
            }
        };
        this.getPaddingLength = function(){
            var match = false;
            if (this.isAlternate()) {
                match = this.getString().match(/0?#0?(\d+)/);
                if (match && match[1]) { return parseInt(match[1], 10); }
            }
            match = this.getString().match(/(0|\.)(\d+|\*)/g);
            return match && parseInt(match[0].slice(1), 10) || 0;
        };
        this.getPaddingString = function(){
            var o = '';
            if (this.isAlternate()) { o = ' '; }
            // 0 take precedence on alternate format
            if (this.getFormat().match(/#0|0#|^0|\.\d+/)) { o = '0'; }
            return o;
        };
        this.getFlags = function(){
            var match = this.getString().matc(/^(0|\#|\-|\+|\s)+/);
            return match && match[0].split('') || [];
        };
        this.isAlternate = function() {
            return !!this.getFormat().match(/^0?#/);
        };
    };

    var arguments2Array = function(args, shift) {
        var o = [];
        for (l=args.length, x=(shift || 0)-1; x<l;x++) { o.push(args[x]); }
        return o;
    };
    $.extend(strings);
})(jQuery);
/*
  jQuery ui.timepickr - 0.7.0a
  http://code.google.com/p/jquery-utils/

  (c) Maxime Haineault <haineault@gmail.com> 
  http://haineault.com

  MIT License (http://www.opensource.org/licenses/mit-license.php

  Note: if you want the original experimental plugin checkout the rev 224 

  Dependencies
  ------------
  - jquery.utils.js
  - jquery.strings.js
  - jquery.ui.js
  
*/

(function($) {

$.tpl('timepickr.menu',   '<div class="ui-helper-reset ui-timepickr ui-widget" />');
$.tpl('timepickr.row',    '<ol class="ui-timepickr-row ui-helper-clearfix" />');
$.tpl('timepickr.button', '<li class="{className:s}"><span class="ui-state-default">{label:s}</span></li>');

$.widget('ui.timepickr', {
    plugins: {},
    _init: function() {
        this._dom = {
            menu: $.tpl('timepickr.menu'),
            row:  $.tpl('timepickr.menu')
        };
        this._trigger('initialize');
        this._trigger('initialized');
    },

    _trigger: function(type, e, ui) {
        var ui = ui || this;
        $.ui.plugin.call(this, type, [e, ui]);
        return $.widget.prototype._trigger.call(this, type, e, ui);
    },

    _createButton: function(i, format, className) {
        var o  = format && $.format(format, i) || i;
        var cn = className && 'ui-timepickr-button '+ className || 'ui-timepickr-button';
        return $.tpl('timepickr.button', {className: cn, label: o}).data('id', i)
                .bind('mouseover', function(){
                    jQuery(this).siblings().find('span')
                        .removeClass('ui-state-hover').end().end()
                        .find('span').addClass('ui-state-hover');
                });

    },

    _addRow: function(range, format, className, insertAfter) {
        var ui  = this;
        var btn = false;
        var row = $.tpl('timepickr.row').bind('mouseover', function(){
            jQuery(this).next().show();
        });
        $.each(range, function(idx, val){
            ui._createButton(val, format || false).appendTo(row);
        });
        if (className) {
            jQuery(row).addClass(className);
        }
        if (this.options.corners) {
             row.find('span').addClass('ui-corner-'+ this.options.corners);
        }
        if (insertAfter) {
            row.insertAfter(insertAfter);
        }
        else {
            ui._dom.menu.append(row);
        }
        return row;
    },

    _setVal: function(val) {
        val = val || this._getVal();
        this.element.data('timepickr.initialValue', val);
        this.element.val(this._formatVal(val));
        if(this._dom.menu.is(':hidden')) {
            this.element.trigger('change');
        }
    },

    _getVal: function() {
        var ols = this._dom.menu.find('ol');
        function get(unit) {
            var u = ols.filter('.'+unit).find('.ui-state-hover:first').text();
            return u || ols.filter('.'+unit+'li:first span').text();
        }
        return {
            h: get('hours'),
            m: get('minutes'),
            s: get('seconds'),
            a: get('prefix'),
            z: get('suffix'),
            f: this.options['format'+ this.c],
            c: this.c
        };
    },

    _formatVal: function(ival) {
        var val = ival || this._getVal();
        val.c = this.options.convention;
        val.f = val.c === 12 && this.options.format12 || this.options.format24;
        return (new Time(val)).getTime();
    },

    blur: function() {
        return this.element.blur();      
    },

    focus: function() {
        return this.element.focus();      
    },
    show: function() {
        this._trigger('show');
        this.element.trigger(this.options.trigger);
    },
    hide: function() {
        this._trigger('hide');
        this._dom.menu.hide();
    }

});

// These properties are shared accross every instances of timepickr 
$.extend($.ui.timepickr, {
    version:     '0.7.0a',
    //eventPrefix: '',
    //getter:      '',
    defaults:    {
        convention:  24, // 24, 12
        trigger:     'mouseover',
        format12:    '{h:02.d}:{m:02.d} {suffix:s}',
        format24:    '{h:02.d}:{m:02.d}',
        hours:       true,
        prefix:      ['am', 'pm'],
        suffix:      ['am', 'pm'],
        prefixVal:   false,
        suffixVal:   true,
        rangeHour12: $.range(1, 13),
        rangeHour24: [$.range(0, 12), $.range(12, 24)],
        rangeMin:    $.range(0, 60, 15),
        rangeSec:    $.range(0, 60, 15),
        corners:     'all',
        // plugins
        core:        true,
        minutes:     true,
        seconds:     false,
        val:         false,
        updateLive:  true,
        resetOnBlur: true,
        keyboardnav: true,
        handle:      false,
        handleEvent: 'click'
    }
});

$.ui.plugin.add('timepickr', 'core', {
    initialized: function(e, ui) {
        var menu = ui._dom.menu;
        var pos  = ui.element.position();

        menu.insertAfter(ui.element).css('left', pos.left);

        if (!$.boxModel) { // IE alignement fix
            menu.css('margin-top', ui.element.height() + 8);
        }
        
        ui.element
            .bind(ui.options.trigger, function() {
                ui._dom.menu.show();
                ui._dom.menu.find('ol:first').show();
                ui._trigger('focus');
                if (ui.options.trigger != 'focus') {
                    ui.element.focus();
                }
                ui._trigger('focus');
            })
            .bind('blur', function() {
                ui.hide();
                ui._trigger('blur');
            });

        menu.find('li').bind('mouseover.timepickr', function() {
            ui._trigger('refresh');
        });
    },
    refresh: function(e, ui) {
        // Realign each menu layers
        ui._dom.menu.find('ol').each(function(){
            var p = jQuery(this).prev('ol');
            try { // .. to not fuckup IE
                jQuery(this).css('left', p.position().left + p.find('.ui-state-hover').position().left);
            } catch(e) {};
        });
    }
});

$.ui.plugin.add('timepickr', 'hours', {
    initialize: function(e, ui) {
        if (ui.options.convention === 24) {
            // prefix is required in 24h mode
            ui._dom.prefix = ui._addRow(ui.options.prefix, false, 'prefix'); 

            // split-range
            if ($.isArray(ui.options.rangeHour24[0])) {
                var range = [];
                $.merge(range, ui.options.rangeHour24[0]);
                $.merge(range, ui.options.rangeHour24[1]);
                ui._dom.hours = ui._addRow(range, '{0:0.2d}', 'hours');
                ui._dom.hours.find('li').slice(ui.options.rangeHour24[0].length, -1).hide();
                var lis   = ui._dom.hours.find('li'); 

                var show = [
                    function() {
                        lis.slice(ui.options.rangeHour24[0].length).hide().end()
                           .slice(0, ui.options.rangeHour24[0].length).show()
                           .filter(':visible:first').trigger('mouseover');

                    },
                    function() {
                        lis.slice(0, ui.options.rangeHour24[0].length).hide().end()
                           .slice(ui.options.rangeHour24[0].length).show()
                           .filter(':visible:first').trigger('mouseover');
                    }
                ];

                ui._dom.prefix.find('li').bind('mouseover.timepickr', function(){
                    var index = ui._dom.menu.find('.prefix li').index(this);
                    show[index].call();
                });
            }
            else {
                ui._dom.hours = ui._addRow(ui.options.rangeHour24, '{0:0.2d}', 'hours');
                ui._dom.hours.find('li').slice(12, -1).hide();
            }
        }
        else {
            ui._dom.hours  = ui._addRow(ui.options.rangeHour12, '{0:0.2d}', 'hours');
            // suffix is required in 12h mode
            ui._dom.suffix = ui._addRow(ui.options.suffix, false, 'suffix'); 
        }
    }});

$.ui.plugin.add('timepickr', 'minutes', {
    initialize: function(e, ui) {
        var p = ui._dom.hours && ui._dom.hours || false;
        ui._dom.minutes = ui._addRow(ui.options.rangeMin, '{0:0.2d}', 'minutes', p);
    }
});

$.ui.plugin.add('timepickr', 'seconds', {
    initialize: function(e, ui) {
        var p = ui._dom.minutes && ui._dom.minutes || false;
        ui._dom.seconds = ui._addRow(ui.options.rangeSec, '{0:0.2d}', 'seconds', p);
    }
});

$.ui.plugin.add('timepickr', 'val', {
    initialized: function(e, ui) {
        ui._setVal(ui.options.val);
    }
});

$.ui.plugin.add('timepickr', 'updateLive', {
    refresh: function(e, ui) {
        ui._setVal();
    }
});

$.ui.plugin.add('timepickr', 'resetOnBlur', {
    initialized: function(e, ui) {
        ui.element.data('timepickr.initialValue', ui._getVal());
        ui._dom.menu.find('li > span').bind('mousedown.timepickr', function(){
            ui.element.data('timepickr.initialValue', ui._getVal()); 
        });
    },
    blur: function(e, ui) {
        ui._setVal(ui.element.data('timepickr.initialValue'));
    }
});

$.ui.plugin.add('timepickr', 'handle', {
    initialized: function(e, ui) {
        jQuery(ui.options.handle).bind(ui.options.handleEvent + '.timepickr', function(){
            ui.show();
        });
    }
});

$.ui.plugin.add('timepickr', 'keyboardnav', {
    initialized: function(e, ui) {
        ui.element
            .bind('keydown', function(e) {
                if ($.keyIs('enter', e)) {
                    ui._setVal();
                    ui.blur();
                }
                else if ($.keyIs('escape', e)) {
                    ui.blur();
                }
            });
    }
});

var Time = function() { // arguments: h, m, s, c, z, f || time string
    if (!(this instanceof arguments.callee)) {
        throw Error("Constructor called as a function");
    }
    // arguments as literal object
    if (arguments.length == 1 && $.isObject(arguments[0])) {
        this.h = arguments[0].h || 0;
        this.m = arguments[0].m || 0;
        this.s = arguments[0].s || 0;
        this.c = arguments[0].c && ($.inArray(arguments[0].c, [12, 24]) >= 0) && arguments[0].c || 24;
        this.f = arguments[0].f || ((this.c == 12) && '{h:02.d}:{m:02.d} {z:02.d}' || '{h:02.d}:{m:02.d}');
        this.z = arguments[0].z || 'am';
    }
    // arguments as string
    else if (arguments.length < 4 && $.isString(arguments[1])) {
        this.c = arguments[2] && ($.inArray(arguments[0], [12, 24]) >= 0) && arguments[0] || 24;
        this.f = arguments[3] || ((this.c == 12) && '{h:02.d}:{m:02.d} {z:02.d}' || '{h:02.d}:{m:02.d}');
        this.z = arguments[4] || 'am';
        
        this.h = arguments[1] || 0; // parse
        this.m = arguments[1] || 0; // parse
        this.s = arguments[1] || 0; // parse
    }
    // no arguments (now)
    else if (arguments.length === 0) {
        // now
    }
    // standards arguments
    else {
        this.h = arguments[0] || 0;
        this.m = arguments[1] || 0;
        this.s = arguments[2] || 0;
        this.c = arguments[3] && ($.inArray(arguments[3], [12, 24]) >= 0) && arguments[3] || 24;
        this.f = this.f || ((this.c == 12) && '{h:02.d}:{m:02.d} {z:02.d}' || '{h:02.d}:{m:02.d}');
        this.z = 'am';
    }
    return this;
};

Time.prototype.get        = function(p, f, u)    { return u && this.h || $.format(f, this.h); };
Time.prototype.getHours   = function(unformated) { return this.get('h', '{0:02.d}', unformated); };
Time.prototype.getMinutes = function(unformated) { return this.get('m', '{0:02.d}', unformated); };
Time.prototype.getSeconds = function(unformated) { return this.get('s', '{0:02.d}', unformated); };
Time.prototype.setFormat  = function(format)     { return this.f = format; };
Time.prototype.getObject  = function()           { return { h: this.h, m: this.m, s: this.s, c: this.c, f: this.f, z: this.z }; };
Time.prototype.getTime    = function()           { return $.format(this.f, {h: this.h, m: this.m, z: this.z}); };
Time.prototype.parse      = function(str) { 
    // 12h formats
    if (this.c === 12) {
        // Supported formats: (can't find any *official* standards for 12h..)
        //  - [hh]:[mm]:[ss] [zz] | [hh]:[mm] [zz] | [hh] [zz] 
        //  - [hh]:[mm]:[ss] [z.z.] | [hh]:[mm] [z.z.] | [hh] [z.z.]
        this.tokens = str.split(/\s|:/);    
        this.h = this.tokens[0] || 0;
        this.m = this.tokens[1] || 0;
        this.s = this.tokens[2] || 0;
        this.z = this.tokens[3] || '';
        return this.getObject();
    }
    // 24h formats
    else { 
        // Supported formats:
        //  - ISO 8601: [hh][mm][ss] | [hh][mm] | [hh]  
        //  - ISO 8601 extended: [hh]:[mm]:[ss] | [hh]:[mm] | [hh]
        this.tokens = /:/.test(str) && str.split(/:/) || str.match(/[0-9]{2}/g);
        this.h = this.tokens[0] || 0;
        this.m = this.tokens[1] || 0;
        this.s = this.tokens[2] || 0;
        this.z = this.tokens[3] || '';
        return this.getObject();
    }
};

})(jQuery);
/*
  jQuery utils - 0.7.0a
  http://code.google.com/p/jquery-utils/

  (c) Maxime Haineault <haineault@gmail.com> 
  http://haineault.com

  MIT License (http://www.opensource.org/licenses/mit-license.php

*/

(function($){
     $.extend($.expr[':'], {
        // case insensitive version of :contains
        icontains: function(a,i,m){return (a.textContent||a.innerText||jQuery(a).text()||"").toLowerCase().indexOf(m[3].toLowerCase())>=0;}
    });

    $.iterators = {
        getText:  function() { return jQuery(this).text(); },
        parseInt: function(v){ return parseInt(v, 10); }
    };

	$.extend({ 

        // Returns a range object
        // Author: Matthias Miller
        // Site:   http://blog.outofhanwell.com/2006/03/29/javascript-range-function/
        range:  function() {
            if (!arguments.length) { return []; }
            var min, max, step;
            if (arguments.length == 1) {
                min  = 0;
                max  = arguments[0]-1;
                step = 1;
            }
            else {
                // default step to 1 if it's zero or undefined
                min  = arguments[0];
                max  = arguments[1]-1;
                step = arguments[2] || 1;
            }
            // convert negative steps to positive and reverse min/max
            if (step < 0 && min >= max) {
                step *= -1;
                var tmp = min;
                min = max;
                max = tmp;
                min += ((max-min) % step);
            }
            var a = [];
            for (var i = min; i <= max; i += step) { a.push(i); }
            return a;
        },

        // Taken from ui.core.js. 
        // Why are you keeping this gem for yourself guys ? :|
        keyCode: {
            BACKSPACE: 8, CAPS_LOCK: 20, COMMA: 188, CONTROL: 17, DELETE: 46, DOWN: 40,
            END: 35, ENTER: 13, ESCAPE: 27, HOME: 36, INSERT:  45, LEFT: 37,
            NUMPAD_ADD: 107, NUMPAD_DECIMAL: 110, NUMPAD_DIVIDE: 111, NUMPAD_ENTER: 108, 
            NUMPAD_MULTIPLY: 106, NUMPAD_SUBTRACT: 109, PAGE_DOWN: 34, PAGE_UP: 33, 
            PERIOD: 190, RIGHT: 39, SHIFT: 16, SPACE: 32, TAB: 9, UP: 38
        },
        
        // Takes a keyboard event and return true if the keycode match the specified keycode
        keyIs: function(k, e) {
            return parseInt($.keyCode[k.toUpperCase()], 10) == parseInt((typeof(e) == 'number' )? e: e.keyCode, 10);
        },
        
        // Returns the key of an array
        keys: function(arr) {
            var o = [];
            for (k in arr) { o.push(k); }
            return o;
        },

        // Redirect to a specified url
        redirect: function(url) {
            window.location.href = url;
            return url;
        },

        // Stop event shorthand
        stop: function(e, preventDefault, stopPropagation) {
            if (preventDefault)  { e.preventDefault(); }
            if (stopPropagation) { e.stopPropagation(); }
            return preventDefault && false || true;
        },

        // Returns the basename of a path
        basename: function(path) {
            var t = path.split('/');
            return t[t.length] === '' && s || t.slice(0, t.length).join('/');
        },

        // Returns the filename of a path
        filename: function(path) {
            return path.split('/').pop();
        }, 

        // Returns a formated file size
        filesizeformat: function(bytes, suffixes){
            var b = parseInt(bytes, 10);
            var s = suffixes || ['byte', 'bytes', 'KB', 'MB', 'GB'];
            if (isNaN(b) || b === 0) { return '0 ' + s[0]; }
            if (b == 1)              { return '1 ' + s[0]; }
            if (b < 1024)            { return  b.toFixed(2) + ' ' + s[1]; }
            if (b < 1048576)         { return (b / 1024).toFixed(2) + ' ' + s[2]; }
            if (b < 1073741824)      { return (b / 1048576).toFixed(2) + ' '+ s[3]; }
            else                     { return (b / 1073741824).toFixed(2) + ' '+ s[4]; }
        },

        fileExtension: function(s) {
            var tokens = s.split('.');
            return tokens[tokens.length-1] || false;
        },
        
        // Returns true if an object is a String
        isString: function(o) {
            return typeof(o) == 'string' && true || false;
        },
        
        // Returns true if an object is a RegExp
		isRegExp: function(o) {
			return o && o.constructor.toString().indexOf('RegExp()') != -1 || false;
		},

        isObject: function(o) {
            return (typeof(o) == 'object');
        },
        
        // Convert input to currency (two decimal fixed number)
		toCurrency: function(i) {
			i = parseFloat(i, 10).toFixed(2);
			return (i=='NaN') ? '0.00' : i;
		},

        /*-------------------------------------------------------------------- 
         * javascript method: "pxToEm"
         * by:
           Scott Jehl (scott@filamentgroup.com) 
           Maggie Wachs (maggie@filamentgroup.com)
           http://www.filamentgroup.com
         *
         * Copyright (c) 2008 Filament Group
         * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
         *
         * Description: pxToEm converts a pixel value to ems depending on inherited font size.  
         * Article: http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/
         * Demo: http://www.filamentgroup.com/examples/pxToEm/	 	
         *							
         * Options:  	 								
                scope: string or jQuery selector for font-size scoping
                reverse: Boolean, true reverses the conversion to em-px
         * Dependencies: jQuery library						  
         * Usage Example: myPixelValue.pxToEm(); or myPixelValue.pxToEm({'scope':'#navigation', reverse: true});
         *
         * Version: 2.1, 18.12.2008
         * Changelog:
         *		08.02.2007 initial Version 1.0
         *		08.01.2008 - fixed font-size calculation for IE
         *		18.12.2008 - removed native object prototyping to stay in jQuery's spirit, jsLinted (Maxime Haineault <haineault@gmail.com>)
        --------------------------------------------------------------------*/

        pxToEm: function(i, settings){
            //set defaults
            settings = jQuery.extend({
                scope: 'body',
                reverse: false
            }, settings);
            
            var pxVal = (i === '') ? 0 : parseFloat(i);
            var scopeVal;
            var getWindowWidth = function(){
                var de = document.documentElement;
                return self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
            };	
            
            /* When a percentage-based font-size is set on the body, IE returns that percent of the window width as the font-size. 
                For example, if the body font-size is 62.5% and the window width is 1000px, IE will return 625px as the font-size. 	
                When this happens, we calculate the correct body font-size (%) and multiply it by 16 (the standard browser font size) 
                to get an accurate em value. */
                        
            if (settings.scope == 'body' && $.browser.msie && (parseFloat(jQuery('body').css('font-size')) / getWindowWidth()).toFixed(1) > 0.0) {
                var calcFontSize = function(){		
                    return (parseFloat(jQuery('body').css('font-size'))/getWindowWidth()).toFixed(3) * 16;
                };
                scopeVal = calcFontSize();
            }
            else { scopeVal = parseFloat(jQuery(settings.scope).css("font-size")); }
                    
            var result = (settings.reverse === true) ? (pxVal * scopeVal).toFixed(2) + 'px' : (pxVal / scopeVal).toFixed(2) + 'em';
            return result;
        }
	});

	$.extend($.fn, { 
        type: function() {
            try { return jQuery(this).get(0).nodeName.toLowerCase(); }
            catch(e) { return false; }
        },
        // Select a text range in a textarea
        selectRange: function(start, end){
            // use only the first one since only one input can be focused
            if (jQuery(this).get(0).createTextRange) {
                var range = jQuery(this).get(0).createTextRange();
                range.collapse(true);
                range.moveEnd('character',   end);
                range.moveStart('character', start);
                range.select();
            }
            else if (jQuery(this).get(0).setSelectionRange) {
                jQuery(this).bind('focus', function(e){
                    e.preventDefault();
                }).get(0).setSelectionRange(start, end);
            }
            return jQuery(this);
        },

        /*-------------------------------------------------------------------- 
         * JQuery Plugin: "EqualHeights"
         * by:	Scott Jehl, Todd Parker, Maggie Costello Wachs (http://www.filamentgroup.com)
         *
         * Copyright (c) 2008 Filament Group
         * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
         *
         * Description: Compares the heights or widths of the top-level children of a provided element 
                and sets their min-height to the tallest height (or width to widest width). Sets in em units 
                by default if pxToEm() method is available.
         * Dependencies: jQuery library, pxToEm method	(article: 
                http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/)							  
         * Usage Example: jQuery(element).equalHeights();
                Optional: to set min-height in px, pass a true argument: jQuery(element).equalHeights(true);
         * Version: 2.1, 18.12.2008
         *
         * Note: Changed pxToEm call to call $.pxToEm instead, jsLinted (Maxime Haineault <haineault@gmail.com>)
        --------------------------------------------------------------------*/

        equalHeights: function(px){
            jQuery(this).each(function(){
                var currentTallest = 0;
                jQuery(this).children().each(function(i){
                    if (jQuery(this).height() > currentTallest) { currentTallest = jQuery(this).height(); }
                });
                if (!px || !$.pxToEm) { currentTallest = $.pxToEm(currentTallest); } //use ems unless px is specified
                // for ie6, set height since min-height isn't supported
                if ($.browser.msie && $.browser.version == 6.0) { jQuery(this).children().css({'height': currentTallest}); }
                jQuery(this).children().css({'min-height': currentTallest}); 
            });
            return this;
        },

        // Copyright (c) 2009 James Padolsey
        // http://james.padolsey.com/javascript/jquery-delay-plugin/
        delay: function(time, callback){
            jQuery.fx.step.delay = function(){};
            return this.animate({delay:1}, time, callback);
        }        
	});
})(jQuery);
/*
  jQuery strings - 0.3
  http://code.google.com/p/jquery-utils/
  
  (c) Maxime Haineault <haineault@gmail.com>
  http://haineault.com   

  MIT License (http://www.opensource.org/licenses/mit-license.php)

  Implementation of Python3K advanced string formatting
  http://www.python.org/dev/peps/pep-3101/

  Documentation: http://code.google.com/p/jquery-utils/wiki/StringFormat
  
*/
(function($){
    var strings = {
        strConversion: {
            // tries to translate any objects type into string gracefully
            __repr: function(i){
                switch(this.__getType(i)) {
                    case 'array':case 'date':case 'number':
                        return i.toString();
                    case 'object': 
                        var o = [];
                        for (x=0; x<i.length; i++) { o.push(i+': '+ this.__repr(i[x])); }
                        return o.join(', ');
                    case 'string': 
                        return i;
                    default: 
                        return i;
                }
            },
            // like typeof but less vague
            __getType: function(i) {
                if (!i || !i.constructor) { return typeof(i); }
                var match = i.constructor.toString().match(/Array|Number|String|Object|Date/);
                return match && match[0].toLowerCase() || typeof(i);
            },
            //+ Jonas Raoni Soares Silva
            //@ http://jsfromhell.com/string/pad [v1.0]
            __pad: function(str, l, s, t){
                var p = s || ' ';
                var o = str;
                if (l - str.length > 0) {
                    o = new Array(Math.ceil(l / p.length)).join(p).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2)) + str + p.substr(0, l - t);
                }
                return o;
            },
            __getInput: function(arg, args) {
                 var key = arg.getKey();
                switch(this.__getType(args)){
                    case 'object': // Thanks to Jonathan Works for the patch
                        var keys = key.split('.');
                        var obj = args;
                        for(var subkey = 0; subkey < keys.length; subkey++){
                            obj = obj[keys[subkey]];
                        }
                        if (typeof(obj) != 'undefined') {
                            if (strings.strConversion.__getType(obj) == 'array') {
                                return arg.getFormat().match(/\.\*/) && obj[1] || obj;
                            }
                            return obj;
                        }
                        else {
                            // TODO: try by numerical index                    
                        }
                    break;
                    case 'array': 
                        key = parseInt(key, 10);
                        if (arg.getFormat().match(/\.\*/) && typeof args[key+1] != 'undefined') { return args[key+1]; }
                        else if (typeof args[key] != 'undefined') { return args[key]; }
                        else { return key; }
                    break;
                }
                return '{'+key+'}';
            },
            __formatToken: function(token, args) {
                var arg   = new Argument(token, args);
                return strings.strConversion[arg.getFormat().slice(-1)](this.__getInput(arg, args), arg);
            },

            // Signed integer decimal.
            d: function(input, arg){
                var o = parseInt(input, 10); // enforce base 10
                var p = arg.getPaddingLength();
                if (p) { return this.__pad(o.toString(), p, arg.getPaddingString(), 0); }
                else   { return o; }
            },
            // Signed integer decimal.
            i: function(input, args){ 
                return this.d(input, args);
            },
            // Unsigned octal
            o: function(input, arg){ 
                var o = input.toString(8);
                if (arg.isAlternate()) { o = this.__pad(o, o.length+1, '0', 0); }
                return this.__pad(o, arg.getPaddingLength(), arg.getPaddingString(), 0);
            },
            // Unsigned decimal
            u: function(input, args) {
                return Math.abs(this.d(input, args));
            },
            // Unsigned hexadecimal (lowercase)
            x: function(input, arg){
                var o = parseInt(input, 10).toString(16);
                o = this.__pad(o, arg.getPaddingLength(), arg.getPaddingString(),0);
                return arg.isAlternate() ? '0x'+o : o;
            },
            // Unsigned hexadecimal (uppercase)
            X: function(input, arg){
                return this.x(input, arg).toUpperCase();
            },
            // Floating point exponential format (lowercase)
            e: function(input, arg){
                return parseFloat(input, 10).toExponential(arg.getPrecision());
            },
            // Floating point exponential format (uppercase)
            E: function(input, arg){
                return this.e(input, arg).toUpperCase();
            },
            // Floating point decimal format
            f: function(input, arg){
                return this.__pad(parseFloat(input, 10).toFixed(arg.getPrecision()), arg.getPaddingLength(), arg.getPaddingString(),0);
            },
            // Floating point decimal format (alias)
            F: function(input, args){
                return this.f(input, args);
            },
            // Floating point format. Uses exponential format if exponent is greater than -4 or less than precision, decimal format otherwise
            g: function(input, arg){
                var o = parseFloat(input, 10);
                return (o.toString().length > 6) ? Math.round(o.toExponential(arg.getPrecision())): o;
            },
            // Floating point format. Uses exponential format if exponent is greater than -4 or less than precision, decimal format otherwise
            G: function(input, args){
                return this.g(input, args);
            },
            // Single character (accepts integer or single character string). 	
            c: function(input, args) {
                var match = input.match(/\w|\d/);
                return match && match[0] || '';
            },
            // String (converts any JavaScript object to anotated format)
            r: function(input, args) {
                return this.__repr(input);
            },
            // String (converts any JavaScript object using object.toString())
            s: function(input, args) {
                return input.toString && input.toString() || ''+input;
            }
        },

        format: function(str, args) {
            var end    = 0;
            var start  = 0;
            var match  = false;
            var buffer = [];
            var token  = '';
            var tmp    = (str||'').split('');
            for(start=0; start < tmp.length; start++) {
                if (tmp[start] == '{' && tmp[start+1] !='{') {
                    end   = str.indexOf('}', start);
                    token = tmp.slice(start+1, end).join('');
                    if (tmp[start-1] != '{' && tmp[end+1] != '}') {
                        var tokenArgs = (typeof arguments[1] != 'object')? arguments2Array(arguments, 2): args || [];
                        buffer.push(strings.strConversion.__formatToken(token, tokenArgs));
                    }
                    else {
                        buffer.push(token);
                    }
                }
                else if (start > end || buffer.length < 1) { buffer.push(tmp[start]); }
            }
            return (buffer.length > 1)? buffer.join(''): buffer[0];
        },

        calc: function(str, args) {
            return eval(format(str, args));
        },

        repeat: function(s, n) { 
            return new Array(n+1).join(s); 
        },

        UTF8encode: function(s) { 
            return unescape(encodeURIComponent(s)); 
        },

        UTF8decode: function(s) { 
            return decodeURIComponent(escape(s)); 
        },

        tpl: function() {
            var out = '';
            var render = true;
            // Set
            // $.tpl('ui.test', ['<span>', helloWorld ,'</span>']);
            if (arguments.length == 2 && $.isArray(arguments[1])) {
                this[arguments[0]] = arguments[1].join('');
                return jQuery(this[arguments[0]]);
            }
            // $.tpl('ui.test', '<span>hello world</span>');
            if (arguments.length == 2 && $.isString(arguments[1])) {
                this[arguments[0]] = arguments[1];
                return jQuery(this[arguments[0]]);
            }
            // Call
            // $.tpl('ui.test');
            if (arguments.length == 1) {
                return jQuery(this[arguments[0]]);
            }
            // $.tpl('ui.test', false);
            if (arguments.length == 2 && arguments[1] == false) {
                return this[arguments[0]];
            }
            // $.tpl('ui.test', {value:blah});
            if (arguments.length == 2 && $.isObject(arguments[1])) {
                return jQuery($.format(this[arguments[0]], arguments[1]));
            }
            // $.tpl('ui.test', {value:blah}, false);
            if (arguments.length == 3 && $.isObject(arguments[1])) {
                return (arguments[2] == true) 
                    ? $.format(this[arguments[0]], arguments[1])
                    : jQuery($.format(this[arguments[0]], arguments[1]));
            }
        }
    };

    var Argument = function(arg, args) {
        this.__arg  = arg;
        this.__args = args;
        this.__max_precision = parseFloat('1.'+ (new Array(32)).join('1'), 10).toString().length-3;
        this.__def_precision = 6;
        this.getString = function(){
            return this.__arg;
        };
        this.getKey = function(){
            return this.__arg.split(':')[0];
        };
        this.getFormat = function(){
            var match = this.getString().split(':');
            return (match && match[1])? match[1]: 's';
        };
        this.getPrecision = function(){
            var match = this.getFormat().match(/\.(\d+|\*)/g);
            if (!match) { return this.__def_precision; }
            else {
                match = match[0].slice(1);
                if (match != '*') { return parseInt(match, 10); }
                else if(strings.strConversion.__getType(this.__args) == 'array') {
                    return this.__args[1] && this.__args[0] || this.__def_precision;
                }
                else if(strings.strConversion.__getType(this.__args) == 'object') {
                    return this.__args[this.getKey()] && this.__args[this.getKey()][0] || this.__def_precision;
                }
                else { return this.__def_precision; }
            }
        };
        this.getPaddingLength = function(){
            var match = false;
            if (this.isAlternate()) {
                match = this.getString().match(/0?#0?(\d+)/);
                if (match && match[1]) { return parseInt(match[1], 10); }
            }
            match = this.getString().match(/(0|\.)(\d+|\*)/g);
            return match && parseInt(match[0].slice(1), 10) || 0;
        };
        this.getPaddingString = function(){
            var o = '';
            if (this.isAlternate()) { o = ' '; }
            // 0 take precedence on alternate format
            if (this.getFormat().match(/#0|0#|^0|\.\d+/)) { o = '0'; }
            return o;
        };
        this.getFlags = function(){
            var match = this.getString().matc(/^(0|\#|\-|\+|\s)+/);
            return match && match[0].split('') || [];
        };
        this.isAlternate = function() {
            return !!this.getFormat().match(/^0?#/);
        };
    };

    var arguments2Array = function(args, shift) {
        var o = [];
        for (l=args.length, x=(shift || 0)-1; x<l;x++) { o.push(args[x]); }
        return o;
    };
    $.extend(strings);
})(jQuery);
/*
  jQuery ui.timepickr - 0.7.0a
  http://code.google.com/p/jquery-utils/

  (c) Maxime Haineault <haineault@gmail.com> 
  http://haineault.com

  MIT License (http://www.opensource.org/licenses/mit-license.php

  Note: if you want the original experimental plugin checkout the rev 224 

  Dependencies
  ------------
  - jquery.utils.js
  - jquery.strings.js
  - jquery.ui.js
  
*/

(function($) {

$.tpl('timepickr.menu',   '<div class="ui-helper-reset ui-timepickr ui-widget" />');
$.tpl('timepickr.row',    '<ol class="ui-timepickr-row ui-helper-clearfix" />');
$.tpl('timepickr.button', '<li class="{className:s}"><span class="ui-state-default">{label:s}</span></li>');

$.widget('ui.timepickr', {
    plugins: {},
    _init: function() {
        this._dom = {
            menu: $.tpl('timepickr.menu'),
            row:  $.tpl('timepickr.menu')
        };
        this._trigger('initialize');
        this._trigger('initialized');
    },

    _trigger: function(type, e, ui) {
        var ui = ui || this;
        $.ui.plugin.call(this, type, [e, ui]);
        return $.widget.prototype._trigger.call(this, type, e, ui);
    },

    _createButton: function(i, format, className) {
        var o  = format && $.format(format, i) || i;
        var cn = className && 'ui-timepickr-button '+ className || 'ui-timepickr-button';
        return $.tpl('timepickr.button', {className: cn, label: o}).data('id', i)
                .bind('mouseover', function(){
                    jQuery(this).siblings().find('span')
                        .removeClass('ui-state-hover').end().end()
                        .find('span').addClass('ui-state-hover');
                });

    },

    _addRow: function(range, format, className, insertAfter) {
        var ui  = this;
        var btn = false;
        var row = $.tpl('timepickr.row').bind('mouseover', function(){
            jQuery(this).next().show();
        });
        $.each(range, function(idx, val){
            ui._createButton(val, format || false).appendTo(row);
        });
        if (className) {
            jQuery(row).addClass(className);
        }
        if (this.options.corners) {
             row.find('span').addClass('ui-corner-'+ this.options.corners);
        }
        if (insertAfter) {
            row.insertAfter(insertAfter);
        }
        else {
            ui._dom.menu.append(row);
        }
        return row;
    },

    _setVal: function(val) {
        val = val || this._getVal();
        this.element.data('timepickr.initialValue', val);
        this.element.val(this._formatVal(val));
        if(this._dom.menu.is(':hidden')) {
            this.element.trigger('change');
        }
    },

    _getVal: function() {
        var ols = this._dom.menu.find('ol');
        function get(unit) {
            var u = ols.filter('.'+unit).find('.ui-state-hover:first').text();
            return u || ols.filter('.'+unit+'li:first span').text();
        }
        return {
            h: get('hours'),
            m: get('minutes'),
            s: get('seconds'),
            a: get('prefix'),
            z: get('suffix'),
            f: this.options['format'+ this.c],
            c: this.c
        };
    },

    _formatVal: function(ival) {
        var val = ival || this._getVal();
        val.c = this.options.convention;
        val.f = val.c === 12 && this.options.format12 || this.options.format24;
        return (new Time(val)).getTime();
    },

    blur: function() {
        return this.element.blur();      
    },

    focus: function() {
        return this.element.focus();      
    },
    show: function() {
        this._trigger('show');
        this.element.trigger(this.options.trigger);
    },
    hide: function() {
        this._trigger('hide');
        this._dom.menu.hide();
    }

});

// These properties are shared accross every instances of timepickr 
$.extend($.ui.timepickr, {
    version:     '0.7.0a',
    //eventPrefix: '',
    //getter:      '',
    defaults:    {
        convention:  24, // 24, 12
        trigger:     'mouseover',
        format12:    '{h:02.d}:{m:02.d} {suffix:s}',
        format24:    '{h:02.d}:{m:02.d}',
        hours:       true,
        prefix:      ['am', 'pm'],
        suffix:      ['am', 'pm'],
        prefixVal:   false,
        suffixVal:   true,
        rangeHour12: $.range(1, 13),
        rangeHour24: [$.range(0, 12), $.range(12, 24)],
        rangeMin:    $.range(0, 60, 15),
        rangeSec:    $.range(0, 60, 15),
        corners:     'all',
        // plugins
        core:        true,
        minutes:     true,
        seconds:     false,
        val:         false,
        updateLive:  true,
        resetOnBlur: true,
        keyboardnav: true,
        handle:      false,
        handleEvent: 'click'
    }
});

$.ui.plugin.add('timepickr', 'core', {
    initialized: function(e, ui) {
        var menu = ui._dom.menu;
        var pos  = ui.element.position();

        menu.insertAfter(ui.element).css('left', pos.left);

        if (!$.boxModel) { // IE alignement fix
            menu.css('margin-top', ui.element.height() + 8);
        }
        
        ui.element
            .bind(ui.options.trigger, function() {
                ui._dom.menu.show();
                ui._dom.menu.find('ol:first').show();
                ui._trigger('focus');
                if (ui.options.trigger != 'focus') {
                    ui.element.focus();
                }
                ui._trigger('focus');
            })
            .bind('blur', function() {
                ui.hide();
                ui._trigger('blur');
            });

        menu.find('li').bind('mouseover.timepickr', function() {
            ui._trigger('refresh');
        });
    },
    refresh: function(e, ui) {
        // Realign each menu layers
        ui._dom.menu.find('ol').each(function(){
            var p = jQuery(this).prev('ol');
            try { // .. to not fuckup IE
                jQuery(this).css('left', p.position().left + p.find('.ui-state-hover').position().left);
            } catch(e) {};
        });
    }
});

$.ui.plugin.add('timepickr', 'hours', {
    initialize: function(e, ui) {
        if (ui.options.convention === 24) {
            // prefix is required in 24h mode
            ui._dom.prefix = ui._addRow(ui.options.prefix, false, 'prefix'); 

            // split-range
            if ($.isArray(ui.options.rangeHour24[0])) {
                var range = [];
                $.merge(range, ui.options.rangeHour24[0]);
                $.merge(range, ui.options.rangeHour24[1]);
                ui._dom.hours = ui._addRow(range, '{0:0.2d}', 'hours');
                ui._dom.hours.find('li').slice(ui.options.rangeHour24[0].length, -1).hide();
                var lis   = ui._dom.hours.find('li'); 

                var show = [
                    function() {
                        lis.slice(ui.options.rangeHour24[0].length).hide().end()
                           .slice(0, ui.options.rangeHour24[0].length).show()
                           .filter(':visible:first').trigger('mouseover');

                    },
                    function() {
                        lis.slice(0, ui.options.rangeHour24[0].length).hide().end()
                           .slice(ui.options.rangeHour24[0].length).show()
                           .filter(':visible:first').trigger('mouseover');
                    }
                ];

                ui._dom.prefix.find('li').bind('mouseover.timepickr', function(){
                    var index = ui._dom.menu.find('.prefix li').index(this);
                    show[index].call();
                });
            }
            else {
                ui._dom.hours = ui._addRow(ui.options.rangeHour24, '{0:0.2d}', 'hours');
                ui._dom.hours.find('li').slice(12, -1).hide();
            }
        }
        else {
            ui._dom.hours  = ui._addRow(ui.options.rangeHour12, '{0:0.2d}', 'hours');
            // suffix is required in 12h mode
            ui._dom.suffix = ui._addRow(ui.options.suffix, false, 'suffix'); 
        }
    }});

$.ui.plugin.add('timepickr', 'minutes', {
    initialize: function(e, ui) {
        var p = ui._dom.hours && ui._dom.hours || false;
        ui._dom.minutes = ui._addRow(ui.options.rangeMin, '{0:0.2d}', 'minutes', p);
    }
});

$.ui.plugin.add('timepickr', 'seconds', {
    initialize: function(e, ui) {
        var p = ui._dom.minutes && ui._dom.minutes || false;
        ui._dom.seconds = ui._addRow(ui.options.rangeSec, '{0:0.2d}', 'seconds', p);
    }
});

$.ui.plugin.add('timepickr', 'val', {
    initialized: function(e, ui) {
        ui._setVal(ui.options.val);
    }
});

$.ui.plugin.add('timepickr', 'updateLive', {
    refresh: function(e, ui) {
        ui._setVal();
    }
});

$.ui.plugin.add('timepickr', 'resetOnBlur', {
    initialized: function(e, ui) {
        ui.element.data('timepickr.initialValue', ui._getVal());
        ui._dom.menu.find('li > span').bind('mousedown.timepickr', function(){
            ui.element.data('timepickr.initialValue', ui._getVal()); 
        });
    },
    blur: function(e, ui) {
        ui._setVal(ui.element.data('timepickr.initialValue'));
    }
});

$.ui.plugin.add('timepickr', 'handle', {
    initialized: function(e, ui) {
        jQuery(ui.options.handle).bind(ui.options.handleEvent + '.timepickr', function(){
            ui.show();
        });
    }
});

$.ui.plugin.add('timepickr', 'keyboardnav', {
    initialized: function(e, ui) {
        ui.element
            .bind('keydown', function(e) {
                if ($.keyIs('enter', e)) {
                    ui._setVal();
                    ui.blur();
                }
                else if ($.keyIs('escape', e)) {
                    ui.blur();
                }
            });
    }
});

var Time = function() { // arguments: h, m, s, c, z, f || time string
    if (!(this instanceof arguments.callee)) {
        throw Error("Constructor called as a function");
    }
    // arguments as literal object
    if (arguments.length == 1 && $.isObject(arguments[0])) {
        this.h = arguments[0].h || 0;
        this.m = arguments[0].m || 0;
        this.s = arguments[0].s || 0;
        this.c = arguments[0].c && ($.inArray(arguments[0].c, [12, 24]) >= 0) && arguments[0].c || 24;
        this.f = arguments[0].f || ((this.c == 12) && '{h:02.d}:{m:02.d} {z:02.d}' || '{h:02.d}:{m:02.d}');
        this.z = arguments[0].z || 'am';
    }
    // arguments as string
    else if (arguments.length < 4 && $.isString(arguments[1])) {
        this.c = arguments[2] && ($.inArray(arguments[0], [12, 24]) >= 0) && arguments[0] || 24;
        this.f = arguments[3] || ((this.c == 12) && '{h:02.d}:{m:02.d} {z:02.d}' || '{h:02.d}:{m:02.d}');
        this.z = arguments[4] || 'am';
        
        this.h = arguments[1] || 0; // parse
        this.m = arguments[1] || 0; // parse
        this.s = arguments[1] || 0; // parse
    }
    // no arguments (now)
    else if (arguments.length === 0) {
        // now
    }
    // standards arguments
    else {
        this.h = arguments[0] || 0;
        this.m = arguments[1] || 0;
        this.s = arguments[2] || 0;
        this.c = arguments[3] && ($.inArray(arguments[3], [12, 24]) >= 0) && arguments[3] || 24;
        this.f = this.f || ((this.c == 12) && '{h:02.d}:{m:02.d} {z:02.d}' || '{h:02.d}:{m:02.d}');
        this.z = 'am';
    }
    return this;
};

Time.prototype.get        = function(p, f, u)    { return u && this.h || $.format(f, this.h); };
Time.prototype.getHours   = function(unformated) { return this.get('h', '{0:02.d}', unformated); };
Time.prototype.getMinutes = function(unformated) { return this.get('m', '{0:02.d}', unformated); };
Time.prototype.getSeconds = function(unformated) { return this.get('s', '{0:02.d}', unformated); };
Time.prototype.setFormat  = function(format)     { return this.f = format; };
Time.prototype.getObject  = function()           { return { h: this.h, m: this.m, s: this.s, c: this.c, f: this.f, z: this.z }; };
Time.prototype.getTime    = function()           { return $.format(this.f, {h: this.h, m: this.m, z: this.z}); };
Time.prototype.parse      = function(str) { 
    // 12h formats
    if (this.c === 12) {
        // Supported formats: (can't find any *official* standards for 12h..)
        //  - [hh]:[mm]:[ss] [zz] | [hh]:[mm] [zz] | [hh] [zz] 
        //  - [hh]:[mm]:[ss] [z.z.] | [hh]:[mm] [z.z.] | [hh] [z.z.]
        this.tokens = str.split(/\s|:/);    
        this.h = this.tokens[0] || 0;
        this.m = this.tokens[1] || 0;
        this.s = this.tokens[2] || 0;
        this.z = this.tokens[3] || '';
        return this.getObject();
    }
    // 24h formats
    else { 
        // Supported formats:
        //  - ISO 8601: [hh][mm][ss] | [hh][mm] | [hh]  
        //  - ISO 8601 extended: [hh]:[mm]:[ss] | [hh]:[mm] | [hh]
        this.tokens = /:/.test(str) && str.split(/:/) || str.match(/[0-9]{2}/g);
        this.h = this.tokens[0] || 0;
        this.m = this.tokens[1] || 0;
        this.s = this.tokens[2] || 0;
        this.z = this.tokens[3] || '';
        return this.getObject();
    }
};

})(jQuery);
