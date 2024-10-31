(function() {
    tinymce.PluginManager.add('dashboard_button', function(editor, url) {
        editor.addButton('dashboard_button', {
            title: 'Insérer la balise du widget de suivi de vos parrainages',
            image: 'http://dc.welovecustomers.fr/wp-content/plugins/sources/img/icon_suivi.png',
            onclick: function() {
                editor.insertContent('[wlc_dashboard]');
            }
        });
    });
})();
