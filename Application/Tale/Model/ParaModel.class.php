<?php
namespace Tale\Model;
use Think\Model;
use User\Model\UserModel;
use Org\Util\Date;
class ParaModel extends Model{
    protected $tableName = "para";
    
    public function getParaNumNext($taleId){
        $data = $this->where(array("taleId"=>$taleId,"deleted"=>0))->order("paraNum desc")->find()['paranum'];
        if($data == null){
            $num = 1;
        }else{
            $num = (int)$data +1;
        }
        return $num;
    }
    
    public function newPara($userId,$taleId,$content,$time,$paraNum){
        $data = array(
            "userId"=>$userId,
            "taleId"=>$taleId,
            "content"=>$content,
            "time"=>$time,
            "paraNum"=>$paraNum
        );
        
        return $this->data($data)->add();
    }
    
    public function removePara($user,$para){
        $tm = new TaleModel();
        $taleId = $this->getTaleIdByParaId($para);
        $this->where(array("id"=>$para))->data(array("deleted"=>1))->save();//标记para表为删除
        $this->unZanParaAll($para);//zan表数据删除
        $this->freshParaZan($para);//刷新para表，实际上没必要
        $tm->freshTaleZan($taleId);//刷新tale表，必要
        
        $paraNum = $this->getParaNumNext($taleId)-1;
        $tm->setParaNumOfATale($taleId, $paraNum);
    }
        
    public function getLatestPara($taleId){
        return $this->where(array("taleId"=>$taleId,"deleted"=>0))->order("id desc")->find();
    }
    
    public function getFirstPara($taleId){
        return $this->where(array("taleId"=>$taleId,"deleted"=>0))->order("id")->find();
    }
    
    public function getParasOfACertainTale($taleId,$paraNumAfter,$limit){
        $data = $this->where(array(
            "taleId" =>array("EQ",$taleId),
            "paraNum"=>array("GT",$paraNumAfter),
            "deleted"=>0
        ))->order('id')->limit($limit)->select();
        
        $um = new UserModel();
        
        foreach ($data as &$v){
            $v['usernick'] = $um->getUser($v['userid'])['nick'];
        }
        
        return ($data == null || $data === false)? FALSE : $data;
    }
    
    public function getTaleIdByParaId($paraId){
        return $this->where(array("id"=>$paraId,"deleted"=>0))->find()['taleid'];
    }
    
    public function getParaZanNum($paraId){
        $zm = new ZanModel();
        return $zm->getParaZanNum($paraId);
    }
    
    public function isParaZanedByUser($userId,$paraId){
        $zm = new ZanModel();
        $resl = $zm->isParaZanedByUser($userId, $paraId);
        if($resl === false){
            return false;
        }elseif($resl == null){
            return "no";
        }else{
            return "yes";
        }
    }
    
    public function zanPara($userId,$paraId){
        $zm = new ZanModel();
        $resl = $zm->zan($userId, $paraId);
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function unZanPara($userId,$paraId){
        $zm = new ZanModel();
        $resl = $zm->unZan($userId, $paraId);
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function unZanParaAll($paraId){
        $zm = new ZanModel();
        $resl = $zm->unZanAll($paraId);
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function freshParaZan($paraId){
        $zan = $this->getParaZanNum($paraId);
        $resl = $this->where(array("id"=>$paraId))->data(array("zan"=>$zan))->save();
        if($resl === false){
            return false;
        }else{
            return true;
        }
    }

}