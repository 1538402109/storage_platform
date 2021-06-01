@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/Supplier/SupplierField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/Funds/PrePaymentMainForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Funds/AddPrePaymentForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Funds/ReturnPrePaymentForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script>
	Ext.onReady(function() {
		PSI.Const.PROD_NAME = "{{$productionName}}";
		var app = Ext.create("PSI.App", {
			userName : "{{$loginUserName}}",
			productionName : "{{$productionName}}"
		});

		app.add(Ext.create("PSI.Funds.PrePaymentMainForm"));
		app.setAppHeader({
			title : "{{$title}}",
			iconCls : "PSI-fid2026"
		});
	});
</script>

@endsection