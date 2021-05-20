<?php

namespace Home\DAO;

/**
 * 码表DAO
 *
 * @author JIATU
 */
class CodeTableDAO extends PSIBaseExDAO {

	/**
	 * 码表分类列表
	 */
	public function categoryList($params) {
		$db = $this->db;
		
		$sql = "select id, code, name
				from t_code_table_category
				order by code";
		$data = $db->query($sql);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	/**
	 * 新增码表分类
	 *
	 * @param array $params        	
	 * @return null|array
	 */
	public function addCodeTableCategory(& $params) {
		$db = $this->db;
		
		$code = $params["code"] ?? "";
		$code = strtoupper($code);
		$name = $params["name"];
		
		// 检查编码是否存在
		if ($code) {
			$sql = "select count(*) as cnt from t_code_table_category where code = '%s' ";
			$data = $db->query($sql, $code);
			$cnt = $data[0]["cnt"];
			if ($cnt) {
				return $this->bad("码表分类编码[{$code}]已经存在");
			}
		} else {
			$code = "";
		}
		
		// 检查分类名称是否存在
		$sql = "select count(*) as cnt from t_code_table_category where name = '%s' ";
		$data = $db->query($sql, $name);
		$cnt = $data[0]["cnt"];
		if ($cnt) {
			return $this->bad("码表分类[{$name}]已经存在");
		}
		
		$id = $this->newId();
		$sql = "insert into t_code_table_category (id, code, name, parent_id)
				values ('%s', '%s', '%s', null)";
		
		$rc = $db->execute($sql, $id, $code, $name);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["id"] = $id;
		return null;
	}

	/**
	 * 编辑码表分类
	 *
	 * @param array $params        	
	 */
	public function updateCodeTableCategory($params) {
		$db = $this->db;
		
		$id = $params["id"];
		$code = $params["code"] ?? "";
		$code = strtoupper($code);
		$name = $params["name"];
		
		$category = $this->getCodeTableCategoryById($id);
		if (! $category) {
			return $this->bad("要编辑的码表分类不存在");
		}
		
		// 检查编码是否存在
		if ($code) {
			$sql = "select count(*) as cnt from t_code_table_category 
					where code = '%s' and id <> '%s' ";
			$data = $db->query($sql, $code, $id);
			$cnt = $data[0]["cnt"];
			if ($cnt) {
				return $this->bad("码表分类编码[{$code}]已经存在");
			}
		} else {
			$code = "";
		}
		
		// 检查分类名称是否存在
		$sql = "select count(*) as cnt from t_code_table_category 
				where name = '%s' and id <> '%s' ";
		$data = $db->query($sql, $name, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt) {
			return $this->bad("码表分类[{$name}]已经存在");
		}
		
		$sql = "update t_code_table_category
				set code = '%s', name = '%s'
				where id = '%s' ";
		
		$rc = $db->execute($sql, $code, $name, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	public function getCodeTableCategoryById($id) {
		$db = $this->db;
		
		$sql = "select code, name from t_code_table_category where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 删除码表分类
	 */
	public function deleteCodeTableCategory(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$category = $this->getCodeTableCategoryById($id);
		if (! $category) {
			return $this->bad("要删除的码表分类不存在");
		}
		$name = $category["name"];
		
		// 查询该分类是否被使用了
		$sql = "select count(*) as cnt from t_code_table_md
				where category_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("码表分类[$name]下还有码表，不能删除");
		}
		
		$sql = "delete from t_code_table_category where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["name"] = $name;
		return null;
	}

	/**
	 * 码表列表
	 */
	public function codeTableList($params) {
		$db = $this->db;
		
		$categoryId = $params["categoryId"];
		
		$sql = "select id, code, name, table_name, memo, fid, md_version, is_fixed
				from t_code_table_md
				where category_id = '%s' 
				order by code, table_name";
		$data = $db->query($sql, $categoryId);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"tableName" => $v["table_name"],
					"fid" => $v["fid"],
					"memo" => $v["memo"],
					"mdVersion" => $v["md_version"],
					"isFixed" => $v["is_fixed"]
			];
		}
		return $result;
	}

	/**
	 * 码表分类自定义字段 - 查询数据
	 */
	public function queryDataForCategory($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"] ?? "";
		
		$sql = "select id, code, name
				from t_code_table_category
				where code like '%s' or name like '%s' ";
		$queryParams = [];
		$queryParams[] = "%{$queryKey}%";
		$queryParams[] = "%{$queryKey}%";
		
		$data = $db->query($sql, $queryParams);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	private function checkTableName($tableName) {
		$tableName = strtolower($tableName);
		
		$len = strlen($tableName);
		if ($len < 5) {
			return $this->bad("数据库表名长度不能小于5");
		}
		
		$c = ord($tableName{0});
		$isABC = ord('a') <= $c && ord('z') >= $c;
		if (! $isABC) {
			return $this->bad("数据库表名需要以字符开头");
		}
		
		for($i = 1; $i < $len; $i ++) {
			$c = ord($tableName{$i});
			$isABC = ord('a') <= $c && ord('z') >= $c;
			$isNumber = ord('0') <= $c && ord('9') >= $c;
			$isOK = $isABC || $isNumber || ord('_') == $c;
			if (! $isOK) {
				$index = $i + 1;
				return $this->bad("数据库表名的第{$index}个字符非法");
			}
		}
		
		// 码表需要以t_ct开头
		if (! (substr($tableName, 0, 4) == "t_ct")) {
			return $this->bad("数据库表名需要以 <span style='color:red'>t_ct</span> 开头");
		}
		
		// 表名正确
		return null;
	}

	/**
	 * 返回码表的系统固有列
	 *
	 * @return array
	 */
	private function getCodeTableSysCols() {
		$result = [];
		
		// id
		$result[] = [
				"caption" => "id",
				"fieldName" => "id",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => - 1000,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// code
		$result[] = [
				"caption" => "编码",
				"fieldName" => "code",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => 0,
				"sysCol" => 1,
				"isVisible" => 1,
				"widthInView" => 120
		];
		
		// name
		$result[] = [
				"caption" => "名称",
				"fieldName" => "name",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => 1,
				"sysCol" => 1,
				"isVisible" => 1,
				"widthInView" => 200
		];
		
		// 拼音字头
		$result[] = [
				"caption" => "拼音字头",
				"fieldName" => "py",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 900,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// 数据域data_org
		$result[] = [
				"caption" => "数据域",
				"fieldName" => "data_org",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 800,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// company_id
		$result[] = [
				"caption" => "公司Id",
				"fieldName" => "company_id",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 700,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// 记录创建时间
		$result[] = [
				"caption" => "记录创建时间",
				"fieldName" => "date_created",
				"fieldType" => "datetime",
				"fieldLength" => 0,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 699,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// 记录创建人id
		$result[] = [
				"caption" => "记录创建人Id",
				"fieldName" => "create_user_id",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 698,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// 记录最后一次编辑时间
		$result[] = [
				"caption" => "最后一次编辑时间",
				"fieldName" => "update_dt",
				"fieldType" => "datetime",
				"fieldLength" => 0,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 697,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// 记录最后一次编辑人id
		$result[] = [
				"caption" => "最后一次编辑人id",
				"fieldName" => "update_user_id",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 696,
				"sysCol" => 1,
				"isVisible" => 2,
				"widthInView" => 0
		];
		
		// 状态
		$result[] = [
				"caption" => "状态",
				"fieldName" => "record_status",
				"fieldType" => "int",
				"fieldLength" => 11,
				"fieldDecimal" => 0,
				"valueFrom" => 2,
				"valueFromTableName" => "t_sysdict_record_status",
				"valueFromColName" => "code_int",
				"mustInput" => 1,
				"showOrder" => 2,
				"sysCol" => 1,
				"isVisible" => 1,
				"widthInView" => 80
		];
		
		return $result;
	}

	/**
	 * 新增码表
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function addCodeTable(&$params) {
		$db = $this->db;
		
		$categoryId = $params["categoryId"];
		if (! $this->getCodeTableCategoryById($categoryId)) {
			return $this->bad("码表分类不存在");
		}
		
		$code = strtoupper($params["code"] ?? "");
		$name = $params["name"];
		$memo = $params["memo"] ?? "";
		$py = $params["py"];
		$tableName = strtolower($params["tableName"]);
		
		// 检查编码是否已经存在
		if ($code) {
			$sql = "select count(*) as cnt from t_code_table_md
					where code = '%s' ";
			$data = $db->query($sql, $code);
			$cnt = $data[0]["cnt"];
			if ($cnt > 0) {
				return $this->bad("编码为[{$code}]的码表已经存在");
			}
		}
		
		// 检查名称是否已经存在
		$sql = "select count(*) as cnt from t_code_table_md
					where name = '%s' ";
		$data = $db->query($sql, $name);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("名称为[{$name}]的码表已经存在");
		}
		
		// 检查表名是否正确
		$rc = $this->checkTableName($tableName);
		if ($rc) {
			return $rc;
		}
		// 检查名表是否已经存在
		$sql = "select count(*) as cnt from t_code_table_md
					where table_name = '%s' ";
		$data = $db->query($sql, $tableName);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("表名为[{$tableName}]的码表已经存在");
		}
		// 检查数据库中是否已经存在该表了
		$dbUtilDAO = new DBUtilDAO($db);
		if ($dbUtilDAO->tableExists($tableName)) {
			return $this->bad("表[{$tableName}]已经在数据库中存在了");
		}
		
		$id = $this->newId();
		$fid = "ct" . date("YmdHis");
		
		$sql = "insert into t_code_table_md (id, category_id, code, name, table_name, py, memo, fid)
				values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
		$rc = $db->execute($sql, $id, $categoryId, $code, $name, $tableName, $py, $memo, $fid);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 码表标准列
		$cols = $this->getCodeTableSysCols();
		foreach ( $cols as $v ) {
			$sql = "insert into t_code_table_cols_md (id, table_id,
						caption, db_field_name, db_field_type, db_field_length,
						db_field_decimal, show_order, value_from, value_from_table_name,
						value_from_col_name, must_input, sys_col, is_visible, width_in_view)
					values ('%s', '%s',
						'%s', '%s', '%s', %d,
						%d, %d, %d, '%s',
						'%s', %d, %d, %d, %d)";
			$rc = $db->execute($sql, $this->newId(), $id, $v["caption"], $v["fieldName"], 
					$v["fieldType"], $v["fieldLength"], $v["fieldDecimal"], $v["showOrder"], 
					$v["valueFrom"], $v["valueFromTableName"], $v["valueFromColName"], 
					$v["mustInput"], $v["sysCol"], $v["isVisible"], $v["widthInView"]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// fid: t_fid_plus
		$sql = "insert into t_fid_plus (fid, name) values ('%s', '%s')";
		$rc = $db->execute($sql, $fid, $name);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 权限: t_permission_plus
		$sql = "insert into t_permission_plus (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s','%s', %d)";
		$rc = $db->execute($sql, $fid, $fid, $name, "模块权限：通过菜单进入{$name}模块的权限", $name, "", 100);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// TODO 权限细化到按钮
		
		// 创建数据库表
		$sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (";
		foreach ( $cols as $v ) {
			$fieldName = $v["fieldName"];
			$fieldType = $v["fieldType"];
			$fieldLength = $v["fieldLength"];
			$fieldDecimal = $v["fieldDecimal"];
			$mustInput = $v["mustInput"];
			
			$type = $fieldType;
			
			if ($fieldType == "varchar") {
				$type .= "({$fieldLength})";
			} else if ($fieldType == "decimal") {
				$type .= "(19, {$fieldDecimal})";
			} else if ($fieldType == "int") {
				$type .= "(11)";
			}
			
			$sql .= "`{$fieldName}` {$type} ";
			if ($mustInput == 1) {
				$sql .= " NOT NULL";
			} else {
				$sql .= " DEFAULT NULL";
			}
			$sql .= ",";
		}
		$sql .= "PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$rc = $db->execute($sql);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["id"] = $id;
		return null;
	}

	/**
	 * 编辑码表主表元数据
	 *
	 * @param array $params        	
	 */
	public function updateCodeTable(& $params) {
		$db = $this->db;
		
		// 码表id
		$id = $params["id"];
		$code = strtoupper($params["code"]) ?? "";
		$name = $params["name"];
		$categoryId = $params["categoryId"];
		$memo = $params["memo"] ?? "";
		
		if (! $this->getCodeTableCategoryById($categoryId)) {
			return $this->bad("码表分类不存在");
		}
		
		$codeTable = $this->getCodeTableById($id);
		if (! $codeTable) {
			return $this->bad("要编辑的码表不存在");
		}
		
		$sql = "update t_code_table_md
				set code = '%s', name = '%s',
					category_id = '%s', memo = '%s',
					md_version = md_version + 1
				where id = '%s' ";
		$rc = $db->execute($sql, $code, $name, $categoryId, $memo, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	private function valueFromCodeToName($valueFrom) {
		switch ($valueFrom) {
			case 1 :
				return "直接录入";
			case 2 :
				return "引用系统数据字典";
			case 3 :
				return "引用其他码表";
			default :
				return "";
		}
	}

	/**
	 * 某个码表的列
	 */
	public function codeTableColsList($params) {
		$db = $this->db;
		
		// 码表id
		$id = $params["id"];
		
		$sql = "select id, caption, db_field_name, db_field_type, db_field_length,
						db_field_decimal, show_order, value_from, value_from_table_name,
						value_from_col_name, must_input, sys_col, is_visible, width_in_view
				from t_code_table_cols_md
				where table_id = '%s' 
				order by show_order";
		$data = $db->query($sql, $id);
		
		$result = [];
		foreach ( $data as $v ) {
			$isVisible = $v["is_visible"] == 1;
			$result[] = [
					"id" => $v["id"],
					"caption" => $v["caption"],
					"fieldName" => $v["db_field_name"],
					"fieldType" => $v["db_field_type"],
					"fieldLength" => $v["db_field_type"] == "datetime" ? null : $v["db_field_length"],
					"fieldDecimal" => $v["db_field_type"] == "decimal" ? $v["db_field_decimal"] : null,
					"showOrder" => $v["show_order"],
					"valueFrom" => $this->valueFromCodeToName($v["value_from"]),
					"valueFromTableName" => $v["value_from_table_name"],
					"valueFromColName" => $v["value_from_col_name"],
					"mustInput" => $v["must_input"] == 1 ? "必录项" : "",
					"sysCol" => $v["sys_col"] == 1 ? "系统列" : "",
					"isVisible" => $isVisible ? "可见" : "不可见",
					"widthInView" => $isVisible ? ($v["width_in_view"] ?? 100) : null
			];
		}
		
		return $result;
	}

	public function getCodeTableById($id) {
		$db = $this->db;
		
		$sql = "select code, name, fid, is_fixed from t_code_table_md where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"],
					"fid" => $data[0]["fid"],
					"isFixed" => $data[0]["is_fixed"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 删除码表
	 */
	public function deleteCodeTable(& $params) {
		$db = $this->db;
		
		// 码表id
		$id = $params["id"];
		
		$codeTable = $this->getCodeTableById($id);
		if (! $codeTable) {
			return $this->bad("要删除的码表不存在");
		}
		$name = $codeTable["name"];
		$fid = $codeTable["fid"];
		$isFixed = $codeTable["isFixed"];
		if ($isFixed == 1) {
			return $this->bad("码表[$name]是系统固有码表，不能删除");
		}
		
		// 列
		$sql = "delete from t_code_table_cols_md where table_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 主表
		$sql = "delete from t_code_table_md where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// fid
		$sql = "delete from t_fid_plus where fid = '%s' ";
		$rc = $db->execute($sql, $fid);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 权限
		// 用like是为了处理按钮权限
		$sql = "delete from t_permission_plus where fid like '%s' ";
		$rc = $db->execute($sql, "{$fid}%");
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["name"] = $name;
		return null;
	}

	/**
	 * 查询码表主表元数据
	 */
	public function codeTableInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$sql = "select c.name as category_name, d.code, d.name,
					d.table_name, d.category_id, d.memo
				from t_code_table_md d, t_code_table_category c
				where d.id = '%s' and d.category_id = c.id ";
		$data = $db->query($sql, $id);
		if ($data) {
			$v = $data[0];
			return [
					"code" => $v["code"],
					"name" => $v["name"],
					"tableName" => $v["table_name"],
					"categoryId" => $v["category_id"],
					"categoryName" => $v["category_name"],
					"memo" => $v["memo"]
			];
		} else {
			return $this->emptyResult();
		}
	}

	/**
	 * 根据fid获得码表的元数据
	 *
	 * @param string $fid        	
	 * @return array|NULL
	 */
	public function getMetaDataByFid($fid) {
		$db = $this->db;
		
		$sql = "select caption from t_menu_item_plus where fid = '%s' ";
		$data = $db->query($sql, $fid);
		if ($data) {
			$result = [];
			$result["title"] = $data[0]["caption"];
			return $result;
		} else {
			return null;
		}
	}

	/**
	 * 查询码表元数据 - 运行界面用
	 */
	public function getMetaDataForRuntime($params) {
		$db = $this->db;
		
		$fid = $params["fid"];
		
		$sql = "select id, name, table_name
				from t_code_table_md 
				where fid = '%s' ";
		$data = $db->query($sql, $fid);
		if (! $data) {
			return null;
		}
		$v = $data[0];
		
		$id = $v["id"];
		
		$result = [
				"fid" => $fid,
				"tableName" => $v["table_name"],
				"name" => $v["name"]
		];
		
		// 列
		$sql = "select caption, 
					db_field_name, db_field_type, db_field_length, db_field_decimal,
					sys_col, is_visible, width_in_view, must_input, value_from,
					value_from_table_name, value_from_col_name
				from t_code_table_cols_md
				where table_id = '%s' 
				order by show_order";
		$data = $db->query($sql, $id);
		$cols = [];
		foreach ( $data as $v ) {
			$isVisible = $v["is_visible"] == 1;
			$valueFrom = $v["value_from"];
			$valueFromTableName = $v["value_from_table_name"];
			$valueFromColName = $v["value_from_col_name"];
			$valueFromExtData = [];
			if ($valueFrom == 2) {
				// 引用系统数据字典
				$sql = "select %s as col_1, name
						from %s
						order by %s";
				$d = $db->query($sql, $valueFromColName, $valueFromTableName, $valueFromColName);
				foreach ( $d as $item ) {
					$valueFromExtData[] = [
							"{$valueFromColName}" => $item["col_1"],
							"name" => $item["name"]
					];
				}
			}
			
			$cols[] = [
					"caption" => $v["caption"],
					"fieldName" => $v["db_field_name"],
					"isVisible" => $isVisible,
					"widthInView" => $isVisible ? ($v["width_in_view"] ?? 100) : null,
					"mustInput" => $v["must_input"] == 1,
					"valueFromExtData" => $valueFromExtData,
					"valueFrom" => $v["value_from"],
					"valueFromColName" => $v["value_from_col_name"],
					"isSysCol" => $v["sys_col"] == 1
			];
		}
		$result["cols"] = $cols;
		
		return $result;
	}

	/**
	 * 新增码表记录
	 *
	 * @param array $params        	
	 * @return array|NULL
	 */
	public function addRecord(&$params, $pyService) {
		$db = $this->db;
		
		$dataOrg = $params["dataOrg"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$fid = $params["fid"];
		$md = $this->getMetaDataForRuntime([
				"fid" => $fid
		]);
		
		if (! $md) {
			return $this->badParam("fid");
		}
		
		$codeTableName = $md["name"];
		
		$code = $params["code"];
		$name = $params["name"];
		$recordStatus = $params["record_status"];
		
		$id = $this->newId();
		
		$tableName = $md["tableName"];
		
		// 检查编码是否重复
		$sql = "select count(*) as cnt from %s where code = '%s' ";
		$queryParams = [];
		$queryParams[] = $tableName;
		$queryParams[] = $code;
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为[$code]的记录已经存在");
		}
		
		$sql = "insert into %s (id, py, data_org, company_id, 
					date_created, create_user_id, code, name, 
					record_status";
		$sqlParams = [];
		$sqlParams[] = $tableName;
		
		foreach ( $md["cols"] as $colMd ) {
			if ($colMd["isSysCol"]) {
				continue;
			}
			
			// 非系统字段
			$fieldName = $colMd["fieldName"];
			
			$sql .= ", %s";
			$sqlParams[] = $fieldName;
		}
		$sql .= ") values ('%s', '%s', '%s', '%s', 
						now(), '%s', '%s', '%s', 
						%d";
		$sqlParams[] = $id;
		$sqlParams[] = $pyService->toPY($name);
		$sqlParams[] = $dataOrg;
		$sqlParams[] = $companyId;
		$sqlParams[] = $loginUserId;
		$sqlParams[] = $code;
		$sqlParams[] = $name;
		$sqlParams[] = $recordStatus;
		
		foreach ( $md["cols"] as $colMd ) {
			if ($colMd["isSysCol"]) {
				continue;
			}
			
			// 非系统字段
			$fieldName = $colMd["fieldName"];
			$fieldType = $colMd["fieldType"];
			if ($fieldType == "int") {
				$sql .= ", %d";
			} else if ($fieldType == "decimal") {
				$sql .= ", %f";
			} else {
				$sql .= ", '%s'";
			}
			$sqlParams[] = $params[$fieldName];
		}
		$sql .= ")";
		
		$rc = $db->execute($sql, $sqlParams);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$code = $params["code"];
		$name = $params["name"];
		$params["id"] = $id;
		$params["log"] = "新增{$codeTableName}记录:{$code}-{$name}";
		$params["logCategory"] = $codeTableName;
		return null;
	}

	/**
	 * 码表记录列表
	 */
	public function codeTableRecordList($params) {
		$db = $this->db;
		
		$fid = $params["fid"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$md = $this->getMetaDataForRuntime($params);
		if (! $md) {
			return $this->emptyResult();
		}
		
		$tableName = $md["tableName"];
		
		$sql .= "select cr.id, cr.code, cr.name, u.name as create_user_name, r.name as record_status";
		
		foreach ( $md["cols"] as $colMd ) {
			if ($colMd["isSysCol"]) {
				continue;
			}
			
			if ($colMd["isVisible"]) {
				$sql .= ", cr." . $colMd["fieldName"];
			}
		}
		
		$sql .= " from %s cr, t_user u, t_sysdict_record_status r ";
		$queryParams[] = $tableName;
		
		$sql .= " where (cr.create_user_id = u.id) and (cr.record_status = r.code_int)";
		// 数据域
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL($fid, "cr", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by code";
		
		$data = $db->query($sql, $queryParams);
		
		return $data;
	}
}