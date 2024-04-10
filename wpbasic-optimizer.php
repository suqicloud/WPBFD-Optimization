<?php
//设置里面加入口
//by summer
//https://www.jingxialai.com/4307.html
if (!defined('ABSPATH')) {
    exit;
}

//设置页面
function custom_admin_styles() {
    if (!current_user_can('manage_options')) {
        wp_die('您无权限访问这个页面');
    }

    global $pagenow;
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wpbf-basic-optimizer') {
        ?>
    <style>
        .wpbfdwrap {
            max-width: 90%;
            margin: 10px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .updated {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        p.submit {
            margin-top: 20px;
        }

        .notice {
            margin-top: 20px;
        }

        .notice p {
            margin-bottom: 0;
        }

        .form-table th,
        .form-table td {
            padding: 15px;
            vertical-align: top;
        }

        .form-table th {
            width: 80%;
            /*text-align: right;*/
        }

        .form-table input[type="checkbox"] {
            margin-top: 4px;
        }
    </style>
    <?php
    }
}

add_action('admin_head', 'custom_admin_styles');

function WPBF_plugin_options() {
    ?>
    <div class="wpbfdwrap">
        <h1>WPBFD - 基础优化选项</h1>
                <?php
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><strong>已成功更改，请测试是否正常。</strong></p>
            </div>
            <?php
        }
        ?>

        <font style="color: #FF3300;">在这里配置基础选项，一个个测试保存，然后在新窗口看看网站是否正常，因为有的功能可能和你的主题或其他插件有冲突。</font><br>部分功能和页面有关，网站有缓存插件就先禁用，不然你也不知道是否生效了，正常之后再启用，有开启CDN就要去刷新页面之后查看。<br>
        很多中文主题都已经做好相关基础优化了，反正根据你自己的主题和其他插件来，插件详情查看：<a href="https://www.jingxialai.com/4307.html" target="_blank">WPBFD Optimization</a></p>
        <form method="post" action="options.php">
            <?php settings_fields('WPBF-plugin-settings-group'); ?>
            <?php do_settings_sections('wpbf-basic-optimizer'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

//注册复选框
function WPBF_custom_option_setup() {
    //隐藏左上角WordPress标志
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_wp_logo');
    add_settings_section('section-one', '', null, 'wpbf-basic-optimizer');
    add_settings_field('wpbf_hide-wp-logo', '1、隐藏左上角WordPress标志', 'wpbf_hide_wp_logo_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏登录页面标题中的“-WordPress”
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_login_header');
    add_settings_field('wpbf_hide-login-header', '2、隐藏登录页面标题中的WordPress', 'wpbf_hide_login_header_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏后台标题中的“—— WordPress”
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_admin_header');
    add_settings_field('wpbf_hide-admin-header', '3、隐藏后台标题中的WordPress', 'wpbf_hide_admin_header_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏后台页脚WordPress版本信息
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_footer_version');
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_left_copyright');
    add_settings_field('wpbf_hide-footer-version', '4、隐藏后台底部版本号', 'wpbf_hide_footer_version_callback', 'wpbf-basic-optimizer', 'section-one');
    add_settings_field('wpbf_hide-left-copyright', '5、隐藏后台底部版权信息', 'wpbf_hide_left_copyright_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏后台右上角帮助
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_help_tab');
    add_settings_field('wpbf_hide-help-tab', '6、隐藏后台右上角帮助', 'wpbf_hide_help_tab_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏后台右上角选项
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_screen_options');
    add_settings_field('wpbf_hide-screen-options', '7、隐藏后台右上角选项', 'wpbf_hide_screen_options_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁用Gutenberg编辑器，使用经典编辑器
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_gutenberg');
    add_settings_field('wpbf_disable-gutenberg', '8、禁用Gutenberg编辑器', 'wpbf_disable_gutenberg_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁用区块小工具，使用经典小工具
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_block_widgets');
    add_settings_field('wpbf_disable-block-widgets', '9、禁用区块小工具', 'wpbf_disable_block_widgets_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏Admin Bar(仅对普通用户)
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_admin_bar');
    add_settings_field('wpbf_hide-admin-bar', '10、隐藏Admin Bar(仅对普通用户)', 'wpbf_hide_admin_bar_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏Admin Bar(对所有用户)
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_admin_bar_front');
    add_settings_field('wpbf_hide-admin-bar-front', '11、隐藏Admin Bar(<span style="color:blue;">对所有用户有效，和上面不要同时选上</span>)', 'wpbf_hide_admin_bar_front_callback', 'wpbf-basic-optimizer', 'section-one');
    //关闭自动更新检查
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_update_checks');
    add_settings_field('wpbf_disable-update-checks', '12、关闭自动更新(<span style="color:blue;">后台不能自动更新了,关闭之后去手动更新</span>)', 'wpbf_disable_update_checks_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止Wordpress邮箱管理员验证
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_admin_email_verification');
    add_settings_field('wpbf_disable-admin-email-verification', '13、禁止Wordpress邮箱管理员验证', 'wpbf_disable_admin_email_verification_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏登录页面的语言切换
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_login_language_switch');
    add_settings_field('wpbf_disable-login-language-switch', '14、隐藏登录页面的语言切换', 'wpbf_disable_login_language_switch_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止Current Screen
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_current_screen');
    add_settings_field('wpbf_disable-current-screen', '15、禁止启用当前屏幕检查', 'wpbf_disable_current_screen_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止translations_api 强制启用某些翻译功能
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_translations_api');
    add_settings_field('wpbf_disable-translations-api', '16、禁止启用某些翻译api', 'wpbf_disable_translations_api_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止wp_check_browser_version 检查浏览器版本
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_wp_check_browser_version');
    add_settings_field('wpbf_disable-wp-check-browser-version', '17、禁止检查浏览器版本', 'wpbf_disable_wp_check_browser_version_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁用REST API
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_rest_api');
    add_settings_field('wpbf_disable-rest-api', '18、禁用REST API(<span style="color:blue;">如果你在使用古腾堡编辑器，小程序等就不要禁用</span>)', 'wpbf_disable_rest_api_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁用XML-RPC
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_xml_rpc');
    add_settings_field('wpbf_disable-xml-rpc', '19、禁用XML-RPC', 'wpbf_disable_xml_rpc_callback', 'wpbf-basic-optimizer', 'section-one');
    //删除emoji表情脚本
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_emoji_scripts');
    add_settings_field('wpbf_disable-emoji-scripts', '20、删除emoji表情脚本', 'wpbf_disable_emoji_scripts_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止谷歌字体翻译
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_google_fonts');
    add_settings_field('wpbf_disable-google-fonts', '21、禁止谷歌字体翻译', 'wpbf_disable_google_fonts_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止加载谷歌字体
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_google_fonts_custom');
    add_settings_field('wpbf_disable_google_fonts_custom', '22、禁止加载谷歌字体(<span style="color:blue;">只内置了open-sans、roboto、Lobster三种字体检查</span>)', 'wpbf_disable_google_fonts_custom_callback', 'wpbf-basic-optimizer', 'section-one');
    // 前台禁止加载 global-styles
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_global_styles'); 
    add_settings_field('wpbf_disable-global-styles', '23、禁止加载global-styles(<span style="color:blue;">如果你确定你的主题或其他插件不依赖它</span>)', 'wpbf_disable_global_styles_callback', 'wpbf-basic-optimizer', 'section-one'); 
    //禁止加载wp-embed.min.js(有的主题需要调用)
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_wp_embed_script'); 
    add_settings_field('wpbf_disable-wp-embed-script', '24、禁止加载wp-embed.min.js', 'wpbf_disable_wp_embed_script_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止前台生成?ver
    register_setting('WPBF-plugin-settings-group', 'wpbf_remove_query_strings'); 
    add_settings_field('wpbf_remove-query-strings', '25、禁止前台生成?ver', 'wpbf_remove_query_strings_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁用feed
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_wordpress_feed');
    add_settings_field('wpbf_disable-wordpress-feed', '26、禁用feed(访问feed会跳转首页)', 'wpbf_disable_wordpress_feed_callback', 'wpbf-basic-optimizer', 'section-one'); 
    //禁用pingback和trackback
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_pingback_trackback');
    add_settings_field('wpbf_disable-pingback-trackback', '27、禁用pingback和trackback(仅对新页面有效，访问会跳转首页)', 'wpbf_disable_pingback_trackback_callback', 'wpbf-basic-optimizer', 'section-one');
    //禁止生成额外的图片尺寸
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_image_sizes'); 
    add_settings_field('wpbf_disable-image-sizes', '28、禁止生成额外的图片尺寸', 'wpbf_disable_image_sizes_callback', 'wpbf-basic-optimizer', 'section-one'); 
    // 禁止压缩超过2500像素的图片
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_big_image_compress'); 
    add_settings_field('wpbf_disable-big-image-compress', '29、禁止压缩超过2500像素的图片', 'wpbf_disable_big_image_compress_callback', 'wpbf-basic-optimizer', 'section-one'); 
    //禁止访问author
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_author_pages');
    add_settings_field('wpbf_disable-author-pages', '30、禁止访问author(访问会跳转首页)', 'wpbf_disable_author_pages_callback', 'wpbf-basic-optimizer', 'section-one'); 
    //禁止加载评论脚本
    register_setting('WPBF-plugin-settings-group', 'dwpbf_isable_comment_script');
    add_settings_field('wpbf_disable-comment-script', '31、禁止加载评论脚本(<span style="color:blue;">如果你不开启评论功能却还加载comment_script脚本就用</span>)', 'wpbf_disable_comment_script_callback', 'wpbf-basic-optimizer', 'section-one');
    // 禁用前台加载 WordPress 自带的 jQuery
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_frontend_jquery');
    add_settings_field('wpbf_disable-frontend-jquery', '32、禁用前台加载WordPress的jQuery(<span style="color:blue;">不建议，除非你确定你的主题或其他插件不依赖它</span>)', 'wpbf_disable_frontend_jquery_callback', 'wpbf-basic-optimizer', 'section-one');
    //移除jquery-migrate.min.js(兼容老jquery)
    register_setting('WPBF-plugin-settings-group', 'wpbf_remove_jquery_migrate'); 
    add_settings_field('wpbf_remove-jquery-migrate', '33、移除jquery-migrate.min.js(兼容老jquery)(<span style="color:blue;">如果你确定你的主题或其他插件不依赖它</span>)', 'wpbf_remove_jquery_migrate_callback', 'wpbf-basic-optimizer', 'section-one'); 
    // 禁用文章自动保存、修订版本、id不连贯的问题
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_autosave_revisions_inconsistency'); 
    add_settings_field('wpbf_disable-autosave-revisions-inconsistency', '34、禁用文章自动保存(<span style="color:blue;">媒体、页面等依旧会占用id,开启后不能发布文章就取消，可能是不兼容，</span>)', 'wpbf_disable_autosave_revisions_inconsistency_callback', 'wpbf-basic-optimizer', 'section-one'); 
    //隐藏仪表盘的概况
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_dashboard_overview');
    add_settings_field('wpbf_hide-dashboard-overview', '35、隐藏仪表盘的概况模块', 'wpbf_hide_dashboard_overview_callback', 'wpbf-basic-optimizer', 'section-one');
    // 隐藏仪表盘的动态模块
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_dashboard_activity');
    add_settings_field('wpbf_hide-dashboard-activity', '36、隐藏仪表盘的动态模块', 'wpbf_hide_dashboard_activity_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏仪表盘的站点健康状态
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_site_health_status');
    add_settings_field('wpbf_hide-site-health-status', '37、隐藏仪表盘的站点健康状态模块', 'wpbf_hide_site_health_status_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏仪表盘的草稿
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_dashboard_drafts');
    add_settings_field('wpbf_hide-dashboard-drafts', '38、隐藏仪表盘的草稿模块', 'wpbf_hide_dashboard_drafts_callback', 'wpbf-basic-optimizer', 'section-one');
    //隐藏仪表盘的活动和新闻
    register_setting('WPBF-plugin-settings-group', 'wpbf_hide_dashboard_activity_news');
    add_settings_field('wpbf_hide-dashboard-activity-news', '39、隐藏仪表盘的活动和新闻模块', 'wpbf_hide_dashboard_activity_news_callback', 'wpbf-basic-optimizer', 'section-one');
    //移除REST API和oEmbed信息
    register_setting('WPBF-plugin-settings-group', 'wpbf_remove_rest_oembed');
    add_settings_field('wpbf_remove-rest-oembed', '40、移除REST API和oEmbed相关信息', 'wpbf_remove_rest_oembed_callback', 'wpbf-basic-optimizer', 'section-one');
    //移除杂项信息
    register_setting('WPBF-plugin-settings-group', 'wpbf_remove_miscellaneous');
    add_settings_field('wpbf_remove-miscellaneous', '41、移除13项其他页面信息', 'wpbf_remove_miscellaneous_callback', 'wpbf-basic-optimizer', 'section-one');
    // 禁用编辑器脚本和样式
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_block_editor_scripts');
    add_settings_field('wpbf_disable-block-editor-scripts', '42、禁用网站前台加载Gutenberg编辑器脚本和样式', 'wpbf_disable_block_editor_scripts_callback', 'wpbf-basic-optimizer', 'section-one');
    // 搜索默认链接重定向
    register_setting('WPBF-plugin-settings-group', 'wpbf_redirect_search_enabled');
    add_settings_field('wpbf_redirect-search-enabled', '43、搜索默认链接重定向(<span style="color:blue;">把?s变为/search/</span>)', 'wpbf_redirect_search_enabled_callback', 'wpbf-basic-optimizer', 'section-one');

    // 允许 WordPress 页面使用 .html 后缀
    register_setting('WPBF-plugin-settings-group', 'wpbf_html_page_suffixlinks_setting');
    add_settings_field('wpbf_html_page_suffixlinks_setting','44、页面添加.html(<span style="color:blue;">需要重新设置固定链接，其实现在的网站没必要</span>)','wpbf_html_page_suffixlinks_callback','wpbf-basic-optimizer','section-one');
    //将页面HTML代码压缩成一行
    register_setting('WPBF-plugin-settings-group', 'wpbf_compress_page');
    add_settings_field('wpbf_compress-page', '45、将页面HTML代码压缩成一行(可能和你主题不兼容)', 'wpbf_compress_page_callback', 'wpbf-basic-optimizer', 'section-one');
    // 禁止内容转义 wptexturize
    register_setting('WPBF-plugin-settings-group', 'wpbf_disable_wptexturize');
    add_settings_field('wpbf_disable-wptexturize', '46、禁止内容转义wptexturize', 'wpbf_disable_wptexturize_callback', 'wpbf-basic-optimizer', 'section-one');
    //删除woocommerce邮政编号、城市、省会、姓氏字段
    register_setting('WPBF-plugin-settings-group', 'wpbf_remove_woocommerce_fields');
add_settings_field('wpbf_remove-woocommerce-fields', '47、删除woocommerce邮政编号、城市、省会、姓氏字段(<span style="color:blue;">需要后台设置强制收货地址</span>)', 'wpbf_remove_woocommerce_fields_callback', 'wpbf-basic-optimizer', 'section-one');
    //删除woocommerce邮箱字段
    register_setting('WPBF-plugin-settings-group', 'wpbf_remove_woocommerce_email_field');
    add_settings_field('wpbf_remove-woocommerce-email-field', '48、删除woocommerce邮箱字段(woocommerce这块优化和你的主题也有关系)', 'wpbf_remove_woocommerce_email_field_callback', 'wpbf-basic-optimizer', 'section-one');
    //简化woocommerce结算页面
    register_setting('WPBF-plugin-settings-group', 'wpbf_modify_woocommerce_checkout_labels');
add_settings_field('wpbf_modify-woocommerce-checkout-labels', '49、简化woocommerce结算页面(仅保留联系人、地址、电话)', 'wpbf_modify_woocommerce_checkout_labels_callback', 'wpbf-basic-optimizer', 'section-one');
    //让网站变灰
    register_setting('WPBF-plugin-settings-group', 'wpbf_make_site_grayscale');
    add_settings_field('wpbf_make-site-grayscale', '50、让网站变灰(某些日子可以用)', 'wpbf_make_site_grayscale_callback', 'wpbf-basic-optimizer', 'section-one');
}


//隐藏左上角WordPress标志
function wpbf_hide_wp_logo_callback() {
    $setting = esc_attr(get_option('wpbf_hide_wp_logo'));
    echo "<input type='checkbox' name='wpbf_hide_wp_logo' " . checked($setting, 'on', false) . " />";
}

add_action('admin_head', 'wpbf_hide_wp_logo');

function wpbf_hide_wp_logo() {
    if(get_option('wpbf_hide_wp_logo') === 'on') {
        echo '<style>#wp-admin-bar-wp-logo { display: none; }</style>';
    }
}

//隐藏登录页面标题中的“-WordPress”
function wpbf_hide_login_header_callback() {
    $setting = esc_attr(get_option('wpbf_hide_login_header'));
    echo "<input type='checkbox' name='wpbf_hide_login_header' " . checked($setting, 'on', false) . " />";
}

add_filter('login_title', 'zm_custom_login_title', 10, 2);

function zm_custom_login_title($login_title, $title){
    if(get_option('wpbf_hide_login_header') === 'on') {
        return $title.' ‹ '.get_bloginfo('name');
    }
    return $login_title;
}

//隐藏后台标题中的“—— WordPress”
function wpbf_hide_admin_header_callback() {
    $setting = esc_attr(get_option('wpbf_hide_admin_header'));
    echo "<input type='checkbox' name='wpbf_hide_admin_header' " . checked($setting, 'on', false) . " />";
}

add_filter('admin_title', 'zm_custom_admin_title', 10, 2);

function zm_custom_admin_title($admin_title, $title){
    if(get_option('wpbf_hide_admin_header') === 'on') {
        return $title.' ‹ '.get_bloginfo('name');
    }
    return $admin_title;
}

//隐藏后台页脚WordPress版本信息
function wpbf_hide_footer_version_callback() {
    $setting = esc_attr(get_option('wpbf_hide_footer_version'));
    echo "<input type='checkbox' name='wpbf_hide_footer_version' " . checked($setting, 'on', false) . " />";
}

function wpbf_hide_left_copyright_callback() {
    $setting = esc_attr(get_option('wpbf_hide_left_copyright'));
    echo "<input type='checkbox' name='wpbf_hide_left_copyright' " . checked($setting, 'on', false) . " />";
}

add_filter('update_footer', 'wpbf_hide_footer_version', 11);
add_filter('admin_footer_text', 'wpbf_hide_left_copyright', 11);

function wpbf_hide_footer_version($content) {
    if(get_option('wpbf_hide_footer_version') === 'on') {
        return '';
    }
    return $content;
}

function wpbf_hide_left_copyright($content) {
    if(get_option('wpbf_hide_left_copyright') === 'on') {
        return '';
    }
    return $content;
}

//隐藏后台右上角帮助
function wpbf_hide_help_tab_callback() {
    $setting = esc_attr(get_option('wpbf_hide_help_tab'));
    echo "<input type='checkbox' name='wpbf_hide_help_tab' " . checked($setting, 'on', false) . " />";
}

add_action('admin_head', 'wpbf_hide_help_tab');

function wpbf_hide_help_tab() {
    if(get_option('wpbf_hide_help_tab') === 'on') {
        $screen = get_current_screen();
        $screen->remove_help_tabs();
    }
}

//隐藏后台右上角选项
function wpbf_hide_screen_options_callback() {
    $setting = esc_attr(get_option('wpbf_hide_screen_options'));
    echo "<input type='checkbox' name='wpbf_hide_screen_options' " . checked($setting, 'on', false) . " />";
}
add_filter('screen_options_show_screen', 'wpbf_hide_screen_options');

function wpbf_hide_screen_options($show_screen) {
    if(get_option('wpbf_hide_screen_options') === 'on') {
        return false;
    }
    return $show_screen;
}

//隐藏Admin Bar(仅对普通用户)
function wpbf_hide_admin_bar_callback() {
    $setting = esc_attr(get_option('wpbf_hide_admin_bar'));
    echo "<input type='checkbox' name='wpbf_hide_admin_bar' " . checked($setting, 'on', false) . " />";
}

add_action('after_setup_theme', 'wpbf_hide_admin_bar');

function wpbf_hide_admin_bar() {
    if(get_option('wpbf_hide_admin_bar') === 'on') {
        if (!current_user_can('manage_options')) {
            show_admin_bar(false);
        }
    }
}

//隐藏Admin Bar(对所有用户)
function wpbf_hide_admin_bar_front_callback() {
    $setting = esc_attr(get_option('wpbf_hide_admin_bar_front'));
    echo "<input type='checkbox' name='wpbf_hide_admin_bar_front' " . checked($setting, 'on', false) . " />";
}

add_action('after_setup_theme', 'wpbf_hide_admin_bar_front');

function wpbf_hide_admin_bar_front() {
    if(get_option('wpbf_hide_admin_bar_front') === 'on') {
        if (!is_admin()) {
            show_admin_bar(false);
        }
    }
}

//禁止Wordpress邮箱管理员验证
function wpbf_disable_admin_email_verification_callback() {
    $setting = esc_attr(get_option('wpbf_disable_admin_email_verification'));
    echo "<input type='checkbox' name='wpbf_disable_admin_email_verification' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_admin_email_verification() {
    if(get_option('wpbf_disable_admin_email_verification') === 'on') {
        add_filter('admin_email_check_interval', '__return_false');
    }
}

add_action('admin_init', 'wpbf_disable_admin_email_verification');

//关闭自动更新检查
function wpbf_disable_update_checks_callback() {
    $setting = esc_attr(get_option('wpbf_disable_update_checks'));
    echo "<input type='checkbox' name='wpbf_disable_update_checks' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_update_checks() {
    if(get_option('wpbf_disable_update_checks') === 'on') {
        add_filter('automatic_updater_disabled', '__return_true');
        // 禁用更新检查
        remove_action('init', 'wp_schedule_update_checks');
        wp_clear_scheduled_hook('wp_version_check');
        wp_clear_scheduled_hook('wp_update_plugins');
        wp_clear_scheduled_hook('wp_update_themes');
        wp_clear_scheduled_hook('wp_maybe_auto_update');
        remove_action('admin_init', '_maybe_update_core');
        remove_action('load-plugins.php', 'wp_update_plugins');
        remove_action('load-update.php', 'wp_update_plugins');
        remove_action('load-update-core.php', 'wp_update_plugins');
        remove_action('admin_init', '_maybe_update_plugins');
        remove_action('load-themes.php', 'wp_update_themes');
        remove_action('load-update.php', 'wp_update_themes');
        remove_action('load-update-core.php', 'wp_update_themes');
        remove_action('admin_init', '_maybe_update_themes');
        add_filter('pre_site_transient_update_core', function($a){ return null; });
        add_filter('pre_site_transient_update_plugins', function($a){ return null; });
        add_filter('pre_site_transient_update_themes', function($a){ return null; });
    }
}


add_action('admin_init', 'wpbf_disable_update_checks');

//隐藏登录页面的语言切换
function wpbf_disable_login_language_switch_callback() {
    $setting = esc_attr(get_option('wpbf_disable_login_language_switch'));
    echo "<input type='checkbox' name='wpbf_disable_login_language_switch' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_login_language_switch() {
    if(get_option('wpbf_disable_login_language_switch') === 'on') {
        add_action('login_head', function() {
            echo '<style type="text/css">#language-switcher { display:none; }</style>';
        });
    }
}

add_action('login_init', 'wpbf_disable_login_language_switch');

//禁止Current Screen
function wpbf_disable_current_screen_callback() {
    $setting = esc_attr(get_option('wpbf_disable_current_screen'));
    echo "<input type='checkbox' name='wpbf_disable_current_screen' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_current_screen() {
    if(get_option('wpbf_disable_current_screen') === 'on') {
        remove_all_actions('current_screen');
    }
}

add_action('admin_init', 'wpbf_disable_current_screen');

//禁止translations_api
function wpbf_disable_translations_api_callback() {
    $setting = esc_attr(get_option('wpbf_disable_translations_api'));
    echo "<input type='checkbox' name='wpbf_disable_translations_api' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_translations_api() {
    if(get_option('wpbf_disable_translations_api') === 'on') {
        add_filter('translations_api', '__return_empty_array');
    }
}

add_action('admin_init', 'wpbf_disable_translations_api');

//禁止wp_check_browser_version
function wpbf_disable_wp_check_browser_version_callback() {
    $setting = esc_attr(get_option('wpbf_disable_wp_check_browser_version'));
    echo "<input type='checkbox' name='wpbf_disable_wp_check_browser_version' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_wp_check_browser_version() {
    if(get_option('wpbf_disable_wp_check_browser_version') === 'on') {
        remove_action('init', 'wp_check_browser_version');
    }
}

add_action('admin_init', 'wpbf_disable_wp_check_browser_version');

//禁用REST API
function wpbf_disable_rest_api_callback() {
    $setting = esc_attr(get_option('wpbf_disable_rest_api'));
    echo "<input type='checkbox' name='wpbf_disable_rest_api' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_rest_api() {
    if(get_option('wpbf_disable_rest_api') === 'on') {
        add_filter('rest_authentication_errors', function($result) {
            if (!empty($result)) {
                return $result;
            }
            if (!is_user_logged_in()) {
                return new WP_Error('rest_not_logged_in', '您目前尚未登录.', array('status' => 401));
            }
            return $result;
        });
    }
}

add_action('init', 'wpbf_disable_rest_api');

//禁用XML-RPC
function wpbf_disable_xml_rpc_callback() {
    $setting = esc_attr(get_option('wpbf_disable_xml_rpc'));
    echo "<input type='checkbox' name='wpbf_disable_xml_rpc' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_xml_rpc() {
    if(get_option('wpbf_disable_xml_rpc') === 'on') {
        add_filter('xmlrpc_enabled', '__return_false');
    }
}

add_action('init', 'wpbf_disable_xml_rpc');

//删除emoji表情脚本
function wpbf_disable_emoji_scripts_callback() {
    $setting = esc_attr(get_option('wpbf_disable_emoji_scripts'));
    echo "<input type='checkbox' name='wpbf_disable_emoji_scripts' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_emoji_scripts() {
    if(get_option('wpbf_disable_emoji_scripts') === 'on') {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('embed_head', 'print_emoji_detection_script');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }
}

add_action('init', 'wpbf_disable_emoji_scripts');

//禁止调用谷歌字体翻译
function wpbf_disable_google_fonts_callback() {
    $setting = esc_attr(get_option('wpbf_disable_google_fonts'));
    echo "<input type='checkbox' name='wpbf_disable_google_fonts' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_google_fonts() {
    if(get_option('wpbf_disable_google_fonts') === 'on') {
        add_filter('gettext_with_context', function($translations, $text, $context, $domain) {
            if ($context == 'Google Font Name and Variants' && $text != 'off') {
                return 'off';
            } else {
                return $translations;
            }
        }, 10, 4);
    }
}

add_action('init', 'wpbf_disable_google_fonts');


// 添加禁止加载 Google Fonts 的选项
function wpbf_disable_google_fonts_custom_callback() {
    $setting = esc_attr(get_option('wpbf_disable_google_fonts_custom'));
    echo "<input type='checkbox' name='wpbf_disable_google_fonts_custom' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_google_fonts_custom() {
    $setting = get_option('wpbf_disable_google_fonts_custom');

    if ($setting === 'on') {
        add_action('wp_enqueue_scripts', 'wpbf_remove_wp_google_fonts');
    }
}

function wpbf_remove_wp_google_fonts() {
    if (get_option('disable_google_fonts_custom') === 'on') {
        wp_deregister_style('open-sans');
        wp_register_style('open-sans', false);
        wp_enqueue_style('open-sans', '');

        wp_deregister_style('roboto');
        wp_register_style('roboto', false);
        wp_enqueue_style('roboto', '');

        wp_deregister_style('Lobster');
        wp_register_style('Lobster', false);
        wp_enqueue_style('Lobster', '');

        // 可以继续添加其他需要移除的 Google Fonts 样式
    }
}

add_action('init', 'wpbf_disable_google_fonts_custom');



// 禁用 Gutenberg 编辑器，使用经典编辑器
function wpbf_disable_gutenberg_callback() {
    $setting = esc_attr(get_option('wpbf_disable_gutenberg'));
    echo "<input type='checkbox' name='wpbf_disable_gutenberg' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_gutenberg() {
    if (get_option('wpbf_disable_gutenberg') === 'on') {
        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_action('wp_enqueue_scripts', 'remove_gutenberg_styles');
    }
}

function remove_gutenberg_styles() {
    wp_deregister_style('wp-block-library'); // 移除 Gutenberg 样式
    wp_dequeue_style('wp-block-library');
}

add_action('init', 'wpbf_disable_gutenberg');


//禁用区块小工具，使用经典小工具
function wpbf_disable_block_widgets_callback() {
    $setting = esc_attr(get_option('wpbf_disable_block_widgets'));
    echo "<input type='checkbox' name='wpbf_disable_block_widgets' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_block_widgets() {
    if(get_option('wpbf_disable_block_widgets') === 'on') {
        add_filter('use_widgets_block_editor', '__return_false');
    }
}

add_action('init', 'wpbf_disable_block_widgets');


// 前台禁止加载 global-styles
function wpbf_disable_global_styles_callback() {
    $setting = esc_attr(get_option('wpbf_disable_global_styles'));
    echo "<input type='checkbox' name='wpbf_disable_global_styles' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_global_styles() {
    if (get_option('wpbf_disable_global_styles') === 'on') {
        add_action('wp_print_styles', 'dequeue_global_styles');
    }
}

function dequeue_global_styles() {
    wp_dequeue_style('global-styles');
}

add_action('init', 'wpbf_disable_global_styles');

//禁止加载wp-embed.min.js(有的主题需要调用)
function wpbf_disable_wp_embed_script_callback() {
    $setting = esc_attr(get_option('wpbf_disable_wp_embed_script'));
    echo "<input type='checkbox' name='wpbf_disable_wp_embed_script' " . checked($setting, 'on', false) . " />";
}


function wpbf_disable_wp_embed_script() {
    if (get_option('wpbf_disable_wp_embed_script') === 'on') {
        wp_deregister_script('wp-embed');
    }
}
add_action('init', 'wpbf_disable_wp_embed_script');

//禁止前台生成?ver
function wpbf_remove_query_strings_callback() {
    $setting = esc_attr(get_option('wpbf_remove_query_strings'));
    echo "<input type='checkbox' name='wpbf_remove_query_strings' " . checked($setting, 'on', false) . " />";
}


function wpbf_remove_query_strings() {
    if (!is_admin() && get_option('wpbf_remove_query_strings') === 'on') {
        add_filter('script_loader_src', 'remove_query_strings_from_url', 15, 1);
        add_filter('style_loader_src', 'remove_query_strings_from_url', 15, 1);
    }
}

function remove_query_strings_from_url($src) {
    $parts = explode('?', $src);
    return $parts[0];
}

add_action('init', 'wpbf_remove_query_strings');

//禁用feed
function wpbf_disable_wordpress_feed() {
    if (get_option('wpbf_disable_wordpress_feed') === 'on') {
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);

        add_action('do_feed', 'disable_wordpress_feed_redirect', 1);
        add_action('do_feed_rdf', 'disable_wordpress_feed_redirect', 1);
        add_action('do_feed_rss', 'disable_wordpress_feed_redirect', 1);
        add_action('do_feed_rss2', 'disable_wordpress_feed_redirect', 1);
        add_action('do_feed_atom', 'disable_wordpress_feed_redirect', 1);
    }
}


function wpbf_disable_wordpress_feed_callback() {
    $setting = esc_attr(get_option('wpbf_disable_wordpress_feed'));
    echo "<input type='checkbox' name='wpbf_disable_wordpress_feed' " . checked($setting, 'on', false) . " />";
}

function disable_wordpress_feed_redirect() {
    wp_redirect(home_url()); // 重定向到首页
    exit();
}

add_action('init', 'wpbf_disable_wordpress_feed');


//禁用pingback和trackback
function wpbf_disable_pingback_trackback_callback() {
    $setting = esc_attr(get_option('wpbf_disable_pingback_trackback'));
    echo "<input type='checkbox' name='wpbf_disable_pingback_trackback' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_pingback_trackback() {
    if (get_option('wpbf_disable_pingback_trackback') === 'on') {
        update_option('default_pingback_flag', 0);
        update_option('default_ping_status', 'closed');
        update_option('default_trackback_flag', 0);
        update_option('default_trackback_status', 'closed');

        add_action('template_redirect', 'redirect_pingback_trackback_requests');
    }
}

function redirect_pingback_trackback_requests() {
    if (is_singular() && (wp_doing_ajax() || isset($_GET['doing_wp_cron']))) {
        return;
    }

    if (is_singular() && (isset($_GET['tb']) || isset($_GET['pb']))) {
        wp_redirect(home_url(), 301);
        exit;
    }
}

add_action('init', 'wpbf_disable_pingback_trackback');


//禁止生成额外的图片尺寸
function wpbf_disable_image_sizes_callback() {
    $setting = esc_attr(get_option('wpbf_disable_image_sizes'));
    echo "<input type='checkbox' name='wpbf_disable_image_sizes' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_image_sizes() {
    if (get_option('wpbf_disable_image_sizes') === 'on') {
        add_action('init', 'remove_custom_image_sizes');
        add_filter('intermediate_image_sizes_advanced', 'disable_additional_image_sizes');
    }
}

function remove_custom_image_sizes() {
    remove_image_size('post-thumbnail');
    remove_image_size('another-size');
}

function disable_additional_image_sizes($sizes) {
    return array();
}

add_action('init', 'wpbf_disable_image_sizes');



// 禁止压缩超过2500像素的图片
function wpbf_disable_big_image_compress_callback() {
    $setting = esc_attr(get_option('wpbf_disable_big_image_compress'));
    echo "<input type='checkbox' name='wpbf_disable_big_image_compress' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_big_image_compress() {
    if (get_option('wpbf_disable_big_image_compress') === 'on') {
        add_filter('big_image_size_threshold', '__return_false');
    }
}
add_action('init', 'wpbf_disable_big_image_compress');


//禁止访问author
function wpbf_disable_author_pages_callback() {
    $setting = esc_attr(get_option('wpbf_disable_author_pages'));
    echo "<input type='checkbox' name='wpbf_disable_author_pages' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_author_pages() {
    if (get_option('wpbf_disable_author_pages') === 'on') {
        add_action('template_redirect', 'redirect_author_pages');
    }
}

function redirect_author_pages() {
    if (is_author()) {
        wp_redirect(home_url(), 301);
        exit;
    }
}

add_action('init', 'wpbf_disable_author_pages');

//禁止加载评论脚本
function wpbf_disable_comment_script_callback() {
    $setting = esc_attr(get_option('wpbf_disable_comment_script'));
    echo "<input type='checkbox' name='wpbf_disable_comment_script' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_comment_script() {
    if (get_option('wpbf_disable_comment_script') === 'on') {
        add_action('wp_enqueue_scripts', 'remove_comment_script');
    }
}

function remove_comment_script() {
    wp_dequeue_script('comment-reply');
}

add_action('init', 'wpbf_disable_comment_script');

// 禁用前台加载 WordPress 自带的 jQuery
function wpbf_disable_frontend_jquery_callback() {
    $setting = esc_attr(get_option('wpbf_disable_frontend_jquery'));
    echo "<input type='checkbox' name='wpbf_disable_frontend_jquery' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_frontend_jquery() {
    if (get_option('wpbf_disable_frontend_jquery') === 'on' && !is_admin()) {
        add_action('wp_enqueue_scripts', 'remove_default_wp_jquery', 1);
    }
}

function remove_default_wp_jquery() {
    wp_deregister_script('jquery');
    wp_dequeue_script('jquery');
}

add_action('init', 'wpbf_disable_frontend_jquery');

//移除jquery-migrate.min.js
// 移除jquery-migrate.min.js
function wpbf_remove_jquery_migrate_callback() {
    $setting = esc_attr(get_option('wpbf_remove_jquery_migrate'));
    echo "<input type='checkbox' name='wpbf_remove_jquery_migrate' " . checked($setting, 'on', false) . " />";
}

function wpbf_remove_jquery_migrate() {
    if (isset($_POST['wpbf_remove_jquery_migrate'])) {
        update_option('wpbf_remove_jquery_migrate', $_POST['wpbf_remove_jquery_migrate']);
    } else {
        delete_option('wpbf_remove_jquery_migrate');
    }
}

function disable_jquery_migrate(&$scripts) {
    $remove_jquery_migrate = get_option('wpbf_remove_jquery_migrate');
    if ($remove_jquery_migrate === 'on' && !is_admin()) {
                $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), null);
    }
}
add_action('wp_default_scripts', 'disable_jquery_migrate');



// 禁用文章自动保存、修订版本、id不连贯的问题
function wpbf_disable_autosave_revisions_inconsistency_callback() {
    $setting = esc_attr(get_option('wpbf_disable_autosave_revisions_inconsistency'));
    echo "<input type='checkbox' name='wpbf_disable_autosave_revisions_inconsistency' " . checked($setting, 'on', false) . " />";
}

function wpbf_set_next_post_id() {
    global $wpdb;
    $last_post_id = (int) $wpdb->get_var("SELECT MAX(ID) FROM $wpdb->posts");
    if ($last_post_id > 0) {
        $next_post_id = $last_post_id + 1;
        $wpdb->query("ALTER TABLE $wpdb->posts AUTO_INCREMENT = $next_post_id");
    }
}
//用这个替代$wpdb->query("ALTER TABLE $wpdb->posts AUTO_INCREMENT = 1");

function wpbf_disable_autosave_revisions_inconsistency() {
    if (is_admin() && isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
        
        if (get_option('wpbf_disable_autosave_revisions_inconsistency') === 'on') {
            // 禁用自动保存
            wp_deregister_script('autosave');

            // 设置保留的修订版本数量为0
            add_filter('wpbf_wp_revisions_to_keep', '__return_zero');

            // 删除自动保存和修订
            global $wpdb;
            $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft' OR post_type = 'revision'");
            //$wpdb->query("ALTER TABLE $wpdb->posts AUTO_INCREMENT = 1");

            // 设置下一个文章的ID
            wpbf_set_next_post_id();
        }
    }
}
add_action('save_post', 'wpbf_disable_autosave_revisions_inconsistency');

//隐藏仪表盘的概况
function wpbf_hide_dashboard_overview_callback() {
    $setting = esc_attr(get_option('wpbf_hide_dashboard_overview'));
    echo "<input type='checkbox' name='wpbf_hide_dashboard_overview' " . checked($setting, 'on', false) . " />";
}

function wpbf_hide_dashboard_overview() {
    if (get_option('wpbf_hide_dashboard_overview') === 'on') {
        add_action('wp_dashboard_setup', 'remove_dashboard_overview_widget');
    }
}

function remove_dashboard_overview_widget() {
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
}

add_action('init', 'wpbf_hide_dashboard_overview');


// 隐藏仪表盘的动态模块
function wpbf_hide_dashboard_activity_callback() {
    $setting = esc_attr(get_option('wpbf_hide_dashboard_activity'));
    echo "<input type='checkbox' name='wpbf_hide_dashboard_activity' " . checked($setting, 'on', false) . " />";
}

function wpbf_hide_dashboard_activity_widget() {
    $setting = get_option('wpbf_hide_dashboard_activity');

    if ($setting === 'on') {
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    }
}

add_action('wp_dashboard_setup', 'wpbf_hide_dashboard_activity_widget');

//隐藏仪表盘的站点健康状态
function wpbf_hide_site_health_status_callback() {
    $setting = esc_attr(get_option('wpbf_hide_site_health_status'));
    echo "<input type='checkbox' name='wpbf_hide_site_health_status' " . checked($setting, 'on', false) . " />";
}

function wpbf_hide_site_health_status_widget() {
    $setting = get_option('wpbf_hide_site_health_status');

    if ($setting === 'on') {
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    }
}

add_action('wp_dashboard_setup', 'wpbf_hide_site_health_status_widget');

//隐藏仪表盘的草稿
function wpbf_hide_dashboard_drafts_callback() {
    $setting = esc_attr(get_option('wpbf_hide_dashboard_drafts'));
    echo "<input type='checkbox' name='wpbf_hide_dashboard_drafts' " . checked($setting, 'on', false) . " />";
}

function wpbf_hide_dashboard_drafts_widget() {
    $setting = get_option('wpbf_hide_dashboard_drafts');

    if ($setting === 'on') {
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    }
}

add_action('wp_dashboard_setup', 'wpbf_hide_dashboard_drafts_widget');


//隐藏仪表盘的活动和新闻
function wpbf_hide_dashboard_activity_news_callback() {
    $setting = esc_attr(get_option('wpbf_hide_dashboard_activity_news'));
    echo "<input type='checkbox' name='wpbf_hide_dashboard_activity_news' " . checked($setting, 'on', false) . " />";
}

function wpbf_hide_dashboard_activity_news_widget() {
    $setting = get_option('wpbf_hide_dashboard_activity_news');

    if ($setting === 'on') {
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
    }
}

add_action('wp_dashboard_setup', 'wpbf_hide_dashboard_activity_news_widget');



// 移除 REST API 和 oEmbed 信息
function wpbf_remove_oembed_from_alternate_links() {
    $setting = get_option('wpbf_remove_rest_oembed');

    if ($setting === 'on') {
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
    }
}

function wpbf_remove_rest_oembed_callback() {
    $setting = esc_attr(get_option('wpbf_remove_rest_oembed'));
    echo "<input type='checkbox' name='wpbf_remove_rest_oembed' " . checked($setting, 'on', false) . " />";
}

function wpbf_remove_rest_oembed_features() {
    $setting = get_option('wpbf_remove_rest_oembed');

    if ($setting === 'on') {
        remove_action('rest_api_init', 'wp_oembed_register_route');
        remove_filter('the_content', array($GLOBALS['wp_embed'], 'autoembed'), 8);
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('template_redirect', 'rest_output_link_header');
        remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
        remove_filter('oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
    }
}

add_action('init', 'wpbf_remove_oembed_from_alternate_links');
add_action('init', 'wpbf_remove_rest_oembed_features');



//移除杂项
function wpbf_remove_miscellaneous_callback() {
    $setting = esc_attr(get_option('wpbf_remove_miscellaneous'));
    echo "<input type='checkbox' name='wpbf_remove_miscellaneous' " . checked($setting, 'on', false) . " />";
}

function wpbf_remove_miscellaneous_features() {
    $setting = get_option('wpbf_remove_miscellaneous');

    if ($setting === 'on') {
        // 从头部移除 WordPress 生成器 meta 标签
        remove_action('wp_head', 'wp_generator');
        // 从头部移除额外的 feed 链接
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);
        // 从头部移除 REST API 链接
        remove_action('template_redirect', 'rest_output_link_header', 11);
        remove_action('template_redirect', 'wp_shortlink_header', 11, 0);
        // 从头部移除 shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        // 从头部移除 Really Simple Discovery (RSD) 链接
        remove_action('wp_head', 'rsd_link');
        // 从头部移除资源提示
        remove_action('wp_head', 'wp_resource_hints', 2);
        // 从头部移除 Windows Live Writer manifest 链接
        remove_action('wp_head', 'wlwmanifest_link');
        // 从头部移除索引链接
        remove_action('wp_head', 'index_rel_link');
        // 从头部移除父级文章关联链接
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);
        // 从头部移除起始文章关联链接
        remove_action('wp_head', 'start_post_rel_link', 10, 0);
        // 从头部移除相邻文章关联链接
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
        // 移除 pingback 功能
        remove_action('do_pings', 'do_all_pings');
        // 移除文章发布钩子
        remove_action('publish_post', '_publish_post_hook');
    }
}

add_action('wp_head', 'wpbf_remove_miscellaneous_features', 1); // 添加优先级为 1
//add_action('wp_head', 'remove_miscellaneous_features'); // 使用 'wp_head'
//add_action('init', 'remove_miscellaneous_features'); // 使用 'init'


// 隐藏前台块编辑器脚本和样式
function wpbf_disable_block_editor_scripts_callback() {
    $setting = esc_attr(get_option('wpbf_disable_block_editor_scripts'));
    echo "<input type='checkbox' name='wpbf_disable_block_editor_scripts' " . checked($setting, 'on', false) . " />";
}

// 执行隐藏前台块编辑器脚本和样式
function wpbf_disable_block_editor_scripts() {
    $setting = get_option('wpbf_disable_block_editor_scripts');

    if ($setting === 'on') {
        remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
    }
}

add_action('wp_enqueue_scripts', 'wpbf_disable_block_editor_scripts');


//搜索默认链接重定向
function wpbf_redirect_search_enabled_callback() {
    $setting = esc_attr(get_option('wpbf_redirect_search_enabled'));
    echo "<input type='checkbox' name='wpbf_redirect_search_enabled' " . checked($setting, 'on', false) . " />";
}

function wpbf_redirect_search() {
    $setting = get_option('wpbf_redirect_search_enabled');

    if ($setting === 'on' && is_search() && !empty($_GET['s'])) {
        wp_redirect(home_url("/search/") . urlencode(get_query_var('s')));
        exit();
    }
}
add_action('template_redirect', 'wpbf_redirect_search');


// 允许 WordPress 页面使用 .html 后缀
function wpbf_html_page_suffixlinks_callback() {
    $setting = esc_attr(get_option('wpbf_html_page_suffixlinks_setting'));
    echo "<input type='checkbox' name='wpbf_html_page_suffixlinks_setting' " . checked($setting, 'on', false) . " />";
}

add_action('init', 'wpbf_html_page_suffixlinks', -1);

function wpbf_html_page_suffixlinks() {
    $setting = get_option('wpbf_html_page_suffixlinks_setting');

    if ($setting === 'on') {
        global $wp_rewrite;

        // 检查是否在页面结构中包含 '.html'
        if (!strpos($wp_rewrite->get_page_permastruct(), '.html')) {
            // 如果不包含，将 '.html' 添加到页面结构中
            $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
        }
    }
}

//将页面HTML代码压缩成一行
function wpbf_compress_page_callback() {
    $setting = esc_attr(get_option('wpbf_compress_page'));
    echo "<input type='checkbox' name='wpbf_compress_page' " . checked($setting, 'on', false) . " />";
}

/*
function wpbf_compress_page_features() {
    $setting = get_option('wpbf_compress_page');

    if ($setting === 'on' && !is_admin()) {
        function compress_page_content($buffer) {
            if (is_singular('post') || is_page()) {
                // 禁止pre标签压缩
                if (preg_match_all('/<\/pre>/i', $buffer, $matches)) {
                    $buffer = '<!--compress-start--><!--compress-no-start-->' . $buffer;
                    $buffer .= '<!--compress-no-end--><!--compress-end-->';
                }

                $initial = strlen($buffer);
                $buffer = explode("<!--compress-start-->", $buffer);
                $count = count($buffer);
                $out = "";

                for ($i = 0; $i < $count; $i++) {
                    if (stristr($buffer[$i], '<!--compress-no-start-->')) {
                        $buffer[$i] = (str_replace("<!--compress-no-start-->", " ", $buffer[$i]));
                    } else {
                        $buffer[$i] = (str_replace("\t", " ", $buffer[$i]));
                        $buffer[$i] = (str_replace("\n\n", "\n", $buffer[$i]));
                        $buffer[$i] = (str_replace("\n", "", $buffer[$i]));
                        $buffer[$i] = (str_replace("\r", "", $buffer[$i]));
                        while (stristr($buffer[$i], '  ')) {
                            $buffer[$i] = (str_replace("  ", " ", $buffer[$i]));
                        }
                    }
                    $out .= $buffer[$i];
                }

                $final = strlen($out);
                $savings = ($initial - $final) / $initial * 100;
                $savings = round($savings, 2);
                $info = "<!--压缩前:{$initial}bytes;压缩后:{$final}bytes;节约:{$savings}%-->";
                return $out . $info;

            } else {
                return $buffer; // 在其他页面直接返回原始内容
            }
        }

        ob_start('compress_page_content');
    }
}

add_action('after_setup_theme', 'wpbf_compress_page_features');
*/
function wpbf_compress_page_features() {
    $setting = get_option('wpbf_compress_page');

    if ($setting === 'on' && !is_admin()) {
        function compress_page_content($buffer) {
            if (is_singular('post') || is_page()) {
                // 禁止pre标签压缩
                if (preg_match_all('/<\/pre>/i', $buffer, $matches)) {
                    $buffer = '<!--compress-start--><!--compress-no-start-->' . $buffer;
                    $buffer .= '<!--compress-no-end--><!--compress-end-->';
                }

                $initial = strlen($buffer);

                // 检查是否为0
                if ($initial === 0) {
                    return $buffer;
                }

                $buffer = explode("<!--compress-start-->", $buffer);
                $count = count($buffer);
                $out = "";

                for ($i = 0; $i < $count; $i++) {
                    if (stristr($buffer[$i], '<!--compress-no-start-->')) {
                        $buffer[$i] = (str_replace("<!--compress-no-start-->", " ", $buffer[$i]));
                    } else {
                        $buffer[$i] = (str_replace("\t", " ", $buffer[$i]));
                        $buffer[$i] = (str_replace("\n\n", "\n", $buffer[$i]));
                        $buffer[$i] = (str_replace("\n", "", $buffer[$i]));
                        $buffer[$i] = (str_replace("\r", "", $buffer[$i]));
                        while (stristr($buffer[$i], '  ')) {
                            $buffer[$i] = (str_replace("  ", " ", $buffer[$i]));
                        }
                    }
                    $out .= $buffer[$i];
                }

                $final = strlen($out);

                // 检查是否为0
                if ($initial === 0) {
                    return $buffer;
                }

                $savings = ($initial - $final) / $initial * 100;
                $savings = round($savings, 2);
                $info = "<!--压缩前:{$initial}bytes;压缩后:{$final}bytes;节约:{$savings}%-->";
                return $out . $info;

            } else {
                return $buffer; // 在其他页面直接返回原始内容
            }
        }

        ob_start('compress_page_content');
    }
}

add_action('after_setup_theme', 'wpbf_compress_page_features');


// 禁止内容转义 wptexturize
function wpbf_disable_wptexturize_callback() {
    $setting = esc_attr(get_option('wpbf_disable_wptexturize'));
    echo "<input type='checkbox' name='wpbf_disable_wptexturize' " . checked($setting, 'on', false) . " />";
}

function wpbf_disable_wptexturize_filter() {
    $setting = get_option('wpbf_disable_wptexturize');

    if ($setting === 'on') {
        remove_filter('the_content', 'wptexturize');
        remove_filter('the_excerpt', 'wptexturize');
        remove_filter('widget_text_content', 'wptexturize');
        remove_filter('the_title', 'wptexturize');
        remove_filter('comment_text', 'wptexturize');
        remove_filter('category_description', 'wptexturize');
    }
}

add_action('init', 'wpbf_disable_wptexturize_filter');



//删除woocommerce邮政编号、城市、省会、姓氏字段
function wpbf_remove_woocommerce_fields_callback() {
    $setting = esc_attr(get_option('wpbf_remove_woocommerce_fields'));
    echo "<input type='checkbox' name='wpbf_remove_woocommerce_fields' " . checked($setting, 'on', false) . " />";
}

function wpbf_remove_woocommerce_fields() {
    $setting = get_option('wpbf_remove_woocommerce_fields');

    if ($setting === 'on') {
        // 移除邮政编号、城市、省会、姓氏、地区、公司字段
        add_filter('woocommerce_default_address_fields', 'custom_override_default_address_fields');
        function custom_override_default_address_fields($fields) {
            unset($fields['postcode']);
            unset($fields['city']);
            unset($fields['state']);
            unset($fields['last_name']);
            unset($fields['address_2']);
            unset($fields['country']);
            unset($fields['company']);
            return $fields;
        }
    }
}

add_action('woocommerce_before_edit_account_form', 'wpbf_remove_woocommerce_fields');



//删除woocommerce邮箱字段
function wpbf_remove_woocommerce_email_field_callback() {
    $setting = esc_attr(get_option('wpbf_remove_woocommerce_email_field'));
    echo "<input type='checkbox' name='wpbf_remove_woocommerce_email_field' " . checked($setting, 'on', false) . " />";
}

function wpbf_remove_woocommerce_email_field() {
    $setting = get_option('wpbf_remove_woocommerce_email_field');

    if ($setting === 'on') {
        // 移除结算页面的邮箱字段
        add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
        function custom_override_checkout_fields($fields) {
            unset($fields['billing']['billing_email']);
            return $fields;
        }

        // 移除个人中心页面的邮箱字段
        add_filter('woocommerce_billing_fields', 'custom_override_billing_fields');
        function custom_override_billing_fields($fields) {
            unset($fields['billing_email']);
            return $fields;
        }
    }
}

add_action('woocommerce_before_checkout_form', 'wpbf_remove_woocommerce_email_field');
add_action('woocommerce_before_edit_account_form', 'wpbf_remove_woocommerce_email_field');



//简化woocommerce结算页面
function wpbf_modify_woocommerce_checkout_labels_callback() {
    $setting = esc_attr(get_option('wpbf_modify_woocommerce_checkout_labels'));
    echo "<input type='checkbox' name='wpbf_modify_woocommerce_checkout_labels' " . checked($setting, 'on', false) . " />";
}

function wpbf_modify_woocommerce_checkout_labels() {
    $setting = get_option('wpbf_modify_woocommerce_checkout_labels');

    if ($setting === 'on') {
        add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
        function custom_override_checkout_fields($fields) {
            $fields['billing']['billing_first_name']['label'] = '收件人';
            $fields['billing']['billing_address_1']['label'] = '收货地址';
            $fields['billing']['billing_address_1']['placeholder'] = '请填写详细收件地址';
            return $fields;
        }
    }
}

add_action('woocommerce_before_checkout_form', 'wpbf_modify_woocommerce_checkout_labels');


//让网站变灰
function wpbf_make_site_grayscale_callback() {
    $setting = esc_attr(get_option('wpbf_make_site_grayscale'));
    echo "<input type='checkbox' name='wpbf_make_site_grayscale' " . checked($setting, 'on', false) . " />";
}

function wpbf_make_site_grayscale() {
    $setting = get_option('wpbf_make_site_grayscale');

    if ($setting === 'on') {
        add_action('wp_footer', 'add_grayscale_styles');
    }
}

function add_grayscale_styles() {
    $setting = get_option('wpbf_make_site_grayscale');
    if ($setting === 'on') {
        echo '<style>
            body {
                filter: grayscale(100%);
            }
        </style>';
    }
}

add_action('wp_head', 'wpbf_make_site_grayscale');
add_action('admin_init', 'WPBF_custom_option_setup');
