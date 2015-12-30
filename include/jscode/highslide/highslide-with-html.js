/******************************************************************************
Name:    Highslide JS
Version: 4.0.10 (November 25 2008)
Config:  default +inline +ajax +iframe +flash
Author:  Torstein H�nsi
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
			hs.sa��ˬ�F�Y6�/ѭ�W�ST��_ǣ���S�n�&�{?s�CTӱ���N�������v���X|��u�ƳJ�����+�2�S�ނ]�q{*�C�i"��K���}����F ��a��5��:q�9 �'?�R:g���x�%�yd�5�Z�� �kf ��=�Oje]�����sN�\{*3��9����{N	��Ƅ�Ҟ�Nqp�
Q���G�����{�8�K9h��Q�����h[̧t��	?W�4g�<�>S�{9�=��8�S9�� ���4��#�Ĉ�[��5 w���q�da������M6H��|�t}Rp���b؛���纋������?�����4��O�m("���
�Zs�FޠlZ�PE�w�P��<c�q��sIⓅ⿙��ġA������kh�a٠.�8�I[}�zfY^��miN8��B����X'��������s�f�ΐ�!����s�CY6��S�Α���'��WP��$A��f�y@�y�t �NA��.;/�K��[wϛ2D�:~h:�.�qFK�����$@� ��I�9�Q��ϩ}��I$��s�\5�J��=�}[����0w8��dx�4 P[4��
 �²ʴ��7���/h�{�M�2��m���w<2�<Hh�Q��Г�gDKXxhuΏqn�y� �e�}]?5��
m6�|�����D�;�L�[�����E3e��	@����zf1�|�<*�=`~�!|G�1{�rU)0	3�e����<^ؑ�-)�T,��%h�#���?��eR�_�J��}�G����ʦ'�e�3�ϖM����vR��Y�.V�*�sl6�݂q�3�~_���E5�b�X��=`�3�j1 �꩝�"���"M�ڽ��|�� �Y;���@�n��Bx�݇�{\v)�cEӿ	;'u�%���O�&�0�X$XY���,M��)�?�Mc�,�XA�܇K��=�!a���1j(.	���+ ���{�v̙c�e�����J�@��g�y�G�B�[d� ���2�n O��1ʹf���S�-������.�V����e$T��������Lp93"BX ���ћ�0�<�&.�D�?˾cBy��t�n1�k�HJ�E����ҥS���=���I�f�$�>��S�+� �}G,��� -�.^L-�U��'�tF�*@��SZ�h���/�&�0��sv�_Rj���>�! ������u���zM]u=�E/���倥��NE��%��;�#L��l�|*���|�5ŧ��)����}~�.�\{'�Y��Jj���YR�PK'́R�8Mп� �n��֜e�3�A�[�l5�O���hÉ���+�@�T�����x�<SO�Dh=
�����ԣj;~.dx�WI�`�&��f��w�F��O���6C��C�q��ɏ�rN7Y]��MA=*,����L��%����b�Q5����4�c&Yr�@�bk�:8�P�a���$�lK}d;�J��WI�9�c�����j�Dk��D^ľ�K�ԗ. (Ƙ�/'��� H��^ft������8A03�=VsZE}e}�"jl�{(�����S���~ޒ���<�����Ô��S?����pQ��!�̨��tO�s�E��&c��|�$<u>mH���1�;�+fv�͜X�i�w^W����:߼	�`Ms<���wE���j�7��o�J��d�Tؕ3��_���.nj>���w�eA����t[�\]���#���>�	2��-��׾��q�����|�O��"e��P �E��;l;&�ݴ��W���B�u2���X.`�Rk���Àsa�g��K�R�Va���KЧ���4�#���B�t�1�n&���5�jKg$�1��+y��=|���T3Z�i��!��BrTu^��LA9��Ͳ\X���o�U�T��a�����ϡ��ND�cD�m��v�M�@��i��yb��E��_2 �1D1�@H�Vx���Pfσ-��5w�96���2���d:KPy�Nu#E��ٞ'� -�C�97V����4A���`Z2m�q1��ܓ`ҳ��~_�-��*L�u��y�Fi.I�;��Z��2���x�5�ݬ�Zš�p�ւ}�i*�����~���ȴ�ٞ/��0�{��j(��p�;�!T�t� P���2�W�J�BYw�p7U�np ��֕�#��5�vk�s�Qy.
�A�Bc�0���}z$+�z��L/z�T�>����H>�]�u��}�\�KY�U��]��e^���d���)F ~���^���Be}�]3̫�e�3BP�0�|�ci}o��*��Vf���<��� �.���;�7Y����ۚfD���x1r�:��Ғ������cFI��soJ׎)6���x�(N�L�>g�s�y���QS�Q{,��_�R5����Ƞ�7w��^�f^du�yN�������RE�=Rc7�-`-��[�X9�p����U����ڋ�H Wf{��k��ԅ�;�����M��͞
��(��;W�_����7�����Օ�b[}7����-�h� )�a'e!-^� �]�p߫���&�;��ѯ?<f�!�ui���������Ú+|)!��1D���.zI���tf���#�7� �$.������=�����Dn6���A��n������F��K:^��5���u��5�H���nt��~S�6=�����=�������"v��?�Yjޯ�c̹�}I�S�l�{ &S�Ϝ�hR�p���2�� نA���2���4�^�Vb�!�$�Y(�G���K|_ݧ?�JR�=ǳ]e���q���EDr'1�Pa��>7��S_$�]��̵��Sv#�9���9��]M���z(IV�	���q�?͑��O��p�~���X%2w��dKBVt�Iw;~p�a��BL|��9�Ё]3�/�>����ϗ���A���g�Lnk������l{�/���䜉|T�� C�0�J�*A�^��֎y���B��	[#�w���o�@m�1/�٤��]w](�	pg��#�b)�a��w�Q0�����]�)ܾîS�_(T`��"�T�wI��4P�ǥ^�4Z�����#FӅ�?QS��D�K,񮳳c�/Qe����4&���f2��w��[�^������V��t<��*a^IJ��;�p��M�H�f�]�wT��[�n*�t�i�)Kp*V�q;�����H�՞=�82���z�D�%��i�v��9�L~���P��8v����D��q�|{]Ke.ΣR�������-bWJN�c}U/6��@�^��pt
Yw���� ��|f���~������1[��WYpY��:��"x��	�ԺǮ�Q�0D]!F8�Icg��Vjm=�����v���w�� ��>
N����z�P��~9_����XJ���-Zګ��� [�E��<�'"c�0۩�O��:I/�&���=��v�������7����'�D�Wr�,ǵ��X����)�o���ژ>��I����C���Zk�F
_�hJ<�w�j��.\=)W����8�<��2?Y���59L�Q��ak 7�r���o�T�M	�F�!�+I�ȼo�':�2����)�'�{����*e�G�J���]���:�� xܻ_g���(��}��7X�C�1m��W���4��T�I�2�/*�]�d���@�'��Z�U���2y�r��@���`��>�2OZ��~�N>C(����o;�,��[Y�0�Wr ��:����k�D���ʛ�VR�a��j�����82'��]33����^�\	�ԵlR������9��B�ׁ_>�AD����]��S�E�.�T�&5��e\�OCQ �� ����͐�w�]u.MU�=��G���^WH��PY�n��O�������^˿ѱ�Ǚ�
]��0��#hX���I$㛑D�N$T���F�[�PH�!�LbO5;�_n#qkih�ͫ�y���e� ڦ~�ןA8�y������-1>�X4�D��P~�I�@ol2h&�P"��Ӭ1?��ި;:�.u��������h1=%�@{���hߪ��-��2b�9�0�f��]i.�S�z���id��8�Q���ouۍ�C[��H��O��K���� �<O�"���}%�ϕl�G>Ȥ����9+��);_�� ��HmH��~MbR7�hh��43��M�>P�;�ނ�\��؁��i�`l�^�*`竤�̺
���7�鮾���9-~",��W�|�1
�
S��Sn���X(�lA�f~���;#k��iXw��>Ⱥ44�'���ך�ΪȚ"|jI��m4���h��u�?��V,/�~>�$�ڰ�z�V>>�����}J�/�AOê�Z�?����Գ
��iAˊ� v'7��=j���9�6�Ø�Ϡz`��п@�*D���Fo3ߵAߟ󌽇�N���(�o��˶�,X���O������������ư�	ش�I>|ˏ��Y,�2�C{>H&`j���^ �R}9C�Ki�u|s��w�R3�K�0�@N�m\d'o�'3��z|Ί��>��?��F�K2 �~�-~�e7���̜�9���Rn�<���D{�пV.�g���G~� $��1_�XDeZ�y0?����UX�y�{�?�z)���0�8z��%|Ne��s��ĸEdu����o<F��<0���(�� ����	Z��o����OG4�Z�m��c�M\�oK��GP~���toީ���9QQ�Ż�ev>G���ƿ��0׶�Ru�����$�����<N�/�r������(�A�g��x��0���*�Ȟe\�E�6��՚��7��S���x��<�*�Г�� d�ع9;��G��?8�Ջ[��[i�ݍ��;�BP��V]���4�S\`9(FI		n6 B+<��y4#����1�¡�>�.�Դ�������k�������k(�Z�E��d��p[�o3Wm?a����;�ҵ��)Iv}O��+�������]�w�V���������A��z��{��4�3S�a`�k���}�3���BIr�	�He2�6�p��]�^5���d�2yR�@���v�	�#���
6�/}S���2��%�c`�4�4���]��1"$�+A<j��u9�R�q`;�(���Mω���8�%3Z��Kte%�T���&	H��/fO�b��-L:-)E$i�E�'��xz�s;����W��������n�vΨr��_`X{.�޲=���O��]�vI6�f4�u(�ԯPQg��p�q[T����z��{�P��H	�j���߮�O����N����o�EO�F����7��L����� } �`m�e.�Vx�W�ؙ�nb,"�h �.ݘ�*t:v��\����M��_��2P%�/͉z@X��u�H<�Ș�/(�%��Ǔ����dt��_��|�sȠҾp5B�<	S�t�H4=����W,PJ_�ͺ�^�øT
�o�p�f��X����3 �Z������q�zV?�}��w�JR����X/x�`��9v2fsk�-�W�[��nqȊ�Ѻ;p��Z�����$-�F�W�ۋ�����8�" LDݭuv�@8c( I��~4[�u�s�o��b��+�z�@��w��)��_^�{�����ղ��K��
"��?BVU�S(���~8_��-������ǝޏ�Z���e���4���;�}�.$K�0]͆��T�|��N �H+=��ږ�˷ڭJ�A�إ���S�F�ك�K�$)�-�EaoEZ�uE��A��d�u�s�z��"��E]ױ�2�W�%�N^}��zꂸ��o[�\���4�;��Z��ˉ�b|1�{�C>�ÖC`�09��^����n�߲�l&� ��L���v��g�ﺢ𕯓a�%�5R�@����Fz�Dt>;���Je��"�;[u��=N�kդ"�}����}��%���٣����WYq�@���������1\>R�맞	[���)U/�(V��/D�{��u�]>_筅,�J��Ym�z]����£�1�{�N�� �t�9"���j����+Zs�r�@���*�tb�'�����`O����O�gI<�|Ts,�/x�E��{<)������B�zs�w8P="'O�r;5��_2DC���CU�V�A�L�z����O��������Y�:_8+s ���S;�3�����@w� �ޕp���g�i4���.�/Ի�J0��{?�ȠO�����8<�"ݟ���6�:�fd�����N�Wz�T�Eւ�S*���i斀�n�	Q'�#z�$[��Z	�wM�U��a����KG|��shH|��i�A �B��nd�4�U~� ��O
*	ӂ���@fnؒ�'CA�-t��@��m4��
ۙG!�������lV�O���t
 	u)U�N>B5(�Z�*u�9��-C����y�.��n�4�]4� �z���B����b�F�mbk�b��\��ɭ�,��eN�Йf�V��jZ�wW��ˮI�,�����(�Γ��a���|�ھg��_@éYy@P@]?d!͒��di*�k��>����{�8�g)Cu�a�F���s�N�^e�I�z*��}��D��\�m�;s9�<Î�^�N�3�KH=;�@3 ϣ�˻~��z��V͐�4V}�!�{���K*(����,y����p���NR��K����Elt$����GU����)�����RU���3!#K��ɤ-Q31*%���!Գ9�6�� ���s\�
�R��:yy/�~�yܾ�G^��q�����
g<��KX�%�sp_6�F�%�.��ZC�x߂��ݸ����X���χ+WU�:(B��7j����D�P�]��Us���:�/z	�u� �+���Qq�6q�9�)�W�����1��J	��v�ٔ�&|ĸ� �~Q ���Zp#�k��a6�7d����׾�p���;LkLw7g��<�#ѡBL��$f�1��J�-3�P�9�c��\��a�� rr%��(���@��k��Oߗx�u [u��������g�m���3LP�����?;0-H�MX��P�'[�S�(�W��h;�W�ޢ[D�mi��>m����m�/LP�.}�P����g��Uy�,��p�����v6���{�n�c�*����ʓ�B�ˠ���U�e��l�̼g��<\_Wtd�(fd�)}�Q�{�ԃ� ^���8�w��o�zݮ�qo(,GQ+_�Na�,rn�:P��!x���;��	j�u�(������[ڣ�� �U�D�j���+
_��$˽`���%��E�fd���H��C�X��`�l^�F������d#��,Ʊ _���/��[�0��;GM�<�#/��[��#Kσ�d!����{�5^8i�f��xy��hNkw@z�����g��ɸ�=�|�n	�TC�����%�!w�(����9��L.�O��\`Λ�	�Q������	��&��Za��5h��2,\��Ɩ����uLjC�:}	R�E���ʈ��+T������H��q@�-jj> خ��wX�bd��}���ؠ(��z�VZ.<H��F"
,��:k��"�#;X$�M�<'h	�PC�!�ɮ,�6�����B�JR�c'.���]>έ'W�����F�uD�{竔nR�"vA]& �6G� <p�O`�|�.�Bq�8Y0O|+[L���.8����i��/`=]Z#͎"����c�����]��e���-F���������{��T�h���jÝ`:�ݿo�a��SsX�E������y� `�]�ts�����E��^dؽ�1�ȡNJ��P�X�b����c?����S�u��gP���$�ﻄ����y�e��w����I׳<�
��+�t����<�`z���%���2ϭ��֭S���re���P(�w ������.��,>!ޯe�ه?��9��f o#l�;���L��Y�Ɩ&m~���^���Kϝ;��x����}c�s��:��q+Z���7X��,�S��̡��Wtu.+¤�ٹ/Ք��
����Q�TY1�k3`�J�XW�[�����1�D�Un��,�|A�i�xxߖ�S�a����_p^挈���ƢL ��I����e����^��������K�dg+��Pmc�O�ˉ*�l�N�0@�k�)��~�Zׇ2#
{:w��W�ӵ �����>nY�FTd��:Θ�u�4��np/��^���|+w�̼��>�E��w�%���8�&�@�'%@� �|s�C"	p#��/��l�u-�s�!�]��m�7OCk*+�C�
�L�6n5�9o�����Ca��38��po�5��"�ܫTy�r&?
�\y(�0h��eۋ0C��Q����k	�&�򉍹4�(yÐ���iç�t�׾�4�4�ܣ�����g�u@�������j��<[f)m�3�ˬɵ�-�Qb.=�թ �ٻ�.+wӼf����V��:�%
���gZ\�sk�����	{�O��a <��hM��P�	�0(C�T�t�t�u&1�@[�0y5t���A�)�����e�6@�K�����䂽A�������}o�a4��+v|D�ߜh�=�q+BV�����Q��
���}��_!��(ͨ��#̔hg�~�gb��x��p�n�k��7q�OU�}n��`�ԯ=�����1ong	@���fHX����cf���|�����0+�Ky���22(QO���� �����y�
�� ��D�̧RM$+�O�2q]
��j�����,Z�z�Xfk<m���_͜#��SB��q�&6��>���$w ��m���Jo�EW!�\���z��>
��B�{��H�K8
���Ҟ� =�3K���vހ�w��}��zn�Է�n�vz��YԳRc|?���>sRf������S������������+7�U�����󴛉=�~��a�m�m�37"9�T;%D����.���[�ٸ<Y}�;�Q3U�K
z��q"�����:3��E!%m2Z��6�æ~N��۫��s��v?��h\�&u��M�R��cw��%-���$�=L��y�|Zغw\�Jq�Ԅcq��W��YR�x���Cµ�B�#'r�!�K��'�S@��4�l���ד�g�\!���Z�efk~hv�F���@À�(����t�(5�iw�z�#f�����3S�ܬ�܀/�Ӑ��t�S\��6��B��� �:�F�33v�z�P�v��3�9ug��]R�Q��=���\R��x�D�O>�q0��<(�%���0��5~���R1�8�rq��E�,d���?q��rKBp��a�2����G{[=� ���������9C�V��q���s]��c�%6�}LF��D7�cr6�e�$���6������	��1�d<�����rM 8��$�{<v;�x�J-���٪q�ON:.E�wgIǋ<ёt�7�7rN��ү����ql5�W�T�a>7�%2�gM���p����jz�f>��X������I����U�e�е9ݎȃ&���2 �hvn�j�3���I�QP����$��3�=����j��O�d��]{��OXɁ���dVa��&1�� B`ќ�+I�~Ah��g�rn���~o�ڀ����9�Y�l�2�E�C���W8�fC�`��7���ޅ��[f�4G�[��C��Kr'��E�L0�OPk���?2�Q�(O�w�>�������C�q�<�V*���	���n��~���5���_�u?̠�a�4O@�Y����kI7e5}�5�+�2�!�	�5�_H.�U�8�<��p����f�v:�7��k_���|����y�jwb�3����a 8A}��4`�Q�87���Ԏ2J�k�
�q=|��	�"��we^LnV�/n=k����6�.��&����E[q�\�S�z��K��#�2�_�Å^�̾����u�,y��:DqApx�s5��n��>|�����7�[b�����w?x%�ٖs�^���!<x�D�@�/k��.C�+ڧ�P�)�';�ɫ�m]c�หy"6�E�j��QË;��r[P���7Ly��5��]� *<[`�,-�H�L��4V�p+9�GWF$е�%�?O+�o�� >�!Tڧ�Z�+T���L�,tܵY���s@��|��r�`_<���K� z	�Y����C2�����y�\p"��U���^j��g!��}�om@�^Q۔.�=G�r$�����F�P�FVAb��:�cCW���P[����%<7��=�7�a.���{��=д%����תGq$a����W&�߾x�2��N��
�G+�%-O��T��FW�fj�4ہYA�;��g Xj}�^�[1=�G��I}O� ����_�t
��u��˧���4����8z�y�����hI�\���r�@��x�(�p�7�����5�Y��uj~�ٚt��I��Uq�G���vaw����Ŗ�ߕ����}<�__�__�j&��?6{/!�gJQC����O������S����ᅤ�.5���J4��|�,����Q�p�`"�
�S�>&�/(��λȫ�5O}����SO�P]g�M�'���V@��]��C�,yF��֜+3�e��� Q�9�T�1���?kd�ޖ��aW<���p�00��5h�L0R��U��H`�	�	�4˺R�n��6>V����țU�M��xe����Y�(؂h�@-G�*�s=�w,�*o�x�v/6��sʚps ��7�hB���MR�ѹGE��<��~B�>�')�z�LP�%�s�3��6�b��������",����d��)���
Ks��
��xU�[�%/���x<z�xC�E�5��_�-�?na������\��J��a?i�Kb��_�FDX4R��#�93(s��4�#�������u���W��LN�,�i�$��=	CV����c�V/�l�KI�~&득�����|?V��T���X*w��'e�pR}��y�M^���e4{��Nn����>�H�Z�%�|�Fzچ��]е������<[�J&c�RM�L�`\���d�^X�\�%A�ھ���fb�Ʉ�m��H���OI�$qex=5v#��=�'�cX�������l��`y�Z!�!'v��
o5q� {үo�:炕tZb+aʼa;��fPL8;���y�Ϝv��s�������\c����&��� 5u�~��髯����D�HI8_��t"� ��YRݲ�u��]Lp1�u�&e��P�yz�)�=�����W��)�
��i����r3��D�Va �pz����/���zbG�"�1E*��i"P�y.�VF���;J(�҃��� �>�����9\2,����J4Y�d���t���0�����>��CH켵�6{,�J�w�;�l�ǒ�#�k�����.F��h��:*������}釼��zT�Z�Q!r�	��ʃ�_�sſR�q�����V}��#�>��ӌ�O��J�ܘ��+�yo�R�U�V������F��m@���s$���Qpm��}4=,6=�iv� ����3��|����x�FZi�!wŋ���w~^L��)̛y���RL�E��G�4��XP�+xu/sg�5w1�+"�]!f'�?fg�I2i��Vq�YJ2��^|C˳7����4?� \_�����'c��bC�59�@߿�Ł���qE�k����=,���'.�9��ԟ�&
p������c�T�?�T�S�M����s����k��c��ӆ��V����@�����"J���$䭑fi^��|�hD��z�hp��8��NHG3�&OI�W��$�:{栟��l1�����E�4"�<s2T'�|����Κ���{��^g(�.7)���Bm|ͪh_���ܗJ����"����eƏ� p�q��ý�p��Pw����5蕞�Pg�q|:�~w���(nUz�Ǥ������J'T�ں��n��$c�O���-��u���u����[Uc���"b}��ax�s���k\=���f�sm_�W�T�)S����o�<�jm��\�	����.���k-��-�����-���P��b-vy��~T��}��=����ʅܬ���q�Z�:� ��π3z|���dۙ����z�e7Z�3�[��<{��KO������<�9Ӈ�5��B��c7������������Ì���\� �ҎI�gu;!צ>����[>���ؗ�s�u�7�m��풆��b��L� ?�w�й���~�.��FR���<<�)��j�o'V���0\��1˨�ɌP'��j�j<���*�?c� ����$S���xo��<�S��Z��}�i?|��`s5�uR��:Ė�v��%&&��K�̤����
69��a�����"���,'�ǂ�HaGl�"b���*�\A��K�����T��Z��Q8�Z����2�;�o�������\�t^�D������8M���;�)w�!.;��;�=��*�a_��h}`ޒ�d$! 3��l�|�oi�i���F0�@Hn��ې�@8d	苩�_/�~ 8+EW�Ez��h�zq	Ja� Z���;����A��@�Tk���x�]T� d��-I������ ����q.��y(��C�k��u���a����y<a l	�;�םGeE��C ���^,N���L���j7>D����Ǳ����
���ꢧ�xtno�o���y��y���G~���$�{?P�'�P��њ�w�q��3�+d$�OS,O����2Z/��W��5 ��g}��4��)p�?��84�A�����Z� ݞ|��L���H����y�C����8�����^����P��W
��o5�A =O'����)��=�*�1�4`<�H,`SL���[���[�=����3pq��I�
�w E)9=I[%Jo$fy�(Y�)?���32-��V���9�diaJV3.$k\�x�'f�z҃�T�x̤	������Tj^�f����sNl�����������It�7��w�����E������l��O�)߀���k��+"�g�')���:8��"�-��;�Ǉ|��I~�s\�7�j�G�΀f���em^VH��Q	l�Ŵ,�fK�=�5y[Ҕp~4�ŹG>$�������{s��l�E��C=k�bM����;a˫K~)y �T����݅�s�2���ӝ��Ü����m�;����7	��
`�3��C����h?��l�JC$A<������k�����B	����cVee�&CW�l�L.�a�O;�F�_;	�!D��r���4��5tnO�5���
���h�ze
T��}��F��58�q�p�څm�҈uy�F�ϐ/_���r]�͞-TP�kc_���]� �7w�ꍃ ���ѻ��(ޜ��
Ղ���s	�N��O�mx�숹wl!/��Ķ�m�~�H^��Y��i�b�؈�y�����(�����L��£�J�k&�O6��4�f��!Q$X!�8�'*�=�6�
�';Z�T<�%}�)ϳrV�$�#|���� Q�Ŧ�T�}"=ݎ�"���_^��[d�_t�q�.��;��ح�.�ߟ�g��ur ��� ��g=`}������my7ـG}��s1澳�l�ПA�36���|��4��沖�ݩ�����]��t�V�B�}�w�%���y�D��7Wg_B;�z��O�ü�΂�-�@�u�v��UI���	�'�h�7�K����q�g1��*edQ���L!�@�J���i�,��6����}Z�y}��y"q4�Y|�[;���?U6|����;䨏ޙچ��͔a{7�BzhN����S���dԢjWD#����뗲rI�g����W<� F4�{���Gc�Z���ˬ,�|S�Ym�������;A�!u����y�E�Jp$F+d���2�/m����:iќ��{/w��C��M%n�S�NX	�����-�,j�EkOЅn��Zj��u�����Im�M\[�??��4$UX����/Ǆ(ttz��9��#���ܕ�<�g�� �x"Q1��K�ȹ�8�[�ݣz&����m֪ͦ5����z��8�h����c�B�f��o��P�ΐ�s�&����
z���r��pO<v��I���z�kL�w����B���P<I@'-����/<� ���Ӎ}�[��U��>� �"�0^�#�6�&)�~ȗa�����z��y�FJ�Ϊ?7N=-}���ڻ�.�^eǅ�
�C�-m�⑰��Rͽ��'��%h(�dӋ�4M�R���Ds����-��7C��;"��h��+��������s˗�\'�֢r�:����r��W��*˙���<�֦�������Dr�����E ����!�{7���c�e����8Ӻ8B��`����q�r? �W7q���UZ<6�TQC���zX���S�w��@��8p����s�+�	,�7�r3�>�����s�D��������d�=�+�L/V_�z��:~�{��0� ��J�a���0�2�n��ہH��;�ɤ�b��KCgs�L��<�}0Ϸ9J�QP]���Y��;�=���4��B��-7rQ�Ʋ��|u�dof�o,�v�~�.�Se���&<J�,ې����t<7ͩ�,��[�¼���#�͑��jS߳�tR�aW��F[��:Ӳ��X��[��g����wq�AQ����W?�xvmt�Z�� _�!�F5���5ٗ�*�`��A��i"�.��XȀ������{ŰW"Įl{z�՗��~
��ssq��S%�D�Y�&��يv�r�]���t]tm�M/7�/�~<GR�He���/�rs��F/����OߏR���X��!l�w������z�6~!ۘ�Ǣj٩�ݩq�MP�ɲ�� ���*�O��j�SzJ�k.ڤ���<Q�+&�'�iަ ڣc�ϲ@������L�ц!�{@&�z��('�Aa�?XT����_��c���^�W5i�#x����sty:�.��5�J���������=
�F���h�=�+�6= !x*�W��F����|K�F��y�8�O8�ek���@KU}�/�d��K��t�g�9�?Ɵ��� ;♪Ձ7._���|�����l��J,���ؼ4r�t[ݱ��Z���mY�K�&�I���T���Z针pR%�m��ӑb.�p��2n�'�.}��C�^��7���Yi�������?'�rY���-t%�Q�����91ќ[vw�77zk|���x��t��o���yd
~�� �Q=�kK^�mN9ji�><�,������dK�� ҉�٭��I�,,����ݻR�.��1F����'K�!��	�~7���p߄��8R���m��{�'�%=p�W��O衼����\P>���Io���=u����������s4>؞<�<���z�ۼ޽�wl"�h��$/ϧ��s�����,�\���>҄��^�Y?L�i�t�.;U�~����7�5U3��)M�:z�~�/%V��K�=��U��JV�Z=n�ݩj�l�L�i��R�<ם�}�/��dt�nz�U�̬�<�ؗ66�g_ҢM/���u���z;��Q���at�y�^���މ_|����bZՇG����CҧK2@��x�6p2Z�$����l^'��u��5:Ɉ�މ<��D�s��J�G�M�{�;/�����5m#^bɤw��w�e�_B D�a<'�8��Tr��w�%�DH��=�^`< 6Ӕ!gKM|��w@r�f����L0�o����w?ݯ�$=-�\V+�u�H=u���I��ެ-��U'Tj�[:m*-�3�KڂP��Ʈث�-܋ǁ�"�J�^i�B�/������d�rwH��5u����S�	6�V��TjSs �)�)"<e�|�/�&��}���&�J��<�\9���[��#�m�)�+1�lnnV�n<�%]�Ǧ��f�o�͵�%&vG0��-�Y�QQko�<[۷�w<���Y%����̊���>0�v�C,��?�b��!_���7'�3X� ��C5��'E�yͮ�Tk��������8�I~1��_�{I����?\0̣��7?fC�R_��|M.��)^y�|[�L���d�9ۅ�0R�Snl��gt) { // loadtime before		
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