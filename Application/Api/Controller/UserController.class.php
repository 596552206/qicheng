<?php
namespace Api\Controller;
use Think\Controller;
use User\Model\UserModel;
use User\Common\User;
use Common\Common\Response;
use User\Event\UserEvent;
class UserController extends Controller {
    public function login(){
        $phone = I("get.phone");
        $passwordDK = I("get.password");
        
        $um = new UserModel();
        $user = $um-> getUserByPhone($phone);
        
        $response = new Response();
        if($user == null){
            //用户不存在
            $this->ajaxReturn($response->setStatus(399)->setDetail("用户不存在")->build());
        }else{
            if($passwordDK == $user['password']){ // TODO 此处密码未加密，后期需要加密！！！
                unset($user['password']);
                $this->ajaxReturn($response->setStatus(200)->setDetail("登录成功")->setData($user)->build());
            }else{
                $this->ajaxReturn($response->setStatus(398)->setDetail("密码错误")->build());
            }
        }
    }
    
    public function loginById(){
        $uId = I("get.id");
        $passwordDK = I("get.password");
        
        $um = new UserModel();
        $user = $um-> getUser($uId);
        
        $response = new Response();
        if($user == null){
            //用户不存在
            $this->ajaxReturn($response->setStatus(399)->setDetail("用户不存在")->build());
        }else{
            if($passwordDK == $user['password']){ // TODO 此处密码未加密，后期需要加密！！！
                unset($user['password']);
                $this->ajaxReturn($response->setStatus(200)->setDetail("登录成功")->build());
            }else{
                $this->ajaxReturn($response->setStatus(398)->setDetail("密码错误")->build());
            }
        }
    }
    
    public function newUser(){
        $phone = I("post.phone");
        $password = I("post.password");
        $nick = I("post.nick");
        //$gender = I("post.gender");
        //$avatar = I("post.avatar");
        
        $ue = new UserEvent();
        $resl = $ue->register($phone, $nick, $password);
        //echo $resl;
        if($resl === true){
            $response = new Response();
            $this->ajaxReturn($response->setStatus(200)->setDetail("注册成功")->setData($resl)->build());
        }else if($resl === false){
            $response = new Response();
            $this->ajaxReturn($response->setStatus(400)->setDetail("注册失败")->build());
        }else if($resl == 2){
            $response = new Response();
            $this->ajaxReturn($response->setStatus(393)->setDetail("手机号已被注册")->build());
        }else if($resl == 3){
            $response = new Response();
            $this->ajaxReturn($response->setStatus(393)->setDetail("用户名已被注册")->build());
        }
    }
    
    public function getAvatarByPhone(){
        $phone = I("get.phone");
        
        $um = new UserModel();
        
        $avatar = $um->getUserAvatarByPhone($phone);
        
        $response = new Response();
        if($avatar == null){
            $this->ajaxReturn($response->setStatus(400)->setDetail("查询失败")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail($avatar)->build());
        }
    }
    
    public function getNickByPhone(){
        $phone = I("get.phone");
    
        $um = new UserModel();
    
        $avatar = $um->getUserNickByPhone($phone);
    
        $response = new Response();
        if($avatar == null){
            $this->ajaxReturn($response->setStatus(400)->setDetail("查询失败")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail($avatar)->build());
        }
    }
    
    public function updateClientId(){
        $user = I("get.user");
        $clientId = I("get.clientid");
        
        $um = new UserModel();
        $resl = $um->where(array("id"=>$user))->data(array("clientid"=>$clientId))->save();
    }
    
    
    
}