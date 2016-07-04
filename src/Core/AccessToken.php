<?php

namespace EnterpriseWechat\Core;

class AccessToken {

    private $get_token_url = [
        'url' => 'https://qyapi.weixin.qq.com/cgi-bin/gettoken',
        'query' => [
            'corpid' => null,
            'corpsecret' => null
        ]
    ];
    public $access_token = '';
    private $Config = null;

    public function __construct($Config) {
        $this->Config = $Config;
        $this->get_token_url['query']['corpid'] = $this->Config->corp_id;
        $this->get_token_url['query']['corpsecret'] = $this->Config->corp_secret;
        $this->access_token = $this->_getAccessToken();
    }
    public function __get($name) {
        if ($name =='access_token') {
            return $this->access_token;
        }
        return '';
    }
    protected function _getAccessToken() {
        $filename = dirname(__DIR__).'/Cache/access_token.json';
        if (file_exists($filename)) {
            $access_token = json_decode(file_get_contents($filename), true);
            if ($access_token['access_token']) {
                if (time() < $access_token['expires_in']) {
                    return $access_token['access_token'];
                }
            }
        }
        return $this->_createAccessToken();
    }

    private function _createAccessToken() {
        $HttpClient = new Http();
        $rel = $HttpClient->get($this->get_token_url['url'], $this->get_token_url['query'])->getBody()->getContents();
        $access_token = json_decode($rel, true);
        if (isset($access_token['access_token'])) {
            $access_token['expires_in'] = time() + $access_token['expires_in'];
            $filename = dirname(__DIR__).'/Cache/access_token.json';
            file_put_contents($filename, json_encode($access_token));
            return $access_token['access_token'];
        }
        return false;
    }

}
