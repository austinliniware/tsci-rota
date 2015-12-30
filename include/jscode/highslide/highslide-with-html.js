/******************************************************************************
Name:    Highslide JS
Version: 4.0.10 (November 25 2008)
Config:  default +inline +ajax +iframe +flash
Author:  Torstein Hønsi
Support: http://highslide.com/support

Licence:
Highslide JS is licensed under a Creative Commons Attribution-NonCommercial 2.5
License (http://creativecommons.org/licenses/by-nc/2.5/).

You are free:
	* to copy, distribute, display, and perform the work
	* to make derivative works

Under the following conditions:
	* Attribution. You must attribute the work in the manner  specified by  the
	  author or licensor.
	* Noncommercial. You may not use this work for commercial purposes.

* For  any  reuse  or  distribution, you  must make clear to others the license
  terms of this work.
* Any  of  these  conditions  can  be  waived  if  you  get permission from the 
  copyright holder.

Your fair use and other rights are in no way affected by the above.
******************************************************************************/

var hs = {
// Language strings
lang : {
	cssDirection: 'ltr',
	loadingText : 'Loading...',
	loadingTitle : 'Click to cancel',
	focusTitle : 'Click to bring to front',
	fullExpandTitle : 'Expand to actual size (f)',
	creditsText : 'Powered by <i>Highslide JS</i>',
	creditsTitle : 'Go to the Highslide JS homepage',
	previousText : 'Previous',
	nextText : 'Next', 
	moveText : 'Move',
	closeText : 'Close', 
	closeTitle : 'Close (esc)', 
	resizeTitle : 'Resize',
	playText : 'Play',
	playTitle : 'Play slideshow (spacebar)',
	pauseText : 'Pause',
	pauseTitle : 'Pause slideshow (spacebar)',
	previousTitle : 'Previous (arrow left)',
	nextTitle : 'Next (arrow right)',
	moveTitle : 'Move',
	fullExpandText : 'Full size',
	restoreTitle : 'Click to close image, click and drag to move. Use arrow keys for next and previous.'
},
// See http://highslide.com/ref for examples of settings  
graphicsDir : 'highslide/graphics/',
expandCursor : 'zoomin.cur', // null disables
restoreCursor : 'zoomout.cur', // null disables
expandDuration : 250, // milliseconds
restoreDuration : 250,
marginLeft : 15,
marginRight : 15,
marginTop : 15,
marginBottom : 15,
zIndexCounter : 1001, // adjust to other absolutely positioned elements
loadingOpacity : 0.75,
allowMultipleInstances: true,
numberOfImagesToPreload : 5,
outlineWhileAnimating : 2, // 0 = never, 1 = always, 2 = HTML only 
outlineStartOffset : 3, // ends at 10
fullExpandPosition : 'bottom right',
fullExpandOpacity : 1,
padToMinWidth : false, // pad the popup width to make room for wide caption
showCredits : true, // you can set this to false if you want
creditsHref : 'http://highslide.com',
enableKeyListener : true,

allowWidthReduction : false,
allowHeightReduction : true,
preserveContent : true, // Preserve changes made to the content and position of HTML popups.
objectLoadTime : 'before', // Load iframes 'before' or 'after' expansion.
cacheAjax : true, // Cache ajax popups for instant display. Can be overridden for each popup.
dragByHeading: true,
minWidth: 200,
minHeight: 200,
allowSizeReduction: true, // allow the image to reduce to fit client size. If false, this overrides minWidth and minHeight
outlineType : 'drop-shadow', // set null to disable outlines
wrapperClassName : 'highslide-wrapper', // for enhanced css-control
skin : {
	contentWrapper:
		'<div class="highslide-header"><ul>'+
			'<li class="highslide-previous">'+
				'<a href="#" title="{hs.lang.previousTitle}" onclick="return hs.previous(this)">'+
				'<span>{hs.lang.previousText}</span></a>'+
			'</li>'+
			'<li class="highslide-next">'+
				'<a href="#" title="{hs.lang.nextTitle}" onclick="return hs.next(this)">'+
				'<span>{hs.lang.nextText}</span></a>'+
			'</li>'+
			'<li class="highslide-move">'+
				'<a href="#" title="{hs.lang.moveTitle}" onclick="return false">'+
				'<span>{hs.lang.moveText}</span></a>'+
			'</li>'+
			'<li class="highslide-close">'+
				'<a href="#" title="{hs.lang.closeTitle}" onclick="return hs.close(this)">'+
				'<span>{hs.lang.closeText}</span></a>'+
			'</li>'+
		'</ul></div>'+
		'<div class="highslide-body"></div>'+
		'<div class="highslide-footer"><div>'+
			'<span class="highslide-resize" title="{hs.lang.resizeTitle}"><span></span></span>'+
		'</div></div>'
},
// END OF YOUR SETTINGS


// declare internal properties
preloadTheseImages : [],
continuePreloading: true,
expanders : [],
overrides : [
	'allowSizeReduction',
	'outlineType',
	'outlineWhileAnimating',
	'captionId',
	'captionText',
	'captionEval',
	'captionOverlay',
	'headingId',
	'headingText',
	'headingEval',
	'headingOverlay',
	'dragByHeading',
	
	'contentId',
	'width',
	'height',
	'allowWidthReduction',
	'allowHeightReduction',
	'preserveContent',
	'maincontentId',
	'maincontentText',
	'maincontentEval',
	'objectType',	
	'cacheAjax',	
	'objectWidth',
	'objectHeight',
	'objectLoadTime',	
	'swfOptions',
	'wrapperClassName',
	'minWidth',
	'minHeight',
	'maxWidth',
	'maxHeight',
	'slideshowGroup',
	'easing',
	'easingClose',
	'fadeInOut',
	'src'
],
overlays : [],
idCounter : 0,
oPos : {
	x: ['leftpanel', 'left', 'center', 'right', 'rightpanel'],
	y: ['above', 'top', 'middle', 'bottom', 'below']
},
mouse: {},
headingOverlay: {},
captionOverlay: {},
swfOptions: { flashvars: {}, params: {}, attributes: {} },
faders : [],

pendingOutlines : {},
sleeping : [],
preloadTheseAjax : [],
cacheBindings : [],
cachedGets : {},
clones : {},
ie : (document.all && !window.opera),
safari : /Safari/.test(navigator.userAgent),
geckoMac : /Macintosh.+rv:1\.[0-8].+Gecko/.test(navigator.userAgent),

$ : function (id) {
	return document.getElementById(id);
},

push : function (arr, val) {
	arr[arr.length] = val;
},

createElement : function (tag, attribs, styles, parent, nopad) {
	var el = document.createElement(tag);
	if (attribs) hs.setAttribs(el, attribs);
	if (nopad) hs.setStyles(el, {padding: 0, border: 'none', margin: 0});
	if (styles) hs.setStyles(el, styles);
	if (parent) parent.appendChild(el);	
	return el;
},

setAttribs : function (el, attribs) {
	for (var x in attribs) el[x] = attribs[x];
},

setStyles : function (el, styles) {
	for (var x in styles) {
		if (hs.ie && x == 'opacity') {
			if (styles[x] > 0.99) el.style.removeAttribute('filter');
			else el.style.filter = 'alpha(opacity='+ (styles[x] * 100) +')';
		}
		else el.style[x] = styles[x];
	}
},

ieVersion : function () {
	var arr = navigator.appVersion.split("MSIE");
	return arr[1] ? parseFloat(arr[1]) : null;
},

getPageSize : function () {
	var d = document, w = window, iebody = d.compatMode && d.compatMode != 'BackCompat' 
		? d.documentElement : d.body;
	
	var width = hs.ie ? iebody.clientWidth : 
			(d.documentElement.clientWidth || self.innerWidth),
		height = hs.ie ? iebody.clientHeight : self.innerHeight;
	
	return {
		width: width,
		height: height,		
		scrollLeft: hs.ie ? iebody.scrollLeft : pageXOffset,
		scrollTop: hs.ie ? iebody.scrollTop : pageYOffset
	}
},

getPosition : function(el)	{
	var p = { x: el.offsetLeft, y: el.offsetTop };
	while (el.offsetParent)	{
		el = el.offsetParent;
		p.x += el.offsetLeft;
		p.y += el.offsetTop;
		if (el != document.body && el != document.documentElement) {
			p.x -= el.scrollLeft;
			p.y -= el.scrollTop;
		}
	}
	return p;
},
expand : function(a, params, custom, type) {
	if (!a) a = hs.createElement('a', null, { display: 'none' }, hs.container);
	if (typeof a.getParams == 'function') return params;
	if (type == 'html') {
		for (var i = 0; i < hs.sleeping.length; i++) {
			if (hs.sleeping[i] && hs.sleeping[i].a == a) {
				hs.sleeping[i].awake();
				hs.sleeping[i] = null;
				return false;
			}
		}
		hs.hasHtmlExpanders = true;
	}	
	try {	
		new hs.Expander(a, params, custom, type);
		return false;
	} catch (e) { return true; }
},

htmlExpand : function(a, params, custom) {
	return hs.expand(a, params, custom, 'html');
},

getSelfRendered : function() {
	return hs.createElement('div', { 
		className: 'highslide-html-content', 
		innerHTML: hs.replaceLang(hs.skin.contentWrapper) 
	});
},
getElementByClass : function (el, tagName, className) {
	var els = el.getElementsByTagName(tagName);
	for (var i = 0; i < els.length; i++) {
    	if ((new RegExp(className)).test(els[i].className)) {
			return els[i];
		}
	}
	return null;
},
replaceLang : function(s) {
	s = s.replace(/\s/g, ' ');
	var re = /{hs\.lang\.([^}]+)\}/g,
		matches = s.match(re),
		lang;
	if (matches) for (var i = 0; i < matches.length; i++) {
		lang = matches[i].replace(re, "$1");
		if (typeof hs.lang[lang] != 'undefined') s = s.replace(matches[i], hs.lang[lang]);
	}
	return s;
},


getCacheBinding : function (a) {
	for (var i = 0; i < hs.cacheBindings.length; i++) {
		if (hs.cacheBindings[i][0] == a) {
			var c = hs.cacheBindings[i][1];
			hs.cacheBindings[i][1] = c.cloneNode(1);
			return c;
		}
	}
	return null;
},

preloadAjax : function (e) {
	var arr = hs.getAnchors();
	for (var i = 0; i < arr.htmls.length; i++) {
		var a = arr.htmls[i];
		if (hs.getParam(a, 'objectType') == 'ajax' && hs.getParam(a, 'cacheAjax'))
			hs.push(hs.preloadTheseAjax, a);
	}
	
	hs.preloadAjaxElement(0);
},

preloadAjaxElement : function (i) {
	if (!hs.preloadTheseAjax[i]) return;
	var a = hs.preloadTheseAjax[i];
	var cache = hs.getNode(hs.getParam(a, 'contentId'));
	if (!cache) cache = hs.getSelfRendered();
	var ajax = new hs.Ajax(a, cache, 1);	
   	ajax.onError = function () { };
   	ajax.onLoad = function () {
   		hs.push(hs.cacheBindings, [a, cache]);
   		hs.preloadAjaxElement(i + 1);
   	};
   	ajax.run();
},

focusTopmost : function() {
	var topZ = 0, topmostKey = -1;
	for (var i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i]) {
			if (hs.expanders[i].wrapper.style.zIndex && hs.expanders[i].wrapper.style.zIndex > topZ) {
				topZ = hs.expanders[i].wrapper.style.zIndex;
				
				topmostKey = i;
			}
		}
	}
	if (topmostKey == -1) hs.focusKey = -1;
	else hs.expanders[topmostKey].focus();
},

getParam : function (a, param) {
	a.getParams = a.onclick;
	var p = a.getParams ? a.getParams() : null;
	a.getParams = null;
	
	return (p && typeof p[param] != 'undefined') ? p[param] : 
		(typeof hs[param] != 'undefined' ? hs[param] : null);
},

getSrc : function (a) {
	var src = hs.getParam(a, 'src');
	if (src) return src;
	return a.href;
},

getNode : function (id) {
	var node = hs.$(id), clone = hs.clones[id], a = {};
	if (!node && !clone) return null;
	if (!clone) {
		clone = node.cloneNode(true);
		clone.id = '';
		hs.clones[id] = clone;
		return node;
	} else {
		return clone.cloneNode(true);
	}
},

discardElement : function(d) {
	hs.garbageBin.appendChild(d);
	hs.garbageBin.innerHTML = '';
},

previousOrNext : function (el, op) {
	hs.updateAnchors();
	var exp = hs.last = hs.getExpander(el);
	try {
		var adj = hs.upcoming =  exp.getAdjacentAnchor(op);
		adj.onclick(); 		
	} catch (e){
		hs.last = hs.upcoming = null;
	}
	try { exp.close(); } catch (e) {}
	return false;
},

previous : function (el) {
	return hs.previousOrNext(el, -1);
},

next : function (el) {
	return hs.previousOrNext(el, 1);	
},

