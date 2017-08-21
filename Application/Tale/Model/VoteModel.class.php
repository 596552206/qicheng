<?php
namespace Tale\Model;
use Think\Model;
class VoteModel extends Model{
    protected $tableName = "vote";
    
    public function addVoteRecord($user,$para){
        $data = array("user"=>$user,"para"=>$para);
        return $this->data($data)->add();
    }
    
    public function getVoteNum($para){
        $data = array("para"=>$para);
        $resl = $this->where($data)->select();
        $voteNum = count($resl);
        return $voteNum;
    }
    
}