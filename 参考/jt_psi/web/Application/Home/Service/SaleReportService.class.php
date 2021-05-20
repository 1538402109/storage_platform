<?php

namespace Home\Service;

use Home\DAO\SaleReportDAO;
use Home\DAO\DataOrgDAO;
use Home\Common\FIdConst;
use Home\Common\DateHelper;
/**
 * 销售报表Service
 *
 * @author JIATU
 */
class SaleReportService extends PSIBaseExService {
	private $LOG_CATEGORY = "销售报表";

	/**
	 * 销售日报表(按商品汇总) - 查询数据
	 */
	public function saleDayByGoodsQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByGoodsQueryData($params);
	}

	/**
	 * 销售日报表(按商品汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleDayByGoodsDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleDayByGoodsQueryData($params);
		
		$data = $this->saleDaySummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $params["dt"],
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售日报表(按商品汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByGoodsPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByGoodsQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按商品汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售日报表(按商品汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售日报表(按商品汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>业务日期：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>商品编号</td><td>商品名称</td><td>规格型号</td><td>销售出库数量</td><td>单位</td>
						<td>销售出库金额</td><td>退货入库数量</td><td>退货入库金额</td><td>净销售数量</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["goodsCode"] . '</td>';
			$html .= '<td>' . $v["goodsName"] . '</td>';
			$html .= '<td>' . $v["goodsSpec"] . '</td>';
			$html .= '<td align="right">' . $v["saleCount"] . '</td>';
			$html .= '<td>' . $v["unitName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejCount"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["c"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		

		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SDG_{$dt}.pdf", "I");
	}

	/**
	 * 销售日报表(按商品汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByGoodsExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByGoodsQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按商品汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售日报表(按商品汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "业务日期: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "商品编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "商品名称");
		
		$sheet->getColumnDimension('C')->setWidth(40);
		$sheet->setCellValue("C2", "规格型号");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "销售出库数量");
		
		$sheet->getColumnDimension('E')->setWidth(10);
		$sheet->setCellValue("E2", "单位");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "销售出库金额");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "退货入库数量");
		
		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->setCellValue("H2", "退货入库金额");
		
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->setCellValue("I2", "净销售数量");
		
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->setCellValue("J2", "净销售金额");
		
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->setCellValue("K2", "毛利");
		
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->setCellValue("L2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["goodsCode"]);
			$sheet->setCellValue("B" . $row, $v["goodsName"]);
			$sheet->setCellValue("C" . $row, $v["goodsSpec"]);
			$sheet->setCellValue("D" . $row, $v["saleCount"]);
			$sheet->setCellValue("E" . $row, $v["unitName"]);
			$sheet->setCellValue("F" . $row, $v["saleMoney"]);
			$sheet->setCellValue("G" . $row, $v["rejCount"]);
			$sheet->setCellValue("H" . $row, $v["rejMoney"]);
			$sheet->setCellValue("I" . $row, $v["c"]);
			$sheet->setCellValue("J" . $row, $v["m"]);
			$sheet->setCellValue("K" . $row, $v["profit"]);
			$sheet->setCellValue("L" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:L' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售日报表(按商品汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');

		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	private function saleDaySummaryQueryData($params) {
		$dt = $params["dt"];
		
		$result = array();
		$result[0]["bizDT"] = $dt;
		
		$us = new UserService();
		$companyId = $us->getCompanyId();
		$loginUserId =$us->getLoginUserId();
		$db = M();

		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_DAY_BY_WAREHOUSE, "d", $loginUserId);
	
		$sql = "select sum(d.goods_money) as goods_money, sum(d.inventory_money) as inventory_money
					from t_ws_bill w, t_ws_bill_detail d
					where w.id = d.wsbill_id and w.bizdt = '%s'
						and w.bill_status >= 1000 and w.company_id = '%s' and ".$rsStr." ";
		$data = $db->query($sql, $dt, $companyId);
		$saleMoney = $data[0]["goods_money"];
		if (! $saleMoney) {
			$saleMoney = 0;
		}
		$saleInventoryMoney = $data[0]["inventory_money"];
		if (! $saleInventoryMoney) {
			$saleInventoryMoney = 0;
		}
		$result[0]["saleMoney"] = $saleMoney;
		
		$sql = "select  sum(d.rejection_sale_money) as rej_money,
						sum(d.inventory_money) as rej_inventory_money
					from t_sr_bill s, t_sr_bill_detail d
					where s.id = d.srbill_id and s.bizdt = '%s'
						and s.bill_status = 1000 and s.company_id = '%s'  and ".$rsStr." ";
		$data = $db->query($sql, $dt, $companyId);
		$rejSaleMoney = $data[0]["rej_money"];
		if (! $rejSaleMoney) {
			$rejSaleMoney = 0;
		}
		$rejInventoryMoney = $data[0]["rej_inventory_money"];
		if (! $rejInventoryMoney) {
			$rejInventoryMoney = 0;
		}
		
		$result[0]["rejMoney"] = $rejSaleMoney;
		
		$m = $saleMoney - $rejSaleMoney;
		$result[0]["m"] = $m;
		$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
		$result[0]["profit"] = $profit;
		if ($m > 0) {
			$result[0]["rate"] = sprintf("%0.2f", $profit / $m * 100) . "%";
		}
		
		return $result;
	}

	/**
	 * 销售日报表(按商品汇总) - 查询汇总数据
	 */
	public function saleDayByGoodsSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 查询数据
	 */
	public function saleDayByCustomerQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByCustomerQueryData($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 查询汇总数据
	 */
	public function saleDayByCustomerSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleDayByCustomerDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleDayByCustomerQueryData($params);
		
		$data = $this->saleDaySummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $params["dt"],
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售日报表(按客户汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByCustomerPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByCustomerQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按客户汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售日报表(按客户汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售日报表(按客户汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>业务日期：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>客户编号</td><td>客户名称</td>
						<td>销售出库金额</td><td>退货入库金额</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["customerCode"] . '</td>';
			$html .= '<td>' . $v["customerName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
		
		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SDC_{$dt}.pdf", "I");
	}

	/**
	 * 销售日报表(按客户汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByCustomerExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByCustomerQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按客户汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售日报表(按客户汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "业务日期: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "客户编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "客户名称");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["customerCode"]);
			$sheet->setCellValue("B" . $row, $v["customerName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:G' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售日报表(按客户汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');

		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询数据
	 */
	public function saleDayByWarehouseQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByWarehouseQueryData($params);
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询汇总数据
	 */
	public function saleDayByWarehouseSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleDayByWarehouseDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleDayByWarehouseQueryData($params);
		
		$data = $this->saleDaySummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $params["dt"],
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售日报表(按仓库汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByWarehousePdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByWarehouseQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按仓库汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售日报表(按仓库汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售日报表(按仓库汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>业务日期：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>仓库编码</td><td>仓库</td>
						<td>销售出库金额</td><td>退货入库金额</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["warehouseCode"] . '</td>';
			$html .= '<td>' . $v["warehouseName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
			
		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SDW_{$dt}.pdf", "I");
	}

	/**
	 * 销售日报表(按仓库汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByWarehouseExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByWarehouseQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按仓库汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售日报表(按仓库汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "业务日期: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "仓库编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "仓库");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["warehouseCode"]);
			$sheet->setCellValue("B" . $row, $v["warehouseName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:G' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售日报表(按仓库汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询数据
	 */
	public function saleDayByBizuserQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByBizuserQueryData($params);
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询汇总数据
	 */
	public function saleDayByBizuserSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleDayByBizuserDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleDayByBizuserQueryData($params);
		
		$data = $this->saleDaySummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $params["dt"],
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售日报表(按业务员汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByBizuserPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByBizuserQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按业务员汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售日报表(按业务员汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售日报表(按业务员汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>业务日期：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>业务员编码</td><td>业务员</td>
						<td>销售出库金额</td><td>退货入库金额</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["userCode"] . '</td>';
			$html .= '<td>' . $v["userName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SDU_{$dt}.pdf", "I");
	}

	/**
	 * 销售日报表(按业务员汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByBizuserExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByBizuserQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按业务员汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售日报表(按业务员汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "业务日期: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "业务员编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "业务员");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["userCode"]);
			$sheet->setCellValue("B" . $row, $v["userName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:G' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");

		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售日报表(按业务员汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售月报表(按商品汇总) - 查询数据
	 */
	public function saleMonthByGoodsQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByGoodsQueryData($params);
	}

	private function saleMonthSummaryQueryData($params) {
		$year = $params["year"];
		$month = $params["month"];
		
		$result = array();
		if ($month < 10) {
			$result[0]["bizDT"] = "$year-0$month";
		} else {
			$result[0]["bizDT"] = "$year-$month";
		}
		
		$us = new UserService();
		$companyId = $us->getCompanyId();
		
		$db = M();
		$userId =  $params["userId"];
		$userSql = " and 1=1 ";
		$userSql2 = " and 1=1 ";
		if($userId){
			$userSql =" and  w.biz_user_id = '" .$userId."' ";
			$userSql2 =" and s.biz_user_id = '" .$userId."' ";
		}
		$loginUserId =$us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_GOODS, "w", $loginUserId);
	
		$sql = "select sum(d.goods_money) as goods_money, sum(d.inventory_money) as inventory_money
					from t_ws_bill w, t_ws_bill_detail d
					where w.id = d.wsbill_id and year(w.bizdt) = %d and month(w.bizdt) = %d ".$userSql."
						and w.bill_status >= 1000 and w.company_id = '%s' and ".$rsStr."  ";
		$data = $db->query($sql, $year, $month, $companyId);
		$saleMoney = $data[0]["goods_money"];
		if (! $saleMoney) {
			$saleMoney = 0;
		}
		$saleInventoryMoney = $data[0]["inventory_money"];
		if (! $saleInventoryMoney) {
			$saleInventoryMoney = 0;
		}
		$result[0]["saleMoney"] = $saleMoney;
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_GOODS, "s", $loginUserId);
	
		$sql = "select  sum(d.rejection_sale_money) as rej_money,
						sum(d.inventory_money) as rej_inventory_money
					from t_sr_bill s, t_sr_bill_detail d
					where s.id = d.srbill_id and year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ".$userSql2." ";
		$data = $db->query($sql, $year, $month, $companyId);
		$rejSaleMoney = $data[0]["rej_money"];
		if (! $rejSaleMoney) {
			$rejSaleMoney = 0;
		}
		$rejInventoryMoney = $data[0]["rej_inventory_money"];
		if (! $rejInventoryMoney) {
			$rejInventoryMoney = 0;
		}
		
		$result[0]["rejMoney"] = $rejSaleMoney;
		
		$m = $saleMoney - $rejSaleMoney;
		$result[0]["m"] = $m;
		$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
		$result[0]["profit"] = $profit;
		if ($m > 0) {
			$result[0]["rate"] = sprintf("%0.2f", $profit / $m * 100) . "%";
		}
		
		return $result;
	}

	/**
	 * 销售月报表(按商品汇总) - 查询汇总数据
	 */
	public function saleMonthByGoodsSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}

	/**
	 * 销售月报表(按商品汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleMonthByGoodsDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleMonthByGoodsQueryData($params);
		
		$data = $this->saleMonthSummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $bizDT,
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"],
				"userName"=>$params["userName"]
		];
	}

	/**
	 * 销售月报表(按商品汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByGoodsPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		$userName = $params["userName"];
		
		$userTitle ="";
		if($userName){
			$userTitle="业务员-".$userName." ";
		}
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByGoodsQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按商品汇总)导出PDF文件".$userTitle;
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售月报表(按商品汇总)".$userTitle);
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售月报表(按商品汇总)".$userTitle);
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>月份：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td>'.$userTitle.'</td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>商品编号</td><td>商品名称</td><td>规格型号</td><td>销售出库数量</td><td>单位</td>
						<td>销售出库金额</td><td>退货入库数量</td><td>退货入库金额</td><td>净销售数量</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["goodsCode"] . '</td>';
			$html .= '<td>' . $v["goodsName"] . '</td>';
			$html .= '<td>' . $v["goodsSpec"] . '</td>';
			$html .= '<td align="right">' . $v["saleCount"] . '</td>';
			$html .= '<td>' . $v["unitName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejCount"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["c"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
	
		
		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SMG_{$dt}.pdf", "I");
	}

	/**
	 * 销售月报表(按商品汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByGoodsExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByGoodsQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];

		$userName = $params["userName"];
		
		$userTitle ="";
		if($userName){
			$userTitle="业务员-".$userName." ";
		}
		// 记录业务日志
		$log = "销售月报表(按商品汇总)导出Excel文件".	$userTitle;
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售月报表(按商品汇总)".$userTitle);
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = $userTitle." 月份: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "商品编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "商品名称");
		
		$sheet->getColumnDimension('C')->setWidth(40);
		$sheet->setCellValue("C2", "规格型号");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "销售出库数量");
		
		$sheet->getColumnDimension('E')->setWidth(10);
		$sheet->setCellValue("E2", "单位");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "销售出库金额");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "退货入库数量");
		
		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->setCellValue("H2", "退货入库金额");
		
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->setCellValue("I2", "净销售数量");
		
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->setCellValue("J2", "净销售金额");
		
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->setCellValue("K2", "毛利");
		
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->setCellValue("L2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["goodsCode"]);
			$sheet->setCellValue("B" . $row, $v["goodsName"]);
			$sheet->setCellValue("C" . $row, $v["goodsSpec"]);
			$sheet->setCellValue("D" . $row, $v["saleCount"]);
			$sheet->setCellValue("E" . $row, $v["unitName"]);
			$sheet->setCellValue("F" . $row, $v["saleMoney"]);
			$sheet->setCellValue("G" . $row, $v["rejCount"]);
			$sheet->setCellValue("H" . $row, $v["rejMoney"]);
			$sheet->setCellValue("I" . $row, $v["c"]);
			$sheet->setCellValue("J" . $row, $v["m"]);
			$sheet->setCellValue("K" . $row, $v["profit"]);
			$sheet->setCellValue("L" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:L' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		$file_name = "销售月报表(按商品汇总)_". $dt.".xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售月报表(按客户汇总) - 查询数据
	 */
	public function saleMonthByCustomerQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByCustomerQueryData($params);
	}

	/**
	 * 销售月报表(按客户汇总) - 查询汇总数据
	 */
	public function saleMonthByCustomerSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}

	/**
	 * 销售月报表(按客户汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleMonthByCustomerDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleMonthByCustomerQueryData($params);
		
		$data = $this->saleMonthSummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $bizDT,
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售月报表(按客户汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByCustomerPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByCustomerQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按客户汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售月报表(按客户汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售月报表(按客户汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>月份：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>客户编号</td><td>客户名称</td>
						<td>销售出库金额</td><td>退货入库金额</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["customerCode"] . '</td>';
			$html .= '<td>' . $v["customerName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');

		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SMC_{$dt}.pdf", "I");
	}

	/**
	 * 销售月报表(按客户汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByCustomerExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByCustomerQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按客户汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售月报表(按客户汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "月份: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "客户编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "客户名称");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["customerCode"]);
			$sheet->setCellValue("B" . $row, $v["customerName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:G' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		

		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		$file_name = "销售月报表(按客户汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询数据
	 */
	public function saleMonthByWarehouseQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByWarehouseQueryData($params);
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询汇总数据
	 */
	public function saleMonthByWarehouseSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleMonthByWarehouseDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleMonthByWarehouseQueryData($params);
		
		$data = $this->saleMonthSummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $bizDT,
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售月报表(按仓库汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByWarehousePdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByWarehouseQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按仓库汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售月报表(按仓库汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售月报表(按仓库汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>月份：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>仓库编码</td><td>仓库</td>
						<td>销售出库金额</td><td>退货入库金额</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["warehouseCode"] . '</td>';
			$html .= '<td>' . $v["warehouseName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');

		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SMW_{$dt}.pdf", "I");
	}

	/**
	 * 销售月报表(按仓库汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByWarehouseExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByWarehouseQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按仓库汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售月报表(按仓库汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "月份: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "仓库编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "仓库");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["warehouseCode"]);
			$sheet->setCellValue("B" . $row, $v["warehouseName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:G' . $lastRow)->applyFromArray($styleArray);
	
		$dt = date("YmdHis");

		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');

		$file_name = "销售月报表(按仓库汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售月报表(按业务员汇总) - 查询数据
	 */
	public function saleMonthByBizuserQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByBizuserQueryData($params);
	}

	/**
	 * 销售月报表(按业务员汇总) - 查询汇总数据
	 */
	public function saleMonthByBizuserSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}
	/**
	 * 销售详情 - 查询数据
	 */
	public function saleAllDetailQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleAllDetailQueryData($params);
		
	}
		/**
	 * 销售详情 - 查询汇总数据
	 */
	public function saleAllDetailQuerySumData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleAllDetailQuerySumData($params);
		
	}
	/**
	 * 销售月报表(按业务员汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleMonthByBizuserDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleMonthByBizuserQueryData($params);
		
		$data = $this->saleMonthSummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $bizDT,
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByBizuserPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByBizuserQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按业务员汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售月报表(按业务员汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售月报表(按业务员汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>月份：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>业务员编码</td><td>业务员</td>
						<td>销售出库金额</td><td>退货入库金额</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td><td>二批利润</td><td>去二批利润</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["userCode"] . '</td>';
			$html .= '<td>' . $v["userName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '<td align="right">' . $v["lev2Money"] . '</td>';
			$html .= '<td align="right">' . $v["exLev2Money"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		

		$dt = date("YmdHis");
		ob_end_clean();
		ob_clean();
		$pdf->Output("SMU_{$dt}.pdf", "I");
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthByBizuserExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		
		$bizDT = "";
		if ($month < 10) {
			$bizDT = "$year-0$month";
		} else {
			$bizDT = "$year-$month";
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthByBizuserQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleMonthSummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表(按业务员汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售月报表(按业务员汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "月份: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "业务员编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "业务员");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");

		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->setCellValue("H2", "二批利润");

		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->setCellValue("I2", "去二批利润");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["userCode"]);
			$sheet->setCellValue("B" . $row, $v["userName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
			$sheet->setCellValue("H" . $row, $v["lev2Money"]);
			$sheet->setCellValue("I" . $row, $v["exLev2Money"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:I' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售月报表(按业务员汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleMonthDetailByBizuserExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		$bizDT =$params["bizDT"];
		// $bizDT = "";
		// if ($month < 10) {
		// 	$bizDT = "$year-0$month";
		// } else {
		// 	$bizDT = "$year-$month";
		// }
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleMonthDetailByBizuserQueryData($params);
		$items = $data["dataList"];
		
		// $data = $this->saleMonthSummaryQueryData($params);
		// $summary = $data[0];
		
		// 记录业务日志
		$log = "销售月报表详情(按业务员汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		

		// foreach ( $items as $i => $v ) {
		// 	$row = $i + 3;
		// 	$sheet->setCellValue("A" . $row, $v["bizDT"]);
		// 	$sheet->setCellValue("B" . $row, $v["userName"]);
		// 	$sheet->setCellValue("C" . $row, $v["saleMoney"]);
		// 	$sheet->setCellValue("D" . $row, $v["rejMoney"]);
		// 	$sheet->setCellValue("E" . $row, $v["m"]);
		// 	$sheet->setCellValue("F" . $row, $v["profit"]);
		// 	$sheet->setCellValue("G" . $row, $v["rate"]);
		// 	$sheet->setCellValue("H" . $row, $v["lev2Money"]);
		// 	$sheet->setCellValue("I" . $row, $v["exLev2Money"]);
		// }
		$summarySaleMoney = array_sum(array_column($items, 'saleMoney'));
		$summaryrejMoney = array_sum(array_column($items, 'rejMoney'));
		$summarylev2Money = array_sum(array_column($items, 'lev2Money'));
		$summaryexLev2Money = array_sum(array_column($items, 'exLev2Money'));
		$sheet->setTitle("销售月报表(按业务员汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "月份: " . $bizDT . " 销售出库金额: " . number_format($summarySaleMoney,2) . " 退货入库金额: " . number_format($summaryrejMoney,2) . " 二批利润: " . number_format($summarylev2Money,2) . " 去二批利润: " . number_format($summaryexLev2Money,2);
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "时间");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "业务员");
		
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->setCellValue("C2", "销售出库金额");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "退货入库金额");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "净销售金额");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "毛利");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "毛利率");

		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->setCellValue("H2", "二批利润");

		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->setCellValue("I2", "去二批利润");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["bizDT"]);
			$sheet->setCellValue("B" . $row, $v["userName"]);
			$sheet->setCellValue("C" . $row, $v["saleMoney"]);
			$sheet->setCellValue("D" . $row, $v["rejMoney"]);
			$sheet->setCellValue("E" . $row, $v["m"]);
			$sheet->setCellValue("F" . $row, $v["profit"]);
			$sheet->setCellValue("G" . $row, $v["rate"]);
			$sheet->setCellValue("H" . $row, $v["lev2Money"]);
			$sheet->setCellValue("I" . $row, $v["exLev2Money"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:I' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售月报表(按业务员汇总)_' . $dt . '.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleAllDetailrExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$year = $params["year"];
		$month = $params["month"];
		$bizDT =$params["bizDT"];

		if($bizDT)
		{
			$helper = new DateHelper();
			$items = $helper->get_day($this->toYMD($bizDT) ,2);
			$params["startDate"]=$items[0]; 
			$params["endDate"]=$items[count($items)-1]; 
		}
	
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleAllDetailQueryData($params);
		$items = $data["dataList"];
		
		// 记录业务日志
		$log = "销售明细表详情导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sumData = $dao->saleAllDetailQuerySumData($params);
	
	//	$summarySaleMoney = array_sum(array_column($items, 'goodsMoney'));

		$sheet->setTitle("销售明细表");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "销售总额: " .$sumData[0]["saleMoney"] . " 退货总额: " .$sumData[0]["reSaleMoney"]  . "  净销售额: " .$sumData[0]["trueSaleMoney"] . "  二批利润: " .$sumData[0]["lev2SaleMoney"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "时间");
		
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->setCellValue("B2", "业务员");
		
		$sheet->getColumnDimension('C')->setWidth(40);
		$sheet->setCellValue("C2", "客户");
		
		$sheet->getColumnDimension('D')->setWidth(40);
		$sheet->setCellValue("D2", "商品");
		
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->setCellValue("E2", "销售单价");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "销售数量");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "销售金额");

		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->setCellValue("H2", "二批价");

		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->setCellValue("I2", "成本单价");

		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->setCellValue("J2", "成本金额");

		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->setCellValue("K2", "退货数量");

		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->setCellValue("L2", "退货单价");

		$sheet->getColumnDimension('M')->setWidth(15);
		$sheet->setCellValue("M2", "退货金额");
		$sheet->getColumnDimension('N')->setWidth(50);
		$sheet->setCellValue("N2", "备注");
	
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["bizDT"]);
			$sheet->setCellValue("B" . $row, $v["userName"]);
			$sheet->setCellValue("C" . $row, $v["customerName"]);
			$sheet->setCellValue("D" . $row, $v["goodsName"]);
			$sheet->setCellValue("E" . $row, $v["goodsPrice"]);
			$sheet->setCellValue("F" . $row, $v["goodsCount"]);
			$sheet->setCellValue("G" . $row, $v["goodsMoney"]);
			$sheet->setCellValue("H" . $row, $v["lev2SalePrice"]);
			$sheet->setCellValue("I" . $row, $v["inventoryPrice"]);
			$sheet->setCellValue("J" . $row, $v["inventoryMoney"]);
			$sheet->setCellValue("K" . $row, $v["reGoodsCount"]);
			$sheet->setCellValue("L" . $row, $v["reGoodsPrice"]);
			$sheet->setCellValue("M" . $row, $v["reGoodsMoney"]);
			$sheet->setCellValue("N" . $row, $v["memo"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:N' . $lastRow)->applyFromArray($styleArray);
		
		$dt = date("YmdHis");
		
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
		
		$file_name = "销售明细导出.xlsx"; 
		$encoded_filename = urlencode($file_name);	// 将文件名进行urlencode转码
		$encoded_filename = str_replace('+', '%20', $encoded_filename);
	
		header('Content-Disposition: attachment;filename="'.$encoded_filename.'"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}
}