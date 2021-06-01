@extends('.layouts.layouts')
@section('content')
<script src="{{asset('extra/Scripts/PSI/Warehouse/WarehouseField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/User/UserField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Customer/CustomerField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Goods/GoodsWithSalePriceField.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Goods/GoodsField.js?dt=dt=1621089072')}}" type="text/javascript"></script>


<script src="{{asset('extra/Scripts/PSI/Sale/SRMainForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/SREditForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>
<script src="{{asset('extra/Scripts/PSI/Sale/SRSelectWSBillForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>

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
            verify: "{{$pVerify}}",
            unVerify: "{{$pUnVerify}}",
            tmsOrder: "{{$pTMSOrder}}",
        };
        app.add(Ext.create("PSI.Sale.SRMainForm", {
            permission: permission
        }));
        app.setAppHeader({
            title: "{{$title}}",
            iconCls: "PSI-fid2006"
        });
    });
</script>

@endsection