@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/Warehouse/WarehouseField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Goods/GoodsWithSalePriceField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Goods/GoodsField.js?dt=dt=1621089072')}}" type="text/javascript"></script>


<script src="{{asset('extra/Scripts/PSI/Sale/WSMainForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/WSEditForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/WSDistributionEdit.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/WSExportForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/WSImportForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>

<script src="{{asset('extra/Scripts/PSI/Customer/CustomerEditForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>

<script src="{{asset('extra/Scripts/PSI/UX/CellEditing.js?dt=dt=1621089072')}}" type="text/javascript"></script>

<script src="{{asset('extra/Lodop/LodopFuncs.js?dt=dt=1621089072')}}" type="text/javascript"></script>

<script>
    Ext.onReady(function() {
        var app = Ext.create("PSI.App", {
            userName: "{{$loginUserName}}",
            productionName: "{{$productionName}}"
        });

        var permission = {
            add: "{{$pAdd}}",
            edit: "{{$pEdit}}",
            del: "{{$pDelete}}",
            commit: "{{$pCommit}}",
            genPDF: "{{$pGenPDF}}",
            print: "{{$pPrint}}",
            tmsOrder: "{{$pTMSOrder}}",
        };
        var formTemp = Ext.create("PSI.Sale.WSMainForm", {
            permission: permission
        });
        app.add(formTemp);
        app.setAppHeader({
            title: "{{$title}}",
            iconCls: "PSI-fid2002"
        });

        orderTrace = function() {
            formTemp.orderTrace();
        };
    });
    //申请配送后信息跟踪
    function orderTrace() {}
</script>
@endsection