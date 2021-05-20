<?php

namespace UnitTest\Service;

/**
 * 测试用例基类
 *
 * @author JIATU
 */
abstract class BaseTestCase {

	/**
	 * 运行测试用例
	 */
	abstract function run($db);

	protected function toResult($id, $name, $result, $msg) {
		return [
				"id" => $id,
				"name" => $name,
				"result" => $result,
				"msg" => $msg
		];
	}
}