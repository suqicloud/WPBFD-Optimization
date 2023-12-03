# WPBFD Optimization
Wordpress cache optimization

1、插件可能不兼容你的网站(和主题或者其他插件冲突)，如果你在主题functions.php里面加过相关功能代码，先去删掉
2、先禁用其他优化插件(比如Autoptimize、WP Super Cache等)，测试正常之后，再去启用这些插件；如果你网站开启了CDN，去刷新下CDN再看
3、优化数据库之前，一定要先备份数据库！先备份数据库！先备份数据库！
4、如果插件一启用就造成网站不正常，就去服务器里面删掉这个插件文件夹，名称：WPBFDoptimizations

每段都有注释，可以看下。

基础功能优化代码结构：

//隐藏仪表盘的站点健康状态
注册 register_setting('');
设置 add_settings_field('');

用function 调用函数去判断执行

注册钩子add_action
