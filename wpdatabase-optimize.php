<?php

//by summer
//https://www.jingxialai.com/4307.html
// 插件设置页面内容
function optimize_postmeta_page() {
     global $wpdb;
    ?>
<style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
        background-color: #f5f5f5;
    }

    .wrap {
        max-width: 95%;
        /*margin: 0 auto;*/
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .optimize-function-divider {
        margin: 5px 0;
        border-bottom: 1px solid #ddd;
    }

    h2 {
        font-size: 1.5em;
        color: #444;
        margin-bottom: 0;
    }

    .optimize-submit {
        display: inline-block;
        color: #fff;
        padding: 1px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 1em;
        transition: background-color 0.3s ease;
        margin-top: 1px;
    }

    .optimize-plugin-notice.custom-notice {
        background-color: #4CAF50;
        color: #fff;
        padding: 5px;
        /*margin: 10px 0; */ 
        margin: 0 auto;     
        border-radius: 5px;
        max-width: 90%;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
        table.widefat.fixed {
        table-layout: fixed;
    }

    table.widefat.fixed th {
        white-space: nowrap;
    }

    table.widefat.fixed th:nth-child(1) {
        width: 250px; /* 调整第一列的宽度 */
    }

    table.widefat.fixed th:nth-child(2) {
        width: 100px; /* 调整第二列的宽度 */
    }

    table.widefat.fixed th:nth-child(3) {
        width: 200px; /* 调整第三列的宽度 */
    }

    table.widefat.fixed th:nth-child(3) {
        width: 50px; /* 调整第三列的宽度 */
    }
    table.widefat.fixed th:nth-child(3) {
        width: 100px; /* 调整第三列的宽度 */
    }
    table.widefat.fixed th:nth-child(3) {
        width: 100px; /* 调整第三列的宽度 */
    }
    table.widefat.fixed th:nth-child(3) {
        width: 100px; /* 调整第三列的宽度 */
    }
    table.widefat.fixed tbody tr:nth-child(odd) {
        background-color: #fff; /* 白色背景 */
    }

    table.widefat.fixed tbody tr:nth-child(even) {
        background-color: #f0f0f0; /* 灰色背景 */
    }
</style>


    <div class="wrap">
                <div class="optimize-section">
            <h2>WPBFD - 数据库基础优化</h2>
            <p style="color: #FF0000; font-weight: bold;;">和数据库有关的操作，一定要先备份数据库！请提前备份数据库！请提前备份数据库！</p>
            关于Wordpress数据库优化详细说明：<a href="https://www.jingxialai.com/2472.html" target="_blank">数据库优化</a> 如果你对数据库不熟，尽量先看下说明，当然前提依旧先备份数据库！
        </div>
<!-- 删除不存在文章的元信息功能 -->
<div class="optimize-function-divider"></div>
<h2>删除不存在于文章的元数据</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfdremove_unused_metadata_nonce', 'wpbfdremove_unused_metadata_nonce'); ?>

    <?php
    $unused_metadata_count = get_unused_metadata_count();
    if ($unused_metadata_count > 0) {
        echo "<p>有{$unused_metadata_count}条不存在文章的元信息</p>";
    } else {
        echo "<p>暂时没有不存在文章的元信息</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_unused_metadata_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfdremove_unused_metadata_nonce', 'wpbfd_remove_unused_metadata', '删除不存在文章的元信息操作完成！', 'wpbfd_remove_unused_metadata_submit'); ?>


<!-- 删除不用的_edit_lock和_edit_last字段功能 -->
<div class="optimize-function-divider"></div>
<h2>删除孤立的关系数据</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_unused_edit_fields_nonce', 'wpbfd_remove_unused_edit_fields_nonce'); ?>

    <?php
    $unused_edit_fields_count = get_unused_edit_fields_count();
    if ($unused_edit_fields_count > 0) {
        echo "<p>有{$unused_edit_fields_count}条孤立的关系数据</p>";
    } else {
        echo "<p>暂时没有孤立的关系数据</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_unused_edit_fields_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_unused_edit_fields_nonce', 'wpbfd_remove_unused_edit_fields', '删除不用的孤立关系数据完成！', 'wpbfd_remove_unused_edit_fields_submit'); ?>


<!-- 删除旧的别名信息功能 -->
<div class="optimize-function-divider"></div>
<h2>删除旧别名(旧的slug)信息</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_old_aliases_nonce', 'wpbfd_remove_old_aliases_nonce'); ?>

    <?php
    $unused_old_aliases_count = get_unused_old_aliases_count();
    if ($unused_old_aliases_count > 0) {
        echo "<p>有{$unused_old_aliases_count}条不用的旧的别名信息</p>";
    } else {
        echo "<p>暂时没有旧的别名信息</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_old_aliases_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_old_aliases_nonce', 'wpbfd_remove_old_aliases', '删除旧的别名信息完成！', 'wpbfd_remove_old_aliases_submit'); ?>


<!-- 删除不存在文章的评论元信息功能 -->
<div class="optimize-function-divider"></div>
<h2>删除未使用的评论元数据</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_unused_commentmeta_nonce', 'wpbfd_remove_unused_commentmeta_nonce'); ?>

    <?php
    $unused_commentmeta_count = get_unused_commentmeta_count();
    if ($unused_commentmeta_count > 0) {
        echo "<p>有{$unused_commentmeta_count}条不存在文章的评论元信息</p>";
    } else {
        echo "<p>暂时没有不存在文章的评论元信息</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_unused_commentmeta_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_unused_commentmeta_nonce', 'wpbfd_remove_unused_commentmeta', '删除不存在文章的评论元信息操作完成！', 'wpbfd_remove_unused_commentmeta_submit'); ?>


<!-- 删除_pingback和trackback记录功能 -->
<div class="optimize-function-divider"></div>
<h2>删除数据库里面之前的pingback和trackback引用记录</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_pingback_trackback_nonce', 'wpbfd_remove_pingback_trackback_nonce'); ?>

    <?php
    $removed_pingback_trackback_count = get_removed_pingback_trackback_count();
    if ($removed_pingback_trackback_count > 0) {
        echo "<p>有{$removed_pingback_trackback_count}条之前的pingback和trackback引用记录</p>";
    } else {
        echo "<p>暂时没有之前的pingback和trackback引用记录</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_pingback_trackback_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_pingback_trackback_nonce', 'wpbfd_remove_pingback_trackback', '删除pingback和trackback记录完成！', 'wpbfd_remove_pingback_trackback_submit'); ?>


<!-- 删除与文章无关的标签功能 -->
<div class="optimize-function-divider"></div>
<h2>删除数据库里与文章无关的标签(没有引用的tag标签)</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_unused_tags_nonce', 'wpbfd_remove_unused_tags_nonce'); ?>

    <?php
    $unused_tags_count = get_unused_tags_count();
    if ($unused_tags_count > 0) {
        echo "<p>有{$unused_tags_count}条未引用的tag标签</p>";
    } else {
        echo "<p>暂时没有未引用的tag标签</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_unused_tags_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_unused_tags_nonce', 'wpbfd_remove_unused_tags', '删除与文章无关的标签完成！', 'wpbfd_remove_unused_tags_submit'); ?>


<!-- 删除数据库里的文章修订 -->
<div class="optimize-function-divider"></div>
<h2>删除数据库里面的文章修订记录</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_post_revisions_nonce', 'wpbfd_remove_post_revisions_nonce'); ?>

    <?php
    $post_revisions_count = get_post_revisions_count();
    if ($post_revisions_count > 0) {
        echo "<p>有{$post_revisions_count}条文章修订记录</p>";
    } else {
        echo "<p>暂时没有文章修订记录</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_post_revisions_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_post_revisions_nonce', 'wpbfd_remove_post_revisions', '删除文章修订操作完成！', 'wpbfd_remove_post_revisions_submit'); ?>


<!-- 删除数据库里的自动草稿 -->
<div class="optimize-function-divider"></div>
<h2>删除数据库里面的自动草稿</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_auto_drafts_nonce', 'wpbfd_remove_auto_drafts_nonce'); ?>

    <?php
    $auto_drafts_count = get_auto_drafts_count();
    
    if ($auto_drafts_count > 0) {
        echo "<p>有 {$auto_drafts_count} 条自动草稿</p>";
    } else {
        echo "<p>暂时没有自动草稿</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_auto_drafts_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_auto_drafts_nonce', 'wpbfd_remove_auto_drafts', '删除自动草稿操作完成！', 'remove_auto_drafts_submit'); ?>


<!-- 删除wp_session记录功能 -->
<div class="optimize-function-divider"></div>
<h2>删除未使用的会话记录(用户会话信息，用户制站点不建议删除)</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_wp_session_records_nonce', 'wpbfd_remove_wp_session_records_nonce'); ?>

    <?php
    $wp_session_records_count = get_wp_session_records_count();
    if ($wp_session_records_count > 0) {
        echo "<p>有{$wp_session_records_count}条未使用的会话记录</p>";
    } else {
        echo "<p>暂时没有未使用的会话记录</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_wp_session_records_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_wp_session_records_nonce', 'wpbfd_remove_wp_session_records', '删除未使用的会话记录操作完成！', 'wpbfd_remove_wp_session_records_submit'); ?>


<!-- 删除expired transients记录功能 -->
<div class="optimize-function-divider"></div>
<h2>删除已过期的瞬态数据(一种临时的存储数据)</h2>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_expired_transients_nonce', 'wpbfd_remove_expired_transients_nonce'); ?>

    <?php
    $expired_transients_count = get_expired_transients_count();
    if ($expired_transients_count > 0) {
        echo "<p>有{$expired_transients_count}条瞬态数据</p>";
    } else {
        echo "<p>暂时没有瞬态数据</p>";
    }
    ?>

    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_expired_transients_submit" class="button-primary" value="点击删除">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_expired_transients_nonce', 'wpbfd_remove_expired_transients', '删除已过期的瞬态数据操作完成！', 'wpbfd_remove_expired_transients_submit'); ?>


<!-- 新增一键删除外链的功能 -->
<div class="optimize-function-divider"></div>
<h2>删除文章里面的链接(会将所有链接变为当前文章网址,外链会保留rel="nofollow")</h2><br>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_remove_external_links_nonce', 'wpbfd_remove_external_links_nonce'); ?>
    <p class="optimize-submit">
        <input type="submit" name="wpbfd_remove_external_links_submit" class="button-primary" value="删除所有文章的链接">
    </p>
</form>
<?php perform_optimization('wpbfd_remove_external_links_nonce', 'wpbfd_remove_external_links', '删除所有文章的链接操作完成！', 'wpbfd_remove_external_links_submit'); ?>


<!-- 删除其它冗余信息功能 -->
        <div class="optimize-function-divider"></div>
        <h2>删除其它冗余信息(一些不需要的元数据)</h2><br>
        <form method="post" action="">
            <?php wp_nonce_field('wpbfd_remove_other_redundant_nonce', 'wpbfd_remove_other_redundant_nonce'); ?>
            <p class="optimize-submit">
                <input type="submit" name="wpbfd_remove_other_redundant_submit" class="button-primary" value="点击删除">
            </p>
        </form>
        <?php perform_optimization('wpbfd_remove_other_redundant_nonce', 'wpbfd_remove_other_redundant', '删除其它冗余信息操作完成！', 'wpbfd_remove_other_redundant_submit');?>

<!-- 使用OPTIMIZE TABLE优化全部记录功能 -->
<div class="optimize-function-divider"></div>
<h2>优化WordPress数据库表(MyISAM类型使用，InnoDB类型偶尔在低峰期执行，建议使用InnoDB)</h2><br>
<form method="post" action="">
    <?php wp_nonce_field('wpbfd_optimize_all_tables_nonce', 'wpbfd_optimize_all_tables_nonce'); ?>
    <p class="optimize-submit">
        <input type="submit" name="wpbfd_optimize_all_tables_submit" class="button-primary" value="点击优化">
    </p>
</form>
<?php perform_optimization('wpbfd_optimize_all_tables_nonce', 'wpbfd_optimize_all_tables', '优化数据库表操作完成！', 'wpbfd_optimize_all_tables_submit'); ?>



<!-- 新增功能：显示数据库表列表 -->
<div class="optimize-section">
    <h2>数据库表</h2>
        <?php
    // 输出成功或失败的消息
    if (isset($_POST['action']) && $_POST['action'] === 'convert_to_innodb') {
        $table_name = $_POST['table_name'];

        // 执行转化为 InnoDB 操作
        $result = convert_to_innodb_operation($table_name);

        // 检查是否成功，并添加相应的消息
        if ($result !== false) {
            // 输出成功消息
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p>' . esc_html("已成功转化表 {$table_name} 为 InnoDB！") . '</p>';
            echo '</div>';
        } else {
            // 输出失败消息
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p>' . esc_html("转化为 InnoDB 操作失败！数据表名可能是单独的名称，不属于wp默认函数") . '</p>';
            echo '</div>';
        }
    }
    ?>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th>数据库表名</th>
                <th>数据库类型</th>
                <th>转化为InnoDB</th>
                <th>行数</th>
                <th>数据大小</th>
                <th>索引大小</th>
                <th>总大小</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 获取数据库表信息
            $wordpress_tables = array(
                $wpdb->prefix . 'commentmeta',
                $wpdb->prefix . 'comments',
                $wpdb->prefix . 'links',
                $wpdb->prefix . 'options',
                $wpdb->prefix . 'postmeta',
                $wpdb->prefix . 'posts',
                $wpdb->prefix . 'term_relationships',
                $wpdb->prefix . 'term_taxonomy',
                $wpdb->prefix . 'termmeta',
                $wpdb->prefix . 'terms',
                $wpdb->prefix . 'usermeta',
                $wpdb->prefix . 'users',
            );

            $tables = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);

            $totalTables = 0;
            $totalRows = 0;
            $totalDataSize = 0;
            $totalIndexSize = 0;
            $totalSize = 0;

            foreach ($tables as $table) {
                $table_name = $table['Name'];

                // 是否显示转化为 InnoDB 按钮
                $show_innodb_button = ($table['Engine'] !== 'InnoDB');
                ?>

                <tr>
                    <td>
                        <?php
                        echo esc_html($table_name);
                        
                        // 检查是否为 WordPress 默认系统表
                        if (in_array($table_name, $wordpress_tables)) {
                            echo '<br><small style="color: red;">WordPress 系统表</small>';
                        }
                        ?>
                    </td>
                    <td><?php echo esc_html($table['Engine']); ?></td>
                    <td>
                        <?php if ($show_innodb_button) : ?>
                            <form method="post" action="">
                                <?php wp_nonce_field("convert_to_innodb_nonce_{$table_name}", "convert_to_innodb_nonce"); ?>
                                <input type="hidden" name="action" value="convert_to_innodb">
                                <input type="hidden" name="table_name" value="<?php echo esc_attr($table_name); ?>">
                                <input type="submit" name="convert_to_innodb_submit" class="button-secondary" value="转化为InnoDB">
                            </form>
                        <?php endif; ?>
                    </td>
                    <td><?php echo esc_html($table['Rows']); ?></td>
                    <td><?php echo esc_html(size_format($table['Data_length'], 2)); ?></td>
                    <td><?php echo esc_html(size_format($table['Index_length'], 2)); ?></td>
                    <td><?php echo esc_html(size_format($table['Data_length'] + $table['Index_length'], 2)); ?></td>
                </tr>

                <?php
                $totalTables++;
                $totalRows += $table['Rows'];
                $totalDataSize += $table['Data_length'];
                $totalIndexSize += $table['Index_length'];
                $totalSize += $table['Data_length'] + $table['Index_length'];
            }
            ?>
        </tbody>
    </table>

    <h2>数据库总计信息</h2>
    <p>
        <span>总共有: <?php echo esc_html($totalTables); ?>张表数</span>
        <span>总记录行数: <?php echo esc_html($totalRows); ?>  </span>
        <span>总数据大小: <?php echo esc_html(size_format($totalDataSize, 2)); ?>  </span>
        <span>总索引大小: <?php echo esc_html(size_format($totalIndexSize, 2)); ?>  </span>
        <span>数据库总大小: <?php echo esc_html(size_format($totalSize, 2)); ?></span>
    </p>
</div>
</div>


    <script>
        // Move success messages after corresponding forms
        document.addEventListener('DOMContentLoaded', function () {
            var successMessages = document.querySelectorAll('.optimize-plugin-notice');
            successMessages.forEach(function (message) {
                var formId = message.getAttribute('data-form-id');
                var form = document.getElementById(formId);
                if (form) {
                    form.parentNode.insertBefore(message, form.nextSibling);
                }
            });
        });
    </script>
    <?php
}


// 用于添加成功消息到 admin_notices 钩子的函数
function add_success_message($message, $formId) {
    add_action('admin_notices', function () use ($message, $formId) {
        echo '<div class="optimize-plugin-notice custom-notice" data-form-id="' . esc_attr($formId) . '">
                <p>' . esc_html($message) . '</p>
            </div>';
    });
}

// 执行删除操作
function perform_optimization($nonce, $callback, $success_message, $formId) {
    global $wpdb;

    // 检查用户权限和 nonce
    if (current_user_can('manage_options') && isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], $nonce)) {
        // 执行删除操作
        call_user_func($callback);

        // 显示成功消息
        add_success_message($success_message, $formId);
    }
}

// 执行删除不存在文章的元信息操作
function wpbfd_remove_unused_metadata() {
    global $wpdb;

    $wpdb->query("
        DELETE pm
        FROM {$wpdb->prefix}postmeta pm
        LEFT JOIN {$wpdb->prefix}posts p ON pm.post_id = p.ID
        WHERE p.ID IS NULL
    ");
}

// 获取不存在文章的元信息数量
function get_unused_metadata_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(pm.meta_id)
        FROM {$wpdb->prefix}postmeta pm
        LEFT JOIN {$wpdb->prefix}posts p ON pm.post_id = p.ID
        WHERE p.ID IS NULL
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfdremove_unused_metadata_nonce', 'wpbfd_remove_unused_metadata', '删除不存在文章的元信息操作完成！', 'wpbfd_remove_unused_metadata_submit');
});

// 执行删除不用的_edit_lock和_edit_last字段操作
function wpbfd_remove_unused_edit_fields() {
    global $wpdb;

    $wpdb->query("
        DELETE FROM {$wpdb->prefix}postmeta
        WHERE meta_key IN ('_edit_lock', '_edit_last')
    ");
}

// 获取不用的_edit_lock和_edit_last字段数量
function get_unused_edit_fields_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(meta_id)
        FROM {$wpdb->prefix}postmeta
        WHERE meta_key IN ('_edit_lock', '_edit_last')
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_unused_edit_fields_nonce', 'wpbfd_remove_unused_edit_fields', '删除孤立的关系数据完成！', 'wpbfd_remove_unused_edit_fields_submit');
});


// 执行删除旧的别名信息操作
function wpbfd_remove_old_aliases() {
    global $wpdb;

    $wpdb->query("
        DELETE FROM {$wpdb->prefix}postmeta
        WHERE meta_key = '_wp_old_slug'
    ");
}

// 获取不用的旧的别名信息数量
function get_unused_old_aliases_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(meta_id)
        FROM {$wpdb->prefix}postmeta
        WHERE meta_key = '_wp_old_slug'
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_old_aliases_nonce', 'wpbfd_remove_old_aliases', '删除旧的别名信息完成！', 'wpbfd_remove_old_aliases_submit');
});


// 执行删除不存在文章的评论元信息操作
function wpbfd_remove_unused_commentmeta() {
    global $wpdb;

    $wpdb->query("
        DELETE cm
        FROM {$wpdb->prefix}commentmeta cm
        WHERE comment_id NOT IN (SELECT comment_ID FROM {$wpdb->prefix}comments)
    ");
}

// 获取不存在文章的评论元信息数量
function get_unused_commentmeta_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(meta_id)
        FROM {$wpdb->prefix}commentmeta
        WHERE comment_id NOT IN (SELECT comment_ID FROM {$wpdb->prefix}comments)
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_unused_commentmeta_nonce', 'wpbfd_remove_unused_commentmeta', '删除不存在文章的评论元信息操作完成！', 'wpbfd_remove_unused_commentmeta_submit');
});

/*// 先查询再执行删除不存在文章的评论元信息操作
function remove_unused_commentmeta() {
    global $wpdb;

    $wpdb->query("
        DELETE cm
        FROM {$wpdb->prefix}commentmeta cm
        LEFT JOIN {$wpdb->prefix}comments c ON cm.comment_id = c.comment_ID
        WHERE c.comment_ID IS NULL
    ");
}
*/

// 删除_pingback和trackback记录操作
function wpbfd_remove_pingback_trackback() {
    global $wpdb;

    $wpdb->query("
        DELETE FROM {$wpdb->prefix}comments
        WHERE comment_type IN ('pingback', 'trackback')
    ");
}

// 获取数据库里之前的pingback和trackback引用记录数量
function get_removed_pingback_trackback_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(comment_ID)
        FROM {$wpdb->prefix}comments
        WHERE comment_type IN ('pingback', 'trackback')
    ");

    return absint($count);
}

// 在删除_pingback和trackback记录操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_pingback_trackback_nonce', 'wpbfd_remove_pingback_trackback', '删除pingback和trackback记录完成！', 'wpbfd_remove_pingback_trackback_submit');
});


// 删除与文章无关的标签操作
function wpbfd_remove_unused_tags() {
    global $wpdb;

    $wpdb->query("
        DELETE t, tt, tr
        FROM {$wpdb->prefix}terms t
        LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON t.term_id = tt.term_id
        LEFT JOIN {$wpdb->prefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
        WHERE tt.taxonomy = 'post_tag' AND tr.term_taxonomy_id IS NULL AND tt.count = 0
    ");
}

// 获取数据库里未引用的tag标签数量
function get_unused_tags_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(t.term_id)
        FROM {$wpdb->prefix}terms t
        LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON t.term_id = tt.term_id
        LEFT JOIN {$wpdb->prefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
        WHERE tt.taxonomy = 'post_tag' AND tr.term_taxonomy_id IS NULL AND tt.count = 0
    ");

    return absint($count);
}

// 在删除与文章无关的标签操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_unused_tags_nonce', 'wpbfd_remove_unused_tags', '删除与文章无关的标签完成！', 'wpbfd_remove_unused_tags_submit');
});


// 执行删除数据库里的文章修订操作
function wpbfd_remove_post_revisions() {
    global $wpdb;

    $wpdb->query("
        DELETE p
        FROM {$wpdb->prefix}posts p
        WHERE post_type = 'revision'
    ");
}

// 获取数据库里文章修订记录数量
function get_post_revisions_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(ID)
        FROM {$wpdb->prefix}posts
        WHERE post_type = 'revision'
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_post_revisions_nonce', 'wpbfd_remove_post_revisions', '删除文章修订操作完成！', 'wpbfd_remove_post_revisions_submit');
});


