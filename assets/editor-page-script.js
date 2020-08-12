var count = 0;

jQuery(document).ready(function($) {

    new SimpleMDE({ element: $('.mdeditor')[0], spellChecker: false, status: false });

    $('.addSection').click(function(e) {
        e.preventDefault();
        addSection($(this), $(this).data('sectionslug'));
    });

    $('.color_field').each(function(){
        $(this).wpColorPicker();
    });

    $('[name*="-from"]').change(function() {
        const to = $(this).parent().find('[name*="-to"]');
        if (to.val() == '')
            to.val($(this).val());
    });

    function addSection(parent, slug, content = '') {
        count++;
        var section_child = parent.parent().find('.parent-section');

        section_child.append(`<div class="${slug}-entry" style="margin-bottom: .5em">
                <input type="text" name="${slug}[${count}]" class="regular-text" maxlength="50" value="${content}" placeholder="${mcit_locale.section_name}" />
                <button class="button button-secondary addEntry" data-sectionslug="${slug}_entry_${count}">Aggiungi ${slug}</button> 
                <button class="button-link button-link-delete delSection">${mcit_locale.section_remove}</button>
                <div class="section-entries section-entries-${count}"></div>
            </div>`).data('section-id', count).data('sectionslug', slug + '_entry_' + count);
    
        $('.addEntry').unbind();
        $('.addEntry').click(function(e) {
            e.preventDefault();

            addEntry($(this), slug);
        });
        
        delEntryListener();
        return section_child;
    }
    
    function addEntry(parent, slug, content = '') {
        const section_slug = parent.data('sectionslug');
        var findclass = '.section-entries';
        if (parent.data('section-id')) findclass += '-' + parent.data('section-id');

        parent.parent().find(findclass).append(`<div class="${slug}-entry" style="margin-top: .5em; margin-left: 1.2em">
                <input type="text" name="${section_slug}[]" value="${content}" class="regular-text ${slug}-name-field" maxlength="50" placeholder="${mcit_locale.label_name} ${slug}" />
                <button class="button-link button-link-delete delSection">${mcit_locale.label_remove}</button>
            </div>`);

        $('.staff-name-field').unbind();
        $('.staff-name-field').change(function() {
            const that = this;
            $.getJSON(ajaxurl + '?action=mcit_get_uuid&username=' + $(that).val(), function(data) {
                if (data != false) {
                    $(that).val(data.id);
                }
            });
        });

        delEntryListener();
    }

    function delEntryListener() {
        $('.delSection').unbind();
        $('.delSection').click(function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });
    }

    if (Array.isArray(staff_repeater_data)) {
        staff_repeater_data.forEach(arr => {
            for (var key in arr) {
                var child = addSection($('.addSection'), 'staff', key);
                arr[key].forEach(val => {
                    addEntry(child, 'staff', val);
                });
            }
        });
    }
});