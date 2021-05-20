<?php

namespace API\Service;

/**
 * 用户Service
 *
 * @author Taoys
 */
class TestService extends PSIApiBaseService {
	public function doTest($params) {
        return $this->ok();
    }
}
