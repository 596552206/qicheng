<?php
namespace Tale\Event;
use Think\Controller;
use Tale\Model\TaleModel;
use Tale\Model\ParaModel;
use Common\Model\ActiveModel;
use Tale\Model\VoteModel;
use Common\Model\SilenceModel;
use Org\Util\Getui;
class TaleEvent extends Controller {
   
    
    /**
     * 
     * @param int $sponsorId
     * @param array $tags
     * @param string $time
     */
    public function newTale($sponsorId,$tags,$time){
        $taleModel = new TaleModel();
        $resl = $taleModel->addTale($sponsorId, $tags, $time);
//        $changeNameList = array();
//        $changeNameListId = array();
//        $isSuccessful = !($resl == false || null);
//         if($isSuccessful)
//         {
//         //var_dump(json_decode($tags));
//         $tagM = new TagModel();
//         foreach ($tags as $v){
//             $ifExist = $tagM->where(array("id"=>(int)$v))->find();
//             if($ifExist == false || null){
//                 //echo "sting";
//                 $addedTagId = $tagM->newTag($v, $resl);
//                 $changeNameList[] = $v;
//                 $changeNameListId[] = $addedTagId;
//             }else{
//                 //echo "else";
//                 $tagM->addTale($v,$resl);
//             }
//         }
//         }
//         foreach ($tags as &$tag){
//             if (in_array($tag, $changeNameList)){
//                 $tag = (int)$changeNameListId[array_keys($changeNameList,$tag)[0]];
//             }
//         }
//         //var_dump($tags);
//         $data['id']=$resl;
//         //echo $resl;
//         $data['tags']=json_encode($tags);
//         $taleModel->save($data);
//         //echo $resl;
        
        return $resl;
    }
    
    public function newPara($userId,$taleId,$content,$time){
        $pm = new ParaModel();
        $tm = new TaleModel();
        $paraNum = $pm->getParaNumNext($taleId);
        
        $tm->setParaNumOfATale($taleId, $paraNum);
        $resl = $pm->newPara($userId, $taleId, $content, $time, $paraNum);
        
        return $resl;
    }
    
//     public function getStatus($userId,$taleId,$time){
//         $pm = new ParaModel();
//         $um = new UserModel();
//         $latestPara = $pm->getLatestPara($taleId);
//         //dump($latestPara);
//         $deltaTime = $time - $latestPara['time'];
//         $latestUser = $latestPara['userid'];
//         $userSAccessibility = (boolean)$um->getUser($userId)['accessibility'];
//         //dump($latestUser);
//         $A = $userSAccessibility;
//         $c = ($userId == $latestUser)? FALSE : TRUE;
//         $B = ($deltaTime > 600)? TRUE : $c ;
//         //dump($B);
//         if($A && $B){
//             return TRUE;
//         }else {
//             if(!$A)return 2;//用户被禁
//             return 3;//十分钟内同一用户只能连续写一段。
//         }
//    }
    
    public function getHotTaleView($timeBefore,$limit){
        $tm = new TaleModel();
        return $tm->getTaleViews($timeBefore, $limit,"zan desc,paraNumber desc,time desc");
    }
    
    public function getLatestTaleView($timeBefore,$limit){
        $tm = new TaleModel();
        return $tm->getTaleViews($timeBefore, $limit,"time desc");
    }
    
    public function getCertainTaleView($taleId){
        $tm = new TaleModel();
        return $tm->getCertainView($taleId);
    }
    
    public function getParasOfACertainTale($taleId,$paraNumAfter,$limit){
        $pm = new ParaModel();
        return $pm->getParasOfACertainTale($taleId, $paraNumAfter, $limit);
    }
    
    public function getTaleZanNum($taleId){
        $tm = new TaleModel();
        return $tm->getTaleZanNum($taleId);
    }
    
    public function getParaZanNum($paraId){
        $pm = new ParaModel();
        return $pm->getParaZanNum($paraId);
    }
    
    public function isParaZanedByUser($userId,$paraId){
        $pm = new ParaModel();
        return $pm->isParaZanedByUser($userId, $paraId);
    }
    
    public function zanPara($userId,$paraId){
        $pm = new ParaModel();
        $tm = new TaleModel();
        $resl = $pm->zanPara($userId, $paraId);
        $pm->freshParaZan($paraId);
        $taleId = $pm->getTaleIdByParaId($paraId);
        $tm->freshTaleZan($taleId);
        
        return $resl;
    }
    
    public function unZanPara($userId,$paraId){
        $pm = new ParaModel();
        $tm = new TaleModel();
        $resl = $pm->unZanPara($userId, $paraId);
        $pm->freshParaZan($paraId);
        $tm->freshTaleZan($pm->getTaleIdByParaId($paraId));
        return $resl;
    }
    
    public function vote($user,$para){
        $am = new ActiveModel();
        $pm = new ParaModel();
        $tale = $pm->getTaleIdByParaId($para);
        $activeNum = $am->getActiveNum($tale);
        $vm = new VoteModel();
        $vm->addVoteRecord($user, $para);
        $voteNum = $vm->getVoteNum($para);
        if($voteNum > $activeNum/2){//**********该条判断可能会根据业务需求改编**********
            $pm->removePara($user, $para);
            return 1;//已删除
        }else{
            return 0;//已投票
        }
    }
    
    
    public function sendMes2ActiveUserInTale($tale,$mes){
        $am = new ActiveModel();
        $cids = $am->getActiveUserCidArr($tale);
        $gt = new Getui();
        return $gt->pushMes2Users($mes, $cids);
    }
    
    public function getStatus($tale){
        $sm = new SilenceModel();
        if($sm->isSilent($tale)){
            return 501;//静默
        }else{
            $pm = new ParaModel();
            $last = $pm->getLatestPara($tale)['time'];
            $now = time();
            $delta = $now - $last;
            if($delta < 20){
                return "502".$last;//冷却
            }else{
                return 500;//就绪
            }
        }
    }
}