<?php
class Sahandigram_Social_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'sahandigram_social'; }
    public function get_title() { return 'Sahandigram Social'; }
    public function get_icon() { return 'eicon-social-icons'; }
    public function get_categories() { return ['basic']; }

    protected function register_controls() {
        // بخش محتوا
        $this->start_controls_section('content_section', [
            'label' => 'محتوا',
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('order_note', [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => 'ترتیب نمایش آیکون‌ها رو با عدد مشخص کنید (1 تا 5). عدد کمتر یعنی نمایش زودتر.',
        ]);
        
        $this->add_control('like_order', [
            'label' => 'ترتیب لایک',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 1,
        ]);
        
        $this->add_control('save_order', [
            'label' => 'ترتیب ذخیره',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 2,
        ]);
        
        $this->add_control('comments_order', [
            'label' => 'ترتیب نظرات',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 3,
        ]);
        
        $this->add_control('share_order', [
            'label' => 'ترتیب شئر',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 4,
        ]);
        
        $this->add_control('views_order', [
            'label' => 'ترتیب بازدید',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 5,
        ]);
        
        $this->add_control('show_like', [
            'label' => 'نمایش لایک',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_save', [
            'label' => 'نمایش ذخیره',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_comments', [
            'label' => 'نمایش تعداد نظرات',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_share', [
            'label' => 'نمایش شئر',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_views', [
            'label' => 'نمایش بازدید',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'yes',
        ]);
        
        $this->add_control('short_number_format', [
            'label' => 'نمایش اعداد به‌صورت کوتاه (K/M)',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'yes',
        ]);
        
        $this->add_control('enable_animation', [
            'label' => 'فعال‌سازی انیمیشن',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'بله',
            'label_off' => 'خیر',
            'default' => 'no',
        ]);
        
        $this->end_controls_section();

        // بخش استایل عمومی
        $this->start_controls_section('style_general', [
            'label' => 'استایل عمومی',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('vertical_align', [
            'label' => 'تراز عمودی',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'top' => 'بالا',
                'middle' => 'وسط',
                'bottom' => 'پایین',
            ],
            'default' => 'middle',
            'selectors' => [
                '{{WRAPPER}} .sahandigram-social .like-count, {{WRAPPER}} .sahandigram-social .comments-count, {{WRAPPER}} .sahandigram-social .views-count' => 'vertical-align: {{VALUE}};',
            ],
        ]);
        
        $this->add_responsive_control('horizontal_align', [
            'label' => 'تراز افقی',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'flex-start' => 'راست',
                'center' => 'وسط',
                'flex-end' => 'چپ',
                'space-between' => 'فاصله‌دار',
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .sahandigram-social' => 'justify-content: {{VALUE}};',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_responsive_control('layout', [
            'label' => 'چیدمان',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'row' => 'افقی',
                'column' => 'عمودی',
            ],
            'default' => 'row',
            'selectors' => [
                '{{WRAPPER}} .sahandigram-social' => 'display: flex; flex-direction: {{VALUE}}; align-items: flex-start;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->end_controls_section();

        // استایل لایک
        $this->start_controls_section('style_like', [
            'label' => 'استایل لایک',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_like' => 'yes'],
        ]);
        
        $this->add_control('like_count_color', [
            'label' => 'رنگ تعداد',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-like .like-count' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('like_count_spacing', [
            'label' => 'فاصله از آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 5, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-like .like-count' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_responsive_control('like_margin', [
            'label' => 'فاصله کل لایک',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-like' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_control('like_icon_color', [
            'label' => 'رنگ آیکون (حالت عادی)',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-like i' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('like_icon_liked_color', [
            'label' => 'رنگ آیکون لایک‌شده',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ed4956',
            'selectors' => ['{{WRAPPER}} .sahandigram-like i.liked' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('like_icon_size', [
            'label' => 'اندازه آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .sahandigram-like i' => 'font-size: {{SIZE}}{{UNIT}} !important;'],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->end_controls_section();

        // استایل ذخیره
        $this->start_controls_section('style_save', [
            'label' => 'استایل ذخیره',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_save' => 'yes'],
        ]);
        
        $this->add_responsive_control('save_margin', [
            'label' => 'فاصله کل ذخیره',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-save' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_control('save_icon_color', [
            'label' => 'رنگ آیکون (حالت عادی)',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-save i' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('save_icon_saved_color', [
            'label' => 'رنگ آیکون ذخیره‌شده',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ed4956',
            'selectors' => ['{{WRAPPER}} .sahandigram-save i.saved' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('save_icon_size', [
            'label' => 'اندازه آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .sahandigram-save i' => 'font-size: {{SIZE}}{{UNIT}} !important;'],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->end_controls_section();

        // استایل نظرات
        $this->start_controls_section('style_comments', [
            'label' => 'استایل نظرات',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_comments' => 'yes'],
        ]);
        
        $this->add_control('comments_count_color', [
            'label' => 'رنگ تعداد',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-comments .comments-count' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('comments_count_spacing', [
            'label' => 'فاصله از آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 5, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-comments .comments-count' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_responsive_control('comments_margin', [
            'label' => 'فاصله کل نظرات',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-comments' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_control('comments_icon_color', [
            'label' => 'رنگ آیکون',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-comments i' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('comments_icon_size', [
            'label' => 'اندازه آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .sahandigram-comments i' => 'font-size: {{SIZE}}{{UNIT}} !important;'],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->end_controls_section();

        // استایل شئر
        $this->start_controls_section('style_share', [
            'label' => 'استایل شئر',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_share' => 'yes'],
        ]);
        
        $this->add_responsive_control('share_margin', [
            'label' => 'فاصله کل شئر',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-share' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_control('share_icon_color', [
            'label' => 'رنگ آیکون شئر',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-share i.fa-share' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_control('social_icon_color', [
            'label' => 'رنگ آیکون‌های سوشال',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-share .share-options a i' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('social_spacing', [
            'label' => 'فاصله آیکون‌های سوشال',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .sahandigram-share .share-options a' => 'margin: 0 {{SIZE}}{{UNIT}} !important;'],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_responsive_control('share_icon_size', [
            'label' => 'اندازه آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .sahandigram-share i' => 'font-size: {{SIZE}}{{UNIT}} !important;'],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->end_controls_section();

        // استایل بازدید
        $this->start_controls_section('style_views', [
            'label' => 'استایل بازدید',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_views' => 'yes'],
        ]);
        
        $this->add_control('views_count_color', [
            'label' => 'رنگ تعداد',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-views .views-count' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('views_count_spacing', [
            'label' => 'فاصله از آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 5, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-views .views-count' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_responsive_control('views_margin', [
            'label' => 'فاصله کل بازدید',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .sahandigram-views' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->add_control('views_icon_color', [
            'label' => 'رنگ آیکون',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#262626',
            'selectors' => ['{{WRAPPER}} .sahandigram-views i' => 'color: {{VALUE}} !important;'],
        ]);
        
        $this->add_responsive_control('views_icon_size', [
            'label' => 'اندازه آیکون',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .sahandigram-views i' => 'font-size: {{SIZE}}{{UNIT}} !important;'],
            'devices' => ['desktop', 'tablet', 'mobile'],
        ]);
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();
        $likes = get_post_meta($post_id, '_sahandigram_likes', true) ?: 0;
        $liked = is_user_logged_in() && in_array(get_current_user_id(), get_post_meta($post_id, '_sahandigram_liked_users', true) ?: array());
        $saved = is_user_logged_in() && in_array($post_id, get_user_meta(get_current_user_id(), '_sahandigram_saved_posts', true) ?: array());
        $comments_count = get_comments_number($post_id);
        $url = urlencode(get_permalink($post_id));
        $title = urlencode(get_the_title($post_id));

        // شمارش بازدید با کوکی
        $views_cookie = 'sahandigram_views_' . $post_id;
        $views = get_post_meta($post_id, '_sahandigram_views', true) ?: 0;
        if (!isset($_COOKIE[$views_cookie])) {
            $views++;
            update_post_meta($post_id, '_sahandigram_views', $views);
            setcookie($views_cookie, 'viewed', time() + (86400 * 30), "/"); // 30 روز
        }

        // فرمت کوتاه اعداد
        $format_number = function($number) use ($settings) {
            if ($settings['short_number_format'] !== 'yes') return $number;
            if ($number >= 1000000) return round($number / 1000000, 1) . 'M';
            if ($number >= 1000) return round($number / 1000, 1) . 'K';
            return $number;
        };

        // ترتیب آیکون‌ها
        $icons = [];
        if ($settings['show_like'] === 'yes') {
            $icons[$settings['like_order']] = 'like';
        }
        if ($settings['show_save'] === 'yes') {
            $icons[$settings['save_order']] = 'save';
        }
        if ($settings['show_comments'] === 'yes') {
            $icons[$settings['comments_order']] = 'comments';
        }
        if ($settings['show_share'] === 'yes') {
            $icons[$settings['share_order']] = 'share';
        }
        if ($settings['show_views'] === 'yes') {
            $icons[$settings['views_order']] = 'views';
        }
        ksort($icons);
        ?>
        <div class="sahandigram-social" style="direction: rtl;">
            <?php
            foreach ($icons as $icon) {
                if ($icon === 'like') {
                    ?>
                    <span class="sahandigram-like" data-post-id="<?php echo $post_id; ?>">
                        <span class="like-count"><?php echo $format_number($likes); ?></span>
                        <i class="<?php echo $liked ? 'fas fa-heart liked' : 'far fa-heart'; ?> <?php echo $settings['enable_animation'] === 'yes' ? 'animate-icon' : ''; ?>"></i>
                    </span>
                    <?php
                } elseif ($icon === 'save') {
                    ?>
                    <span class="sahandigram-save" data-post-id="<?php echo $post_id; ?>">
                        <i class="<?php echo $saved ? 'fas fa-bookmark saved' : 'far fa-bookmark'; ?> <?php echo $settings['enable_animation'] === 'yes' ? 'animate-icon' : ''; ?>"></i>
                    </span>
                    <?php
                } elseif ($icon === 'comments') {
                    ?>
                    <span class="sahandigram-comments">
                        <span class="comments-count"><?php echo $format_number($comments_count); ?></span>
                        <i class="far fa-comment <?php echo $settings['enable_animation'] === 'yes' ? 'animate-icon' : ''; ?>"></i>
                    </span>
                    <?php
                } elseif ($icon === 'share') {
                    ?>
                    <span class="sahandigram-share">
                        <i class="fas fa-share <?php echo $settings['enable_animation'] === 'yes' ? 'animate-icon' : ''; ?>"></i>
                        <div class="share-options" style="display: none;">
                            <a href="https://t.me/share/url?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank"><i class="fab fa-telegram"></i></a>
                            <a href="https://wa.me/?text=<?php echo $title; ?>%20<?php echo $url; ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            <a href="https://www.instagram.com/?url=<?php echo $url; ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="copy-link" data-url="<?php echo get_permalink($post_id); ?>"><i class="fas fa-link"></i></a>
                        </div>
                    </span>
                    <?php
                } elseif ($icon === 'views') {
                    ?>
                    <span class="sahandigram-views">
                        <span class="views-count"><?php echo $format_number($views); ?></span>
                        <i class="far fa-eye <?php echo $settings['enable_animation'] === 'yes' ? 'animate-icon' : ''; ?>"></i>
                    </span>
                    <?php
                }
            }
            ?>
        </div>

        <style>
            <?php if ($settings['enable_animation'] === 'yes') : ?>
            .animate-icon:hover {
                animation: heartbeat 0.5s ease-in-out;
            }
            @keyframes heartbeat {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
            <?php endif; ?>
            .sahandigram-message {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #333;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                display: none;
                z-index: 1000;
            }
            {{WRAPPER}} .sahandigram-social {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            {{WRAPPER}} .sahandigram-share {
                position: relative;
                display: inline-block;
            }
            {{WRAPPER}} .sahandigram-share .share-options {
                display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: flex-start;
    background: #f0f0f0;
    padding: 10px;
    border-radius: 5px;
    position: absolute;
    top: calc(100% + 5px);
    z-index: 10;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    overflow-x: hidden;
    width: max-content;
            }
            {{WRAPPER}} .sahandigram-share .share-options a {
                margin: 0 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            {{WRAPPER}} .sahandigram-share .share-options a i {
                font-size: 18px;
            }
            @media (max-width: 767px) {
                {{WRAPPER}} .sahandigram-social {
                    gap: 10px;
                }
                {{WRAPPER}} .sahandigram-share .share-options {
                    right: auto;
        transform: translateX(-30%);
        top: calc(100% + 5px);
        padding: 10px;
        max-width: max-content;
        overflow-x: auto;
        white-space: nowrap;
        box-sizing: border-box;
        width: fit-content;
                }
                {{WRAPPER}} .sahandigram-share .share-options a {
                    margin: 0 10px;
                }
            }
        </style>

        <div class="sahandigram-message" id="sahandigram-message"></div>

    <?php
    // اصلاح مسیر فایل جاوااسکریپت
    $plugin_dir = plugin_dir_url(dirname(__FILE__)); // یک سطح به بالا می‌ریم
    $script_url = $plugin_dir . 'assets/js/sahandigram-social.js';
    wp_enqueue_script(
        'sahandigram-social-js',
        $script_url,
        array('jquery'),
        '1.0.4', // نسخه جدید برای ریست کش
        true
    );

    wp_localize_script(
        'sahandigram-social-js',
        'sahandigramData',
        array(
            'isLoggedIn' => is_user_logged_in(),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sahandigram_nonce'),
            'scriptUrl' => $script_url // برای دیباگ
        )
    );
    ?>
    <script type="text/javascript">
        console.log('جاوااسکریپت درون‌خطی لود شد! مسیر اسکریپت: <?php echo $script_url; ?>');
    </script>
    <?php
}}