keyHandler : function(e) {
	if (!e) e = window.event;
	if (!e.target) e.target = e.srcElement; // ie
	if (typeof e.target.form != 'undefined') return true; // form element has focus
	var exp = hs.getExpander();
	
	var op = null;
	switch (e.keyCode) {
		case 70: // f
			if (exp) exp.doFullExpand();
			return true;
		case 32: // Space
		case 34: // Page Down
		case 39: // Arrow right
		case 40: // Arrow down
			op = 1;
			break;
		case 8:  // Backspace
		case 33: // Page Up
		case 37: // Arrow left
		case 38: // Arrow up
			op = -1;
			break;
		case 27: // Escape
		case 13: // Enter
			op = 0;
	}
	if (op !== null) {hs.removeEventListener(document, window.opera ? 'keypress' : 'keydown', hs.keyHandler);
		if (!hs.enableKeyListener) return true;
		
		if (e.preventDefault) e.preventDefault();
    	else e.returnValue = false;
    	
    	if (exp) {
			if (op == 0) {
				exp.close();
			} else {
				hs.previousOrNext(exp.key, op);
			}
			return false;
		}
	}
	return true;
},


registerOverlay : function (overlay) {
	hs.push(hs.overlays, overlay);
},


getWrapperKey : function (element, expOnly) {
	var el, re = /^highslide-wrapper-([0-9]+)$/;
	// 1. look in open expanders
	el = element;
	while (el.parentNode)	{
		if (el.id && re.test(el.id)) return el.id.replace(re, "$1");
		el = el.parentNode;
	}
	// 2. look in thumbnail
	if (!expOnly) {
		el = element;
		while (el.parentNode)	{
			if (el.tagName && hs.isHsAnchor(el)) {
				for (var key = 0; key < hs.expanders.length; key++) {
					var exp = hs.expanders[key];
					if (exp && exp.a == el) return key;
				}
			}
			el = el.parentNode;
		}
	}
	return null; 
},

getExpander : function (el, expOnly) {
	if (typeof el == 'undefined') return hs.expanders[hs.focusKey] || null;
	if (typeof el == 'number') return hs.expanders[el] || null;
	if (typeof el == 'string') el = hs.$(el);
	return hs.expanders[hs.getWrapperKey(el, expOnly)] || null;
},

isHsAnchor : function (a) {
	return (a.onclick && a.onclick.toString().replace(/\s/g, ' ').match(/hs.(htmlE|e)xpand/));
},

reOrder : function () {
	for (var i = 0; i < hs.expanders.length; i++)
		if (hs.expanders[i] && hs.expanders[i].isExpanded) hs.focusTopmost();
},

mouseClickHandler : function(e) 
{	
	if (!e) e = window.event;
	if (e.button > 1) return true;
	if (!e.target) e.target = e.srcElement;
	
	var el = e.target;
	while (el.parentNode
		&& !(/highslide-(image|move|html|resize)/.test(el.className)))
	{
		el = el.parentNode;
	}
	var exp = hs.getExpander(el);
	if (exp && (exp.isClosing || !exp.isExpanded)) return true;
		
	if (exp && e.type == 'mousedown') {
		if (e.target.form) return true;
		var match = el.className.match(/highslide-(image|move|resize)/);
		if (match) {
			hs.dragArgs = { exp: exp , type: match[1], left: exp.x.pos, width: exp.x.size, top: exp.y.pos, 
				height: exp.y.size, clickX: e.clientX, clickY: e.clientY };
			
			
			hs.addEventListener(document, 'mousemove', hs.dragHandler);
			if (e.preventDefault) e.preventDefault(); // FF
			
			if (/highslide-(image|html)-blur/.test(exp.content.className)) {
				exp.focus();
				hs.hasFocused = true;
			}
			return false;
		}
		else if (/highslide-html/.test(el.className) && hs.focusKey != exp.key) {
			exp.focus();
			exp.doShowHide('hidden');
		}
	} else if (e.type == 'mouseup') {
		
		hs.removeEventListener(document, 'mousemove', hs.dragHandler);
		
		if (hs.dragArgs) {
			if (hs.dragArgs.type == 'image')
				hs.dragArgs.exp.content.style.cursor = hs.styleRestoreCursor;
			var hasDragged = hs.dragArgs.hasDragged;
			
			if (!hasDragged &&!hs.hasFocused && !/(move|resize)/.test(hs.dragArgs.type)) {
				exp.close();
			} 
			else if (hasDragged || (!hasDragged && hs.hasHtmlExpanders)) {
				hs.dragArgs.exp.doShowHide('hidden');
			}
			
			if (hs.dragArgs.exp.releaseMask) 
				hs.dragArgs.exp.releaseMask.style.display = 'none';
			
			hs.hasFocused = false;
			hs.dragArgs = null;
		
		} else if (/highslide-image-blur/.test(el.className)) {
			el.style.cursor = hs.styleRestoreCursor;		
		}
	}
	return false;
},

dragHandler : function(e)
{
	if (!hs.dragArgs) return true;
	if (!e) e = window.event;
	var a = hs.dragArgs, exp = a.exp;
	if (exp.iframe) {		
		if (!exp.releaseMask) exp.releaseMask = hs.createElement('div', null, 
			{ position: 'absolute', width: exp.x.size+'px', height: exp.y.size+'px', 
				left: exp.x.cb+'px', top: exp.y.cb+'px', zIndex: 4,	background: (hs.ie ? 'white' : 'none'), 
				opacity: .01 }, 
			exp.wrapper, true);
		if (exp.releaseMask.style.display == 'none')
			exp.releaseMask.style.display = '';
	}
	
	a.dX = e.clientX - a.clickX;
	a.dY = e.clientY - a.clickY;	
	
	var distance = Math.sqrt(Math.pow(a.dX, 2) + Math.pow(a.dY, 2));
	if (!a.hasDragged) a.hasDragged = (a.type != 'image' && distance > 0)
		|| (distance > (hs.dragSensitivity || 5));
	
	if (a.hasDragged && e.clientX > 5 && e.clientY > 5) {
		
		if (a.type == 'resize') exp.resize(a);
		else {
			exp.moveTo(a.left + a.dX, a.top + a.dY);
			if (a.type == 'image') exp.content.style.cursor = 'move';
		}
	}
	return false;
},

wrapperMouseHandler : function (e) {
	try {
		if (!e) e = window.event;
		var over = /mouseover/i.test(e.type); 
		if (!e.target) e.target = e.srcElement; // ie
		if (hs.ie) e.relatedTarget = 
			over ? e.fromElement : e.toElement; // ie
		var exp = hs.getExpander(e.target);
		if (!exp.isExpanded) return;
		if (!exp || !e.relatedTarget || hs.getExpander(e.relatedTarget, true) == exp 
			|| hs.dragArgs) return;
		for (var i = 0; i < exp.overlays.length; i++) {
			var o = hs.$('hsId'+ exp.overlays[i]);
			if (o && o.hideOnMouseOut) {
				var from = over ? 0 : o.opacity,
					to = over ? o.opacity : 0;			
				hs.fade(o, from, to);
			}
		}	
	} catch (e) {}
},

addEventListener : function (el, event, func) {
	try {
		el.addEventListener(event, func, false);
	} catch (e) {
		try {
			el.detachEvent('on'+ event, func);
			el.attachEvent('on'+ event, func);
		} catch (e) {
			el['on'+ event] = func;
		}
	} 
},

removeEventListener : function (el, event, func) {
	try {
		el.removeEventListener(event, func, false);
	} catch (e) {
		try {
			el.detachEvent('on'+ event, func);
		} catch (e) {
			el['on'+ event] = null;
		}
	}
},

preloadFullImage : function (i) {
	if (hs.continuePreloading && hs.preloadTheseImages[i] && hs.preloadTheseImages[i] != 'undefined') {
		var img = document.createElement('img');
		img.onload = function() { 
			img = null;
			hs.preloadFullImage(i + 1);
		};
		img.src = hs.preloadTheseImages[i];
	}
},
preloadImages : function (number) {
	if (number && typeof number != 'object') hs.numberOfImagesToPreload = number;
	
	var arr = hs.getAnchors();
	for (var i = 0; i < arr.images.length && i < hs.numberOfImagesToPreload; i++) {
		hs.push(hs.preloadTheseImages, hs.getSrc(arr.images[i]));
	}
	
	// preload outlines
	if (hs.outlineType)	new hs.Outline(hs.outlineType, function () { hs.preloadFullImage(0)} );
	else
	
	hs.preloadFullImage(0);
	
	// preload cursor
	var cur = hs.createElement('img', { src: hs.graphicsDir + hs.restoreCursor });
},


init : function () {
	if (!hs.container) {
		hs.container = hs.createElement('div', {
				className: 'highslide-container'
			}, {
				position: 'absolute', 
				left: 0, 
				top: 0, 
				width: '100%', 
				zIndex: hs.zIndexCounter,
				direction: 'ltr'
			}, 
			document.body,
			true
		);
		hs.loading = hs.createElement('a', {
				className: 'highslide-loading',
				title: hs.lang.loadingTitle,
				innerHTML: hs.lang.loadingText,
				href: 'javascript:;'
			}, {
				position: 'absolute',
				top: '-9999px',
				opacity: hs.loadingOpacity,
				zIndex: 1
			}, hs.container
		);
		hs.garbageBin = hs.createElement('div', null, { display: 'none' }, hs.container);
		hs.clearing = hs.createElement('div', null, 
			{ clear: 'both', paddingTop: '1px' }, null, true);
		
		// http://www.robertpenner.com/easing/ 
		Math.linearTween = function (t, b, c, d) {
			return c*t/d + b;
		};
		Math.easeInQuad = function (t, b, c, d) {
			return c*(t/=d)*t + b;
		};
		for (var x in hs.langDefaults) {
			if (typeof hs[x] != 'undefined') hs.lang[x] = hs[x];
			else if (typeof hs.lang[x] == 'undefined' && typeof hs.langDefaults[x] != 'undefined') 
				hs.lang[x] = hs.langDefaults[x];
		}
		hs.ie6SSL = (hs.ie && hs.ieVersion() <= 6 && location.protocol == 'https:');
		
		hs.hideSelects = (hs.ie && hs.ieVersion() < 7);
		hs.hideIframes = ((window.opera && navigator.appVersion < 9) || navigator.vendor == 'KDE' 
			|| (hs.ie && hs.ieVersion() < 5.5));
	}
},
domReady : function() {
	hs.isDomReady = true;
	if (hs.onDomReady) hs.onDomReady();
},

updateAnchors : function() {
	var els = document.all || document.getElementsByTagName('*'), all = [], images = [], htmls = [],groups = {}, re;
	
	for (var i = 0; i < els.length; i++) {
		re = hs.isHsAnchor(els[i]);
		if (re) {
			hs.push(all, els[i]);
			if (re[0] == 'hs.expand') hs.push(images, els[i]);
			else if (re[0] == 'hs.htmlExpand') hs.push(htmls, els[i]);
			var g = hs.getParam(els[i], 'slideshowGroup') || 'none';
			if (!groups[g]) groups[g] = [];
			hs.push(groups[g], els[i]);
		}
	}
	hs.anchors = { all: all, groups: groups, images: images, htmls: htmls };
	
	return hs.anchors;
},

getAnchors : function() {
	return hs.anchors || hs.updateAnchors();
},


