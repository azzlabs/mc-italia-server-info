const mcit_smde_toolbar = [
    {
        name: "bold", 
        action: SimpleMDE.toggleBold, 
        title: "Grassetto", 
        className: "dashicons dashicons-editor-bold"
    },
    {
        name: "italic", 
        action: SimpleMDE.toggleItalic, 
        title: "Corsivo", 
        className: "dashicons dashicons-editor-italic"
    },
    {
        name: "strikethrough", 
        action: SimpleMDE.toggleStrikethrough, 
        title: "Barrato", 
        className: "dashicons dashicons-editor-strikethrough"
    },
    {
        name: "heading", 
        action: SimpleMDE.toggleHeadingSmaller, 
        title: "Intestazione grande", 
        className: "dashicons dashicons-heading"
    },
    '|',
    {
        name: "code", 
        action: SimpleMDE.toggleCodeBlock, 
        title: "Codice", 
        className: "dashicons dashicons-editor-code"
    },
    {
        name: "quote", 
        action: SimpleMDE.toggleBlockquote, 
        title: "Citazione", 
        className: "dashicons dashicons-editor-quote"
    },
    {
        name: "unordered-list", 
        action: SimpleMDE.toggleUnorderedList, 
        title: "Elenco puntato", 
        className: "dashicons dashicons-editor-ul"
    },
    {
        name: "ordered-list", 
        action: SimpleMDE.toggleOrderedList, 
        title: "Elenco numerato", 
        className: "dashicons dashicons-editor-ol"
    },
    {
        name: "clean-block", 
        action: SimpleMDE.cleanBlock, 
        title: "Cancella formattazione", 
        className: "dashicons dashicons-editor-removeformatting"
    },
    '|',
    {
        name: "link", 
        action: SimpleMDE.drawLink, 
        title: "Crea link", 
        className: "dashicons dashicons-admin-links"
    },
    {
        name: "image", 
        action: SimpleMDE.drawImage, 
        title: "Inserisci immagine da url", 
        className: "dashicons dashicons-format-image"
    },
    {
        name: "media", 
        action: function (editor) {
            var cm = editor.codemirror;
            var media_select;
            if (media_select) media_select.open();
            
            media_select = wp.media({
                title: 'Seleziona immagini da inserire',
                multiple : true,
                library : {
                    type : 'image',
                }
            });

            media_select.on('close',function() {
                var selected = media_select.state().get('selection');
                selected.each(function(image) {
                    cm.replaceSelection(`![${cm.getSelection()}](${image.attributes.url})\n`);
                });
            });

            media_select.open();
        },
        title: "Inserisci media da wordpress", 
        className: "dashicons dashicons-admin-media"
    },
    {
        name: "youtube", 
        action: function (editor) {
            var cm = editor.codemirror;
            cm.replaceSelection(`@[youtube](https://www.youtube-nocookie.com/watch?v=VIDEO_ID)\n`);
        },
        title: "Inserisci un video di YouTube", 
        className: "dashicons dashicons-youtube"
    },
    {
        name: "table", 
        action: SimpleMDE.drawTable, 
        title: "Inserisci tabella", 
        className: "dashicons dashicons-editor-table"
    },
    {
        name: "horizontal-rule", 
        action: SimpleMDE.drawHorizontalRule, 
        title: "Inserisci linea orizzonatale", 
        className: "dashicons dashicons-minus"
    },
    '|',
    {
        name: "preview", 
        action: SimpleMDE.togglePreview, 
        title: "Mostra anteprima", 
        className: "dashicons dashicons-visibility no-disable"
    },
    {
        name: "side-by-side", 
        action: SimpleMDE.toggleSideBySide, 
        title: "Mostra anteprima a fianco", 
        className: "dashicons dashicons-columns no-disable no-mobile"
    },
    {
        name: "fullscreen", 
        action: SimpleMDE.toggleFullScreen, 
        title: "Attiva/disattiva schermo intero", 
        className: "dashicons dashicons-fullscreen-alt no-disable no-mobile"
    },
    '|',
    {
        name: "mc-italia", 
        action: function() {
            window.open("https://www.minecraft-italia.it/stuff/markdown-editor");
        }, 
        title: "Sandbox markdown di Minecraft-Italia", 
        className: "dashicons dashicons-external"
    },
    {
        name: "info", 
        action: function() {
            window.open("https://commonmark.org/help/");
        }, 
        title: "Info su markdown", 
        className: "dashicons dashicons-info-outline"
    }
];