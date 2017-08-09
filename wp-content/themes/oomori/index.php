<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

<div class="content-wrap contentWrap">
    <div id="header_wrap"></div>
    <div class="company-fifty-year container">
        <div class="title row">
            <img src="<?php echo get_template_directory_uri() . '/img/logo-title.png' ?>" alt="">
            <p class="col-md-12">岡山で不動産ひと筋50年<br>だからこその提案力</p>
        </div>
        <div class="images row">
            <div class="col-sm-4">
                <div class="fifty-year-image text-center">
                    <a href="#"><img src="<?php echo get_template_directory_uri() . '/img/index-img-company-fifty-year-01.png' ?>" alt=""></a>
                </div>
                <p class="fifty-year-image-description">昭和◯◯年から続く<a href="#">歴史</a></p>
            </div>
            <div class="col-sm-4">
                <div class="fifty-year-image text-center">
                    <a href="#"><img src="<?php echo get_template_directory_uri() . '/img/index-img-company-fifty-year-02.png' ?>" alt=""></a>
                </div>
                <p class="fifty-year-image-description">岡山市中区・東区の物件に特化した<br class="line-break hidden-sm"><a href="#">地域密着</a></p>
            </div>
            <div class="col-sm-4">
                <div class="fifty-year-image text-center">
                    <a href="#"><img src="<?php echo get_template_directory_uri() . '/img/index-img-company-fifty-year-03.png' ?>" alt=""></a>
                </div>
                <p class="fifty-year-image-description">賃貸・売買から資産コンサルティング<br class="line-break hidden-sm">まで幅広く<a href="#">サポート</a></p>
            </div>
        </div>
        <div class="button-link row">
            <a href="/company/company.html" target="_blank" class="block-btn">大森商事について <img src="<?php echo get_template_directory_uri() . '/img/icn-arrow-right.png' ?>" alt=""></a>
        </div>
    </div>
    <div class="membership clearfix">
        <div class="list-oomoris">
            <p>OOMORISYOUJI CO.,LTD</p>
        </div>
    </div>
    <div class="introduce container">
        <div class="title row">
            <img src="<?php echo get_template_directory_uri() . '/img/logo-title.png' ?>" alt="">
            <p class="col-md-12">プロパティマネジメントで<br> 資産価値・収益を最大化</p>
        </div>
        <div class="content row">
            <div class="col-md-6">
                <p>大森商事は、○○○○に力を入れています。<br>
                    テキストが入ります。テキストが入ります。テキストが入ります。テキストが入ります。テキテキストが入ります。テキストが入ります。テキストが入ります。テキストが入ります。テキテキストが入ります。テキストが入ります。テキストが入ります。テキストが入ります。テキ</p>
            </div>
            <div class="col-md-6">
                <img src="<?php echo get_template_directory_uri() . '/img/index-img-introduce.png' ?>" alt="">
            </div>
        </div>
    </div>
    <div class="news">&nbsp;
        <div class="container">
            <div class="title row">
                <img src="<?php echo get_template_directory_uri() . '/img/logo-title.png' ?>" alt="">
                <p class="col-md-12">お知らせ</p>
            </div>
            <div class="notice row">
                <ul>
                    <?php
                    if ( have_posts() ) {

                        $args = array(
                            'posts_per_page' => 4,
                            'cat' => 12,
                            'post_type' => 'post'
                        );

                        $the_query = new WP_Query($args);
                        $html = '';
                        // The Loop
                        if ($the_query->have_posts()) :
                            while ($the_query->have_posts()) : $the_query->the_post();
                                $html .= '<li>';
                                $html .= '<a href="#" class="category">' . get_the_title() . '</a>';
                                $html .= '<span class="date">' . get_the_date() . '</span>';
                                $html .= '<span class="title"><br class="visible-xs visible-sm"><a href="#">' . get_the_content() . '</a></span>';
                                $html .= '</li>';
                            endwhile;
                            echo $html;
                        endif;
                        // Reset Post Data
                        wp_reset_postdata();
                    }
                    ?>
                </ul>
            </div>
            <div class="btn-category row">
                <a href="#" class="block-btn">一覧を見る <img src="<?php echo get_template_directory_uri() . '/img/icn-arrow-right.png' ?>" alt=""></a>
            </div>
        </div>&nbsp;
    </div>
    <div class="business container" id="business">
        <div class="title row">
            <img src="<?php echo get_template_directory_uri() . '/img/logo-title.png' ?>" alt="">
            <p class="col-md-12">事業内容</p>
        </div>
        <div class="categories row">
            <?php
            global $post;
            $args = ['posts_per_page' => 8,
                     'category'  => 11,
                     'post_type'    => 'post',
                     'orderby'        => 'date',
                     'order'          => 'ASC',];
            $html = "";
            $myposts = get_posts($args);
            foreach ($myposts as $post) {
                setup_postdata($post);
                $html = "<div class='col-md-3 col-sm-6 col-xs-12 category'>";
                $html .= "<a href='#'>".get_the_post_thumbnail()."</a>";
                $html .= "<p class='name'>" . $post->post_title . "</p>";
                $html .= "<p class='content'>". $post->post_content ."</p>";
                $html .= "</div>";
                echo $html;
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <div class="store-information" id="store-information">
        <div class="container-fluid top-info">
            <div class="title row">
                <img src="<?php echo get_template_directory_uri() . '/img/logo-title.png' ?>" alt="">
                <p class="col-sm-12">店舗情報</p>
            </div>
        </div>
        <div class="container last-info">
            <div class="content row">
                <div class="content-left col-sm-6">
                    <div class="top col-sm-12">
                        <div class="img-store">
                            <img src="<?php echo get_template_directory_uri() . '/img/index-img-store-info-02.png' ?>" alt="">
                            <div class="title-img">
                                <p>大森商事株式会社　東区店</p>
                            </div>
                        </div>
                        <div class="text">
                            <p>
                                〒704-8183<br>
                                岡山県岡山市東区西大寺松崎215-22<br>
                                9:00～18:00<br>
                                10:00～17:00（日曜）
                            </p>
                        </div>
                        <div class="tel">
                            <a href="tel:0869423830"><span>TEL</span> 086-942-3830</a>
                        </div>
                    </div>
                    <div class="map col-sm-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6563.745281977914!2d134.011636!3d34.6579192!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9d8e13fff6dd591a!2z5aSn5qOu5ZWG5LqL5qCq5byP5Lya56S-!5e0!3m2!1sja!2sjp!4v1501583862161" width="100%" height="260" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="content-right col-sm-6">
                    <div class="top col-sm-12">
                        <div class="img-store">
                            <img src="<?php echo get_template_directory_uri() . '/img/index-img-store-info-01.png' ?>" alt="">
                            <div class="title-img">
                                <p>大森商事株式会社　中区店</p>
                            </div>
                        </div>
                        <div class="text">
                            <p>
                                〒703-8271<br>
                                岡山県岡山市中区円山79-16<br>
                                9:00～18:00<br>
                                10:00～17:00（日曜）
                            </p>
                        </div>
                        <div class="tel">
                            <a href="tel:0862003830"><span>TEL</span> 086-200-3830</a>
                        </div>
                    </div>
                    <div class="map col-sm-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6564.081301549291!2d133.9656159!3d34.6536763!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x355408eeba987879%3A0xde4af21bfd2c81f5!2z5aSn5qOu5ZWG5LqL5qCq5byP5Lya56S-IOS4reWMuuW6lw!5e0!3m2!1sja!2sjp!4v1501583823213" width="100%" height="260" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="advertisement container">
        <div class="row">
            <a href="#" class="col-xs-12 bn-oomorisyouji">
                <img src="<?php echo get_template_directory_uri() . '/img/img-advertisement-01.png' ?>" alt="">
            </a>
        </div>
        <div class="row">
            <a href="#" class="col-sm-6 col-xs-12 left">
                <img src="<?php echo get_template_directory_uri() . '/img/img-advertisement-02.png' ?>" alt="">
            </a>
            <a href="#" class="col-sm-6 col-xs-12 right">
                <img src="<?php echo get_template_directory_uri() . '/img/img-advertisement-03.png' ?>" alt="">
            </a>
        </div>
    </div>
    <div class="brand-group">
        <div class="container">
            <p class="name">加入団体</p>
            <ul class="clearfix">
                <li>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() . '/img/index-icn-brand-01.png' ?>" alt="">
                        <p>（公社）岡山県宅地建物取引業協会</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() .  '/img/index-icn-brand-02.png' ?>" alt="">
                        <p>（公社）全国宅地建物取引業保証協会</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() .  '/img/index-icn-brand-03.png' ?>" alt="">
                        <p>（一社）全国賃貸管理ビジネス協会</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() .  '/img/index-icn-brand-04.png' ?>" alt="">
                        <p>IREM JAPAN</p>
                    </a>
                </li>
                <li class="brand-last">
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() .  '/img/index-icn-brand-05.png' ?>" alt="">
                        <p>Agent 7</p>
                    </a>
                </li>
                <li class="brand-last">
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() .  '/img/index-icn-brand-06.png' ?>" alt="">
                        <p>リノベーション住宅推進協議会</p>
                    </a>
                </li>
                <li class="brand-last">
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() .  '/img/index-icn-brand-07.png' ?>" alt="">
                        <p>JPMC 日本管理センター</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php
    get_footer();
    ?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/scroll.js'?>"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/slide.js'?>"></script>
