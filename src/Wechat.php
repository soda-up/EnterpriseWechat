<?php
/**
 * 用于与微信交互 被动响应微信
 */
namespace EnterpriseWechat;

use EnterpriseWechat\Util\Prpcrypt;

class Wechat extends Base {
    const ENVENT_MSG=1;
    const SIMPLE_MSG=2;

    public function run($get){
        //验证url
        //返回解析后的普通消息
        //
    }
    public function verifyURL($msg_signature = '', $timestamp = '', $nonce = '', $echostr = '', &$reply_echostr) {
        if (strlen($this->_config['encoding_aes_key']) != 43) {
            return self::$IllegalAesKey;
        }

        $pc = new Prpcrypt($this->_config['encoding_aes_key']);
        //verify msg_signature
        $sha1 = new SHA1();
        $array = $sha1->getSHA1($this->_config['token'], $timestamp, $nonce, $echostr);
        $ret = $array[0];

        if ($ret != 0) {
            return $ret;
        }

        $signature = $array[1];
        if ($signature != $msg_signature) {
            return self::$ValidateSignatureError;
        }

        $result = $pc->decrypt($echostr, $this->_config['corp_id']);
        if ($result[0] != 0) {
            return $result[0];
        }
        $reply_echostr = $result[1];

        return self::$OK;
    }

    public function DecryptMsg($sMsgSignature, $sTimeStamp = null, $sNonce, $sPostData, &$sMsg) {
        if (strlen($this->m_sEncodingAesKey) != 43) {
            return ErrorCode::$IllegalAesKey;
        }

        $pc = new Prpcrypt($this->m_sEncodingAesKey);

        //提取密文
        $xmlparse = new XMLParse;
        $array = $xmlparse->extract($sPostData);
        $ret = $array[0];

        if ($ret != 0) {
            return $ret;
        }

        if ($sTimeStamp == null) {
            $sTimeStamp = time();
        }

        $encrypt = $array[1];
        $touser_name = $array[2];

        //验证安全签名
        $sha1 = new SHA1;
        $array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
        $ret = $array[0];

        if ($ret != 0) {
            return $ret;
        }

        $signature = $array[1];
        if ($signature != $sMsgSignature) {
            return ErrorCode::$ValidateSignatureError;
        }

        $result = $pc->decrypt($encrypt, $this->m_sCorpid);
        if ($result[0] != 0) {
            return $result[0];
        }
        $sMsg = $result[1];

        return ErrorCode::$OK;
    }
    public function EncryptMsg($sReplyMsg, $sTimeStamp, $sNonce, &$sEncryptMsg) {
        $pc = new Prpcrypt($this->m_sEncodingAesKey);

        //加密
        $array = $pc->encrypt($sReplyMsg, $this->m_sCorpid);
        $ret = $array[0];
        if ($ret != 0) {
            return $ret;
        }

        if ($sTimeStamp == null) {
            $sTimeStamp = time();
        }
        $encrypt = $array[1];

        //生成安全签名
        $sha1 = new SHA1;
        $array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
        $ret = $array[0];
        if ($ret != 0) {
            return $ret;
        }
        $signature = $array[1];

        //生成发送的xml
        $xmlparse = new XMLParse;
        $sEncryptMsg = $xmlparse->generate($encrypt, $signature, $sTimeStamp, $sNonce);
        return ErrorCode::$OK;
    }

}
