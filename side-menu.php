<style>

/* MODERN DARK SIDEBAR STYLE 
   Matches Slate/Blue theme (#0f172a)
*/

:root {
    --side-bg: #0f172a;
    --side-hover: #1e293b;
    --side-active: #38bdf8;
    --side-border: #334155;
    --side-text: #94a3b8;
    --side-text-bright: #f8fafc;
}

/* Main Admin Menu Container */
#adminmenumain {
    background: var(--side-bg) !important;
    padding: 20px 14px;
    height: 100dvh;
    overflow-y: auto;
    width: 200px;
    position: fixed;
    top: 0;
    border-right: 1px solid var(--side-border);
    box-shadow: 4px 0 10px rgba(0,0,0,0.2);
}

/* Hide WP branding/extra junk in sidebar */
#adminmenumain > :not(#adminmenuwrap) {
    display: none !important;
}

div#adminmenuwrap {
    position: relative !important;
    top: 0 !important;
}

/* Menu List */
ul#adminmenu {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 20px;
}

/* Hide separators and collapse menu button */
.wp-not-current-submenu.wp-menu-separator,
#collapse-menu,
#adminmenu div.separator {
    display: none !important;
}

/* Top-level Links */
#adminmenu li.menu-top > a {
    display: flex !important;
    align-items: center !important;
    padding: 10px 16px !important;
    color: var(--side-text) !important;
    text-decoration: none !important;
    font-weight: 500 !important;
    font-size: 13px;
    border-radius: 8px;
    transition: all 0.2s ease-in-out !important;
    background: transparent !important;
}

/* Icons */
#adminmenu div.wp-menu-image:before {
    color: var(--side-text) !important;
    opacity: 0.7;
}

/* Hover & Active States */
#adminmenu li.menu-top:hover > a,
#adminmenu li.wp-has-current-submenu > a.menu-top {
    color: var(--side-text-bright) !important;
    background: var(--side-hover) !important;
}

#adminmenu li.menu-top:hover div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu div.wp-menu-image:before {
    color: var(--side-active) !important;
    opacity: 1;
}

/* Submenu Container */
#adminmenu .wp-submenu {
    background: rgba(30, 41, 59, 0.5) !important;
    margin: 5px 0 5px 15px !important;
    border-left: 1px solid var(--side-border) !important;
    padding: 5px 0 !important;
    position: static !important; /* Prevents fly-outs for a vertical feel */
}

/* Submenu Links */
#adminmenu .wp-submenu li a {
    color: var(--side-text) !important;
    font-size: 12px !important;
    padding: 6px 15px !important;
    transition: color 0.2s ease;
}

#adminmenu .wp-submenu li a:hover,
#adminmenu .wp-submenu li.current a {
    color: var(--side-active) !important;
    background: transparent !important;
}

/* Current Page Marker */
#adminmenu li.current a.menu-top,
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu {
    background: var(--side-hover) !important;
    border-left: 3px solid var(--side-active) !important;
    border-radius: 0 8px 8px 0;
}
 
</style>