<?php
namespace Api\Controller;
use Think\Controller;
use Group\Event\GroupEvent;
use Common\Common\Response;
use Common\Model\ActiveModel;
use User\Model\UserModel;
use Org\Util\Getui;
use Common\Model\SilenceModel;
use Common\Common\Timestamp;
use Common\Event\SpeechEvent;
class GroupController extends Controller{
    public function newGroup(){
        $userId = I("post.user");
        $groupName = I("post.name");
        
        $ge = new GroupEvent();
        $resl = $ge->newGroup($userId, $groupName);
        
        $response = new Response();
        if($resl === false){
            $response = $response->setStatus(395)->setDetail("创建小组时发生未知错误")->build();
        }else{
            $response = $response->setStatus(200)->setDetail("成功")->setData((int)$resl)->build();
        }
        $this->ajaxReturn($response);
    }
    
    public function joinGroup(){
        $userId = I("post.user");
        $groupId = I("post.group");
        
        $ge = new GroupEvent();
        $isExist = $ge->isGroupExist($groupId);
        if($isExist == 2){
            //存在
            $resl = $ge->joinGroup($userId, $groupId);
            
            $response = new Response();
            if($resl === false){
                $response = $response->setStatus(395)->setDetail("加入小组时发生未知错误")->build();
            }else{
                $response = $response->setStatus(200)->setDetail("成功")->build();
            }
            $this->ajaxReturn($response);
        }else if($isExist == 1){
            //不存在
            $response = new Response();
            $response = $response->setStatus(394)->setDetail("小组".$groupId."不存在")->build();
            $this->ajaxReturn($response);
        }else if($isExist == 0){
            //网络错误
            $response = new Response();
            $response = $response->setStatus(395)->setDetail("加入小组时发生未知错误")->build();
            $this->ajaxReturn($response);
        }
    }
    
    public function getGroupByUser(){
        $userId = I("get.user");
        
        $ge = new GroupEvent();
        $resl = $ge->getGroupByUser($userId);
        
        $response = new Response();
        if($resl === false){
            $response = $response->setStatus(395)->setDetail("发生未知错误")->build();
        }else if($resl == null){
            $response = $response->setStatus(203)->build();
        }else {
            $response = $response->setStatus(200)->setData($resl)->build();
        }
        $this->ajaxReturn($response);
    }
    
    public function getGroupView(){
        $groupId = I("get.group");
        
        $ge = new GroupEvent();
        $resl = $ge->getGroupView($groupId);
        
        $response = new Response();
        if($resl === false){
            $response = $response->setStatus(395)->setDetail("发生未知错误")->build();
        }else{
            $response = $response->setStatus(200)->setData($resl)->build();
        }
        $this->ajaxReturn($response);
    }
    
    public function getParaOfGroup(){
        $paraNumAfter = I("get.paraNumAfter");
        $groupId = I("get.group");
        $limit = I("get.limit");
        
        $ge = new GroupEvent();
        $resl = $ge->getParaOfGroup($groupId, $paraNumAfter, $limit);
        
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(400)->setDetail("无结果")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }
    }
    
    public function hasUserJoinedAnyGroup(){
        $user = I("get.user");
        
        $ge = new GroupEvent();
        $resl = $ge->hasUserJoinedAnyGroup($user);
        
        $response = new Response();
        if($resl == 0){
            //错误
            $response = $response->setStatus(395)->setDetail("发生未知错误")->build();
        }else if($resl == 1){
            //无
            $response = $response->setStatus(200)->setDetail("成功")->setData("no")->build();
        }else if($resl == 2){
            //有
            $response = $response->setStatus(200)->setDetail("成功")->setData("yes")->build();
        }
        $this->ajaxReturn($response);
    }
    
    public function writeParaOfAGroup(){
        $user = I("post.user");
        $group = I("post.group");
        $content = I("post.content");
        $time = I("post.time");
        
        $ge = new GroupEvent();
        $resl = $ge->writeParaOfAGroup($user, $group, $content, $time);
        
        $response = new Response();
        if($resl === false){
            $response = $response->setStatus(395)->setDetail("发生未知错误")->build();
        }else{
            $response = $response->setStatus(200)->setDetail("成功")->setData($resl)->build();
        }
        $this->ajaxReturn($response);
    }
    
    public function setActive(){
        $user = I("get.user");
        $group = I("get.group");
    
        $am = new ActiveModel();
        $am->setGroupActive($user, $group);
    }
    
    public function unsetActive(){
       $user = I("get.user");
        $group = I("get.group");
    
        $am = new ActiveModel();
        $am->unsetGroupActive($user, $group);
    }
    
    public function testSendMessage2ActiveUser(){
        $group = I("get.group");
        $status = I("get.status");
        $ge = new GroupEvent();
        $res = $ge->sendMes2ActiveUserInGroup($group, $status);
        dump($res);
    }
    
    public function askPushStatus(){
        $group = I("get.group");
        $user = I("get.user");
        $um = new UserModel();
        $cid = $um->getUserCidByUid($user);
        $ge = new GroupEvent();
        $status = $ge->getStatus($group);
        $gt = new Getui();
        $res = $gt->pushMes2Single($status, $cid);
        dump($res);
    }
    
    public function setSilent(){
        $group = I("get.group");
    
        $sm = new SilenceModel();
        $sm->setGroupSilent($group);
    
        $ge = new GroupEvent();
        $res = $ge->sendMes2ActiveUserInGroup($group, 551);
        dump($res);
    }
    public function unsetSilent(){
        $group = I("get.group");
    
        $sm = new SilenceModel();
        $sm->unsetGroupSilent($group);
    
        $ge = new GroupEvent();
        $res = $ge->sendMes2ActiveUserInGroup($group, 550);
        dump($res);
    }
    
    public function getSpeechesOld(){
        $group = I("get.group");
        $timeBefore = Timestamp::format(I("get.timeBefore"));
    
        $se = new SpeechEvent();
        $resl = $se->getGroupSpeechesOld($group, 4, $timeBefore);
    
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(393)->setDetail("错误")->build());
        }else if($resl == null){
            $this->ajaxReturn($response->setStatus(203)->build());
        }else {
            $this->ajaxReturn($response->setStatus(200)->setData($resl)->build());
        }
    }
    
    public function getSpeechesNew(){
        $group = I("get.group");
        $timeAfter = Timestamp::format(I("get.timeAfter"));
    
        $se = new SpeechEvent();
        $resl = $se->getGroupSpeechesNew($group, $timeAfter);
    
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(393)->setDetail("错误")->build());
        }else if($resl == null){
            $this->ajaxReturn($response->setStatus(203)->build());
        }else {
            $this->ajaxReturn($response->setStatus(200)->setData($resl)->build());
        }
    }
    
    public function addSpeech(){
        $group = I("post.group");
        $content = I("post.content");
        $user = I("post.user");
        $atuser = I("post.atuser",null);
        ($atuser == -1)?null:$atuser;
        $atpara = I("post.atpara",null);
        ($atpara == -1)?null:$atpara;
        $time = Timestamp::format(I("post.time"));
    
        $se = new SpeechEvent();
        $resl = $se->addGroupSpeech($user, $group, $time, $content,$atuser,$atpara);
    
        $ge = new GroupEvent();
        $res = $ge->sendMes2ActiveUserInGroup($group, 555);
    
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(393)->setDetail("错误")->build());
        }else {
            $this->ajaxReturn($response->setStatus(200)->setDetail($resl)->build());
        }
    }
     
}