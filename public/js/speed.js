/**
 * TODO : links to current page just redirect you to top of page (behaviour like href="#")
 * TODO : way to make async calls to links only on screen/window and not beyond
 *  TODO : also add a way to get newer links (and make calls to) to ones appearing after scrolling
 * TODO : do not load full dom tree from HTML, but only content after header & before footer
 * TODO : add a way to initialize the js/jQuery in the new dom (if not yet initialized)
 * TODO : better way of detecting links (currently it can brake if link to page doesn't have '/' at the beginning)
 * TODO : maybe refactor at some point
 *
 * Order of work,
 * 1) Get into page and load each of the links (currently made to find every one of them and load them asynchronously).
 * 2) Go to another page find links not loaded currently & load them as well.
 * 3) At some point have every page cached fo an easy experience.
 *  - Add TTL on cache.
 *  - Maybe add a way to not cache specific pages.
 */

var speed = {


    init : function () {
        speed.getPageLinks();
    },

    asyncCall : function (link) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.onload = function () {
                resolve(speed.responseText);
            };
            xhr.onerror = reject;
            xhr.open('GET', link, true);
            xhr.setRequestHeader('Async', 'true');
            xhr.send();
        });
    },

    getPageLinks : function () {
        let a = document.getElementsByTagName('a');
        let links = [];
        for (let link of a) {
            if (link.attributes.href) {
                let href = link.attributes.href.nodeValue;
                if (href.charAt(0) == '/' && href.search('logout') == -1) { // just so it doesn't trigger logout page load
                    if (href != window.location.pathname) {
                        links.push(href);
                        speed.asyncCall(href)
                            .then(function (result) {
                                speed.addDataToLink(link, result);
                            })
                            .catch(function () {
                                // An error occurred
                            });
                    } else {
                        // cache current page and on click load it

                        // speed.cacheCurrentPage(link);
                    }
                }
            }
        }
        return links;
    },

    addDataToLink : function (link, data) {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            var html = document.querySelector('#wrapper');
            var newHtml = document.createElement('div');
            newHtml.setAttribute("id", "wrapper");
            newHtml.innerHTML = data;
            html.parentNode.replaceChild(newHtml, html);
            window.history.pushState({}, '', link.attributes.href.nodeValue);
        });
    },

    /*cacheCurrentPage : function (link) {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            var html = document.querySelector('#wrapper');
            html.parentNode.replaceChild(newHtml, html);
            window.history.pushState({}, '', link.attributes.href.nodeValue);
        });
    },*/

    /*
    *  How to deal with script tags in a document :
    *
    *  var script = document.createElement('script');
    *  script.append('$(document).ready(function(){console.log(\'test1\')});');
    *  document.querySelector('body').append(script);
    * */
};

var cache = {};

window.onload = speed.init;