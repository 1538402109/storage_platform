@extends('.layouts.layouts')
@section('content')

<script src="{{asset('extra/Scripts/PSI/Funds/CashMainForm.js?dt=dt=1621089072')}}" type="text/javascript"></script>


<script>
    Ext.onReady(function(){
        var app = Ext.create("PSI.App", {
            userName: "{{$loginUserName}}",
            productionName: "{{$productionName}}"
        });

        app.add(Ext.create("PSI.Funds.CashMainForm"));
        app.setAppHeader({
            title: "{{$title}}",
            iconCls: "PSI-fid2024"
            });
    });
</script>

@endsection