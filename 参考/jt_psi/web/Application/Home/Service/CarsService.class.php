<?php

namespace Home\Service;
use Home\Common\FIdConst;
use Home\DAO\CarsDAO;

class CarsService extends PSIBaseExService{
    /**
     * 新增或编辑车辆
     */
    public function editCar($params){
        if ($this->isNotOnline()) {
			return $this->emptyResult();
        }
        $params["org"]=$this->getLoginUserDataOrg();
        $dao = new CarsDAO($this->db());
        $db = $this->db();
		$db->startTrans();
        if($params["id"]==""){
            $rc = $dao->addCar($params);
            if ($rc) {
				$db->rollback();
				return $rc;
			}
        }
        else {
            $rc = $dao->editCar($params);
            if ($rc) {
				$db->rollback();
				return $rc;
			}
        }
        // 记录业务日志
		$bs = new BizlogService($db);
        $bs->insertBizlog($log, $this->LOG_CATEGORY_UNIT);
        
        $db->commit();
		return $this->ok($id);
    }
}