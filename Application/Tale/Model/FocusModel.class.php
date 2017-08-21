<?php
namespace Tale\Model;
use Think\Model;
class FocusModel extends Model{
    protected $tableName = "focus";
    
    public function isUserFocusTale($userId,$taleId){
        $resl = $this->where(array(
            "user"=>$userId,
            "tale"=>$taleId
        ))->find();
        
        return $resl;
    }
    
    public function toggleFocus($userId,$taleId){
        $isFocus = $this->isUserFocusTale($userId, $taleId);
        if($isFocus === false){
            $resl = false;
        }else if($isFocus == null){
            //weiguanzhu
            $resl = $this->data(array(
            "user"=>$userId,
            "tale"=>$taleId
        ))->add();
            
        }else{
            //yiguazhu
            $resl = $this->where(array(
                "user"=>$userId,
                "tale"=>$taleId
            ))->delete();
        }
        if($resl === false ){
            return false;
        }else{
            return true;
        }
    }
    
}