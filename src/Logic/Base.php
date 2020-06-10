<?php
declare (strict_types=1);

namespace Smalls\VideoTools\Logic;

use Smalls\VideoTools\Exception\ErrorVideoException;
use Smalls\VideoTools\Traits\HttpRequest;
use Smalls\VideoTools\Utils\CommonUtil;
use Smalls\VideoTools\Utils\Config;

/**
 * Created By 1
 * Author：smalls
 * Email：smalls0098@gmail.com
 * Date：2020/6/10 - 11:49
 **/
class Base
{
    use HttpRequest;

    /**
     * 请求的url地址
     * @var string
     */
    protected $url;
    /**
     * 校验的url列表
     * @var array
     */
    protected $urlList;
    /**
     * 是否开启检查列表
     * @var bool
     */
    private $isCheckUrl = true;
    /**
     * 公共配置文件
     * @var Config
     */
    private $config;

    protected $isProxy = false;

    protected $proxyIpPort = "";

    public function __construct($url, $urlList, $config)
    {
        $this->url = $url;
        $this->urlList = $urlList;
        $this->config = $config;
        $this->isCheckUrl = $this->config->get('is_check_url', true);
        $className = str_replace(__NAMESPACE__, "", get_class($this));
        $className = substr($className, 1, strlen($className) - 6);
        $className = strtolower($className);
        $proxyWhitelist = $this->config->get('proxy_whitelist', []);
        $this->isProxy = in_array($className, $proxyWhitelist);
        $this->proxyIpPort = $this->config->get('proxy', '');
    }

    public function checkUrlHasTrue()
    {
        if ($this->isCheckUrl) {
            if (empty($this->url)) {
                throw new ErrorVideoException("URL为空");
            }
            if (empty($this->urlList)) {
                throw new ErrorVideoException("校验URL列表为空");
            }
            if (!CommonUtil::checkArrContainStr($this->urlList, $this->url)) {
                throw new ErrorVideoException("URL校验失败");
            }
        }
    }

}