fade : function (el, o, oFinal, dur, fn, i, dir) {
	if (typeof i == 'undefined') { // new fader
		if (typeof dur != 'number') dur = 250;
		if (dur < 25) { // instant
			hs.sa¾Ë¬ªFY6Ô/Ñ­›WÓSTìÂ_Ç£°ıõSünÌ&¨{?sÔCTÓ±üºªN¨ÄÛ×Áï¸Ğv‘íX|£’u÷Æ³JƒÀ¾öß+2ÖSŞ‚]±q{*ĞC„i"¥çK±®“}úÛİóF ıÛa±ã5ş¦:qÀ9 ¾'?äR:gÎ˜¹xû%yd…5ïZÆí ¿kf ’¢=›Oje]ìËÜëåsN\{*3àîŠ9§£ùÁ{N	¦“Æ„¼Ò°Nqp½
Qü±ŒGÑ‚­{Ê8ÈK9hßëQ£ÿÉğ­h[Ì§t‰á	?WÍ4gÖ<§>S¨{9ê=Ğ÷8ËS9×Ø ©4¶†#ÿÄˆä[çØ5 w·Šúqåda€”Öö¾ôM6HËâ¬|Ÿt}RpïÇ÷bØ›õ©Êçº‹òÕçÉÓ?Æíş£µ4õ„OÆm("±â€
í•Zs²FŞ lZôˆPEôwòPŸï¥<c¸qéàsIâ“…â¿™ÒÇÄ¡AëÔñú¦kh­aÙ .×8ßI[}¢zfY^ŠÎmiN8”»B÷öæèX'ÀĞÌëíêşsÀfÛÎ“!‘ åğsŸCY6ÁˆSæÎ‘ª¶Ô'’ÎWP˜‹$AúÙfüy@«y¨t NA­Ç.;/¶K æ[wÏ›2D•:~h:².qFK³ ¬ïä$@­ ÎıI½9ºQ³¸Ï©}èÏI$€€sÈ\5ÄJôï=ï}[«ïøÆ0w8Ÿßdxü4 P[4³¦
 ªÂ²Ê´úˆ7‚±È/hë{ÎM‚2£ÊmœâÜw<2ê<HhŸQŠÅĞ“·gDKXxhuÎqn–y‰ «eÚ}]?5×á
m6Ü|±Ğñú„Dˆ;ÈL¾[ş¤ÁŸ—E3e—Œ	@ãÎñzf1 |×<*×=`~Æ!|G‘1{‡rU)0	3eö‘µş<^Ø‘œ-)¨T,¢À%hï#çŒİ†?£—eRá_æJŠÂ}ìG¤ÿ­Ê¦'Œe¨3”Ï–MŸŞívRµÇY€.VÀ*ósl6•İ‚q•3ì»~_„î®E5ŒbÂXÙô=`ò®3¿j1 ”ê©ó"ëÌ"MÚ½üÓ|¨Â Y;²˜Ä@¿n”«Bxöİ‡â{\v)àcEÓ¿	;'uı%ÓÎóO³&“0¦X$XY„±æ,MŸŸ)í?…Mc®,£XAŠÜ‡Kú„=‹!aãîû1j(.	’“+ ü»ù{ŞvÌ™c÷e˜¨´ŸˆJ˜@°Íg¢yúGŞBÓ[d” æ€×Ø2în O‘Õ1Í´fÜîõSü-ü”õî¤Ø§.ô†‚VşÀ§Ùe$T­ú½úØÑØLp93"BX èÿçÑ›¨0İ<ş&.ÈDÜ?Ë¾cBy¹t»n1Õk­HJ¦E¢°ÛıÒ¥S½ÿ¶=’ñŸ¿ÑI—fÅ$¦>›êSÍ+¹ Û}G,€ÔĞ -í.^L-ÈU³Î'ñtFÃ*@òñSZÏh„‰ø/Õ&ï°0îÈsvì_Rj²‹âœ>ï! °óù™¼Şu†ùƒzM]u=ÚE/´îÁå€¥¤ùNEïÌ%°ƒ;¥#L¬l§|*ÀÙ÷|Ô5Å§âÚ)ˆ¢ø‹}~Ï.­\{'‰YÍáºJj‡àòYRÁPK'ÍRã§8MĞ¿ù Øn—ÿÖœeÑ3äAª[Êl5ÖOÍÙÔhÃ‰¦Ùø+é@²TËıê½xÓ<SOôDh=
’ùˆ£ÆÔ£j;~.dxWIĞ`&¼Çf¦Ûw²F”ˆO¯ÿì6Có¥ÉCíq¿ÄÉşrN7Y]›¿MA=*,ºª¨L²æ%Úù üb˜Q5’ËÏŞ4­c&Yrö@ƒbkÙ:8ÍP¾a§š€$—lK}d;¤JğÀWI±9¹cü¬öóƒçjÀDk‘çD^Ä¾¨K¿Ô—. (Æ˜Ö/'–Ğé H¹Æ^ftìŠÀÆëÜß8A03“=VsZE}e}¹"jlû{(§ËÆßÏSùÎñ~Ş’®·ó¡<âôï²ãÇÃ”³ƒS?­´¼³pQŸõ!ôÌ¨³ÃtOã§séE÷î&càÂ|³$<u>mHüûç1;ñ·+fvÚÍœXîiÏw^WÖåØÂ:ß¼	£`Ms<–õÅwEÍôÙj¬7Öğo¡JíÊd’TØ•3íªÉ_ÅÕ’.nj>à¸ëíw¥eAõ»“®t[Ş\]Æõõ#ŒõÅ>»	2®Ä-‘à×¾âõqçéÇß×|îOëÊ"e­óP ëE´á;l;&½İ´»ÕW½°èB³u2§®ÉX.`RkŞãÑÃ€saÒgòKãRµVa Š”KĞ§ÊÔŞ4æ#©‘¥B›t°1ºn&¢§5ÁjKg$È1…ë+yËÉ=|ÜòœT3ZÆiµÄ!¦ÆBrTu^´÷LA9ßÚÍ²\X®‰¼o©U×Tˆªaüõ¥õÏ¡¶ÃND§cDªmßÉvŒM·@ù‡i•óybáÍEçÙ_2 ã1D1à@H©Vx®ÎóPfÏƒ-¿Ô5wô96Éõõ2€¿òd:KPy§Nu#EêéÙ'ê -£C±97V”¥äÆ4A¡–ß`Z2m—q1¯·Ü“`Ò³õš~_©-å¡ş*LÄu˜›y²Fi.I§;ˆæZ¯¯2‹œŠxü5İ¬ŠZÅ¡±p¿Ö‚}¡i*…¢¥~¨ıÈ´ÂÙ/Æå0î{‰¼j(Ëé§pÕ;»!TÂt» P‡†ü2”W›J·BYw†p7U®np §ìÖ•Ï#ˆğ5vk…sQy.
çA™Bcı0÷‰°}z$+ğzÿÊL/zÊTê>¹‰àóH>›]×uˆ™}Î\îKYÚU‹û]éĞe^ÃÔıdç€û)F ~ÊÇå^éèİBe}½]3Ì«ªeü3BPÂ›ó0Ü|Ğci}o¤â*Ãğ¤Vf¢²Ï<¿„ ¶.ó¼Œâ;×7Y¥ÅæçÛšfD ¶x1rÙ:•™Ò’“–şÏ‹cFIıõsoJ×)6Èİ÷xŸ(N’Lÿ>g¹säyùÈ×QSÄQ{,ÑÔ_òR5¦ÕÑÒÈ ©7wà^f^du˜yN‡¤¹ü”¶ÄREó=Rc7ü-`-—Ù[àX9ğp¡«²’UÚãã·âÚ‹ H Wf{İúk‰²Ô…ö;ã¡ú®ŸMîÍ
İæ(ñî;WÇ_­ëê»7´•˜şŸÕ•©b[}7™¥¾Ç-h  )„a'e!-^ñ á]™pß«™•€&¨; ±Ñ¯?<fü!‚ui™‚˜òÅşŠ¼¦Ãš+|)!®1DÌäÛ.zI²ÎtfÉú×#Ê7¯ ±$.²²¯•€È=’¯«‰Dn6¯ºAÀ³n‰™¡»’ Fú•K:^ıŞ5ü¾âu5ÿHş÷nt€Ç~S·6=ş²î…Ï=Îîäúùã¼Ê"v”ö?ÂYjŞ¯™cÌ¹â}I’SólÕ{ &SµÏœÙhRé´p†–”2Œñ» Ù†Aô–ã¾2ºö4š^Vbÿ!š$îŸY(ë¤GêßïK|_İ§?óJRõ=Ç³]e´¥ÉqÕØõEDr'1éPa„³>7¦¸S_$Ş]ÃøÌµ­ÛSv#’9Ù9şÒ]M‡Ãïz(IVì	‚ÕÔqÁ?Í‘¿öOù§p¿~òğ÷X%2wéídKBVtÔIw;~pÏaê“ÅBL|Û9¿Ğ]3Â/”>â²¶òğÏ—†êA­‰¹gïLnkû£èÿ²ˆl{Ğ/¸’Øäœ‰|T¾á C°0©Jç*A›^Œ¶Öyª¥æBûÀ	[#éw’’¥o¤@mß1/¯Ù¤‰õ]w](	pg¨¢#àb)ç®aµÔw¾Q0ñù®•”]Õ)Ü¾Ã®Sğ_(T`ß—"â®TÔwIÒÍ4PìÇ¥^‡4ZäËÕ»®#FÓ…¶?QSêæŠDÕK,ñ®³³cĞ/Qeü»õÓ4&ìÒèf2¡ówáç[â^†û•äìüV¥ÿt<Ò*a^IJ‹ó;àpÿê¤MÕHÈfÀ]ÄwT £[n*‘tÈiØ)Kp*Vàq;¤§¦¹ØHæÕ=¢82­ã›ÕzÁDğ%©Çiöv×ÿ9™L~îĞÓP™Ô8v¦’DŸñqÁ|{]Ke.Î£R¾ÑäùçÍÒ-bWJNÓc}U/6•š@½^ÄĞpt
Yw’¬ƒ¨ “|fÂéû~åîîøıì1[§WYpYŸÕ:èµş"xÏåˆ	‘ÔºÇ®áQŠ0D]!F8ÅIcg´¤Vjm=ûï ÉÒv’ğówÄ ——>
N§±¿z€P€Ÿ~9_¢¬¯’XJÌÿˆ-ZÚ«§Ÿ½ [ÔEµš<Ã'"c—0Û©¼O© :I/î&¦êÕ=Ÿ‰vˆ¹ÖçøÊè7£µ¶×'‡DïWrÑ,Çµê‚ßX“ÆÃ¢)İo¬ ÉÚ˜>ÈÌIÉãÙúC­ÔÍZkôF
_šhJ<ÏwÏj«³.\=)W±ÊÈ8£<¹Ù2?Y¡¤59L¥QåÆak 7êrĞÃõo TM	ŠFš!±+I’È¼o':Ú2¡ËøÛ)µ'Ô{ŒŸ¾Ö*eŸGúJ³¶å]§ı:óÔ xÜ»_gìÀ(ûÎ}¥ö7XèCî1m“ıWò¿ç4†ºTßIÑ2È/*ü]¢d‚÷Æ@'¦©ZäU­Ãô2y¨r¨Í@ğ›‘`Œ¤>¯2OZ”æ~ãN>C(ÿõú¾o;¾,ë†[Y©0àWr ‰ú:…äÎ×ká—D‘‹ñÊ›¿VR‘aÁñjµ±‘¥¹82'×ö]33—Åàæ^‰\	ãÔµlR¯·Æå÷·9üæBØ×_>ÀAD‰½Ä]³«SáE¡.ïTØ&5Ççe\çOCQ °º ¾íÖÑÍÀwÉ]u.MU‹=ôGšŒı^WHúÕPYın£ÆOÿ·ĞßŞóı^Ë¿Ñ±ÎÇ™Å
]×ñ0Œˆ#hXöµ§I$ïŠ¢ã›‘DóN$Tßš—Få[èPHÅ!¾LbO5;ó®_n#qkih¯Í«–yºŒ¿eß Ú¦~ø×ŸA8ÔyŒ£›ïßæ‚-1>ùî¿™X4­D©ºP~‚I@ol2h&õP"¦ÕÓ¬1?‚ÄŞ¨;:é.u€§°Ğ÷õßâh1=%Å@{»´hßª±û-ËÜ2bã‹9Ê0 f¸ä]i.é±SØz°ïíidâ½ê¤8ªQÑõ“ouÛ©C[­òH¥’O‰äK§¦û ‹<OŸ"ˆ–ú}%×Ï•l•G>È¤¶ºÍã9+¼•);_­î³ ÿüHmHâã~MbR7¾hhø¼43’ÛMî>Pß;ïŞ‚å\ÿâØç«iÁ`lë^ª*`ç«¤ïÌº
õç‹7ºé®¾Ãè·á9-~",Š…WŸ|Ä1
¾
S³©SnéçáX(ÂlAôf~óŠÈø;#k²ùiXw‘¤>Èº44±'„êÙ×šüÎªÈš"|jIÕém4µÁüh°†u ?ÁV,/œ~>èŸ$Ú°ŒzŒV>>ºÉÏß¯}Jñ²£/½AOÃªZ‚?ŠÔ³
’‰iAËŠí v'7ÊÂ=j ïù9Ÿ6ŞÃ˜ïÏ z`ÃóĞ¿@À*D¥¸ Fo3ßµAßŸóŒ½‡úNº¨Ø(Ğo¿ĞË¶İ,X³”»O´ÓÕÁúù›ï¦ûìÆ°ì¹	Ø´ìI>|Ëã”çY,°2C{>H&`j´ÂÜ^ •R}9CKiÿî¾u|s«šw‚R3¥K¶0Í@N³m\d'o‹'3¡‚z|ÎŠ¾í¼>ó©ø?ÏàFàK2 ç~¹-~§e7éïËÌœË9ÔÓğ˜RnÅ<¥³D{»Ğ¿V.¾gÿ™¡G~­ $Œ¬1_ÆXDeZØy0?ÇìÛàUXıy­{å?ïz)¾å¨Ä08zÆğ%|Ne½ˆs»¯Ä¸Edu´·—åo<FÕı<0¤á(øó½ °À¿’	Z³ÈoÛô…†úÃOG4Zımû½cM\’oKø‡GP~°ÉçtoŞ©µíï9QQÙÅ»Øev>G¬³äƒÆ¿½¬0×¶¹Ru¯‹ÿÓ$Çö«âÒ<N‹/ÎrŒ«¬ÛÅ(ãAæg¾—x•Ñ0£Œ*ŒÈe\¹Eè6ã©Õš­á7ŒüSàÁ¯xÀä<×*ğĞ“íÕ dÌØ¹9;åµG¤À?8ÏÕ‹[³Å[iİÒå; BP„¸V]´•4ÔS\`9(FI		n6 B+<ÑÚy4#üõûå1õÂ¡±>˜.ÆÔ´‹İùÄÀ´²kêÜçä•úÈê¿k(¼ZÏE¦Üd·úp[Éo3Wm?aç¼ı©˜;ÿÒµ½½)Iv}OâÀ+©Ÿ—ÇÚ²¬]ñw°VÄÒÎøà÷û–ïA¸ûzŸ’{õı4²3SÊa`îkÿ”ñ«}Ÿ3ë‘ÊBIrÄ	àHe2Í6Àp­Õ]€^5ñÜdÄ2yR£@Ğîıv¤	‰#š³›
6…/}S¾œö2„Ê%¬c`˜4Æ4©ñê]ƒÎ1"$¶+A<j…ÿu9íR„q`;ù(òıøMÏ‰µµ8Ç%3ZĞ¿Kte%¿T‚‡Ğ&	HÜ¦/fOb‡™-L:-)E$iÉEİ'÷ëxzÜs;¦ê¥øÂWôªƒ´²†ĞnÑvÎ¨ríÇ_`X{.˜Ş²=óæëOßË]évI6Âf4Æu(ÍÔ¯PQgìÄpîq[T±»Óz«Ö{…P«şH	äj¶ôÈß®÷O–¡÷NµÑÖûo½EOÏF–´¿7½ÿL³£¢¬‡ } ¤`mÀe.ÜVxÙWá±Ø™°nb,"íh Š.İ˜ä¹*t:v’ó\§ÉòM¨Ò_ÌË2P%Ÿ/Í‰z@XÌuÿH<ÿÈ˜ô/(„%Î­Ç“©¨ßÂdtöÔ_®²|ÊsÈ Ò¾p5Bü<	SÔt¶H4=â¦œW,PJ_‰Íºâ^¨Ã¸T
ÚoÏp‡f®ğX‘†‡ˆ3 êZ—š÷²‘qÆzV?è}¯ØwåJRßşÉÉX/xï`œ•9v2fskç-ó½Wå[¢ØnqÈŠÂÑº;p»ÖZÙğ¤œ¹$-„FªWÊÛ‹ığÖÍä8ã" LDİ­uvŞ@8c( Iÿì~4[u·sêo»¿b‘œ+Àz·@ˆ—wŒ‚)î³ëª_^—{Ÿò¼äÓÜÕ²¡ÉKúê
"âÙ?BVUúS(ŸşÛ~8_Õå-ğ£ÄïÖøœÇŞ§Zëê‘ße²§¿4‚‰™;ğ}¢.$K‘0]Í†‹·T¾|€ìN ŒH+=¦Ú–†Ë·Ú­J±Aâ³Ø¥êñ÷SÍFèÙƒ‘K®$)”-ğEaoEZüuEº’Añådá­u¥s‘z°€"òçE]×±»2ÔWò%ÅN^}ëÒzê‚¸ºŞo[å\“¦ù4•;ÉçZ¸÷Ë‰b|1ã¾{C>õÃ–C`ê—09éĞ^ÍºŞÙnñß²ål&Ğ ªãL¥Šşvñıg‡ïº¢ğ•¯“a›%Ó5RÌ@îë×—Fz¦Dt>;¾ŸëJe­Ü"ó;[u·=NãkÕ¤"ğ}£÷êİ}„ª%¸ ¦Ù£èœ‘ôWYq@ ÂÀÀÕßËŞ1\>RÔë§	[¿¹ç)U/ó(VÁê‰/DÇ{ù½u¾]>_ç­…,õJƒYm”z]÷ß÷¼Â£¶1Ÿ{ğN” °tî9"óÎíjõ§‹+ZsŠr¤@­Ú”*êtb‡'ÕûëëÜ`Oû™ ¶OšgI<ğ|Ts,æ/xÜEØì{<)´µµíı­BñzsŠw8P="'Or;5æ±ç_2DCÕ¦ØCU«VAÉL×zñÏñâO‰„ø–ºÈçYÔ:_8+s ¼ü S;Œ3ö›—Áô@w¥ ¼Ş•p¯Ïágá¥i4–¡¹.›/Ô»ªJ0ö£{?…È O¢’•õÁ8<¨"İŸ†ÿ6‘:éfdú¥ãÁN¾WzöT€EÖ‚îS*üÁï©iæ–€Šnª	Q'Ò#zë$[à–Z	îwMÉUÉîaˆ¥‘ã½KG|†ìshH|Ä¡iÍA ™BåÒndô4ŞU~¯ “ğO
*	Ó‚÷°î@fnØ’ï'CA’-tı¤@ºÒm4á
Û™G!±¹àœµlVÁO‚ät
 	u)UŸN>B5(Z¸*uÊ9¥–-C®©‡¯yÁ.²…n€4È]4« …z–ö’Bê­ƒğbó¦FËmbkğ§bçü\œå¶É­é,æeNöĞ™f«Vü€jZ÷wW¤²Ë®I­,—îâÃŠ(¨Î“œÇa¼ı|ÉÚ¾gµ_@Ã©Yy@P@]?d!Í’«³di*škÉğ¨>¾‘ßä§{§8g)Cu×aêF±ÚşsûN ^eïIÅz*”ê}ñÖDûî\Ñm³;s9Ä<Ã©^ÇN™3ÿKH=;©@3 Ï£ Ë»~ûÏzê˜ÑVÍ–4V}Ü!¦{ªŠK*(»˜—à,yíæù€pÏİôNRœ•K†™ŸàElt$úı¾GUÈÅÄı)åöšïıRU€©£3!#KŞöÉ¤-Q31*%‡®×!Ô³9É6“ä ¯º¶s\Ê
R–ø:yy/†~øyÜ¾üG^¯´që¾ı¥òä
g<‘ıKXá½%ásp_6±F–% .´‚ZCÀxß‚¬İ¸ş¶¢°X² šÏ‡+WUì:(BÍö7jöìéùD¦P‰]¼¨Us¨ÕÉ:å /z	ê«u ô+üº¦Qq6qŒ9ä)ÕWùÒúÓ1ŸçJ	©ŞvéÙ”&|Ä¸ˆ Ú~Q Ä¨„Zp#—kÊÜa6ß7dßúÀè×¾‚p¹°í;LkLw7g˜÷<â#Ñ¡BL«$fÁ1ŸèJÏ-3—PÄ9öcÆå\öÌaŸ¼ rr%Åò®(™í‚@¥kô¹Oß—xîu [u‡÷äóóıõ‚gãm›ŠÖ3LP””ÉÁ?;0-HªMXÈ÷PÙ'[—S‡(€W¿âh;ĞW£Ş¢[D–miÌ>m©êëçm¹/LP¸.}‘P¥«ögìıUyœ,øÉpÛ…š©Ÿv6¿÷ä{£nêc‚*‘° ÃÊ“ßBüË …ÒÉUÌeØ lá©Ì¼g”“<\_Wtd¡(fd±)}™Qæ{æÔƒò ^çø¢8±w’äoÅzİ®ç„qo(,GQ+_øNa¸,rn:P„È!x¹ç¥;×Û	juÙ(’€¹›ı»[Ú££å šUDäjÅÈô+
_·ú$Ë½`ßï• %—Eñfd£›ÃH¬½CûXªò`ßl^®F¸£ª½²€d#ÿÍ,Æ± _»Œ¸/Ãà[‹0¤°;GMˆ<é#/âÚ[›Ì#KÏƒç”d!øáú{İ5^8i™fÀÒxyÆïhNkw@z®ŒƒîÕg»ÎÉ¸¼=ÿ|Ûn	“TC°¤‘øú%²!w†(¢ğõğ9½¶L.ĞO©ˆ\`Î›Ç	ªQ  †Şãªç	İú&íùZa–ß5héÂ2,\€ÍÆ–²ÈÂùuLjCû:}	RòEªóôÊˆÌèŸ+T½‰åÙÊHq@³-jj> Ø®ÇßwXbd«}ÿ½ÓØ (ğÇzëVZ.<Hÿ¦F"
,õî:kÌü" #;X$·Mì<'h	PCß!İÉ®,Æ6®ü‹ãæB…JRØc'.˜íÌ]>Î­'WµíñÚ÷Fğ®uD¦{ç«”nR©"vA]& ëº6Gš <pãO`¤|´.ÚBqÍ8Y0O|+[L¦âí.8±¡„¬iàş/`=]Z#Í"îĞÜÏc ¦¯ˆë] ›eáÕÎ-F ¹ù€Äÿ¹ª®{—ğThÃ‘jÃ`:ïİ¿oÒaëòSsXãEş˜ØÑêïy¤ `£]·ts™Íûı¾Eïå^dØ½º1ÀÈ¡NJ£²P·X»bç’Îúc?ı¶¾SŒu€—gP°¾Ë$Üï»„öĞyåeòéwëÿäıI×³<²
Îù+ÛtÒ÷¦ï<Ã`zÓşõ%áçû2Ï­»êÖ­S£ªµreæû¤P(±w …º¡†›ñ.¼õ,>!Ş¯e¦Ù‡?¦9Šüf o#l¢;¯éñL‡îY›Æ–&m~®Ãü^îÌKÏ;Ê£xª”‡ }c“sìÚ:Êéq+Z´†÷7XÍÑ,ëS›–Ì¡¾ôWtu.+Â¤ÄÙ¹/Õ”’ı
¼ğùöQîTY1Ôk3`ó¡J†XWŞ[…Ûõ‰1 DéUnıé,À|Aói§xxß–ãSa™ßõ…_p^æŒˆ¹šÕÆ¢L ÁI£­È“e±÷»Ÿ^öìëùÊëåKşdg+¹ªPmc¼OğË‰*Œl›N§0@ókŒ)¯¸~ùZ×‡2#
{:w‰ÇWĞÓµ ˜÷Ÿî>nY¤FTdš:Î˜ìuç›4Äâ€np/Àí^µÈÈ|+w¦Ì¼ƒú>ƒE†²wå%´¦ì8ü&Á@ô'%@ƒ ‰|s×C"	p#©æ/ïşl­u-sø!‡]¢émÉ7OCk*+”C»
´LÈ6n5Ü9o¢ú´ÛûCaõ¸38ÂøpoŸ5û¨"ÃÜ«Tyær&?
Ñ\y(Û0h’ÌeÛ‹0CÂïQÜÖô½k	“&éò‰¹4å(yÃ³“ÓiÃ§½tÉ×¾Ğ4á4èÜ£ÄÌåÔÕgÎu@¨÷ı“Öûğj÷”<[f)m÷3¾Ë¬Éµ¹-‹Qb.=Õ© ëÙ»°.+wÓ¼f§úÊõVı:ô%
é•ÃgZ\‰sk¾ÃÑá	{¨O¤¨a <¬íhM—„PŒ	¦0(C›T±t›táu&1´@[À0y5t‡©ˆA)Øö³¸ e×6@ŒK¨æä±å†™ä‚½A·Æõèµä½—}oÇa4şÓ+v|DîßœhÕ=×q+BV¶´ŸšQÀÂ
ø„‡}½³_!ˆº(Í¨İİ#Ì”hgé~æ›gbŞ§xèÃpn‡k ø7qç½OU}n¥œ`ÇÔ¯=Ì£ÔÀ1ong	@åÀÇfHX¶õõÒcf¾ó|ëà€çß0+ûÂ„Ky–æí22(QOâñıà «ı“ûy–
‚ƒ ÑåDêÌ§RM$+¾Oó2q]
À¾jŒ‡¹æ,ZızÆXfk<m€Ëï_Íœ#ÿ¦SBÚôq¢&6˜å>šöœ$w ƒám¤ãÉJoïEW!õ\õßæ±zíı>
şÛB{©‡HÂK8
ŠÓûÒÆ =Û3KçîâvŞ€›w·ê§}áãzn²Ô·Ènóvz¹ßYÔ³Rc|?‘™Ş>sRf¦Œ€†ßÔS¢ŠµÇüÑè’šÃõ+7¾UÀ²ÎÌÁó´›‰=Î~šæa°mÛmëœ37"9æ¡T;%DÓËÕù.–û½[Ù¸<Y}Å;ÏQ3UÜK
zÆüq"°Û÷…²:3÷ÒE!%m2Z÷ğ6íÃ¦~Në‚Û«­¾sé€v?—úh\“&uƒÇM…R­ícw°¡%-­—‡$ò=LŞÅy|ZØºw\²JqïÔ„cq‡ÈWöƒYRãxàŞêCÂµ™B#'r‹!²KİÄ'µS@ÇÏ4Çlö©××“Ûg¸\!¥ù¹Zşefk~hvËF‚ğÒ@Ã€¼(˜…ïÿt»(5·iw°zÈ#f€¢3S†Ü¬˜Ü€/ùÓ¶ítS\ÄÃ6¯åB…§¥ Ş:ÎFÀ33vÉzPëv¹ş3Ã9ugÕÁ]RçQ¢æ=Œ‹Ë\R¿ÑxÈDŠO>àq0æÍ<(¼%—Äé0Ğ5~‡·šR1Ÿ8àrqÀç³Eœ,d—•œ?q´’rKBpĞåaï2–ïœú­G{[=‡ ½şò†±„¸èø9CıV¾”q‰·šs] cğ%6ä¶}LF¶¹D7ºcr6¾eÇ$˜…¹6ô°ÜãñÔ	Ê1¹d<³à­‚‹rM 8¸$æ{<v;äxæJ-‚û•ÙªqÆON:.EéwgIÇ‹<Ñ‘tˆ7Î7rN—èÒ¯ôšÑÆql5åWÏTì§a>7ì»%2ôgM”Ëépıö´jzÊf>ø¬XÀ¶ã†­øIüëúäUîeåĞµ9İÈƒ&–öü2 ÔhvnÀjî3…åÎI¯QPöÃİÆ$ı­3Ï=­™œ§j˜ĞOÔdßó¸]{¬™OXÉ”üdVaš¯&1ƒ¥ B`ÑœÜ+I„~Ahş¸g÷rnÖõÊ~oë’Ú€ùÓí®9‚YÇlŞ2·EƒCºœÔW8ôfCº`şâ7š”ÈŞ…âè[f³4G‡[…“Cœ™Kr'âóEÆL0ĞOPkúåˆ?2éQ(O£wÃ> ı©›Ş°ºC¬qô<ì³V*ÕÛ÷	¼—ØnŒ„~êğ€5Ù‘_Ğu?Ì ‡a4O@ûYüÚ×ïkI7e5}Š5á+â2Æ!ÿ	ì«5‘_H.ŠU8Ğ<êÓpåíÙç„f×v:£7àÂk_·ï|ª­±õyûjwb‹3ö¢a 8A}Š“4`ÊQü87ş­¶Ô2Jğkÿ
Œq=|é	ü"Èìwe^LnVç/n=kû€¤6õ.¼û&­ÜòåE[q¿\ÌS”zº±Kïª#¼2ğ_ŸÃ…^ÊÌ¾£­Öúu‚,yÂÇ:DqApx°s5µnÈı>|Á­úíÄ7Ä[b°š©Ïw?x%àÙ–sš^êßå!<xÙD´@İ/k¢º.CÜ+Ú§µP¦)ˆ';‚É««m]cÑà¸«y"6ÎE¬jÁØQÃ‹;œÿr[PãìÕ7Ly÷5îö] *<[`º,-ÃH¿Lëó4V—p+9æGWF$Ğµì%ñ‘?O+²o´ï >ú!TÚ§ËZö+T§ö¾LÔ,tÜµYºûís@Çæ|„Órü`_<“£²K° z	§YÁ‹ìÖC2Ùò›ÒÁôyğ¸\p"ş˜UòúÕ^jÿ°g!÷}ìom@Ÿ^QÛ”.Â=GÀr$¶½ºíFìPÃFî¼ªVAbš´:ÀcCWØÒûP[¿ßÃà%<7‚ö=à7êa.äÿ³{ ù=Ğ´%ø‚š×ªGq$aôšë³W&€ß¾x«2ğNÛş
G+Ó%-O¢ÎTğFW fjç4ÛYAö;¤ûg Xj}¼^İ[1=ÍG—ÚI}O ˆÁÓÃ_õt
ñÉu‹ÙË§óÃ™4şõ»ß8zˆyùìÑôähI¤\ÿÅörî@®Ÿx (—p€7¼“„Ñ÷5 Y´·uj~·ÙštˆıIÆşUqßGó»Ø¿vaw»ÑğşÅ–ˆß•ÓäË}<Ë__ò__˜j&¿ë„?6{/!ùgJQC«ƒ´®OæùÏÀåœıSƒìèá…¤Ÿ.5¦£ëJ4¯…|Ä,¢à¢Q¬pĞ`" 
şSÒ>&Û/(¶³Î»È«£5O}³ û÷SOP]gğ¬Mó'ÂöÈV@³û]ÇÇCñ,yF…œÖœ+3ÎešÎ Qş9ÿT©1«Ïû?kdİŞ–› aW<áíÅp¿00ŠŸ5hÿL0RíÊU ÊH`ï	Ş	Á4ËºR°nÿ¯6>VçßçöÈ›UûM¹áxe¯¿ûÈYŞ(Ø‚h˜@-Gÿ*ï§s=w,“*oŒxÏv/6§ùsÊšps µş7šhBö†˜MR©Ñ¹GEº±<ƒ~Bµ>”') z¡LPË%şs£3ù–6Ùb‘’ŠØÜñ",¿‡ÿ¦d€¯)¸š¹
KsôŸ
˜xUÀ[°%/ÃØÿx<zôxCäE¡5‚ş_ß-ù?na¸¼¶ÿàÇ\æŞJ¯éa?iôKb¼_¤FDX4Rô´#ş93(s¶¾4ó¸#–ö°ØùİuäÒÜW½€LNı,„i×$¶£=	CVîáßcàV/¼lşKIî~&ë“‘õ¼Áâ|?VÜŞTüˆ½X*w“'e†pR}òÊyĞM^ÕêÀe4{¹—Nn¥Ë„æ> HíZê%º|ÀFzÚ†¹±]Ğµù²ïà‘¥<[ïJ&cöRM¾Lô`\øˆ’dÍ^Xä\Ü%AşÚ¾û‹ıfbíÉ„³m–’HÕŸ÷OI’$qex=5v#»=Ç'¥cXœöíÍÓÓêlñÙ`yÒZ!š!'v¼®
o5q© {Ò¯oğ:ç‚•tZb+aÊ¼a;ØófPL8;öÎyÒÏœvîäs¡ŞÈëãäç\cœÇ·’&ªŒî¸ 5u«~—é«¯ö¤çD›HI8_›ët"˜ ùYRİ²Ñu±´]Lp1ªuå&eâáPØyzš)=áù¾ÈWŞÉ)¶
¹¶iîø©r3§D…Va Ûpz—‹‹Ÿ/ú¨zbGÒ"Ï1E*ši"P¾y.ÅVF‡Óã“;J(¦Òƒ§Öß ú>üãÕÈÅ9\2,»ÔôÊJ4YédÔŸ•tş´”0ô½ĞÃë>úãCHì¼µ6{,­JŞwÆ;õl°Ç’­#²kÆĞ×ÙÒ.F¶—h”÷:*ƒ¡ïöåû}é‡¼ÌëzT¬ZàQ!rÃ	«ªÊƒç_’sÅ¿R²qş©Õó¼çV}Úó#Ù>ïÃÓŒ°O¶¡JËÜ˜‡+„yo—R¿UÛV¬ËÍßùF¤¨m@…ˆä‹s$ŸìåQpmõ}4=,6=øiv½ êÿ¶‡3ö´|»çŞûxËFZiê!wÅ‹ƒ¯äw~^LŒ¿)Ì›yœù‘RLï²E¨G„4¹XP•+xu/sg®5w1ñ+"Ş]!f'İ?fg¯I2iËÒVqèYJ2÷÷^|CË³7¤ÒÌì4?‰ \_æ¬šşÃ'c®ÇbCŞ59¸@ß¿ÂÅƒ•¢qE©kşºò¦ø=,ÕÅÒ'.Ñ9ŸîÔŸ¨&
p™¥ÊÜíØc±T°?„TçSšMğÌïsı¦”ëkËØc‰•Ó†ÛÈVŸ³Ëê@™ËßğÔ"J«¥$ä­‘fi^–|õhD•åzğhp¢×8ÍÔNHG3ì&OIò¾W¢í$¢:{æ Ÿµêl1ğëÚßï²EÄ4"›<s2T'û|¨èÄÍÎš§Æö{•¬^g(½.7)ŠÎñ¥ºBm|Íªh_¹“ÊÜ—JšèØÆ"ÕÆŸ¿eÆÆ pç©q¹¦Ã½»p¯äšPwï±“ŒÒ5è•†Pgİq|:½~wÅŞŸ(nUzÌÇ¤‘¸òõ•µJ'T…Úº™—n£Ç$c«O•™¼-æáu¶÷¯u”·„ç[Ucúı´"b}§ïˆaxïsöî™k\=“²ÀfÀsm_¿W¿T„)S‘Ïëˆo«<¤jmïš\	™û¯Å.ÒÅ¿k-†œ-«‹ùîĞ-’ÂÓP…Ób-vyşõ~TÜÒ}üò=İÒìûÊ…Ü¬µ“ÌqˆZ÷:‚ üìÏ€3z|ßóûdÛ™¬ÖÆëzâ”e7Z£3Å[»†<{”ÑKOæı±®ë’<í9Ó‡¦5‚ùBéùc7íğò¸«õñãıñ±ÁÃÃŒ ìÑ\Œ ÒIâgu;!×¦>ò×ÃÅ[>Œ˜ßØ—süuÙ7í¿mØÖí’†ÓÔbİñL¼ ?ã½wøĞ¹¸Ÿ»~ÿ.ˆœFR»Çë<<Û)—êj©o'Và¾Úø0\›×1Ë¨ÇÉŒP'¨÷j¾j<µú*À?c¬ ·˜¼ğ$SÎxo˜¦<…S—¨Z±}¼i?|îî—`s5‡uRÆá:Ä–Åv»§%&&õÀKÌ¤ˆÿ¿»
69ÙÂaÂ‚Ñèï"·¼ğ,'‡Ç‚ÿHaGlğ¶"bÅÏé*§\Aı»Kä¤ø½ƒTÊÂZøÜQ8ûZ¤²¡2à«;Úoş àÿÀ¸Ÿ\£t^“D¦ÜÈ«Ï8M«Ñ;Å)w©!.;üá;ˆ=ÿĞ*è’a_Äçh}`Ş’îd$! 3“õl—|¿oiİiÓşÃF0é@Hn¼ŠÛ€@8d	è‹©ò_/ò~ 8+EWÀEzèhİzq	Jaë ZÄ²…;ÿâÿ¡A´‹@¶Tk¨øŠxÃ]T´ d¿-IÏ¯úğï ş³äøq.®¨y(ùïCšk„ÂuÿÎÕaÙêâ¼y<a l	Ù;¢×GeEõ£C ûùç^,Nªà¼ÑL™€¹j7>DÃéğÇ±€Æÿ£
€«óê¢§Æxtno„o˜—›yèy×ÄòG~š€³$í{?PÉ'PÑÑšìwöqÙß3‘+d$ÿOS,Oàåêø2Z/»³W–•5 ı¾g}óÛ4óğ¿)pş?“ã84¸A»²´½ĞZò İ|£ØL¯ÿó¶°H»ş ¿y•CŒ‹úÎ8£…¯´¿^òÀÿÎPšW
Úêo5€A =O'şÃŠê)÷é=À*Ş1¤4`<ÅH,`SLäıß[½Èÿ[Ë=¾Éâğ½3pq×ÛIó
ï™w E)9=I[%Jo$fy¶(Y÷)?¦²¼32-”µV¡ÂÙ9ëdiaJV3.$k\ÏxÆ'f³zÒƒ®TÉxÌ¤	€äÓ‘æÁTj^ÕfŠ¸¥ÑsNl¼Ëãóä¾ğ‹¯½˜ãItƒ7ëéšwáõ‹ê²Eù®úôáËlçê¡Oğ)ß€¤ËÔkƒ+"îgí')ı²¹:8éÁ"„-Üÿ;ºÇ‡|£á£I~´s\½7€j‰GÎ€fÆõ‡em^VHÄ×Q	l‰Å´,¡fKÅ=Ö5y[Ò”p~4ĞÅ¹G>$‘šíşæóø{sé³ÏlµE¯ÈC=kÓbM¹ÖŞÛ;aË«K~)y ïTÜõ°òİ…ìsÍ2†»ÅÓÇäÃœ™í­ğÊm¼;šÈÌ7	§ï
`Ô3”ŒC·¿ çh?àlğJC$A<—¨öæÜók—–íôóB	úõ¬ÖcVeeù&CWÛlíL.ÜaøO;ûF¿_;	ò!D¹ùrøŠ4Ïä5tnOÎ5Íäê
ìü©hê¢ze
Tıî}Œé¾Fïß58Îq¸pä¥Ú…mòÒˆuyöF—Ï/_¼Œå›r]«Í-TP±kc_é¾ı¼] 7wºêƒ °‡ñÑ»™¯(Şœü‰
Õ‚á³ûæs	àN·ÑO‰mxìˆ¹wl!/ö’Ä¶öm©~âH^€öY¸ibÀØˆÿyğãğºÖù(¡®÷—L¶àÂ£èJék&‡O6µò4¼f„ñµ!Q$X! 8è'*Ù=äŸ6ù
ê';Z¡T<Ã%}»)Ï³rVó$ #|³‡ÿŒ Q¯Å¦©TÉ}"=İê¹"†¼¹_^„Å[dé_t‡qª.öƒ;©‰Ø­œ.¼ßŸgòÎur öåı ìg=`}ö³Åí×œmy7Ù€G}¾—s1æ¾³õlãĞŸA±36ïï¬Ú|µ4†Üæ²–Òİ©­¶ÀÊğ]¦ÀtüVúB«}’w»%ù¡¶yÿDˆ’7Wg_B;ızí üOÄÃ¼ÁÎ‚—-²@ŸuÆvğÎUIİâƒ	ë'€h®7ÒKŠ¼ö’qøg1›*edQÑöÒL!á‰@½JÂ¦†i¹,œâ6’Î‚}ZÁy}¯Ëy"q4ÊY|®[;¯·ï§?U6|¯úÿ¿;ä¨Ş™Ú†§Í”a{7ßBzhN”°ğSç²ô’dÔ¢jWD#½µˆıë—²rI²gÿ»©ÎW<ñ F4Ä{ë‹ğ„GcğZ„¨×Ë¬,–|S«Ym²“¸õØÕ;AØ!uîı˜Äy•E–Jp$F+d© õ2ğ/m°çµ‰:iÑœŒö{/w²C‘ªM%nìS®NX	¸ş•¨®-Ö,j›EkOĞ…nÉÒZjôûu³Š³€æImÜM\[??Êÿ4$UX¤éüÜ/Ç„(ttzëÅ9ÓÕ#ÍĞîÜ•–<Ég•û æx"Q1ğ¥ÃKÚÈ¹„8Û[àİ£z&­ß÷€mÖªÍ¦5•²áz©×8Áh®ÀŸcëBßfŞçoîƒçP‚ÎÎsÔ&‘ôæ¾
z›ŒÈrÓâpO<všîI¨ÑÇz«kLƒw¹¢ªï¹BñÂúP<I@'-òğ†æ/<¸ –œÿÓ}±[¬öUÌö>… Ø"İ0^ê#À6¼&)û~È—a»¬Èô¹zıçˆyFJ¾Îª?7N=-}æáç“Ú»ó.Ò^eÇ…ñ
°CÓ-möâ‘°¨ıRÍ½‡ô'ĞÍ%h(ådÓ‹å4MûRğä¢Ds»‚¥™-Ïñ7C€±;"¿ hæİ+šú¹¶ò²ûÑÂsË—Å\'Ö¢rÚ:¢’Œ×r¯íWû*Ë™ÕÔË<óÖ¦šàğåõôäDr¿å‘»õE èåÍï¡!û{7õ Îcíe®™¼Ë8Óº8B˜`˜õŞÚq×r? ¬W7qıú‘UZ<6àTQC±ãízXå‰áóS¢wÁä£@üŠ8p®¼çsâ+æ	,÷7˜r3Ä>œ€©¼‚s·DïËÑÁ…¨•ä¶dş=½+‹L/V_Ôz‘°:~¡{º0á —ÜJàa‘ùì0“2­n™ŞÛHŠš;¡É¤äb„KCgsêLôŞ<Å}0Ï·9JQP]°ÎÍYÔˆ;£=’âÁ4’ğBõş-7rQŠÆ²¦£|u½dofÉo,Üv©~¹.ÖSe¸˜Ú&<JŸ,Û½Ûét<7Í©ò—,Áí[‰Â¼ô°¸#ûÍ‘øÛjSß³átR·aW§öF[ß¢:Ó²”‡X¼é[¥ègÔóŠÁ’wqìAQÇü²ÚW?®xvmtûZ‰™ _¯!F5ö¹Ä5Ù—š*ÿ`ö£AîÅi"‹.´‰XÈ€¯‡ô´€{Å°W"Ä®l{zÕ—©¢~
Šôssq’úS%®DëYš&¾ÙŠv–rò]öóõt]tmÀM/7È/~<GRúHeŠª/§rsáÛF/âİé´İOßR¢¯ÎXš !l÷w«¸™šŞzî6~!Û˜ºÇ¢jÙ©œİ©qëMP’É²¹ú ³€£*¸OØújĞSzJ¤k.Ú¤ä‘Ù<Q²+&®'¶iŞ¦ Ú£cÎÏ²@ü¤˜õÓÚLÂÑ†!¤{@&ğzÃ('ñAa?XTäÁ™_çó¾c£—¶^¿W5i«#xù½¯ïsty:›.Àß5ûJ–òêôêáşÂ=
’Fıü˜h¿=Í+§6= !x*«Wú›F‚Ÿ¨ú|K¦F°ÿyç8×O8úekœú@KU}/îd¤ K´‰tÄg¯9ˆ?ÆŸ„Èğ ;â™ªÕ7._·½Ç|şìü¹¶lŞJ,¦ÓÛØ¼4r¯t[İ±ÀçZü÷ÓmYäK®&•Iˆı¶T³‚øZé’ˆpR%ámë¿Ó‘b.“pü¾2nº'ß.}áöC”^öÄ7ñêæYiåÂ•ş¿ş˜Üù?'årYŒê¥·-t%ôQ¾ÛÁ‹˜91Ñœ[vwŞ77zk|®è“ñx§Ût Ùoá›ïyd
~°Ş ÍQ=¬kK^ßmN9ji><‘,¾ŠıîdK’İ Ò‰¢Ù­‘ôIÇ,,±ËØÎİ»Rš.Ç1F·½ÛÏ'Ká!¯‡	À~7¤»‡pß„×„8R›º§mğÜ{­'%=pğWŠOè¡¼Úš—¯\P>´—æIoó˜â=u—À§¹—©øö÷†s4>Ø<É<¤·µzæÛ¼Ş½»wl"ÃhôÁ$/Ï§ÜÄsø‹ÜÑ,•\Âô±>Ò„‰‹^ÒY?Lßiüt–.;Uï~’¬¿ó7§5U3ª¯)M©:zõ~ˆ/%Vö€KÈ=üò­UÓõJVÆZ=n€İ©jël¦LÕi ÚRü<×È}Ş/õİdtÂnzU‰Ì¬ÿ<Ø—66¡g_Ò¢M/‰¹œu¼û–z;¯QÙÂ÷atğyÁ^»¢¶Ş‰_|“Ç÷€bZÕ‡GµöÓCÒ§K2@ó‰¯Şxî6p2Zî$‰¥¨·l^'‰‚u¶Ã5:ÉˆŞ‰<ÅòDÕs¢J–GÎM{¼;/Ç¹®‹5m#^bÉ¤wÃÙwáe»_B DÑa<'Ü8¡àTrÁ²w™%¦DHã¸ó=Ø^`< 6Ó”!gKM|ŞÏw@r»fõÚ›íL0´o©¾ú…w?İ¯—$=-†\V+Òu©H=uşœÁIœËŞ¬-«¨U'TjŒ[:m*-ê‡3à¤KÚ‚P™şÆ®Ø«Ø-Ü‹Çí"ÄJŸ^i‡BÒ/Òışğœ§ÓÕdÅrwHµË5uºôñ³àSè	6¿V§³TjSs Š)˜)"<eñ¹|…/´&ôÇ}¡…†&ÕJªí<§\9ıÒ[€ç#½mê)¬+1çlnnVá¤n<ö%]½Ç¦­Öf³oÍµ·%&vÂŸG0¢À-ıY¥QQkoèœ<[Û·Îw<ıª•Y%„¿¦“ÌŠ¢Á‘>0vŞC,ùı?ßbƒ!_íşâ¨7'Ù3X¼ °ÈC5¯Ø'E‚yÍ®ßTkáæüÚıëë8öI~1Õç_²{I÷Öûä?\0Ì£”¬7?fCËR_|M.‚)^yò|[ÆL½¶Èdæ9Û…ß0R¸Snl¥gt) { // loadtime before		
		this.iframe.style.height = this.body.style.height = this.getIframePageHeight() +'px';
	}
	this.innerContent.appendChild(hs.clearing);
	if (!this.x.full) this.x.full = this.innerContent.offsetWidth;
    this.y.full = this.innerContent.offsetHeight;
    this.innerContent.removeChild(hs.clearing);
    if (hs.ie && this.newHeight > parseInt(this.innerContent.currentStyle.height)) { // ie css bug
		this.newHeight = parseInt(this.innerContent.currentStyle.height);
	}
	hs.setStyles( this.wrapper, { position: 'absolute',	padding: '0'});
	hs.setStyles( this.content, { width: this.x.t +'px',	height: this.y.t +'px'});
},

getIframePageHeight : function() {
	var h;
	try {
		var doc = this.iframe.contentDocument || this.iframe.contentWindow.document;
		var clearing = doc.createElement('div');
		clearing.style.clear = 'both';
		doc.body.appendChild(clearing);
		h = clearing.offsetTop;
		if (hs.ie) h += parseInt(doc.body.currentStyle.marginTop) 
			+ parseInt(doc.body.currentStyle.marginBottom) - 1;
	} catch (e) { // other domain
		h = 300;
	}
	return h;
},
correctIframeSize : function () {
	var wDiff = this.innerContent.offsetWidth - this.ruler.offsetWidth;
	if (wDiff < 0) wDiff = 0;
	
	var hDiff = this.innerContent.offsetHeight - this.body.offsetHeight;
	
    hs.setStyles(this.iframe, { width: (this.x.size - wDiff) +'px', 
		height: (this.y.size - hDiff) +'px' });
    hs.setStyles(this.body, { width: this.iframe.style.width, 
    	height: this.iframe.style.height });
    	
    this.scrollingContent = this.iframe;
    this.scrollerDiv = this.scrollingContent;
},
htmlSizeOperations : function () {
	
	this.setObjContainerSize(this.innerContent);
	
	
	if (this.objectType == 'swf' && this.objectLoadTime == 'before') this.writeExtendedContent();	
	
    // handle minimum size
    if (this.x.size < this.x.full && !this.allowWidthReduction) this.x.size = this.x.full;
    if (this.y.size < this.y.full && !this.allowHeightReduction) this.y.size = this.y.full;
    this.scrollerDiv = this.innerContent;
    hs.setStyles(this.mediumContent, { 
		width: this.x.size +'px',
		position: 'relative',
		left: (this.x.pos - this.x.tpos) +'px',
		top: (this.y.pos - this.y.tpos) +'px'
	});
    hs.setStyles(this.innerContent, { 
    	border: 'none', 
    	width: 'auto', 
    	height: 'auto'
    });
	var node = hs.getElementByClass(this.innerContent, 'DIV', 'highslide-body');
    if (node && !/(iframe|swf)/.test(this.objectType)) {
    	var cNode = node; // wrap to get true size
    	node = hs.createElement(cNode.nodeName, null, {overflow: 'hidden'}, null, true);
    	cNode.parentNode.insertBefore(node, cNode);
    	node.appendChild(hs.clearing); // IE6
    	node.appendChild(cNode);
    	
    	var wDiff = this.innerContent.offsetWidth - node.offsetWidth;
    	var hDiff = this.innerContent.offsetHeight - node.offsetHeight;
    	node.removeChild(hs.clearing);
    	
    	var kdeBugCorr = hs.safari || navigator.vendor == 'KDE' ? 1 : 0; // KDE repainting bug
    	hs.setStyles(node, { 
    			width: (this.x.size - wDiff - kdeBugCorr) +'px', 
    			height: (this.y.size - hDiff) +'px',
    			overflow: 'auto', 
    			position: 'relative' 
    		} 
    	);
		if (kdeBugCorr && cNode.offsetHeight > node.offsetHeight)	{
    		node.style.width = (parseInt(node.style.width) + kdeBugCorr) + 'px';
		}
    	this.scrollingContent = node;
    	this.scrollerDiv = this.scrollingContent;
	}
    if (this.iframe && this.objectLoadTime == 'before') this.correctIframeSize();
    if (!this.scrollingContent && this.y.size < this.mediumContent.offsetHeight) this.scrollerDiv = this.content;
	
	if (this.scrollerDiv == this.content && !this.allowWidthReduction && !/(iframe|swf)/.test(this.objectType)) {
		this.x.size += 17; // room for scrollbars
	}
	if (this.scrollerDiv && this.scrollerDiv.offsetHeight > this.scrollerDiv.parentNode.offsetHeight) {
		setTimeout("try { hs.expanders["+ this.key +"].scrollerDiv.style.overflow = 'auto'; } catch(e) {}",
			 hs.expandDuration);
	}
},

justify : function (p, moveOnly) {
	var tgtArr, tgt = p.target, dim = p == this.x ? 'x' : 'y';
	
		var hasMovedMin = false;
		
		var allowReduce = hs.allowSizeReduction;
			p.pos = Math.round(p.pos - ((p.get('wsize') - p.t) / 2));
		if (p.pos < p.scroll + p.marginMin) {
			p.pos = p.scroll + p.marginMin;
			hasMovedMin = true;		
		}
		if (!moveOnly && p.size < p.minSize) {
			p.size = p.minSize;
			allowReduce = false;
		}
		if (p.pos + p.get('wsize') > p.scroll + p.clientSize - p.marginMax) {
			if (!moveOnly && hasMovedMin && allowReduce) {
				p.size = p.get('fitsize'); // can't expand more
			} else if (p.get('wsize') < p.get('fitsize')) {
				p.pos = p.scroll + p.clientSize - p.marginMax - p.get('wsize');
			} else { // image larger than viewport
				p.pos = p.scroll + p.marginMin;
				if (!moveOnly && allowReduce) p.size = p.get('fitsize');
			}			
		}
		
		if (!moveOnly && p.size < p.minSize) {
			p.size = p.minSize;
			allowReduce = false;
		}
		
	
		
	if (p.pos < p.marginMin) {
		var tmpMin = p.pos;
		p.pos = p.marginMin; 
		
		if (allowReduce && !moveOnly) p.size = p.size - (p.pos - tmpMin);
		
	}
},

correctRatio : function(ratio) {
	var x = this.x, y = this.y;
	var changed = false;
	if (x.size / y.size > ratio) { // width greater
		x.size = y.size * ratio;
		if (x.size < x.minSize) { // below minWidth
			if (hs.padToMinWidth) x.imgSize = x.size;			
			x.size = x.minSize;
			if (!x.imgSize)
			y.size = x.size / ratio;
		}
		changed = true;
	
	} else if (x.size / y.size < ratio) { // height greater
		var tmpHeight = y.size;
		y.size = x.size / ratio;
		changed = true;
	}
	this.fitOverlayBox(ratio);
	
	if (changed) {
		x.pos = x.tpos - x.cb + x.tb;
		x.minSize = x.size;
		this.justify(x, true);
	
		y.pos = y.tpos - y.cb + y.tb;
		y.minSize = y.size;
		this.justify(y, true);
		if (this.overlayBox) this.sizeOverlayBox();
	}
},
fitOverlayBox : function(ratio) {
	var x = this.x, y = this.y;
	if (this.overlayBox) {
		while (y.size > this.minHeight && x.size > this.minWidth 
				&&  y.get('wsize')  > y.get('fitsize')) {
			y.size -= 10;
			if (ratio) x.size = y.size * ratio;
			this.sizeOverlayBox(0, 1);
		}
	}
},

show : function () {
	this.doShowHide('hidden');
	// Apply size change
	this.changeSize(
		1,
		{ 
			xpos: this.x.tpos + this.x.tb - this.x.cb,
			ypos: this.y.tpos + this.y.tb - this.y.cb,
			xsize: this.x.t,
			ysize: this.y.t,
			xp1: 0,
			xp2: 0,
			yp1: 0,
			yp2: 0,
			ximgSize: this.x.t,
			ximgPad: 0,
			o: hs.outlineStartOffset
		},
		{
			xpos: this.x.pos,
			ypos: this.y.pos,
			xsize: this.x.size,
			ysize: this.y.size,
			xp1: this.x.p1,
			yp1: this.y.p1,
			xp2: this.x.p2,
			yp2: this.y.p2,
			ximgSize: this.x.imgSize,
			ximgPad: this.x.get('imgPad'),
			o: this.outline ? this.outline.offset : 0
		},
		hs.expandDuration
	);
},

changeSize : function(up, from, to, dur) {
	
	if (this.outline && !this.outlineWhileAnimating) {
		if (up) this.outline.setPosition(this);
		else this.outline.destroy(
				(this.isHtml && this.preserveContent));
	}
	
	
	if (!up && this.overlayBox) {
		if (this.isHtml && this.preserveContent) {
			this.overlayBox.style.top = '-9999px';
			hs.container.appendChild(this.overlayBox);
		} else
		hs.discardElement(this.overlayBox);
	}
	if (this.fadeInOut) {
		from.op = up ? 0 : 1;
		to.op = up;
	}
	var t,
		exp = this,
		easing = Math[this.easing] || Math.easeInQuad,
		steps = (up ? hs.expandSteps : hs.restoreSteps) || parseInt(dur / 25) || 1;
	if (!up) easing = Math[this.easingClose] || easing;
	for (var i = 1; i <= steps ; i++) {
		t = Math.round(i * (dur / steps));
		
		(function(){
			var pI = i, size = {};
			
			for (var x in from) {
				size[x] = easing(t, from[x], to[x] - from[x], dur);
				if (isNaN(size[x])) size[x] = to[x];
				if (!/^op$/.test(x)) size[x] = Math.round(size[x]);
			}
			setTimeout ( function() {
				if (up && pI == 1) {
					exp.content.style.visibility = 'visible';
					exp.a.className += ' highslide-active-anchor';
				}
				exp.setSize(size);
			}, t);				
		})();
	}
	
	if (up) { 
			
		setTimeout(function() {
			if (exp.outline) exp.outline.table.style.visibility = "visible";
		}, t);
		setTimeout(function() {
			exp.afterExpand();
		}, t + 50);
	}
	else setTimeout(function() { exp.afterClose(); }, t);
},

setSize : function (to) {
	try {		
		if (to.op) hs.setStyles(this.wrapper, { opacity: to.op });
		hs.setStyles ( this.wrapper, {
			width : (to.xsize +to.xp1 + to.xp2 +
				2 * this.x.cb) +'px',
			height : (to.ysize +to.yp1 + to.yp2 +
				2 * this.y.cb) +'px',
			left: to.xpos +'px',
			top: to.ypos +'px'
		});
		hs.setStyles(this.content, {
			top: to.yp1 +'px',
			left: (to.xp1 + to.ximgPad) +'px',
			width: (to.ximgSize ||to.xsize) +'px',
			height: to.ysize +'px'
		});
		if (this.isHtml) {
			hs.setStyles(this.mediumContent, { 
				left: (this.x.pos - to.xpos 
					+ this.x.p1 - to.xp1) +'px',
				top: (this.y.pos - to.ypos 
					+ this.y.p1 - to.yp1) +'px' 
			});			
			this.innerContent.style.visibility = 'visible';
		}
		
		if (this.outline && this.outlineWhileAnimating) {
			var o = this.outline.offset - to.o;
			this.outline.setPosition(this, {
				x: to.xpos + o, 
				y: to.ypos + o, 
				w: to.xsize + to.xp1 + to.xp2 + - 2 * o, 
				h: to.ysize + to.yp1 + to.yp2 + - 2 * o
			}, 1);
		}
			
		this.wrapper.style.visibility = 'visible';
		
	} catch (e) {
		window.location.href = this.src;	
	}
},


afterExpand : function() {
	this.isExpanded = true;	
	this.focus();
	
	if (this.isHtml && this.objectLoadTime == 'after') this.writeExtendedContent();
	
	if (this.isHtml) {
		if (this.iframe) {
			try {
				var exp = this,
					doc = this.iframe.contentDocument || this.iframe.contentWindow.document;
				hs.addEventListener(doc, 'mousedown', function () {
					if (hs.focusKey != exp.key) exp.focus();
				});
			} catch(e) {}
			if (hs.ie && typeof this.isClosing != 'boolean') // first open 
				this.iframe.style.width = (this.objectWidth - 1) +'px'; // hasLayout
		}
	}
	this.prepareNextOutline();
	
	
	var p = hs.page, mX = hs.mouse.x + p.scrollLeft, mY = hs.mouse.y + p.scrollTop;
	this.mouseIsOver = this.x.pos < mX && mX < this.x.pos + this.x.get('wsize')
		&& this.y.pos < mY && mY < this.y.pos + this.y.get('wsize');
	
	if (this.overlayBox) this.showOverlays();
	
},


prepareNextOutline : function() {
	var key = this.key;
	var outlineType = this.outlineType;
	new hs.Outline(outlineType, 
		function () { try { hs.expanders[key].preloadNext(); } catch (e) {} });
},


preloadNext : function() {
	var next = this.getAdjacentAnchor(1);
	if (next && next.onclick.toString().match(/hs\.expand/)) 
		var img = hs.createElement('img', { src: hs.getSrc(next) });
},


getAdjacentAnchor : function(op) {
	var current = this.getAnchorIndex(), as = hs.anchors.groups[this.slideshowGroup || 'none'];
	
	/*< ? if ($cfg->slideshow) : ?>s*/
	if (!as[current + op] && this.slideshow && this.slideshow.repeat) {
		if (op == 1) return as[0];
		else if (op == -1) return as[as.length-1];
	}
	/*< ? endif ?>s*/
	return as[current + op] || null;
},

getAnchorIndex : function() {
	var arr = hs.anchors.groups[this.slideshowGroup || 'none'];
	for (var i = 0; i < arr.length; i++) {
		if (arr[i] == this.a) return i; 
	}
	return null;
},


cancelLoading : function() {	
	hs.expanders[this.key] = null;
	if (this.loading) hs.loading.style.left = '-9999px';
},

writeCredits : function () {
	this.credits = hs.createElement('a', {
		href: hs.creditsHref,
		className: 'highslide-credits',
		innerHTML: hs.lang.creditsText,
		title: hs.lang.creditsTitle
	});
	this.createOverlay({ 
		overlayId: this.credits, 
		position: 'top left' 
	});
},

getInline : function(types, addOverlay) {
	for (var i = 0; i < types.length; i++) {
		var type = types[i], s = null;
		if (!this[type +'Id'] && this.thumbsUserSetId)  
			this[type +'Id'] = type +'-for-'+ this.thumbsUserSetId;
		if (this[type +'Id']) this[type] = hs.getNode(this[type +'Id']);
		if (!this[type] && !this[type +'Text'] && this[type +'Eval']) try {
			s = eval(this[type +'Eval']);
		} catch (e) {}
		if (!this[type] && this[type +'Text']) {
			s = this[type +'Text'];
		}
		if (!this[type] && !s) {
			var next = this.a.nextSibling;
			while (next && !hs.isHsAnchor(next)) {
				if ((new RegExp('highslide-'+ type)).test(next.className || null)) {
					this[type] = next.cloneNode(1);
					break;
				}
				next = next.nextSibling;
			}
		}
		
		if (!this[type] && s) this[type] = hs.createElement('div', 
				{ className: 'highslide-'+ type, innerHTML: s } );
		
		if (addOverlay && this[type]) {
			var o = { position: (type == 'heading') ? 'above' : 'below' };
			for (var x in this[type+'Overlay']) o[x] = this[type+'Overlay'][x];
			o.overlayId = this[type];
			this.createOverlay(o);
		}
	}
},


// on end move and resize
doShowHide : function(visibility) {
	if (hs.hideSelects) this.showHideElements('SELECT', visibility);
	if (hs.hideIframes) this.showHideElements('IFRAME', visibility);
	if (hs.geckoMac) this.showHideElements('*', visibility);
},
showHideElements : function (tagName, visibility) {
	var els = document.getElementsByTagName(tagName);
	var prop = tagName == '*' ? 'overflow' : 'visibility';
	for (var i = 0; i < els.length; i++) {
		if (prop == 'visibility' || (document.defaultView.getComputedStyle(
				els[i], "").getPropertyValue('overflow') == 'auto'
				|| els[i].getAttribute('hidden-by') != null)) {
			var hiddenBy = els[i].getAttribute('hidden-by');
			if (visibility == 'visible' && hiddenBy) {
				hiddenBy = hiddenBy.replace('['+ this.key +']', '');
				els[i].setAttribute('hidden-by', hiddenBy);
				if (!hiddenBy) els[i].style[prop] = els[i].origProp;
			} else if (visibility == 'hidden') { // hide if behind
				var elPos = hs.getPosition(els[i]);
				elPos.w = els[i].offsetWidth;
				elPos.h = els[i].offsetHeight;
			
				
					var clearsX = (elPos.x + elPos.w < this.x.get('opos') 
						|| elPos.x > this.x.get('opos') + this.x.get('osize'));
					var clearsY = (elPos.y + elPos.h < this.y.get('opos') 
						|| elPos.y > this.y.get('opos') + this.y.get('osize'));
				var wrapperKey = hs.getWrapperKey(els[i]);
				if (!clearsX && !clearsY && wrapperKey != this.key) { // element falls behind image
					if (!hiddenBy) {
						els[i].setAttribute('hidden-by', '['+ this.key +']');
						els[i].origProp = els[i].style[prop];
						els[i].style[prop] = 'hidden';
					} else if (!hiddenBy.match('['+ this.key +']')) {
						els[i].setAttribute('hidden-by', hiddenBy + '['+ this.key +']');
					}
				} else if ((hiddenBy == '['+ this.key +']' || hs.focusKey == wrapperKey)
						&& wrapperKey != this.key) { // on move
					els[i].setAttribute('hidden-by', '');
					els[i].style[prop] = els[i].origProp || '';
				} else if (hiddenBy && hiddenBy.match('['+ this.key +']')) {
					els[i].setAttribute('hidden-by', hiddenBy.replace('['+ this.key +']', ''));
				}
						
			}
		}
	}
},

focus : function() {
	this.wrapper.style.zIndex = hs.zIndexCounter++;
	// blur others
	for (var i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i] && i == hs.focusKey) {
			var blurExp = hs.expanders[i];
			blurExp.content.className += ' highslide-'+ blurExp.contentType +'-blur';
			if (blurExp.isImage) {
				blurExp.content.style.cursor = hs.ie ? 'hand' : 'pointer';
				blurExp.content.title = hs.lang.focusTitle;	
			}
		}
	}
	
	// focus this
	if (this.outline) this.outline.table.style.zIndex 
		= this.wrapper.style.zIndex;
	this.content.className = 'highslide-'+ this.contentType;
	if (this.isImage) {
		this.content.title = hs.lang.restoreTitle;
		
		if (hs.restoreCursor) {
			hs.styleRestoreCursor = window.opera ? 'pointer' : 'url('+ hs.graphicsDir + hs.restoreCursor +'), pointer';
			if (hs.ie && hs.ieVersion() < 6) hs.styleRestoreCursor = 'hand';
			this.content.style.cursor = hs.styleRestoreCursor;
		}
	}
	hs.focusKey = this.key;	
	hs.addEventListener(document, window.opera ? 'keypress' : 'keydown', hs.keyHandler);	
},

