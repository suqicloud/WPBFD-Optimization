<?php
/*
不能直接使用！不能直接使用！不能直接使用！
*/

//移除谷歌字体(国内主题一般默认不用谷歌字体)
    if (!function_exists('remove_wp_open_sans')) :
    function remove_wp_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    }
    // 前台删除Google字体CSS
    add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
    // 后台删除Google字体CSS
    add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
  endif;

//删除emoji表情脚本
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('embed_head', 'print_emoji_detection_script');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

//页面链接添加html后缀(有的主题默认)
add_action('init', 'html_page_permalink', -1);
function html_page_permalink() {
    global $wp_rewrite;
    if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
    }
}

//禁用XML-RPC(第三方接口，最好把xmlrpc.php文件里面的代码清空，保留个空文件)
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', '__return_empty_array');

//禁用REST API（有的主题以及小程序app需要调用）
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
add_filter( 'rest_authentication_errors', function( $access ) {
  return new WP_Error( 'rest_cannot_acess', 'REST API已关闭', array( 'status' => 403 ) );
});

//禁止加载wp-embed.min.js(有的主题需要调用)
function my_deregister_scripts(){
    wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

//除后台之外删除静态资源的查询字符串
function j_remove_script_version( $src ) {
    $parts = explode( '?ver', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', 'j_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'j_remove_script_version', 15, 1 );


//禁用feed
function disable_feed() {
wp_die( '本站不提供feed。<script>location.href="'.bloginfo('url').'";</script>' );
}
add_action('do_feed', 'disable_feed', 1);
add_action('do_feed_rdf', 'disable_feed', 1);
add_action('do_feed_rss', 'disable_feed', 1);
add_action('do_feed_rss2', 'disable_feed', 1);
add_action('do_feed_atom', 'disable_feed', 1);

//12取消内容转义
$qmr_work_tags = array(
  'the_title',             // 标题
  'the_content',           // 内容 *
  'the_excerpt',           // 摘要 *
  'single_post_title',     // 单篇文章标题
  'comment_author',        // 评论作者
  'comment_text',          // 评论内容 *
  'bloginfo',              // 博客信息
  'wp_title',              // 网站标题
  'term_description',      // 项目描述
  'category_description',  // 分类描述
  'widget_title',          // 小工具标题
  'widget_text'            // 小工具文本
  );
foreach ( $qmr_work_tags as $qmr_work_tag ) {
  remove_filter ($qmr_work_tag, 'wptexturize');
}

/*整体移除页面还会显示的*/
//移除 REST API 端点
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
//移除AutoEmbeds镶嵌
remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
//移除oEmbed自动发现功能
add_filter( 'embed_oembed_discover', '__return_false' );
//移除oEmbed结果
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
//移除oEmbed发现链接
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
//移除oEmbed使用的JavaScript 文件
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
//移除json连接加载
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
//remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header');
//移除WordPress版本
remove_action( 'wp_head', 'wp_generator' );
//移除分类等feed
remove_action( 'wp_head', 'feed_links_extra', 3 );
//移除文章和评论feed
remove_action( 'wp_head', 'feed_links', 2 );
//屏蔽API产生的信息
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
//屏蔽rel=shortlink信息
remove_action( 'template_redirect','wp_shortlink_header', 11, 0);
//移除ShortLink
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
//移除rsd链接，不用XML-RPC接口可以关闭输出
remove_action( 'wp_head', 'rsd_link' );
//移除dns-prefetch
remove_action('wp_head', 'wp_resource_hints', 2);
//移除wlwmanifest(Windows Live Writer接口)
remove_action( 'wp_head', 'wlwmanifest_link' );
//移除当前页面的索引
remove_action( 'wp_head', 'index_rel_link' );
//移除后面文章的url
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
//移除最开始文章的url
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
//移除前后文meta信息
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
//移除相邻文章的url
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
//移除检查当前页面的检测(以下也可以不用加)
remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
remove_filter('oembed_response_data',   'get_oembed_response_data_rich',  10, 4);
//移除古腾堡编辑器样式block-library CSS
function remove_block_library_css() {
 wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'remove_block_library_css', 100 );
//移除pingbacks, enclosures, trackbacks
remove_action('do_pings', 'do_all_pings');
//移除_encloseme 和 do_ping 操作。
remove_action('publish_post', '_publish_post_hook');
//彻底禁用Pingback与Trackback
add_filter('wp_headers', function($headers, $wp_query){
    if(isset($headers['X-Pingback'])){
        unset($headers['X-Pingback']);
    }
    return $headers;
}, 11, 2);
add_filter('pre_option_enable_xmlrpc', function($state){
    return '0';
});
add_action('wp', function(){
    remove_action('wp_head', 'rsd_link');
}, 9);
add_filter('bloginfo_url', function($output, $property){ 
    return ($property == 'pingback_url') ? null : $output;
}, 11, 2);
add_action('xmlrpc_call', function($method){
    if($method != 'pingback.ping') return;
    wp_die(
        'Pingback functionality is disabled on this Blog.',
        'Pingback Disabled!',
        array('response' => 403)
    );
});
//禁止current_screen
add_filter('current_screen', '__return_false', 1);
//禁止translations_api
add_filter('translations_api', '__return_true', 1);
//禁止wp_check_browser_version
add_filter('wp_check_browser_version', '__return_false', 1);
//禁止wp_check_php_version
add_filter('pre_site_transient_{$transient}', '__return_true', 1);
//禁用后台登录页语言切换
add_filter( 'login_display_language_dropdown', '__return_false' );
/*整体移除页面还会显示的 结束*/
//禁止谷歌地图(国外主题多)
//add_filter( 'avf_load_google_map_api', '__return_false' );
//禁止每个页面加载Contact Form7(表单)
//add_filter( 'wpcf7_load_js', '__return_false' );
//add_filter( 'wpcf7_load_css', '__return_false' );
/*//禁用Heartbeat API(自动存储草稿，每隔15秒向服务器发送ajax调用，如果出错可以删掉，建议保留)
add_action( 'init', 'stop_heartbeat', 1 );
function stop_heartbeat() {
wp_deregister_script('heartbeat');
}*/

// 只为已登录用户加载dashicons
add_action( 'wp_enqueue_scripts', 'bs_dequeue_dashicons' );
function bs_dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}
/*//禁用Gutenberg编辑器(看个人习惯，我个人博客在用，更建议用官方插件)
add_filter('use_block_editor_for_post', '__return_false');
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );
add_filter( 'use_widgets_block_editor', '__return_false' );
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );

//禁用小工具区块编辑模式
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
*/
//禁用版本修订(放到wp-config)
define('WP_POST_REVISIONS', false);
//如果这句加进去还有其他不连贯，可以用下面的
/*彻底关闭修订版本(id不连贯) 开始*/
function keep_id_continuous(){
global $wpdb;
//删掉自动草稿和修订版
$wpdb->query("DELETE FROM `$wpdb->posts` WHERE `post_status` = 'auto-draft' OR `post_type` = 'revision'");
//自增值小于现有最大ID，MySQL会自动设置正确的自增值
$wpdb->query("ALTER TABLE `$wpdb->posts` AUTO_INCREMENT = 1");
}
//将函数钩在新建文章、上传媒体和自定义菜单之前。
add_filter( 'load-post-new.php', 'keep_id_continuous' );
add_filter( 'load-media-new.php', 'keep_id_continuous' );
add_filter( 'load-nav-menus.php', 'keep_id_continuous' ); 
//禁用文章自动保存
add_action('wp_print_scripts','fanly_no_autosave');
function fanly_no_autosave(){
    wp_deregister_script('autosave');
}
//禁用文章修订版本
add_filter( 'wp_revisions_to_keep', 'fanly_wp_revisions_to_keep', 10, 2 );
function fanly_wp_revisions_to_keep( $num, $post ) { return 0;}
/*彻底修订版本(id不连贯) 结束*/

//禁止图片超过2500像素压缩
add_filter('big_image_size_threshold', '__return_false');
//禁止响应式图片
function disable_srcset( $sources ) {
return false;
}
add_filter( 'wp_calculate_image_srcset', 'disable_srcset' );
//禁止自动生成图片尺寸
function shapeSpace_disable_image_sizes($sizes) {
    
    unset($sizes['thumbnail']);    // disable thumbnail size
    unset($sizes['medium']);       // disable medium size
    unset($sizes['large']);        // disable large size
    unset($sizes['medium_large']); // disable medium-large size
    unset($sizes['1536x1536']);    // disable 2x medium-large size
    unset($sizes['2048x2048']);    // disable 2x large size
    
    return $sizes;
    
}
add_action('intermediate_image_sizes_advanced', 'shapeSpace_disable_image_sizes');
//禁止其他图片尺寸
function shapeSpace_disable_other_image_sizes() {
    
    remove_image_size('post-thumbnail'); // disable images added via set_post_thumbnail_size() 
    remove_image_size('another-size');   // disable any other added image sizes
    
}
add_action('init', 'shapeSpace_disable_other_image_sizes');

//禁止Wordpress邮箱管理员验证
add_filter('admin_email_check_interval', '__return_false');

//重定向?author
add_action('template_redirect', 'disableAuthorUrl');
function disableAuthorUrl(){
    if (is_author())) {
       wp_redirect(home_url());
       exit();
    }
}
//author大概率可能不使用你的主题 取决于主题

