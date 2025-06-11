<?php
/*
Plugin Name: 小半WP优化助手
Plugin URI: https://www.jingxialai.com/4307.html
Description: 一个Wordpress基础功能、数据库简单优化以及基础安全加固插件。
Version: 2.7
Author: Summer
License: GPL License
Author URI: https://www.jingxialai.com/
*/


if (!defined('ABSPATH')) {
    exit;
}

// 主菜单
add_action('admin_menu', 'wpbfd_optimize_menu');
include_once(plugin_dir_path(__FILE__) . 'wpbasic-optimizer.php');
include_once(plugin_dir_path(__FILE__) . 'wpdatabase-optimize.php');
include_once(plugin_dir_path(__FILE__) . 'wpbfd-surety.php');

// 设置链接回调函数
function wpbfd_optimize_settings_link($links) {
    $settings_link = '<a href="admin.php?page=wpdfdoptimize">设置</a>';
    array_unshift($links, $settings_link);
    return $links;
}
// 设置入口
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpbfd_optimize_settings_link');


// 菜单入口设置
function wpbfd_optimize_menu() {
    // 添加主菜单页
add_menu_page(
    '小半WP优化助手设置说明',// 页面标题
    '小半优化设置',// 菜单标题
    'manage_options',// 权限要求
    'wpdfdoptimize',// 菜单Slug
    'wpdfdoptimize_main_page',// 页面回调函数
    'dashicons-superhero'// 图标
    );
    
    // 添加子菜单
add_submenu_page(
    'wpdfdoptimize', // 父菜单Slug
    '小半WP优化助手基础功能优化', // 子菜单标题
    '基础功能优化',// 子菜单标题
    'manage_options',// 权限要求
    'wpbf-basic-optimizer',// 子菜单Slug
    'wpbfd_basic_page'// 页面回调函数
);

add_submenu_page(
    'wpdfdoptimize', 
    '小半WP优化助手数据库优化',
    '数据库优化',
    'manage_options',
    'wpbfd-database',
    'optimize_postmeta_page'
);
    
add_submenu_page(
    'wpdfdoptimize', 
    '小半WP优化助手基础安全优化',
    '基础安全优化',
    'manage_options',
    'wpbfd-surety',
    'wpbfd_surety_settings_page'
);  
    
}

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

// 检查category分类链接设置开始
add_action('init', 'check_no_category_base_setting');

function check_no_category_base_setting() {
    if (get_option('no_category_base_enabled', 'off') == 'on') {
        no_category_base_permastruct();
    }
}
// 检查category分类链接设置结束

// 图片延迟加载开始
function wpbfd_optimize_add_lazy_loading($content) {
    if (get_option('wpbfd_optimize_lazy_loading') === '1') {
        $content = str_replace('<img ', '<img loading="lazy" ', $content);
    }
    return $content;
}
add_filter('the_content', 'wpbfd_optimize_add_lazy_loading');
// 图片延迟加载结束

// 图片转换为WebP格式显示开始
function wpbfd_optimize_convert_to_webp($content) {
    if (get_option('wpbfd_optimize_convert_to_webp') === '1') {
        global $post;
        preg_match_all('/<img (.*?)src=["\'](.*?)["\'](.*?)\/>/', $content, $images);
        if (!is_null($images)) {
            foreach ($images[2] as $index => $src) {
                // 检查图片是否为jpg或png格式
                if (preg_match('/\.(jpg|jpeg|png)$/i', $src)) {
                    // 获取图片路径和文件名
                    $image_path = parse_url($src, PHP_URL_PATH);
                    $upload_dir = wp_upload_dir();
                    $full_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $src);
                    
                    // 生成WebP路径
                    $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $full_path);
                    $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
                    
                    // 检查WebP文件是否已存在
                    if (!file_exists($webp_path)) {
                        // 尝试创建WebP版本
                        $image = false;
                        if (preg_match('/\.(jpg|jpeg)$/i', $src)) {
                            $image = imagecreatefromjpeg($full_path);
                        } elseif (preg_match('/\.png$/i', $src)) {
                            $image = imagecreatefrompng($full_path);
                        }
                        
                        if ($image) {
                            // 转换为WebP
                            imagewebp($image, $webp_path, 80); // 80是WebP的质量参数
                            imagedestroy($image);
                        }
                    }
                    
                    // 如果WebP文件存在，替换原图URL
                    if (file_exists($webp_path)) {
                        $new_img = str_replace($src, $webp_url, $images[0][$index]);
                        $content = str_replace($images[0][$index], $new_img, $content);
                    }
                }
            }
        }
    }
    return $content;
}
add_filter('the_content', 'wpbfd_optimize_convert_to_webp');
// 图片转换为WebP格式显示结束

