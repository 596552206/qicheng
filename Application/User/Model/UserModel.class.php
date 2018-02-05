<?php
namespace User\Model;
use Think\Model;
class UserModel extends Model{
    protected $tableName = 'user';
    
    public function newUser($user){
        $this->create($user);
        return $this->add();
    }
    
    public function getUser($userId){
        return $this->where(array("id"=>$userId))->find();
    }
    
    public function getUserByPhone($phone){
        $resl =  $this->where(array("phone"=>$phone))->find();
        return ($resl == null || $resl == false) ? null : $resl; 
       }
    
    public function getUserAvatarByPhone($phone){
        $resl = $this->where(array("phone"=>$phone))->getField("avatar");
        $resl = $this->formateAvatar($resl);
        return ($resl == null || $resl == false) ? null : $resl;  
    }
    
    public function getUserNickByPhone($phone){
        $resl = $this->where(array("phone"=>$phone))->getField("nick");
        return ($resl == null || $resl == false) ? null : $resl;
    }
    public function getUserNickById($uid){
        $resl = $this->where(array("id"=>$uid))->getField("nick");
        return ($resl == null || $resl == false) ? null : $resl;
    }
    public function getUserCidByUid($user){
        $resl = $this->where(array("id"=>$user))->getField("clientid");
        return ($resl == null || $resl == false) ? null : $resl;
    }
    public function getUserRough($userId){
        $rough = $this->where(array("id"=>$userId))->field("id,nick,avatar")->find();
        $rough['avatar'] = $this->formateAvatar($rough['avatar']);
        return $rough;
    }
    public function isNickExist($nick){
        $resl =  $this->where(array("nick"=>$nick))->find();
        return ($resl == null || $resl == false) ? false : true; 
    }
    
    
    private function formateAvatar($avatar){
        if(substr($avatar, 0,6) == "Public"){
            return C("APP_URL").$avatar;
        }else{
            return $avatar;
        }
    }
}