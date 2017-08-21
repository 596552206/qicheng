<?php
namespace Tale\Model;
use Think\Model;
use User\Model\UserModel;
class TaleModel extends Model{
    protected $tableName = "tale";
    

    /**
     * 
     * @param id $sponsorId
     * @param array $tags
     * @param int $time
     */
    public function addTale($sponsorId,$tags,$time){
        $this->sponsorId = $sponsorId;
        $this->tags = json_encode($tags);
        $this->time = $time;
        $taleId = $this->add();
        
        $tagM = new TagModel();
        foreach ($tags as $v){
            
                $tagM->addTale($v, $taleId);
        }
        return $taleId;
    }
    
    public function getTaleViews($timeBefore,$limit,$order){
        $pm = new ParaModel();
        $um = new UserModel();
        $tm = new TagModel();
        
        $resl = $this->where(
            array(
            "time"=>array("LT",$timeBefore),
            "deleted"=>0
            )
            )->order("id desc")->limit($limit)->order($order)->select();
        
        foreach ($resl as &$v){
            $v['paraone'] = $pm->getFirstPara($v['id'])['content'];
            $v['sponsornick'] = $um->getUser($v['sponsorid'])['nick'];
            $v['sponsoravatar'] = $um->getUser($v['sponsorid'])['avatar'];
            $v['tagset'] = $tm->generateTagIdAndNameSet(json_decode($v['tags']));
            unset($v['tags']);
        }
        
        return $resl;
    }
    
    
    
    public function getCertainView($taleId){
        $pm = new ParaModel();
        $um = new UserModel();
        $tm = new TagModel();
        
        $resl = $this->where(array("id"=>$taleId,"deleted"=>0))->find();
        
        if($resl == null || $resl === FALSE){
            return false;
        }else {
            $resl['paraone'] = $pm->getFirstPara($resl['id'])['content'];
            $resl['sponsornick'] = $um->getUser($resl['sponsorid'])['nick'];
            $resl['sponsoravatar'] = $um->getUser($resl['sponsorid'])['avatar'];
            $resl['tagset'] = $tm->generateTagIdAndNameSet(json_decode($resl['tags']));
            unset($resl['tags']);
            return $resl;
        }
    }
    
    public function setParaNumOfATale($taleId,$paraNum){
        $this->where(array("id"=>$taleId))->data(array("paraNumber"=>$paraNum))->save();
    }
    
    
    public function getTaleZanNum($taleId){
        $zm = new ZanModel();
        return $zm->getTaleZanNum($taleId);
    }
    
    public function freshTaleZan($taleId){
        $zan = $this->getTaleZanNum($taleId);
        $resl = $this->where(array("id"=>$taleId))->data(array("zan"=>$zan))->save();
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
   
}