# Feeligo GiftBar plugin for SocialEngine 4

## How it Works

This plugin adds the Feeligo GiftBar to your site. The GiftBar is an HTML5 web application which sits at the bottom of your users' screen and displays a gallery of the gifts they have received from their friends.

The GiftBar also enables your users to send Premium or Regular gifts to their friends. Premium gifts cost a certain amount of Credits, which can be purchased with various payment methods.

Gifts increase your members' activity and engagement, and you get a share of the revenue generated by each purchase.
You can monitor purchases on your Dashboard at [feeligo.com/dashboard](http://www.feeligo.com/dashboard).


## Quick automated install (for production use)

  1. Log in or sign up for free at [www.feeligo.com/dashboard](http://www.feeligo.com/dashboard), from where you can download a copy of the stable version of this plugin which will be pre-configured for your site.

  2. Follow the instructions from the download page to install (it's easy and quick).


## Manual install (for development use)

If you are downloading and installing the plugin for development purposes, you can follow these instructions to obtain the latest version of the plugin source code and manually configure it for your development site.

  1. log in or sign up for free at [www.feeligo.com/dashboard](http://www.feeligo.com/dashboard), in order to obtain your API keys. You can select SocialEngine 4 as your platform when asked, but do not download the plug-in from there.

  2. find the source code of this plugin on [GitHub](https://github.com/feeligo/plugin-socialengine-4.0-feeligo).
  If you plan on contributing to the development of this plugin, you may want to fork the repository.

  3. clone the repository inside the root directory of your SocialEngine 4 site, 

        cd YOUR_SITE_ROOT
        git clone --no-checkout git@github.com:Feeligo/plugin-socialengine-4.0-feeligo.git ./application.tmp
        mv ./application.tmp/.git .
        rmdir ./application.tmp
        git reset --hard HEAD
        git submodule update --init

  4. add the lines below at the top of your main `index.php` file. This allows to set up the plugin with your API credentials, without hardcoding them into the plugin.

        // Feeligo development mode settings
        define('FLG__community_api_key', 'YOUR_API_KEY_HERE');
        define('FLG__community_secret' , 'YOUR_SECRET_KEY_HERE');
        define('FLG__server_url'       , 'http://www.feeligo.com/'); // mind the trailing slash!
        define('FLG_ENV'               , 'development'); // remove this line for production use!

  5. log into your database administration interface, and run the SQL code found in 

        application/modules/Feeligo/settings/my-install.sql

  6. log into your SocialEngine 4 admin panel and Activate the plug-in. Also remember to add the Feeligo GiftBar widget to your site's Footer from the Layout Manager.
