<?php
namespace Home\Service;

require __DIR__ . '/../Common/Pinyin/pinyin.php';

/**
 * 拼音Service
 *
 * @author JIATU
 */
class PinyinService {
	public function toPY($s) {
		return strtoupper(pinyin($s, "first", ""));
	}
}
