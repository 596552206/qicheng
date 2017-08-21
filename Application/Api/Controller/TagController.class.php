<?php
namespace Api\Controller;
use Think\Controller;
use Tale\Model\TagModel;
use Common\Common\Response;
class TagController extends Controller{
    public function getTags(){
        $tagM = new TagModel();
        
        $resl = $tagM->getAllTagsSet();
        
        $response = new Response();
        if($resl === false){
            $this->ajaxReturn($response->setStatus(400)->setDetail("错误")->build());
        }else{
            $this->ajaxReturn($response->setStatus(200)->setDetail("OK")->setData($resl)->build());
        }
    }
}