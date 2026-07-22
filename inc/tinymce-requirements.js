(function() {
    tinymce.PluginManager.add('requirements_button', function(editor, url) {
        editor.addButton('requirements_button', {
            title: 'Mark as Requirements',
            text: '[Requirements]',
            icon: false,
            onclick: function() {
                var selected_text = editor.selection.getContent();
                var return_text = '';
                if(selected_text) {
                    return_text = '[requirements]' + selected_text + '[/requirements]';
                } else {
                    return_text = '[requirements]<br>Type specific requirements here<br>[/requirements]';
                }
                editor.insertContent(return_text);
            }
        });
    });
})();
