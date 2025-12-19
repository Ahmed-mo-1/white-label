<style>

:root {
	--primary-color : #505050;
	--background-color : #252525;
	--background-light-color : #252525;
	--text-color : #fff;
}
* {
	padding: 0;
	margin: 0;
	box-sizing: border-box;
	color: white;
	font-size: 12px;
	font-family: poppins
}

body {
	font-family: poppins;
	background: var(--background-light-color);
}


/* Target the scrollbar */
::-webkit-scrollbar {
  width: 4px; /* width of vertical scrollbar */
  height: 4px; /* height of horizontal scrollbar */
}

/* Track (background) */
::-webkit-scrollbar-track {
  background: none;
  border-radius: 6px;
}

/* Handle (thumb) */
::-webkit-scrollbar-thumb {
  background: var(--primary-color);
  border-radius: 6px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: var(--primary-color);
}



div#wpbody {
    margin-left: 200px;
	min-height: 100dvh
} 

.wrap {
    max-width: 100%;
    width: 100%;
    margin: 40px auto;
    padding: 20px 30px;
    border-radius: 10px;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    color: #fff;
    box-shadow: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: start;
    align-items: start;
}


.woocommerce-admin-page .wp-has-current-submenu:after {
	display: none
}


#toplevel_page_admin-page-wc-settings-tab-checkout-from-PAYMENTS_MENU_ITEM,
#toplevel_page_wc-admin-path--analytics-overview,
#toplevel_page_woocommerce-marketing,
#toplevel_page_astra {
	display: none
}


#wpwrap {display: flex}
#wp-auth-check-wrap {display: none}

.wp-menu-image,wp-menu-image svg {
	display: none
}






#wpadminbar {display: none}

#screen-options-wrap , #screen-meta, #screen-meta-links, .woocommerce-layout__header {
	display: none
}

#wpcontent {
	width: 100%;
	background: var(--background-light-color);
}

button, input[type="submit"]{
	border: none;
    background: var(--primary-color);
    color: white;
    padding: 0;
    border-radius: 0;
    height: 40px;
    padding-inline: 10px;
}

input, select {
	border-radius: 20px
}

ul {
list-style: none
}


.alignleft.actions {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 14px;
    gap: 10px;
    overflow: hidden;
}

a {
    text-decoration: none;
}

div#wpbody {
/*
    height: 94dvh;
    overflow: auto;
    box-shadow: 2px 2px 10px black;
    border-radius: 16px;
    padding: 10px;
*/
}



div.woocommerce-message.updated {
	display: none
}


#astra-optin-notice, .notice.e-notice.e-notice--extended {
	display: none
}

#toplevel_page_logout-link {
	order: 100
}
</style>