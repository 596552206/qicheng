<?php
namespace Common\Event;
use Think\Controller;
use Common\Model\SpeechModel;
class SpeechEvent extends Controller{
    
    public function getTaleSpeechesOld($id,$limit,$beforeTime){
        $sm = new SpeechModel();
        return $sm->getTaleSpeechesOld($id, $limit, $beforeTime);
    }
    
    public function getTaleSpeechesNew($id,$afterTime){
        $sm = new SpeechModel();
        return $sm->getTaleSpeechesNew($id, $afterTime);
    }
    
    public function getGroupSpeechesOld($id,$limit,$beforeTime){
        $sm = new SpeechModel();
        return $sm->getGroupSpeechesOld($id, $limit, $beforeTime);
    }
    
    public function getGroupSpeechesNew($id,$afterTime){
        $sm = new SpeechModel();
        return $sm->getGroupSpeechesNew($id, $afterTime);
    }
    
    public function addTaleSpeech($user,$tale,$time,$content,$atuser = null,$atpara = null){
        $sm = new SpeechModel();
        return $sm->addTaleSpeech($user, $tale, $content, $time, $atuser, $atpara);
    }
    
    public function addGroupSpeech($user,$group,$time,$content,$atuser = null,$atpara = null){
        $sm = new SpeechModel();
        return $sm->addGroupSpeech($user, $group, $content, $time, $atuser, $atpara);
    }
    
}