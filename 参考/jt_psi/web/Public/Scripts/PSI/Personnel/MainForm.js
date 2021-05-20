/**
 * 业务日志 - 主界面
 * 
 * @author JIATU
 */
 Ext.define("PSI.Personnel.MainForm", {
    extend: "PSI.AFX.BaseMainExForm",

    config: {
        pAddPersonnel: null,
        pEditPersonnel: null,
        pDeletePersonnel: null,
        pChangePassword: null,
        pGoodsBinding: null
    },

    initComponent: function() {
        var me = this;

        Ext.apply(me, {
            tbar:[{
				text: "新增用户",
				disabled: me.getPAddPersonnel() == "0",
				handler: me.onAddUser,
				scope: me,
                disabled:true
			}, {
				text: "编辑用户",
				disabled: me.getPEditPersonnel() == "0",
				handler: me.onEditUser,
				scope: me
			}, {
				text: "删除用户",
				disabled: me.getPDeletePersonnel() == "0",
				handler: me.onDeleteUser,
				scope: me,
                disabled:true
			}, "-", {
				text: "修改用户密码",
				disabled: me.getPChangePassword() == "0",
				handler: me.onEditUserPassword,
				scope: me
			}, "-", {
                text: "业务商品绑定",
				disabled: me.getPGoodsBinding() == "0",
				handler: me.onGoodsBinding,
				scope: me
            },"-",{
				text: "帮助",
				handler: function () {
					window.open(me
						.URL("/Home/Help/index?t=user"));
				}
			}, "-", {
				text: "关闭",
				handler: function () {
					me.closeWindow();
				}
			}],
            items: [{
                id: "panelQueryCmp",
				region: "north",
				border: 0,
				height: 35,
				header: false,
				collapsible: true,
				collapseMode: "mini",
				layout: {
					type: "table",
					columns: 4
				},
				items: me.getQueryCmp()
            },{
                region: "center",
                layout: "fit",
                border: 0,
                items: [me.getMainGrid()]
            }],
            
        });
        me.callParent();
        me.onRefresh();
    },

    getQueryCmp: function () {
		var me = this;
		return [{
			id: "editQueryLoginName",
			labelWidth: 60,
			labelAlign: "right",
			labelSeparator: "",
			fieldLabel: "登录名",
			margin: "5, 0, 0, 0",
			xtype: "textfield"
		}, {
			id: "editQueryName",
			labelWidth: 60,
			labelAlign: "right",
			labelSeparator: "",
			fieldLabel: "姓名",
			margin: "5, 0, 0, 0",
			xtype: "textfield"
		}, {
			xtype: "container",
			items: [{
				xtype: "button",
				text: "查询",
				width: 100,
				height: 26,
				margin: "5, 0, 0, 20",
				handler: me.onQuery,
				scope: me
			}, {
				xtype: "button",
				text: "清空查询条件",
				width: 100,
				height: 26,
				margin: "5, 0, 0, 5",
				handler: me.onClearQuery,
				scope: me
			}, {
				xtype: "button",
				text: "隐藏查询条件栏",
				width: 130,
				height: 26,
				iconCls: "PSI-button-hide",
				margin: "5 0 0 10",
				handler: function () {
					Ext.getCmp("panelQueryCmp").collapse();
				},
				scope: me
			}]
		}];
	},

    getMainGrid: function() {
        var me = this;
        if (me.__mainGrid) {
            return me.__mainGrid;
        }

        var modelName = "PSI_Bizlog_MainForm_PSILog";
        Ext.define(modelName, {
            extend: "Ext.data.Model",
            fields: ["id", "login_name", "name", "enabled", "org_code",
				"gender", "birthday", "id_card_number", "tel",
				"tel02", "address", "data_org", "permission"],
            idProperty: "id"
        });
        var store = Ext.create("Ext.data.Store", {
            model: modelName,
            pageSize: 20,
            proxy: {
                type: "ajax",
                actionMethods: {
                    read: "POST"
                },
                url: me.URL("Home/Personnel/users"),
                reader: {
                    root: 'dataList',
					totalProperty: 'totalCount'
                }
            },
            autoLoad: true
        });
        store.on("beforeload", function() {
            store.proxy.extraParams = me.getQueryParam();
        });

        me.__mainGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            header: {
				height: 30,
				title: me.formatGridHeaderTitle("人员列表")
			},
            viewConfig: {
                enableTextSelection: true
            },
            loadMask: true,
            columnLines: true,
            columns: {
                defaults: {
                    menuDisabled: true,
                    sortable: false
                },
                items: [Ext.create("Ext.grid.RowNumberer", {
                    text: "序号",
                    width: 50
                }), {
                    header: "登录名",
                    dataIndex: "login_name",
                    menuDisabled: true,
                    sortable: false,
                    locked: true
                }, {
                    header: "姓名",
                    dataIndex: "name",
                    menuDisabled: true,
                    sortable: false,
                    locked: true
                }, {
                    header: "权限角色",
                    dataIndex: "permission",
                    menuDisabled: true,
                    sortable: false,
                    width: 200
                }, {
                    header: "编码",
                    dataIndex: "org_code",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "是否允许登录",
                    dataIndex: "enabled",
                    menuDisabled: true,
                    sortable: false,
                    renderer: function (value) {
                        return value == 1
                            ? "允许登录"
                            : "<span style='color:red'>禁止登录</span>";
                    }
                }, {
                    header: "性别",
                    dataIndex: "gender",
                    menuDisabled: true,
                    sortable: false,
                    width: 70
                }, {
                    header: "生日",
                    dataIndex: "birthday",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "身份证号",
                    dataIndex: "id_card_number",
                    menuDisabled: true,
                    sortable: false,
                    width: 200
                }, {
                    header: "联系电话",
                    dataIndex: "tel",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "备用联系电话",
                    dataIndex: "tel02",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "家庭住址",
                    dataIndex: "address",
                    menuDisabled: true,
                    sortable: false,
                    width: 200
                }, {
                    header: "数据域",
                    dataIndex: "data_org",
                    menuDisabled: true,
                    sortable: false,
                    width: 100
                }]
            },
            store: store,
            listeners: {
                celldblclick: {
                    fn: me.onEditUser,
                    scope: me
                }
            },
        });

        return me.__mainGrid;
    },

    getToolbarCmp: function() {
        var me = this;

        var store = me.getMainGrid().getStore();

        var buttons = [{
            cls: "PSI-toolbox",
            id: "pagingToobar",
            xtype: "pagingtoolbar",
            border: 0,
            store: store
        }, "-", {
            xtype: "displayfield",
            value: "每页显示"
        }, {
            cls: "PSI-toolbox",
            id: "comboCountPerPage",
            xtype: "combobox",
            editable: false,
            width: 60,
            store: Ext.create("Ext.data.ArrayStore", {
                fields: ["text"],
                data: [
                    ["20"],
                    ["50"],
                    ["100"],
                    ["300"],
                    ["1000"]
                ]
            }),
            value: 20,
            listeners: {
                change: {
                    fn: function() {
                        store.pageSize = Ext
                            .getCmp("comboCountPerPage").getValue();
                        store.currentPage = 1;
                        Ext.getCmp("pagingToobar").doRefresh();
                    },
                    scope: me
                }
            }
        }, {
            xtype: "displayfield",
            value: "条记录"
        }
        , "-", {
            text: "帮助",
            iconCls: "PSI-help",
            handler: function() {
                window.open(me.URL("/Home/Help/index?t=bizlog"));
            }
        }
        , "-", {
            text: "关闭",
            handler: function() {
                me.closeWindow();
            }
        }
    ];
        return buttons;
    },

