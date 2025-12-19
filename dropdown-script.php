<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hide all submenus initially
    document.querySelectorAll('#adminmenu .wp-submenu-wrap').forEach(function (submenu) {
        submenu.style.display = 'none';
    });

    // Toggle submenu on click
    document.querySelectorAll('#adminmenu li.menu-top > a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const submenu = link.parentElement.querySelector('.wp-submenu-wrap');

            // If there is a submenu
            if (submenu) {
                e.preventDefault();

                // Close all other submenus
                document.querySelectorAll('#adminmenu .wp-submenu-wrap').forEach(function (other) {
                    if (other !== submenu) {
                        other.style.display = 'none';
                    }
                });

                // Toggle this submenu
                submenu.style.display =
                    submenu.style.display === 'none' ? 'block' : 'none';
            }
            // If no submenu → link works normally
        });
    });
});


</script>