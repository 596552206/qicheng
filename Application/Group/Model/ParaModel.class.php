<?php
namespace Group\Model;
use Think\Model;
use User\Model\UserModel;
class ParaModel extends Model{
    protected $tableName = "para_group";
    
    public function getParaNumNext($group){
        $data = $this->where(array("groupid"=>$group))->order("paranum desc")->find()['paranum'];
        if($data == null){
            $num = 1;
        }else{
            $num = (int)$data +1;
        }
        return $num;
    }
    
    public function newPara($userId,$groupId,$content,$time,$paraNum){
        $data = array(
            "user"=>$userId,
            "groupid"=>$groupId,
            "content"=>$content,
            "time"=>$time,
            "paranum"=>$paraNum
        );
        
        return $this->data($data)->add();
    }
    
    public function getLatestPara($group){
        return $this->where(array("groupid"=>$group))->order("id desc")->find();
    }
    
    public function getFirstPara($group){
        return $this->where(array("groupid"=>$group))->order("id")->find();
    }
    
    public function getParasOfACertainGroup($groupId,$paraNumAfter,$limit){
        $data = $this->where(array(
            "groupid" =>array("EQ",$groupId),
            "paranum"=>array("GT",$paraNumAfter)
        ))->order('id')->limit($limit)->select();
        
        $um = new UserModel();
        
        foreach ($data as &$v){
            $v['usernick'] = $um->getUser($v['user'])['nick'];
        }
        
        return ($data == null || $data === false)? FALSE : $data;
    }
    
    
    
}