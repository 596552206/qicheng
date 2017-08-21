<?php
namespace Common\Model;
use Think\Model;
use User\Model\UserModel;
class SpeechModel extends Model{
    protected $tableName = "speech";
    
    public function getTaleSpeechesOld($tale,$limit,$beforeTime){
        $speeches = $this->where(array(
            "taleid"=>$tale,
            "time"=>array("lt",$beforeTime)
        ))->limit($limit)->field("userid,content,atuser,atpara,time")->order("id desc")->select();
        $um = new UserModel();
        foreach ($speeches as &$s){
            $s['nick'] = $um->getUserNickById($s['userid']);
            if($s['atuser']!=null)$s['atusernick'] = $um->getUserNickById($s['atuser']);
        }
        return $speeches;
    }
    
    public function getTaleSpeechesNew($tale,$afterTime){
        $speeches = $this->where(array(
            "taleid"=>$tale,
            "time"=>array("gt",$afterTime)
        ))->field("userid,content,atuser,atpara,time")->order("id desc")->select();
        $um = new UserModel();
        foreach ($speeches as &$s){
            $s['nick'] = $um->getUserNickById($s['userid']);
            if($s['atuser']!=null)$s['atusernick'] = $um->getUserNickById($s['atuser']);
        }
        return $speeches;
    }
    
    public function getGroupSpeechesOld($group,$limit,$beforeTime){
        $speeches = $this->where(array(
            "groupid"=>$group,
            "time"=>array("lt",$beforeTime)
        ))->limit($limit)->field("userid,content,atuser,atpara,time")->order("id desc")->select();
        $um = new UserModel();
        foreach ($speeches as &$s){
            $s['nick'] = $um->getUserNickById($s['userid']);
            if($s['atuser']!=null)$s['atusernick'] = $um->getUserNickById($s['atuser']);
        }
        return $speeches;
    }
    
    public function getGroupSpeechesNew($group,$afterTime){
        $speeches = $this->where(array(
            "groupid"=>$group,
            "time"=>array("gt",$afterTime)
        ))->field("userid,content,atuser,atpara,time")->order("id desc")->select();
        $um = new UserModel();
        foreach ($speeches as &$s){
            $s['nick'] = $um->getUserNickById($s['userid']);
            if($s['atuser']!=null)$s['atusernick'] = $um->getUserNickById($s['atuser']);
        }
        return $speeches;
    }
    
    public function addTaleSpeech($user,$tale,$content,$time,$atuser,$atpara){
        $speechId = $this->data(array(
            "userid"=>$user,
            "taleid"=>$tale,
            "content"=>$content,
            "time"=>$time,
            "atuser"=>$atuser,
            "atpara"=>$atpara
        ))->add();
        return $speechId;
    }
    
    public function addGroupSpeech($user,$group,$content,$time,$atuser,$atpara){
        $speechId = $this->data(array(
            "userid"=>$user,
            "groupid"=>$group,
            "content"=>$content,
            "time"=>$time,
            "atuser"=>$atuser,
            "atpara"=>$atpara,
            "atusernick"=>M("user")->where(array("id"=>$atuser))->getField("nick")
        ))->add();
        return $speechId;
    }
}