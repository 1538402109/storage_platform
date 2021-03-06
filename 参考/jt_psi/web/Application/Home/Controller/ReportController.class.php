<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\InventoryReportService;
use Home\Service\PayablesReportService;
use Home\Service\ReceivablesReportService;
use Home\Service\SaleReportService;
use Home\Service\UserService;
require __DIR__ . '/../Common/Excel/PHPExcel/IOFactory.php';

/**
 * 报表Controller
 *
 * @author JIATU
 *        
 */
class ReportController extends PSIBaseController {

	/**
	 * 销售日报表(按商品汇总)
	 */
	public function saleDayByGoods() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_DAY_BY_GOODS)) {
			$this->initVar();
			
			$this->assign("title", "销售日报表(按商品汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleDayByGoods");
		}
	}

	/**
	 * 销售日报表(按商品汇总) - 查询数据
	 */
	public function saleDayByGoodsQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByGoodsQueryData($params));
		}
	}

	/**
	 * 销售日报表(按商品汇总) - 查询汇总数据
	 */
	public function saleDayByGoodsSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByGoodsSummaryQueryData($params));
		}
	}

	/**
	 * 销售日报表(按商品汇总) - 生成打印页面
	 */
	public function genSaleDayByGoodsPrintPage() {
		if (IS_POST) {
			$params = [
					"dt" => I("post.dt"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleDayByGoodsDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售日报表(按商品汇总) - 生成PDF文件
	 */
	public function saleDayByGoodsPdf() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByGoodsPdf($params);
	}

	/**
	 * 销售日报表(按商品汇总) - 生成ExcelF文件
	 */
	public function saleDayByGoodsExcel() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByGoodsExcel($params);
	}

	/**
	 * 销售日报表(按客户汇总)
	 */
	public function saleDayByCustomer() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_DAY_BY_CUSTOMER)) {
			$this->initVar();
			
			$this->assign("title", "销售日报表(按客户汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleDayByCustomer");
		}
	}

	/**
	 * 销售日报表(按客户汇总) - 查询数据
	 */
	public function saleDayByCustomerQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByCustomerQueryData($params));
		}
	}

	/**
	 * 销售日报表(按客户汇总) - 查询汇总数据
	 */
	public function saleDayByCustomerSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByCustomerSummaryQueryData($params));
		}
	}

	/**
	 * 销售日报表(按客户汇总) - 生成打印页面
	 */
	public function genSaleDayByCustomerPrintPage() {
		if (IS_POST) {
			$params = [
					"dt" => I("post.dt"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleDayByCustomerDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售日报表(按客户汇总) - 生成PDF文件
	 */
	public function saleDayByCustomerPdf() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByCustomerPdf($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 生成ExcelF文件
	 */
	public function saleDayByCustomerExcel() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByCustomerExcel($params);
	}

	/**
	 * 销售日报表(按仓库汇总)
	 */
	public function saleDayByWarehouse() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_DAY_BY_WAREHOUSE)) {
			$this->initVar();
			
			$this->assign("title", "销售日报表(按仓库汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleDayByWarehouse");
		}
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询数据
	 */
	public function saleDayByWarehouseQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByWarehouseQueryData($params));
		}
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询汇总数据
	 */
	public function saleDayByWarehouseSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByWarehouseSummaryQueryData($params));
		}
	}

	/**
	 * 销售日报表(按仓库汇总) - 生成打印页面
	 */
	public function genSaleDayByWarehousePrintPage() {
		if (IS_POST) {
			$params = [
					"dt" => I("post.dt"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleDayByWarehouseDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售日报表(按仓库汇总) - 生成PDF文件
	 */
	public function saleDayByWarehousePdf() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByWarehousePdf($params);
	}

	/**
	 * 销售日报表(按仓库汇总) - 生成ExcelF文件
	 */
	public function saleDayByWarehouseExcel() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByWarehouseExcel($params);
	}

	/**
	 * 销售日报表(按业务员汇总)
	 */
	public function saleDayByBizuser() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_DAY_BY_BIZUSER)) {
			$this->initVar();
			
			$this->assign("title", "销售日报表(按业务员汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleDayByBizuser");
		}
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询数据
	 */
	public function saleDayByBizuserQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByBizuserQueryData($params));
		}
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询汇总数据
	 */
	public function saleDayByBizuserSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"dt" => I("post.dt")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleDayByBizuserSummaryQueryData($params));
		}
	}

	/**
	 * 销售日报表(按业务员汇总) - 生成打印页面
	 */
	public function genSaleDayByBizuserPrintPage() {
		if (IS_POST) {
			$params = [
					"dt" => I("post.dt"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleDayByBizuserDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售日报表(按业务员汇总) - 生成PDF文件
	 */
	public function saleDayByBizuserPdf() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByBizuserPdf($params);
	}

	/**
	 * 销售日报表(按业务员汇总) - 生成ExcelF文件
	 */
	public function saleDayByBizuserExcel() {
		$params = [
				"dt" => I("get.dt"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleDayByBizuserExcel($params);
	}

	/**
	 * 销售月报表(按商品汇总)
	 */
	public function saleMonthByGoods() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_MONTH_BY_GOODS)) {
			$this->initVar();
			
			$this->assign("title", "销售月报表(按商品汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleMonthByGoods");
		}
	}

	/**
	 * 销售月报表(按商品汇总) - 查询数据
	 */
	public function saleMonthByGoodsQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort"),
					"userId" => I("post.userId")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByGoodsQueryData($params));
		}
	}

	/**
	 * 销售月报表(按商品汇总) - 查询汇总数据
	 */
	public function saleMonthByGoodsSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month"),
					"userId" => I("post.userId")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByGoodsSummaryQueryData($params));
		}
	}

	/**
	 * 销售月报表(按商品汇总) - 生成打印页面
	 */
	public function genSaleMonthByGoodsPrintPage() {
		if (IS_POST) {
			$params = [
					"year" => I("post.year"),
					"month" => I("post.month"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort"),
					"userId"=> I("post.userId"),
					"userName"=> I("post.userName"),
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleMonthByGoodsDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售月报表(按商品汇总) - 生成PDF文件
	 */
	public function saleMonthByGoodsPdf() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort"),
				"userId"=> I("get.userId"),
				"userName"=> I("get.userName"),
		];
		
		$service = new SaleReportService();
		$service->saleMonthByGoodsPdf($params);
	}

	/**
	 * 销售月报表(按商品汇总) - 生成Excel文件
	 */
	public function saleMonthByGoodsExcel() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort"),
				"userId"=> I("get.userId"),
				"userName"=> I("get.userName"),
		];
		
		$service = new SaleReportService();
		$service->saleMonthByGoodsExcel($params);
	}

	/**
	 * 销售月报表(按客户汇总)
	 */
	public function saleMonthByCustomer() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_MONTH_BY_CUSTOMER)) {
			$this->initVar();
			
			$this->assign("title", "销售月报表(按客户汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleMonthByCustomer");
		}
	}

	/**
	 * 销售月报表(按客户汇总) - 查询数据
	 */
	public function saleMonthByCustomerQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByCustomerQueryData($params));
		}
	}

	/**
	 * 销售月报表(按客户汇总) - 查询汇总数据
	 */
	public function saleMonthByCustomerSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByCustomerSummaryQueryData($params));
		}
	}

	/**
	 * 销售月报表(按客户汇总) - 生成打印页面
	 */
	public function genSaleMonthByCustomerPrintPage() {
		if (IS_POST) {
			$params = [
					"year" => I("post.year"),
					"month" => I("post.month"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleMonthByCustomerDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售月报表(按客户汇总) - 生成PDF文件
	 */
	public function saleMonthByCustomerPdf() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleMonthByCustomerPdf($params);
	}

	/**
	 * 销售月报表(按客户汇总) - 生成Excel文件
	 */
	public function saleMonthByCustomerExcel() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleMonthByCustomerExcel($params);
	}

	/**
	 * 销售月报表(按仓库汇总)
	 */
	public function saleMonthByWarehouse() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_MONTH_BY_WAREHOUSE)) {
			$this->initVar();
			
			$this->assign("title", "销售月报表(按仓库汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleMonthByWarehouse");
		}
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询数据
	 */
	public function saleMonthByWarehouseQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByWarehouseQueryData($params));
		}
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询汇总数据
	 */
	public function saleMonthByWarehouseSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByWarehouseSummaryQueryData($params));
		}
	}

	/**
	 * 销售月报表(按仓库汇总) - 生成打印页面
	 */
	public function genSaleMonthByWarehousePrintPage() {
		if (IS_POST) {
			$params = [
					"year" => I("post.year"),
					"month" => I("post.month"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleMonthByWarehouseDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售月报表(按仓库汇总) - 生成PDF文件
	 */
	public function saleMonthByWarehousePdf() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleMonthByWarehousePdf($params);
	}

	/**
	 * 销售月报表(按仓库汇总) - 生成Excel文件
	 */
	public function saleMonthByWarehouseExcel() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleMonthByWarehouseExcel($params);
	}

	/**
	 * 销售月报表(按业务员汇总)
	 */
	public function saleMonthByBizuser() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SALE_MONTH_BY_BIZUSER)) {
			$this->initVar();
			
			$this->assign("title", "销售月报表(按业务员汇总)");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/saleMonthByBizuser");
		}
	}

	/**
	 * 销售月报表(按业务员汇总) - 查询数据
	 */
	public function saleMonthByBizuserQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByBizuserQueryData($params));
		}
	}

	/**
	 * 销售月报表(按业务员汇总) - 查询汇总数据
	 */
	public function saleMonthByBizuserSummaryQueryData() {
		if (IS_POST) {
			$params = array(
					"year" => I("post.year"),
					"month" => I("post.month"),
				
			);
			
			$rs = new SaleReportService();
			
			$this->ajaxReturn($rs->saleMonthByBizuserSummaryQueryData($params));
		}
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成打印页面
	 */
	public function genSaleMonthByBizuserPrintPage() {
		if (IS_POST) {
			$params = [
					"year" => I("post.year"),
					"month" => I("post.month"),
					"limit" => I("post.limit"),
					"sort" => I("post.sort")
			];
			
			$service = new SaleReportService();
			$data = $service->getSaleMonthByBizuserDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成PDF文件
	 */
	public function saleMonthByBizuserPdf() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleMonthByBizuserPdf($params);
	}

	/**
	 * 销售月报表(按业务员汇总) - 生成Excel文件
	 */
	public function saleMonthByBizuserExcel() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"sort" => I("get.sort")
		];
		
		$service = new SaleReportService();
		$service->saleMonthByBizuserExcel($params);
	}
