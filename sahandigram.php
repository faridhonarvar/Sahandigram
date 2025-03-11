<?php
/*
Plugin Name: Sahandigram
Description: Add Instagram-like features (Like, Save, Comments Count, Share) to WordPress with Elementor widget and shortcodes.
Version: 1.0
Author: You & Grok
License: GPL2
*/

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}

// ثبت استایل‌ها و اسکریپت‌ها
function sahandigram_enqueue_assets() {
    wp_enqueue_style('sahandigram-style', plugins_url('/assets/css/sahandigram.css', __FILE__));
    wp_enqueue_script('sahandigram-script', plugins_url('/assets/js/sahandigram.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('sahandigram-script', 'sahandigram_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sahandigram_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'sahandigram_enqueue_assets');

// اعمال استایل‌های تنظیمات برای شورت‌کدها
function sahandigram_custom_styles() {
    $count_color = get_option('sahandigram_count_color', '#262626');
    $count_spacing = get_option('sahandigram_count_spacing', '10');
    $vertical_align = get_option('sahandigram_vertical_align', 'middle');
    $icon_color = get_option('sahandigram_icon_color', '#262626');
    $icon_liked_color = get_option('sahandigram_icon_liked_color', '#ed4956');
    $icon_saved_color = get_option('sahandigram_icon_saved_color', '#ed4956');
    $share_icon_color = get_option('sahandigram_share_icon_color', '#262626');
    $social_icon_color = get_option('sahandigram_social_icon_color', '#262626');
    $social_spacing = get_option('sahandigram_social_spacing', '10');
    $social_layout = get_option('sahandigram_social_layout', 'horizontal');
    $icon_size = get_option('sahandigram_icon_size', '18');
    $mobile_font_size = get_option('sahandigram_mobile_font_size', '14');
    
    echo "<style>
        .sahandigram-like, .sahandigram-save, .sahandigram-comments, .sahandigram-share {
            display: inline-flex;
            align-items: center;
            direction: rtl;
            margin: 0 !important; /* حذف فاصله‌های اضافی */
        }
        .sahandigram-like .like-count, .sahandigram-comments .comments-count {
            color: $count_color !important;
            margin-right: {$count_spacing}px !important;
            vertical-align: $vertical_align;
        }
        .sahandigram-like i, .sahandigram-save i, .sahandigram-comments i, .sahandigram-share i.fa-share {
            color: $icon_color !important;
            font-size: {$icon_size}px !important;
        }
        .sahandigram-like i.liked {
            color: $icon_liked_color !important;
        }
        .sahandigram-save i.saved {
            color: $icon_saved_color !important;
        }
        .sahandigram-share i.fa-share {
            color: $share_icon_color !important;
        }
        .sahandigram-share .share-options a i {
            color: $social_icon_color !important;
            font-size: {$icon_size}px !important;
        }
        .sahandigram-share .share-options {
            display: " . ($social_layout == 'vertical' ? 'block' : 'flex') . ";
        }
        .sahandigram-share .share-options a {
            " . ($social_layout == 'vertical' ? "margin-bottom: {$social_spacing}px !important;" : "margin: 0 {$social_spacing}px !important;") . "
        }
        @media (max-width: 767px) {
            .sahandigram-like i, .sahandigram-save i, .sahandigram-comments i, .sahandigram-share i {
                font-size: {$mobile_font_size}px !important;
            }
        }
    </style>";
}
add_action('wp_head', 'sahandigram_custom_styles');

// شورت‌کد لایک
function sahandigram_like_shortcode($atts) {
    $post_id = get_the_ID();
    $likes = get_post_meta($post_id, '_sahandigram_likes', true) ?: 0;
    $liked = is_user_logged_in() && in_array(get_current_user_id(), get_post_meta($post_id, '_sahandigram_liked_users', true) ?: array());
    $icon_class = $liked ? 'fas fa-heart liked' : 'far fa-heart';
    return '<span class="sahandigram-like" data-post-id="' . $post_id . '"><span class="like-count">' . $likes . '</span><i class="' . $icon_class . '"></i></span>';
}
add_shortcode('sahandigram_like', 'sahandigram_like_shortcode');

// شورت‌کد ذخیره
function sahandigram_save_shortcode($atts) {
    if (!is_user_logged_in()) return '';
    $post_id = get_the_ID();
    $saved = in_array($post_id, get_user_meta(get_current_user_id(), '_sahandigram_saved_posts', true) ?: array());
    $icon_class = $saved ? 'fas fa-bookmark saved' : 'far fa-bookmark';
    return '<span class="sahandigram-save" data-post-id="' . $post_id . '"><i class="' . $icon_class . '"></i></span>';
}
add_shortcode('sahandigram_save', 'sahandigram_save_shortcode');

// شورت‌کد تعداد نظرات
function sahandigram_comments_shortcode($atts) {
    $post_id = get_the_ID();
    $comments_count = get_comments_number($post_id);
    return '<span class="sahandigram-comments"><span class="comments-count">' . $comments_count . '</span><i class="far fa-comment"></i></span>';
}
add_shortcode('sahandigram_comments', 'sahandigram_comments_shortcode');

// شورت‌کد اشتراک
function sahandigram_share_shortcode($atts) {
    $post_id = get_the_ID();
    $url = urlencode(get_permalink($post_id));
    $title = urlencode(get_the_title($post_id));
    return '
    <span class="sahandigram-share">
        <i class="fas fa-share"></i>
        <div class="share-options" style="display: none;">
            <a href="https://t.me/share/url?url=' . $url . '&text=' . $title . '" target="_blank"><i class="fab fa-telegram"></i></a>
            <a href="https://wa.me/?text=' . $title . '%20' . $url . '" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.instagram.com/?url=' . $url . '" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="sms:?body=' . $title . '%20' . $url . '"><i class="fas fa-sms"></i></a>
        </div>
    </span>';
}
add_shortcode('sahandigram_share', 'sahandigram_share_shortcode');

// AJAX برای لایک
function sahandigram_like_post() {
    check_ajax_referer('sahandigram_nonce', 'nonce');
    $post_id = intval($_POST['post_id']);
    $user_id = get_current_user_id();
    $liked_users = get_post_meta($post_id, '_sahandigram_liked_users', true) ?: array();
    
    if (in_array($user_id, $liked_users)) {
        $likes = get_post_meta($post_id, '_sahandigram_likes', true) - 1;
        $liked_users = array_diff($liked_users, array($user_id));
    } else {
        $likes = get_post_meta($post_id, '_sahandigram_likes', true) + 1;
        $liked_users[] = $user_id;
    }
    
    update_post_meta($post_id, '_sahandigram_likes', $likes);
    update_post_meta($post_id, '_sahandigram_liked_users', $liked_users);
    wp_send_json_success(array('likes' => $likes));
}
add_action('wp_ajax_sahandigram_like', 'sahandigram_like_post');

// AJAX برای ذخیره
function sahandigram_save_post() {
    check_ajax_referer('sahandigram_nonce', 'nonce');
    $post_id = intval($_POST['post_id']);
    $user_id = get_current_user_id();
    $saved_posts = get_user_meta($user_id, '_sahandigram_saved_posts', true) ?: array();
    
    if (in_array($post_id, $saved_posts)) {
        $saved_posts = array_diff($saved_posts, array($post_id));
    } else {
        $saved_posts[] = $post_id;
    }
    
    update_user_meta($user_id, '_sahandigram_saved_posts', $saved_posts);
    wp_send_json_success();
}
add_action('wp_ajax_sahandigram_save', 'sahandigram_save_post');

// ثبت ویجت المنتور
function sahandigram_register_elementor_widgets($widgets_manager) {
    require_once plugin_dir_path(__FILE__) . 'widgets/social-widget.php';
    $widgets_manager->register(new \Sahandigram_Social_Widget());
}
add_action('elementor/widgets/register', 'sahandigram_register_elementor_widgets');

// فعال‌سازی Font Awesome
function sahandigram_load_fontawesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'sahandigram_load_fontawesome');

// منوی فعالیت‌ها در ووکامرس
function sahandigram_add_my_activity_endpoint() {
    add_rewrite_endpoint('my-activity', EP_ROOT | EP_PAGES);
}
add_action('init', 'sahandigram_add_my_activity_endpoint');

function sahandigram_my_activity_menu($items) {
    $items['my-activity'] = 'فعالیت‌های من';
    return $items;
}
add_filter('woocommerce_account_menu_items', 'sahandigram_my_activity_menu');

function sahandigram_my_activity_content() {
    $paged = max(1, get_query_var('paged') ?: (isset($_GET['paged']) ? $_GET['paged'] : 1));
    ?>
    <div class="sahandigram-activity-tabs">
        <ul class="tabs">
            <li><a href="#saved" class="tab-link active">ذخیره‌شده‌ها</a></li>
            <li><a href="#liked" class="tab-link">لایک‌شده‌ها</a></li>
            <li><a href="#comments" class="tab-link">نظرات من</a></li>
        </ul>
        <div class="tab-content-wrapper">
            <div id="saved" class="tab-content active">
                <?php
                $saved_posts = get_user_meta(get_current_user_id(), '_sahandigram_saved_posts', true) ?: array();
                if (!empty($saved_posts)) {
                    $query = new WP_Query(array(
                        'post__in' => $saved_posts,
                        'posts_per_page' => 10,
                        'paged' => $paged,
                        'post_type' => array('post', 'product'),
                        'ignore_sticky_posts' => true,
                    ));
                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            ?>
                            <div class="tab-item">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <span><?php echo get_the_date(); ?></span>
                            </div>
                            <?php
                        endwhile;
                        echo '<div class="sahandigram-pagination" data-tab="saved" data-max="' . $query->max_num_pages . '"></div>';
                        wp_reset_postdata();
                    } else {
                        echo '<p class="no-items">هنوز چیزی ذخیره نکرده‌اید!</p>';
                    }
                } else {
                    echo '<p class="no-items">هنوز چیزی ذخیره نکرده‌اید!</p>';
                }
                ?>
            </div>
            <div id="liked" class="tab-content">
                <?php
                $liked_posts = array();
                $all_posts = get_posts(array('posts_per_page' => -1, 'post_type' => array('post', 'product')));
                foreach ($all_posts as $post) {
                    $liked_users = get_post_meta($post->ID, '_sahandigram_liked_users', true) ?: array();
                    if (in_array(get_current_user_id(), $liked_users)) {
                        $liked_posts[] = $post->ID;
                    }
                }
                if (!empty($liked_posts)) {
                    $query = new WP_Query(array(
                        'post__in' => $liked_posts,
                        'posts_per_page' => 10,
                        'paged' => $paged,
                        'post_type' => array('post', 'product'),
                        'ignore_sticky_posts' => true,
                    ));
                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            ?>
                            <div class="tab-item">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <span><?php echo get_the_date(); ?></span>
                            </div>
                            <?php
                        endwhile;
                        echo '<div class="sahandigram-pagination" data-tab="liked" data-max="' . $query->max_num_pages . '"></div>';
                        wp_reset_postdata();
                    } else {
                        echo '<p class="no-items">هنوز چیزی لایک نکرده‌اید!</p>';
                    }
                } else {
                    echo '<p class="no-items">هنوز چیزی لایک نکرده‌اید!</p>';
                }
                ?>
            </div>
            <div id="comments" class="tab-content">
                <?php
                $comments = get_comments(array(
                    'user_id' => get_current_user_id(),
                    'number' => 10,
                    'paged' => $paged,
                ));
                $total_comments = count(get_comments(array('user_id' => get_current_user_id())));
                $max_pages = ceil($total_comments / 10);
                if (!empty($comments)) {
                    foreach ($comments as $comment) {
                        ?>
                        <div class="tab-item">
                            <p><?php echo esc_html($comment->comment_content); ?></p>
                            <a href="<?php echo get_permalink($comment->comment_post_ID); ?>">
                                <?php echo get_the_title($comment->comment_post_ID); ?>
                            </a>
                            <span><?php echo get_comment_date('', $comment->comment_ID); ?></span>
                        </div>
                        <?php
                    }
                    echo '<div class="sahandigram-pagination" data-tab="comments" data-max="' . $max_pages . '"></div>';
                } else {
                    echo '<p class="no-items">هنوز نظری ثبت نکرده‌اید!</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <style>
        .sahandigram-activity-tabs { max-width: 800px; margin: 20px auto; }
        .tabs { list-style: none; display: flex; gap: 20px; padding: 0; border-bottom: 2px solid #eee; margin-bottom: 20px; }
        .tabs .tab-link { text-decoration: none; color: #666; padding: 10px 15px; transition: all 0.3s ease; position: relative; }
        .tabs .tab-link.active { color: #ed4956; font-weight: bold; }
        .tabs .tab-link.active:after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px; background: #ed4956; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-item { background: #f9f9f9; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: all 0.3s ease; }
        .tab-item:hover { background: #f1f1f1; transform: translateY(-2px); }
        .tab-item a { color: #333; text-decoration: none; font-weight: 500; }
        .tab-item span { display: block; color: #999; font-size: 12px; margin-top: 5px; }
        .no-items { text-align: center; color: #666; padding: 20px; }
        .sahandigram-pagination { text-align: center; margin-top: 20px; }
        .sahandigram-pagination a { padding: 5px 10px; margin: 0 5px; background: #eee; text-decoration: none; color: #333; border-radius: 5px; }
        .sahandigram-pagination a.active { background: #ed4956; color: #fff; }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('.sahandigram-activity-tabs .tab-link').click(function(e) {
                e.preventDefault();
                var tab = $(this).attr('href');
                $('.sahandigram-activity-tabs .tab-content').removeClass('active');
                $('.sahandigram-activity-tabs .tab-link').removeClass('active');
                $(tab).addClass('active');
                $(this).addClass('active');
                loadPagination(tab.slice(1), 1);
            });

            function loadPagination(tab, page) {
                var $pagination = $('.sahandigram-pagination[data-tab="' + tab + '"]');
                var max = parseInt($pagination.data('max'));
                if (max > 1) {
                    var html = '';
                    for (var i = 1; i <= max; i++) {
                        html += '<a href="#" data-page="' + i + '" class="' + (i == page ? 'active' : '') + '">' + i + '</a>';
                    }
                    $pagination.html(html);
                } else {
                    $pagination.empty();
                }
            }

            $('.sahandigram-pagination').on('click', 'a', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                var tab = $(this).closest('.sahandigram-pagination').data('tab');
                $.ajax({
                    url: sahandigram_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sahandigram_load_tab',
                        tab: tab,
                        page: page,
                        nonce: sahandigram_ajax.nonce
                    },
                    success: function(response) {
                        $('#' + tab).html(response);
                        loadPagination(tab, page);
                    }
                });
            });

            loadPagination('saved', 1);
        });
    </script>
    <?php
}
add_action('woocommerce_account_my-activity_endpoint', 'sahandigram_my_activity_content');

// AJAX برای صفحه‌بندی
function sahandigram_load_tab() {
    check_ajax_referer('sahandigram_nonce', 'nonce');
    $tab = sanitize_text_field($_POST['tab']);
    $page = intval($_POST['page']);
    ob_start();
    
    if ($tab == 'saved') {
        $saved_posts = get_user_meta(get_current_user_id(), '_sahandigram_saved_posts', true) ?: array();
        if (!empty($saved_posts)) {
            $query = new WP_Query(array(
                'post__in' => $saved_posts,
                'posts_per_page' => 10,
                'paged' => $page,
                'post_type' => array('post', 'product'),
                'ignore_sticky_posts' => true,
            ));
            if ($query->have_posts()) {
                while ($query->have_posts()) : $query->the_post();
                    ?>
                    <div class="tab-item">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span><?php echo get_the_date(); ?></span>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            }
        }
    } elseif ($tab == 'liked') {
        $liked_posts = array();
        $all_posts = get_posts(array('posts_per_page' => -1, 'post_type' => array('post', 'product')));
        foreach ($all_posts as $post) {
            $liked_users = get_post_meta($post->ID, '_sahandigram_liked_users', true) ?: array();
            if (in_array(get_current_user_id(), $liked_users)) {
                $liked_posts[] = $post->ID;
            }
        }
        if (!empty($liked_posts)) {
            $query = new WP_Query(array(
                'post__in' => $liked_posts,
                'posts_per_page' => 10,
                'paged' => $page,
                'post_type' => array('post', 'product'),
                'ignore_sticky_posts' => true,
            ));
            if ($query->have_posts()) {
                while ($query->have_posts()) : $query->the_post();
                    ?>
                    <div class="tab-item">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span><?php echo get_the_date(); ?></span>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            }
        }
    } elseif ($tab == 'comments') {
        $comments = get_comments(array(
            'user_id' => get_current_user_id(),
            'number' => 10,
            'paged' => $page,
        ));
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                ?>
                <div class="tab-item">
                    <p><?php echo esc_html($comment->comment_content); ?></p>
                    <a href="<?php echo get_permalink($comment->comment_post_ID); ?>">
                        <?php echo get_the_title($comment->comment_post_ID); ?>
                    </a>
                    <span><?php echo get_comment_date('', $comment->comment_ID); ?></span>
                </div>
                <?php
            }
        }
    }
    
    echo ob_get_clean();
    wp_die();
}
add_action('wp_ajax_sahandigram_load_tab', 'sahandigram_load_tab');

// پنل تنظیمات
function sahandigram_settings_menu() {
    add_options_page('تنظیمات سهندیگرام', 'تنظیمات سهندیگرام', 'manage_options', 'sahandigram-settings', 'sahandigram_settings_page');
}
add_action('admin_menu', 'sahandigram_settings_menu');

function sahandigram_register_settings() {
    register_setting('sahandigram_settings_group', 'sahandigram_count_color');
    register_setting('sahandigram_settings_group', 'sahandigram_count_spacing');
    register_setting('sahandigram_settings_group', 'sahandigram_vertical_align');
    register_setting('sahandigram_settings_group', 'sahandigram_icon_color');
    register_setting('sahandigram_settings_group', 'sahandigram_icon_liked_color');
    register_setting('sahandigram_settings_group', 'sahandigram_icon_saved_color');
    register_setting('sahandigram_settings_group', 'sahandigram_share_icon_color');
    register_setting('sahandigram_settings_group', 'sahandigram_social_icon_color');
    register_setting('sahandigram_settings_group', 'sahandigram_social_spacing');
    register_setting('sahandigram_settings_group', 'sahandigram_social_layout');
    register_setting('sahandigram_settings_group', 'sahandigram_icon_size');
    register_setting('sahandigram_settings_group', 'sahandigram_mobile_font_size');
}
add_action('admin_init', 'sahandigram_register_settings');

function sahandigram_settings_page() {
    ?>
    <div class="wrap">
        <h1>تنظیمات سهندیگرام</h1>
        <form method="post" action="options.php">
            <?php settings_fields('sahandigram_settings_group'); ?>
            <h2>تنظیمات عمومی</h2>
            <table class="form-table">
                <tr>
                    <th><label for="sahandigram_count_color">رنگ تعداد</label></th>
                    <td>
                        <input type="text" name="sahandigram_count_color" id="sahandigram_count_color" 
                               value="<?php echo esc_attr(get_option('sahandigram_count_color', '#262626')); ?>" 
                               class="regular-text" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_count_spacing">فاصله تعداد از آیکون (px)</label></th>
                    <td>
                        <input type="number" name="sahandigram_count_spacing" id="sahandigram_count_spacing" 
                               value="<?php echo esc_attr(get_option('sahandigram_count_spacing', '10')); ?>" 
                               min="0" max="50" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_vertical_align">تراز عمودی</label></th>
                    <td>
                        <select name="sahandigram_vertical_align" id="sahandigram_vertical_align">
                            <option value="top" <?php selected(get_option('sahandigram_vertical_align', 'middle'), 'top'); ?>>بالا</option>
                            <option value="middle" <?php selected(get_option('sahandigram_vertical_align', 'middle'), 'middle'); ?>>وسط</option>
                            <option value="bottom" <?php selected(get_option('sahandigram_vertical_align', 'middle'), 'bottom'); ?>>پایین</option>
                        </select>
                    </td>
                </tr>
            </table>
            <h2>تنظیمات آیکون‌ها</h2>
            <table class="form-table">
                <tr>
                    <th><label for="sahandigram_icon_color">رنگ آیکون (حالت عادی)</label></th>
                    <td>
                        <input type="text" name="sahandigram_icon_color" id="sahandigram_icon_color" 
                               value="<?php echo esc_attr(get_option('sahandigram_icon_color', '#262626')); ?>" 
                               class="regular-text" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_icon_liked_color">رنگ آیکون لایک‌شده</label></th>
                    <td>
                        <input type="text" name="sahandigram_icon_liked_color" id="sahandigram_icon_liked_color" 
                               value="<?php echo esc_attr(get_option('sahandigram_icon_liked_color', '#ed4956')); ?>" 
                               class="regular-text" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_icon_saved_color">رنگ آیکون ذخیره‌شده</label></th>
                    <td>
                        <input type="text" name="sahandigram_icon_saved_color" id="sahandigram_icon_saved_color" 
                               value="<?php echo esc_attr(get_option('sahandigram_icon_saved_color', '#ed4956')); ?>" 
                               class="regular-text" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_share_icon_color">رنگ آیکون شئر</label></th>
                    <td>
                        <input type="text" name="sahandigram_share_icon_color" id="sahandigram_share_icon_color" 
                               value="<?php echo esc_attr(get_option('sahandigram_share_icon_color', '#262626')); ?>" 
                               class="regular-text" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_social_icon_color">رنگ آیکون‌های سوشال</label></th>
                    <td>
                        <input type="text" name="sahandigram_social_icon_color" id="sahandigram_social_icon_color" 
                               value="<?php echo esc_attr(get_option('sahandigram_social_icon_color', '#262626')); ?>" 
                               class="regular-text" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_social_spacing">فاصله آیکون‌های سوشال (px)</label></th>
                    <td>
                        <input type="number" name="sahandigram_social_spacing" id="sahandigram_social_spacing" 
                               value="<?php echo esc_attr(get_option('sahandigram_social_spacing', '10')); ?>" 
                               min="0" max="50" style="width: 100px;" />
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_social_layout">چیدمان آیکون‌های سوشال</label></th>
                    <td>
                        <select name="sahandigram_social_layout" id="sahandigram_social_layout">
                            <option value="horizontal" <?php selected(get_option('sahandigram_social_layout', 'horizontal'), 'horizontal'); ?>>افقی</option>
                            <option value="vertical" <?php selected(get_option('sahandigram_social_layout', 'horizontal'), 'vertical'); ?>>عمودی</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="sahandigram_icon_size">اندازه آیکون‌ها (px)</label></th>
                    <td>
                        <input type="number" name="sahandigram_icon_size" id="sahandigram_icon_size" 
                               value="<?php echo esc_attr(get_option('sahandigram_icon_size', '18')); ?>" 
                               min="10" max="50" style="width: 100px;" />
                    </td>
                </tr>
            </table>
            <h2>تنظیمات واکنش‌گرا</h2>
            <table class="form-table">
                <tr>
                    <th><label for="sahandigram_mobile_font_size">اندازه آیکون در موبایل (px)</label></th>
                    <td>
                        <input type="number" name="sahandigram_mobile_font_size" id="sahandigram_mobile_font_size" 
                               value="<?php echo esc_attr(get_option('sahandigram_mobile_font_size', '14')); ?>" 
                               min="10" max="30" style="width: 100px;" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}