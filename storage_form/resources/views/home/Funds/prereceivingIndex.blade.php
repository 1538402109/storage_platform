@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>

<script
	src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script
	src="{{asset('extra/Scripts/PSI/Funds/PreReceivingMainForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Funds/AddPreReceivingForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>
<script
	src="{{asset('extra/Scripts/PSI/Funds/ReturnPreReceivingForm.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script>
	Ext.onReady(function() {
		PSI.Const.PROD_NAME = "{{$productionName}}";

		var app = Ext.create("PSI.App", {
			userName : "{{$loginUserName}}",
			productionName : "{{$productionName}}"
		});

		app.add(Ext.create("PSI.Funds.PreReceivingMainForm"));
		app.setAppHeader({
			title : "{{$title}}",
			iconCls : "PSI-fid2025"
		});
	});
</script>

@endsection