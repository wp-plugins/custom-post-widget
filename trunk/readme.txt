=== Custom Post Widget ===
Contributors: vanderwijk
Donate link: http://www.vanderwijk.com/
Tags: custom post, widget, sidebar
Tested up to: 3.0.1
Stable tag: 1.1.1
Requires at least: 2.9.2

This plugin enables you to display the content of a custom post type called Content Block in a sidebar widget.

== Description ==

The Custom Post Widget allows you to display the contents of a specific custom post in a widget.

Even though you could use the text widget that comes with the default WordPress install, this plugin has some clear benefits:

* If you are using widgets to display content on various areas of your template, this content can only be edited by users with administrator access. If you would like editors to modify the widget content, you can use this plugin to provide them access to the custom posts that provide the content for the widget areas.
* It enables users to use the WYSIWYG editor for editing the content and adding images

This plugin creates a 'content_block' custom post type. The title is never displayed, use this to describe the position of the widget on the page. Note that these content blocks can only be displayed in the context of the page. I have added 'public' => false to the custom post type which means that it is not accessible outside the page context.

To add content to a widget, drag it to the required position in the sidebar and select the title of the custom post in the widget configuration.

== Screenshots ==

1. After activating the plugin a new post type called 'Content Blocks' is added.
2. The widget has a select box to choose the content block.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
You will see that a new custom post type has been added called Content Block.
3. Type some content for the widget. The title can be used to describe the position of the content on the page, It will not be displayed in the actual widget.
4. Go to 'Appearance' > 'Widgets' and drag the Content Block widget to the required position in the sidebar.
5. Select a Content Block from the drop-down list.
6. Click save.

== Frequently Asked Questions ==

= Why can't I use the default text-widget? =

Of course you can always use the default text widget, but if you prefer to use the WYSIWYG editor or if you have multiple editors and you don't want to give them administrator rights, it is recommended to use this plugin.

= How can I show the content bock on a specific page? =

It is recommended to install the Widget Logic plugin, this will give you complete flexibility on widget placement.

== Changelog ==

= 1.0 =
First release

= 1.1 =
Fixed screenshots for plugin directory

= 1.1.1 =
Added showposts=-1 to the post query to display more than 10 custom posts in the widget configuration select box.

== Upgrade Notice ==

= 1.1.1 =
Now supports more than 10 custom posts in the select box. Note that after upgrading you might have to save the widget state before the correct posts are being displayed.


