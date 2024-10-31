
(function() {
    tinymce.PluginManager.add('box_button', function(editor, url) {
        editor.addButton('box_button', {
            title: 'Insérer la balise du widget de parrainage',
            image: 'http://dc.welovecustomers.fr/wp-content/plugins/sources/img/referral_icon.png',
            onclick: function() {
                editor.insertContent('[wlc_box]');
            }
        });
    });
})();