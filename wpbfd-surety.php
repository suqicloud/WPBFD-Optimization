<?php
// 二级页面，基础安全页面wpbfd-surety
if (!defined('ABSPATH')) {
    exit;
}

function wpbfd_surety_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('您无权限访问这个页面');
    }

    global $pagenow;
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wpbfd-surety')  {
    //仅在这个页面生效的css
    // 保存成功提醒
    $settings_saved = isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true';
    // 保存失败提醒
    $settings_failed = isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'false';

    // 获取参数
    $parameter1 = get_option('wpbfd_surety_parameter1');
    $parameter2 = get_option('wpbfd_surety_parameter2');

    // 显示参数链接
    $login_url = wp_login_url() . '?' . urlencode($parameter1) . '=' . urlencode($parameter2);
    ?>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }
            .wrap {
                max-width: 95%;
                margin: 20px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }

            .wpbfd-surety-function-divider {
                margin: 10px 0;
                border-bottom: 1px solid #ddd;
            }
            .wpbfd-surety-redirect-url-input {
                width: 350px;
            }
            .wpbfd-surety-devices-url-input {
                width: 350px;
            }
            h2 {
                font-size: 1.5em;
                color: #444;
                margin: 0 0 10px;
            }

            .wpbfd-surety-submit {
                display: inline-block;
                color: #fff;
                padding: 8px 20px;
                border-radius: 5px;
                text-decoration: none;
                font-size: 1em;
                transition: background-color 0.3s ease;
                background-color: #0073aa;
                border: none;
                cursor: pointer;
            }

            .wpbfd-surety-notice {
                padding: 10px;
                border-radius: 5px;
                max-width: 90%;
                margin: 10px auto;
                font-size: 14px;
                opacity: 1;
                transition: opacity 0.5s ease;
            }
            .wpbfd-surety-notice.success {
                background-color: #4CAF50;
                color: #fff;
            }
            .wpbfd-surety-notice.error {
                background-color: #f44336;
                color: #fff;
            }
            .wpbfd-surety-notice.hidden {
                opacity: 0;
                display: none;
            }

            .wpbfd-surety-form-table {
                width: 100%;
            }
            .wpbfd-surety-form-table th,
            .wpbfd-surety-form-table td {
                padding: 8px;
            }
            .wpbfd-surety-form-table th {
                font-weight: bold;
                vertical-align: middle;
            }
            .wpbfd-surety-form-table td input[type="text"],
            .wpbfd-surety-form-table td textarea {
                display: block;
                border-radius: 4px;
                box-sizing: border-box;
                padding: 2px;
            }
            .wpbfd-surety-form-table td textarea {
                min-height: 100px;
                resize: vertical;
            }
            .wpbfd-surety-form-table .submit-button-container {
                margin-top: 10px;
            }
            .wpbfd-surety-form-table .submit-button-container .submit-button {
                display: inline-block;
                padding: 8px 20px;
                border-radius: 5px;
                text-decoration: none;
                font-size: 1em;
                transition: background-color 0.3s ease;
                background-color: #0073aa;
                border: none;
                cursor: pointer;
                vertical-align: middle;
            }

        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const notices = document.querySelectorAll('.wpbfd-surety-notice');
            notices.forEach(notice => {
                setTimeout(() => {
                    notice.classList.add('hidden');
                }, 3000);
            });
        });
        </script>

    <div class="wrap">
        <h2>小半WP优化助手 - 基础安全优化</h2>
        <?php if ( $settings_saved ) : ?>
            <div class="wpbfd-surety-notice success">
                保存成功!
            </div>
        <?php endif; ?>
        <?php if ( $settings_failed ) : ?>
            <div class="wpbfd-surety-notice error">
                保存失败，原因未知.
            </div>
        <?php endif; ?>
        <form method="post" action="options.php" class="wpbfd-surety-form-table">
            <?php settings_fields('wpbfd_surety_settings_group'); ?>
            <?php do_settings_sections('wpbfd-surety-settings'); ?>
            <?php submit_button(); ?>
        </form>
        <p>保存之后，新的登录地址就是：<code style="color: blue;"><?php echo esc_url( $login_url ); ?></code> 请记住了！</p>
        
         <form method="post" action="options.php" class="wpbfd-surety-form-table">
                <input type="hidden" name="reset_default_login" value="true">
                <button type="submit" class="button button-primary">恢复默认登录页面</button>
            </form>
        <p>恢复之后登录地址就是默认页面：<code style="color: blue;"><?php echo esc_url( wp_login_url() ); ?></code> </p>允许错误登录说明：开启之后登录可以试错，不开启只要用户名和密码不对就直接跳转，不建议开启，一般只用于测试某些功能。
    </div>

    <div class="wrap">
        <form method="post" action="options.php" class="wpbfd-surety-form-table">
            <?php settings_fields('wpbfd_surety_ip_settings_group'); ?>
            <?php do_settings_sections('wpbfd-surety-ip-settings'); ?>
            <?php submit_button(); ?>
        </form>
        <p>只是禁止登录，但是依旧可以正常浏览网站其他页面，如果设置了自定义后台登录参数，那只要错误了1次就会被封禁。</p>
        <?php wpbfd_surety_display_blocked_ips(); ?>
    </div>

    <div class="wrap">
        <form method="post" action="options.php" class="wpbfd-surety-form-table">
            <?php settings_fields('wpbfd_surety_blocked_query_params_group'); ?>
            <?php do_settings_sections('wpbfd-surety-blocked-query-params-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>

    <div class="wrap">
        <form method="post" action="options.php" class="wpbfd-surety-form-table">
            <?php settings_fields('wpbfd_surety_max_devices_group'); ?>
            <?php do_settings_sections('wpbfd-surety-max-devices-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>

    <div class="wrap">
        <form method="post" action="options.php" class="wpbfd-surety-form-table">
            <?php settings_fields('wpbfd_surety_disable_password_reset_group'); ?>
            <?php do_settings_sections('wpbfd-surety-disable-password-reset-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>

    <div class="wrap">
        <form method="post" action="options.php" class="wpbfd-surety-form-table">
            <?php settings_fields('wpbfd_surety_disable_user_sitemap_group'); ?>
            <?php do_settings_sections('wpbfd-surety-disable-user-sitemap-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
        
    <?php
}
}

// 登录参数设置开始
add_action('admin_init', 'wpbfd_surety_settings_init');
function wpbfd_surety_settings_init() {
    register_setting('wpbfd_surety_settings_group', 'wpbfd_surety_parameter1');//登录参数1
    register_setting('wpbfd_surety_settings_group', 'wpbfd_surety_parameter2');//登录参数2 
    register_setting('wpbfd_surety_settings_group', 'wpbfd_surety_allow_error_login');//是否允许错误登录
    
    add_settings_section('wpbfd_surety_settings_section', '自定义后台登录参数设置', 'wpbfd_surety_settings_section_callback', 'wpbfd-surety-settings');

    add_settings_field('wpbfd_surety_parameter1', '参数', 'wpbfd_surety_parameter1_callback', 'wpbfd-surety-settings', 'wpbfd_surety_settings_section');
    add_settings_field('wpbfd_surety_parameter2', '密码', 'wpbfd_surety_parameter2_callback', 'wpbfd-surety-settings', 'wpbfd_surety_settings_section');
    add_settings_field('wpbfd_surety_allow_error_login', '是否允许错误登录', 'wpbfd_surety_allow_error_login_callback', 'wpbfd-surety-settings', 'wpbfd_surety_settings_section');
}


function wpbfd_surety_settings_section_callback() {
    echo '设置之后，访问默认登录页面就会自动跳转到首页，只能用带参数密码的地址才能登录.';
}

// 参数1
function wpbfd_surety_parameter1_callback() {
    $parameter1 = get_option('wpbfd_surety_parameter1');
    echo '<input type="text" name="wpbfd_surety_parameter1" value="' . esc_attr($parameter1) . '" />';
}

// 参数2 密码
function wpbfd_surety_parameter2_callback() {
    $parameter2 = get_option('wpbfd_surety_parameter2');
    echo '<input type="text" name="wpbfd_surety_parameter2" value="' . esc_attr($parameter2) . '" />';
}

// 允许错误复选框功能
function wpbfd_surety_allow_error_login_callback() {
    $allow_error_login = get_option('wpbfd_surety_allow_error_login');
    ?>
    <input type="checkbox" name="wpbfd_surety_allow_error_login" <?php checked($allow_error_login, 'on'); ?> />
    <label>允许错误登录</label>
    <?php
}

// 恢复按钮功能
add_action('admin_init', 'reset_default_login');
function reset_default_login() {
    if (isset($_POST['reset_default_login']) && $_POST['reset_default_login'] === 'true') {
        // 如果用户点击了恢复默认登录页面按钮
        // 删除之前设置的登录参数
        delete_option('wpbfd_surety_parameter1');
        delete_option('wpbfd_surety_parameter2');
        // 重定向回设置页面，可以显示成功消息
        wp_redirect(admin_url('admin.php?page=wpbfd-surety&settings-updated=true'));
        exit;
    }
}

// 默认登录页面自动跳转到首页
add_action('login_enqueue_scripts', 'custom_login_redirect');
function custom_login_redirect() {
    // 检查是否有设置参数，如果没有则不进行跳转
    $parameter1 = get_option('wpbfd_surety_parameter1');
    $parameter2 = get_option('wpbfd_surety_parameter2');
    $allow_error_login = get_option('wpbfd_surety_allow_error_login');

    // 如果参数都未设置，则不进行任何重定向
    if (empty($parameter1) || empty($parameter2)) {
        return;
    }
    
    // 如果未勾选“允许错误登录”，直接验证参数并重定向
    if ($allow_error_login !== 'on') {
        if (empty($_GET[$parameter1]) || $_GET[$parameter1] !== $parameter2) {
            wp_redirect(home_url());
            exit;
        }
    } else {
        // 如果勾选了“允许错误登录”
        // 如果是登录页面，并且用户提交了用户名和密码
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log']) && isset($_POST['pwd'])) {
            $username = $_POST['log'];
            $password = $_POST['pwd'];

            // 验证用户名和密码
            $user = wp_authenticate($username, $password);

            // 如果用户名不存在或者密码错误，继续登录流程
            if (is_wp_error($user)) {
                return;
            }
        }

        // 如果用户没有提供正确的登录参数，重定向到首页
        if (empty($_GET[$parameter1]) || $_GET[$parameter1] !== $parameter2) {
            wp_redirect(home_url());
            exit;
        }
    }
}


// 登录参数设置结束

//ip封锁代码开始
add_action('admin_init', 'wpbfd_surety_ip_settings_init');
function wpbfd_surety_ip_settings_init() {
    register_setting('wpbfd_surety_ip_settings_group', 'wpbfd_surety_max_login_attempts'); //登录次数
    register_setting('wpbfd_surety_ip_settings_group', 'wpbfd_surety_lockout_time'); //封禁时间
    register_setting('wpbfd_surety_ip_settings_group', 'wpbfd_surety_ip_whitelist'); //白名单
    register_setting('wpbfd_surety_ip_settings_group', 'wpbfd_surety_time_window'); //多少时间内
    register_setting('wpbfd_surety_ip_settings_group', 'wpbfd_surety_redirect_url');//跳转的URL
    register_setting('wpbfd_surety_ip_settings_group', 'wpbfd_surety_enable_ip_lockdown'); // 是否启用 IP 封锁

    add_settings_section('wpbfd_surety_ip_settings_section', '登录IP封锁设置', 'wpbfd_surety_ip_settings_section_callback', 'wpbfd-surety-ip-settings');
    add_settings_field('wpbfd_surety_time_window', '在多少秒内', 'wpbfd_surety_time_window_callback', 'wpbfd-surety-ip-settings', 'wpbfd_surety_ip_settings_section'); 
    add_settings_field('wpbfd_surety_max_login_attempts', '最多登录尝试次数', 'wpbfd_surety_max_login_attempts_callback', 'wpbfd-surety-ip-settings', 'wpbfd_surety_ip_settings_section');
    add_settings_field('wpbfd_surety_lockout_time', '封锁时间（秒）', 'wpbfd_surety_lockout_time_callback', 'wpbfd-surety-ip-settings', 'wpbfd_surety_ip_settings_section');
    add_settings_field('wpbfd_surety_ip_whitelist', 'IP白名单', 'wpbfd_surety_ip_whitelist_callback', 'wpbfd-surety-ip-settings', 'wpbfd_surety_ip_settings_section');
    add_settings_field('wpbfd_surety_redirect_url', '封锁后跳转的URL', 'wpbfd_surety_redirect_url_callback', 'wpbfd-surety-ip-settings', 'wpbfd_surety_ip_settings_section'); 
    add_settings_field('wpbfd_surety_enable_ip_lockdown', '启用 IP 封锁', 'wpbfd_surety_enable_ip_lockdown_callback', 'wpbfd-surety-ip-settings', 'wpbfd_surety_ip_settings_section');

}

// 默认基础设置框架
function wpbfd_surety_ip_settings_section_callback() {
    echo '在多少秒之内错误次数达到设置值，就不能再登录了，在禁止登录期间，用户还尝试登录就会跳转到指定url页面.';
}

// 添加复选框字段以控制是否启用 IP 封锁功能
function wpbfd_surety_enable_ip_lockdown_callback() {
    $enable_ip_lockdown = get_option('wpbfd_surety_enable_ip_lockdown', '0'); // 默认关闭
    echo '<input type="checkbox" name="wpbfd_surety_enable_ip_lockdown" value="1" ' . checked( $enable_ip_lockdown, '1', false ) . ' />';
}

// 封禁之后跳转的URL
function wpbfd_surety_redirect_url_callback() {
    $redirect_url = get_option('wpbfd_surety_redirect_url'); // 获取已保存的跳转URL
    echo '<input type="text" name="wpbfd_surety_redirect_url" value="' . esc_attr($redirect_url) . '" class="wpbfd-surety-redirect-url-input" />'; // 添加类名 wpbfd-surety-redirect-url-input
}

// 时间窗口
function wpbfd_surety_time_window_callback() {
    $time_window = get_option('wpbfd_surety_time_window', 60); // 默认60秒，1分钟
    echo '<input type="number" min="1" name="wpbfd_surety_time_window" value="' . esc_attr($time_window) . '" />';
}

// 登录尝试次数
function wpbfd_surety_max_login_attempts_callback() {
    $max_login_attempts = get_option('wpbfd_surety_max_login_attempts', 5); // 默认5次
    echo '<input type="number" min="1" name="wpbfd_surety_max_login_attempts" value="' . esc_attr($max_login_attempts) . '" />';
}

// 封禁时间
function wpbfd_surety_lockout_time_callback() {
    $lockout_time = get_option('wpbfd_surety_lockout_time', 300); // 默认封禁时间300秒，5分钟
    echo '<input type="number" min="1" name="wpbfd_surety_lockout_time" value="' . esc_attr($lockout_time) . '" />';
}

// ip白名单
function wpbfd_surety_ip_whitelist_callback() {
    $ip_whitelist = get_option('wpbfd_surety_ip_whitelist');
    echo '<textarea name="wpbfd_surety_ip_whitelist" rows="4" cols="50">' . esc_textarea($ip_whitelist) . '</textarea>';
   echo ' <p>一行一个</p>';
}

// 展示被封的ip
function wpbfd_surety_display_blocked_ips() {
    if (isset($_POST['unblock_ip'])) {
        $ip_to_unblock = $_POST['unblock_ip'];
        $blocked_ips = get_option('wpbfd_surety_blocked_ips', array());
        if (isset($blocked_ips[$ip_to_unblock])) {
            unset($blocked_ips[$ip_to_unblock]);
            update_option('wpbfd_surety_blocked_ips', $blocked_ips);
        }
    }

    $blocked_ips = get_option('wpbfd_surety_blocked_ips', array());
    $ip_whitelist = get_option('wpbfd_surety_ip_whitelist');

    // 封禁ip
    echo '<h3>被封锁的IP地址</h3>';
    echo '<ul>';
    foreach ($blocked_ips as $ip => $unlock_time) {
        if (is_int($unlock_time)) { // 确保 $unlock_time 是整数类型的时间戳
            echo '<li>' . esc_html($ip) . ' - 封锁至 ' . date('Y-m-d H:i:s', $unlock_time) . '
            <form method="post" style="display:inline;">
            <input type="hidden" name="unblock_ip" value="' . esc_attr($ip) . '">
            <button type="submit" class="button">解封</button>
            </form>
            </li>';
        }
    }
    echo '</ul>';
}


// 被封的ip会跳转到指定url
add_action('wp_login_failed', 'wpbfd_surety_check_blocked_ips');
function wpbfd_surety_check_blocked_ips($username) {
    if (isset($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $blocked_ips = get_option('wpbfd_surety_blocked_ips', array());
        $redirect_url = get_option('wpbfd_surety_redirect_url'); // 获取管理员设置的跳转URL

        if (isset($blocked_ips[$ip_address])) {
            $unlock_time = $blocked_ips[$ip_address];
            if (is_int($unlock_time)) { // 确保 $unlock_time 是整数类型的时间戳
                if (time() < $unlock_time) {
                    wp_redirect($redirect_url); //如果被封了就重定向到设置的跳转URL
                    exit;
                } else {
                    unset($blocked_ips[$ip_address]);
                    update_option('wpbfd_surety_blocked_ips', $blocked_ips);
                }
            }
        }
    }
}


// 判断尝试的登录次数
add_action('wp_login_failed', 'wpbfd_surety_check_max_login_attempts');
function wpbfd_surety_check_max_login_attempts($username) {
    $enable_ip_lockdown = get_option('wpbfd_surety_enable_ip_lockdown', '0'); // 获取是否启用 IP 封锁的设置

    if ($enable_ip_lockdown === '1') { // 只有启用 IP 封锁时才执行下面的逻辑
    if (isset($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $blocked_ips = get_option('wpbfd_surety_blocked_ips', array());
        $max_attempts = get_option('wpbfd_surety_max_login_attempts', 5); // 默认3次
        $lockout_time = get_option('wpbfd_surety_lockout_time', 300); // 默认300秒，5分钟

        if (!isset($blocked_ips[$ip_address])) {
            $blocked_ips[$ip_address] = array('attempts' => 1, 'time' => time());
        } else {
            $last_attempt_time = $blocked_ips[$ip_address]['time'];
            if (time() - $last_attempt_time < $lockout_time) {
                $blocked_ips[$ip_address]['attempts'] += 1;
            } else {
                $blocked_ips[$ip_address] = array('attempts' => 1, 'time' => time());
            }

            if ($blocked_ips[$ip_address]['attempts'] >= $max_attempts) {
                // 被封的ip计算时间
                $blocked_ips[$ip_address]['unlock_time'] = time() + $lockout_time;
            }
        }

        update_option('wpbfd_surety_blocked_ips', $blocked_ips);
        }
    }
}
//ip封锁代码结束

// query参数拦截开始
add_action('admin_init', 'wpbfd_surety_settings_query_init');
function wpbfd_surety_settings_query_init() {
    register_setting('wpbfd_surety_blocked_query_params_group', 'wpbfd_surety_blocked_query_params');
    
    add_settings_section('wpbfd_surety_blocked_query_params_section', '查询参数拦截设置', 'wpbfd_surety_blocked_query_params_section_callback', 'wpbfd-surety-blocked-query-params-settings');

    add_settings_field('wpbfd_surety_blocked_query_params_field', '要拦截的查询参数', 'wpbfd_surety_blocked_query_params_field_callback', 'wpbfd-surety-blocked-query-params-settings', 'wpbfd_surety_blocked_query_params_section');
}

function wpbfd_surety_blocked_query_params_section_callback() {
    echo '设置要拦截的查询参数，当携带这些参数进行访问时会提示 403 错误，防范恶意统计流量访问。<br>比如qq,wx，那么访问你的：网址/?qq 就会提示403错误';
}

// 设置query参数
function wpbfd_surety_blocked_query_params_field_callback() {
    echo '<textarea name="wpbfd_surety_blocked_query_params" rows="2" cols="50">' . esc_textarea(get_option('wpbfd_surety_blocked_query_params', '')) . '</textarea>';
    echo '<p>多个查询参数之间用逗号分隔</p>';
}

// 如果携带query参数就403
add_action('init', 'wpbfd_surety_intercept_query_params');
function wpbfd_surety_intercept_query_params() {
    $blocked_query_params = get_option('wpbfd_surety_blocked_query_params', '');
    $blocked_params_array = explode(',', $blocked_query_params);
    
    foreach ($blocked_params_array as $param) {
        if (isset($_GET[$param])) {
            wp_die(__('您没有权限访问这个页面.', 'textdomain'), __('Access Denied', 'textdomain'), array('response' => 403));
            exit;
        }
    }
}
// query参数拦截结束

// 用户最大登录设备限制设置开始
add_action('admin_init', 'wpbfd_surety_settings_max_devices_init');
function wpbfd_surety_settings_max_devices_init() {
    register_setting('wpbfd_surety_max_devices_group', 'wpbfd_surety_max_devices_count');
    register_setting('wpbfd_surety_max_devices_group', 'wpbfd_surety_max_devices_redirect_url');
    
    add_settings_section('wpbfd_surety_max_devices_section', '用户最大登录设备限制设置', 'wpbfd_surety_max_devices_section_callback', 'wpbfd-surety-max-devices-settings');

    add_settings_field('wpbfd_surety_max_devices_count_field', '最大登录设备数量', 'wpbfd_surety_max_devices_count_field_callback', 'wpbfd-surety-max-devices-settings', 'wpbfd_surety_max_devices_section');
    add_settings_field('wpbfd_surety_max_devices_redirect_url_field', '超出限制时跳转的URL', 'wpbfd_surety_max_devices_redirect_url_field_callback', 'wpbfd-surety-max-devices-settings', 'wpbfd_surety_max_devices_section');
}

function wpbfd_surety_max_devices_section_callback() {
    echo '设置用户最大登录设备数量以及超出限制时跳转的URL。';
}

// 设置最大登录设备数量字段回调函数
function wpbfd_surety_max_devices_count_field_callback() {
    $max_devices_count = get_option('wpbfd_surety_max_devices_count', 2);
    echo '<input type="number" name="wpbfd_surety_max_devices_count" value="' . esc_attr($max_devices_count) . '" min="1" step="1" />';
    echo '<p>设置允许的最大登录设备数量</p>';
}

// 设置跳转URL字段回调函数
function wpbfd_surety_max_devices_redirect_url_field_callback() {
    $redirect_url = get_option('wpbfd_surety_max_devices_redirect_url', home_url());
    echo '<input type="url" name="wpbfd_surety_max_devices_redirect_url" value="' . esc_url($redirect_url) . '" class="wpbfd-surety-devices-url-input"/>';
    echo '<p>设置超出最大登录设备数量限制时跳转的URL</p>';
}

// 在用户登录时更新已登录设备数量
add_action('wp_login', 'wpbfd_update_logged_in_devices_count', 10, 2);
function wpbfd_update_logged_in_devices_count($user_login, $user) {
    $logged_in_devices = get_user_meta($user->ID, 'wpbfd_logged_in_devices', true);

    // 如果用户没有已登录设备，则初始化为一个空数组
    if (!$logged_in_devices) {
        $logged_in_devices = array();
    }

    // 添加当前设备到已登录设备列表
    $current_device = md5($_SERVER['HTTP_USER_AGENT']);
    $logged_in_devices[$current_device] = time();

    // 更新用户的已登录设备列表
    update_user_meta($user->ID, 'wpbfd_logged_in_devices', $logged_in_devices);
}

// 检查登录设备数量并进行跳转
add_action('wp_login', 'wpbfd_check_logged_in_devices_count', 10, 2);
function wpbfd_check_logged_in_devices_count($user_login, $user) {
    $max_devices_allowed = get_option('wpbfd_surety_max_devices_count', ''); // 获取设置的最大登录设备数量
    $redirect_url = get_option('wpbfd_surety_max_devices_redirect_url', ''); // 获取设置的跳转URL

    // 如果最大登录设备数量或跳转URL为空，则不执行任何判断
    if ($max_devices_allowed === '' || $redirect_url === '') {
        return;
    }

    $logged_in_devices = get_user_meta($user->ID, 'wpbfd_logged_in_devices', true);

    // 如果设备数量超过允许的最大数量，则进行跳转
    if (count($logged_in_devices) > intval($max_devices_allowed)) {
        wp_logout(); // 注销当前用户
        wp_redirect($redirect_url); // 跳转到指定页面
        exit;
    }
}

// 用户最大登录设备限制设置结束


// 关闭找回密码
add_action('admin_init', 'wpbfd_surety_settings_disable_password_reset_init');
function wpbfd_surety_settings_disable_password_reset_init() {
    register_setting('wpbfd_surety_disable_password_reset_group', 'wpbfd_surety_disable_password_reset');

    add_settings_section('wpbfd_surety_disable_password_reset_section', '关闭找回密码功能', 'wpbfd_surety_disable_password_reset_section_callback', 'wpbfd-surety-disable-password-reset-settings');

    add_settings_field('wpbfd_surety_disable_password_reset_field', '关闭找回密码', 'wpbfd_surety_disable_password_reset_field_callback', 'wpbfd-surety-disable-password-reset-settings', 'wpbfd_surety_disable_password_reset_section');
}

function wpbfd_surety_disable_password_reset_section_callback() {
    echo '勾选后关闭找回密码功能，并隐藏找回密码入口，关闭之后会提示“不能重设该用户的密码”。';
}

function wpbfd_surety_disable_password_reset_field_callback() {
    $disable_password_reset = get_option('wpbfd_surety_disable_password_reset', '');
    echo '<input type="checkbox" name="wpbfd_surety_disable_password_reset" value="1" ' . checked($disable_password_reset, '1', false) . ' />';
}

// 检查是否关闭找回密码功能
add_action('login_init', 'wpbfd_surety_disable_password_reset');
function wpbfd_surety_disable_password_reset() {
    $disable_password_reset = get_option('wpbfd_surety_disable_password_reset', '');

    if ($disable_password_reset === '1') {
        add_filter('allow_password_reset', '__return_false');
        add_action('login_footer', 'wpbfd_surety_hide_password_reset_link');
    }
}

// 隐藏找回密码入口
function wpbfd_surety_hide_password_reset_link() {
    echo '<style>#nav a[href*="wp-login.php?action=lostpassword"] {
    display: none;
}
</style>';
}
// 关闭找回密码结束

// 屏蔽用户Sitemap功能开始
add_action('admin_init', 'wpbfd_surety_settings_disable_user_sitemap_init');
function wpbfd_surety_settings_disable_user_sitemap_init() {
    register_setting('wpbfd_surety_disable_user_sitemap_group', 'wpbfd_surety_disable_user_sitemap');

    add_settings_section(
        'wpbfd_surety_disable_user_sitemap_section',
        '屏蔽默认用户Sitemap',
        'wpbfd_surety_disable_user_sitemap_section_callback',
        'wpbfd-surety-disable-user-sitemap-settings'
    );

    add_settings_field(
        'wpbfd_surety_disable_user_sitemap_field',
        '禁用默认用户Sitemap',
        'wpbfd_surety_disable_user_sitemap_field_callback',
        'wpbfd-surety-disable-user-sitemap-settings',
        'wpbfd_surety_disable_user_sitemap_section'
    );
}

function wpbfd_surety_disable_user_sitemap_section_callback() {
    echo '勾选后将禁止生成用户相关的Sitemap文件（如 wp-sitemap-users-1.xml），有助于防止用户名泄露。';
}

function wpbfd_surety_disable_user_sitemap_field_callback() {
    $disable_user_sitemap = get_option('wpbfd_surety_disable_user_sitemap', '');
    echo '<input type="checkbox" name="wpbfd_surety_disable_user_sitemap" value="1" ' . checked($disable_user_sitemap, '1', false) . ' />';
    echo '<label>禁用默认的用户users地图xml</label>';
}

// 移除用户sitemap
add_filter('wp_sitemaps_add_provider', 'wpbfd_surety_maybe_disable_user_sitemap_provider', 10, 2);
function wpbfd_surety_maybe_disable_user_sitemap_provider($provider, $name) {
    $disable_user_sitemap = get_option('wpbfd_surety_disable_user_sitemap', '');

    if ($disable_user_sitemap === '1' && $name === 'users') {
        return null;
    }
    return $provider;
}
// 屏蔽用户Sitemap功能结束

// 插件主文件中添加插件卸载钩子
register_uninstall_hook(__FILE__, 'wpbfd_surety_plugin_uninstall');

// 当插件被卸载时执行的函数
function wpbfd_surety_plugin_uninstall() {
    // 清理数据库中的插件相关数据
    delete_option('wpbfd_surety_parameter1');
    delete_option('wpbfd_surety_parameter2');
    delete_option('wpbfd_surety_max_login_attempts');
    delete_option('wpbfd_surety_lockout_time');
    delete_option('wpbfd_surety_ip_whitelist');
    delete_option('wpbfd_surety_time_window');
    delete_option('wpbfd_surety_blocked_ips');
    delete_option('wpbfd_surety_blocked_query_params');
    delete_option('wpbfd_surety_redirect_url');
    delete_option('wpbfd_surety_max_devices_count');
    delete_option('wpbfd_surety_max_devices_redirect_url');
    delete_option('wpbfd_surety_disable_user_sitemap');
}

?>