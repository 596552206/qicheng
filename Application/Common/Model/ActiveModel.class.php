<?php
namespace Common\Model;
use Think\Model;
use User\Model\UserModel;
class ActiveModel extends Model{
    protected $tableName = "active_user";
    
    public function setActive($user,$tale){
        $this->clearActive($user);
        $data = array(
            "user"=>$user,
            "tale"=>$tale
        );
        $this->data($data)->add();
      
    }
    public function setGroupActive($user,$group){
        $this->clearActive($user);
        $data = array(
            "user"=>$user,
            "groupid"=>$group
        );
        $this->data($data)->add();
    
    }
    
    
    public function unsetActive($user,$tale){
        $this->where(array(
            "user"=>$user,
            "tale"=>$tale
        ))->delete();
    }
    public function unsetGroupActive($user,$group){
        $this->where(array(
            "user"=>$user,
            "groupid"=>$group
        ))->delete();
    }
    
    public function clearActive($user){
        $this->where(array(
            "user"=>$user
        ))->delete();
    }
    
    public function isActive($user,$tale){
         $data = array(
            "user"=>$user,
            "tale"=>$tale
        );
         $resl = $this->where($data)->find();
         if($resl == null){
             return false;
         }else{
             return true;
         }
    }
    public function isGroupActive($user,$group){
        $data = array(
            "user"=>$user,
            "groupid"=>$group
        );
        $resl = $this->where($data)->find();
        if($resl == null){
            return false;
        }else{
            return true;
        }
    }
    
    
    public function getActiveNum($tale){
        $resl = $this->where(array("tale"=>$tale))->select();
        if($resl == null){
            return 0;
        }else{
            return count($resl);
        }
    }
    public function getGroupActiveNum($group){
        $resl = $this->where(array("groupid"=>$group))->select();
        if($resl == null){
            return 0;
        }else{
            return count($resl);
        }
    }
    
    public function getActiveUserArr($tale){
        $resl = $this->where(array("tale"=>$tale))->field("user")->select();  
        $userArr = [];
        foreach ($resl as $v){
            $userArr[] = $v['user'];
        }
        return $userArr;
    }
    public function getGroupActiveUserArr($group){
        $resl = $this->where(array("groupid"=>$group))->field("user")->select();
        $userArr = [];
        foreach ($resl as $v){
            $userArr[] = $v['user'];
        }
        return $userArr;
    }
    
    public function getActiveUserCidArr($tale){
        $um = new UserModel();
        $uidList = $this->getActiveUserArr($tale);
        $cidList=[];
        foreach ($uidList as $v){
            $cidList[] = $um->getUserCidByUid($v);
        }
        return $cidList;
    }
    public function getGroupActiveUserCidArr($group){
        $um = new UserModel();
        $uidList = $this->getGroupActiveUserArr($group);
        $cidList=[];
        foreach ($uidList as $v){
            $cidList[] = $um->getUserCidByUid($v);
        }
        return $cidList;
    }
}