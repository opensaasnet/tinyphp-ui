<?php
/**
 * @Copyright (C), 2013-, King.
 * @Name Builder.php
 * @Author King
 * @Version Beta 1.0
 * @Date 2020年6月1日下午5:37:07
 * @Description
 * @Class List 1.
 * @Function List 1.
 * @History King 2020年6月1日下午5:37:07 第一次建立该文件
 *                 King 2020年6月1日下午5:37:07 修改
 *
 */
namespace Tiny\MVC\View\UI;

use Tiny\MVC\ApplicationBase;
use Tiny\Config\Configuration;
use Tiny\MVC\Plugin\Iplugin;

/**
 * JS前端库的绝对路径
 *
 * @var string
 */
define('TINY_UI_FRONTEND_LIBRARY_PATH', dirname(dirname(__DIR__)) . '/dist/tinyphp-ui/');

/**
 * 打包器插件
 *
 * @package Tiny.MVC.Plugin
 * @since 2020年6月1日下午5:37:30
 * @final 2020年6月1日下午5:37:30
 */
class UIInstaller implements Iplugin
{

    /**
     * UI库安装路径
     *
     * @var string
     */
    const UI_FRONTEND_LIBRARY_PATH = TINY_UI_FRONTEND_LIBRARY_PATH;

    /**
     * 当前应用实例
     *
     * @var \Tiny\MVC\ApplicationBase
     */
    protected $_app;

    /**
     * app属性
     *
     * @var Configuration
     */
    protected $_properties;

    /**
     * 初始化
     *
     * @param $app ApplicationBase
     *            当前应用实例
     * @return void
     */
    public function __construct(ApplicationBase $app)
    {
        $this->_app = $app;
        $this->_properties = $app->properties;
    }

    /**
     * 本次请求初始化时发生的事件
     *
     * @return void
     */
    public function onBeginRequest()
    {
        
    }

    /**
     * 本次请求初始化结束时发生的事件
     *
     * @return void
     */
    public function onEndRequest()
    {
        
    }

    /**
     * 执行路由前发生的事件
     *
     * @return void
     */
    public function onRouterStartup()
    {
        $config = (array) $this->_app->properties['view.ui'];
       
        if (! $config['enabled'] || ! $config['installer']) 
        {
            return;
        }
        $installConfig = (array)$config['installer'];
        $paramName = (string)$installConfig['param_name'] ?: 'ui-install';
        if (! $this->_app->request->param[$paramName]) 
        {
            return;
        }
        $this->_copyUIFrontendLibrary($installConfig);
        $this->_app->response->end();
    }

    /**
     * 复制UI的前端库
     * 
     * @param array $config
     */
    protected function _copyUIFrontendLibrary($config)
    {
        $installPath = dirname(get_included_files()[0]) . '/' . $config['frontend_path'];
        $sourcePath = self::UI_FRONTEND_LIBRARY_PATH;
        
        if (! is_dir(self::UI_FRONTEND_LIBRARY_PATH))
        {
            return;
        }
        
        if (preg_match("/^(\*|\/|\/(usr|home|root|lib|lib64|etc|var)\/?|)$/i", $installerPath))
        {
            return;
        }
        if (!function_exists('system'))
        {
            throw new UIException(sprintf('Function "system" is not exists for copy UI library from %s to %s', self::UI_FRONTEND_LIBRARY_PATH, $installerPath));
        }
        
        if (file_exists($installerPath))
        {
            if (filemtime($installerPath) >= filemtime(self::UI_FRONTEND_LIBRARY_PATH))
            {
                return;
            }
            printf("Frontend Library of saasjit/tinyphp-ui is updated and rm -rf [%s]\n", $installerPath);
            system(sprintf("rm -rf %s", $installerPath));
        }
        printf("Frontend Library of saasjit/tinyphp-ui [%s] is copyded from [%s]\n", $installerPath, self::UI_FRONTEND_LIBRARY_PATH);
        system(sprintf("cp -ar %s %s", self::UI_FRONTEND_LIBRARY_PATH, $installerPath));
    }
    
    /**
     * 复制文件夹去安装路径
     * 
     * @param string $sourcePath 源文件路径
     * @param string $installPath 安装路径
     * @throws UIException
     * @return void|boolean
     */
    protected function _copyto($sourcePath, $installPath)
    {
        if (!function_exists('system'))
        {
            throw new UIException(sprintf('Function "system" is not exists for copy UI library from %s to %s', $sourcePath, $installPath));
        }
        
        if (preg_match("/^(\*|\/|\/(usr|home|root|lib|lib64|etc|var)\/?|)$/i", $installPath))
        {
            return;
        }
        
        if (file_exists($installPath))
        {
            // 更新最新文件
            if (filemtime($installPath) >= filemtime(self::UI_FRONTEND_LIBRARY_PATH))
            {
                return;
            }
            printf("saasjit/tinyphp-ui is updated and rm -rf [%s]\n", $installPath);
            system(sprintf("rm -rf %s", $installPath));
        }
        
        $ret = system(sprintf("cp -ar %s %s", self::UI_FRONTEND_LIBRARY_PATH, $installPath));
        if ($ret === FALSE)
        {
            return;
        }
        return TRUE;
    }
    
    /**
     * 执行路由后发生的事件
     *
     * @return void
     */
    public function onRouterShutdown()
    {
        
    }

    /**
     * 执行分发前发生的动作
     *
     * @return void
     */
    public function onPreDispatch()
    {
        
    }

    /**
     * 执行分发后发生的动作
     *
     * @return void
     */
    public function onPostDispatch()
    {
        
    }
}
?>