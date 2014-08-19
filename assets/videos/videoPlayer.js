(function (window, doc) {
    var m = Math,
        dummyStyle = doc.createElement('div').style,
        vendor = (function () {
            var vendors = 't,webkitT,MozT,msT,OT'.split(','),
                t,
                i = 0,
                l = vendors.length;

            for (; i < l; i++) {
                t = vendors[i] + 'ransform';
                if (t in dummyStyle) {
                    return vendors[i].substr(0, vendors[i].length - 1);
                }
            }

            return false;
        })(),
        cssVendor = vendor ? '-' + vendor.toLowerCase() + '-' : '',

// Style properties
        transform = prefixStyle('transform'),
        transitionProperty = prefixStyle('transitionProperty'),
        transitionDuration = prefixStyle('transitionDuration'),
        transformOrigin = prefixStyle('transformOrigin'),
        transitionTimingFunction = prefixStyle('transitionTimingFunction'),
        transitionDelay = prefixStyle('transitionDelay'),

// Browser capabilities
        isAndroid = (/android/gi).test(navigator.appVersion),
        isIDevice = (/iphone|ipad/gi).test(navigator.appVersion),
        isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),

        has3d = prefixStyle('perspective') in dummyStyle,
        hasTouch = 'ontouchstart' in window && !isTouchPad,
        hasTransform = vendor !== false,
        hasTransitionEnd = prefixStyle('transition') in dummyStyle,

        RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
        START_EV = hasTouch ? 'touchstart' : 'mousedown',
        MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
        END_EV = hasTouch ? 'touchend' : 'mouseup',
        CANCEL_EV = hasTouch ? 'touchcancel' : 'mouseup',
        TRNEND_EV = (function () {
            if (vendor === false) return false;

            var transitionEnd = {
                '':'transitionend',
                'webkit':'webkitTransitionEnd',
                'Moz':'transitionend',
                'O':'otransitionend',
                'ms':'MSTransitionEnd'
            };

            return transitionEnd[vendor];
        })(),

        nextFrame = (function () {
            return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                window.msRequestAnimationFrame ||
                function (callback) {
                    return setTimeout(callback, 1);
                };
        })(),
        cancelFrame = (function () {
            return window.cancelRequestAnimationFrame ||
                window.webkitCancelAnimationFrame ||
                window.webkitCancelRequestAnimationFrame ||
                window.mozCancelRequestAnimationFrame ||
                window.oCancelRequestAnimationFrame ||
                window.msCancelRequestAnimationFrame ||
                clearTimeout;
        })(),

// Helpers
        translateZ = has3d ? ' translateZ(0)' : '',

// Constructor
        iScroll = function (el, options) {
            var that = this,
                i;

            that.wrapper = typeof el == 'object' ? el : doc.getElementById(el);
            that.wrapper.style.overflow = 'hidden';
            that.scroller = that.wrapper.children[0];

            // Default options
            that.options = {
                hScroll:true,
                vScroll:true,
                x:0,
                y:0,
                bounce:true,
                bounceLock:false,
                momentum:true,
                lockDirection:true,
                useTransform:true,
                useTransition:false,
                topOffset:0,
                checkDOMChanges:false, // Experimental
                handleClick:true,

                // Scrollbar
                hScrollbar:true,
                vScrollbar:true,
                fixedScrollbar:isAndroid,
                hideScrollbar:isIDevice,
                fadeScrollbar:isIDevice && has3d,
                scrollbarClass:'',

                // Zoom
                zoom:false,
                zoomMin:1,
                zoomMax:4,
                doubleTapZoom:2,
                wheelAction:'scroll',

                // Snap
                snap:false,
                snapThreshold:1,

                // Events
                onRefresh:null,
                onBeforeScrollStart:function (e) {
                    e.preventDefault();
                },
                onScrollStart:null,
                onBeforeScrollMove:null,
                onScrollMove:null,
                onBeforeScrollEnd:null,
                onScrollEnd:null,
                onTouchEnd:null,
                onDestroy:null,
                onZoomStart:null,
                onZoom:null,
                onZoomEnd:null,
//custom
                keepInCenterH:false,
                keepInCenterV:false
            };

// User defined options
            for (i in options) that.options[i] = options[i];

// Set starting position
            that.x = that.options.x;
            that.y = that.options.y;

// Normalize options
            that.options.useTransform = hasTransform && that.options.useTransform;
            that.options.hScrollbar = that.options.hScroll && that.options.hScrollbar;
            that.options.vScrollbar = that.options.vScroll && that.options.vScrollbar;
            that.options.zoom = that.options.useTransform && that.options.zoom;
            that.options.useTransition = hasTransitionEnd && that.options.useTransition;
//custom
            that.keepInCenterH = that.options.keepInCenterH;
            that.keepInCenterV = that.options.keepInCenterV;

// Helpers FIX ANDROID BUG!
// translate3d and scale doesn't work together!
// Ignoring 3d ONLY WHEN YOU SET that.options.zoom
            if (that.options.zoom && isAndroid) {
                translateZ = '';
            }

// Set some default styles
            that.scroller.style[transitionProperty] = that.options.useTransform ? cssVendor + 'transform' : 'top left';
            that.scroller.style[transitionDuration] = '0';
            that.scroller.style[transformOrigin] = '0 0';
            if (that.options.useTransition) that.scroller.style[transitionTimingFunction] = 'cubic-bezier(0.33,0.66,0.66,1)';

            if (that.options.useTransform) that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px)' + translateZ;
            else that.scroller.style.cssText += ';position:absolute;top:' + that.y + 'px;left:' + that.x + 'px';

            if (that.options.useTransition) that.options.fixedScrollbar = true;

            that.refresh();

            that._bind(RESIZE_EV, window);
            that._bind(START_EV);
            if (!hasTouch) {
                if (that.options.wheelAction != 'none') {
                    that._bind('DOMMouseScroll');
                    that._bind('mousewheel');
                }
            }

            if (that.options.checkDOMChanges) that.checkDOMTime = setInterval(function () {
                that._checkDOMChanges();
            }, 500);
        };

