@extends('.layouts.layouts')
@section('content')

<script
	src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/SaleContract/SCMainForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/SaleContract/SCEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<!-- 生成销售订单用 -->
<script
	src="{{asset('extra/Scripts/PSI/SaleOrder/SOEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script src="{{asset('extra/Scripts/PSI/UX/CellEditing.js?dt=dt=1621089072')}}"
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
	src="{{asset('extra/Scripts/PSI/Customer/CustomerEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<!-- CustomerEditForm.js中用到了WarehouseField.js -->
<script
	src="{{asset('extra/Scripts/PSI/Warehouse/WarehouseField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script src="{{asset('extra/Lodop/LodopFuncs.js?dt=dt=1621089072')}}"
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
			commit : "{{$pCommit}}",
			genSOBill : "{{$pGenSOBill}}",
			print : "{{$pPrint}}"
		};

		app.add(Ext.create("PSI.SaleContract.SCMainForm", {
			permission : permission
		}));

		app.setAppHeader({
			title : "{{$title}}"
		});
	});
</script>

@endsection