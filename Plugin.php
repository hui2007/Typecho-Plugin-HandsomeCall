<?php
/**
 * 一款信息提示插件
 * @package ThemeNotice
 * @author Hui2007
 * @version 1.0
 * @link https://blog.hui2007.ml
 */
 


class ThemeNotice implements Typecho_Plugin_Interface
{
	public static function activate()
	{
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
        return _t('插件已启用，请访问前台查看效果');
    }

	/* 禁用插件方法 */
	public static function deactivate()
	{
        return _t('插件已禁用，感谢使用');
	}
         /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {

        // 是否引入jQuery
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio(
            'jquery',
            array(
                '0' => _t('否'),
                '1' => _t('是'),
            ),
            '1',
            _t('是否引入了jQuery'),
            _t('部分主题/插件可能已引入，多次引入可能会减慢网站加载速度')
        );
        $form->addInput($jquery);
        
        // 是否引入Bootstrap
        $bootstrap = new Typecho_Widget_Helper_Form_Element_Radio(
            'bootstrap',
            array(
                '0' => _t('否'),
                '1' => _t('是'),
            ),
            '1',
            _t('是否引入了Bootstrap'),
            _t('部分主题/插件可能已引入，多次引入可能会减慢网站加载速度')
        );
        $form->addInput($bootstrap);

        // 是否引入Popper.js
        $popper = new Typecho_Widget_Helper_Form_Element_Radio(
            'popper',
            array(
                '0' => _t('否'),
                '1' => _t('是'),
            ),
            '1',
            _t('是否引入了Popper.js'),
            _t('部分主题/插件可能已引入，多次引入可能会减慢网站加载速度')
        );
        $form->addInput($popper);
    }
    /**
     * 页脚输出相关代码
     *
     * @access public
     * @param unknown render
     * @return unknown
     */
    public static function footer()
    {
        $referer = $_SERVER["HTTP_REFERER"];
        $refererhost = parse_url($referer);
        $host = strtolower($refererhost['host']);
        $ben=$_SERVER['HTTP_HOST'];
	    $options = Helper::options();
		$jquery = $options->plugin('ThemeNotice')->jquery;
		$bootstrap = $options->plugin('ThemeNotice')->bootstrap;
        if ($jquery == 0){
            echo '<script src="https://cdn.staticfile.org/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>';
        }
        if ($bootstrap == 0){
            echo '<script src="https://cdn.staticfile.org/twitter-bootstrap/4.2.1/js/bootstrap.min.js"></script>
            <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.2.1/css/bootstrap.css">';
        }
        if ($popper == 0){
            echo '<script src="https://cdn.staticfile.org/popper.js/1.14.7/popper.min.js"></script>';
        }
        $hello = "欢迎来自<strong>".$host."</strong>的朋友！你好哇！";
        if($referer == ""||$referer == null){
            if(!Typecho_Cookie::get('firstView')){
                Typecho_Cookie::set('firstView', '1', 0, Helper::options()->siteUrl);
                $hello = "欢迎来到小站里喝茶~ 我倍感荣幸啊，嘿嘿<br / > ";
            }else{
                $hello = "欢迎来到小站里喝茶！<br / > ";
            }
        }elseif(strstr($ben,$host)){ 
            $hello ="host"; 
        }elseif (preg_match('/baiducontent.*/i', $host)){
            $hello = '欢迎通过<strong>百度快照</strong>访问的朋友！<br / > ';
        }elseif(preg_match('/baidu.*/i', $host)){
            $hello = '欢迎来自<strong>百度</strong>的朋友！<br / > ';
        }elseif(preg_match('/so.*/i', $host)){
            $hello = '欢迎来自<strong>好搜</strong>的朋友！<br / > ';
        }elseif(!preg_match('/www\.google\.com\/reader/i', $referer) && preg_match('/google\./i', $referer)) {
            $hello = '欢迎来自<strong>Google</strong>的朋友！<br / > ';
        }elseif(preg_match('/search\.yahoo.*/i', $referer) || preg_match('/yahoo.cn/i', $referer)){
            $hello = '欢迎来自<strong>Yahoo</strong>的朋友！<br / > '; 
        }elseif(preg_match('/cn\.bing\.com\.*/i', $referer) || preg_match('/yahoo.cn/i', $referer)){
            $hello = '欢迎来自<strong>Bing</strong>的朋友！<br / > ';
        }elseif(preg_match('/google\.com\/reader/i', $referer)){
            $hello = "感谢通过<strong>Google</strong>订阅我的朋友！欢迎！<br / > ";
        } elseif (preg_match('/xianguo\.com\/reader/i', $referer)) {
            $hello = "感谢通过<strong>鲜果</strong>订阅我的朋友！欢迎！<br / > ";
        } elseif (preg_match('/zhuaxia\.com/i', $referer)) {
            $hello = "感谢通过<strong>抓虾</strong>订阅我的朋友！欢迎！<br / > ";
        } elseif (preg_match('/inezha\.com/i', $referer)) {
            $hello = "感谢通过<strong>哪吒</strong>订阅我的朋友！欢迎！<br / > ";
        } elseif (preg_match('/reader\.youdao/i', $referer)) {
            $hello = "感谢通过<strong>有道</strong>订阅我的朋友！欢迎！<br / > ";
        }
        $jsUrl = Helper::options()->pluginUrl . '/ThemeNotice/dist/toast.min.js';
        $cssUrl = Helper::options()->pluginUrl . '/ThemeNotice/dist/toast.min.css';
        if( $hello != "host"){//排除本地访问
	        echo "
<script src='{$jsUrl}'></script>
<link rel=\"stylesheet\" href=\"{$cssUrl}\">
<script type=\"text/javascript\">
    function notice(){
		$.toast({
	      title: '页面加载完毕',
          subtitle: '',
          content: '{$hello}',
          type: 'success',
          pause_on_hover: 'true',
          delay: 5000
    	});
	};
	$(function(){
		notice();
	});
</script>
";
        }

    }

}

?>