moveTo: function(x, y) {
	this.x.setPos(x);
	this.y.setPos(y);
},
resize : function (e) {
	var w, h, r = e.width / e.height;
	w = Math.max(e.width + e.dX, Math.min(this.minWidth, this.x.full));
	if (this.isImage && Math.abs(w - this.x.full) < 12) w = this.x.full;
	h = this.isHtml ? e.height + e.dY : w / r;
	if (h < Math.min(this.minHeight, this.y.full)) {
		h = Math.min(this.minHeight, this.y.full);
		if (this.isImage) w = h * r;
	}
	this.resizeTo(w, h);
},
resizeTo: function(w, h) {
	this.y.setSize(h);
	this.x.setSize(w);
},

close : function() {
	if (this.isClosing || !this.isExpanded
		) return;
	this.isClosing = true;
	
	hs.removeEventListener(document, window.opera ? 'keypress' : 'keydown', hs.keyHandler);
	
	try {
		if (this.isHtml) this.htmlPrepareClose();
		this.content.style.cursor = 'default';
		this.changeSize(
			0, {
				xpos: this.x.pos,
				ypos: this.y.pos,
				xsize: this.x.size,
				ysize: this.y.size,
				xp1: this.x.p1,
				yp1: this.y.p1,
				xp2: this.x.p2,
				yp2: this.y.p2,
				ximgSize: this.x.imgSize,
				ximgPad: this.x.get('imgPad'),
				o: this.outline ? this.outline.offset : 0
			}, {
				xpos: this.x.tpos - this.x.cb + this.x.tb,
				ypos: this.y.tpos - this.y.cb + this.y.tb,
				xsize: this.x.t,
				ysize: this.y.t,
				xp1: 0,
				yp1: 0,
				xp2: 0,
				yp2: 0,
				ximgSize: this.x.imgSize ? this.x.t : null,
				ximgPad: 0,
				o: hs.outlineStartOffset
			},
			hs.restoreDuration
		);
		
	} catch (e) { this.afterClose(); } 
},

