<?php

namespace EnterpriseWechat\Resource;

use EnterpriseWechat\Core\AccessToken;
use EnterpriseWechat\Core\Http;

abstract Class AbstractBase {

    abstract protected function _init_url();

    public $property = [];
    protected $AccessToken = null;
    protected $Config = null;
    protected $HttpClient = null;

    public function __construct($Config) {
        $this->Config = $Config;
        $this->AccessToken = new AccessToken($Config);
        $this->HttpClient = new Http();
        $this->_init_url();
    }

    /**
     * 获取一个属性值
     * @param type $name
     */
    public function __get($name) {
        return $this->$name;
    }

    /**
     * 设置一个属性值
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    protected function get($url, array $options = [], $get_key = []) {
        $rel = json_decode($this->HttpClient->get($url, $options)->getBody()->getContents(), true);
        $result=[];
        if($get_key){
            foreach($get_key as $key){
                $result[$key]=$rel[$key];
            }
        }else{
            $result=$rel;
        }
        return $result;
    }

}
