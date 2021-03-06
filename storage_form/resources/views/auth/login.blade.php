@extends('.layouts.guest')
@section('content')
    <style type="text/css">
        #loading-mask {
            background-color: white;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            z-index: 20000;
        }

        #loading {
            height: auto;
            position: absolute;
            left: 45%;
            top: 40%;
            padding: 2px;
            z-index: 20001;
        }

        #loading .loading-indicator {
            background: white;
            color: #444;
            font: bold 13px Helvetica, Arial, sans-serif;
            height: auto;
            margin: 0;
            padding: 10px;
        }

        #loading-msg {
            font-size: 10px;
            font-weight: normal;
        }
    </style>

    <div id="loading-mask" style=""></div>
    <div id="loading">
        <div class="loading-indicator">
            <img src="{{asset('Images/loader.gif')}}" width="32" height="32" style="margin-right: 8px; float: left; vertical-align: top;" /> 欢迎使用进销货管理系统&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <br /> <span id="loading-msg">正在加载中，请稍候...</span>
        </div>
    </div>

{{--    <script src="{{asset('extra/ExtJS/ext-all.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('extra/ExtJS/ext-lang-zh_CN.js')}}" type="text/javascript"></script>
    <script src="{{asset('extra/Scripts/PSI/Const.js?dt=1')}}" type="text/javascript"></script>

    <link href="{{asset('extra/Content/foundation/foundation.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('extra/Content/foundation/app.css')}}" rel="stylesheet" type="text/css" />


    <script type="text/javascript" src="https://pv.sohu.com/cityjson?ie=utf-8" charset="gb2312"></script>

    <div class="top-bar">
        <div class="top-bar-left">
            <ul class="menu">
                <li class="menu-text"><span style="font-size: 1.5em">欢迎使用进销货管理系统</span></li>
            </ul>
        </div>
        <div class="top-bar-right">
            <ul class="menu">
                <li><a href="https://gitee.com/jtbb/jt_psi.git" target="_blank"><span
                            style="font-size: 1em">官网</span></a></li>
                <li><a href="{$uri}Home/Help/index?t=login" target="_blank"><span
                            style="font-size: 1em">帮助</span></a></li>
            </ul>
        </div>
    </div>

    <br />

    <div class="row">
        <div class="large-8 columns">
            <img src="{{asset('Images/background.png')}}" style="width: 100%; hegiht: 100%" />
        </div>

        <div class="large-4 columns">
            <div style="margin: 50px 0 0 0">
                <form>
                    <div class="row">
                        <div class="small-3 columns">
                            <label for="editLoginName" class="text-right middle">登录名</label>
                        </div>
                        <div class="small-9 columns">
                            <input type="text" id="editLoginName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-3 columns">
                            <label for="editPassword" class="text-right middle">密码</label>
                        </div>
                        <div class="small-9 columns">
                            <input type="password" id="editPassword">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-3 columns">&nbsp;</div>
                        <div class="small-6 columns">
                            <a href="#" class="button expanded psi_secondary" id="buttonOK">登&nbsp;&nbsp;录</a>
                        </div>
                        <div class="small-3 columns">&nbsp;</div>
                    </div>
                </form>
            </div>

            <div class="callout alert" id="divInfoCallout" style="display: none" data-closable>
                <div id="divInfo"></div>
            </div>

            <div class="callout warning" id="divDemoWarning">当前处于演示环境，请勿保存正式数据，默认的登录名和密码均为 admin

                如果发现登录失败或者页面样式不正确，请使用360浏览器来访问</div>
        </div>
    </div>

    <footer>
        <div class="row">
            <div class="large-3 columns">&nbsp;</div>
            <div class="large-6 columns">
            <span style="font-size: 0.8em">Copyright &copy; 2015-2021
				PSI Team, All Rights Reserved</span>
            </div>
            <div class="large-3 columns">&nbsp;</div>
        </div>
    </footer>

    <script type="text/javascript">
        Ext.onReady(function() {
            var ip = "";
            var cname = "";
            if (window.returnCitySN) {
                ip = returnCitySN.cip;
                cname = returnCitySN.cname;
            }

            PSI.Const.BASE_URL = "{$uri}";
            if (Ext.isIE7m) {
                Ext.BLANK_IMAGE_URL = "{{asset('extra/Images/s.gif')}}";
            }

            if ("{$demoInfo}" == "") {
                Ext.get("divDemoWarning").remove();
            }

            var editLoginName = document.getElementById("editLoginName");
            var editPassword = document.getElementById("editPassword");
            var loginName = Ext.util.Cookies.get("PSI_user_login_name");
            if (loginName) {
                editLoginName.value = decodeURIComponent(loginName);
                editPassword.focus();
            } else {
                editLoginName.focus();
            }

            editLoginName.onkeydown = function(e) {
                if (e.keyCode == 13) {
                    editPassword.focus();
                }
            };
            editPassword.onkeydown = function(e) {
                if (e.keyCode == 13) {
                    doLogin(editLoginName.value, editPassword.value, ip, cname);
                }
            }
            document.getElementById("buttonOK").onclick = function() {
                doLogin(editLoginName.value, editPassword.value, ip, cname);
            }

            Ext.get("loading").remove();
            Ext.get("loading-mask").remove();
        });

        function doLogin(loginName, password, ip, ipFrom) {
            if (!loginName) {
                showInfo("没有输入登录名");
                setInputFocus();
                return;
            }
            if (!password) {
                showInfo("没有输入密码");
                setInputFocus();
                return;
            }

            var r = {
                url: PSI.Const.BASE_URL + "Home/User/loginPOST",
                method: "POST",
                params: {
                    loginName: loginName,
                    password: password,
                    ip: ip,
                    ipFrom: ipFrom
                },
                callback: function(options, success, response) {

                    if (success) {
                        var data = Ext.JSON.decode(response.responseText);
                        if (data.success) {
                            setLoginNameToCookie(loginName);

                            var returnPage = "{$returnPage}";
                            if (returnPage) {
                                location.replace(returnPage);
                            } else {
                                location.replace(PSI.Const.BASE_URL);
                            }
                        } else {
                            showInfo(data.msg);
                            setInputFocus();
                        }
                    }
                }
            };

            Ext.Ajax.request(r);
        }

        function setInputFocus() {
            var editLoginName = document.getElementById("editLoginName");
            var editPassword = document.getElementById("editPassword");
            if (editLoginName.value) {
                editPassword.focus();
            } else {
                editLoginName.focus();
            }
        }

        function showInfo(info) {
            document.getElementById("divInfoCallout").style.display = "";
            document.getElementById("divInfo").innerHTML = info;
        }

        function setLoginNameToCookie(loginName) {
            loginName = encodeURIComponent(loginName);
            var dt = Ext.Date.add(new Date(), Ext.Date.YEAR, 1)
            Ext.util.Cookies.set("PSI_user_login_name", loginName, dt);
        }
    </script>
@endsection
