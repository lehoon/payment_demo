<?php
class Config{
    private $cfg = array(
        'url'=>'https://qra.95516.com/pay/gateway',/*支付请求接口地址，无需更改 */
        'mchId'=>'QRA29045311KKR1',/* 测试商户号，商户正式上线时需更改为自己的 */
        'key'=>'979da4cfccbae7923641daa5dd7047c2',  /* 测试密钥，商户需更改为自己的*/
        'version'=>'2.0',
        'sign_type'=>'MD5'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>