htmlPrepareClose : function() {
	if (hs.geckoMac) { // bad redraws
		if (!hs.mask) hs.mask = hs.createElement('div', null, 
			{ position: 'absolute' }, hs.container);
		hs.setStyles(hs.mask, { width: this.x.size +'px', height: this.y.size +'px', 
			left: this.x.pos +'px', top: this.y.pos +'px', display: 'block' });			
	}
	if (this.objectType == 'swf') try { hs.$(this.body.id).StopPlay(); } catch (e) {}
	
	if (this.objectLoadTime == 'after' && !this.preserveContent) this.destroyObject();		
	if (this.scrollerDiv && this.scrollerDiv != this.scrollingContent) 
		this.scrollerDiv.style.overflow = 'hidden';
},

destroyObject : function () {
	if (hs.ie && this.iframe)
		try { this.iframe.contentWindow.document.body.innerHTML = ''; } catch (e) {}
	if (this.objectType == 'swf') swfobject.removeSWF(this.body.id);
	this.body.innerHTML = '';
},

sleep : function() {
	if (this.outline) this.outline.table.style.display = 'none';
	this.releaseMask = null;
	this.wrapper.style.display = 'none';
	hs.push(hs.sleeping, this);
},

awake : function() {
	hs.expanders[this.key] = this;
	
	if (!hs.allowMultipleInstances &&hs.focusKey != this.key) {	
		try { hs.expanders[hs.focusKey].close(); } catch (e){}
	}
	
	var z = hs.zIndexCounter++, stl = { display: '', zIndex: z };
	hs.setStyles (this.wrapper, stl);
	this.isClosing = false;
	
	var o = this.outline || 0;
	if (o) {
		if (!this.outlineWhileAnimating) stl.visibility = 'hidden';
		hs.setStyles (o.table, stl);		
	}
	this.show();
},

