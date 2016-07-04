<?php

namespace EnterpriseWechat\Resource;

class Department extends AbstractBase {

    private $list_url = [
        'url' => "https://qyapi.weixin.qq.com/cgi-bin/department/list",
        'query' => [
            'access_token' => '',
            'id' => ''
        ]
    ];
    
    private $departments_key = ['department'];

    public function __construct($Config) {
        parent::__construct($Config);
    }

    protected function _init_url() {
        $this->list_url['query']['access_token'] = $this->AccessToken->access_token;
    }

    /**
     * 获取实例化部门的子部门
     * @param type $department_id
     * @return type
     */
    public function getChilds($department_id = '') {
        if ($department_id <= 0) {
            return [];
        }
        $query = $this->list_url['query'];
        $query['id'] = $department_id;
        return $this->get($this->list_url['url'], $query, $this->departments_key);
    }

    /**
     * 获取全部的部门
     */
    public function getAllDepartments() {
        return $this->get($this->list_url['url'], $this->list_url['query'], $this->departments_key);
    }
}