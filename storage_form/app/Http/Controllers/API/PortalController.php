<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventorys;
use App\Models\Portals;
use App\Models\Srbill;
use App\Models\User;
use App\Models\Warehouses;
use App\Models\Wsbill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    /**
     * @name 库存看板
     */
    public function getInventoryPortal()
    {
        $result = array();
        $Warehouses = Warehouses::select('id', 'name')->where('inited', 1)->get();

        foreach ($Warehouses as $i => $val) {
            $result[$i]["warehouseName"] = $val->name;
            $balance_money = Portals::_inventory_amount($val->id);
            $result[$i]["inventoryMoney"] = $balance_money ? $balance_money : 0;

            $low_cnt = Portals::_lower_inventory_number($val->id);
            $result[$i]["siCount"] = $low_cnt;

            $high_cnt = Portals::_high_inventory_number($val->id);
            $result[$i]["iuCount"] = $high_cnt;
        }

        return $result;
    }

    /**
     * @name 销售看板
     */
    public function getSalePortal()
    {
        $year = date("Y");
        $result = array();
        $saleMoney = 0;
        $profit = 0;
        for ($i = 0; $i < 12; $i++) {
            $month = $i + 1;
            $month_format = $month < 10 ? "0" . $month : $month;
            $result[$i]["month"] = "$year-$month_format";
            $sale_money = Portals::_sale_profit_money($year, $month);
            $rejection_money = Portals::_rejection_sale_money($year, $month);
            $saleMoney -= $sale_money["sale_money"] + $rejection_money["rej_sale_money"];
            $profit += $sale_money["profit"] + $rejection_money["rej_profit"];

            $result[$i]["saleMoney"] = $saleMoney;
            $result[$i]["profit"] = $profit;

            if ($saleMoney != 0) {
                $result[$i]["rate"] = sprintf("%0.2f", $profit / $saleMoney * 100) . "%";
            } else {
                $result[$i]["rate"] = "";
            }

        }

        return $result;
    }


    /**
     * @name  采购看板
     */
    public function getPurchasePortal()
    {
        $year = date("Y");
        $result = array();
        $purchaseMoney = 0;
        $profit = 0;
        $goodsMoney = 0;
        for ($i = 0; $i < 12; $i++) {
            $month = $i + 1;
            $month_format = $month < 10 ? "0" . $month : $month;
            $result[$i]["month"] = "$year-$month_format";
            $purchase_money = Portals::_purchase_money($year, $month);
            $rejection_money = Portals::_purchase_rej_money($year, $month);
            $goodsMoney += $purchase_money["purchase_money"] - $rejection_money["rej_money"];
            $result[$i]["purchaseMoney"] = $goodsMoney;
        }
        return $result;
    }

    /**
     * @name 资金看板
     */
    public function getMoneyPortal()
    {
        $result[0]["item"] = "应收账款";
        $company_id = User::getCompanyId();
        $result[0]["balanceMoney"] = Portals::_receivables_money($company_id);
        $result[0]["money30"] = Portals::_lower_30_receivables_money($company_id);
        $result[0]["money30to60"] = Portals::_between_30_and_60_receivables_money($company_id);
        $result[0]["money60to90"] = Portals::_between_60_and_90_receivables_money($company_id);
        $result[0]["money90"] = Portals::_hight_90_receivables_money($company_id);

        $result[1]["item"] = "应付账款";
        $result[1]["balanceMoney"] = Portals::_payables_money($company_id);
        $result[1]["money30"] = Portals::_lower_30_payables_money($company_id);
        $result[1]["money30to60"] = Portals::_between_30_and_60_payables_money($company_id);
        $result[1]["money60to90"] = Portals::_between_60_and_90_payables_money($company_id);
        $result[1]["money90"] = Portals::_high_90_payables_money($company_id);

        return $result;
    }

}
