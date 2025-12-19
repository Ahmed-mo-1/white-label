<?php


add_action( 'admin_footer', 'amtf_inject_toggle_logic' );
function amtf_inject_toggle_logic() {
?>
<style>
    /* ===============================
       FLOATING BUTTON (Hidden desktop)
    ================================ */
    #amtf-fab {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background-color: #fff;
        color: #000;
        border-radius: 50%;
        z-index: 999999;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        cursor: pointer;
        font-size: 26px;
        border: none;
    }

    /* ===============================
       OVERLAY
    ================================ */
    #amtf-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 999997;
    }

    /* ===============================
       MOBILE ONLY
    ================================ */
    @media screen and (max-width: 782px) {

        #amtf-fab {
            display: flex;
        }

        /* Hide admin menu by default */
        #adminmenumain {
            display: none !important;
        }

        /* Reset content layout */
        #wpbody {
            margin-left: 0 !important;
        }

        /* Menu when active */
        body.amtf-active #adminmenumain {
            display: block !important;
            position: fixed !important;
            top: 0;
            left: 0;
            bottom: 0;
            width: 190px !important;
            overflow-y: auto;
            z-index: 999998;
        }

        /* Show overlay when active */
        body.amtf-active #amtf-overlay {
            display: block;
        }
    }
</style>

<div id="amtf-overlay"></div>
<button id="amtf-fab" type="button">☰</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var fab = document.getElementById('amtf-fab');
    var body = document.body;
    var overlay = document.getElementById('amtf-overlay');

    function closeMenu() {
        body.classList.remove('amtf-active');
        fab.innerHTML = '☰';
    }

    fab.addEventListener('click', function (e) {
        e.stopPropagation();
        body.classList.toggle('amtf-active');
        fab.innerHTML = body.classList.contains('amtf-active') ? '✕' : '☰';
    });

    overlay.addEventListener('click', closeMenu);
});
</script>
<?php
}
