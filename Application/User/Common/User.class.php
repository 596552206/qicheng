<?php
namespace User\Common;
class User{
    
    public $id;
    public $phone;
    public $password;
    public $nick;
    public $gender;
    public $avatar;
    
    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

 /**
     * @param field_type $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

 /**
     * @param field_type $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

 /**
     * @param field_type $nick
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    }

 /**
     * @param field_type $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

 /**
     * @param field_type $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

 function __construct($id,$phone,$password,$nick,$gender,$avatar){
        $this->id = $id;
        $this->phone = (int)$phone;
        $this->password = $password;
        $this->nick = $nick;
        $this->gender = $gender;
        $this->avatar = $avatar;
    }
    
    
    
}