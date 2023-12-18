<?php
/*
Plugin Name: WPBFD Optimization
Plugin URI: https://www.jingxialai.com/4307.html
Description: 一个Wordpress基础功能和数据库简单优化插件。
Version: 1.0
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

            .image-container {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .image-container img {
                width: 48%;
            }

        </style>
        <?php
    }
}
add_action('admin_enqueue_scripts', 'wpbfd_optimize_enqueue_styles');


// 主菜单页
function wpdfdoptimize_main_page() {
    ?>
    <div class="wrap">
        <h1>WPBFD设置(一款代码开源的wordpress基础优化插件)</h1>
        <p>WPBFD Optimization 基础功能数据库优化说明：</p>
        1、插件可能不兼容你的网站(和主题或者其他插件冲突)，如果你在主题functions.php里面加过相关功能代码，先去删掉<br>
        2、先禁用其他优化插件(比如Autoptimize、WP Super Cache等)，测试正常之后，再去启用这些插件；如果你网站开启了CDN，去刷新下CDN再看<br>
        <font style="color: #FF3300;">3、优化数据库之前，一定要先备份数据库！先备份数据库！先备份数据库！</font><br>
        4、如果插件一启用就造成网站不正常，就去服务器里面删掉这个插件文件夹，名称：WPBFDoptimizations<br>
        5、可以联系我进行反馈，根据具体错误原因，之后更新兼容，也可以自己看代码修复.<br>
        6、插件相关函数说明查看：<a href="https://www.jingxialai.com/4307.html" target="_blank">WPBFD Optimization</a>方便你自己更改增加代码 ｜ GitHub下载<a href="https://github.com/suqicloud/WPBFD-Optimization" target="_blank">WPBFD Optimization</a><br>


        <!-- 清除对象缓存按钮 -->
        <form method="post" action="">
            <p>
                <input type="submit" name="clear_object_cache" class="button button-primary" value="清除对象缓存">
                (如果你网站有开启Redis、Memcached对象缓存，设置保存之后就清空下)
            </p>
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

        <!-- 图片广告 -->
        <div class="image-container">
             <img src="广告图片链接" height="150" width="300" style="height: 150px; width: 300px;">
             <img src="广告图片链接" height="150" width="300" style="height: 150px; width: 300px;">
        </div>
    </div>
    <?php
}


// 菜单
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
add_action('admin_menu', 'wpbfd_optimize_menu');
