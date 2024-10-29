(function() {
    tinymce.PluginManager.add('altlistly_tc_button', function( editor, url ) {
        editor.addButton( 'altlistly_tc_button', {
            text: 'List.ly',
            icon: false,
            onclick: function() {
                editor.insertContent('[listly_shortcode url="" number_per_page_listly="12"]');
            }
        });
    });
})();