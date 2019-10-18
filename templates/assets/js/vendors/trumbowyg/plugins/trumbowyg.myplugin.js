import axios from 'axios';

(function ($) {
    'use strict';

    var isSupported = function () {
        return typeof FileReader !== 'undefined';
    };

    var isValidImage = function (type) {
        return /^data:image\/[a-z]?/i.test(type);
    };

    // Plugin default options
    var defaultOptions = {
        path: 'api/files',
        images:  [],
    };

    // If the plugin is a button
    function buildButtonDef(trumbowyg) {
        return {
            fn: function () {
                var $modal = trumbowyg.openModal('Add image', [
                    
                ].join('\n')),
                    $language = $modal.find('.language'),
                    $code = $modal.find('.code');

                // Listen clicks on modal box buttons
                $modal.on('tbwconfirm', function () {
                    trumbowyg.restoreRange();
                    trumbowyg.execCmd('insertHTML', '<p><br></p>');
                    trumbowyg.execCmd('insertHTML', '<p><br></p>');

                    trumbowyg.closeModal();
                });

                $modal.on('tbwcancel', function () {
                    trumbowyg.closeModal();
                });
            }
        };
    }

    $.extend(true, $.trumbowyg, {
        // Add some translations
        langs: {
            en: {
                myplugin: 'My plugin'
            }
        },
        // Register plugin in Trumbowyg
        plugins: {
            myplugin: {
                // Code called by Trumbowyg core to register the plugin
                init: function (trumbowyg) {
                    // Fill current Trumbowyg instance with the plugin default options
                    trumbowyg.o.plugins.myplugin = $.extend(true, {},
                        defaultOptions,
                        trumbowyg.o.plugins.myplugin || {}
                    );

                    // If the plugin is a paste handler, register it
                    trumbowyg.pasteHandlers.push(function (pasteEvent) {
                        // My plugin paste logic
                    });

                    // If the plugin is a button
                    trumbowyg.addBtnDef('myplugin', buildButtonDef(trumbowyg));
                },
                // Return a list of button names which are active on current element
                tagHandler: function (element, trumbowyg) {
                    return [];
                },
                destroy: function (trumbowyg) {
                }
            }
        }
    })

    // Get the images from api/files
    function getItems() {
        var images = [];

        axios
            .get(trumbowyg.o.plugins.myplugin.path)
            .then(res => {
                images = res.data.items;
            })
            .catch(err => console.log(err));

         return images;   
    }
})(jQuery);