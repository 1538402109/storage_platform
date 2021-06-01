<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Base;
class Warehouses extends Base
{
    use HasFactory;
    protected $table = 't_warehouse' ;

	/**
	 * Í¨¹ý²Ö¿âid²éÑ¯²Ö¿â
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getWarehouseById($id) {
		$sql = "select code, name, data_org, inited from t_warehouse where id = '".$id."' ";
		$data = DB::select($sql);
		
		if (! $data) {
			return null;
		}
		$data = json_decode(json_encode($data),true);
		return array(
				"code" => $data[0]["code"],
				"name" => $data[0]["name"],
				"dataOrg" => $data[0]["data_org"],
				"inited" => $data[0]["inited"]
		);
	}

	public function queryData($queryKey, $fid) {
		$params = array(
				"loginUserId" => $this->getLoginUserId(),
				"queryKey" => $queryKey
		);
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$queryKey = $params["queryKey"];
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$sql = "select id, code, name from t_warehouse
					where (enabled = 1) and (code like ? or name like ? or py like ? ) ";
		$key = "%{$queryKey}%";
		$queryParams = [];
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$rs = $this->buildSQL("1003-01", "t_warehouse", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by code";
		
		$data = DB::select($sql, $queryParams);
		return json_decode(json_encode($data),true);
	}
}
