<?php
namespace Org\Util;
use Think;
Vendor('Getui.IGt','','.Push.php');

class Getui{
    private $host="";
    private $appkey;
    private $appid;
    private $mastersecret;
    
    function __construct(){
        //$this->host = C("APP_URL");
        
        $this->appkey = "IJwRAXu6v47PBWdXLipS35";
        $this->appid = "Dd7JnqTHjU9hQeSG0sPPyA";
        $this->mastersecret = "woJMn0Y3YC6Q8HkAzj3jr5";
    }
    
    public function pushNoti2All($title,$content){
        $igt = new \IGeTui($this->host, $this->appkey, $this->mastersecret);
        
        $template = $this->getMyNotiTemp($this->appid, $this->appkey, $title, $content);
        
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        
        $appIdList=array($this->appid);
        $phoneTypeList=array('ANDROID');
        //$provinceList=array('浙江');
        //$tagList=array('haha');
        
        $message->set_appIdList($appIdList);
        $message->set_phoneTypeList($phoneTypeList);
        
        $res = $igt->pushMessageToApp($message);
        return $res;
    }
    
    public function pushMes2Users($content,$usersCid){
        $igt = new \IGeTui($this->host, $this->appkey, $this->mastersecret);
        $batch = $igt->getBatch();
        
        $template = $this->getMyMesTemp($this->appid, $this->appkey, $content);
        
        foreach($usersCid as $v){
            $message = new \IGtSingleMessage();
            $message->set_isOffline(false);
            $message->set_data($template);
            $target = new \IGtTarget();
            $target->set_appId($this->appid);
            $target->set_clientId($v);
            $batch->add($message, $target);
        }
        try {
            $rep = $batch->submit();
            return $rep;
        }catch(\RequestException $e){
            $rep=$batch->retry();
            return $rep;
        }
    }
    
    public function pushMes2Single($content,$userCid){
        $igt = new \IGeTui($this->host, $this->appkey, $this->mastersecret);
    
        $template = $this->getMyMesTemp($this->appid, $this->appkey, $content);
    
        $message = new \IGtSingleMessage();
        $message->set_isOffline(false);
        $message->set_data($template);
        $target = new \IGtTarget();
        $target->set_appId($this->appid);
        $target->set_clientId($userCid);
    
        try {
            return $igt->pushMessageToSingle($message, $target);
        }catch(\RequestException $e){
            $requstId = $e->getRequestId();
            //失败时重发
            return $igt->pushMessageToSingle($message, $target,$requstId);
        }
    }
    
    
    
    private function getMyNotiTemp($appid,$appkey,$title,$content){
        $template =  new \IGtNotificationTemplate();
        $template->set_appId($appid);                      //应用appid
        $template->set_appkey($appkey);                    //应用appkey
        //$template->set_transmissionType(1);               //透传消息类型,1为立即驱动，0为等来客户端自启动
        //$template->set_transmissionContent("测试离线");   //透传内容
        $template->set_title($title);                     //通知栏标题
        $template->set_text($content);        //通知栏内容
        //$template->set_logo("logo.png");                  //通知栏logo
        //$template->set_logoURL("http://wwww.igetui.com/logo.png"); //通知栏logo链接
        $template->set_isRing(true);                      //是否响铃
        $template->set_isVibrate(true);                   //是否震动
        $template->set_isClearable(true);                 //通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        return $template;
    }
    
    private function getMyMesTemp($appid,$appkey,$content){
        $template =  new \IGtTransmissionTemplate();
        //应用appid
        $template->set_appId($appid);
        //应用appkey
        $template->set_appkey($appkey);
        //透传消息类型
        $template->set_transmissionType(2);
        //透传内容
        $template->set_transmissionContent($content);
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //这是老方法，新方法参见iOS模板说明(PHP)*/
        //$template->set_pushInfo("actionLocKey","badge","message",
        //"sound","payload","locKey","locArgs","launchImage");
        return $template;
    }
    
}