var wpCookies = { each: function(d, a, c) { var e, b; if (!d) { return 0 }
        c = c || d; if (typeof(d.length) != "undefined") { for (e = 0, b = d.length; e < b; e++) { if (a.call(c, d[e], e, d) === false) { return 0 } } } else { for (e in d) { if (d.hasOwnProperty(e)) { if (a.call(c, d[e], e, d) === false) { return 0 } } } } return 1 }, getHash: function(c) { var a = this.get(c),
            b; if (a) { this.each(a.split("&"), function(d) { d = d.split("=");
                b = b || {};
                b[d[0]] = d[1] }) } return b }, setHash: function(i, a, f, c, h, b) { var g = "";
        this.each(a, function(e, d) { g += (!g ? "" : "&") + d + "=" + e });
        this.set(i, g, f, c, h, b) }, get: function(h) { var g = document.cookie,
            f, d = h + "=",
            a; if (!g) { return }
        a = g.indexOf("; " + d); if (a == -1) { a = g.indexOf(d); if (a != 0) { return null } } else { a += 2 }
        f = g.indexOf(";", a); if (f == -1) { f = g.length } return decodeURIComponent(g.substring(a + d.length, f)) }, set: function(h, a, f, c, g, b) { document.cookie = h + "=" + encodeURIComponent(a) + ((f) ? "; expires=" + f.toGMTString() : "") + ((c) ? "; path=" + c : "") + ((g) ? "; domain=" + g : "") + ((b) ? "; secure" : "") }, remove: function(c, a) { var b = new Date();
        b.setTime(b.getTime() - 1000);
        this.set(c, "", b, a, b) } };

function getUserSetting(a, b) { var c = getAllUserSettings(); if (c.hasOwnProperty(a)) { return c[a] } if (typeof b != "undefined") { return b } return "" }

function setUserSetting(a, i, k) { if ("object" !== typeof userSettings) { return false } var h = "wp-settings-" + userSettings.uid,
        e = wpCookies.getHash(h) || {},
        g = new Date(),
        b, f = a.toString().replace(/[^A-Za-z0-9_]/, ""),
        j = i.toString().replace(/[^A-Za-z0-9_]/, ""); if (k) { delete e[f] } else { e[f] = j }
    g.setTime(g.getTime() + 31536000000);
    b = userSettings.url;
    wpCookies.setHash(h, e, g, b);
    wpCookies.set("wp-settings-time-" + userSettings.uid, userSettings.time, g, b); return a }

function deleteUserSetting(a) { return setUserSetting(a, "", 1) }

function getAllUserSettings() { if ("object" !== typeof userSettings) { return {} } return wpCookies.getHash("wp-settings-" + userSettings.uid) || {} };

jQuery(document).ready(function($) {
    $('.incsub_wiki_revisions').find('.action-links').find('a').on("click", function(e) {
        if (!confirm(Wiki.restoreMessage)) {
            e.preventDefault();
        }
    });

    $('.incsub_wiki_message').find('a.dismiss').on("click", function(e) {
        e.preventDefault();
        var $parent = $(this).parent();
        $parent.fadeOut(500, function() {
            $parent.remove();
        })
    });
});