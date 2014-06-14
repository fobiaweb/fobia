(function(window) {
    var AC = {
        addLoadEvent: function(func) {
            var oldonload = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = func;
            } else {
                window.onload = function() {
                    if (oldonload) {
                        oldonload();
                    }
                    func();
                }
            }
        },
        loadScript: function(data, callback) {
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
        },
        initLog: function() {
            $(document).ready(function() {
                $(window).keyup(function(e) {
                    if (e.keyCode == 120) {
                        $("#ac-logger").slideToggle(1, function() {
                            AC.ac_logger_session();
                        });
                    }
                });
                $("#ac-logger-switch").click(function() {
                    $("#ac-logger").slideToggle(1, function() {
                        AC.ac_logger_session();
                    });
                });
                $("#ac-logger td.level:contains(error)").parent('tr').addClass('error');
                $("#ac-logger td.level:contains(warning)").parent('tr').addClass('warning');
                $("#ac-logger td.level:contains(dump)").parent('tr').addClass('dump');
                if ($.cookie != undefined) {
                    $("#ac-logger").css("display", $.cookie('ac-logger'));
                }
            });
        },
        ac_logger_session: function() {
            if ($.cookie == undefined) {
                return;
            }
            var display = $("#ac-logger").css("display");
            $.cookie('ac-logger', display);
        }
    };
    if (window.jQuery == undefined) {
        var jf = "http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js";
        AC.loadScript(jf, function() {
            AC.initLog();
        });
    } else {
        AC.initLog();
    }
})(window);