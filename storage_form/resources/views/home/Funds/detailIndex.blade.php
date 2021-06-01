@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}"
	type="text/javascript"></script>


<script src="{{asset('extra/Scripts/PSI/Funds/DetailMainForm.js?dt=dt=1621089072')}}"
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
		var formTemp=Ext.create("PSI.Funds.DetailMainForm");
		app.add(formTemp);
		app.setAppHeader({
			title : "{{$title}}",
			iconCls : "PSI-fid2004"
		});
		changeReceivable=function(){
			formTemp.changeReceivable();
		};
	});
	//转为应收帐
	function changeReceivable(){
	}
</script>

@endsection