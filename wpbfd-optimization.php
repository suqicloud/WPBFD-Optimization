<?php
/*
Plugin Name: WPBFD Optimization
Plugin URI: https://www.jingxialai.com/4307.html
Description: 一个Wordpress基础功能和数据库简单优化插件。
Version: 1.3
Author: Summer
License: GPL License
*/

// 主菜单
add_action('admin_menu', 'wpbfd_optimize_menu');
include_once(plugin_dir_path(__FILE__) . 'wpbasic-optimizer.php');
include_once(plugin_dir_path(__FILE__) . 'wpdatabase-optimize.php');

// 设置链接回调函数
function wpbfd_optimize_settings_link($links) {
    $settings_link = '<a href="admin.php?page=wpdfdoptimize">设置</a>';
    array_unshift($links, $settings_link);
    return $links;
}
// 设置入口
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpbfd_optimize_settings_link');



// css代码
function wpbfd_optimize_enqueue_styles() {
    if (isset($_GET['page']) && $_GET['page'] === 'wpdfdoptimize') {
        ?>
        <style>
            .wrap {
                max-width: 90%;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
            }
            .image-container {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .image-container img {
                width: 48%;
            }
            .button-primary {
                background-color: #4CAF50;
                color: #fff;
            }

            .notice {
                padding: 10px;
                border-left: 4px solid #4CAF50;
                margin-top: 20px;
            }

            .notice-success {
                background-color: #dff0d8;
            }

            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }

            .switch input { 
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #2196F3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }

            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }
        </style>
        <?php
    }
}
add_action('admin_enqueue_scripts', 'wpbfd_optimize_enqueue_styles');

// 检查category分类链接设置
add_action('init', 'check_no_category_base_setting');

function check_no_category_base_setting() {
    if (get_option('no_category_base_enabled', 'off') == 'on') {
        no_category_base_permastruct();
    }
}

// 图片延迟加载
function wpbfd_optimize_add_lazy_loading($content) {
    // 图片延迟加载
    if (get_option('wpbfd_optimize_lazy_loading') === '1') {
        $content = str_replace('<img ', '<img loading="lazy" ', $content);
    }
    return $content;
}
add_filter('the_content', 'wpbfd_optimize_add_lazy_loading');


// 添加图片alt
function wpbfd_optimize_add_image_alt($content) {
    // 添加图片 alt 和 title
    if (get_option('wpbfd_optimize_add_image_alt') === '1') {
        global $post;
        preg_match_all('/<img (.*?)\/>/', $content, $images);
        if (!is_null($images)) {
            foreach ($images[1] as $index => $value) {
                if (preg_match('/alt=["\']\s*["\']/', $images[0][$index])) {
                    // 图片原本的 alt 属性为空时，替换现有的 alt 属性为文章标题
                    $new_img = preg_replace('/(alt=["\'])(\s*)["\']/', '${1}' . $post->post_title . '${2}"', $images[0][$index]);
                    // 如果已经有 title 属性，则不再添加 title 属性
                    if (!preg_match('/title=["\']([^"\']+)["\']/', $new_img)) {
                        $new_img = preg_replace('/<img(.*?)alt=["\']([^"\']+)["\']/', '<img${1}title="' . $post->post_title . '" alt="${2}"', $new_img);
                    }
                    $content = str_replace($images[0][$index], $new_img, $content);
                } else {
                    // 图片没有 alt 属性时，添加文章标题作为 alt 属性值
                    $new_img = str_replace('<img', '<img title="' . $post->post_title . '" alt="' . $post->post_title . '"', $images[0][$index]);
                    $content = str_replace($images[0][$index], $new_img, $content);
                }
            }
        }
    }
    return $content;
}
add_filter('the_content', 'wpbfd_optimize_add_image_alt');


