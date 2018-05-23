/**
 * TODO : links to current page just redirect you to top of page (behaviour like href="#")
 * TODO : way to make async calls to links only on screen/window and not beyond
 *  TODO : also add a way to get newer links (and make calls to) to ones appearing after scrolling
 * TODO : do not load full dom tree from HTML, but only content after header & before footer
 * TODO : add a way to initialize the js/jQuery in the new dom (if not yet initialized)
 * TODO : better way of detecting links (currently it can brake if link to page doesn't have '/' at the beginning)
 * TODO : maybe refactor at some point
 */

window.onload = init;

function init () {
    getPageLinks();
    // asyncCall(links);
}

function asyncCall(link) {
    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function () {
            resolve(this.responseText);
        };
        xhr.onerror = reject;
        xhr.open('GET', link, true);
        xhr.send();
    });
}

function getPageLinks () {
    let a = document.getElementsByTagName('a');
    let links = [];
    for (let link of a) {
        if(link.attributes.href) {
            let href = link.attributes.href.nodeValue;
            if(href.charAt(0) == '/' && href.search('logout') == -1) {
                links.push(href);
                asyncCall(href)
                    .then(function(result) {
                        addDataToLink(link, result);
                    })
                    .catch(function() {
                        // An error occurred
                    });
            }
        }
    }
    return links;
}

function addDataToLink(link, data) {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        var html = document.getElementsByTagName('html');
        var newHtml = document.createElement('html');
        newHtml.innerHTML = data;
        html[0].parentNode.replaceChild(newHtml, html[0]);

        window.history.pushState({}, '', link.attributes.href.nodeValue);
    });
}