@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script src="{{asset('extra/Scripts/PSI/Funds/PayMainForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Funds/PaymentEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<!-- 查询条件 -->
<script
	src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Supplier/SupplierField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Factory/FactoryField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script>
	Ext.onReady(function() {
		PSI.Const.PROD_NAME = "{$productionName}";

		var app = Ext.create("PSI.App", {
			userName : "{{$loginUserName}}",
			productionName : "{{$productionName}}"
		});

		app.add(Ext.create("PSI.Funds.PayMainForm"));
		app.setAppHeader({
			title : "{{$title}}",
			iconCls : "PSI-fid2005"
		});
	});
</script>

@endsection