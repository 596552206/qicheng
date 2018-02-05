<?php
namespace User\Event;
use Think\Controller;
use User\Model\UserModel;
use User\Common\User;
class UserEvent extends Controller{
    public function register($phone,$nick,$password){
        $um = new UserModel();
        if($um->getUserByPhone($phone) != null){
            return 2;//手机号已被注册
        }
        if($um->isNickExist($nick)){
            return 3;//用户名已被注册
        }else{
            $gender = 1;
            $avatar = "Public/user/avatar/default-avatar.png";
            $user = new User(null, $phone, $password, $nick, $gender, $avatar);
            $resl = $um->newUser($user);
            if($resl===null||$resl===false){
                return false;
            }else{
                return true;
            }
        }
    }
}