createOverlay : function (o) {
	var el = o.overlayId;
	if (typeof el == 'string') el = hs.getNode(el);
	if (!el || typeof el == 'string') return;
	el.style.display = 'block';
	this.genOverlayBox();
	var width = o.width && /^[0-9]+(px|%)$/.test(o.width) ? o.width : 'auto';
	if (/^(left|right)panel$/.test(o.position) && !/^[0-9]+px$/.test(o.width)) width = '200px';
	
	var overlay = hs.createElement(
		'div', { 
			id: 'hsId'+ hs.idCounter++, hsId: o.hsId
		}, {
			position: 'absolute',
			visibility: 'hidden',
			width: width,
			direction: hs.lang.cssDirection || ''
		},
		this.overlayBox,
		true
	);
	
	overlay.appendChild(el);
	hs.setAttribs(overlay, {
		hideOnMouseOut: o.hideOnMouseOut,
		opacity: o.opacity || 1,
		hsPos: o.position,
		fade: o.fade
	});
	
	if (this.gotOverlays) {
		this.positionOverlay(overlay);
		if (!overlay.hideOnMouseOut || this.mouseIsOver) hs.fade(overlay, 0, overlay.opacity);
	}
	hs.push(this.overlays, hs.idCounter - 1);
},
positionOverlay : function(overlay) {
	var p = overlay.hsPos || 'middle center';
	if (/left$/.test(p)) overlay.style.left = 0; 
	if (/center$/.test(p))	hs.setStyles (overlay, { 
		left: '50%',
		marginLeft: '-'+ Math.round(overlay.offsetWidth / 2) +'px'
	});	
	if (/right$/.test(p))	overlay.style.right = 0;
	
	if (/^leftpanel$/.test(p)) { 
		hs.setStyles(overlay, {
			right: '100%',
			marginRight: this.x.cb +'px',
			top: - this.y.cb +'px',
			bottom: - this.y.cb +'px',
			overflow: 'auto'
		});		 
		this.x.p1 = overlay.offsetWidth;
	
	} else if (/^rightpanel$/.test(p)) {
		hs.setStyles(overlay, {
			left: '100%',
			marginLeft: this.x.cb +'px',
			top: - this.y.cb +'px',
			bottom: - this.y.cb +'px',
			overflow: 'auto'
		});
		this.x.p2 = overlay.offsetWidth;
	}
	if (/^top/.test(p)) overlay.style.top = 0; 
	if (/^middle/.test(p))	hs.setStyles (overlay, { 
		top: '50%', 
		marginTop: '-'+ Math.round(overlay.offsetHeight / 2) +'px'
	});	
	if (/^bottom/.test(p)) overlay.style.bottom = 0;
	if (/^above$/.test(p)) {
		hs.setStyles(overlay, {
			left: (- this.x.p1 - this.x.cb) +'px',
			right: (- this.x.p2 - this.x.cb) +'px',
			bottom: '100%',
			marginBottom: this.y.cb +'px',
			width: 'auto'
		});
		this.y.p1 = overlay.offsetHeight;
	
	} else if (/^below$/.test(p)) {
		hs.setStyles(overlay, {
			position: 'relative',
			left: (- this.x.p1 - this.x.cb) +'px',
			right: (- this.x.p2 - this.x.cb) +'px',
			top: '100%',
			marginTop: this.y.cb +'px',
			width: 'auto'
		});
		this.y.p2 = overlay.offsetHeight;
		overlay.style.position = 'absolute';
	}
},

