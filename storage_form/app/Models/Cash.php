<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use App\Models\Warehouses;
use Illuminate\Support\Facades\DB;
class Cash extends Base
{
	use HasFactory;

	/**
	 * 按日期现金收支列表
	 */
	public function cashList($params) {
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$dtFrom = $params["dtFrom"];
		$dtTo = $params["dtTo"];
		
		$us = new User();
		$companyId = $us->getCompanyId();
		$userDataOrg = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLoginUserId()]);

		$loginUserId = $this->getLoginUserId();
		$rsStr = $this->buildSQLStr2("2024", "s", $loginUserId);
	
		$result = array();
		$sql = "select biz_date, in_money, out_money, balance_money
				from t_cash s
				where biz_date >= ? and biz_date <= ?
					and company_id = ? and  ".$rsStr."
				order by biz_date
				limit ?, ? ";
		$data = DB::select($sql, [$dtFrom, $dtTo, $companyId, $start, $limit]);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $i => $v ) {
			$result[$i]["bizDT"] = $this->toYMD($v["biz_date"]);
			$result[$i]["inMoney"] = $v["in_money"];
			$result[$i]["outMoney"] = $v["out_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
		}
		
		$sql = "select count(*) as cnt
				from t_cash s
				where biz_date >= ? and biz_date <= ? 
					and company_id = ? and ".$rsStr." ";
		$data = DB::select($sql, [$dtFrom, $dtTo, $companyId]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 某天的现金收支列表
	 */
	public function cashDetailList($params) {
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$bizDT = $params["bizDT"];
		
		$us = new User();
		$companyId = $us->getCompanyId();
		$userDataOrg = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLoginUserId()]);
		$loginUserId = $this->getLoginUserId();
		$rsStr = $this->buildSQLStr2("2024", "s", $loginUserId);
	
		$result = array();
		$sql = "select biz_date, in_money, out_money, balance_money, date_created,
					ref_type, ref_number
				from t_cash_detail s
				where biz_date = ? and company_id = ? and ".$rsStr."
				order by date_created
				limit ?, ? ";
		$data = DB::select($sql, [$bizDT, $companyId, $start, $limit]);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $i => $v ) {
			$result[$i]["bizDT"] = $this->toYMD($v["biz_date"]);
			$result[$i]["inMoney"] = $v["in_money"];
			$result[$i]["outMoney"] = $v["out_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["refType"] = $v["ref_type"];
			$result[$i]["refNumber"] = $v["ref_number"];
		}
		
		$sql = "select count(*) as cnt
				from t_cash_detail s
				where biz_date = ? and company_id = ? and ".$rsStr." ";
		$data = DB::select($sql, [$bizDT, $companyId]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}
}