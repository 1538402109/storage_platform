<?php
    namespace Home\Controller;

    use Home\Service\UserService;
    use think\Controller;

    class PrintingController extends Controller{
        //修改数据
        public function printInfo(){
            if(IS_POST){
                $us = new UserService();
                $this->ajaxReturn($us->printInfo());
            }
        }

        //编辑打印地址
        public  function printEdit(){
            if(IS_POST){
                $print = new UserService();
                $printUrl = I("post.printUrl");
                $id = I("post.id");
                $result = $print->editPrintUrl($id,$printUrl);
                // dump($result);die;
                $this->ajaxReturn($result);
            }
        }
    }