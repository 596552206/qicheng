<?php
namespace Api\Controller;
use Think\Controller;
use Common\Common\Response;
use Tale\Event\TaleEvent;
use Common\Common\Timestamp;
use Tale\Model\FocusModel;
use Tale\Model\ParaModel;
use Org\Util\Getui;
use Common\Model\ActiveModel;
use User\Model\UserModel;
use Common\Model\SilenceModel;
use Common\Event\SpeechEvent;
class TaleController extends Controller{
    
    public function newTale(){
        $sponsorId = I("post.sponsorId");
        $tags = json_decode(html_entity_decode(I("post.tags")));
        $time = Timestamp::format(I("post.time"));
        $content = I("post.content");
        
        $te = new TaleEvent();
        $taleId = $te->newTale($sponsorId, $tags, $time);
        $resl = $te->newPara($sponsorId, $taleId, $content, $time);
        
        $response = new Response();
        //echo $resl;
        $isSuccessful = !($resl === false || null);
        if($isSuccessful)
        {
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }else
        {
            $this->ajaxReturn($response->setStatus(400)->setDetail("Err")->build());
        }
        
    }
    
    public function writePara(){
        $userId = I("post.userId");
        $taleId = I("post.taleId");
        $content = I("post.content");
        $time = Timestamp::format(I("post.time"));
        
        $te = new TaleEvent();
        $resl = $te->newPara($userId, $taleId, $content, $time);
        
        $response = new Response();
        $isSuccessful = !($resl === false || null);
        if($isSuccessful)
        {
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }else
        {
            $this->ajaxReturn($response->setStatus(400)->setDetail("Err")->build());
        }
    }
    
    
    public function getStatus(){
        $userId = I("get.userId");
        $taleId = I("get.taleId");
        $time = Timestamp::format(I("get.time"));
        
        $te = new TaleEvent();
        $resl = $te->getStatus($userId, $taleId, $time);
        
        $response = new Response();
        if($resl === TRUE)
        {
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }else
        {
            if($resl == 2){
                $this->ajaxReturn($response->setStatus(400)->setDetail("用户被禁言")->build());
            }else if($resl == 3){
                $this->ajaxReturn($response->setStatus(400)->setDetail("上一段也是你写的哦")->build());
            }
        }
    }
    
    public function getHotTaleView(){
        $timeBefore = Timestamp::format(I("get.timeBefore"));
        $limit = I("get.limit");
        
        $te = new TaleEvent();
        $resl = $te->getHotTaleView($timeBefore, $limit);
        
        $response = new Response();
        if(count($resl)==0 || $resl == null || $resl === false){
            $this->ajaxReturn($response->setStatus(400)->setDetail("无结果")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }
        
    }
    
    public function getLatestTaleView(){
        $timeBefore = Timestamp::format(I("get.timeBefore"));
        $limit = I("get.limit");
    
        $te = new TaleEvent();
        $resl = $te->getLatestTaleView($timeBefore, $limit);
    
        $response = new Response();
        if(count($resl)==0 || $resl == null || $resl === false){
            $this->ajaxReturn($response->setStatus(400)->setDetail("无结果")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }
    
    }
    
    public function getCertainTaleView(){
        $taleId = I("get.taleId");
        
        $te = new TaleEvent();
        $resl = $te->getCertainTaleView($taleId);
        
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(400)->setDetail("无结果")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }
    }
    
    public function getParasOfTale(){
        $paraNumAfter = I("get.paraNumAfter");
        $taleId = I("get.taleId");
        $limit = I("get.limit");
        
        $te = new TaleEvent();
        $resl = $te->getParasOfACertainTale($taleId,$paraNumAfter,$limit);
        
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(400)->setDetail("无结果")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }
    }
    
