/**
 * 仓库 - 新增或编辑界面
 */
Ext.define("PSI.Warehouse.EditForm", {
    extend: "PSI.AFX.BaseDialogForm",

    /**
     * 初始化组件
     */
    initComponent: function() {
        var me = this;

        var entity = me.getEntity();

        me.adding = entity == null;

        var buttons = [];
        if (!entity) {
            var btn = {
                text: "保存并继续新增",
                formBind: true,
                handler: function() {
                    me.onOK(true);
                },
                scope: me
            };

            buttons.push(btn);
        }

        var btn = {
            text: "保存",
            formBind: true,
            iconCls: "PSI-button-ok",
            handler: function() {
                me.onOK(false);
            },
            scope: me
        };
        buttons.push(btn);

        var btn = {
            text: entity == null ? "关闭" : "取消",
            handler: function() {
                me.close();
            },
            scope: me
        };
        buttons.push(btn);

        var t = entity == null ? "新增仓库" : "编辑仓库";
        var f = entity == null ?
            "edit-form-create.png" :
            "edit-form-update.png";
        var logoHtml = "<img style='float:left;margin:10px 20px 0px 10px;width:48px;height:48px;' src='" +
            PSI.Const.BASE_URL +
            "Images/" +
            f +
            "'></img>" +
            "<h2 style='color:#196d83'>" +
            t +
            "</h2>" +
            "<p style='color:#196d83'>标记 <span style='color:red;font-weight:bold'>*</span>的是必须录入数据的字段</p>";
        Ext.apply(me, {
            header: {
                title: me.formatTitle(PSI.Const.PROD_NAME),
                height: 40
            },
            width: 400,
            height: me.adding ? 270 : 300,
            layout: "border",
            listeners: {
                show: {
                    fn: me.onWndShow,
                    scope: me
                },
                close: {
                    fn: me.onWndClose,
                    scope: me
                }
            },
            items: [{
                region: "north",
                height: 90,
                border: 0,
                html: logoHtml
            }, {
                region: "center",
                border: 0,
                id: "PSI_Warehouse_EditForm_editForm",
                xtype: "form",
                layout: {
                    type: "table",
                    columns: 1
                },
                height: "100%",
                bodyPadding: 5,
                defaultType: 'textfield',
                fieldDefaults: {
                    labelWidth: 60,
                    labelAlign: "right",
                    labelSeparator: "",
                    msgTarget: 'side',
                    width: 370,
                    margin: "5"
                },
                items: [{
                    xtype: "hidden",
                    name: "id",
                    value: entity == null ? null : entity
                        .get("id")
                }, {
                    id: "PSI_Warehouse_EditForm_editCode",
                    fieldLabel: "仓库编码",
                    allowBlank: false,
                    blankText: "没有输入仓库编码",
                    beforeLabelTextTpl: PSI.Const.REQUIRED,
                    name: "code",
                    value: entity == null ? null : entity
                        .get("code"),
                    listeners: {
                        specialkey: {
                            fn: me.onEditCodeSpecialKey,
                            scope: me
                        }
                    }
                }, {
                    id: "PSI_Warehouse_EditForm_editName",
                    fieldLabel: "仓库名称",
                    allowBlank: false,
                    blankText: "没有输入仓库名称",
                    beforeLabelTextTpl: PSI.Const.REQUIRED,
                    name: "name",
                    value: entity == null ? null : entity
                        .get("name"),
                    listeners: {
                        specialkey: {
                            fn: me.onEditNameSpecialKey,
                            scope: me
                        }
                    }
                }, {
                    id: "PSI_Warehouse_EditForm_editIsDefault",
                    xtype: "combo",
                    queryMode: "local",
                    editable: false,
                    valueField: "id",
                    labelWidth: 60,
                    labelAlign: "right",
                    labelSeparator: "",
                    fieldLabel: "默认仓库",
                    beforeLabelTextTpl: PSI.Const.REQUIRED,
                    store: Ext.create("Ext.data.ArrayStore", {
                        fields: ["id", "text"],
                        data: [
                            [1, "是"],
                            [0, "否"]
                        ]
                    }),
                    hidden: me.adding,
                    value: entity == null ?
                        1 : parseInt(entity.get("isDefault"))
                }, {
                    id: "PSI_Warehouse_EditForm_hiddenIsDefault",
                    xtype: "hidden",
                    name: "isDefault"
                }, {
                    id: "PSI_Warehouse_EditForm_editEnabled",
                    xtype: "combo",
                    queryMode: "local",
                    editable: false,
                    valueField: "id",
                    labelWidth: 60,
                    labelAlign: "right",
                    labelSeparator: "",
                    fieldLabel: "状态",
                    beforeLabelTextTpl: PSI.Const.REQUIRED,
                    store: Ext.create("Ext.data.ArrayStore", {
                        fields: ["id", "text"],
                        data: [
                            [1, "启用"],
                            [2, "停用"]
                        ]
                    }),
                    hidden: me.adding,
                    value: entity == null ?
                        1 : parseInt(entity.get("enabled"))
                }, {
                    id: "PSI_Warehouse_EditForm_hiddenEnabled",
                    xtype: "hidden",
                    name: "enabled"
                }],
                buttons: buttons
            }]
        });

        me.callParent(arguments);

        me.editForm = Ext.getCmp("PSI_Warehouse_EditForm_editForm");

        me.editCode = Ext.getCmp("PSI_Warehouse_EditForm_editCode");
        me.editName = Ext.getCmp("PSI_Warehouse_EditForm_editName");
        me.editEnabled = Ext.getCmp("PSI_Warehouse_EditForm_editEnabled");
        me.hiddenEnabled = Ext.getCmp("PSI_Warehouse_EditForm_hiddenEnabled");
        me.editIsDefault = Ext.getCmp("PSI_Warehouse_EditForm_editIsDefault");
        me.hiddenIsDefault = Ext.getCmp("PSI_Warehouse_EditForm_hiddenIsDefault");
    },

    /**
     * 保存
     */
    onOK: function(thenAdd) {
        var me = this;

        me.hiddenEnabled.setValue(me.editEnabled.getValue());
        me.hiddenIsDefault.setValue(me.editIsDefault.getValue());
        var f = me.editForm;
        var el = f.getEl();
        el.mask(PSI.Const.SAVING);
        var sf = {
            url: me.URL("/Home/Warehouse/editWarehouse"),
            method: "POST",
            success: function(form, action) {
                me.__lastId = action.result.id;

                el.unmask();

                PSI.MsgBox.tip("数据保存成功");
                me.focus();
                if (thenAdd) {
                    me.clearEdit();
                } else {
                    me.close();
                }
            },
            failure: function(form, action) {
                el.unmask();
                PSI.MsgBox.showInfo(action.result.msg, function() {
                    me.editCode.focus();
                });
            }
        };
        f.submit(sf);
    },

    onEditCodeSpecialKey: function(field, e) {
        var me = this;

        if (e.getKey() == e.ENTER) {
            var editName = me.editName;
            editName.focus();
            editName.setValue(editName.getValue());
        }
    },

    onEditNameSpecialKey: function(field, e) {
        var me = this;

        if (e.getKey() == e.ENTER) {
            var f = me.editForm;
            if (f.getForm().isValid()) {
                me.onOK(me.adding);
            }
        }
    },

    clearEdit: function() {
        var me = this;
        me.editCode.focus();

        var editors = [me.editCode, me.editName];
        for (var i = 0; i < editors.length; i++) {
            var edit = editors[i];
            edit.setValue(null);
            edit.clearInvalid();
        }
    },

    onWindowBeforeUnload: function(e) {
        return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
    },

    onWndClose: function() {
        var me = this;

        Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);

        if (me.__lastId) {
            if (me.getParentForm()) {
                me.getParentForm().freshGrid(me.__lastId);
            }
        }
    },

    onWndShow: function() {
        var me = this;

        Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);

        var editCode = me.editCode;
        editCode.focus();
        editCode.setValue(editCode.getValue());
    }
});