=== OG ===
Contributors: iworks
Donate link: http://iworks.pl/donate/og.php
Tags: OpenGraph, Open Graph, Facebook, Twitter, social
Requires at least: 3.8
Tested up to: 5.2
Stable tag: 2.7.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple method to add Open Graph metadata to your entries so that they look great when shared on sites like Facebook and Google+.

== Description ==

The [Open Graph protocol][] enables any web page to become a rich object in a social graph.  Most notably, this allows for these pages to be used with Facebook's [Like Button][] and [Graph API][] as well as within [Google+][] posts.

The Open Graph plugin inserts the Open Graph metadata into page head section and provides filters for other plugins and themes to override this data, or to provide additional Open Graph data.

No configuration, pure power.

Plugin grabs data from content and if contains YouTube URL, then plugin try to get movie thumbnail and use it in og:image.

If the post contains YouTube links, this plugin saves as post meta video thumbnail link and add it to og:image as post thumbnail.

[Open Graph Protocol]: http://ogp.me/
[Like Button]: https://developers.facebook.com/docs/reference/plugins/like
[Graph API]: https://developers.facebook.com/docs/reference/api/
[Google+]: http://www.google.com/+
[+1 Button]: https://developers.google.com/+/plugins/+1button/

== Installation ==

There are 3 ways to install this plugin:

= The super easy way =

1. **Login** to your WordPress Admin panel.
1. **Go to Plugins > Add New.**
1. **Type** ‘OG’ into the Search Plugins field and hit Enter. Once found, you can view details such as the point release, rating, and description.
1. **Click** Install Now. After clicking the link, you’ll be asked if you’re sure you want to install the plugin.
1. **Click** Yes, and WordPress completes the installation.
1. **Activate** the plugin.
1. That's all. The plugin does not have any configuration.

***

= The easy way =

1. Download the plugin (.zip file) on the right column of this page
1. In your Admin, go to menu Plugins > Add
1. Select button `Upload Plugin`
1. Upload the .zip file you just downloaded
1. Activate the plugin
1. That's all. The plugin does not have any configuration.

***

= The old and reliable way (FTP) =

1. Upload `OG` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That's all. The plugin does not have any configuration.

== Frequently Asked Questions ==

= How do I configure the Open Graph plugin? =

There is nothing to configure and there is no admin page. By default, it will use standard WordPress data which can to populate the Open Graph. There are very simple and powerful filters which you can use to modify or extend the metadata returned by the plugin. More information below.

= What plugin add for all type of content? =

* og:locale - site locale
* og:site_name - blog title
* og:title - post/page/archive/tag/... title
* og:url - the post/page permalink
* og:type - "website" for the homepage, "article" for single content and blog for all others
* og:description - site description
* og:site_name - site name

= What plugin add for single content? =

* og:image: From a specific custom field of the post/page, or if not set from the post/page featured/thumbnail image, or if it doesn't exist from the first image in the post content, or if it doesn't exist from the first image on the post media gallery, or if it doesn't exist from the default image defined in the options menu. The same image chosen here will be used and enclosure/media:content on the RSS feed.
* og:video - add links to YouTube movies.
* article:author - author of post link
* article:published_time - date of first article publication
* article:modified_time - date of last article modification
* article:tag - tags used in post
* twitter:card - summary
* twitter:title - the same line og:title
* twitter:description - the same like og:description
* twitter:image - the same like og:image
* twitter:player - the same like og:video

= What plugin add for a single WooCommerce product? =

* og:price:amount - price amount
* og:price:currency - price currency
* og:availability - stock status

= I installed OG and ... nothing happens! =

Please be patient, sometimes you need more a day to see results. The reason of this is cache on Facebook. But check your plugins too and if you use and caching plugins, try to do "flush cache" on your site.

You can force FB to refresh OpenGraph data by using this page https://developers.facebook.com/tools/debug/sharing/. Just go to Sharing Debugger, enter your URL and hit the button "Scrap Again".

= How to filter values? =

Use auto filters. If you have value like this:

    <meta property="og:title" content="WordPress Trunk" />

Then auto filter is created like this:

og_ + (word before ":") + _ + (word after ":") + _value

In this case:

og_og_title_value

    add_filter('og_og_title_value', 'my_og_og_title_value');
    function my_og_og_title_value($title)
    {
        if ( is_home() ) {
            return __('This is extra home title!', 'translate-domain');
        }
        return $title;
    }

= How to filter whole meta tag? =

Use auto filters. If you have value like this:

    <meta property="og:title" content="WordPress Trunk" />

Then auto filter is created like this:

og_ + (word before ":") + _ + (word after ":") + _meta

In this case:

og_og_title_meta

    add_filter('og_og_title_meta', 'my_og_og_title_meta');
    function my_og_og_title_meta($title)
    {
        if ( is_home() ) {
            return '<meta property="og:title" content="WordPress Title" />';
        }
        return $title;
    }

= How to setup default image? =

Use filter "og_image_init":

    add_filter('og_image_init', 'my_og_image_init');
    function my_og_image_init($images)
    {
        if ( is_front_page() || is_home() ) {
            $images[] = 'http://wordpress/wp-content/uploads/2014/11/DSCN0570.jpg';
        }
        return $images;
    }

= How to setup image on the front page? =

Use filter "og_image_init":

    add_filter('og_og_image_value', 'my_og_og_image_value');
    function my_og_og_image_value($images)
    {
        if ( empty($images) ) {
            $images[] = 'http://wordpress/wp-content/uploads/2014/11/DSCN0570.jpg';
        }
        return $images;
    }

= How to add Facebook app_id? =

