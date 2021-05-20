@extends('.layouts.layouts')
@section('content')
    <script src="{{asset('extra/Scripts/PSI/Home/MainForm.js?dt=1621089072')}}"
            type="text/javascript"></script>
    <script>
        Ext.onReady(function() {
            var app = Ext.create("PSI.App", {
                userName : "admin",
                productionName : "进销货",
                showCopyright : true
            });

            app.add(Ext.create("PSI.Home.MainForm", {
                pSale : 1,
                pInventory : 1,
                pPurchase : 1,
                pMoney : 1,
                productionName : "进销货"
            }));
            app.setAppHeader({
                title : "进销货",
                iconCls : "PSI-fid-9997"
            });
        });
    </script>
 @endsection
