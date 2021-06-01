@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script src="{{asset('extra/Scripts/PSI/Funds/DiRvMainForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Funds/RvRecordEditForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Supplier/SupplierField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script>
	Ext.onReady(function() {
		PSI.Const.PROD_NAME = "{{$productionName}}";

		var app = Ext.create("PSI.App", {
			userName : "{{$loginUserName}}",
			productionName : "{{$productionName}}"
		});

		app.add(Ext.create("PSI.Funds.DiRvMainForm"));
		app.setAppHeader({
			title : "{{$title}}",
			iconCls : "PSI-fid2004"
		});
	});
</script>

@endsection