// 添加图片alt开始
function wpbfd_optimize_add_image_alt($content) {
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
// 添加图片alt结束

// 隐藏文章图片开始
function wpbfd_optimize_hide_cover_image($content) {
    if (get_option('wpbfd_optimize_hide_cover_image') === '1' && !is_user_logged_in()) {
        $content = preg_replace('/<img(.*?)class=[\'"](.*?wp-image-\d+)[\'"](.*?)>/i', '', $content);
    }
    return $content;
}
add_filter('the_content', 'wpbfd_optimize_hide_cover_image');
// 隐藏文章图片结束

// 添加用户IP自定义列开始
function wpbfd_add_user_ip_column($columns) {
    if (get_option('wpbfd_optimize_get_user_ip') === '1') {
        $columns['user_ip'] = '用户IP';
    }
    return $columns;
}
add_filter('manage_users_columns', 'wpbfd_add_user_ip_column');

// 显示用户IP地址的值
function wpbfd_display_user_ip($value, $column_name, $user_id) {
    if (get_option('wpbfd_optimize_get_user_ip') === '1' && $column_name === 'user_ip') {
        $registration_ip = get_user_meta($user_id, 'registration_ip', true);
        $last_login_ip = get_user_meta($user_id, 'last_login_ip', true);
        $user_ip = '';
        if ($registration_ip) {
            $user_ip .= '注册IP: ' . $registration_ip . '<br>';
        } else {
            $user_ip .= '注册IP: 未知<br>';
        }
        if ($last_login_ip) {
            $user_ip .= '最后登录IP: ' . $last_login_ip;
        } else {
            $user_ip .= '最后登录IP: 未知';
        }
        return $user_ip;
    }
    return $value;
}
add_filter('manage_users_custom_column', 'wpbfd_display_user_ip', 10, 3);

// 在用户注册时记录注册IP
function wpbfd_record_user_registration_ip($user_id) {
    $ip_address = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    add_user_meta($user_id, 'registration_ip', $ip_address, true);
}
add_action('user_register', 'wpbfd_record_user_registration_ip');

// 在用户登录时记录最后登录IP和时间
function wpbfd_record_user_last_login_ip($user_login, $user) {
    $user_id = $user->ID;
    $ip_address = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT']; //获取设备信息

    // 更新最后登录IP
    update_user_meta($user_id, 'last_login_ip', $ip_address);

    // 更新设备信息
    update_user_meta($user_id, 'login_device_info', $user_agent);

    // 更新最后登录时间
    update_user_meta($user_id, 'last_login_time', current_time('mysql'));
}
add_action('wp_login', 'wpbfd_record_user_last_login_ip', 10, 2);

// 添加用户IP自定义列结束

// 添加用户时间自定义列开始
function wpbfd_add_user_time_column($columns) {
    if (get_option('wpbfd_optimize_show_user_time') === '1') {
        $columns['user_time'] = '用户时间';
    }
    return $columns;
}
add_filter('manage_users_columns', 'wpbfd_add_user_time_column');

// 显示用户注册时间和最后登录时间的值
function wpbfd_display_user_time($value, $column_name, $user_id) {
    if (get_option('wpbfd_optimize_show_user_time') === '1' && $column_name === 'user_time') {
        $user_registered = get_userdata($user_id)->user_registered;
        $last_login = get_user_meta($user_id, 'last_login_time', true); 
        $user_time = '';
        if ($user_registered) {
            $user_time .= '注册时间: ' . date('Y-m-d H:i:s', strtotime($user_registered)) . '<br>';
        } else {
            $user_time .= '注册时间: 未知<br>';
        }
        if ($last_login) {
            $user_time .= '最后登录时间: ' . date('Y-m-d H:i:s', strtotime($last_login)) . '<br>';
            $login_device_info = get_user_meta($user_id, 'login_device_info', true);
            $user_time .= '登录设备信息: ' . $login_device_info;
        } else {
            $user_time .= '最后登录时间: 未知';
        }
        return $user_time;
    }
    return $value;
}
add_filter('manage_users_custom_column', 'wpbfd_display_user_time', 10, 3);
// 添加用户时间自定义列结束

// 获取设备
function wpbfd_add_device_meta_fields() {
    
    add_user_meta_field('login_device_info');
}
add_action('admin_init', 'wpbfd_add_device_meta_fields');
// 获取设备结束


// 上传附件自动随机命名
if (get_option('enable_random_attachment_name', '0') === '1') {
    // 添加上传预处理过滤器
    add_filter('wp_handle_upload_prefilter', 'rename_uploaded_file');

    function rename_uploaded_file($file) {
        // 检查 $file 是否被定义并且不为 null
        if (isset($file) && isset($file['name'])) {
            $file_info = pathinfo($file['name']);
            $extension = isset($file_info['extension']) ? '.' . $file_info['extension'] : '';
            $new_filename = date('YmdHis') . '_' . wp_generate_password(6, false) . $extension;
            $file['name'] = $new_filename;
        } else {
            // 处理 $file 未定义或者为 null 的情况
            // 例如：记录错误日志或者进行其他适当的处理
            error_log("错误：文件未定义或者为 null");
        }
        return $file;
    }
}
// 上传附件自动随机命名结束

// 消息提醒
function wpbfd_optimize_add_lazy_loading_and_category_settings() {
    if (isset($_POST['toggle_settings'])) {
        $settings_updated = false; // 标记是否有设置更新

        // 保存图片延迟加载设置
        $lazy_loading_value = isset($_POST['lazy_loading']) ? $_POST['lazy_loading'] : '0';
        $result_lazy_loading = update_option('wpbfd_optimize_lazy_loading', $lazy_loading_value);
        if ($result_lazy_loading) {
            $settings_updated = true;
        }

        // 保存隐藏文章图片开关设置
        $hide_cover_image_value = isset($_POST['hide_cover_image']) ? $_POST['hide_cover_image'] : '0';
        $result_hide_cover_image = update_option('wpbfd_optimize_hide_cover_image', $hide_cover_image_value);
        if ($result_hide_cover_image) {
            $settings_updated = true;
        }

        // 保存图片转换为WebP设置
        $convert_to_webp_value = isset($_POST['convert_to_webp']) ? $_POST['convert_to_webp'] : '0';
        $result_convert_to_webp = update_option('wpbfd_optimize_convert_to_webp', $convert_to_webp_value);
        if ($result_convert_to_webp) {
            $settings_updated = true;
        }

        // 保存添加图片alt设置
        $add_image_alt_value = isset($_POST['add_image_alt']) ? $_POST['add_image_alt'] : '0';
        $result_add_image_alt = update_option('wpbfd_optimize_add_image_alt', $add_image_alt_value);
        if ($result_add_image_alt) {
            $settings_updated = true;
        }

        // 保存category按钮设置
        $result_category = update_option('no_category_base_enabled', isset($_POST['no_category_base_enabled']) ? $_POST['no_category_base_enabled'] : 'off');
        if ($result_category) {
            $settings_updated = true;
        }

        // 保存用户时间设置
        $show_user_time_value = isset($_POST['show_user_time']) ? $_POST['show_user_time'] : '0';
        $result_show_user_time = update_option('wpbfd_optimize_show_user_time', $show_user_time_value);
        if ($result_show_user_time) {
            $settings_updated = true;
        }

        // 保存用户IP设置
        $user_ip_value = isset($_POST['user_ip']) ? $_POST['user_ip'] : '0';
        $result_user_ip = update_option('wpbfd_optimize_get_user_ip', $user_ip_value);
        if ($result_user_ip) {
            $settings_updated = true;
            // 添加用户IP字段到用户meta表
            if ($user_ip_value === '1') {
                add_user_meta_field('registration_ip');
                add_user_meta_field('last_login_ip');
            }
        }

        // 保存随机命名
        $enable_random_attachment_name = isset($_POST['enable_random_attachment_name']) ? $_POST['enable_random_attachment_name'] : '0';
        $result_enable_random_attachment_name = update_option('enable_random_attachment_name', $enable_random_attachment_name);
        if ($result_enable_random_attachment_name) {
            $settings_updated = true;
        }

        if ($settings_updated) {
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


// 添加用户meta字段 用于ip
function add_user_meta_field($field_name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'usermeta';
    $user_id = get_current_user_id(); 

    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'meta_key' => $field_name,
            'meta_value' => ''
        ),
        array(
            '%d',
            '%s',
            '%s'
        )
    );
}


// 主页设置
function wpdfdoptimize_main_page() {
        // 检查用户权限
    if (!current_user_can('manage_options')) {
        wp_die('您无权限访问这个页面');
    }
    
    wpbfd_optimize_add_lazy_loading_and_category_settings();

    // 获取开关设置
    $enabled_lazy_loading = get_option('wpbfd_optimize_lazy_loading', '0'); // 获取图片延迟加载
    $enabled_hide_cover_image = get_option('wpbfd_optimize_hide_cover_image', '0');  // 获取文中图片
    $enabled_add_image_alt = get_option('wpbfd_optimize_add_image_alt', '0'); // 获取图片标题属性设置
    $enabled_convert_to_webp = get_option('wpbfd_optimize_convert_to_webp', '0'); // 获取webp设置
    $enabled_category = get_option('no_category_base_enabled', 'off'); // 获取分类链接设置
    $enabled_user_ip = get_option('wpbfd_optimize_get_user_ip', '0'); // 获取用户ip设置
    $enabled_user_time = get_option('wpbfd_optimize_show_user_time', '0'); // 获取用户时间设置
    $enabled_random_attachment_name = get_option('enable_random_attachment_name', '0'); // 获取附件自动随机命名设置

    ?>
    <div class="wrap">
        <h1>小半WP优化助手  - 基础优化设置(插件设置之后最好去保存下固定链接)</h1>


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
                <label>隐藏全站文章中的图片(登录可见)：</label>
                <label class="switch">
                    <input type="checkbox" name="hide_cover_image" value="1" <?php checked($enabled_hide_cover_image, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>为文章中的图片添加alt和title属性：</label>
                <label class="switch">
                    <input type="checkbox" name="add_image_alt" value="1" <?php checked($enabled_add_image_alt, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>将文章图片转换为WebP格式(需要启用完整的GD库)：</label>
                <label class="switch">
                    <input type="checkbox" name="convert_to_webp" value="1" <?php checked($enabled_convert_to_webp, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>删掉分类中间的category链接：</label>
                <label class="switch">
                    <input type="checkbox" name="no_category_base_enabled" value="on" <?php checked($enabled_category, 'on'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>显示用户IP(仅限启用之后的用户)：</label>
                <label class="switch">
                    <input type="checkbox" name="user_ip" value="1" <?php checked($enabled_user_ip, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>显示用户登录时间与设备信息：</label>
                <label class="switch">
                    <input type="checkbox" name="show_user_time" value="1" <?php checked($enabled_user_time, '1'); ?>>
                    <span class="slider round"></span>
                </label>
            </p>

            <p>
                <label>上传附件自动随机命名：</label>
                <label class="switch">
                    <input type="checkbox" name="enable_random_attachment_name" value="1" <?php checked(get_option('enable_random_attachment_name', '0'), '1'); ?>>
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
            特别说明、删掉category的代码全部来自No category base插件，用这里就不要安装插件了，保存之后重新保存下固定链接<br>
            <p>插件QQ群：<a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=dgfThTp7nW4_hoRc1wjaGWEKlNmemlqB&jump_from=webapi&authKey=kwUfvush+fV1G/4Mvr5cva6EnWnQPave2J61QzmfTmUEk+OdGg6c9H1tPaHQYjLJ"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="Wordpress运营技术瞎折腾" title="Wordpress运营技术瞎折腾"></a></p>
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
        // 检测 Redis 扩展是否安装
        if (extension_loaded('redis')) {
            ?>
            <p>Redis 扩展状态：<span style="color: green;">已安装</span></p>
            <?php
        } else {
            ?>
            <p>Redis 扩展状态：<span style="color: red;">未安装</span> 功能强大，但是也占用更多的内存，一般是大型网站特别是商城类使用更多.</p><?php
        }
        // 检测 Memcached 扩展是否安装
        if (extension_loaded('memcached')) {
            ?>
            <p>Memcached 扩展状态：<span style="color: green;">已安装</span></p>
            <?php
        } else {
            ?>
            <p>Memcached 扩展状态：<span style="color: red;">未安装</span> 功能相对较少，一般比Redis占用更少的内存，以展示为主的网站够用了.</p>
            <?php
        }

        ?>
        <!-- 清除对象缓存按钮结束 -->
        <!-- 基础信息 -->
        <p>小半WP优化助手基础说明：</p>
        1、插件可能不兼容你的网站(和主题或其他插件冲突)，如果你在主题functions.php里面加过相关功能代码，先去删掉<br>
        2、现在很多主题也自带很多优化功能了，对比下主题的功能，尽量不要重复了<br>
        3、先禁用其他优化插件(比如Autoptimize、WP Super Cache等)，测试正常之后，再去启用这些插件；如果你网站开启了CDN，去刷新下CDN再看<br>
        <font style="color: #FF3300;">4、优化数据库之前，一定要先备份数据库！先备份数据库！先备份数据库！</font><br>
        5、如果你网站有开启Redis、Memcached对象缓存，基础功能优化之后就清除下对象缓存，如果没有就不用点<br>
        6、如果插件一启用就造成网站不正常，代码冲突引起的，就去服务器里面删掉这个插件文件夹，名称：WPBFDoptimizations<br>
        7、可以联系我进行反馈，根据具体错误原因，之后更新兼容，也可以自己看代码修复.<br>
        8、插件相关函数说明查看：<a href="https://www.jingxialai.com/4307.html" target="_blank">小半WP优化助手</a>方便你自己更改增加代码 ｜ GitHub下载：<a href="https://www.jingxialai.com/4307.html" target="_blank">小半WP优化助手</a><br>

    </div>
<!-- 基础信息结束 -->
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


// 在插件激活和停用时刷新重写规则
register_activation_hook(__FILE__, 'wpbfd_optimize_activate');
register_deactivation_hook(__FILE__, 'wpbfd_optimize_deactivate');

function wpbfd_optimize_activate() {
    // 在激活插件时刷新重写规则
    flush_rewrite_rules();
}

function wpbfd_optimize_deactivate() {
    // 在停用插件时刷新重写规则
    flush_rewrite_rules();
}

// 卸载插件之后删掉数据库里面的设置值
register_uninstall_hook(__FILE__, 'wpbfd_optimize_uninstall');

function wpbfd_optimize_uninstall() {
    // 删除插件在数据库中存储的数据
    delete_option('wpbfd_optimize_lazy_loading');
    delete_option('wpbfd_optimize_hide_cover_image');
    delete_option('wpbfd_optimize_add_image_alt');
    delete_option('wpbfd_optimize_convert_to_webp');
    delete_option('no_category_base_enabled');
    delete_option('wpbfd_optimize_get_user_ip');
    delete_option('wpbfd_optimize_show_user_time');
    
    // 删除用户meta字段
    remove_user_meta_field('registration_ip');
    remove_user_meta_field('last_login_ip');
    remove_user_meta_field('last_login_time');
}

function remove_user_meta_field($field_name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'usermeta';
    $user_id = get_current_user_id(); 

    $wpdb->delete(
        $table_name,
        array(
            'meta_key' => $field_name
        ),
        array(
            '%s'
        )
    );
}