<?php

namespace EnterpriseWechat;

use EnterpriseWechat\Auth\AccessToken;
use EnterpriseWechat\Util\Http;

class Base {
    public static $OK = 0;
    public static $ValidateSignatureError = -40001; //签名验证错误
    public static $ParseXmlError = -40002; //xml解析失败
    public static $ComputeSignatureError = -40003; //sha加密生成签名失败
    public static $IllegalAesKey = -40004; //encodingAesKey 非法
    public static $ValidateCorpidError = -40005; //corpid 校验错误
    public static $EncryptAESError = -40006; //aes 加密失败
    public static $DecryptAESError = -40007; //aes 解密失败
    public static $IllegalBuffer = -40008; //解密后得到的buffer非法
    public static $EncodeBase64Error = -40009; // base64加密失败
    public static $DecodeBase64Error = -40010; //base64解密失败
    public static $GenReturnXmlError = -40011; //生成xml失败
    

    protected $_access_token_cache_file = '';
    protected $_access_token = '';
    protected $_config = [];
    protected $_http_client = null;

    public function __construct($config) {
        $this->_initConfig($config);
        $this->_http_client = new Http();
        $this->_access_token = $this->_getAccessToken();
    }

    protected function _initConfig($config) {
        if (!$this->_config) {
            $this->_config = $config;
        }
    }

    protected function _getAccessToken() {
        if (file_exists($this->_access_token_cache_file)) {
            $access_token = json_decode(file_get_contents($this->_access_token_cache_file), true);
            if ($access_token['access_token']) {
                if (time() < $access_token['expires_in']) {
                    return $access_token['access_token'];
                }
            }
        }
        return $this->_createAccessToken();
    }

    private function _createAccessToken() {
        $url = sprintf(self::ACCESS_TOKEN_URL, $this->_config['corp_id'], $this->_config['corp_secret']);
        $access_token = json_decode($this->_http_client->get($url), true);
        $access_token['expires_in'] = time() + $access_token['expires_in'];
        file_put_contents($this->_access_token_cache_file, json_encode($access_token));
        return $access_token['access_token'];
    }
}