// 隐藏文章图片
function wpbfd_optimize_hide_cover_image($content) {
    // 隐藏文章图片
    if (get_option('wpbfd_optimize_hide_cover_image') === '1' && !is_user_logged_in()) {
        $content = preg_replace('/<img(.*?)class=[\'"](.*?wp-image-\d+)[\'"](.*?)>/i', '', $content);
    }
    return $content;
}
add_filter('the_content', 'wpbfd_optimize_hide_cover_image');




// 消息提醒
function wpbfd_optimize_add_lazy_loading_and_category_settings() {
    if (isset($_POST['toggle_settings'])) {
        // 保存图片延迟加载设置
        //$lazy_loading = isset($_POST['lazy_loading']) ? $_POST['lazy_loading'] : '0';
        // $result_lazy_loading = update_option('wpbfd_optimize_lazy_loading', $_POST['lazy_loading']);

        $lazy_loading_value = isset($_POST['lazy_loading']) ? $_POST['lazy_loading'] : '0';
        $result_lazy_loading = update_option('wpbfd_optimize_lazy_loading', $lazy_loading_value);

        // 保存隐藏文章图片开关设置
        $hide_cover_image_value = isset($_POST['hide_cover_image']) ? $_POST['hide_cover_image'] : '0';
        $result_hide_cover_image = update_option('wpbfd_optimize_hide_cover_image', $hide_cover_image_value);

        // 保存添加图片alt设置
        $add_image_alt_value = isset($_POST['add_image_alt']) ? $_POST['add_image_alt'] : '0';
        $result_add_image_alt = update_option('wpbfd_optimize_add_image_alt', $add_image_alt_value);


        // 保存category按钮设置
        $result_category = update_option('no_category_base_enabled', $_POST['no_category_base_enabled']);

        if ($result_lazy_loading || $result_category || $result_hide_cover_image || $result_add_image_alt) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p>已保存。</p>
            </div>
            <?php
        } else {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>没有开启成功，具体错误可以查看网站日志。</p>
            </div>
            <?php
        }
    }
}

