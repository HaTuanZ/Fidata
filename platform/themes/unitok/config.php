<?php

use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these events can be overridden by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function($theme)
        {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme)
        {
            // Partial composer.
            // $theme->partialComposer('header', function($view) {
            //     $view->with('auth', \Auth::user());
            // });

            // You may use this event to set up your assets.
            $theme->asset()->usePath()->add('awesome-css', 'awesome/css/all.css', [], [], '6.2.1');
	        $theme->asset()->usePath()->add('bootstrap-reboot', 'css/bootstrap-reboot.min.css');
	        $theme->asset()->usePath()->add('bootstrap-grid', 'css/bootstrap-grid.min.css');
	        //$theme->asset()->usePath()->add('owl.carousel', 'css/owl.carousel.min.css');
	        //$theme->asset()->usePath()->add('magnific-popup', 'css/magnific-popup.css');
	        //$theme->asset()->usePath()->add('select2', 'css/select2.min.css');
	        $theme->asset()->usePath()->add('main', 'css/main.css');

            $theme->asset()->container('header')->usePath()->add('jquery', 'js/jquery-3.5.1.min.js');
            $theme->asset()->container('header')->add('jquery-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js');
            $theme->asset()->container('header')->add('tailwindcss', 'https://cdn.tailwindcss.com');
            $theme->asset()->container('footer')->usePath()->add('bootstrap.bundle', 'js/bootstrap.bundle.min.js');
	        //$theme->asset()->container('footer')->usePath()->add('owl.carousel', 'js/owl.carousel.min.js');
	        //$theme->asset()->container('footer')->usePath()->add('jquery.magnific-popup', 'js/jquery.magnific-popup.min.js');
	        //$theme->asset()->container('footer')->usePath()->add('select2', 'js/select2.min.js');
	        //$theme->asset()->container('footer')->usePath()->add('smooth-scrollbar', 'js/smooth-scrollbar.js');
	        //$theme->asset()->container('footer')->usePath()->add('jquery.countdown', 'js/jquery.countdown.min.js');
	        $theme->asset()->container('footer')->usePath()->add('main', 'js/main.js', [], [], time());

            if (function_exists('shortcode')) {
                $theme->composer(['page', 'post'], function (\Botble\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function ($theme)
            {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
                $theme->asset()->container('footer')->usePath()->add('fundraising', 'css/fundraising.css');
            },
            'vuejs' => function ($theme) {
	            $version = get_cms_version();
	            $theme->asset()->add('fontawesome-css', 'https://pro.fontawesome.com/releases/v5.15.4/css/all.css', [], [], '5.15.4');
	            $theme->asset()->usePath()->add('aside-css', 'css/aside.css');
                $theme->asset()->usePath()->add('event-css', 'css/event.css', [], [], time());
	            $theme->asset()->usePath()->add('custom-css', 'css/custom.css', [], [], time());
                $theme->asset()->usePath()->add('responsive-css', 'css/responsive.css', [], [], time());

                $theme->asset()->container('footer')->add('moment-js', 'https://momentjs.com/downloads/moment.min.js', [], [], '2.29.4');
                $theme->asset()->container('footer')->add('translate.google', 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit');
                $theme->asset()->container('footer')->usePath()->add('app-js', 'js/app.js', [], [], time());
            },
            'events' => function ($theme) {
                $version = get_cms_version();
                //$theme->asset()->add('fontawesome-css', 'https://pro.fontawesome.com/releases/v5.15.4/css/all.css', [], [], '5.15.4');
                $theme->asset()->usePath()->add('fontawesome-css', 'awesome/css/all.min.css', [], [], '6.2.0');
                $theme->asset()->usePath()->add('aside-css', 'css/aside.css');
                $theme->asset()->usePath()->add('custom-css', 'css/custom.css', [], [], time());
                $theme->asset()->usePath()->add('responsive-css', 'css/responsive.css', [], [], time());
                $theme->asset()->usePath()->add('event-css', 'css/event.css', [], [], time());
                $theme->asset()->container('footer')->usePath()->add('events-js', 'js/events.js', [], [], time());
            },
            'project' => function ($theme) {
                $version = get_cms_version();
                //$theme->asset()->add('fontawesome-css', 'https://pro.fontawesome.com/releases/v5.15.4/css/all.css', [], [], '5.15.4');
                $theme->asset()->usePath()->add('aside-css', 'css/aside.css');
                $theme->asset()->usePath()->add('custom-css', 'css/custom.css', [], [], time());
                $theme->asset()->usePath()->add('responsive-css', 'css/responsive.css', [], [], time());
                $theme->asset()->container('footer')->usePath()->add('project-js', 'js/project.js', [], [], $version);
            },
            'dashboard' => function ($theme) {
                $version = get_cms_version();
                //$theme->asset()->add('fontawesome-css', 'https://pro.fontawesome.com/releases/v5.15.4/css/all.css', [], [], '5.15.4');
                $theme->asset()->usePath()->add('dashboard-custom-css', 'css/dashboard-custom.css', [], [], time());
                $theme->asset()->usePath()->add('dashboard-responsive-css', 'css/dashboard-responsive.css', [], [], time());

                //$theme->asset()->container('footer')->add('moment-js', 'https://momentjs.com/downloads/moment.min.js', [], [], '2.29.4');
                $theme->asset()->container('footer')->usePath()->add('google-translate-js', 'js/google-translate.js', [], [], '1.0.4');
                $theme->asset()->container('footer')->usePath()->add('dashboard-js', 'js/dashboard.js', [], [], time());
            },
            'account' => function ($theme) {
                $version = get_cms_version();
                $theme->asset()->usePath()->add('dashboard-custom-css', 'css/dashboard-custom.css', [], [], time());
                $theme->asset()->usePath()->add('dashboard-responsive-css', 'css/dashboard-responsive.css', [], [], time());
                $theme->asset()->usePath()->add('my-gems-css', 'css/my-gems.css', [], [], time());

                $theme->asset()->container('footer')->usePath()->add('google-translate-js', 'js/google-translate.js', [], [], '1.0.4');
                $theme->asset()->container('footer')->usePath()->add('account-js', 'js/account.js', [], [], time());
            },
        ]
    ]
];
