<?php
namespace Tale\Model;
use Think\Model;
class ZanModel extends Model{
    protected $tableName = "zan";
    
    public function isParaZanedByUser($userId,$paraId){
        $resl = $this->where(array(
            "user"=>$userId,
            "para"=>$paraId
        ))->find();
        
        return $resl;
    }
    
    
    public function zan($userId,$paraId){
        $pm = new ParaModel();
        $taleId = $pm->getTaleIdByParaId($paraId);
        return $this->data(array(
                "user"=>$userId,
                "para"=>$paraId,
                "tale"=>$taleId
            ))->add();
    }
    
    public function unZan($userId,$paraId){
        return $this->where(array(
                "user"=>$userId,
                "para"=>$paraId
            ))->delete();
    }
    
    public function unZanAll($paraId){
        return $this->where(array(
            "para"=>$paraId
        ))->delete();
    }
    
    public function getParaZanNum($paraId){
        $resl = $this->where(array("para"=>$paraId))->select();
        if($resl === false){
            return false;
        }else if ($resl == null){
            return 0;
        }else{
            return count($resl);
        }
    }
    
    public function getTaleZanNum($taleId){
        $resl = $this->where(array("tale"=>$taleId))->select();
        if($resl === false){
            return false;
        }elseif ($resl == null){
            return 0;
        }else{
            return count($resl);
        }
    }
}