// 执行删除数据库里的自动草稿操作
function wpbfd_remove_auto_drafts() {
    global $wpdb;

    $wpdb->query("
        DELETE p
        FROM {$wpdb->prefix}posts p
        WHERE post_type = 'auto-draft'
    ");
}

// 获取数据库里自动草稿记录数量
function get_auto_drafts_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(ID)
        FROM {$wpdb->prefix}posts
        WHERE post_type = 'auto-draft'
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_auto_drafts_nonce', 'wpbfd_remove_auto_drafts', '删除自动草稿操作完成！', 'wpbfd_remove_auto_drafts_submit');
});


// 执行删除wp_session记录功能
function wpbfd_remove_wp_session_records() {
    global $wpdb;

    $wpdb->query("
        DELETE
        FROM {$wpdb->prefix}options
        WHERE option_name LIKE '_wp_session_%'
    ");
}

// 获取数据库里session记录数量
function get_wp_session_records_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(option_id)
        FROM {$wpdb->prefix}options
        WHERE option_name LIKE '_wp_session_%'
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_wp_session_records_nonce', 'wpbfd_remove_wp_session_records', '删除未使用的会话记录操作完成！', 'wpbfd_remove_wp_session_records_submit');
});


// 执行删除expired transients记录功能
function wpbfd_remove_expired_transients() {
    global $wpdb;

    $wpdb->query("
        DELETE
        FROM {$wpdb->prefix}options
        WHERE option_name LIKE '_transient_timeout%'
        AND option_value < UNIX_TIMESTAMP()
    ");
}

// 获取数据库里transients临时数据数量
function get_expired_transients_count() {
    global $wpdb;

    $count = $wpdb->get_var("
        SELECT COUNT(option_id)
        FROM {$wpdb->prefix}options
        WHERE option_name LIKE '_transient_timeout%'
        AND option_value < UNIX_TIMESTAMP()
    ");

    return absint($count);
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_expired_transients_nonce', 'wpbfd_remove_expired_transients', '删除瞬态数据操作完成！', 'wpbfd_remove_expired_transients_submit');
});


// 执行删除所有文章的链链操作
function wpbfd_remove_external_links() {
    global $wpdb;

    $posts = $wpdb->get_results("SELECT ID, post_content FROM {$wpdb->prefix}posts WHERE post_type = 'post'", ARRAY_A);

    foreach ($posts as $post) {
        $content = $post['post_content'];
        $updated_content = preg_replace('/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU', '<a href="" rel="nofollow">$3</a>', $content);

        if ($content !== $updated_content) {
            $wpdb->update(
                $wpdb->prefix . 'posts',
                array('post_content' => $updated_content),
                array('ID' => $post['ID']),
                array('%s'),
                array('%d')
            );
        }
    }
}
// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_external_links_nonce', 'wpbfd_remove_external_links', '删除所有文章的链接操作完成！', 'wpbfd_remove_external_links_submit');
});


