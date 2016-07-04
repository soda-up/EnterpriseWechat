<?php

namespace EnterpriseWechat\Resource;

class User extends AbstractBase {

    private $user_list_url = [
        'url' => 'https://qyapi.weixin.qq.com/cgi-bin/user/list',
        'query' => [
            'access_token' => '',
            'department_id' => 0,
            'fech_child' => 0,
            'status' => 0
        ]
    ];
    private $simple_list_url = [
        'url' => 'https://qyapi.weixin.qq.com/cgi-bin/user/simplelist',
        'query' => [
            'access_token' => '',
            'department_id' => 0,
            'fech_child' => 0,
            'status' => 0
        ]
    ];
    private $user_get_url = [
        'url' => 'https://qyapi.weixin.qq.com/cgi-bin/user/get',
        'query' => [
            'access_token' => '',
            'userid' => ''
        ]
    ];
    private $userlist_key = ['userlist'];
    private $user_key = [];
    private $simplelist_key= ['userlist'];

    public function __construct($Config) {
        parent::__construct($Config);
    }

    protected function _init_url() {
        $this->user_list_url['query']['access_token'] = $this->AccessToken->access_token;
        $this->user_get_url['query']['access_token'] = $this->AccessToken->access_token;
        $this->simple_list_url['query']['access_token'] = $this->AccessToken->access_token;
    }

    public function getUsersByDepartment($dp_id = 0, $fetch_child = 0, $status = 0) {
        $query = $this->user_list_url['query'];
        $query['department_id'] = $dp_id;
        $query['fech_child'] = $fetch_child;
        $query['status'] = $status;
        return $this->get($this->user_list_url['url'], $query, $this->userlist_key);
    }
    public function getUsersSimpleByDepartment($dp_id = 0, $fetch_child = 0, $status = 0){
        $query = $this->simple_list_url['query'];
        $query['department_id'] = $dp_id;
        $query['fech_child'] = $fetch_child;
        $query['status'] = $status;
        return $this->get($this->user_list_url['url'], $query, $this->simplelist_key);
    }
    public function getUser($user_id = '') {
        if ($user_id) {
            $query = $this->user_list_url['query'];
            $query['userid'] = $user_id;
            return $this->get($this->user_get_url['url'], $query, $this->user_key);
        }
        return [];
    }

}
