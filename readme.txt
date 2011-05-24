=== Custom Page Menus ===
Contributors: Fubra, ray.viljoen
Tags: custom menus, custom menu, menu widget, page menu, unique menu, page-specific menu, related menu
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 0.1

Custom Page Menus plugin allows custom menus to be defined on a per-page basis.

== Description ==

The included pages can be defined using the 'Custom Page Menus' admin panel on the page-edit screen ( see screenshots ).

The widget allows you to select whether to display the page's featured image next to the link. (I would recommend using icon sized images for this feature)

On the page-edit screen a custom title for that specific page can also be defined. This title will be used should that page appear anywhere on one of the menus created with the plugin.

The menu can be added to any dynamic sidebar using the Custom Page Menus widget or alternatively coded into themes using the custom template tag:

cpMenu()
 
 $args = array( <br /> 
'show_date' => , <br /> 
'date_format' => get_option('date_format'), <br /> 
'child_of' => 0, <br /> 
'exclude' => , <br /> 
'title_li' => __('Pages'), <br /> 
'echo' => 1, <br /> 
'authors' => , <br /> 
'sort_column' => 'menu_order, post_title', <br /> 
'link_before' => , <br /> 
'link_after' => , <br /> 
'walker' => ); <br />

Example Usage: *http://catn.com/tech/data-centre/*

== Installation ==

1. Upload the `custom-page-menu` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add and customise the widget to one of your sidebars through the widgets menu in WP admin or alternatively code the menu into your theme using the tag provided 'cpMenu()'.
4. Select Menu pages to be added in the page-edit screen.


== Frequently Asked Questions ==

= Will the widget be displayed on all pages? =

No, the widget will only output on pages with included Custom Page Menus Widget items.
Should a page not have any menu defined the widget will not display anything or affect the layout of the page in any way.

= Where is the Custom Page Menus data stored? =

The included pages will be stored in a comma seperated list of ID's in the page custom meta.
This meta data might be visible in some WP installs as 'custom field' below the page edit window and should not be edited directly.

= Can the Custom Page Menus Widget be coded into a theme/template? =

Appart from the widget Custom Page Menus can also be included using the template tag: '<?php cpMenu() ?>'
This function will accept all the standard wp_list_pages arguments. Note that the featured image can not yet be added via the template tag although this will be added to a future release.

== Screenshots ==

1. Styled version used on WP page.
2. Custom Page Menus settings in page-edit screen.
3. Widget settings.

== Changelog ==