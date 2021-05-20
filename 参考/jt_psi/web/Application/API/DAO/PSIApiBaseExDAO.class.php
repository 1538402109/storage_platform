<?php
namespace API\DAO;
class PSIApiBaseExDAO extends PSIApiBaseDAO
{
    protected $db;

	function __construct($db) {
		$this->db = $db;
	}
    protected function loginUserIdNotExists($loginUserId) {
		$db = $this->db;
		
		$sql = "select count(*) as cnt from t_user where id = '%s' ";
		$data = $db->query($sql, $loginUserId);
		$cnt = $data[0]["cnt"];
		
		return $cnt != 1;
	}
}