//移除comment-reply.min.js(如果不用评论)
function crunchify_clean_header_hook() {
    wp_deregister_script( 'comment-reply' );
}
add_action('init','crunchify_clean_header_hook');

//15移除jquery-migrate.min.js(兼容老jquery库，有的主题默认不启用)
function isa_remove_jquery_migrate( &$scripts ) {
    if( !is_admin() ) {
        $scripts->remove( 'jquery' );
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }
}
add_filter( 'wp_default_scripts', 'isa_remove_jquery_migrate' );

//16禁用cron(定时任务，放到wp-config.php)
define('DISABLE_WP_CRON', true);

//关闭更新检查定时作业
    remove_action('init', 'wp_schedule_update_checks');
//移除已有的版本检查定时作业
    wp_clear_scheduled_hook('wp_version_check');
//移除已有的插件更新定时作业
    wp_clear_scheduled_hook('wp_update_plugins');
//移除已有的主题更新定时作业
    wp_clear_scheduled_hook('wp_update_themes');
//移除已有的自动更新定时作业
    wp_clear_scheduled_hook('wp_maybe_auto_update');
//移除后台内核更新检查
    remove_action('admin_init', '_maybe_update_core');
//移除后台插件更新检查
    remove_action('load-plugins.php', 'wp_update_plugins');
    remove_action('load-update.php', 'wp_update_plugins');
    remove_action('load-update-core.php', 'wp_update_plugins');
    remove_action('admin_init', '_maybe_update_plugins');