// Prototype
    iScroll.prototype = {
        enabled:true,
        x:0,
        y:0,
        steps:[],
        scale:1,
        currPageX:0, currPageY:0,
        pagesX:[], pagesY:[],
        aniTime:null,
        wheelZoomCount:0,

        handleEvent:function (e) {
            var that = this;
            switch (e.type) {
                case START_EV:
                    if (!hasTouch && e.button !== 0) return;
                    that._start(e);
                    break;
                case MOVE_EV:
                    that._move(e);
                    break;
                case END_EV:
                case CANCEL_EV:
                    that._end(e);
                    break;
                case RESIZE_EV:
                    that._resize();
                    break;
                case 'DOMMouseScroll':
                case 'mousewheel':
                    that._wheel(e);
                    break;
                case TRNEND_EV:
                    that._transitionEnd(e);
                    break;
            }
        },

        _checkDOMChanges:function () {
            if (this.moved || this.zoomed || this.animating ||
                (this.scrollerW == this.scroller.offsetWidth * this.scale && this.scrollerH == this.scroller.offsetHeight * this.scale)) return;

            this.refresh();
        },

        _scrollbar:function (dir) {
            var that = this,
                bar;

            if (!that[dir + 'Scrollbar']) {
                if (that[dir + 'ScrollbarWrapper']) {
                    if (hasTransform) that[dir + 'ScrollbarIndicator'].style[transform] = '';
                    that[dir + 'ScrollbarWrapper'].parentNode.removeChild(that[dir + 'ScrollbarWrapper']);
                    that[dir + 'ScrollbarWrapper'] = null;
                    that[dir + 'ScrollbarIndicator'] = null;
                }

                return;
            }

            if (!that[dir + 'ScrollbarWrapper']) {
                // Create the scrollbar wrapper
                bar = doc.createElement('div');

                if (that.options.scrollbarClass) bar.className = that.options.scrollbarClass + dir.toUpperCase();
                else bar.style.cssText = 'position:absolute;z-index:100;' + (dir == 'h' ? 'height:7px;bottom:1px;left:2px;right:' + (that.vScrollbar ? '7' : '2') + 'px' : 'width:7px;bottom:' + (that.hScrollbar ? '7' : '2') + 'px;top:2px;right:1px');

                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:opacity;' + cssVendor + 'transition-duration:' + (that.options.fadeScrollbar ? '350ms' : '0') + ';overflow:hidden;opacity:' + (that.options.hideScrollbar ? '0' : '1');

                that.wrapper.appendChild(bar);
                that[dir + 'ScrollbarWrapper'] = bar;

                // Create the scrollbar indicator
                bar = doc.createElement('div');
                if (!that.options.scrollbarClass) {
                    bar.style.cssText = 'position:absolute;z-index:100;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);' + cssVendor + 'background-clip:padding-box;' + cssVendor + 'box-sizing:border-box;' + (dir == 'h' ? 'height:100%' : 'width:100%') + ';' + cssVendor + 'border-radius:3px;border-radius:3px';
                }
                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:' + cssVendor + 'transform;' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1);' + cssVendor + 'transition-duration:0;' + cssVendor + 'transform: translate(0,0)' + translateZ;
                if (that.options.useTransition) bar.style.cssText += ';' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1)';

                that[dir + 'ScrollbarWrapper'].appendChild(bar);
                that[dir + 'ScrollbarIndicator'] = bar;
            }

            if (dir == 'h') {
                that.hScrollbarSize = that.hScrollbarWrapper.clientWidth;
                that.hScrollbarIndicatorSize = m.max(m.round(that.hScrollbarSize * that.hScrollbarSize / that.scrollerW), 8);
                that.hScrollbarIndicator.style.width = that.hScrollbarIndicatorSize + 'px';
                that.hScrollbarMaxScroll = that.hScrollbarSize - that.hScrollbarIndicatorSize;
                that.hScrollbarProp = that.hScrollbarMaxScroll / that.maxScrollX;
            } else {
                that.vScrollbarSize = that.vScrollbarWrapper.clientHeight;
                that.vScrollbarIndicatorSize = m.max(m.round(that.vScrollbarSize * that.vScrollbarSize / that.scrollerH), 8);
                that.vScrollbarIndicator.style.height = that.vScrollbarIndicatorSize + 'px';
                that.vScrollbarMaxScroll = that.vScrollbarSize - that.vScrollbarIndicatorSize;
                that.vScrollbarProp = that.vScrollbarMaxScroll / that.maxScrollY;
            }

// Reset position
            that._scrollbarPos(dir, true);
        },

        _resize:function () {
            var that = this;
            setTimeout(function () {
                that.refresh();
            }, isAndroid ? 200 : 0);
        },

        _pos:function (x, y) {
            if (this.zoomed) return;
            //custom - we need to center the scroller if there is no scrollbars
//                if(!this.keepInCenterH)
//                    x = this.hScroll ? x : 0;
//                if(!this.keepInCenterV)
//                    y = this.vScroll ? y : 0;

            if (this.options.useTransform) {
                this.scroller.style[transform] = 'translate(' + x + 'px,' + y + 'px) scale(' + this.scale + ')' + translateZ;
            } else {
                x = m.round(x);
                y = m.round(y);
                this.scroller.style.left = x + 'px';
                this.scroller.style.top = y + 'px';
            }

            this.x = x;
            this.y = y;

            this._scrollbarPos('h');
            this._scrollbarPos('v');
        },

        _scrollbarPos:function (dir, hidden) {
            var that = this,
                pos = dir == 'h' ? that.x : that.y,
                size;

            if (!that[dir + 'Scrollbar']) return;

            pos = that[dir + 'ScrollbarProp'] * pos;

            if (pos < 0) {
                if (!that.options.fixedScrollbar) {
                    size = that[dir + 'ScrollbarIndicatorSize'] + m.round(pos * 3);
                    if (size < 8) size = 8;
                    that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px';
                }
                pos = 0;
            } else if (pos > that[dir + 'ScrollbarMaxScroll']) {
                if (!that.options.fixedScrollbar) {
                    size = that[dir + 'ScrollbarIndicatorSize'] - m.round((pos - that[dir + 'ScrollbarMaxScroll']) * 3);
                    if (size < 8) size = 8;
                    that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px';
                    pos = that[dir + 'ScrollbarMaxScroll'] + (that[dir + 'ScrollbarIndicatorSize'] - size);
                } else {
                    pos = that[dir + 'ScrollbarMaxScroll'];
                }
            }

            that[dir + 'ScrollbarWrapper'].style[transitionDelay] = '0';
            that[dir + 'ScrollbarWrapper'].style.opacity = hidden && that.options.hideScrollbar ? '0' : '1';
            that[dir + 'ScrollbarIndicator'].style[transform] = 'translate(' + (dir == 'h' ? pos + 'px,0)' : '0,' + pos + 'px)') + translateZ;
        },

        _start:function (e) {
            var that = this,
                point = hasTouch ? e.touches[0] : e,
                matrix, x, y,
                c1, c2;

            if (!that.enabled) return;

            if (that.options.onBeforeScrollStart) that.options.onBeforeScrollStart.call(that, e);

            if (that.options.useTransition || that.options.zoom) that._transitionTime(0);

            that.moved = false;
            that.animating = false;
            that.zoomed = false;
            that.distX = 0;
            that.distY = 0;
            that.absDistX = 0;
            that.absDistY = 0;
            that.dirX = 0;
            that.dirY = 0;

            // Gesture start
            if (that.options.zoom && hasTouch && e.touches.length > 1) {
                c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX);
                c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY);
                that.touchesDistStart = m.sqrt(c1 * c1 + c2 * c2);

                that.originX = m.abs(e.touches[0].pageX + e.touches[1].pageX - that.wrapperOffsetLeft * 2) / 2 - that.x;
                that.originY = m.abs(e.touches[0].pageY + e.touches[1].pageY - that.wrapperOffsetTop * 2) / 2 - that.y;

                if (that.options.onZoomStart) that.options.onZoomStart.call(that, e);
            }

            if (that.options.momentum) {
                if (that.options.useTransform) {
                    // Very lame general purpose alternative to CSSMatrix
                    matrix = getComputedStyle(that.scroller, null)[transform].replace(/[^0-9\-.,]/g, '').split(',');
                    x = +(matrix[12] || matrix[4]);
                    y = +(matrix[13] || matrix[5]);
                } else {
                    x = +getComputedStyle(that.scroller, null).left.replace(/[^0-9-]/g, '');
                    y = +getComputedStyle(that.scroller, null).top.replace(/[^0-9-]/g, '');
                }

                if (x != that.x || y != that.y) {
                    if (that.options.useTransition) that._unbind(TRNEND_EV);
                    else cancelFrame(that.aniTime);
                    that.steps = [];
                    that._pos(x, y);
                    if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);
                }
            }

            that.absStartX = that.x;	// Needed by snap threshold
            that.absStartY = that.y;

            that.startX = that.x;
            that.startY = that.y;
            that.pointX = point.pageX;
            that.pointY = point.pageY;

            that.startTime = e.timeStamp || Date.now();

            if (that.options.onScrollStart) that.options.onScrollStart.call(that, e);

            that._bind(MOVE_EV, window);
            that._bind(END_EV, window);
            that._bind(CANCEL_EV, window);
        },

        _move:function (e) {
            var that = this,
                point = hasTouch ? e.touches[0] : e,
                deltaX = point.pageX - that.pointX,
                deltaY = point.pageY - that.pointY,
                newX = that.x + deltaX,
                newY = that.y + deltaY,
                c1, c2, scale,
                timestamp = e.timeStamp || Date.now();

            if (that.options.onBeforeScrollMove) that.options.onBeforeScrollMove.call(that, e);

            // Zoom
            if (that.options.zoom && hasTouch && e.touches.length > 1) {
                c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX);
                c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY);
                that.touchesDist = m.sqrt(c1 * c1 + c2 * c2);

                that.zoomed = true;

                scale = 1 / that.touchesDistStart * that.touchesDist * this.scale;

                if (scale < that.options.zoomMin) scale = 0.5 * that.options.zoomMin * Math.pow(2.0, scale / that.options.zoomMin);
                else if (scale > that.options.zoomMax) scale = 2.0 * that.options.zoomMax * Math.pow(0.5, that.options.zoomMax / scale);

                that.lastScale = scale / this.scale;

                newX = this.originX - this.originX * that.lastScale + this.x,
                    newY = this.originY - this.originY * that.lastScale + this.y;

                this.scroller.style[transform] = 'translate(' + newX + 'px,' + newY + 'px) scale(' + scale + ')' + translateZ;

                if (that.options.onZoom) that.options.onZoom.call(that, e);
                return;
            }

            that.pointX = point.pageX;
            that.pointY = point.pageY;

