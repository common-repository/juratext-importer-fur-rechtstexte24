(function () {
    tinymce.create('tinymce.plugins.juratextloader', {
        init : function(ed, url) {
            ed.addButton('juratextloader', {
                title : 'JuraText laden',
                image : url+"../../../images/icons/law.jpg",
                onclick : function() {
                    var text = prompt("Welchen Rechtstext wollen sie laden?", "z.B.: agb oder impressum etc.");

                    if (text != null && text != ''){
                        ed.execCommand('mceInsertContent', false, '[rechtstext typ="'+text+'"]');
                    }
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "JuraText-Importer Editor-Plugin",
                author : 'sirconic-group',
                authorurl : 'http://sirconic-group.de',
                infourl : '',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('juratextloader', tinymce.plugins.juratextloader);
})();