OG plugin is super simple and we do not plan to add any "options page" which is needed if you want to have data like "fb:app_id" or "fb:pages".

But, if you really wanna use OG and serve "FB" OpenGraph tags, then you can use `og_array` filter to add (or modify) OpenGraph tags.

    add_filter( 'og_array', 'add_og_facebook_data' );
    function add_og_facebook_data( $og ) {
        $og['fb'] = array(
            'app_id' => 'my-app-id',
            'pages' => 'foo, bar',
        );
        return $og;
    }

== Changelog ==

= 2.7.6 (2019-08-18) =
* Added filter `og_profile` to allow change `profile` values. Props for [edmorrow](https://wordpress.org/support/users/edmorrow/)

= 2.7.5 (2019-05-15) =

* Fixed missing `og:image:alt` for featured image.

= 2.7.4 (2019-04-25) =
* Fixed a problem with `og:image` content for document with featured image and images in the content. Props for [sudoranger](https://wordpress.org/support/users/sudoranger/)

= 2.7.3 (2019-04-23) =
* Added "summary_large_image" for twitter:card if attached image has width bigger then 520px. Props for [Bobby Eberle](https://www.facebook.com/bobby.eberle).


= 2.7.2 (2019-04-13) =
* Fixed `sprintf()` issue. Props for [John Glynn](https://www.linkedin.com/in/john-glynn-2a233426/)

= 2.7.1 (2019-04-13) =
* Added locale string into cache settings to be able handle languages. Props for [Oleksandr Omelchenko](https://wordpress.org/support/users/konusua/).
* Added cache locale value inside the class object to.

= 2.7.0 (2018-10-21) =
* Added proper og:url for custom post archive page. Props for [cabaltc](https://wordpress.org/support/users/cabaltc/).
* Added proper og:url for a day, month and year archive page.
* Added proper og:url for a search result.
* Added proper og:url for taxonomy archive page.
* Removed OpenGraph from 404 page.

= 2.6.2 (2018-10-11) =
* Fixed blog posts page og:url. Props for [cabaltc](https://wordpress.org/support/users/cabaltc/).

= 2.6.1 (2018-09-06) =
* Added `esc_url` for image src value.
* Striped tags from OG tag value.

= 2.6.0 (2018-06-04) =
* Added attached audio files to `og:audio` tag.
* Added attached video files to `og:video` tag.
* Added `og:update_time` tag.
* Added transient cache for single entries to decrease DB usage.
* Updated Facebook locales list.

= 2.5.3 (2018-05-09) =

* Remove debug function, which broke whole plugin.

= 2.5.2 (2018-05-08) =

* Added filter `og_description_words` to allow change `og:description` length.
* Fixed a problem with striping last word. Props for [intrex](https://wordpress.org/support/users/intrex/).
* We are back to trim `og:description` to 55 words (it is default for `wp_trim_words()` function).

= 2.5.1 (2018-04-16) =

* Added og:image:width and og:image:height for first content image from site URL.

= 2.5.0 (2018-04-14) =

* Added og:image:width and og:image:height for featured image.

= 2.4.9 (2018-02-27) =

* Remove filter "the_content" to avoid incompatibility with some plugins.

= 2.4.8 (2018-02-19) =

* Added first content image to og:image if featured image is not set. Props for [andreyenkin](https://wordpress.org/support/users/andreyenkin/).

= 2.4.7 (2017-09-26) =

* Added filter "og_[og_name]_value" to change single og value.
* Added og:type for post formats "audio" and "video".
* Fixed og:type for WooCommerce product. Props for [shaharsol](https://wordpress.org/support/users/shaharsol/).

= 2.4.6 (2017-09-13) =

* Removed limit for og:description.

= 2.4.5 (2017-06-13) =

* Added filter "og_array" which allows to change whole OG array before print it.


= 2.4.4 (2017-05-20) =

* Fixed site crash when WooCommerce is active. Props for [JLY](https://wordpress.org/support/users/jose-luis-yanez/).

= 2.4.3 (2017-05-09) =

* Update "Rate" module to 1.0.1 - fixed wrong rate URL for non-English.

= 2.4.2 (2017-05-03) =

* Added tags "og:video" and "twitter:player" for YouTube embed movies.
* Added WooCommerce integration for tags: "og:price:amount", "og:price:currency" and "og:availability".
* Improved description tag, when entry content is empty, add entry title as description.

= 2.4.1 (2016-10-26) =

* Fixed problem for pages and another single content. At this moment OG works for all types of single entries.
* Added ask for the rating on the plugin page.
* Short twitter description.

= 2.4 (2016-04-10) =

* Fixed the problem with proper preparation for localization.
* Fixed the profile with grabbing YouTube image.
* Implement WordPress code standard for PHP code used in the plugin.

= 2.3 (2016-02-03) =

* Added categories as og:section.
* Added esc_attr to headers tags.
* Added msapplication-TileImage.
* Added og:site_name.
* Added profile:first_name, profile:last_name & profile:username props for [Arek](http://arek.bibliotekarz.com/).
* Added tags.
* Added twitter tags.
* Added usage of site icon when is no icon - all cases.

= 2.2 (2015-08-19) =

* IMPROVEMENT: added the site icon as og:image for the home page.

= 2.1 (2015-05-21) =

* IMPROVEMENT: added checking site locale with facebook allowed locale.

= 2.0 (2014-12-11) =

* IMPROVEMENT: added check to post_content exists for CPT without this field.
* IMPROVEMENT: added og:author link
* IMPROVEMENT: big refactoring
* IMPROVEMENT: added filters, see [FAQ](https://wordpress.org/plugins/og/faq/) section

= 1.0 (2014-10-02) =

Init.