    public function isUserFocusTale(){
        $taleId = I("get.taleId");
        $userId = I("get.userId");
        
        $fm = new FocusModel();
        $resl = $fm->isUserFocusTale($userId, $taleId);
        
        $response = new Response();
        if($resl === false){
            //查询失败
            $this->ajaxReturn($response->setStatus(397)->setDetail($resl."")->build());
        }else if($resl == null){
            //未关注
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData("no")->build());
        }else{
            //已关注
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData("yes")->build());
        }
        
       
    }
    
    public function toggleFocus(){
        $taleId = I("get.taleId");
        $userId = I("get.userId");
        
        $fm = new FocusModel();
        $resl = $fm->toggleFocus($userId, $taleId);
        
        $response = new Response();
        if($resl == false){
            //失败
            $this->ajaxReturn($response->setStatus(397)->setDetail("查询失败")->build());
        }else{
            //成功
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->build());
        }
        
    }
    
    public function getTaleZanNum(){
        $taleId = I("get.taleId");
        
        $te = new TaleEvent();
        $resl = $te->getTaleZanNum($taleId);
        
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(396)->setDetail("数据库错误")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail($resl)->build()); //数据直接通过detail传递
        }
    }

    public function getParaZanNum(){
        $paraId = I("get.paraId");
        
        $te = new TaleEvent();
        $resl = $te->getParaZanNum($paraId);
        
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(396)->setDetail("数据库错误")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail($resl)->build()); //数据直接通过detail传递
        }
    }
    
    public function isParaZanedByUser(){
        $paraId = I("get.paraId");
        $userId = I("get.userId");
        
        $te = new TaleEvent();
        $resl = $te->isParaZanedByUser($userId, $paraId);
        
    $response = new Response();
        if($resl === false){
            //查询失败
            $this->ajaxReturn($response->setStatus(396)->setDetail($resl."")->build());
        }else if($resl == "yes"){
            //未zan
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData("yes")->build());
        }else{
            //已zan
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData("no")->build());
        }
    }
    
    public function toggleZan(){
        $paraId = I("get.paraId");
        $userId = I("get.userId");
        
        $te = new TaleEvent();
        $isZaned = $te->isParaZanedByUser($userId, $paraId);
        //echo "yuanlaide".$isZaned;
        if($isZaned == "no"){
            $te->zanPara($userId, $paraId);
        }else if($isZaned == "yes"){
            $te->unZanPara($userId, $paraId);
        }
        $resl = $te->isParaZanedByUser($userId, $paraId);
        
        $response = new Response();
        if($resl === false){
            //查询失败
            $this->ajaxReturn($response->setStatus(396)->setDetail($resl."")->build());
        }else if($resl == "yes"){
            //未zan
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData("yes")->build());
        }else{
            //已zan
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData("no")->build());
        }
    }
    
    public function voteToDelete(){
        $user = I("get.user");
        $para = I("get.para");
        
        $pm = new ParaModel();
        $tale = $pm->getTaleIdByParaId($para);
        $te = new TaleEvent();
        $resl = $te->vote($user, $para);
        
        $response = new Response();
        if($resl == 0){
            //已投票
            $this->ajaxReturn($response->setStatus(503)->setDetail("投票成功")->build());
        }else if ($resl == 1){
            $te->sendMes2ActiveUserInTale($tale,504);
            $this->ajaxReturn($response->setStatus(504)->setDetail("删除成功")->build());
        }
    }
    
    public function setActive(){
        $user = I("get.user");
        $tale = I("get.tale");
        
        $am = new ActiveModel();
        $am->setActive($user, $tale);
    }
    
    public function unsetActive(){
        $user = I("get.user");
        $tale = I("get.tale");
        
        $am = new ActiveModel();
        $am->unsetActive($user, $tale);
    }
    
   
    public function testSendMessage2ActiveUser(){
        $tale = I("get.tale");
        $status = I("get.status");
        $te = new TaleEvent();
        $res = $te->sendMes2ActiveUserInTale($tale, $status);
        dump($res);
    }
    
    public function askPushStatus(){
        $tale = I("get.tale");
        $user = I("get.user");
        $um = new UserModel();
        $cid = $um->getUserCidByUid($user);
        $te = new TaleEvent();
        $status = $te->getStatus($tale);
        $gt = new Getui();
        $res = $gt->pushMes2Single($status, $cid);
        dump($res);
    }
    
    public function setSilent(){
        $tale = I("get.tale");
        
        $sm = new SilenceModel();
        $sm->setSilent($tale);
        
        $te = new TaleEvent();
        $res = $te->sendMes2ActiveUserInTale($tale, 501);
        dump($res);
    }
    public function unsetSilent(){
        $tale = I("get.tale");
    
        $sm = new SilenceModel();
        $sm->unsetSilent($tale);
        
        $te = new TaleEvent();
        $status = $te->getStatus($tale);
        $res = $te->sendMes2ActiveUserInTale($tale, $status);
        dump($res);
    }
    
    public function getSpeechesOld(){
        $tale = I("get.tale");
        $timeBefore = Timestamp::format(I("get.timeBefore"));
        
        $se = new SpeechEvent();
        $resl = $se->getTaleSpeechesOld($tale, 4, $timeBefore);
        
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
        $tale = I("get.tale");
        $timeAfter = Timestamp::format(I("get.timeAfter"));
    
        $se = new SpeechEvent();
        $resl = $se->getTaleSpeechesNew($tale,$timeAfter);
    
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
        $tale = I("post.tale");
        $content = I("post.content");
        $user = I("post.user");
        $atuser = I("post.atuser",null);
        ($atuser == -1)?null:$atuser;
        $atpara = I("post.atpara",null);
        ($atpara == -1)?null:$atpara;
        $time = Timestamp::format(I("post.time"));
        
        $se = new SpeechEvent();
        $resl = $se->addTaleSpeech($user, $tale, $time, $content, $atuser, $atpara);
        
        $te = new TaleEvent();
        $res = $te->sendMes2ActiveUserInTale($tale, 505);
    
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(393)->setDetail("错误")->build());
        }else {
            $this->ajaxReturn($response->setStatus(200)->setDetail($resl)->build());
        }
    }
   
        
}
