<?php

namespace EnterpriseWechat\Resource;

class Tag extends AbstractBase {

    private $list_url = [
        'url' => "https://qyapi.weixin.qq.com/cgi-bin/tag/list",
        'query' => [
            'access_token' => ''
        ]
    ];
    private $get_url = [
        'url' => "https://qyapi.weixin.qq.com/cgi-bin/tag/get",
        'query' => [
            'access_token' => '',
            'tagid'=>''
        ]
    ];
    private $tags_key = ['taglist'];
    private $items_list=['userlist','partylist'];
    public function __construct($Config) {
        parent::__construct($Config);
    }

    protected function _init_url() {
        $this->list_url['query']['access_token'] = $this->AccessToken->access_token;
        $this->get_url['query']['access_token'] = $this->AccessToken->access_token;
    }
    /**
     * 获取实例化部门的子部门
     * @param type $department_id
     * @return type
     */
    public function getTags() {
        return $this->get($this->list_url['url'],$this->list_url['query'], $this->tags_key);
    }

    /**
     * 获取全部的部门
     */
    public function getItemsForTagId($id) {
        $query = $this->get_url['query'];
        $query['tagid'] = $id;
        return $this->get($this->list_url['url'], $query, $this->items_key);
    }
}

