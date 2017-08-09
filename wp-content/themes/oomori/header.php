<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/nav.css'?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/reset.css'?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/slide.css'?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/statics/stylus/css/style.css'?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/bootstrap.min.css'?>">
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/jquery.min.js'?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/bootstrap.min.js'?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/active-menu.js'?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/common.js'?>"></script>
<!--<script type="text/javascript" src="--><?php //echo get_template_directory_uri() . '/js/customizer.js'?><!--"></script>-->
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/nav.js'?>"></script>
</head>

<body <?php body_class(); ?>>
    <div id="<?php echo is_page('company') ? 'company' : 'home'?>" class="wrapper">
        <header>
            <div class="pc-content">
                <div class="headerInner">
                    <div class="headerGlobalContent container clearfix">
                        <div class="col-md-4">
                            <h1 id="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btnOn"><img src="<?php echo get_template_directory_uri() . '/img/header-logo.png' ?>" alt=""></a></h1>
                        </div>
                        <div class="col-sm-8">
                            <ul>
	                            <?php
	                            $mainMenuItems = wp_get_nav_menu_items('header-menu');

	                            foreach ($mainMenuItems as $item) {
		                            ?>
                                    <li>
                                        <a href="<?php echo get_home_url().$item->url ?>" <?php echo strpos($item->url, '#') !== false ? "class = 'scroll-link'" : ""?>><?php echo $item->title ?></a>
                                    </li>
		                            <?php
	                            }
	                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sp-content">
                <div class="headerInner clearfix" id="headerInner">
                    <div class="box-head">
                        <h1 id="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri() . '/img/header-logo.png' ?>" alt=""></a></h1>
                        <nav class="spMenu">
                            <a class="menu-trigger" href="#">
                                <img src="<?php echo get_template_directory_uri() . '/img/sp_menu_trigger.png' ?>" alt="">
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="headerGlobalNavi">
                    <nav>
                        <ul class="globalNavi">
	                        <?php
	                        $mainMenuItems = wp_get_nav_menu_items('header-menu');

	                        foreach ($mainMenuItems as $item) {
		                        ?>
                                <li>
                                    <a href="<?php echo get_home_url().$item->url ?>"><?php echo $item->title ?></a>
                                </li>
		                        <?php
	                        }
	                        ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
