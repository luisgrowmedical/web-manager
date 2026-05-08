/**
 * web-manager Connector - Admin Scripts
 */
jQuery(document).ready(function($) {
    
    // Copy to clipboard
    $('.web-manager-copy-btn').on('click', function() {
        const btn = $(this);
        const targetId = btn.data('target');
        const input = $('#' + targetId);
        
        // Temporarily change type to text if it's password to copy the actual value
        const originalType = input.attr('type');
        input.attr('type', 'text');
        
        input.select();
        document.execCommand('copy');
        
        // Revert type
        input.attr('type', originalType);
        
        // Feedback
        const originalText = btn.text();
        btn.text('Copied!');
        btn.css('background', '#39b54a').css('color', '#fff').css('border-color', '#39b54a');
        
        setTimeout(() => {
            btn.text(originalText);
            btn.css('background', '').css('color', '').css('border-color', '');
        }, 2000);
    });

    // Toggle API Key visibility
    $('.web-manager-view-btn').on('click', function() {
        const btn = $(this);
        const input = $('#web-manager-key');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            btn.text('Hide');
        } else {
            input.attr('type', 'password');
            btn.text('Show');
        }
    });
});
