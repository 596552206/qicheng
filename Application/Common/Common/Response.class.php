<?php
namespace Common\Common;
class Response{
    private $status;
    private $detail;
    private $data;
    
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }
    
    public function setDetail($detail){
        $this->detail = $detail;
        return $this;
    }
    
    public function setData($data){
        $this->data = $data;
        return $this;
    }
    
    public function build(){
        $r = array();
        $this->status !== null ? $r['status'] = $this->status : $r['status'] = 400;
        $this->detail !== null ? $r['detail'] = $this->detail : $r['detail'] = null;
        $this->data !== null ? $r['data'] = $this->data : $r['data'] = null;
        return $r;
    }

}