// Slow down if outside of the boundaries
            if (newX > 0 || newX < that.maxScrollX) {
                newX = that.options.bounce ? that.x + (deltaX / 2) : newX >= 0 || that.maxScrollX >= 0 ? 0 : that.maxScrollX;
            }
            if (newY > that.minScrollY || newY < that.maxScrollY) {
                newY = that.options.bounce ? that.y + (deltaY / 2) : newY >= that.minScrollY || that.maxScrollY >= 0 ? that.minScrollY : that.maxScrollY;
            }

            that.distX += deltaX;
            that.distY += deltaY;
            that.absDistX = m.abs(that.distX);
            that.absDistY = m.abs(that.distY);

            if (that.absDistX < 6 && that.absDistY < 6) {
                return;
            }


            // Lock direction
            if (that.options.lockDirection) {
                if (that.absDistX > that.absDistY + 5) {
                    newY = that.y;
                    deltaY = 0;
                } else if (that.absDistY > that.absDistX + 5) {
                    newX = that.x;
                    deltaX = 0;
                }
            }

            that.moved = true;
            that._pos(newX, newY);
            that.dirX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
            that.dirY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

            if (timestamp - that.startTime > 300) {
                that.startTime = timestamp;
                that.startX = that.x;
                that.startY = that.y;
            }

            if (that.options.onScrollMove) that.options.onScrollMove.call(that, e);
        },

        _end:function (e) {
            if (hasTouch && e.touches.length !== 0) return;

            var that = this,
                point = hasTouch ? e.changedTouches[0] : e,
                target, ev,
                momentumX = { dist:0, time:0 },
                momentumY = { dist:0, time:0 },
                duration = (e.timeStamp || Date.now()) - that.startTime,
                newPosX = that.x,
                newPosY = that.y,
                distX, distY,
                newDuration,
                snap,
                scale;

            that._unbind(MOVE_EV, window);
            that._unbind(END_EV, window);
            that._unbind(CANCEL_EV, window);

            if (that.options.onBeforeScrollEnd) that.options.onBeforeScrollEnd.call(that, e);

            if (that.zoomed) {
                scale = that.scale * that.lastScale;
                scale = Math.max(that.options.zoomMin, scale);
                scale = Math.min(that.options.zoomMax, scale);
                that.lastScale = scale / that.scale;
                that.scale = scale;

                that.x = that.originX - that.originX * that.lastScale + that.x;
                that.y = that.originY - that.originY * that.lastScale + that.y;

                that.scroller.style[transitionDuration] = '200ms';
                that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + that.scale + ')' + translateZ;

                that.zoomed = false;
                that.refresh();

                if (that.options.onZoomEnd) that.options.onZoomEnd.call(that, e, scale);
                return;
            }

            if (!that.moved) {
                //custom - double click and double tap
                if (true) {
//                    if (hasTouch) {
                    if (that.doubleTapTimer && that.options.zoom) {
                        // Double tapped
                        clearTimeout(that.doubleTapTimer);
                        that.doubleTapTimer = null;

                        if (that.options.onZoomStart) that.options.onZoomStart.call(that, e);
//                            that.zoom(that.pointX, that.pointY, that.scale == 1 ? that.options.doubleTapZoom : 1);
                        //custom    - double tap zoom disabled
//                        that.zoom(that.pointX, that.pointY, that.scale < that.options.zoomMin * that.options.doubleTapZoom ? that.options.zoomMin * that.options.doubleTapZoom : that.options.zoomMin);
                        if (that.options.onZoomEnd) {
                            setTimeout(function () {
                                that.options.onZoomEnd.call(that, e, scale);
                            }, 200); // 200 is default zoom duration
                        }
                    } else if (this.options.handleClick) {
                        //first tap
                        that.doubleTapTimer = setTimeout(function () {
                            that.doubleTapTimer = null;

                            // Find the last touched element
                            target = point.target;
                            while (target.nodeType != 1) target = target.parentNode;

                            if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA') {
                                ev = doc.createEvent('MouseEvents');
                                ev.initMouseEvent('click', true, true, e.view, 1,
                                    point.screenX, point.screenY, point.clientX, point.clientY,
                                    e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
                                    0, null);
                                ev._fake = true;
                                target.dispatchEvent(ev);
                            }

                            //custom - first tap, after timeout
                            if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                        }, that.options.zoom ? 250 : 0);
                    }
                }

                that._resetPos(400);
                //custom - call only if no double tap
                //                    if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            if (duration < 300 && that.options.momentum) {
                momentumX = newPosX ? that._momentum(newPosX - that.startX, duration, -that.x, that.scrollerW - that.wrapperW + that.x, that.options.bounce ? that.wrapperW : 0) : momentumX;
                momentumY = newPosY ? that._momentum(newPosY - that.startY, duration, -that.y, (that.maxScrollY < 0 ? that.scrollerH - that.wrapperH + that.y - that.minScrollY : 0), that.options.bounce ? that.wrapperH : 0) : momentumY;

                newPosX = that.x + momentumX.dist;
                newPosY = that.y + momentumY.dist;

                if ((that.x > 0 && newPosX > 0) || (that.x < that.maxScrollX && newPosX < that.maxScrollX)) momentumX = { dist:0, time:0 };
                if ((that.y > that.minScrollY && newPosY > that.minScrollY) || (that.y < that.maxScrollY && newPosY < that.maxScrollY)) momentumY = { dist:0, time:0 };
            }

            if (momentumX.dist || momentumY.dist) {
                newDuration = m.max(m.max(momentumX.time, momentumY.time), 10);

                // Do we need to snap?
                if (that.options.snap) {
                    distX = newPosX - that.absStartX;
                    distY = newPosY - that.absStartY;
                    if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) {
                        that.scrollTo(that.absStartX, that.absStartY, 200);
                    }
                    else {
                        snap = that._snap(newPosX, newPosY);
                        newPosX = snap.x;
                        newPosY = snap.y;
                        newDuration = m.max(snap.time, newDuration);
                    }
                }

                that.scrollTo(m.round(newPosX), m.round(newPosY), newDuration);

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            // Do we need to snap?
            if (that.options.snap) {
                distX = newPosX - that.absStartX;
                distY = newPosY - that.absStartY;
                if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) that.scrollTo(that.absStartX, that.absStartY, 200);
                else {
                    snap = that._snap(that.x, that.y);
                    if (snap.x != that.x || snap.y != that.y) that.scrollTo(snap.x, snap.y, snap.time);
                }

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            that._resetPos(200);
            if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
        },

        _resetPos:function (time) {
            var that = this;
            //custom : stay in center
            if (that.keepInCenterH && that.scrollerW < that.wrapperW) {
                resetX = that.x >= 0 ? (that.wrapperW - that.scrollerW) / 2 : that.x < that.maxScrollX ? that.maxScrollX : that.x;
            }
            else
                resetX = that.x >= 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x;

            if (that.keepInCenterV && that.scrollerH < that.wrapperH) {
                resetY = that.y >= that.minScrollY || that.maxScrollY > 0 ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;
                resetY = that.y > 0 ? (that.wrapperH - that.scrollerH) / 2 : resetY;
            }

            else
                resetY = that.y >= that.minScrollY || that.maxScrollY > 0 ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;

            if (resetX == that.x && resetY == that.y) {
                if (that.moved) {
                    that.moved = false;
                    if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);		// Execute custom code on scroll end
                }

                if (that.hScrollbar && that.options.hideScrollbar) {
                    if (vendor == 'webkit') that.hScrollbarWrapper.style[transitionDelay] = '300ms';
                    that.hScrollbarWrapper.style.opacity = '0';
                }
                if (that.vScrollbar && that.options.hideScrollbar) {
                    if (vendor == 'webkit') that.vScrollbarWrapper.style[transitionDelay] = '300ms';
                    that.vScrollbarWrapper.style.opacity = '0';
                }

                return;
            }

            that.scrollTo(resetX, resetY, time || 0);
        },

        _wheel:function (e) {
            var that = this,
                wheelDeltaX, wheelDeltaY,
                deltaX, deltaY,
                deltaScale;

            if ('wheelDeltaX' in e) {
                wheelDeltaX = e.wheelDeltaX / 12;
                wheelDeltaY = e.wheelDeltaY / 12;
            } else if ('wheelDelta' in e) {
                wheelDeltaX = wheelDeltaY = e.wheelDelta / 12;
            } else if ('detail' in e) {
                wheelDeltaX = wheelDeltaY = -e.detail * 3;
            } else {
                return;
            }

            if (that.options.wheelAction == 'zoom') {
                deltaScale = that.scale * Math.pow(2, 1 / 3 * (wheelDeltaY ? wheelDeltaY / Math.abs(wheelDeltaY) : 0));
                if (deltaScale < that.options.zoomMin) deltaScale = that.options.zoomMin;
                if (deltaScale > that.options.zoomMax) deltaScale = that.options.zoomMax;

                if (deltaScale != that.scale) {
                    if (!that.wheelZoomCount && that.options.onZoomStart) that.options.onZoomStart.call(that, e);
                    that.wheelZoomCount++;

                    that.zoom(e.pageX, e.pageY, deltaScale, 400);

                    setTimeout(function () {
                        that.wheelZoomCount--;
                        if (!that.wheelZoomCount && that.options.onZoomEnd) that.options.onZoomEnd.call(that, e, that.scale);
                    }, 400);
                }

                return;
            }

            deltaX = that.x + wheelDeltaX;
            deltaY = that.y + wheelDeltaY;

            if (deltaX > 0) deltaX = 0;
            else if (deltaX < that.maxScrollX) deltaX = that.maxScrollX;

            if (deltaY > that.minScrollY) deltaY = that.minScrollY;
            else if (deltaY < that.maxScrollY) deltaY = that.maxScrollY;

            if (that.maxScrollY < 0) {
                that.scrollTo(deltaX, deltaY, 0);
            }
        },

        _transitionEnd:function (e) {
            var that = this;

            if (e.target != that.scroller) return;

            that._unbind(TRNEND_EV);

            that._startAni();
        },


        /**
         *
         * Utilities
         *
         */
        _startAni:function () {
            var that = this,
                startX = that.x, startY = that.y,
                startTime = Date.now(),
                step, easeOut,
                animate;

            if (that.animating) return;

            if (!that.steps.length) {
                that._resetPos(400);
                return;
            }

            step = that.steps.shift();

            if (step.x == startX && step.y == startY) step.time = 0;

            that.animating = true;
            that.moved = true;

            if (that.options.useTransition) {
                that._transitionTime(step.time);
                that._pos(step.x, step.y);
                that.animating = false;
                if (step.time) that._bind(TRNEND_EV);
                else that._resetPos(0);
                return;
            }

            animate = function () {
                var now = Date.now(),
                    newX, newY;

                if (now >= startTime + step.time) {
                    that._pos(step.x, step.y);
                    that.animating = false;
                    if (that.options.onAnimationEnd) that.options.onAnimationEnd.call(that);			// Execute custom code on animation end
                    that._startAni();
                    return;
                }

                now = (now - startTime) / step.time - 1;
                easeOut = m.sqrt(1 - now * now);
                newX = (step.x - startX) * easeOut + startX;
                newY = (step.y - startY) * easeOut + startY;
                that._pos(newX, newY);
                if (that.animating) that.aniTime = nextFrame(animate);
            };

            animate();
        },

        _transitionTime:function (time) {
            time += 'ms';
            this.scroller.style[transitionDuration] = time;
            if (this.hScrollbar) this.hScrollbarIndicator.style[transitionDuration] = time;
            if (this.vScrollbar) this.vScrollbarIndicator.style[transitionDuration] = time;
        },

        _momentum:function (dist, time, maxDistUpper, maxDistLower, size) {
            var deceleration = 0.0006,
                speed = m.abs(dist) / time,
                newDist = (speed * speed) / (2 * deceleration),
                newTime = 0, outsideDist = 0;

            // Proportinally reduce speed if we are outside of the boundaries
            if (dist > 0 && newDist > maxDistUpper) {
                outsideDist = size / (6 / (newDist / speed * deceleration));
                maxDistUpper = maxDistUpper + outsideDist;
                speed = speed * maxDistUpper / newDist;
                newDist = maxDistUpper;
            } else if (dist < 0 && newDist > maxDistLower) {
                outsideDist = size / (6 / (newDist / speed * deceleration));
                maxDistLower = maxDistLower + outsideDist;
                speed = speed * maxDistLower / newDist;
                newDist = maxDistLower;
            }

            newDist = newDist * (dist < 0 ? -1 : 1);
            newTime = speed / deceleration;

            return { dist:newDist, time:m.round(newTime) };
        },

        _offset:function (el) {
            var left = -el.offsetLeft,
                top = -el.offsetTop;

            while (el = el.offsetParent) {
                left -= el.offsetLeft;
                top -= el.offsetTop;
            }

            if (el != this.wrapper) {
                left *= this.scale;
                top *= this.scale;
            }

            return { left:left, top:top };
        },

        _snap:function (x, y) {
            var that = this,
                i, l,
                page, time,
                sizeX, sizeY;

            // Check page X
            page = that.pagesX.length - 1;
            //custom - fix for loop
            l = that.pagesX.length;
            for (i = 0; i < l; i++) {
//            for (i = 0, l = that.pagesX.length; i < l; i++) {
                if (x >= that.pagesX[i]) {
                    page = i;
                    break;
                }
            }
            if (page == that.currPageX && page > 0 && that.dirX < 0) page--;
            x = that.pagesX[page];
            sizeX = m.abs(x - that.pagesX[that.currPageX]);
            sizeX = sizeX ? m.abs(that.x - x) / sizeX * 500 : 0;
            that.currPageX = page;

            // Check page Y
            page = that.pagesY.length - 1;
            for (i = 0; i < page; i++) {
                if (y >= that.pagesY[i]) {
                    page = i;
                    break;
                }
            }
            if (page == that.currPageY && page > 0 && that.dirY < 0) page--;
            y = that.pagesY[page];
            sizeY = m.abs(y - that.pagesY[that.currPageY]);
            sizeY = sizeY ? m.abs(that.y - y) / sizeY * 500 : 0;
            that.currPageY = page;

            // Snap with constant speed (proportional duration)
            time = m.round(m.max(sizeX, sizeY)) || 200;

            return { x:x, y:y, time:time };
        },

        _bind:function (type, el, bubble) {
            (el || this.scroller).addEventListener(type, this, !!bubble);
        },

        _unbind:function (type, el, bubble) {
            (el || this.scroller).removeEventListener(type, this, !!bubble);
        },


        /**
         *
         * Public methods
         *
         */
        destroy:function () {
            var that = this;

            that.scroller.style[transform] = '';

            // Remove the scrollbars
            that.hScrollbar = false;
            that.vScrollbar = false;
            that._scrollbar('h');
            that._scrollbar('v');

            // Remove the event listeners
            that._unbind(RESIZE_EV, window);
            that._unbind(START_EV);
            that._unbind(MOVE_EV, window);
            that._unbind(END_EV, window);
            that._unbind(CANCEL_EV, window);

            if (!that.options.hasTouch) {
                that._unbind('DOMMouseScroll');
                that._unbind('mousewheel');
            }

            if (that.options.useTransition) that._unbind(TRNEND_EV);

            if (that.options.checkDOMChanges) clearInterval(that.checkDOMTime);

            if (that.options.onDestroy) that.options.onDestroy.call(that);
        },

        refresh:function () {
            var that = this,
                offset,
                i, l,
                els,
                pos = 0,
                page = 0;

            if (that.scale < that.options.zoomMin) that.scale = that.options.zoomMin;
            that.wrapperW = that.wrapper.clientWidth || 1;
            that.wrapperH = that.wrapper.clientHeight || 1;

            that.minScrollY = -that.options.topOffset || 0;
            that.scrollerW = m.round(that.scroller.offsetWidth * that.scale);
            that.scrollerH = m.round((that.scroller.offsetHeight + that.minScrollY) * that.scale);
            that.maxScrollX = that.wrapperW - that.scrollerW;
            that.maxScrollY = that.wrapperH - that.scrollerH + that.minScrollY;
            that.dirX = 0;
            that.dirY = 0;

            if (that.options.onRefresh) that.options.onRefresh.call(that);

            that.hScroll = that.options.hScroll && that.maxScrollX < 0;
            that.vScroll = that.options.vScroll && (!that.options.bounceLock && !that.hScroll || that.scrollerH > that.wrapperH);

            that.hScrollbar = that.hScroll && that.options.hScrollbar;
            that.vScrollbar = that.vScroll && that.options.vScrollbar && that.scrollerH > that.wrapperH;

            offset = that._offset(that.wrapper);
            that.wrapperOffsetLeft = -offset.left;
            that.wrapperOffsetTop = -offset.top;

            // Prepare snap
            if (typeof that.options.snap == 'string') {
                that.pagesX = [];
                that.pagesY = [];
                els = that.scroller.querySelectorAll(that.options.snap);
                //custom - fix for loop
                l = els.length;
                for (i = 0; i < l; i++) {
                    pos = that._offset(els[i]);
                    pos.left += that.wrapperOffsetLeft;
                    pos.top += that.wrapperOffsetTop;
                    that.pagesX[i] = pos.left < that.maxScrollX ? that.maxScrollX : pos.left * that.scale;
                    that.pagesY[i] = pos.top < that.maxScrollY ? that.maxScrollY : pos.top * that.scale;
                }
            } else if (that.options.snap) {
                that.pagesX = [];
                while (pos >= that.maxScrollX) {
                    that.pagesX[page] = pos;
                    pos = pos - that.wrapperW;
                    page++;
                }
                if (that.maxScrollX % that.wrapperW) that.pagesX[that.pagesX.length] = that.maxScrollX - that.pagesX[that.pagesX.length - 1] + that.pagesX[that.pagesX.length - 1];

                pos = 0;
                page = 0;
                that.pagesY = [];
                while (pos >= that.maxScrollY) {
                    that.pagesY[page] = pos;
                    pos = pos - that.wrapperH;
                    page++;
                }
                if (that.maxScrollY % that.wrapperH) that.pagesY[that.pagesY.length] = that.maxScrollY - that.pagesY[that.pagesY.length - 1] + that.pagesY[that.pagesY.length - 1];
            }

            // Prepare the scrollbars
            that._scrollbar('h');
            that._scrollbar('v');

            if (!that.zoomed) {
                that.scroller.style[transitionDuration] = '0';
                that._resetPos(400);
            }
        },

        scrollTo:function (x, y, time, relative) {
            var that = this,
                step = x,
                i, l;

            that.stop();

            if (!step.length) step = [
                { x:x, y:y, time:time, relative:relative }
            ];
             //custom - fix for loop
            l = step.length;
            for (i = 0; i < l; i++) {
                if (step[i].relative) {
                    step[i].x = that.x - step[i].x;
                    step[i].y = that.y - step[i].y;
                }
                that.steps.push({ x:step[i].x, y:step[i].y, time:step[i].time || 0 });
            }

            that._startAni();
        },

        scrollToElement:function (el, time) {
            var that = this, pos;
            el = el.nodeType ? el : that.scroller.querySelector(el);
            if (!el) return;

            pos = that._offset(el);
            pos.left += that.wrapperOffsetLeft;
            pos.top += that.wrapperOffsetTop;

            pos.left = pos.left > 0 ? 0 : pos.left < that.maxScrollX ? that.maxScrollX : pos.left;
            pos.top = pos.top > that.minScrollY ? that.minScrollY : pos.top < that.maxScrollY ? that.maxScrollY : pos.top;
            time = time === undefined ? m.max(m.abs(pos.left) * 2, m.abs(pos.top) * 2) : time;

            that.scrollTo(pos.left, pos.top, time);
        },

        scrollToPage:function (pageX, pageY, time) {
            var that = this, x, y;

            time = time === undefined ? 400 : time;

            if (that.options.onScrollStart) that.options.onScrollStart.call(that);

            if (that.options.snap) {
                pageX = pageX == 'next' ? that.currPageX + 1 : pageX == 'prev' ? that.currPageX - 1 : pageX;
                pageY = pageY == 'next' ? that.currPageY + 1 : pageY == 'prev' ? that.currPageY - 1 : pageY;

                pageX = pageX < 0 ? 0 : pageX > that.pagesX.length - 1 ? that.pagesX.length - 1 : pageX;
                pageY = pageY < 0 ? 0 : pageY > that.pagesY.length - 1 ? that.pagesY.length - 1 : pageY;

                that.currPageX = pageX;
                that.currPageY = pageY;
                x = that.pagesX[pageX];
                y = that.pagesY[pageY];
            } else {
                x = -that.wrapperW * pageX;
                y = -that.wrapperH * pageY;
                if (x < that.maxScrollX) x = that.maxScrollX;
                if (y < that.maxScrollY) y = that.maxScrollY;
            }

            that.scrollTo(x, y, time);
        },

        disable:function () {
            this.stop();
            this._resetPos(0);
            this.enabled = false;

            // If disabled after touchstart we make sure that there are no left over events
            this._unbind(MOVE_EV, window);
            this._unbind(END_EV, window);
            this._unbind(CANCEL_EV, window);
        },

        enable:function () {
            this.enabled = true;
        },

        stop:function () {
            if (this.options.useTransition) this._unbind(TRNEND_EV);
            else cancelFrame(this.aniTime);
            this.steps = [];
            this.moved = false;
            this.animating = false;
        },

        zoom:function (x, y, scale, time) {
            var that = this,
                relScale = scale / that.scale;

            if (!that.options.useTransform) return;

            that.zoomed = true;
            time = time === undefined ? 200 : time;
            x = x - that.wrapperOffsetLeft - that.x;
            y = y - that.wrapperOffsetTop - that.y;
            that.x = x - x * relScale + that.x;
            that.y = y - y * relScale + that.y;

            that.scale = scale;
            that.refresh();

            that.x = that.x > 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x;
            that.y = that.y > that.minScrollY ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;

            //custom   - fix for inner container to be in the middle
            if (that.keepInCenterH) {
                if (that.scrollerW < that.wrapperW) {
                    that.x = (that.wrapperW - that.scrollerW) / 2;
                }
            }
            if (that.keepInCenterV) {
                if (that.scrollerH < that.wrapperH) {
                    that.y = (that.wrapperH - that.scrollerH) / 2;
                }
            }
            that.scroller.style[transitionDuration] = time + 'ms';
            that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + scale + ')' + translateZ;
            that.zoomed = false;
        },

        isReady:function () {
            return !this.moved && !this.zoomed && !this.animating;
        },

        // custom
        setZoomMin:function (value) {
            this.options.zoomMin = value;
        },
        setX:function (value) {
            this.x = value;
        }
    };

    function prefixStyle(style) {
        if (vendor === '') return style;

        style = style.charAt(0).toUpperCase() + style.substr(1);
        return vendor + style;
    }

    dummyStyle = null;	// for the sake of it

    if (typeof exports !== 'undefined') exports.iScroll = iScroll;
    else window.iScroll = iScroll;

})(window, document);

