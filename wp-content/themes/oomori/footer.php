<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

?>
    <footer>
        <span id="page-top"></span>
        <div class="container">
            <div class="row">
                <div class="footer-content clearfix">
                    <div class="company-contact col-md-5  col-xs-12">
                        <a href="/" class="company-name">Oomori Syouji co.,LTD</a>
                        <ul>
                            <li>大森商事株式会社</li>
                            <li>〒704-8183</li>
                            <li>岡山県岡山市東区西大寺松崎215-22</li>
                            <li><a href="tel:0869423830">TEL 086-942-3830</a></li>
                        </ul>
                    </div>
                    <div class="footer-menu col-md-7 col-xs-12">
                        <nav>
                            <ul class="clearfix">
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
            </div>
        </div>
        <div class="copyright">
            <p>Copyright (C) 2017 大森商事株式会社. All Rights Reserved.</p>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
