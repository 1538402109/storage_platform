<!DOCTYPE html>
<html>
<head>
    <title>进销货管理系统</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('Images/favicon.ico')}}" rel="shortcut icon" type="image/x-icon" />
    <script src="{{asset('extra/ExtJS/ext-all.js')}}" type="text/javascript"></script>
    <script src="{{asset('extra/ExtJS/ext-lang-zh_CN.js')}}" type="text/javascript"></script>
    <script src="{{asset('extra/Scripts/PSI/MsgBox.js?dt=1')}}" type="text/javascript"></script>
    <script src="{{asset('extra/Scripts/PSI/Const.js?dt=1')}}" type="text/javascript"></script>

    <link href="{{asset('extra/ExtJS/resources/css/ext-all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('extra/Content/Site.css?dt=1')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('extra/Content/icons.css?dt=1')}}" rel="stylesheet" type="text/css"/>

    <script src="{{asset('extra/Scripts/PSI/App.js?dt=1')}}" type="text/javascript"></script>
    <script src="{{asset('extra/Scripts/PSI/AFX/BaseMainExForm.js?dt=1')}}" type="text/javascript"></script>
    <script src="{{asset('extra/Scripts/PSI/AFX/BaseMainForm.js?dt=1')}}" type="text/javascript"></script>
    <script src="{{asset('extra/Scripts/PSI/AFX/BaseDialogForm.js?dt=1')}}" type="text/javascript"></script>

    <script src="{{asset('extra/Scripts/PSI/UX/PickerOverride.js?dt=1')}}" type="text/javascript"></script>
</head>
<body>
<script>
    if (Ext.isIE7m) {
        Ext.BLANK_IMAGE_URL = "{{asset('Images/s.gif')}}";
    }

    Ext.tip.QuickTipManager.init();

    PSI.Const.MOT = 0;
    PSI.Const.GC_DEC_NUMBER = parseInt(12);
</script>
<div class="container">
    @yield('content')
</div>

{{--{__CONTENT__}--}}
</body>
</html>


{{--<!DOCTYPE html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
{{--    <head>--}}
    {{--        <meta charset="utf-8">--}}
    {{--        <meta name="viewport" content="width=device-width, initial-scale=1">--}}
    {{--        <meta name="csrf-token" content="{{ csrf_token() }}">--}}

    {{--        <title>{{ config('app.name', 'Laravel') }}</title>--}}

    {{--        <!-- Fonts -->--}}
    {{--        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">--}}

    {{--        <!-- Styles -->--}}
    {{--        <link rel="stylesheet" href="{{ asset('css/app.css') }}">--}}

    {{--        <!-- Scripts -->--}}
    {{--        <script src="{{ asset('js/app.js') }}" defer></script>--}}
    {{--    </head>--}}
{{--    <body>--}}
{{--        <div class="font-sans text-gray-900 antialiased">--}}
    {{--            {{ $slot }}--}}
    {{--        </div>--}}
{{--    </body>--}}
{{--</html>--}}

