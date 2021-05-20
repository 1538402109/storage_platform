<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Portals extends Model
{
    use HasFactory;
    protected $table = '';

    /**
     * @name 库存金额
     * @param $Warehouses_id
     * @return number
     */
    public static function _inventory_amount($warehouse_id)
    {
        $sum = Inventorys::where("warehouse_id",$warehouse_id)->sum('balance_money');
        return $sum;
    }

    /**
     *@name 低于安全库存数量的商品种类
     *@param  $warehouse_id
     * @return number
     */

    public static function _lower_inventory_number($warehouse_id)
    {
        $lower_inventory_number = Inventorys::select(DB::raw('count(*) as cnt'))
            ->join('t_goods_si',function ($join){
                $join->on('t_inventory.goods_id','t_goods_si.goods_id')
                    ->on('t_inventory.warehouse_id','t_goods_si.warehouse_id')
                    ->where('t_goods_si.safety_inventory' , '>','t_inventory.balance_count');
            })
            ->where("t_inventory.warehouse_id",$warehouse_id)->get();
        $siCount = $lower_inventory_number?$lower_inventory_number[0]->cnt:0;

        return $siCount;
    }

    /**
     *@name 超过库存上限的商品种类
     *@param  $warehouse_id
     * @return number
     */
    public static function _high_inventory_number($warehouse_id)
    {
        $higher_inventory_number = Inventorys::select(DB::raw('count(*) as cnt'))
            ->join('t_goods_si',function ($join){
                $join->on('t_inventory.goods_id','t_goods_si.goods_id')
                    ->on('t_inventory.warehouse_id','t_goods_si.warehouse_id')
                    ->where('t_goods_si.inventory_upper' , '>','t_inventory.balance_count');
            })->where('t_goods_si.inventory_upper','<>',0)
            ->whereNotNull('t_goods_si.inventory_upper')
            ->where("t_inventory.warehouse_id",$warehouse_id)->get();

        $iuCount = $higher_inventory_number?$higher_inventory_number[0]->cnt:0;

        return $iuCount;
    }

    /**
     * @name 计算销售额和毛利
     * @param $year $month
     *@return array
     */

    public static function _sale_profit_money($year,$month)
    {
        $data = array("sale_money"=>0,"profit"=>0);
        $balance = Wsbill::select(DB::raw("sum(sale_money) as sale_money"),DB::raw(" sum(profit) as profit"))
            ->where("bill_status",">",1000)
            ->where(DB::raw("year(bizdt)"), $year)
            ->where(DB::raw("month(bizdt)"), $month)
            ->get();
        if($balance){
            $data = array("sale_money"=>$balance[0]->sale_money,"profit"=>$balance[0]->profit);
        }
        return $data;
    }

    /**
     *@name 扣除退货
     * @param $year $month
     * @return array
     */

    public static function _rejection_sale_money($year,$month)
    {
        $data = array("rej_sale_money"=>0,"rej_profit"=>0);

        $balance = Srbill::select(DB::raw("sum(rejection_sale_money) as rej_sale_money"),DB::raw(" sum(profit) as rej_profit"))
            ->where("bill_status",">",1000)
            ->where(DB::raw("year(bizdt)"), $year)
            ->where(DB::raw("month(bizdt)"), $month)
            ->get();

        if($balance){
            $data = array("rej_sale_money"=>$balance[0]->rej_sale_money,"rej_profit"=>$balance[0]->rej_profit);
        }

        return $data;
    }


    /**
     * @name 采购金额
     * @param $year $month
     *@return array
     */

    public static function _purchase_money($year,$month)
    {
        $data = array("purchase_money"=>0);
        $purchase_money = Pwbill::select(DB::raw('sum(goods_money) as goods_money'))->where('bill_status',">=",1000)
                            ->where(DB::raw('year(biz_dt)'),$year)
                            ->where(DB::raw('month(biz_dt)'),$month)
                            ->get();
        if($purchase_money){
            $data = array("purchase_money"=>$purchase_money[0]->goods_money);
        }
        return $data;

    }

    /**
     * @name  采购扣除退货
     * @param $year,$month
     * @return array
     * */

    public static function _purchase_rej_money($year,$month)
    {
        $data = array("rej_money"=>0);
        $purchase_rej_money = Prbill::select(DB::raw('sum(rejection_money) as rejection_money'))->where('bill_status',1000)
            ->where(DB::raw('year(bizdt)'),$year)
            ->where(DB::raw('month(bizdt)'),$month)
            ->get();
        if($purchase_rej_money){
            $data = array("rej_money"=>$purchase_rej_money[0]->rejection_money);
        }

        return $data;
    }


    /**
     * @name  应收账款
     * @param $company_id
     * @return number
     */

    public static function _receivables_money($company_id)
    {
        $balance_money = Receivables::where('company_id',$company_id)->sum('balance_money');
        return $balance_money?$balance_money:0;
    }

    /**
     * @name 30天以内
     *@param $company_id
     *@return number
     */
    public static function _lower_30_receivables_money($company_id)
    {
        // 账龄30天内
        $balance = ReceivableDetails::where("company_id",$company_id)->where(DB::raw('datediff(current_date(), biz_date)'),"<",30)->sum('balance_money');
        return $balance?$balance:0;
    }


    /**
     * @name  账龄30-60天之间
     * @param $company_id
     * @return number
     */

    public static function _between_30_and_60_receivables_money($company_id)
    {
        $balance = ReceivableDetails::where("company_id",$company_id)
            ->where(DB::raw('datediff(current_date(), biz_date)'),">=",30)
            ->where(DB::raw('datediff(current_date(), biz_date)'),"<=",60)
            ->sum('balance_money');
        return $balance?$balance:0;
    }


    /**
     * @name 账龄60-90天之间
     * @param $company_id
     * @return number
     */

    public static function _between_60_and_90_receivables_money($company_id)
    {
        $balance = ReceivableDetails::where("company_id",$company_id)
            ->where(DB::raw('datediff(current_date(), biz_date)'),">",60)
            ->where(DB::raw('datediff(current_date(), biz_date)'),"<=",90)
            ->sum('balance_money');
        return $balance?$balance:0;
    }

    /**
     * @name 账龄大于90天
     * @param $company_id
     * @return number
     */
    public static function _hight_90_receivables_money($company_id)
    {
        // 账龄30-60天
        $balance = ReceivableDetails::where("company_id",$company_id)
            ->where(DB::raw('datediff(current_date(), biz_date)'),">",90)
            ->sum('balance_money');
        return $balance?$balance:0;
    }

    /**
     *@name 应付账款
     * @param $company_id
     * @return number
     */

    public static function _payables_money($companyId)
    {
        // 应付账款
        $payables_money = Payables::where("company_id",$companyId)->sum("balance_money");
        return $payables_money?$payables_money:0;
    }

    /**
     *@name 账龄30天内
     * @param $company_id
     * @return number
     */
    public static function _lower_30_payables_money($companyId)
    {
        $balance = PayableDetails::where("company_id",$companyId)->where(DB::raw('datediff(current_date(), biz_date)'),'<',30)->sum('balance_money');
        return $balance?$balance:0;
    }

    /**
     *@name 账龄30,60天内
     * @param $company_id
     * @return number
     */
    public static function _between_30_and_60_payables_money($companyId)
    {
        $balance = PayableDetails::where("company_id",$companyId)
            ->where(DB::raw('datediff(current_date(), biz_date)'),'>=',30)
            ->where(DB::raw('datediff(current_date(), biz_date)'),'<=',60)
            ->sum('balance_money');
        return $balance?$balance:0;
    }

    /**
     *@name 账龄60,90天内
     * @param $company_id
     * @return number
     */
    public static function _between_60_and_90_payables_money($companyId)
    {
        $balance = PayableDetails::where("company_id",$companyId)
            ->where(DB::raw('datediff(current_date(), biz_date)'),'>',60)
            ->where(DB::raw('datediff(current_date(), biz_date)'),'<=',90)
            ->sum('balance_money');
        return $balance?$balance:0;
    }

    /**
     *@name 账龄大于90
     * @param $company_id
     * @return number
     */
    public static function _high_90_payables_money($companyId)
    {
        $balance = PayableDetails::where("company_id",$companyId)->where(DB::raw('datediff(current_date(), biz_date)'),'>',90)->sum('balance_money');
        return $balance?$balance:0;
    }
}