/**
	 * 新增用户
	 */
 onAddUser: function () {
    var me = this;
    var form = Ext.create("PSI.Personnel.PersonnelEditForm", {
        parentForm: me,
    });
    form.show();
},

/**
 * 编辑用户
 */
onEditUser: function () {
    var me = this;
    if (me.getPEditPersonnel() == "0") {
        return;
    }

    var item = me.getMainGrid().getSelectionModel().getSelection();
    if (item === null || item.length !== 1) {
        me.showInfo("请选择要编辑的用户");
        return;
    }
    var user = item[0].data;

    var form = Ext.create("PSI.Personnel.PersonnelEditForm", {
        parentForm: me,
        entity: user
    });
    form.show();
},

/**
 * 修改用户密码
 */
onEditUserPassword: function () {
    var me = this;

    var item = me.getMainGrid().getSelectionModel().getSelection();
    if (item === null || item.length !== 1) {
        me.showInfo("请选择要修改密码的用户");
        return;
    }

    var user = item[0].getData();
    var form = Ext.create("PSI.Personnel.ChangePersonnelPasswordForm", {
        entity: user
    });
    form.show();
},

/**
 * 业务商品绑定
 */
 onGoodsBinding: function () {
    var me = this;
    if (me.getPGoodsBinding() == "0") {
        return;
    }

    var item = me.getMainGrid().getSelectionModel().getSelection();
    if (item === null || item.length !== 1) {
        me.showInfo("请选择要业务商品绑定的用户");
        return;
    }
    var user = item[0].data;

    var form = Ext.create("PSI.Personnel.GoodsBindingForm", {
        parentForm: me,
        entity: user
    });
    form.show();
},

/**
 * 删除用户
 */
onDeleteUser: function () {
    var me = this;
    var item = me.getMainGrid().getSelectionModel().getSelection();
    if (item === null || item.length !== 1) {
        me.showInfo("请选择要删除的用户");
        return;
    }

    var user = item[0].getData();

    var funcConfirm = function () {
        Ext.getBody().mask("正在删除中...");
        var r = {
            url: me.URL("Home/Personnel/deletePersonnel"),
            params: {
                id: user.id
            },
            callback: function (options, success, response) {
                Ext.getBody().unmask();

                if (success) {
                    var data = me.decodeJSON(response.responseText);
                    if (data.success) {
                        me.showInfo("成功完成删除操作", function () {
                            me.freshUserGrid();
                        });
                    } else {
                        me.showInfo(data.msg);
                    }
                }
            }
        };
        me.ajax(r);
    };

    var info = "请确认是否删除用户 <span style='color:red'>" + user.name
        + "</span> ?";
    me.confirm(info, funcConfirm);
},

    /**
     * 刷新
     */
    onRefresh: function() {
        var me = this;

        me.getMainGrid().getStore().currentPage = 1;
        me.focus();
    },

    onUnitTest: function() {
        var url = PSI.Const.BASE_URL + "UnitTest";
        window.open(url);
    },

    getQueryParam: function() {
        var result = {
            loginName: Ext.getCmp("editQueryLoginName").getValue(),
            QueryName: Ext.getCmp("editQueryName").getValue(),
        };
        return result;
    },

    freshOrgGrid: function () {
		var me = this;

		me.getMainGrid().getStore().reload();
	},

    onQuery: function () {
		var me = this;

		me.getMainGrid().getStore().removeAll();

		me.freshOrgGrid();
	},

    onClearQuery: function () {
		var me = this;

		Ext.getCmp("editQueryLoginName").setValue(null);
		Ext.getCmp("editQueryName").setValue(null);

		me.onQuery();
	},
});