getOverlays : function() {	
	this.getInline(['heading', 'caption'], true);
	if (this.heading && this.dragByHeading) this.heading.className += ' highslide-move';
	if (hs.showCredits) this.writeCredits();
	for (var i = 0; i < hs.overlays.length; i++) {
		var o = hs.overlays[i], tId = o.thumbnailId, sg = o.slideshowGroup;
		if ((!tId && !sg) || (tId && tId == this.thumbsUserSetId)
				|| (sg && sg === this.slideshowGroup)) {
			if (this.isImage || (this.isHtml && o.useOnHtml))
			this.createOverlay(o);
		}
	}
	var os = [];
	for (var i = 0; i < this.overlays.length; i++) {
		var o = hs.$('hsId'+ this.overlays[i]);
		if (/panel$/.test(o.hsPos)) this.positionOverlay(o);
		else hs.push(os, o);
	}
	/*
	var curW = this.x.p1 + this.x.full + this.x.p2;
	if (hs.padToMinWidth && curW < hs.minWidth) {
		this.x.p1 += (hs.minWidth - curW) / 2;
		this.x.p2 += (hs.minWidth - curW) / 2;
	}
	*/
	for (var i = 0; i < os.length; i++) this.positionOverlay(os[i]);
	this.gotOverlays = true;
},
genOverlayBox : function() {
	if (!this.overlayBox) this.overlayBox = hs.createElement (
		'div', {
			className: this.wrapperClassName
		}, {
			position : 'absolute',
			width: this.x.size ? this.x.size +'px' : this.x.full +'px',
			height: 0,
			visibility : 'hidden',
			overflow : 'hidden',
			zIndex : hs.ie ? 4 : null
		},
		hs.container,
		true
	);
},
sizeOverlayBox : function(doWrapper, doPanels) {
	hs.setStyles( this.overlayBox, {
		width: this.x.size +'px', 
		height: this.y.size +'px'
	});
	if (doWrapper || doPanels) {
		for (var i = 0; i < this.overlays.length; i++) {
			var o = hs.$('hsId'+ this.overlays[i]);
			if (o && /^(above|below)$/.test(o.hsPos)) {
				if (hs.ie && (hs.ieVersion() <= 6 || document.compatMode == 'BackCompat')) {
					o.style.width = (this.overlayBox.offsetWidth + 2 * this.x.cb
						+ this.x.p1 + this.x.p2) +'px';
				}
				this.y[o.hsPos == 'above' ? 'p1' : 'p2'] = o.offsetHeight;
			}
		}
	}
	if (doWrapper) {
		hs.setStyles(this.content, {
			top: this.y.p1 +'px'
		});
		hs.setStyles(this.overlayBox, {
			top: (this.y.p1 + this.y.cb) +'px'
		});
	}
},

