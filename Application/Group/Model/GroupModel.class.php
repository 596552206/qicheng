<?php
namespace Group\Model;
use Think\Model;
class GroupModel extends Model{
    protected $tableName = "groups";
    
    public function newGroup($name,$password){
        if($name == null){
            $data = array();
        }else{
            $data = array("name"=>$name,"password"=>$password);
        }
        return $this->data($data)->add();
    }
    
    public function groupMemberPlusOne($group){
        $memberOld = $this->where(array("id"=>$group))->getField("member");
        $memberNew = $memberOld+1;
        $resl = $this->where(array("id"=>$group))->data(array("member"=>$memberNew))->save();
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function groupMemberMinusOne($group){
        $memberOld = $this->where(array("id"=>$group))->getField("member");
        $memberNew = $memberOld-1;
        $resl = $this->where(array("id"=>$group))->data(array("member"=>$memberNew))->save();
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function groupParaNumPlusOne($group){
        $numOld = $this->where(array("id"=>$group))->getField("paranumber");
        $numNew = $numOld+1;
        $resl = $this->where(array("id"=>$group))->data(array("paranumber"=>$numNew))->save();
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
    
    public function getGroupRough($groupid){
        return $this->where(array("id"=>$groupid))->find();
    }
}