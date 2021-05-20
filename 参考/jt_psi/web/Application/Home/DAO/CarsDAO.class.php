<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 车辆管理
 */
class CarsDAO extends PSIBaseExDAO {
    /**
     * 新增车辆
     */
    public function addCar($params){
        $db = $this->db;
        $plantNumber=$params["plantNumber"];
        $size=$params["size"];
        $type=$params["type"];
        $memo=$params["memo"];
        $org=$params["org"];
        $sql="select plant_number,size,type,memo from t_cars where plant_number=''";
        $data = $db->query($sql, $plantNumber);
        if($data){
            return $this->bad("此车牌号码已存在");
        }
        
        $id=$this->newId();
        $sql="insert into t_cars (id,plant_number,size,type,create_org,memo,status) values ('%s','%s','%s','%s','%s','%s',1)";
        $rc = $db->execute($sql, $id,$plantNumber, $size, $type, $memo, $org);
        if ($rc === false) {
            return $this->sqlError(__METHOD__, __LINE__);
        }
        return null;
    }

    /**
     * 编辑车辆
     */
    public function editCar($params){
        $db = $this->db;
        $id=$params["id"];
        $plantNumber=$params["plantNumber"];
        $size=$params["size"];
        $type=$params["type"];
        $memo=$params["memo"];
        $org=$params["org"];
        $sql="select plant_number,size,type,memo from ";
        if($id==""){
            $id=$this->newId();
            $sql="insert into t_cars (id,plant_number,size,type,create_org,memo,status) values ('%s','%s','%s','%s','%s','%s',1)";
            $rc = $db->execute($sql, $id,$plantNumber, $size, $type, $memo, $org);
            if ($rc === false) {
                return $this->sqlError(__METHOD__, __LINE__);
            }
        }
        return null;
    }
}