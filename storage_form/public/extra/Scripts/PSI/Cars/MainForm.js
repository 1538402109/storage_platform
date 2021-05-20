/**
 * 应收账款明细 主界面
 */
Ext.define("PSI.Cars.CarsMainForm",{
    extend: "PSI.AFX.BaseMainExForm",
    initComponent: function() {
        var me = this;
        Ext.define("PSICACategory", {
            extend: "Ext.data.Model",
            fields: ["id", "name"]
        });

        Ext.apply(me, {
            tbar: me.getToolbarCmp(),
            layout: "border",
            border: 0,
            items: [{
                id: "panelQueryCmp",
                region: "north",
                height: 35,
                border: 0,
                collapsible: true,
                collapseMode: "mini",
                header: false,
                layout: {
                    type: "table",
                    columns: 4
                },
                items: me.getQueryCmp()
            },{
                region: "center",
                layout: "fit",
                border: 0,
                items: [me.getCarGrid()]
            }]
        });

        me.callParent(arguments);

    },
    getToolbarCmp:function(){
        var me=this;
        
        return [{
            text: "新增车辆",
            handler: me.onAddcar,
            scope: me
        }, {
            text: "编辑车辆",
            handler: me.onEditCategory,
            scope: me
        }, {
            text: "删除车辆",
            handler: me.onDeleteCategory,
            scope: me
        }, "-", {
            text: "帮助",
            handler: function() {
                window.open(me.URL("/Home/Help/index?t=goods"));
            }
        }, "-", {
            text: "关闭",
            handler: function() {
                me.closeWindow();
            }
        }];
    },

    onAddcar:function(){

    },

    getQueryCmp: function() {
        var me = this;

        return [{
            id: "editPlateNumber",
            labelWidth: 60,
            labelAlign: "right",
            labelSeparator: "",
            fieldLabel: "车牌号码",
            margin: "5, 0, 0, 0",
            xtype: "textfield",
            listeners: {
                specialkey: {
                    fn: me.onQueryEditSpecialKey,
                    scope: me
                }
            }
        }, {
            id: "editQueryName",
            labelWidth: 60,
            labelAlign: "right",
            labelSeparator: "",
            fieldLabel: "车辆类型",
            margin: "5, 0, 0, 0",
            xtype: "textfield",
            listeners: {
                specialkey: {
                    fn: me.onQueryEditSpecialKey,
                    scope: me
                }
            }
        }, {
            id: "editQueryAddress",
            labelWidth: 60,
            labelAlign: "right",
            labelSeparator: "",
            fieldLabel: "尺寸",
            margin: "5, 0, 0, 0",
            xtype: "textfield",
            listeners: {
                specialkey: {
                    fn: me.onQueryEditSpecialKey,
                    scope: me
                }
            }
        },{
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
                margin: "5, 0, 0, 15",
                handler: me.onClearQuery,
                scope: me
            }, {
                xtype: "button",
                text: "隐藏查询条件栏",
                width: 130,
                height: 26,
                iconCls: "PSI-button-hide",
                margin: "5 0 0 10",
                handler: function() {
                    Ext.getCmp("panelQueryCmp").collapse();
                },
                scope: me
            }]
        }];
    },

    onAddcar:function(){
        var me = this;

        var form = Ext.create("PSI.Car.CarEditForm", {
            parentForm: me
        });

        form.show();
    },

    getCarGrid: function() {
        var me = this;
        if (me.__rvGrid) {
            return me.__rvGrid;
        }

        Ext.define("PSICar", {
            extend: "Ext.data.Model",
            fields: ["id", "plateNumber", "size", "type", "memo"
            ]
        });

        var store = Ext.create("Ext.data.Store", {
            model: "PSICar",
            pageSize: 20,
            proxy: {
                type: "ajax",
                actionMethods: {
                    read: "POST"
                },
                url: PSI.Const.BASE_URL + "Home/Cars/carList",
                reader: {
                    root: 'dataList',
                    totalProperty: 'totalCount'
                }
            },
            autoLoad: false,
            data: []
        });

        /*
        store.on("beforeload", function() {
            Ext.apply(store.proxy.extraParams, {
                caType: Ext.getCmp("comboCA").getValue(),
                categoryId: Ext.getCmp("comboCategory")
                    .getValue(),
                customerId: Ext.getCmp("editCustomerQuery")
                    .getIdValue(),
                supplierId: Ext.getCmp("editSupplierQuery")
                    .getIdValue()
            });
        });
        */

        me.__carGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            viewConfig: {
                enableTextSelection: true
            },
            bbar: ["->", {
                xtype: "pagingtoolbar",
                border: 0,
                store: store
            }],
            columnLines: true,
            columns: [{
                header: "车牌号",
                dataIndex: "plateNumber",
                menuDisabled: true,
                sortable: false
            }, {
                header: "尺寸",
                dataIndex: "size",
                menuDisabled: true,
                sortable: false
            }, {
                header: "车型",
                dataIndex: "type",
                menuDisabled: true,
                sortable: false
            }, {
                header: "备注",
                dataIndex: "memo",
                menuDisabled: true,
                sortable: false
            }],
            store: store,
            listeners: {
                select: {
                    fn: me.onCarGridSelect,
                    scope: me
                }
            }
        });

        return me.__carGrid;
    },

    getRvParam: function() {
        var item = this.getCarGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return null;
        }

        var rv = item[0];
        return rv.get("caId");
    },

    onCarGridSelect: function() {
        var me = this;

    },

    onRvDetailGridSelect: function() {
        var me = this;

    },


    onQuery: function() {
        var me = this;
        me.getCarGrid().getStore().loadPage(1);
    },
    onQueryEditSpecialKey: function(field, e) {
        if (e.getKey() === e.ENTER) {
            var me = this;
            var id = field.getId();
            for (var i = 0; i < me.__queryEditNameList.length - 1; i++) {
                var editorId = me.__queryEditNameList[i];
                if (id === editorId) {
                    var edit = Ext.getCmp(me.__queryEditNameList[i + 1]);
                    edit.focus();
                    edit.setValue(edit.getValue());
                }
            }
        }
    },
    onClearQuery: function() {
        var me = this;

        Ext.getCmp("editCustomerQuery").clearIdValue();
        Ext.getCmp("editSupplierQuery").clearIdValue();
        me.onQuery();
    }
});