// This THREEx helper makes it easy to handle the fullscreen API
// * it hides the prefix for each browser
// * it hides the little discrepencies of the various vendor API
// * at the time of this writing (nov 2011) it is available in 
//   [firefox nightly](http://blog.pearce.org.nz/2011/11/firefoxs-html-full-screen-api-enabled.html),
//   [webkit nightly](http://peter.sh/2011/01/javascript-full-screen-api-navigation-timing-and-repeating-css-gradients/) and
//   [chrome stable](http://updates.html5rocks.com/2011/10/Let-Your-Content-Do-the-Talking-Fullscreen-API).

// 
// # Code

//

/** @namespace */
var THREEx		= THREEx 		|| {};
THREEx.FullScreen	= THREEx.FullScreen	|| {};

/**
 * test if it is possible to have fullscreen
 * 
 * @returns {Boolean} true if fullscreen API is available, false otherwise
*/
THREEx.FullScreen.available	= function()
{
	return this._hasWebkitFullScreen || this._hasMozFullScreen;
}

/**
 * test if fullscreen is currently activated
 * 
 * @returns {Boolean} true if fullscreen is currently activated, false otherwise
*/
THREEx.FullScreen.activated	= function()
{
	if( this._hasWebkitFullScreen ){
		return document.webkitIsFullScreen;
	}else if( this._hasMozFullScreen ){
		return document.mozFullScreen;
	}else{
		console.assert(false);
	}
}

/**
 * Request fullscreen on a given element
 * @param {DomElement} element to make fullscreen. optional. default to document.body
*/
THREEx.FullScreen.request	= function(element)
{
	element	= element	|| document.body;
	if( this._hasWebkitFullScreen ){
		element.webkitRequestFullScreen();
	}else if( this._hasMozFullScreen ){
		element.mozRequestFullScreen();
	}else{
		console.assert(false);
	}
}

/**
 * Cancel fullscreen
*/
THREEx.FullScreen.cancel	= function()
{
	if( this._hasWebkitFullScreen ){
		document.webkitCancelFullScreen();
	}else if( this._hasMozFullScreen ){
		document.mozCancelFullScreen();
	}else{
		console.assert(false);
	}
}

// internal functions to know which fullscreen API implementation is available
THREEx.FullScreen._hasWebkitFullScreen	= 'webkitCancelFullScreen' in document	? true : false;	
THREEx.FullScreen._hasMozFullScreen	= 'mozCancelFullScreen' in document	? true : false;	


