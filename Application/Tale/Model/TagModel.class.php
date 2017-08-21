<?php
namespace Tale\Model;
use Think\Model;
class TagModel extends Model{
    protected $tableName = "tag";
    

    
    public function addTale($tagId,$taleId){
        $oldTags = json_decode($this->where("id=$tagId")->getField("tales"));
        $newTags = $oldTags;
        $newTags[] = (int)$taleId;
        $data['tales'] = json_encode($newTags);
        $this->where("id=$tagId")->save($data);
    }
    
    public function newTag($name,$taleId){
        $data['name'] = $name;
        $data['tales'] = json_encode(array((int)$taleId));
        return $this->data($data)->add();
    }
    
    public function taslateTag($tagId){
        return $this->where(array("id"=>$tagId))->getField("name");
    }
    
    public function taslateTags($tags){
        foreach ($tags as &$v){
            $v = $this->taslateTag($v);
        }
        return $tags;
    }
    
    public function generateTagIdAndNameSet($tags){
        $set = array();
        foreach ($tags as $v){
            $set[] = array(
                "id"=>$v,
                "name"=>$this->taslateTag($v)
            );
        }
        return $set;
    }
    
    public function getAllTagsSet(){
        return $this->field("id,name")->order("priority desc")->select();
    }
    
}