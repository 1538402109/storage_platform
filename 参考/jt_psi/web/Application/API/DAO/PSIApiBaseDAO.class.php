<?php
namespace API\DAO;

class PSIApiBaseDAO
{
    protected function bad($msg) {
		return array(
				"success" => false,
				"msg" => $msg
		);
	}
}