(function(a){a.fn.Video=function(a,k){return new c(this,a)};var n={autoplay:!1,autohideControls:4,videoPlayerWidth:746,videoPlayerHeight:420,posterImg:"/admin/assets/videos/intro.jpg",fullscreen_native:!1,fullscreen_browser:!0,restartOnFinish:!0,spaceKeyActive:!0,rightClickMenu:!0,share:[{show:!0,facebookLink:"http://demo.creativeslave.com/admin/",twitterLink:"http://demo.creativeslave.com/admin/",youtubeLink:"http://demo.creativeslave.com/admin/",pinterestLink:"http://demo.creativeslave.com/admin/",linkedinLink:"http://demo.creativeslave.com/admin/",googlePlusLink:"http://demo.creativeslave.com/admin/",
    deliciousLink:"http://demo.creativeslave.com/admin/",mailLink:"http://demo.creativeslave.com/admin/"}],logo:[{show:!0,clickable:!0,path:"/admin/assets/img/telespine-logo-white.png",goToLink:"http://demo.creativeslave.com/admin/",position:"top-right"}],embed:[{show:!0,embedCode:'<iframe src="demo.creativeslave.com/player/index.html" width="746" height="420" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'}],videos:[{id:0,title:"Logo reveal",mp4:"videos/video1.mp4",webm:"videos/video1.webm",ogv:"videos/video1.ogv",info:"Video info goes here",
    popupAdvertisementShow:!0,popupAdvertisementPath:"images/advertisement_images/ad2.jpg",popupAdvertisementGotoLink:"http://demo.creativeslave.com/admin/",popupAdvertisementStartTime:"00:02",popupAdvertisementEndTime:"00:05",videoAdvertisementShow:!0,videoAdvertisementClickable:!0,videoAdvertisementGotoLink:"http://demo.creativeslave.com/admin/",videoAdvertisement_mp4:"videos/video3.mp4",videoAdvertisement_webm:"videos/video3.webm",videoAdvertisement_ogv:"videos/video3.ogv"}]},p=/hp-tablet/gi.test(navigator.appVersion),f="ontouchstart"in
    window&&!p,q="onorientationchange"in window?"orientationchange":"resize",r=f?"touchend":"click",s=f?"touchstart":"mousedown",l=f?"touchmove":"mousemove",t=f?"touchend":"mouseup",c=function(b,k){this._class=c;this.parent=b;this.options=a.extend({},n,k);this.sources=this.options.srcs||this.options.sources;this.useNative=this.options.useNative;this.options.useFullScreen=!!this.useNative;this.state=null;this.embedOn=this.shareOn=this.adOn=this.infoOn=this.stretching=this.realFullscreenActive=this.inFullScreen=
    !1;pw=!0;this.loaded=!1;this.readyList=[];this.hasTouch=f;this.RESIZE_EV=q;this.CLICK_EV=r;this.START_EV=s;this.MOVE_EV=l;this.END_EV=t;this.maximumWidth=930;this.canPlay=!1;myVideo=document.createElement("video");this.options.rightClickMenu||a("#video").bind("contextmenu",function(){return!1});this.setupElement();this.init()};c.fn=c.prototype;c.fn.init=function(){console.log("init");this.preloader=a("<div />");this.preloader.addClass("preloader");this._playlist=new PLAYER.Playlist(this.options,this.options.videos,
    this,this.element,this.preloader,myVideo,this.canPlay,this.CLICK_EV,pw);this.videos_array=[];this.item_array=[];this.playerWidth=this.options.videoPlayerWidth-this._playlist.playlistW;this.playerHeight=this.options.videoPlayerHeight;this.playlistWidth=this._playlist.playlistW;this.initPlayer();this.resize()};c.fn.initPlayer=function(){this.setupHTML5Video();this.ready(a.proxy(function(){this.setupEvents();this.change("initial");this.setupControls();this.load();this.setupAutoplay();this.element.bind("idle",
    a.proxy(this.idle,this));this.element.bind("state.videoPlayer",a.proxy(function(){this.element.trigger("reset.idle")},this))},this));this.secondsFormat=function(a){isNaN(a)&&(a=0);var b=[],c=Math.floor(a/60),e=Math.floor(a/3600);a=Math.round(0==a?0:a%60);0<e&&b.push(10>e?"0"+e:e);b.push(10>c?"0"+c:c);b.push(10>a?"0"+a:a);return b.join(":")};var b=this;a(window).resize(function(){b.inFullScreen||b.realFullscreenActive||b.resizeAll()});a(document).bind("webkitfullscreenchange mozfullscreenchange fullscreenchange",
    function(a){b.resize(a)});this.resize=function(c){document.webkitIsFullScreen||document.fullscreenElement||document.mozFullScreen?(this._playlist.hidePlaylist(),this.element.addClass("fullScreen"),a(this.controls).find(".icon-expand").removeClass("icon-expand").addClass("icon-contract"),b.element.width(a(document).width()),b.element.height(a(document).height()),this.infoWindow.css({bottom:b.controls.height()+30,left:a(window).width/2-this.infoWindow.width()/2}),b.realFullscreenActive=!0):(this._playlist.showPlaylist(),
    this.element.removeClass("fullScreen"),a(this.controls).find(".icon-contract").removeClass("icon-contract").addClass("icon-expand"),b.element.width(b.playerWidth),b.element.height(b.playerHeight),this.infoWindow.css({bottom:b.controls.height()+30,left:b.playerWidth/2-this.infoWindow.width()/2}),this.stretching&&(this.stretching=!1,this.toggleStretch()),b.element.css({zIndex:100}),b.realFullscreenActive=!1,b.resizeAll());this.resizeVideoTrack();this.positionInfoWindow();this.positionShareWindow();
    this.positionEmbedWindow();this.positionLogo();this.positionAds();this.positionVideoAdBox();this.resizeBars();this.resizeControls();this.autohideControls()}};c.fn.autohideControls=function(){var b=a(this.element),c=!1,d=1E3*this.options.autohideControls,g=0,e=function(){c&&b.trigger("idle",!1);c=!1;g=0};b.bind("mousemove keydown DOMMouseScroll mousewheel mousedown reset.idle",e);var f=setInterval(function(){g>=d?(e(),c=!0,b.trigger("idle",!0)):g+=1E3},1E3);b.unload(function(){clearInterval(f)})};
    c.fn.resizeAll=function(){a(window).width()<this.options.videoPlayerWidth?400>a(window).width()?(this.newPlayerWidth=a(window).width(),this.controls.css({width:a(window).width()}),this.infoWindow.css({width:a(window).width()}),this.embedWindow.css({width:a(window).width()}),this.resizeControls(),280>a(window).width()?this.rewindBtn.hide():this.rewindBtn.show(),255>a(window).width()?this.infoBtn.hide():this.infoBtn.show(),235>a(window).width()?this.embedBtn.hide():this.embedBtn.show(),210>a(window).width()?
        this.shareBtn.hide():this.shareBtn.show()):(this.newPlayerWidth=a(window).width(),this.positionInfoWindow(),this.positionEmbedWindow()):this.newPlayerWidth=this.options.videoPlayerWidth;this.newPlayerHeight=this.newPlayerWidth*this.playerHeight/this.playerWidth;this.element.width(this.newPlayerWidth);this.element.height(this.newPlayerHeight);this.positionEmbedWindow();this.positionAds();this.positionVideoAdBox();this.positionInfoWindow();this.resizeVideoTrack();this.positionShareWindow();this.positionLogo();
        this.resizeBars();this.resizeControls()};c.fn.resizeControls=function(){this.controls.css({left:this.element.width()/2-this.controls.width()/2})};c.fn.resizeBars=function(){this.downloadWidth=this.buffered/this.video.duration*this.videoTrack.width();this.videoTrackDownload.css("width",this.downloadWidth);this.progressWidth=this.video.currentTime/this.video.duration*this.videoTrack.width();this.videoTrackProgress.css("width",this.progressWidth)};c.fn.createLogo=function(){var b=this;this.logoImg=a("<div/>");
        this.logoImg.addClass("logo");this.img=new Image;this.img.src=b.options.logo[0].path;a(this.img).load(function(){b.logoImg.append(b.img);b.positionLogo()});b.options.logo[0].show&&this.element.append(this.logoImg);b.options.logo[0].clickable&&(this.logoImg.bind(this.START_EV,a.proxy(function(){window.open(b.options.logo[0].goToLink)},this)),this.logoImg.mouseover(function(){a(this).stop().animate({opacity:0.5},200)}),this.logoImg.mouseout(function(){a(this).stop().animate({opacity:1},200)}),a(".logo").css("cursor",
            "pointer"))};c.fn.positionLogo=function(){"bottom-right"==this.options.logo[0].position?this.logoImg.css({bottom:this.controls.height()+this.toolTip.height()+8,left:this.element.width()-this.logoImg.width()-buttonsMargin}):"bottom-left"==this.options.logo[0].position?this.logoImg.css({bottom:this.controls.height()+this.toolTip.height()+8,left:buttonsMargin}):"top-right"==this.options.logo[0].position&&this.logoImg.css({top:30,right:30})};c.fn.createAds=function(){var b=this;this.adImg=a("<div/>");
        this.adImg.addClass("ads");b.image=new Image;b.image.src=b._playlist.videos_array[0].adPath;a(b.image).load(function(){b.adImg.append(b.image);b.positionAds()});this.element.append(this.adImg);this.adImg.hide();this.adImg.css({opacity:0});this.adClose=a("<div />");this.adClose.addClass("adClose");this.adImg.append(this.adClose);this.adClose.css({bottom:0});this.adClose.bind(this.START_EV,a.proxy(function(){b.adOn=!0;b.toggleAdWindow()},this));this.adClose.mouseover(function(){a(this).stop().animate({opacity:0.5},
            200)});this.adClose.mouseout(function(){a(this).stop().animate({opacity:1},200)})};c.fn.positionAds=function(){this.adImg.css({bottom:this.controls.height()+40,left:this.element.width()/2-this.adImg.width()/2})};c.fn.newAd=function(b,c){var d=this;this.adImg.hide();d.image.src="";d.image.src=d._playlist.videos_array[0].adPath;a(d.image).bind(this.START_EV,a.proxy(function(){d.options.videos[0].popupAdvertisementClickable&&(window.open(d._playlist.videos_array[0].adGotoLink),d.pause())},this));d.options.videos[0].popupAdvertisementClickable&&
    a(".ads").css("cursor","pointer")};c.fn.setupAutoplay=function(){this.options.autoplay?this.play():this.options.autoplay||(this.pause(),this.preloader.hide())};c.fn.createNowPlayingText=function(){this.element.append('<p class="nowPlayingText">'+this._playlist.videos_array[0].title+"</p>")};c.fn.createInfoWindowContent=function(){this.infoWindow.append('<p class="infoTitle">'+this._playlist.videos_array[0].title+"</p>");this.infoWindow.append('<p class="infoText">'+this._playlist.videos_array[0].info_text+
        "</p>");this.infoWindow.hide();this.positionInfoWindow()};c.fn.createVideoAdTitle=function(){this.videoAdBox=a("<div />");this.videoAdBox.addClass("videoAdBox");this.element.append(this.videoAdBox);this.videoAdBox.append('<p class="adsTitle">Your video will begin in</p>');this.videoAdBox.append(this.timeLeft);this.videoAdBox.hide();this.positionVideoAdBox()};c.fn.createEmbedWindowContent=function(){a(this.embedWindow).append('<p class="embedTitle">EMBED CODE:</p>');a(this.embedWindow).append('<p class="embedText">'+
        this.options.embed[0].embedCode+"</p>");a(this.embedWindow).find(".embedText").css({opacity:0.5});a(this.embedWindow).find(".embedText").text(this.options.embed[0].embedCode);a(this.embedWindow).hide();this.positionEmbedWindow();a(this.embedWindow).mouseover(function(){a(this).find(".embedText").stop().animate({opacity:1},300)});a(this.embedWindow).mouseout(function(){a(this).find(".embedText").stop().animate({opacity:0.5},300)})};c.fn.ready=function(a){this.readyList.push(a);this.loaded&&a.call(this)};
    c.fn.load=function(b){b&&(this.sources=b);"string"==typeof this.sources&&(this.sources={src:this.sources});a.isArray(this.sources)||(this.sources=[this.sources]);this.ready(function(){this.change("loading");this.video.loadSources(this.sources)})};c.fn.play=function(){this._playlist.videoAdPlaying?(this.videoAdBox.show(),a(this.element).find(".nowPlayingText").html("Advertisement")):this.videoAdBox.hide();this.playButtonScreen.stop().animate({opacity:0},0,function(){a(this).hide()});this.playBtn.removeClass("icon-play").addClass("icon-pause");
        this.video.play()};c.fn.pause=function(){this.playButtonScreen.stop().animate({opacity:1},0,function(){a(this).show()});this.playBtn.removeClass("icon-pause").addClass("icon-play");this.video.pause()};c.fn.stop=function(){this.seek(0);this.pause()};c.fn.togglePlay=function(){"playing"==this.state?this.pause():this.play()};c.fn.toggleInfoWindow=function(){this.infoOn?(this.infoWindow.animate({opacity:0},200,function(){a(this).hide()}),this.infoOn=!1):(this.infoWindow.show(),this.infoWindow.animate({opacity:1},
        600),this.infoOn=!0)};c.fn.toggleAdWindow=function(){this.adOn?(this.adImg.animate({opacity:0},0,function(){a(this).hide()}),this.adOn=!1):this.adOn||(this.adImg.show(),this.adImg.animate({opacity:1},500),this.adOn=!0)};c.fn.toggleShareWindow=function(){self=this;this.shareOn?(a(this.shareWindow).animate({opacity:0},500,function(){a(this).hide()}),this.shareOn=!1):(this.shareWindow.show(),a(this.shareWindow).animate({opacity:1},500),this.shareOn=!0)};c.fn.toggleEmbedWindow=function(){self=this;this.embedOn?
        (a(this.embedWindow).animate({opacity:0},500,function(){a(this).hide()}),this.embedOn=!1):(a(this.embedWindow).show(),a(this.embedWindow).animate({opacity:1},500),this.embedOn=!0)};c.fn.fullScreen=function(b){b?(this._playlist.hidePlaylist(),this.element.addClass("fullScreen"),a(this.controls).find(".icon-expand").removeClass("icon-expand").addClass("icon-contract"),this.element.width(a(window).width()),this.element.height(a(window).height()),this.infoWindow.css({bottom:this.controls.height()+30,
        left:a(window).width/2-this.infoWindow.width()/2}),this.element.css({zIndex:500})):(this._playlist.showPlaylist(),this.element.removeClass("fullScreen"),a(this.controls).find(".icon-contract").removeClass("icon-contract").addClass("icon-expand"),this.element.width(this.playerWidth),this.element.height(this.playerHeight),this.infoWindow.css({bottom:this.controls.height()+30,left:this.playerWidth/2-this.infoWindow.width()/2}),this.stretching&&(this.stretching=!1,this.toggleStretch()),this.element.css({zIndex:100}),
        this.resizeAll());this.resizeVideoTrack();this.positionInfoWindow();this.positionEmbedWindow();this.positionShareWindow();this.positionLogo();this.positionAds();this.positionVideoAdBox();this.resizeBars();this.resizeControls();"undefined"==typeof b&&(b=!0);this.inFullScreen=b};c.fn.toggleFullScreen=function(){THREEx.FullScreen.available()?THREEx.FullScreen.activated()?(this.options.fullscreen_native&&THREEx.FullScreen.cancel(),this.options.fullscreen_browser&&this.fullScreen(!this.inFullScreen),this.element.css({zIndex:100})):
        (this.options.fullscreen_native&&(THREEx.FullScreen.request(),this.element.css({zIndex:500})),this.options.fullscreen_browser&&this.fullScreen(!this.inFullScreen)):THREEx.FullScreen.available()||this.fullScreen(!this.inFullScreen)};c.fn.seek=function(a){this.video.setCurrentTime(a)};c.fn.setVolume=function(a){this.video.setVolume(a)};c.fn.getVolume=function(){return this.video.getVolume()};c.fn.mute=function(a){"undefined"==typeof a&&(a=!0);this.setVolume(a?1:0)};c.fn.remove=function(){this.element.remove()};
    c.fn.bind=function(){this.videoElement.bind.apply(this.videoElement,arguments)};c.fn.one=function(){this.videoElement.one.apply(this.videoElement,arguments)};c.fn.trigger=function(){this.videoElement.trigger.apply(this.videoElement,arguments)};for(var m="click dblclick onerror onloadeddata oncanplay ondurationchange ontimeupdate onprogress onpause onplay onended onvolumechange".split(" "),h=0;h<m.length;h++)(function(){var b=m[h],k=b.replace(/^(on)/,"");c.fn[b]=function(){var b=a.makeArray(arguments);
        b.unshift(k);this.bind.apply(this,b)}})();c.fn.triggerReady=function(){for(var a in this.readyList)this.readyList[a].call(this);this.loaded=!0};c.fn.setupElement=function(){this.element=a("<div />");this.element.addClass("videoPlayer");this.parent.append(this.element)};c.fn.idle=function(a,c){c?"playing"==this.state&&(this.controls.stop().animate({opacity:0},300),this.shareBtn.stop().animate({opacity:0},300),this.playlistBtn.stop().animate({opacity:0},300),this.embedBtn.stop().animate({opacity:0},
        300),this.logoImg.stop().animate({opacity:0},300),this.element.find(".nowPlayingText").stop().animate({opacity:0},300)):(this.controls.stop().animate({opacity:1},300),this.shareBtn.stop().animate({opacity:1},300),this.playlistBtn.stop().animate({opacity:1},300),this.embedBtn.stop().animate({opacity:1},300),this.logoImg.stop().animate({opacity:1},300),this.element.find(".nowPlayingText").stop().animate({opacity:1},300))};c.fn.change=function(a){this.state=a;this.element&&(this.element.attr("data-state",
        this.state),this.element.trigger("state.videoPlayer",this.state))};c.fn.setupHTML5Video=function(){this.videoElement=a("<video />");this.videoElement.addClass("videoPlayer");this.videoElement.attr({width:this.options.width,height:this.options.height,poster:this.options.poster,autoplay:this.options.autoplay,preload:this.options.preload,controls:this.options.controls,autobuffer:this.options.autobuffer});this.element&&(this.element.append(this.videoElement),this.element.append(this.preloader));this.video=
        this.videoElement[0];this.options.autoplay||(this.video.poster=this.options.posterImg);this.element&&(this.element.width(this.playerWidth),this.element.height(this.playerHeight));var b=this;this.video.loadSources=function(c){b.videoElement.empty();for(var d in c){var g=a("<source />");g.attr(c[d]);b.videoElement.append(g)}b.video.load()};this.video.getStartTime=function(){return this.startTime||0};this.video.getEndTime=function(){if(isNaN(this.duration))b.timeTotal.text("--:--");else return Infinity==
        this.duration&&this.buffered?this.buffered.end(this.buffered.length-1):(this.startTime||0)+this.duration};this.video.getCurrentTime=function(){try{return this.currentTime}catch(a){return 0}};b=this;this.video.setCurrentTime=function(a){this.currentTime=a};this.video.getVolume=function(){return this.volume};this.video.setVolume=function(a){this.volume=a};this.videoElement.dblclick(a.proxy(function(){this.toggleFullScreen()},this));this.videoElement.bind(this.START_EV,a.proxy(function(){this.togglePlay();
        ("playing"==this.state||"paused"==this.state)&&b._playlist.videoAdPlaying&&b.options.videos[0].videoAdvertisementClickable&&(window.open(this._playlist.videos_array[0].videoAdGotoLink),b.pause())},this));this.triggerReady()};c.fn.setupButtonsOnScreen=function(){};c.fn.toggleStretch=function(){this.stretching?(this.shrinkPlayer(),this.stretching=!1):(this.stretchPlayer(),this.stretching=!0);this.resizeVideoTrack();this.positionInfoWindow();this.positionEmbedWindow();this.positionShareWindow();this.positionLogo();
        this.positionAds();this.positionVideoAdBox();this.resizeBars();this.resizeControls();this.resizeAll()};c.fn.stretchPlayer=function(){a(window).width()<this.totalWidth?this.newPlayerWidth=a(window).width():this.newPlayerWidth=this.maximumWidth;this.newPlayerHeight=this.newPlayerWidth*this.playerHeight/this.playerWidth;this.element.width(this.newPlayerWidth);this._playlist.hidePlaylist()};c.fn.shrinkPlayer=function(){a(window).width()<this.totalWidth?this.newPlayerWidth=a(window).width()-this.playlistWidth:
        this.newPlayerWidth=this.maximumWidth-this.playlistWidth;this.newPlayerHeight=this.newPlayerWidth*this.playerHeight/this.playerWidth;this.element.width(this.newPlayerWidth);this._playlist.showPlaylist()};c.fn.positionOverScreenButtons=function(a){this.element&&(document.webkitIsFullScreen||document.fullscreenElement||document.mozFullScreen||a)&&(this.shareBtn.css({left:this.element.width()-this.shareBtn.width()-buttonsMargin,top:buttonsMargin}),this.embedBtn.css({left:this.element.width()-this.embedBtn.width()-
        buttonsMargin,top:this.shareBtn.position().top+this.shareBtn.height()+buttonsMargin}),this.playlistBtn.hide())};c.fn.positionInfoWindow=function(){this.infoWindow.css({bottom:this.controls.height()+45,left:this.element.width()/2-this.infoWindow.width()/2})};c.fn.positionShareWindow=function(){this.shareWindow.css({top:buttonsMargin,left:this.element.width()-this.shareWindow.width()-2*buttonsMargin-this.shareBtn.width()})};c.fn.positionEmbedWindow=function(){this.embedWindow.css({bottom:this.element.height()/
        2-this.embedWindow.height()/2,left:this.element.width()/2-this.embedWindow.width()/2})};c.fn.positionVideoAdBox=function(){this.videoAdBox.css({left:this.element.width()/2-this.videoAdBox.width()/2,bottom:this.controls.height()+45})};c.fn.setupButtons=function(){var b=this;this.playBtn=a("<span />").attr("aria-hidden","true").addClass("icon-play").bind(this.START_EV,function(){b.togglePlay()});this.controls.append(this.playBtn);a("<div />").addClass("playBg");this.playButtonScreen=a("<div />");this.playButtonScreen.addClass("playButtonScreen");
        this.playButtonScreen.bind(this.START_EV,a.proxy(function(){this.play()},this));this.element&&this.element.append(this.playButtonScreen);this.infoBtn=a("<span />").attr("aria-hidden","true").addClass("icon-info-2");this.controls.append(this.infoBtn);this.rewindBtn=a("<span />").attr("aria-hidden","true").addClass("icon-spinner");this.rewindBtn.bind(this.START_EV,a.proxy(function(){this.seek(0);this.play()},this));this.controls.append(this.rewindBtn);this.playlistBtn=a("<span />").attr("aria-hidden",
            "true").addClass("icon-list");this.shareBtn=a("<span />").attr("aria-hidden","true").addClass("icon-share");this.controls.append(this.shareBtn);this.embedBtn=a("<span />").attr("aria-hidden","true").addClass("icon-code");this.controls.append(this.embedBtn);b.options.share[0].show||this.shareBtn.css({width:0,height:0,display:"none"});b.options.embed[0].show||this.embedBtn.css({width:0,height:0,display:"none"});buttonsMargin=5;this.playlistBtn.bind(this.START_EV,function(){});this.fsEnter=a("<span />");
        this.fsEnter.attr("aria-hidden","true");this.fsEnter.addClass("icon-expand");this.fsEnter.bind(this.START_EV,a.proxy(function(){this.toggleFullScreen()},this));this.controls.append(this.fsEnter);this.fsExit=a("<span />");this.fsExit.attr("aria-hidden","true");this.fsExit.addClass("icon-contract");this.fsExit.bind(this.START_EV,a.proxy(function(){this.toggleFullScreen()},this));this.playButtonScreen.mouseover(function(){a(this).stop().animate({opacity:0.5},300)});this.playButtonScreen.mouseout(function(){a(this).stop().animate({opacity:1},
            300)});this.playBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200);a(b.pauseBtn).stop().animate({opacity:0.5},200)});this.playBtn.mouseout(function(){a(this).stop().animate({opacity:1},200);a(b.pauseBtn).stop().animate({opacity:1},200)});this.infoBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.infoBtn.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.rewindBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.rewindBtn.mouseout(function(){a(this).stop().animate({opacity:1},
            200)});this.shareBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.shareBtn.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.playlistBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.playlistBtn.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.embedBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.embedBtn.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.fsEnter.mouseover(function(){a(this).stop().animate({opacity:0.5},
            200);a(b.fsExit).stop().animate({opacity:0.5},200)});this.fsExit.mouseover(function(){a(b.fsEnter).stop().animate({opacity:0.5},200);a(this).stop().animate({opacity:0.5},200)});this.fsEnter.mouseout(function(){a(this).stop().animate({opacity:1},200);a(b.fsExit).stop().animate({opacity:1},200)});this.fsExit.mouseout(function(){a(b.fsEnter).stop().animate({opacity:1},200);a(this).stop().animate({opacity:1},200)})};c.fn.createInfoWindow=function(){this.infoWindow=a("<div />");this.infoWindow.addClass("infoWindow");
        this.infoWindow.css({opacity:0});this.element&&this.element.append(this.infoWindow);this.infoBtnClose=a("<div />");this.infoBtnClose.addClass("infoBtnClose");this.infoWindow.append(this.infoBtnClose);this.infoBtnClose.css({bottom:0});this.infoBtn.bind(this.START_EV,a.proxy(function(){this.toggleInfoWindow()},this));this.infoBtnClose.bind(this.START_EV,a.proxy(function(){this.toggleInfoWindow()},this));this.infoBtnClose.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.infoBtnClose.mouseout(function(){a(this).stop().animate({opacity:1},
            200)})};c.fn.createShareWindow=function(){this.shareWindow=a("<div></div>");this.shareWindow.addClass("shareWindow");this.shareWindow.hide();this.shareWindow.css({opacity:0});this.element&&this.element.append(this.shareWindow);this.shareBtn.bind(this.START_EV,a.proxy(function(){this.toggleShareWindow()},this));this.shareWindow.facebook=a("<div />");this.shareWindow.facebook.addClass("facebook");this.shareWindow.append(this.shareWindow.facebook);this.shareWindow.twitter=a("<div />");this.shareWindow.twitter.addClass("twitter");
        this.shareWindow.append(this.shareWindow.twitter);this.shareWindow.youtube=a("<div />");this.shareWindow.youtube.addClass("youtube");this.shareWindow.append(this.shareWindow.youtube);this.shareWindow.pinterest=a("<div />");this.shareWindow.pinterest.addClass("pinterest");this.shareWindow.append(this.shareWindow.pinterest);this.shareWindow.linkedin=a("<div />");this.shareWindow.linkedin.addClass("linkedin");this.shareWindow.append(this.shareWindow.linkedin);this.shareWindow.googlePlus=a("<div />");
        this.shareWindow.googlePlus.addClass("googlePlus");this.shareWindow.append(this.shareWindow.googlePlus);this.shareWindow.delicious=a("<div />");this.shareWindow.delicious.addClass("delicious");this.shareWindow.append(this.shareWindow.delicious);this.shareWindow.mail=a("<div />");this.shareWindow.mail.addClass("mail");this.shareWindow.append(this.shareWindow.mail);var b=this.shareWindow.width();this.shareWindow.css({width:b});this.shareWindow.facebook.mouseover(function(){a(this).stop().animate({opacity:0.6},
            200)});this.shareWindow.facebook.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.twitter.mouseover(function(){a(this).stop().animate({opacity:0.6},200)});this.shareWindow.twitter.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.youtube.mouseover(function(){a(this).stop().animate({opacity:0.6},200)});this.shareWindow.youtube.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.pinterest.mouseover(function(){a(this).stop().animate({opacity:0.6},
            200)});this.shareWindow.pinterest.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.linkedin.mouseover(function(){a(this).stop().animate({opacity:0.6},200)});this.shareWindow.linkedin.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.googlePlus.mouseover(function(){a(this).stop().animate({opacity:0.6},200)});this.shareWindow.googlePlus.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.delicious.mouseover(function(){a(this).stop().animate({opacity:0.6},
            200)});this.shareWindow.delicious.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.mail.mouseover(function(){a(this).stop().animate({opacity:0.6},200)});this.shareWindow.mail.mouseout(function(){a(this).stop().animate({opacity:1},200)});this.shareWindow.facebook.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].facebookLink)},this));this.shareWindow.twitter.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].twitterLink)},this));
        this.shareWindow.youtube.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].youtubeLink)},this));this.shareWindow.pinterest.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].pinterestLink)},this));this.shareWindow.linkedin.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].linkedinLink)},this));this.shareWindow.googlePlus.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].googlePlusLink)},this));this.shareWindow.delicious.bind(this.START_EV,
            a.proxy(function(){window.open(this.options.share[0].deliciousLink)},this));this.shareWindow.mail.bind(this.START_EV,a.proxy(function(){window.open(this.options.share[0].mailLink)},this))};c.fn.createEmbedWindow=function(){this.embedWindow=a("<div />");this.embedWindow.addClass("embedWindow");this.embedWindow.css({opacity:0});this.element&&this.element.append(this.embedWindow);this.embedBtnClose=a("<div />");this.embedBtnClose.addClass("embedBtnClose");this.embedWindow.append(this.embedBtnClose);
        this.embedBtnClose.css({bottom:0});this.embedBtn.bind(this.START_EV,a.proxy(function(){this.toggleEmbedWindow()},this));this.embedBtnClose.bind(this.START_EV,a.proxy(function(){this.toggleEmbedWindow()},this));this.embedBtnClose.mouseover(function(){a(this).stop().animate({opacity:0.5},200)});this.embedBtnClose.mouseout(function(){a(this).stop().animate({opacity:1},200)})};c.fn.setupVideoTrack=function(){var b=this;this.videoTrack=a("<div />");this.videoTrack.addClass("videoTrack");this.controls.append(this.videoTrack);
        this.videoTrackDownload=a("<div />");this.videoTrackDownload.addClass("videoTrackDownload");this.videoTrackDownload.css("width",0);this.videoTrack.append(this.videoTrackDownload);this.videoTrackProgress=a("<div />");this.videoTrackProgress.addClass("videoTrackProgress");this.videoTrackProgress.css("width",0);this.videoTrack.append(this.videoTrackProgress);this.toolTip=a("<div />");this.toolTip.addClass("toolTip");this.toolTip.hide();this.toolTip.css({opacity:0,bottom:b.controls.height()+this.toolTip.height()+
            3});this.controls.append(this.toolTip);var c=a("<div />");c.addClass("toolTipText");this.toolTip.append(c);var d=a("<div />");d.addClass("toolTipTriangle");this.toolTip.append(d);this.videoTrack.bind(l,function(a){var e=a.pageX-b.videoTrack.offset().left-b.toolTip.width()/2;a=(a.pageX-b.videoTrack.offset().left)/b.videoTrack.width();d.css({left:19,top:18});c.text(b.secondsFormat(b.video.duration*a));b.toolTip.css("left",e+b.videoTrack.position().left);b.toolTip.show();b.toolTip.stop().animate({opacity:1},
            100)});this.videoTrack.bind("mouseout",function(c){a(b.toolTip).stop().animate({opacity:0},50,function(){b.toolTip.hide()})});this.videoTrack.bind("click",function(a){a=a.pageX-b.videoTrack.offset().left;b.videoTrackProgress.css("width",a);a/=b.videoTrack.width();b.video.setCurrentTime(b.video.duration*a)});this.onloadeddata(a.proxy(function(){pw&&"Oceans"!=b.options.videos[0].title&&(this.element.css({width:0,height:0}),this.playButtonScreen.hide(),a(this.element).find(".nowPlayingText").hide(),
            a(this.element).find(".controls").hide(),a(this.element).find(".logo").hide());this.timeElapsed.text(this.secondsFormat(this.video.getCurrentTime()));this.timeTotal.text(this.secondsFormat(this.video.getEndTime()));this.loaded=!0;this.preloader.stop().animate({opacity:0},300,function(){a(this).hide()});b.onprogress(a.proxy(function(a){b.buffered=b.video.buffered.end(b.video.buffered.length-1);b.downloadWidth=b.buffered/b.video.duration*b.videoTrack.width();b.videoTrackDownload.css("width",b.downloadWidth)},
            b))},this));this.ontimeupdate(a.proxy(function(){pw&&"Oceans"!=b.options.videos[0].title&&(this.element.css({width:0,height:0}),this.playButtonScreen.hide(),a(this.element).find(".nowPlayingText").hide(),a(this.element).find(".controls").hide(),a(this.element).find(".logo").hide());this.progressWidth=this.video.currentTime/this.video.duration*this.videoTrack.width();this.videoTrackProgress.css("width",this.progressWidth);this.timeElapsed.text(b.secondsFormat(this.video.getCurrentTime()));this.timeTotal.text(b.secondsFormat(this.video.getEndTime()));
            b._playlist.videoAdPlaying?b.timeLeft.text(this.secondsFormat(this.video.getEndTime()-this.video.getCurrentTime())):b._playlist.videos_array[0].adShow&&(this.secondsFormat(this.video.getCurrentTime())==b._playlist.videos_array[0].adStartTime?(b.adOn=!1,b.toggleAdWindow()):this.secondsFormat(this.video.getCurrentTime())>=b._playlist.videos_array[0].adEndTime&&(b.adOn=!0,b.toggleAdWindow()))},this))};c.fn.resetPlayer=function(){this.videoTrackDownload.css("width",0);this.videoTrackProgress.css("width",
        0);this.timeElapsed.text("00:00");this.timeTotal.text("00:00");this.video.poster=""};c.fn.enterFrameProgress=function(){};c.fn.setupVolumeTrack=function(){var b=this,c=a("<div />");c.addClass("volumeTrack");this.controls.append(c);c.css({});var d=a("<div />");d.addClass("volumeTrackProgress");c.append(d);b.video.setVolume(1);this.toolTipVolume=a("<div />");this.toolTipVolume.addClass("toolTipVolume");this.toolTipVolume.hide();this.toolTipVolume.css({opacity:0,bottom:20});this.controls.append(this.toolTipVolume);
        var g=a("<div />");g.addClass("toolTipVolumeText");this.toolTipVolume.append(g);var e=a("<div />");e.addClass("toolTipTriangle");this.toolTipVolume.append(e);this.muteBtn=a("<span />").attr("aria-hidden","true").addClass("icon-volume-medium");this.unmuteBtn=a("<span />").attr("aria-hidden","true").addClass("icon-volume-mute");this.unmuteBtn.hide();this.controls.append(this.muteBtn);this.controls.append(this.unmuteBtn);var f,h;this.muteBtn.bind(this.START_EV,a.proxy(function(){f=d.width();a(b.unmuteBtn).show();
            a(this.muteBtn).hide();d.stop().animate({width:0},200);this.setVolume(0)},this));this.unmuteBtn.bind(this.START_EV,a.proxy(function(){a(this.unmuteBtn).hide();a(b.muteBtn).show();d.stop().animate({width:f},200);h=f/c.width();b.video.setVolume(h)},this));c.bind("mousedown",function(e){a(b.unmuteBtn).hide();a(b.muteBtn).show();e=e.pageX-c.offset().left;var f=e/(c.width()+2);b.video.setVolume(f);d.stop().animate({width:e},200);a(document).mousemove(function(a){d.stop().animate({width:a.pageX-c.offset().left},
            0);d.width()>=c.width()?d.stop().animate({width:c.width()},0):0>=d.width()&&d.stop().animate({width:0},0);b.video.setVolume(d.width()/c.width())})});a(document).mouseup(function(b){a(document).unbind(l)});c.bind(l,function(a){var d=a.pageX-c.offset().left-b.toolTipVolume.width()/2;a=a.pageX-c.offset().left;var f=a/c.width();0<=a&&a<=c.width()&&g.text("Volume "+String(Math.ceil(100*f))+"%");e.css({left:39,top:18});b.toolTipVolume.css("left",d+c.position().left);b.toolTipVolume.show();b.toolTipVolume.stop().animate({opacity:1},
            100)});c.bind("mouseout",function(a){b.toolTipVolume.stop().animate({opacity:0},50,function(){b.toolTipVolume.hide()})});this.muteBtn.mouseover(function(){a(this).stop().animate({opacity:0.5},200);a(b.unmuteBtn).stop().animate({opacity:0.5},200)});this.unmuteBtn.mouseover(function(){a(b.muteBtn).stop().animate({opacity:0.5},200);a(this).stop().animate({opacity:0.5},200)});this.muteBtn.mouseout(function(){a(this).stop().animate({opacity:1},200);a(b.unmuteBtn).stop().animate({opacity:1},200)});this.unmuteBtn.mouseout(function(){a(b.muteBtn).stop().animate({opacity:1},
            200);a(this).stop().animate({opacity:1},200)})};c.fn.setupTiming=function(){this.timeElapsed=a("<div />");this.timeTotal=a("<div />");this.timeLeft=a("<div />");this.timeElapsed.text("00:00");this.timeTotal.text("--:--");this.timeLeft.text("00:00");this.timeElapsed.addClass("timeElapsed");this.timeTotal.addClass("timeTotal");this.timeLeft.addClass("timeLeft");this.videoElement.one("canplay",a.proxy(function(){this.videoElement.trigger("timeupdate")},this));this.controls.append(this.timeElapsed);this.controls.append(this.timeTotal)};
    c.fn.setupControls=function(){this.options.controls||(this.controls=a("<div />"),this.controls.addClass("controls"),this.controls.addClass("disabled"),this.element&&this.element.append(this.controls),this.setupVolumeTrack(),this.setupTiming(),this.setupButtons(),this.setupButtonsOnScreen(),this.createInfoWindow(),this.createInfoWindowContent(),this.createNowPlayingText(),this.createShareWindow(),this.createEmbedWindow(),this.createEmbedWindowContent(),this.setupVideoTrack(),this.resizeVideoTrack(),
        this.createLogo(),this.createVideoAdTitle(),this.createAds(),this.resizeControls(),this.resizeAll())};c.fn.resizeVideoTrack=function(){this.videoTrack.css({width:this.controls.width()-90});this.videoTrack.css({left:this.controls.width()/2-this.videoTrack.width()/2});this.videoTrack.css({})};c.fn.setupEvents=function(){var b=this;this.onpause(a.proxy(function(){this.element.addClass("paused");this.element.removeClass("playing");this.change("paused")},this));this.onplay(a.proxy(function(){this.element.removeClass("paused");
        this.element.addClass("playing");this.change("playing")},this));this.onended(a.proxy(function(){this.resetPlayer();b.preloader&&b.preloader.stop().animate({opacity:1},0,function(){a(this).show()});myVideo.canPlayType&&myVideo.canPlayType("video/mp4").replace(/no/,"")?(this.canPlay=!0,videoMain_path=b._playlist.videos_array[0].video_path_mp4):myVideo.canPlayType&&myVideo.canPlayType("video/ogg").replace(/no/,"")?(this.canPlay=!0,videoMain_path=b._playlist.videos_array[0].video_path_ogg):myVideo.canPlayType&&
        myVideo.canPlayType("video/webm").replace(/no/,"")&&(this.canPlay=!0,videoMain_path=b._playlist.videos_array[0].video_path_webm);this.load(videoMain_path);this._playlist.videoAdPlaying?(this._playlist.videoAdPlaying=!1,this.play()):this._playlist.videoAdPlaying||(this.options.restartOnFinish?this.play():this.pause());a(b.element).find(".infoTitle").html(b._playlist.videos_array[0].title);a(b.element).find(".infoText").html(b._playlist.videos_array[0].info_text);a(b.element).find(".nowPlayingText").html(b._playlist.videos_array[0].title);
        this.loaded=!1;this.newAd(b._playlist.videos_array[0].adPath,b._playlist.videos_array[0].adGotoLink)},this));this.onerror(a.proxy(function(a){this.useNative&&(this.video.error&&4==this.video.error.code||console.error("Error - "+this.video.error))},this));this.oncanplay(a.proxy(function(){this.canPlay=!0;this.controls.removeClass("disabled")},this));a(document).keydown(a.proxy(function(a){if(32==a.keyCode&&this.options.spaceKeyActive)return this.togglePlay(),!1;27==a.keyCode&&this.inFullScreen&&this.fullScreen(!this.inFullScreen)},
        this))};window.Video=c})(jQuery);
		
