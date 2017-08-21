<?php
namespace Common\Model;
use Think\Model;
class SilenceModel extends Model{
    protected $tableName = "silent";
    
    public function setSilent($tale){
        $this->data(array("tale"=>$tale))->add();
    }
    
    public function unsetSilent($tale){
        $this->where(array("tale"=>$tale))->delete();
    }
    
    public function isSilent($tale){
        $resl = $this->where(array("tale"=>$tale))->select();
        if($resl == null){
            return false;
        }else{
            return true;
        }
    }
    
    public function setGroupSilent($group){
        $this->data(array("groupid"=>$group))->add();
    }
    
    public function unsetGroupSilent($group){
        $this->where(array("groupid"=>$group))->delete();
    }
    
    public function isGroupSilent($group){
        $resl = $this->where(array("groupid"=>$group))->select();
        if($resl == null){
            return false;
        }else{
            return true;
        }
    }
}