// 主页设置
function wpdfdoptimize_main_page() {
    wpbfd_optimize_add_lazy_loading_and_category_settings();

    // 获取开关设置
    $enabled_lazy_loading = get_option('wpbfd_optimize_lazy_loading', '0');
    $enabled_hide_cover_image = get_option('wpbfd_optimize_hide_cover_image', '0');
    $enabled_add_image_alt = get_option('wpbfd_optimize_add_image_alt', '0');
    $enabled_category = get_option('no_category_base_enabled', 'off');
    
    ?>
    <div class="wrap">
        <h1>WPBFD设置(一款代码开源的wordpress基础优化插件)</h1>
        <!-- 新增检查更新按钮 -->
        <p>
           <button class="button-primary" id="check-for-updates">检查更新</button>
        </p>
        <!-- 新增检查更新按钮结束 -->

        <!-- 图片延迟加载和category按钮合并的表单 -->
        <form method="post" action="">
            <p>
                <label>文章图片延迟加载：</label>
                <label class="switch">
                    <input type="checkbox" name="lazy_loading" value="1" <?php checked($enabled_lazy_loading, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>隐藏文章图片(登录可见)：</label>
                <label class="switch">
                    <input type="checkbox" name="hide_cover_image" value="1" <?php checked($enabled_hide_cover_image, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>添加图片alt和title：</label>
                <label class="switch">
                    <input type="checkbox" name="add_image_alt" value="1" <?php checked($enabled_add_image_alt, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>
            
            <p>
                <label>删掉分类category链接：</label>
                <label class="switch">
                    <input type="checkbox" name="no_category_base_enabled" value="on" <?php checked($enabled_category, 'on'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>
            <p>
                <input type="submit" name="toggle_settings" class="button-primary" value="保存更改">
            </p>
        </form>
        <!-- 图片延迟加载和category按钮合并的表单结束 -->

        <!-- 清除对象缓存按钮 -->
        <form method="post" action="">
            <p>
                <input type="submit" name="clear_object_cache" class="button-primary" value="清除对象缓存">
            </p>
            1、删掉category的代码来自No category base插件，用这里就不要安装插件了，保存之后重新保存下固定链接<br>
            2、如果你网站有开启Redis、Memcached对象缓存，基础功能优化之后之后就清除下对象缓存，如果没有就不要点<br>
            3、文章图片延迟加载大部分主题都有这个功能，所以这里的文章图片延迟加载功能只支持新标准浏览器
        </form>
        
        <?php
        // 执行清除对象缓存
        if (isset($_POST['clear_object_cache'])) {
            wp_cache_flush(); 
            ?>
            <div class="notice notice-success is-dismissible">
                <p>清除对象缓存成功.</p>
            </div>
            <?php
        }
        ?>
        <!-- 清除对象缓存按钮结束 -->
        <!-- 基础信息 -->
        <p>插件QQ群：<a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=dgfThTp7nW4_hoRc1wjaGWEKlNmemlqB&jump_from=webapi&authKey=kwUfvush+fV1G/4Mvr5cva6EnWnQPave2J61QzmfTmUEk+OdGg6c9H1tPaHQYjLJ"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="Wordpress运营技术瞎折腾" title="Wordpress运营技术瞎折腾"></a></p>

        <p>WPBFD Optimization 基础功能数据库优化说明：</p>
        1、插件可能不兼容你的网站(和主题或者其他插件冲突)，如果你在主题functions.php里面加过相关功能代码，先去删掉<br>
        2、先禁用其他优化插件(比如Autoptimize、WP Super Cache等)，测试正常之后，再去启用这些插件；如果你网站开启了CDN，去刷新下CDN再看<br>
        <font style="color: #FF3300;">3、优化数据库之前，一定要先备份数据库！先备份数据库！先备份数据库！</font><br>
        4、如果插件一启用就造成网站不正常，就去服务器里面删掉这个插件文件夹，名称：WPBFDoptimizations<br>
        5、可以联系我进行反馈，根据具体错误原因，之后更新兼容，也可以自己看代码修复.<br>
        6、插件相关函数说明查看：<a href="https://www.jingxialai.com/4307.html" target="_blank">WPBFD Optimization</a>方便你自己更改增加代码 ｜ GitHub下载：<a href="https://www.jingxialai.com/4307.html" target="_blank">WPBFD Optimization</a><br>

    </div>
<!-- 基础信息结束 -->
<!--在插件设置页面上显示版本号和更新提示-->
    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('check-for-updates').addEventListener('click', async function (event) {
        event.preventDefault();

        try {
            const response = await fetch('https://www.jingxialai.com/pulginapi/WPBFD-version-check.php');

            if (!response.ok) {
                throw new Error('网络响应不正常');
            }

            const data = await response.json();
            var pluginVersion = '1.3';
            var downloadURL = data.download_url;
            var releaseNotesURL = data.release_notes;

            if (data.version > pluginVersion) {

                showNotice('success', '已有新版本，请前往<a href="' + downloadURL + '" target="_blank">Github下载新版本</a>。 ' +
                    '查看<a href="' + releaseNotesURL + '" target="_blank">查看新版本说明</a>.');
            } else {
                showNotice('info', '已经是最新版。');
            }
        } catch (error) {
            showNotice('error', '检查更新失败：' + error.message);
        }
    });

    // 显示WordPress样式的消息通知
    function showNotice(type, message) {
        var notice = document.createElement('div');
        notice.className = 'notice notice-' + type + ' is-dismissible';
        notice.innerHTML = '<p>' + message + '</p>';

        // 触发 "检查更新" 按钮
        var targetElement = document.getElementById('check-for-updates');
        targetElement.parentNode.insertBefore(notice, targetElement);
    }
});

    </script>

<!--在插件设置页面上显示版本号和更新提示结束-->
    <?php
}


//以下为插件代码
/* hooks */
register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
register_deactivation_hook(__FILE__, 'no_category_base_deactivate');

/* actions */
add_action('created_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
//add_action('init', 'no_category_base_permastruct');

/* filters */
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
add_filter('query_vars', 'no_category_base_query_vars'); 
add_filter('request', 'no_category_base_request');

function no_category_base_refresh_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

function no_category_base_deactivate() {
    remove_filter( 'category_rewrite_rules', 'no_category_base_rewrite_rules' );
    no_category_base_refresh_rules();
}

function no_category_base_permastruct()
{
    if (get_option('no_category_base_enabled', 'off') == 'on') {
        global $wp_rewrite;
        global $wp_version;

        if ( $wp_version >= 3.4 ) {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        }
    }
}


function no_category_base_rewrite_rules($category_rewrite) {
    if (get_option('no_category_base_enabled', 'off') == 'on') {
    global $wp_rewrite;
    $category_rewrite=array();

    if ( class_exists( 'Sitepress' ) ) {
        global $sitepress;

        remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
        $categories = get_categories( array( 'hide_empty' => false ) );
        add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
    } else {
        $categories = get_categories( array( 'hide_empty' => false ) );
    }

    foreach( $categories as $category ) {
        $category_nicename = $category->slug;

        if ( $category->parent == $category->cat_ID ) {
            $category->parent = 0;
        } elseif ( $category->parent != 0 ) {
            $category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
        }

        $category_rewrite['('.$category_nicename.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
        $category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
        $category_rewrite['('.$category_nicename.')/?$'] = 'index.php?category_name=$matches[1]';
    }

    $old_category_base = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
    $old_category_base = trim( $old_category_base, '/' );
    $category_rewrite[$old_category_base.'/(.*)$'] = 'index.php?category_redirect=$matches[1]';
}
    return $category_rewrite;
}

function no_category_base_query_vars($public_query_vars) {
    if (get_option('no_category_base_enabled', 'off') == 'on') {
        $public_query_vars[] = 'category_redirect';
    }

    return $public_query_vars;
}

function no_category_base_request($query_vars) {
    if (get_option('no_category_base_enabled', 'off') == 'on' && isset( $query_vars['category_redirect'] )) {
        $catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
        status_header( 301 );
        header( "Location: $catlink" );
        exit();
    }

    return $query_vars;
}


// 菜单入口设置
function wpbfd_optimize_menu() {
    // 添加主菜单页
add_menu_page(
    'WPBFD Optimization设置说明',// 页面标题
    'WPBFD设置',// 菜单标题
    'manage_options',// 权限要求
    'wpdfdoptimize',// 菜单Slug
    'wpdfdoptimize_main_page',// 页面回调函数
    'dashicons-superhero'// 图标
    );
    
    // 添加子菜单
add_submenu_page(
    'wpdfdoptimize', // 父菜单Slug
    'WPBFD基础功能优化', // 子菜单标题
    '基础功能优化',// 子菜单标题
    'manage_options',// 权限要求
    'wpbf-basic-optimizer',// 子菜单Slug
    'WPBF_plugin_options'// 页面回调函数
);

add_submenu_page(
    'wpdfdoptimize', 
    'WPBFD数据库优化',
    '数据库优化',
    'manage_options',
    'wpbfd-database',
    'optimize_postmeta_page'
);
}

/* 停用插件之后删掉数据库里面的设置值
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');

function my_plugin_deactivate() {
    delete_option('wpbfd_optimize_lazy_loading');
    delete_option('no_category_base_enabled');
    // 在这里添加想要删除的其他选项
}
*/

/*<!-- 验证图片延迟加载有没有生效 -->
        <?php
        if (get_option('wpbfd_optimize_lazy_loading') === '1') {
            echo 'wpbfd_optimize_lazy_loading的值为1';
        } else {
            echo 'wpbfd_optimize_lazy_loading的值不为1';
        }
        ?>
<!-- 验证图片延迟加载有没有生效 -->*/
