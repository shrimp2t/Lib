<?php

define('WP_SG_URL', trailingslashit(plugins_url('', __FILE__)));
define('WP_SG_PATH', trailingslashit(plugin_dir_path(__FILE__)));

// add the tab
add_filter('media_upload_tabs', 'my_upload_tab');
function my_upload_tab($tabs) {
    $tabs['mytabname'] = "My Tab Name";
    return $tabs;
}

// call the new tab with wp_iframe
add_action('media_upload_mytabname', 'add_my_new_form');
function add_my_new_form() {
    wp_iframe( 'my_new_form' );
}

// the tab content
function my_new_form() {
    echo media_upload_header(); // This function is used for print media uploader headers etc.
    echo '<p>Example HTML content goes here.</p>';
}

//wp_enqueue_media();

///----------------------------------------------------------


add_filter('media_upload_tabs', 'my_media_upload_tabs_filter');

function my_media_upload_tabs_filter($tabs) {
    unset($tabs["type_url"]);
    unset($tabs['library']);
    $newtab = array('ell_insert_gmap_tab' => __('Google Map','insertgmap'));

    return array_merge($tabs,$newtab);
}

add_action('media_upload_ell_insert_gmap_tab', 'media_upload_ell_gmap_tab');

function media_upload_ell_gmap_tab() {
    return wp_iframe('media_upload_ell_gmap_form', $errors );
}

function media_upload_ell_gmap_form() {
    ?>
    <h2>HTML Form</h2>

    <?php
}


///----------------------------------------------------------


/**
 * Register a custom menu page.
 */
function wpsg_register_menu_page(){
    add_media_page(
        __( 'Social Gallery', 'textdomain' ),
        __( 'Social Gallery', 'textdomain' ),
        'manage_options',
        'social-gallery',
        'wpsg_menu_page'
    );
}
add_action( 'admin_menu', 'wpsg_register_menu_page' );

/**
 * Display a custom menu page
 */
function wpsg_menu_page(){
    ?>
    <div class="wrap">
        <h2>Gallery</h2>

        <h3>Facebook gallery</h3>
        <div class="fb-images"></div>


        <h3>Flickr</h3>
        <div class="flickr-images"></div>

        <h3>Instagram</h3>
        <div class="instagram-images"></div>

        <script type="text/javascript">


            jQuery(  document).ready( function( $ ){


                jQuery.ajaxSetup({ cache: false });

                $.getJSON( 'https://graph.facebook.com/v2.5/217290018449322?',
                    {
                        fields:"photos.limit(500){images,link,name,picture,width}",
                        access_token: '202670940102926|bb14cd826cee5490f19051e6bd0bd77c' // app ID|secret key
                    },
                    function( response ){
                    //console.log( response );

                    $.each( response.photos.data , function(  key, photo ){
                        jQuery( '.fb-images').append( '<img alt="//" src="'+photo.picture+'"/>' );
                    });

                } );

                //var _url =  'https://api.flickr.com/services/rest/?medthod=flickr.photosets.getPhotos&api_key=a68c0befe246035b74a8f67943da7edc&photoset_id=72157594162136485&format=json';
                $.get(
                    'https://api.flickr.com/services/rest/',
                    {
                        method: 'flickr.photosets.getPhotos',
                        api_key: 'a68c0befe246035b74a8f67943da7edc',
                        photoset_id: '72157651609884000',
                        format: 'json',
                        nojsoncallback: 1,

                    },
                    function ( response ) {
                       // console.log('flickr');
                       // console.log(response);
                        // https://www.flickr.com/services/api/misc.urls.html

                        /// https://www.flickr.com/photos/78942874@N06/17825679861/in/album-72157651609884000/
                        /// https://www.flickr.com/photos/{user-id}/{photo-id}/in/album-{album-id}/

                       $.each(response.photoset.photo, function (key, photo) {
                           var img = 'https://farm' + photo.farm + '.staticflickr.com/' + photo.server + '/' + photo.id + '_' + photo.secret + '_m.jpg';
                            jQuery('.flickr-images').append('<img alt="//" src="' + img + '"/>');
                       });
                    }

                );

                /// instagram
                //sanbox token: 3063603856.23fca78.190905a347f3419e8cdc90dee97151aa
                // search user iD https://api.instagram.com/v1/users/search?q=hoangsa2t&access_token=3063603856.23fca78.190905a347f3419e8cdc90dee97151aa
                // url = 'https://api.instagram.com/v1/users/245621240/media/recent/?access_token=3063603856.23fca78.190905a347f3419e8cdc90dee97151aa&count=50;
                // https://api.instagram.com/v1/users/245621240/media/recent/?access_token=35163631.3a5ca5d.be9b66ad0f964d17b996d2a899bc8cd3&count=50

                $.ajax( {
                    url:  'https://api.instagram.com/v1/users/245621240/media/recent/',
                    dataType: 'jsonp',
                    type: 'get',
                    cache: false,
                    crossDomain:true,
                    data:  {
                        // access_token: '35163631.3a5ca5d.be9b66ad0f964d17b996d2a899bc8cd3',
                        client_id: 'ea126bd8795744b2a86ec4e775f6454c',
                        count: 100
                    },
                    success: function( response ){
                        console.log('instagram');
                        console.log(response);

                        $.each(response.data, function (key, photo) {
                            var img = photo.images.thumbnail.url;
                            jQuery('.instagram-images').append('<img alt="//" src="' + img + '"/>');
                        });

                    }
                });


            } );

        </script>

    </div>
    <?php
}