// 执行删除其它冗余信息操作
function wpbfd_remove_other_redundant() {
    global $wpdb;

    $wpdb->query("
        DELETE FROM {$wpdb->prefix}postmeta
        WHERE meta_key = '_revision-control' OR meta_value = '{{unknown}}'
    ");
}

// 在删除操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_remove_other_redundant_nonce', 'wpbfd_remove_other_redundant', '删除其它冗余信息操作完成！', 'wpbfd_remove_other_redundant_submit');
});


// 使用OPTIMIZE TABLE优化全部记录功能
function optimize_all_tables() {
    global $wpdb;

    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    
    foreach ($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE {$table[0]}");
    }
}

// 在操作前添加消息
add_action('admin_init', function () {
    perform_optimization('wpbfd_optimize_all_tables_nonce', 'wpbfd_optimize_all_tables', '优化数据库表操作完成！', 'optimize_all_tables_submit');
});




// 新增功能：执行转化为InnoDB操作
function convert_to_innodb_operation($table_name) {
    global $wpdb;

    // 执行转化为 InnoDB 操作
    $result = $wpdb->query("ALTER TABLE {$wpdb->prefix}{$table_name} ENGINE=InnoDB");
    //$result = $wpdb->query("ALTER TABLE {$table_name} ENGINE=InnoDB");
    return $result;
}


?>
