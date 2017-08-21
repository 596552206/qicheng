<?php
namespace User\Controller;
use Think\Controller;
use User\Model\UserModel;
use User\Common\User;
class UserController extends Controller {
    public function getUserByPhone($phone,$password){
        
    }
    
    public function newUser(){
        $phone = I("get.phone");
        $password = I("get.password");
        $nick = I("get.nick");
        $gender = I("get.gender");
        $avatar = I("get.avatar");
        $um = new UserModel();
        $user = new User(null, $phone, $password, $nick, $gender, $avatar);
        $um->newUser($user);
    }
}