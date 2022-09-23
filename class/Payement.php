<?php


class Payement
{
    var $key;
    var $content;
    var $parameters;

    function __construct() {
        $this->Payment();
    }

    function Payment() {
        $this->parameters = array();
    }

    function setContent($xml) {
        $this->content = $xml;
        $this->parserXml();
    }

    /**
     *设置参数值
     */
    function setParameter($parameter, $parameterValue) {
        $this->parameters[$parameter] = $parameterValue;
    }

    function parserXml() {
        libxml_disable_entity_loader(true);
        $xml = simplexml_load_string($this->content);
        $encode = $this->getXmlEncode($this->content);

        Logger::INFO("xml编码格式:" . $encode);
        if($xml && $xml->children()) {
            foreach ($xml->children() as $node){
                //有子节点
                if($node->children()) {
                    $k = $node->getName();
                    $nodeXml = $node->asXML();
                    $v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);

                } else {
                    $k = $node->getName();
                    $v = (string)$node;
                }

                if($encode!="" && $encode != "UTF-8") {
                    $k = iconv("UTF-8", $encode, $k);
                    $v = iconv("UTF-8", $encode, $v);
                }

                $this->setParameter($k, $v);
                Logger::INFO("key=" . $k . ", value=" . $v);
            }
        }
    }

    function createMD5Sign() {
        $signPars = "";
        ksort($this->parameters);
        foreach($this->parameters as $k => $v) {
            if("" != $v && "sign" != $k) {
                $signPars .= $k . "=" . $v . "&";
            }
        }

        $signPars = substr($signPars, 0, strlen($signPars) - 1);
        Logger::INFO('Payement.createMD5Sign.signPars=' . $signPars);
        $sign = strtoupper(md5($signPars));
        Logger::INFO('Payement.createMD5Sign.sign=' . $sign);
        $this->setParameter("sign", $sign);

        //debug信息
        $this->_setDebugInfo($signPars . " => sign:" . $sign);
    }

    //获取xml编码
    function getXmlEncode($xml) {
        $ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
        if($ret) {
            return strtoupper ( $arr[1] );
        } else {
            return "";
        }
    }

    function setKey($key) {
        $this->key = $key;
    }

    function getKey() {
        return $this->key;
    }
}