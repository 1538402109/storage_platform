<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\InstallService;
use Home\Service\UserService;
use Home\Service\BizConfigService;
use Home\Service\PersonnelService;

class PersonnelController extends PSIBaseController {
    public function index(){
        $us = new UserService();
        if($us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT)){
            $this->initVar();

            $this->assign('title',"人员管理");

            //判断权限
            $this->assign('pAddPersonnel',$us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT_ADD) ? 1 : 0);
            $this->assign('pEditPersonnel',$us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT_EDIT) ? 1 : 0);
            $this->assign('pDeletePersonnel',$us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT_DELETE) ? 1 : 0);
            $this->assign('pChangePassword',$us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT_CHANGE_PASSWORD) ? 1 : 0);
            //获取主题风格
            $interfaceStyle = $us->userUi();
            $style = $interfaceStyle['interface_style'];
            $this->assign('interfaceStyle',$style);

            $this->display();
        }else{
            $this->gotoLoginPage("Home/User/index");
        }
    }

    /**
     * 人员列表
     */
    public function users(){
        if(IS_POST){
            $us = new PersonnelService();
            $params = [
                'loginName' => I('post.loginName'),
                'name' => I('post.QueryName'),
                "limit" => I("post.limit"),
                "start" => I("post.start")
            ];
            $res = $us->personnels($params);
            $this->ajaxReturn($res);
        }
    }

    /**
     * 添加/修改人员
     */
    public function editPersonnel(){
        if(IS_POST){
            $us = new UserService();

            if(I('post.id')){
                //编辑人员
                if(!$us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT_EDIT)){
                    $this->ajaxReturn($this->noPermission('编辑用户'));
                    return;
                }
            }else{
                //新增人员
                if(!$us->hasPermission(FIdConst::PERSONNEL_MANAGEMENT_ADD)){
                    $this->ajaxReturn($this->noPermission('新增用户'));
                    return;
                }
            }
            $params = array(
                "id" => I("post.id"),
                "loginName" => I("post.loginName"),
                "name" => I("post.name"),
                "orgCode" => strtoupper(I("post.orgCode")),
                "enabled" => I("post.enabled") == "true" ? 1 : 0,
                "gender" => I("post.gender"),
                "birthday" => I("post.birthday"),
                "idCardNumber" => I("post.idCardNumber"),
                "tel" => I("post.tel"),
                "tel02" => I("post.tel02"),
                "address" => I("post.address"),
                'orgId' => I('post.orgId'),
                'dataOrg' => I('post.dataOrg')
        );
            $personnel = new PersonnelService();
            $result = $personnel->editPersonnel($params);
            $this->ajaxReturn($result);
        }
    }

    /**
	 * 获得某个用户的完整信息
	 */
	public function userInfo() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			$us = new PersonnelService();
			$this->ajaxReturn($us->userInfo($params));
		}
	}

    /**
     * 删除人员
     */
    public function deletePersonnel(){
        if (IS_POST) {
			$us = new UserService();
			
			if (! $us->hasPermission(FIdConst::USER_MANAGEMENT_DELETE_USER)) {
				$this->ajaxReturn($this->noPermission("删除用户"));
				return;
			}
			
			$params = array(
					"id" => I("post.id")
			);
			$per = new PersonnelService();
			$result = $per->deleteUser($params);
			
			$this->ajaxReturn($result);
		}
    }

    /**
     * 修改密码
     */
    public function changePassword(){
        if (IS_POST) {
			$us = new UserService();
			
			if (! $us->hasPermission(FIdConst::USER_MANAGEMENT_CHANGE_USER_PASSWORD)) {
				$this->ajaxReturn($this->noPermission("修改用户密码"));
				return;
			}
			
			$params = array(
					"id" => I("post.id"),
					"password" => I("post.password")
			);
			$per = new PersonnelService();
			$result = $per->changePassword($params);
			
			$this->ajaxReturn($result);
		}
    }

    /**
     * 获取人员绑定的商品信息
     */
    public function goodsBindingInfo(){
        if(IS_POST){
            $id = I('post.id');
        }
        $ps = new PersonnelService();
        $this->ajaxReturn($ps->goodsBindingSel($id));
    }

    /**
     * 进行商品绑定
     */
    public function Binding(){
        if(IS_POST){
            $json = I('post.jsonStr');
        }
        $ps = new PersonnelService();
        $this->ajaxReturn($ps->addBinding($json));
    }
}