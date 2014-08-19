

function browser() {
    var b = !! (window.opera && window.opera.version);
    var e = a("MozBoxSizing");
    var c = Object.prototype.toString.call(window.HTMLElement).indexOf("Constructor") > 0;
    var d = !c && a("WebkitTransform");

    function a(f) {
        return f in document.documentElement.style
    }
    if (b) {
        return false
    } else {
        if (c || d) {
            return true
        } else {
            return false
        }
    }
}
jQuery(document).ready(function (a) {
    if (jQuery.browser.version.substring(0, 2) == "8.") {
        a(".hideInIE8").remove()
    }
    a('a[href="#"][data-top!=true]').click(function (b) {
        b.preventDefault()
    });
    a(".noty").click(function (c) {
        c.preventDefault();
        var b = a.parseJSON(a(this).attr("data-noty-options"));
        noty(b)
    });
    a("#myTab a:first").tab("show");
    a("#myTab a").click(function (b) {
        b.preventDefault();
        a(this).tab("show")
    });
    a('[rel="tooltip"],[data-rel="tooltip"]').tooltip({
        placement: "top",
        delay: {
            show: 400,
            hide: 200
        }
    });
    a('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();
    a("#toggle-fullscreen").button().click(function () {
        var c = a(this),
            b = document.documentElement;
        if (!c.hasClass("active")) {
            a("#thumbnails").addClass("modal-fullscreen");
            if (b.webkitRequestFullScreen) {
                b.webkitRequestFullScreen(window.Element.ALLOW_KEYBOARD_INPUT)
            } else {
                if (b.mozRequestFullScreen) {
                    b.mozRequestFullScreen()
                }
            }
        } else {
            a("#thumbnails").removeClass("modal-fullscreen");
            (document.webkitCancelFullScreen || document.mozCancelFullScreen || a.noop).apply(document)
        }
    });
    a(".btn-close").click(function (b) {
        b.preventDefault();
        a(this).parent().parent().parent().fadeOut()
    });
    a(".btn-minimize").click(function (c) {
        c.preventDefault();
        var b = a(this).parent().parent().next(".box-content");
        if (b.is(":visible")) {
            a("i", a(this)).removeClass("fa fa-chevron-up").addClass("fa fa-chevron-down")
        } else {
            a("i", a(this)).removeClass("fa fa-chevron-down").addClass("fa fa-chevron-up")
        }
        b.slideToggle("slow", function () {
            widthFunctions()
        })
    });
    a(".btn-setting").click(function (b) {
        b.preventDefault();
        a("#myModal").modal("show")
    })
});
jQuery(document).ready(function (a) {
    a(".discussions").find(".delete").click(function () {
        a(this).parent().fadeTo("slow", 0, function () {
            a(this).slideUp("slow", function () {
                a(this).remove()
            })
        })
    })
});
jQuery(document).ready(function (a) {
    if (a(".messagesList").width()) {
        if (jQuery.browser.version.substring(0, 2) == "8.") {
            a("ul.messagesList li:nth-child(2n+1)").addClass("odd")
        }
    }
});

function retina() {
    retinaMode = (window.devicePixelRatio > 1);
    return retinaMode
}
jQuery(document).ready(function (a) {
    a("ul.main-menu li a").each(function () {
        if (a(a(this))[0].href == String(window.location)) {
            a(this).parent().addClass("active")
        }
    });
    a("ul.main-menu li ul li a").each(function () {
        if (a(a(this))[0].href == String(window.location)) {
            a(this).parent().addClass("active");
            a(this).parent().parent().show()
        }
    });
    a(".dropmenu").click(function (b) {
        b.preventDefault();
        a(this).parent().find("ul").slideToggle()
    })
});
jQuery(document).ready(function (b) {
    var a = true;
    b("#main-menu-toggle").click(function () {
        if (b(this).hasClass("open")) {
            b(this).removeClass("open").addClass("close");
            var f = b("#content").attr("class");
            var e = parseInt(f.replace(/^\D+/g, ""));
            var c = e + 2;
            var d = "span" + c;
            b("#content").addClass("full");
            b(".navbar-brand").addClass("noBg");
            b("#sidebar-left").hide()
        } else {
            b(this).removeClass("close").addClass("open");
            var f = b("#content").attr("class");
            var e = parseInt(f.replace(/^\D+/g, ""));
            var c = e - 2;
            var d = "span" + c;
            b("#content").removeClass("full");
            b(".navbar-brand").removeClass("noBg");
            b("#sidebar-left").show()
        }
    })
});
jQuery(document).ready(function (a) {
    if (a(".boxchart").length) {
        if (retina()) {
            a(".boxchart").sparkline("html", {
                type: "bar",
                height: "60",
                barWidth: "8",
                barSpacing: "2",
                barColor: "#ffffff",
                negBarColor: "#eeeeee"
            });
            if (jQuery.browser.mozilla) {
                a(".boxchart").css("MozTransform", "scale(0.5,0.5)").css("height", "30px;");
                a(".boxchart").css("height", "30px;").css("margin", "-15px 15px -15px -15px")
            } else {
                a(".boxchart").css("zoom", 0.5)
            }
        } else {
            a(".boxchart").sparkline("html", {
                type: "bar",
                height: "30",
                barWidth: "4",
                barSpacing: "1",
                barColor: "#ffffff",
                negBarColor: "#eeeeee"
            })
        }
    }
    if (a(".chart-stat").length) {
        if (retina()) {
            a(".chart-stat > .chart").each(function () {
                var b = a(this).css("color");
                a(this).sparkline("html", {
                    width: "180%",
                    height: 80,
                    lineColor: b,
                    fillColor: false,
                    spotColor: true,
                    maxSpotColor: false,
                    minSpotColor: false,
                    spotRadius: 8,
                    lineWidth: 4
                });
                if (jQuery.browser.mozilla) {
                    a(this).css("MozTransform", "scale(0.5,0.5)");
                    a(this).css("height", "40px;").css("margin", "-20px 0px -20px -25%")
                } else {
                    a(this).css("zoom", 0.6)
                }
            })
        } else {
            a(".chart-stat > .chart").each(function () {
                var b = a(this).css("color");
                a(this).sparkline("html", {
                    width: "90%",
                    height: 40,
                    lineColor: b,
                    fillColor: false,
                    spotColor: false,
                    maxSpotColor: false,
                    minSpotColor: false,
                    spotRadius: 2,
                    lineWidth: 2
                })
            })
        }
    }
});

function hexToRgb(b) {
    var a = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(b);
    return a ? {
        r: parseInt(a[1], 16),
        g: parseInt(a[2], 16),
        b: parseInt(a[3], 16)
    } : null
}

function rgbToRgba(a, b) {
    if (jQuery.browser.version <= 8) {
        a = hexToRgb(a);
        rgba = "rgba(" + a.r + "," + a.g + "," + a.b + "," + b + ")"
    } else {
        a = a.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        rgba = "rgba(" + a[1] + "," + a[2] + "," + a[3] + "," + b + ")"
    }
    return rgba
}
$(document).ready(function () {
    widthFunctions()
});
$(window).bind("resize", widthFunctions);

function widthFunctions(f) {
    var g = $("#sidebar-left").outerHeight();
    var d = $("#content").height();
    var c = $("#content").outerHeight();
    var b = $(window).height();
    var a = $(window).width();
    if (a > 767) {
        if (g > d) {
            $("#content").css("min-height", g)
        } else {
            $("#content").css("min-height", "auto")
        }
        $("#white-area").css("height", c)
    } else {
        $("#white-area").css("height", "auto")
    }
};