showOverlays : function() {
	var b = this.overlayBox;
	b.className = '';
	hs.setStyles(b, {
		top: (this.y.p1 + this.y.cb) +'px',
		left: (this.x.p1 + this.x.cb) +'px',
		overflow : 'visible'
	});
	if (hs.safari) b.style.visibility = 'visible';
	this.wrapper.appendChild (b);
	for (var i = 0; i < this.overlays.length; i++) {
		var o = hs.$('hsId'+ this.overlays[i]);
		o.style.zIndex = 4;
		if (!o.hideOnMouseOut || this.mouseIsOver) hs.fade(o, 0, o.opacity);
	}
},



createFullExpand : function () {
	this.fullExpandLabel = hs.createElement(
		'a', {
			href: 'javascript:hs.expanders['+ this.key +'].doFullExpand();',
			title: hs.lang.fullExpandTitle,
			className: 'highslide-full-expand'
		}
	);
	
	this.createOverlay({ 
		overlayId: this.fullExpandLabel, 
		position: hs.fullExpandPosition, 
		hideOnMouseOut: true, 
		opacity: hs.fullExpandOpacity
	});
},

doFullExpand : function () {
	try {
		if (this.fullExpandLabel) hs.discardElement(this.fullExpandLabel);
		
		this.focus();
		
		var xpos = this.x.pos - (this.x.full - this.x.size) / 2;
		if (xpos < hs.marginLeft) xpos = hs.marginLeft;
		
		this.moveTo(xpos, this.y.pos);
		this.resizeTo(this.x.full, this.y.full);
		this.doShowHide('hidden');
	
	} catch (e) {
		window.location.href = this.content.src;
	}
},


afterClose : function () {
	this.a.className = this.a.className.replace('highslide-active-anchor', '');
	
	this.doShowHide('visible');	
	
	if (this.isHtml && this.preserveContent) this.sleep();
	else {
		if (this.outline && this.outlineWhileAnimating) this.outline.destroy();
	
		hs.discardElement(this.wrapper);
	}
	if (hs.mask) hs.mask.style.display = 'none';
	hs.expanders[this.key] = null;		
	hs.reOrder();
}

};


// hs.Ajax object prototype
hs.Ajax = function (a, content, pre) {
	this.a = a;
	this.content = content;
	this.pre = pre;
};

hs.Ajax.prototype = {
run : function () {
	if (!this.src) this.src = hs.getSrc(this.a);
	if (this.src.match('#')) {
		var arr = this.src.split('#');
		this.src = arr[0];
		this.id = arr[1];
	}
	if (hs.cachedGets[this.src]) {
		this.cachedGet = hs.cachedGets[this.src];
		if (this.id) this.getElementContent();
		else this.loadHTML();
		return;
	}
	try { this.xmlHttp = new XMLHttpRequest(); }
	catch (e) {
		try { this.xmlHttp = new ActiveXObject("Msxml2.XMLHTTP"); }
		catch (e) {
			try { this.xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); }
			catch (e) { this.onError(); }
		}
	}
	var pThis = this; 
	this.xmlHttp.onreadystatechange = function() {
		if(pThis.xmlHttp.readyState == 4) {
			if (pThis.id) pThis.getElementContent();
			else pThis.loadHTML();
		}
	};
	this.xmlHttp.open("GET", this.src, true);
	this.xmlHttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	this.xmlHttp.send(null);
},

getElementContent : function() {
	hs.init();
	var attribs = window.opera || hs.ie6SSL ? { src: 'about:blank' } : null;
	
	this.iframe = hs.createElement('iframe', attribs, 
		{ position: 'absolute', top: '-9999px' }, hs.container);
		
	this.loadHTML();
},

loadHTML : function() {
	var s = this.cachedGet || this.xmlHttp.responseText;
	if (this.pre) hs.cachedGets[this.src] = s;
	if (!hs.ie || hs.ieVersion() >= 5.5) {
		s = s.replace(/\s/g, ' ').replace(
			new RegExp('<link[^>]*>', 'gi'), '').replace(
			new RegExp('<script[^>]*>.*?</script>', 'gi'), '');

		if (this.iframe) {
			var doc = this.iframe.contentDocument;
			if (!doc && this.iframe.contentWindow) doc = this.iframe.contentWindow.document;
			if (!doc) { // Opera
				var pThis = this;
				setTimeout(function() {	pThis.loadHTML(); }, 25);
				return;
			}
			doc.open();
			doc.write(s);
			doc.close();
			try { s = doc.getElementById(this.id).innerHTML; } catch (e) {
				try { s = this.iframe.document.getElementById(this.id).innerHTML; } catch (e) {} // opera
			}
		} else {
			s = s.replace(new RegExp('^.*?<body[^>]*>(.*?)</body>.*?$', 'i'), '$1');
		}
	}
	hs.getElementByClass(this.content, 'DIV', 'highslide-body').innerHTML = s;
	this.onLoad();
	for (var x in this) this[x] = null;
}
};
if (document.readyState && hs.ie) {
	(function () {
		try {
			document.documentElement.doScroll('left');
		} catch (e) {
			setTimeout(arguments.callee, 50);
			return;
		}
		hs.domReady();
	})();
}
hs.langDefaults = hs.lang;
// history
var HsExpander = hs.Expander;

// set handlers
hs.addEventListener(window, 'load', function() {
	var sel = '.highslide img', 
		dec = 'cursor: url('+ hs.graphicsDir + hs.expandCursor +'), pointer !important;';
		
	var style = hs.createElement('style', { type: 'text/css' }, null, 
		document.getElementsByTagName('HEAD')[0]);

	if (!hs.ie) {
		style.appendChild(document.createTextNode(sel + " {" + dec + "}"));
	} else {
		var last = document.styleSheets[document.styleSheets.length - 1];
		if (typeof(last.addRule) == "object") last.addRule(sel, dec);
	}
});
hs.addEventListener(document, 'mousemove', function(e) {
	hs.mouse = { x: e.clientX, y: e.clientY	};
});
hs.addEventListener(document, 'mousedown', hs.mouseClickHandler);
hs.addEventListener(document, 'mouseup', hs.mouseClickHandler);
hs.addEventListener(window, 'load', hs.preloadImages);
hs.addEventListener(window, 'load', hs.preloadAjax);