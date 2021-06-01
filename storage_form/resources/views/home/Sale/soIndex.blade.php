@extends('.layouts.layouts')
@section('content')
<script
	src="{{asset('extra/Scripts/PSI/Warehouse/WarehouseField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/User/OrgWithDataOrgField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Goods/GoodsWithSalePriceField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Goods/GoodsField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/SaleOrder/SOMainForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/SaleOrder/SOEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/SaleOrder/ChangeOrderEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script src="{{asset('extra/Scripts/PSI/Sale/WSEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/WSExportForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/WSImportForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/Customer/CustomerEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/UX/CellEditing.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script src="{{asset('extra/Lodop/LodopFuncs.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<!-- 生成采购订单 -->
<script
	src="{{asset('extra/Scripts/PSI/PurchaseOrder/POEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Supplier/SupplierField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Goods/GoodsWithPurchasePriceField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script>
	Ext.onReady(function() {
		var app = Ext.create("PSI.App", {
			userName : "{{$loginUserName}}",
			productionName : "{{$productionName}}"
		});

		var permission = {
			add : "{{$pAdd}}",
			edit : "{{$pEdit}}",
			del : "{{$pDelete}}",
			genPDF : "{{$pGenPDF}}",
			confirm : "{{$pConfirm}}",
			genWSBill : "{{$pGenWSBill}}",
			genPOBill : "{{$pGenPOBill}}",
			print : "{{$pPrint}}",
			closeBill : "{{$pCloseBill}}"
		};

		app.add(Ext.create("PSI.SaleOrder.SOMainForm", {
			permission : permission
		}));
		app.setAppHeader({
			title : "{{$title}}",
			iconCls : "PSI-fid2028"
		});
	});
</script>

@endsection