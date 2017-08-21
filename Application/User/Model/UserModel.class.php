<?php
namespace User\Model;
use Think\Model;
class UserModel extends Model{
    protected $tableName = 'user';
    
    public function newUser($user){
        $this->create($user);
        $this->add();
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
}