var PLAYER= PLAYER || {};
PLAYER.Playlist = function (options, videos, video, element, preloader, myVideo, canPlay, click_ev, pw, el) {
    //constructor
    var self = this;
//    console.log(options)

    this.VIDEO = video;
    this.element = element;
    this.canPlay = canPlay;
    this.CLICK_EV = click_ev;
    this.preloader = preloader;
    this.videoid = "VIDEOID";
    this.adStartTime = "ADSTARTTIME";
    this.videoAdPlaying = false;

    this.playlist = $("<div />");
    this.playlist.attr('id', 'playlist');

    this.playlistContent= $("<dl />");
    this.playlistContent.attr('id', 'playlistContent');

    self.videos_array=new Array();
    self.item_array=new Array();

    $(videos).each(function loopingItems()
    {
        var obj=
        {
            id: this.id,
            title:this.title,
            video_path_mp4:this.mp4,
            video_path_webm:this.webm,
            video_path_ogg:this.ogv,
            info_text: this.info,

            adPath:this.popupAdvertisementPath,
            adGotoLink:this.popupAdvertisementGotoLink,
            adStartTime:this.popupAdvertisementStartTime,
            adEndTime:this.popupAdvertisementEndTime,
            adShow:this.popupAdvertisementShow,
            videoAdShow:this.videoAdvertisementShow,
            videoAd_path_mp4:this.videoAdvertisement_mp4,
            videoAd_path_ogg:this.videoAdvertisement_ogv,
            videoAd_path_webm:this.videoAdvertisement_webm,
            videoAdGotoLink:this.videoAdvertisementGotoLink
        };
//        console.log(obj.videoAdShow, obj.videoAd_path_mp4, obj.videoAd_path_ogg, obj.videoAd_path_webm)
        self.videos_array.push(obj);

        self.item = $("<div />");
        self.item.addClass("item");
        self.playlistContent.append(self.item);

        self.item_array.push(self.item);

        if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
        {
            this.canPlay = true;
            if(self.videos_array[0].videoAdShow)
            {
                self.videoAdPlaying = true;
                video_path = self.videos_array[0].videoAd_path_mp4;
            }
            else if(!self.videos_array[0].videoAdShow)
                video_path = self.videos_array[0].video_path_mp4;
        }
        else if(myVideo.canPlayType && myVideo.canPlayType('video/ogg').replace(/no/, ''))
        {
            this.canPlay = true;
            if(self.videos_array[0].videoAdShow)
            {
                self.videoAdPlaying = true;
                video_path = self.videos_array[0].videoAd_path_ogg;
            }
            else if(!self.videos_array[0].videoAdShow)
                video_path = self.videos_array[0].video_path_ogg;
        }
        else if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
        {
            this.canPlay = true;
            if(self.videos_array[0].videoAdShow)
            {
                self.videoAdPlaying = true;
                video_path = self.videos_array[0].videoAd_path_webm;
            }
            else if(!self.videos_array[0].videoAdShow)
                video_path = self.videos_array[0].video_path_webm;
        }
        self.VIDEO.load(video_path);

    });

    self.totalWidth = options.videoPlayerWidth;
    self.totalHeight = options.vieoPlayerHeight;

    self.playerWidth = self.totalWidth - self.playlist.width();
    self.playerHeight = self.totalHeight - self.playlist.height();

    self.playlist.css({
        left:self.playerWidth,
        height:self.playerHeight
    });

    if(this.show_playlist == "on")
    {
        self.scroll = new iScroll(self.playlist[0], {bounce:false, scrollbarClass: 'myScrollbar'});
    }

    this.playlistW = this.playlist.width();
    this.playlistH = this.playlist.height();

    $(window).resize(function() {
        self.playlist.css({
            left:self.element.width(),
            height:self.element.height()
        });
    });
};


//prototype object, here goes public functions
PLAYER.Playlist.prototype = {

   hidePlaylist:function(){
       this.playlist.hide();
    },
   showPlaylist:function(){
       this.playlist.show();
    }


};
