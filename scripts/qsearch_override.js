/**
 * AJAX functions for the pagename quicksearch
 *
 * @license  GPL2 (http://www.gnu.org/licenses/gpl.html)
 * @author   Andreas Gohr <andi@splitbrain.org>
 * @author   Adrian Lang <lang@cosmocode.de>
 * @author   Michal Rezler <m.rezler@centrum.cz>
 */
 jQuery.fn.dw_qsearch = function (overrides) {

    var dw_qsearch = {

        output: '#qsearch__out',

        $inObj: this,
        $outObj: null,
        timer: null,
        curRequest: null,

        /**
         * initialize the quick search
         *
         * Attaches the event handlers
         *
         */
        init: function () {
            var do_qsearch;

            dw_qsearch.$outObj = jQuery(dw_qsearch.output);

            // objects found?
            if (dw_qsearch.$inObj.length === 0 ||
                dw_qsearch.$outObj.length === 0) {
                return;
            }

            // attach eventhandler to search field
            do_qsearch = function () {
                // abort any previous request
                if (dw_qsearch.curRequest != null) {
                    dw_qsearch.curRequest.abort();
                }
                var value = dw_qsearch.getSearchterm();
                if (value === '') {
                    dw_qsearch.clear_results();
                    return;
                }
                dw_qsearch.curRequest = jQuery.post(
                    DOKU_BASE + 'lib/exe/ajax.php',
                    {
                        call: 'qsearch',
                        q: encodeURI(value)
                    },
                    dw_qsearch.onCompletion,
                    'html'
                );
            };

            dw_qsearch.$inObj.keydown(
                function (event) {
                    // Abort if the key pressed is not a character
                    switch (event.key) {
                        case "ArrowDown": return;
                        case "ArrowUp": return;
                        case "ArrowLeft": return;
                        case "ArrowRight": return;
                        case "Enter": return;
                        case "Shift": return;
                        case "Escape": return;
                        case "CapsLock": return;
                        case "Meta": return;
                        case "Alt": return;
                        case "Tab": return;
                        case "Control": return;
                        case "ContextMenu": return;
                        case "Clear": return;
                        case "PageUp": return;
                        case "PageDown": return;
                        case "F1": return;
                        case "F2": return;
                        case "F3": return;
                        case "F4": return;
                        case "F5": return;
                        case "F6": return;
                        case "F7": return;
                        case "F8": return;
                        case "F9": return;
                        case "F10": return;
                        case "F12": return;
                        case "F13": return;
                    }

                    if (dw_qsearch.timer) {
                        window.clearTimeout(dw_qsearch.timer);
                        dw_qsearch.timer = null;
                    }
                    dw_qsearch.timer = window.setTimeout(do_qsearch, 50);
                }
            );

            // Show the search results when the search field is clicked
            dw_qsearch.$inObj.click(do_qsearch);
        },

        /**
         * Read search term from input
         */
        getSearchterm: function() {
            return dw_qsearch.$inObj.val();
        },

        /**
         * Empty the output div
         */
        clear_results: function () {
            // Keep the unordered list for other scripts to attach to
            dw_qsearch.$outObj.html('<ul></ul>');
        },

        /**
         * Callback. Reformat and display the results.
         *
         * Namespaces are shortened here to keep the results from overflowing
         * or wrapping
         *
         * @param data The result HTML
         */
        onCompletion: function (data) {
            var max, $links, too_big;

            dw_qsearch.curRequest = null;


            dw_qsearch.$outObj
                .html(data)
                .show()
                .css('white-space', 'nowrap');

            // disable overflow during shortening
            dw_qsearch.$outObj.find('li').css('overflow', 'visible');

            $links = dw_qsearch.$outObj.find('a');
            max = dw_qsearch.$outObj[0].clientWidth; // maximum width allowed (but take away paddings below)
            if (document.documentElement.dir === 'rtl') {
                max -= parseInt(dw_qsearch.$outObj.css('padding-left'));
                too_big = function (l) {
                    return l.offsetLeft < 0;
                };
            } else {
                max -= parseInt(dw_qsearch.$outObj.css('padding-right'));
                too_big = function (l) {
                    return l.offsetWidth + l.offsetLeft > max;
                };
            }

            if (data === '') {
                dw_qsearch.clear_results();
                return;
            }

            $links.each(function () {
                var start, length, replace, nsL, nsR, eli, runaway;

                if (!too_big(this)) {
                    return;
                }

                // make IE's innerText available to W3C conform browsers
                if (this.textContent) {
                    this.__defineGetter__('innerText', function () {
                        return this.textContent
                    });
                    this.__defineSetter__('innerText', function (val) {
                        this.textContent = val
                    });
                }

                nsL = this.innerText.indexOf('(');
                nsR = this.innerText.indexOf(')');
                eli = 0;
                runaway = 0;

                while ((nsR - nsL > 3) && too_big(this) && runaway++ < 500) {
                    if (eli !== 0) {
                        // elipsis already inserted
                        if ((eli - nsL) > (nsR - eli)) {
                            // cut left
                            start = eli - 2;
                            length = 2;
                        } else {
                            // cut right
                            start = eli + 1;
                            length = 1;
                        }
                        replace = '';
                    } else {
                        // replace middle with ellipsis
                        start = Math.floor(nsL + ((nsR - nsL) / 2));
                        length = 1;
                        replace = '…';
                    }
                    this.innerText = substr_replace(this.innerText,
                        replace, start, length);

                    eli = this.innerText.indexOf('…');
                    nsL = this.innerText.indexOf('(');
                    nsR = this.innerText.indexOf(')');
                }
            });

            // reenable overflow
            dw_qsearch.$outObj.find('li').css('overflow', 'hidden').css('text-overflow', 'ellipsis');
        }


    };

    jQuery.extend(dw_qsearch, overrides);

    if (!overrides.deferInit) {
        dw_qsearch.init();
    }

    return dw_qsearch;
};

jQuery(function () {
    jQuery('#qsearch__in').dw_qsearch({
        output: '#qsearch__out'
    });
});