//移除后台主题更新检查
    remove_action('load-themes.php', 'wp_update_themes');
    remove_action('load-update.php', 'wp_update_themes');
    remove_action('load-update-core.php', 'wp_update_themes');
    remove_action('admin_init', '_maybe_update_themes');


//自动给图片加上alt/title
function content_img_add_alt_title($content)
{
    global $post;
    preg_match_all('/<img (.*?)\/>/', $content, $images);
    if (!is_null($images)) {
        foreach ($images[1] as $index => $value) {
            $new_img = str_replace('<img', '<img title=' . $post->post_title . '
             alt=' . $post->post_title, $images[0][$index]);
            $content = str_replace($images[0][$index], $new_img, $content);
        }
    }
    return $content;
}

add_filter('the_content', 'content_img_add_alt_title', 99);

//19-删除XFN (XHTML Friends Network) Profile 链接 和 Pingback URL
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
//找到header.php删了就行

/*后台优化*/
//禁用后台右上角帮助
add_action('in_admin_header', function(){
  global $current_screen;
  $current_screen->remove_help_tabs();
});

//禁用后台右上角选项
add_action('in_admin_header', function(){
  add_filter('screen_options_show_screen', '__return_false');
  add_filter('hidden_columns', '__return_empty_array');
});

//禁用Admin Bar(登录之后顶部栏)
add_filter( 'show_admin_bar', '__return_false' );

//屏蔽后台页脚WordPress版本信息
function change_footer_admin () {return '';}
add_filter('admin_footer_text', 'change_footer_admin', 9999);
function change_footer_version() {return '';}
add_filter( 'update_footer', 'change_footer_version', 9999);

//隐藏后台标题中的“—— WordPress”
add_filter('admin_title', 'zm_custom_admin_title', 10, 2);
function zm_custom_admin_title($admin_title, $title){
    return $title.' ‹ '.get_bloginfo('name');
}

//隐藏登录页面标题中的“WordPress”
add_filter('login_title', 'zm_custom_login_title', 10, 2);
    function zm_custom_login_title($login_title, $title){
        return $title.' ‹ '.get_bloginfo('name');
}

//隐藏左上角WordPress标志
function hidden_admin_bar_remove() {
    global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'hidden_admin_bar_remove', 0);

//27隐藏后台状态
function disable_dashboard_widgets() {  
remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
//近期评论
remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');
//近期草稿
remove_meta_box('dashboard_primary', 'dashboard', 'core');
//wordpress博客  
remove_meta_box('dashboard_secondary', 'dashboard', 'core');
//其它新闻  
remove_meta_box('dashboard_right_now', 'dashboard', 'core');
//wordpress概况  
remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
//wordresss链入链接  
remove_meta_box('dashboard_plugins', 'dashboard', 'core');
//链入插件  
remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
//快速发布
remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
//动态
}  
add_action('admin_menu', 'disable_dashboard_widgets');
