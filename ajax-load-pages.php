<?php

add_action('admin_footer', function() {
    ?>
    <script>
jQuery(document).ready(function($) {
    // 1. Listen for clicks on admin menu links
    $(document).on('click', '#adminmenu a', function(e) {
        const url = $(this).attr('href');

        // Only AJAX-load internal admin links (skip logout or external links)
        if (!url || url.indexOf('wp-login.php') !== -1 || url.indexOf('logout') !== -1) {
            return;
        }

        e.preventDefault();

        // 2. Visual feedback (optional)
        $('#wpbody').css('opacity', '0.5');

        // 3. Fetch the new page
        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                // 4. Parse the response and extract only the #wpbody content
                const newContent = $(data).find('#wpbody-content').html();
                
                // 5. Update the DOM
                $('#wpbody-content').html(newContent);
                $('#wpbody').css('opacity', '1');

                // 6. Update the browser URL and page title
                window.history.pushState({path: url}, '', url);
                
                // 7. Re-highlight the active menu item
                $('#adminmenu li').removeClass('current');
                $(e.currentTarget).parent('li').addClass('current');
            },
            error: function() {
                window.location.href = url; // Fallback if AJAX fails
            }
        });
    });

    // 8. Handle the Back/Forward buttons
    window.onpopstate = function() {
        window.location.reload(); // Simple fix for history navigation
    };
});    </script>
    <?php
});