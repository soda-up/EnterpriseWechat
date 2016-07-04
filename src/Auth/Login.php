<?php
namespace EnterpriseWechat\Auth;
use EnterpriseWechat\Base;
class Login extends Base{
    const GET_CODE_URL="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=%s&scope=%s&state=%s#wechat_redirect";
    const GET_USER_INFO_URL="https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=%s&code=%s";
    public function getAuthUrl($redirect_uri='',$response_type='code',$scope='snsapi_base',$state='default'){
        return sprintf(self::GET_CODE_URL,$this->_config['corp_id'],$redirect_uri,$response_type,$scope,$state);
    }
    public function getUserInfo($code=''){
        $url = sprintf(self::GET_USER_INFO_URL,$this->_access_token,$code);
        return json_decode($this->_http_client->get($url),true);
    }
}