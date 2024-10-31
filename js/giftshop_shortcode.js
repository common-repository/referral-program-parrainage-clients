
(function() {
    tinymce.PluginManager.add('giftshop_button', function(editor, url) {
        editor.addButton('giftshop_button', {
            title: 'Insérer la balise du lien vers la vitrine',
            image: '/wp-content/plugins/sources/img/referral_icon.png',
            onclick: function() {
                editor.insertContent('[wlc_giftshop]');
            }
        });
    });
})();