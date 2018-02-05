<?php
namespace Group\Event;
use Think\Controller;
use Group\Model\GroupModel;
use Group\Model\ParaModel;
use Common\Model\ActiveModel;
use Org\Util\Getui;
use Common\Model\SilenceModel;
use User\Model\UserModel;
class GroupEvent extends Controller{
    public function newGroup($user,$name,$password){
        //在group表插入记录
        $gm = new GroupModel();
        $groupId = $gm->newGroup($name,$password);
        //在User-Group表插入记录
        if($groupId === false){
            return false;
        }else{
            $data = array(
                "userid"=>$user,
                "groupid"=>$groupId,
            );
            $resl = M("user_group")->data($data)->add();
            if($resl === false){
                return false;
            }else{
                return $groupId;
            }
        }
    }
    
    public function joinGroup($user,$group){
        //让group表的member值加一
        $gm = new GroupModel();
        $resl1 = $gm->groupMemberPlusOne($group);
        //在user-group表插入记录
        if($resl1 === false){
            return false;
        }else{
            $data = array(
                "userid"=>$user,
                "groupid"=>$group
            );
            $resl2 = M("user_group")->data($data)->add();
            if($resl2 === false){
                return false;
            }else{
                return true;
            }
        }
        
    }
    
    public function quitGroup($user,$group){
        //让group表的member值减一
        $gm = new GroupModel();
        $resl1 = $gm->groupMemberMinusOne($group);
        //在user-group表删除记录
        if($resl1 === false){
            return false;
        }else{
        $resl = M("user_group")->where(array("userid"=>$user,"groupid"=>$group))->delete();
        if($resl == 0 || $resl === false){
            return false;
        }else{
            return true;
        }
        }
    }
    
    public function getGroupByUser($user){
        $groupIdList = M("user_group")->where(array("userid"=>$user))->order("id desc")->select();
        if($groupIdList == null)return  null;
        $groupList = array();
        foreach ($groupIdList as $v){
            $groupList[] = $this->getGroupView($v['groupid']);
        }
        return $groupList;
    }
    
    public function getParaOfGroup($groupId,$paraNumAfter,$limit){
        $pm = new ParaModel();
        return $pm->getParasOfACertainGroup($groupId, $paraNumAfter, $limit);
    }
    
    public function hasUserJoinedAnyGroup($userid){
        $resl = M("user_group")->where(array("userid"=>$userid))->select();
        if($resl === false)return 0;//错误
        if($resl == null)return 1;//无
        return 2;//有
    }
    
    public function hasUserJoinedCertainGroup($userid,$groupid){
        $resl = M("user_group")->where(array("userid"=>$userid,"groupid"=>$groupid))->select();
        if($resl === false)return 0;//错误
        if($resl == null)return 1;//无
        return 2;//有
    }
    
    public function writeParaOfAGroup($user,$group,$content,$time){
        //在group-para表中创建一个新para
        $pm = new ParaModel();
        $paraNum = $pm->getParaNumNext($group);
        $paraId = $pm->newPara($user, $group, $content, $time, $paraNum);
        //在group表中使paranumber的值加一
        if($paraId === false){
            return false;//失败
        }else{
            $gm = new GroupModel();
            $resl = $gm->groupParaNumPlusOne($group);
            if($resl){
                return $paraNum;
            }else {
                return false;
            }
        }
    }
    
    public function isGroupExist($group){
        $gm = new GroupModel();
        $resl = $gm->where(array("id"=>$group))->find();
        if($resl === false)return 0;//网络错误
        if($resl == null)return 1;//不存在
        return 2;//存在
    }
    
    public function confirmPassword($password,$group){
        $gm = new GroupModel();
        $realPassword = $gm->where(array("id"=>$group))->field("password")->find();
        if($realPassword['password'] == $password){
            return true;
        }else{
            return false;
        }
    }
    
    public function getGroupView($groupid){
        $gm = new GroupModel();
        $groupView = $gm->getGroupRough($groupid);
        $pm = new ParaModel();
        $groupView['paraone'] = $pm->getFirstPara($groupid)['content'];
        return $groupView;
    }
    
    
    public function getMemberInGroup($groupid){
        $memberIds = M("user_group")->where(array("groupid"=>$groupid))->field("userid")->select();
        $members = array();
        $um = new UserModel();
        foreach ($memberIds as $memberId){
            $members[] = $um->getUserRough($memberId['userid']);
        }
        return $members;
    }
    
    public function sendMes2ActiveUserInGroup($group,$mes){
        $am = new ActiveModel();
        $cids = $am->getGroupActiveUserCidArr($group);
        $gt = new Getui();
        return $gt->pushMes2Users($mes, $cids);
    }
    
    public function getStatus($group){
        $sm = new SilenceModel();
        if($sm->isGroupSilent($group)){
            return 551;//静默
        }else{
            return 550;//就绪
        }
    }
}