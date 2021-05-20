@extends('.layouts.layouts')
@section('content')

<script
    src="{{asset('extra/Scripts/PSI/User/ChangeMyPasswordForm.js?dt=123456')}}"
    type="text/javascript"></script>
<script>
    Ext.onReady(function() {
        var app = Ext.create("PSI.App", {
            userName : "公司\\信息部\\系统管理员",
            productionName : "进销货",
            appHeaderInfo : {
                title : "修改我的密码",
                iconCls : "PSI-fid-9996"
            }
        });

        var mainForm = Ext.create("PSI.User.ChangeMyPasswordForm", {
            loginUserId : "6C2A09CD-A129-11E4-9B6A-782BCBD7746B",
            loginUserName : "admin",
            loginUserFullName : "公司\\信息部\\系统管理员"
        });

        app.add(mainForm);
    });
</script>
@endsection
