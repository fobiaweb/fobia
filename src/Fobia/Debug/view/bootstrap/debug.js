+(function(window) {
    var jf = "http://yandex.st/jquery/2.1.0/jquery.min.js";

    function initLog() {
        var $ = window.jQuery;
        $(document).ready(function() {
            $(window).keyup(function(e) {
                if (e.keyCode == 120 || e.keyCode == 113) { // key: F9
                    console.log('key:', e.keyCode);
                    $("#ac-logger").slideToggle(1, function() {
                        logger_session();
                    });
                }
            });
            $("#ac-logger-switch").click(function() {
                $("#ac-logger").slideToggle(1, function() {
                    logger_session();
                });
            });
            $("#ac-logger td.level:contains(error)").parent('tr').addClass('error');
            $("#ac-logger td.level:contains(warning)").parent('tr').addClass('warning');
            $("#ac-logger td.level:contains(info)").parent('tr').addClass('info');
            $("#ac-logger td.level:contains(notice)").parent('tr').addClass('notice');
            $("#ac-logger td.level:contains(debug)").parent('tr').addClass('debug');
            // $("#ac-logger td.level:contains(dump)").parent('tr').addClass('dump');
            $("#ac-logger td.message").each(function() {
                var m = $(this).html().replace(/^\[([^\]]+)]::/, "[<span class=\"text-danger\">$1</span>]::");
                $(this).html(m);
            });
            if (typeof $.cookie != 'undefined') {
                $("#ac-logger").css("display", $.cookie('ac-logger'));
            }
        });
    }

    function loadScript(data, callback) {
        var head = window.document.getElementsByTagName('head')[0];
        if (typeof data !== 'object') {
            data = {
                element: 'script',
                attr: {
                    src: data,
                    type: 'text/javascript'
                }
            };
        }
        var script = window.document.createElement(data.element);
        for (var i in data.attr) {
            script[i] = data.attr[i];
        }
        var userAgent = window.navigator.userAgent.toLowerCase();
        if (/msie/.test(userAgent) && !/opera/.test(userAgent)) {
            script.onreadystatechange = function() {
                if (script.readyState == 'complete' && callback != undefined) {
                    window.console.log("onload: ", data.attr);
                    callback();
                }
            };
        } else {
            script.onload = function() {
                if (callback != undefined) {
                    console.log("onload: ", data.attr);
                    callback();
                }
            };
        }
        head.appendChild(script);
    }

    function logger_session() {
        var $ = window.jQuery;
        if (typeof $.cookie == 'undefined') {
            return;
        }
        var display = $("#ac-logger").css("display");
        $.cookie('ac-logger', display);
    }
    if (typeof window.jQuery == 'undefined') {
        loadScript(jf, function() {
            initLog();
        });
    } else {
        initLog();
    }
})(window);