/**
	 * 销售月报表(按业务员汇总) - 生成Excel文件
	 */
	public function saleMonthDetailByBizuserExcel() {
		$params = [
				"year" => I("get.year"),
				"month" => I("get.month"),
				"limit" => I("get.limit"),
				"userId" => I("get.userid"),
				"bizDT" => I("get.bizdt"),
		];
		
		$service = new SaleReportService();
		$service->saleMonthDetailByBizuserExcel($params);
	}

	
	/**
	 *销售明细
	 */
	public function saleDetailIndex() {
		$us = new UserService();
	

		$userId = I("get.userid");
		$this->assign("userId", $userId);
		$userName = I("get.username");
		$this->assign("userName", $userName);
		$beginDate = I("get.begindate");
		$this->assign("beginDate", $beginDate);
		$endDate = I("get.enddate");
		$this->assign("endDate", $endDate);
		$this->initVar();
			
		$this->assign("title", "销售明细");
		//获取主题风格
		$interfaceStyle = $us->userUi();
		$style = $interfaceStyle['interface_style'];
		$this->assign('interfaceStyle',$style);
		
		$this->display();
	}
		/**
	 * 销售明细  查询
	 */
	public function saleAllDetailQueryData() {
		$params = [
			"year" => I("post.year"),
			"month" => I("post.month"),
			"page" => I("post.page"),
			"start" => I("post.start"),
			"limit" => I("post.limit"),
			"sort" => I("post.sort"),
			"categoryId" => I("post.categoryId"),
			"caId" => I("post.caId"),
			"startDate"=>I("post.startDate"),
			"endDate"=>I("post.endDate"),
			"userId"=>I("post.editBizUser")
		];
		
		$service = new SaleReportService();
		$this->ajaxReturn($service->saleAllDetailQueryData($params));
	}
			/**
	 * 销售明细  查询
	 */
	public function saleAllDetailQuerySumData() {
		$params = [
			"year" => I("post.year"),
			"month" => I("post.month"),
			"page" => I("post.page"),
			"start" => I("post.start"),
			"limit" => I("post.limit"),
			"sort" => I("post.sort"),
			"categoryId" => I("post.categoryId"),
			"caId" => I("post.caId"),
			"startDate"=>$this->toYMD(I("post.startDate")),
			"endDate"=>$this->toYMD(I("post.endDate")),
			"userId"=>I("post.userId")
		];
		
		$service = new SaleReportService();
		$this->ajaxReturn($service->saleAllDetailQuerySumData($params));
	}

		/**
	 * 销售明细 - 生成Excel文件
	 */
	public function saleAllDetailQueryExcel() {
		$params = [
			"year" => I("get.year"),
			"month" => I("get.month"),
			"page" => I("get.page"),
			"start" => I("get.start"),
			"limit" => I("get.limit"),
			"sort" => I("get.sort"),
			"categoryId" => I("get.categoryId"),
			"caId" => I("get.caId"),
			"startDate"=>$this->toYMD(I("get.startDate")),
			"endDate"=>$this->toYMD(I("get.endDate")),
			"userId"=>I("get.userId")
		];
		
		$service = new SaleReportService();
		$service->saleAllDetailrExcel($params);
	}
	protected function toYMD($d) {
		return date("Y-m-d", strtotime($d));
	}
	/**
	/**
	 * 安全库存明细表
	 */
	public function safetyInventory() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_SAFETY_INVENTORY)) {
			$this->initVar();
			
			$this->assign("title", "安全库存明细表");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/safetyInventory");
		}
	}

	/**
	 * 安全库存明细表 - 查询数据
	 */
	public function safetyInventoryQueryData() {
		if (IS_POST) {
			$params = array(
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			
			$is = new InventoryReportService();
			
			$this->ajaxReturn($is->safetyInventoryQueryData($params));
		}
	}

	/**
	 * 安全库存明细表 - 生成打印页面
	 */
	public function genSafetyInventoryPrintPage() {
		if (IS_POST) {
			$params = [
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			];
			
			$service = new InventoryReportService();
			
			$data = $service->getSafetyInventoryDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 安全库存明细表 - 生成PDF文件
	 */
	public function safetyInventoryPdf() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new InventoryReportService();
		$service->safetyInventoryPdf($params);
	}

	/**
	 * 安全库存明细表 - 生成Excel文件
	 */
	public function safetyInventoryExcel() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new InventoryReportService();
		$service->safetyInventoryExcel($params);
	}

	/**
	 * 应收账款账龄分析表
	 */
	public function receivablesAge() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_RECEIVABLES_AGE)) {
			$this->initVar();
			
			$this->assign("title", "应收账款账龄分析表");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/receivablesAge");
		}
	}

	/**
	 * 应收账款账龄分析表 - 数据查询
	 */
	public function receivablesAgeQueryData() {
		if (IS_POST) {
			$params = array(
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			
			$rs = new ReceivablesReportService();
			
			$this->ajaxReturn($rs->receivablesAgeQueryData($params));
		}
	}

	/**
	 * 应收账款账龄分析表 - 当期汇总数据查询
	 */
	public function receivablesSummaryQueryData() {
		if (IS_POST) {
			$rs = new ReceivablesReportService();
			
			$this->ajaxReturn($rs->receivablesSummaryQueryData());
		}
	}

	/**
	 * 应收账款账龄分析表 - 生成打印页面
	 */
	public function genReceivablesAgePrintPage() {
		if (IS_POST) {
			$params = [
					"limit" => I("post.limit")
			];
			
			$service = new ReceivablesReportService();
			
			$data = $service->getReceivablesAgeDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 应收账款账龄分析表 - 生成PDF文件
	 */
	public function receivablesAgePdf() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new ReceivablesReportService();
		$service->receivablesAgePdf($params);
	}

	/**
	 * 应收账款账龄分析表 - 生成Excel文件
	 */
	public function receivablesAgeExcel() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new ReceivablesReportService();
		$service->receivablesAgeExcel($params);
	}

	/**
	 * 应付账款账龄分析表
	 */
	public function payablesAge() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_PAYABLES_AGE)) {
			$this->initVar();
			
			$this->assign("title", "应付账款账龄分析表");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/payablesAge");
		}
	}

	/**
	 * 应付账款账龄分析表 - 数据查询
	 */
	public function payablesAgeQueryData() {
		if (IS_POST) {
			$params = array(
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			
			$ps = new PayablesReportService();
			
			$this->ajaxReturn($ps->payablesAgeQueryData($params));
		}
	}

	/**
	 * 应付账款账龄分析表 - 当期汇总数据查询
	 */
	public function payablesSummaryQueryData() {
		if (IS_POST) {
			$ps = new PayablesReportService();
			
			$this->ajaxReturn($ps->payablesSummaryQueryData());
		}
	}

	/**
	 * 应付账款账龄分析表 - 生成打印页面
	 */
	public function genPayablesAgePrintPage() {
		if (IS_POST) {
			$params = [
					"limit" => I("post.limit")
			];
			
			$service = new PayablesReportService();
			
			$data = $service->getPayablesAgeDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 应付账款账龄分析表 - 生成PDF文件
	 */
	public function payablesAgePdf() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new PayablesReportService();
		$service->payablesAgePdf($params);
	}

	/**
	 * 应付账款账龄分析表 - 生成Excel文件
	 */
	public function payablesAgeExcel() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new PayablesReportService();
		$service->payablesAgeExcel($params);
	}

	/**
	 * 库存超上限明细表
	 */
	public function inventoryUpper() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::REPORT_INVENTORY_UPPER)) {
			$this->initVar();
			
			$this->assign("title", "库存超上限明细表");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Report/inventoryUpper");
		}
	}

	/**
	 * 库存超上限明细表 - 查询数据
	 */
	public function inventoryUpperQueryData() {
		if (IS_POST) {
			$params = array(
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			
			$is = new InventoryReportService();
			
			$this->ajaxReturn($is->inventoryUpperQueryData($params));
		}
	}

	/**
	 * 库存超上限明细表 - 生成打印页面
	 */
	public function genInventoryUpperPrintPage() {
		if (IS_POST) {
			$params = [
					"limit" => I("post.limit")
			];
			
			$service = new InventoryReportService();
			
			$data = $service->getInventoryUpperDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}

	/**
	 * 库存超上限明细表 - 生成PDF文件
	 */
	public function inventoryUpperPdf() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new InventoryReportService();
		$service->inventoryUpperPdf($params);
	}

	/**
	 * 库存超上限明细表 - 生成Excel文件
	 */
	public function inventoryUpperExcel() {
		$params = [
				"limit" => I("get.limit")
		];
		
		$service = new InventoryReportService();
		$service->inventoryUpperExcel($params);
	}
}