const swup = new Swup();

// Start Visual Editor
function setupVisualEditor() {
    initializeProsemirror();
    window.proseMirrorIsActive = false;

    if (jQuery('#dw__editform').find('[name=prosemirror_json]').length) {
        handleEditSession();
    }

    jQuery(window).on('fastwiki:afterSwitch', function(evt, viewMode, isSectionEdit, prevViewMode) {
        if (viewMode === 'edit' || isSectionEdit) {
            handleEditSession();
        }
    });
}
swup.on("contentReplaced", setupVisualEditor);