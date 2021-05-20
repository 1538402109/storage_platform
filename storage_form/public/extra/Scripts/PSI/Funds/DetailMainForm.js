/**
 * 应收账款明细 主界面
 */
Ext.define("PSI.Funds.DetailMainForm",{
    extend: "PSI.AFX.BaseMainExForm",
    initComponent: function() {
        var me = this;
        Ext.define("PSICACategory", {
            extend: "Ext.data.Model",
            fields: ["id", "name"]
        });

        Ext.apply(me, {
            layout: "border",
            border: 0,
            items: [
                {
                    id : "panelQueryCmp",
                    region : "north",
                    height : 65,
                    layout : "fit",
                    border : 0,
                    header : false,
                    collapsible : true,
                    collapseMode : "mini",
                    layout : {
                        type : "table",
                        columns : 5
                    },
                    items : me.getSearchItem()
                }, {
                    region: "center",
                    layout: "border",
                    border: 0,   
                    items:[{
                        region: "north",
                        border: 0,
                        layout: "fit",
                        height:"50%",
                        split: true,
                        items: [me.getRvDetailGrid()]
                    }, {
                        region: "center",
                        layout: "fit",
                        border: 0,
                        split: true,
                        items: [me.getRvRecordGrid()]
                    }]
                }]
        });

        me.callParent(arguments);
        me.onComboCASelect();
    },
    /**
     * 获取查询条件
     */
    getSearchItem:function(){
        var me = this;
        return [{
            id : "comboCategory",
            xtype : "combo",
            queryMode : "local",
            editable : false,
            valueField: "id",
            displayField: "name",
            labelWidth : 60,
            labelAlign : "right",
            labelSeparator : "",
            fieldLabel : "分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;类",
            margin : "5, 0, 0, 0",
            store: Ext.create("Ext.data.Store", {
                model: "PSICACategory",
                autoLoad: false,
                data: []
            })
        }, {
            id : "editCustomerQuery",
            fieldLabel : "客&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;户",
            xtype : "psi_customerfield",
            showModal : true,
            labelAlign : "right",
            labelSeparator : "",
            labelWidth : 60,
            margin : "5, 0, 0, 0"
        }, {
            id : "editStartDate",
            xtype : "datefield",
            labelWidth : 60,
            margin : "5, 0, 0, 0",
            format : "Y-m-d",
            labelAlign : "right",
            labelSeparator : "",
            fieldLabel : "起始日期"
        }, {
            id : "editEndDate",
            xtype : "datefield",
            margin : "5, 0, 0, 0",
            labelWidth : 60,
            format : "Y-m-d",
            labelAlign : "right",
            labelSeparator : "",
            fieldLabel : "截止日期"
        },{
            id : "comboCollect",
            xtype : "combo",
            queryMode : "local",
            editable : false,
            valueField : "id",
            labelWidth : 60,
            labelAlign : "right",
            labelSeparator : "",
            fieldLabel : "收款方式",
            margin : "5, 0, 0, 0",
            store: Ext.create("Ext.data.ArrayStore", {
                fields: ["id", "text"],
                data: [
                    ["", "全部"],
                    ["3", "物流代收"],
                    ["0", "自主收款"]
                ]
            }),
            value:""
        }, {
            id : "editCode",
            xtype : "textfield",
            showModal : true,
            labelAlign : "right",
            labelSeparator : "",
            labelWidth : 60,
            margin : "5, 0, 0, 0",
            fieldLabel : "单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号"
        },{
            id : "editBizUser",
            xtype: "psi_userfield",
            queryMode : "local",
            editable : false,
            valueField: "id",
            displayField: "name",
            labelWidth : 60,
            labelAlign : "right",
            labelSeparator : "",
            fieldLabel : "业&nbsp;&nbsp;务&nbsp;&nbsp;员",
            margin : "5, 0, 0, 0"
        }, {
            xtype : "container",
            items : [{
                        xtype : "button",
                        text : "查询",
                        width : 100,
                        height : 26,
                        margin : "5 0 0 10",
                        handler : me.onQuery,
                        scope : me
                    }, {
                        xtype : "button",
                        text : "清空查询条件",
                        width : 100,
                        height : 26,
                        margin : "5, 0, 0, 10",
                        handler : me.onClearQuery,
                        scope : me
                    }]
        }, {
            xtype : "container",
            items : [{
                        xtype : "button",
                        text : "隐藏查询条件栏",
                        width : 130,
                        height : 26,
                        iconCls : "PSI-button-hide",
                        margin : "5 0 0 10",
                        handler : function() {
                            Ext.getCmp("panelQueryCmp").collapse();
                        },
                        scope : me
                    }]
        }];
    },
/*
    changeCategory:function(e){
        
        var me=this;
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Customer/customerListByCateId",
            params: {
                categoryId: Ext.getCmp("comboCategory").getValue()
            },
            method: "POST",
            callback: function(options, success, response) {
                var combo = Ext.getCmp("editCustomerQuery");
                var store = combo.getStore();
                store.removeAll();
                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    store.add({id:"",name:"全部"});
                    store.add(data);
                    if (store.getCount() > 0) {
                        combo.setValue(store.getAt(0).get("id"));
                    }
                }

                el.unmask();
            }
        });
    },
*/
    onComboCASelect: function() {
        var me = this;

        me.getRvDetailGrid().getStore().removeAll();
        me.getRvRecordGrid().getStore().removeAll();

        var el = Ext.getBody();
        el.mask(PSI.Const.LOADING);
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Funds/rvCategoryList",
            params: {
                id: "customer"
            },
            method: "POST",
            callback: function(options, success, response) {
                var combo = Ext.getCmp("comboCategory");
                var store = combo.getStore();

                store.removeAll();
                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    store.add(data);
                    if (store.getCount() > 0) {
                        combo.setValue(store.getAt(0).get("id"));
                        me.changeCategory();
                    }
                }

                el.unmask();
            }
        });
    },
    getRvGrid: function() {
        var me = this;
        if (me.__rvGrid) {
            return me.__rvGrid;
        }
        Ext.define("PSIRv", {
            extend: "Ext.data.Model",
            fields: ["id", "caId", "code", "name", "rvMoney",
                "actMoney", "balanceMoney"
            ]
        });
        var store = Ext.create("Ext.data.Store", {
            model: "PSIRv",
            pageSize: 20,
            proxy: {
                type: "ajax",
                actionMethods: {
                    read: "POST"
                },
                url: PSI.Const.BASE_URL + "Home/Funds/rvList2",
                reader: {
                    root: 'dataList',
                    totalProperty: 'totalCount'
                }
            },
            autoLoad: false,
            data: []
        });
        store.on("beforeload", function() {
            Ext.apply(store.proxy.extraParams, {
                caType: "customer",
                categoryId: Ext.getCmp("comboCategory").getValue(),
                customerId: Ext.getCmp("editCustomerQuery").getIdValue(),
                startDate:Ext.getCmp("editStartDate").getValue(),
                endDate:Ext.getCmp("editEndDate").getValue(),
                code:Ext.getCmp("editCode").getValue(),
                editBizUser:Ext.getCmp("editBizUser").getIdValue(),
                CollectType:Ext.getCmp("comboCollect").getValue()
            });
        });

        me.__rvGrid = Ext.create("Ext.grid.Panel", {
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
                header: "编码",
                dataIndex: "code",
                menuDisabled: true,
                sortable: false
            }, {
                header: "名称",
                dataIndex: "name",
                menuDisabled: true,
                sortable: false,
                width: 300
            }, {
                header: "应收金额",
                dataIndex: "rvMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn",
                width: 160
            }, {
                header: "已收金额",
                dataIndex: "actMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn",
                width: 160
            }, {
                header: "未收金额",
                dataIndex: "balanceMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn",
                width: 160
            }],
            store: store,
            listeners: {
                select: {
                    fn: me.onRvGridSelect,
                    scope: me
                }
            }
        });

        return me.__rvGrid;
    },

    getRvParam: function() {
        var item = this.getRvGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return null;
        }

        var rv = item[0];
        return rv.get("caId");
    },

    onRvGridSelect: function() {
        var me = this;

        this.getRvRecordGrid().getStore().removeAll();
        this.getRvRecordGrid().setTitle(me.formatGridHeaderTitle("收款记录"));

        this.getRvDetailGrid().getStore().loadPage(1);
    },

    getRvDetailGrid: function() {
        var me = this;
        if (me.__rvDetailGrid) {
            return me.__rvDetailGrid;
        }

        Ext.define("PSIRvDetail", {
            extend: "Ext.data.Model",
            fields: ["id", "rvMoney", "actMoney", "balanceMoney","name",
                "refType", "refNumber", "bizDT", "dateCreated", 'operator', 'receivingType',"bname"
            ]
        });

        var store = Ext.create("Ext.data.Store", {
            model: "PSIRvDetail",
            pageSize: 20,
            proxy: {
                type: "ajax",
                actionMethods: {
                    read: "POST"
                },
                url: PSI.Const.BASE_URL + "Home/Funds/rvDetailList2",
                reader: {
                    root: 'dataList',
                    totalProperty: 'totalCount'
                }
            },
            autoLoad: false,
            data: []
        });

        store.on("beforeload", function() {
            Ext.apply(store.proxy.extraParams, {
                caType: "customer",
                caId: Ext.getCmp("editCustomerQuery").getIdValue(),
                categoryId: Ext.getCmp("comboCategory").getValue(),
                startDate:Ext.getCmp("editStartDate").getValue(),
                endDate:Ext.getCmp("editEndDate").getValue(),
                code:Ext.getCmp("editCode").getValue(),
                editBizUser:Ext.getCmp("editBizUser").getIdValue(),
                CollectType:Ext.getCmp("comboCollect").getValue()
            });
        });

        me.__rvDetailGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            viewConfig: {
                enableTextSelection: true
            },
            header: {
                height: 30,
                title: me.formatGridHeaderTitle("业务单据")
            },
            bbar: ["->", {
                xtype: "pagingtoolbar",
                border: 0,
                store: store
            }],
            columnLines: true,
            columns: [{
                    header: "业务类型",
                    dataIndex: "refType",
                    menuDisabled: true,
                    sortable: false,
                    width: 120
                }, {
                    header: "单号",
                    dataIndex: "refNumber",
                    menuDisabled: true,
                    sortable: false,
                    width: 120,
                    renderer: function(value, md, record) {
                        if (record.get("refType") == "应收账款期初建账") {
                            return value;
                        }

                        return "<a href='" +
                            PSI.Const.BASE_URL +
                            "Home/Bill/viewIndex?fid=2004&refType=" +
                            encodeURIComponent(record
                                .get("refType")) +
                            "&ref=" +
                            encodeURIComponent(record
                                .get("refNumber")) +
                            "' target='_blank'>" + value +
                            "</a>";
                    }
                }, {
                    header: "业务日期",
                    dataIndex: "bizDT",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "应收金额",
                    dataIndex: "rvMoney",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    xtype: "numbercolumn"
                }, {
                    header: "已收金额",
                    dataIndex: "actMoney",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    xtype: "numbercolumn"
                }, {
                    header: "未收金额",
                    dataIndex: "balanceMoney",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    xtype: "numbercolumn"
                }, {
                    header: "客户名称",
                    dataIndex: "name",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                {
                    header: "收款方式",
                    dataIndex: "receivingType",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    renderer: function(value) {
                        if (value == 3) {
                            return '物流代收';
                        } else {
                            return '记应收账款';
                        }

                    }
                }, {
                    header: "业务员",
                    dataIndex: "bname",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                }, {
                    header: "操作人",
                    dataIndex: "operator",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                }, {
                    header: "创建时间",
                    dataIndex: "dateCreated",
                    menuDisabled: true,
                    sortable: false,
                    width: 140
                }, {
                    header: "转换收款方式",
                    dataIndex:"receivingType",
                    menuDisabled: true,
                    sortable: false,
                    width: 140,
                    renderer: function(value) {
                        if (value == 3) {
                            return '<a href="javascript:changeReceivable();">转为记应收账款</a>';
                        } else {
                            return '';
                        }
                    }
                }
            ],
            store: store,
            listeners: {
                select: {
                    fn: me.onRvDetailGridSelect,
                    scope: me
                }
            }
        });

        return me.__rvDetailGrid;
    },
    
    /**
     * 将物流代收转为记应收账款
     */
    changeReceivable:function(){
        var me = this;
        var item = this.getRvDetailGrid().getSelectionModel().getSelection();
        var rvDetail = item[0];
        PSI.MsgBox.confirm("您确定要将【物流代收】的金额"+rvDetail.get("balanceMoney")+"的订单"+rvDetail.get("refNumber")+",转为【记应收账款】吗",function(){
            
            Ext.Ajax.request({
                url: PSI.Const.BASE_URL + "Home/Funds/changeReceivable",
                params: {
                    id: rvDetail.get("id")
                },
                method: "POST",
                callback: function(options, success, response) {
                    if (success) {
                        var data = Ext.JSON.decode(response.responseText);
                        if(data==1){
                            me.onQuery();
                        }
                        else{
                            PSI.MsgBox.showInfo("数据转换失败")
                        }
                    }
    
                    el.unmask();
                }
            });
        });
    },
    onRvDetailGridSelect: function() {
        var me = this;

        var grid = this.getRvRecordGrid();
        var item = this.getRvDetailGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            grid.setTitle(me.formatGridHeaderTitle("收款记录"));
            return null;
        }

        var rvDetail = item[0];

        grid.setTitle(me.formatGridHeaderTitle(rvDetail.get("refType") +
            " - 单号: " + rvDetail.get("refNumber") + " 的收款记录"));
        grid.getStore().loadPage(1);
    },

    getRvRecordGrid: function() {
        var me = this;
        if (me.__rvRecordGrid) {
            return me.__rvRecordGrid;
        }

        Ext.define("PSIRvRecord", {
            extend: "Ext.data.Model",
            fields: ["id", "actMoney", "bizDate", "bizUserName",
                "inputUserName", "dateCreated", "remark"
            ]
        });

        var store = Ext.create("Ext.data.Store", {
            model: "PSIRvRecord",
            pageSize: 20,
            proxy: {
                type: "ajax",
                actionMethods: {
                    read: "POST"
                },
                url: PSI.Const.BASE_URL + "Home/Funds/rvRecordList",
                reader: {
                    root: 'dataList',
                    totalProperty: 'totalCount'
                }
            },
            autoLoad: false,
            data: []
        });

        store.on("beforeload", function() {
            var rvDetail
            var item = me.getRvDetailGrid().getSelectionModel()
                .getSelection();
            if (item == null || item.length != 1) {
                rvDetail = null;
            } else {
                rvDetail = item[0];
            }

            Ext.apply(store.proxy.extraParams, {
                refType: rvDetail == null ? null : rvDetail
                    .get("refType"),
                refNumber: rvDetail == null ? null : rvDetail
                    .get("refNumber")
            });
        });

        me.__rvRecordGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            viewConfig: {
                enableTextSelection: true
            },
            header: {
                height: 30,
                title: me.formatGridHeaderTitle("收款记录")
            },
            tbar: [{
                text: "录入收款记录",
                iconCls: "PSI-button-add",
                handler: me.onAddRvRecord,
                scope: me
            }],
            bbar: ["->", {
                xtype: "pagingtoolbar",
                border: 0,
                store: store
            }],
            columnLines: true,
            columns: [{
                header: "收款日期",
                dataIndex: "bizDate",
                menuDisabled: true,
                sortable: false,
                width: 80
            }, {
                header: "收款金额",
                dataIndex: "actMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "收款人",
                dataIndex: "bizUserName",
                menuDisabled: true,
                sortable: false,
                width: 80
            }, {
                header: "录入时间",
                dataIndex: "dateCreated",
                menuDisabled: true,
                sortable: false,
                width: 140
            }, {
                header: "录入人",
                dataIndex: "inputUserName",
                menuDisabled: true,
                sortable: false,
                width: 80
            }, {
                header: "备注",
                dataIndex: "remark",
                menuDisabled: true,
                sortable: false,
                width: 150
            }],
            store: store
        });

        return me.__rvRecordGrid;
    },

    onQuery: function() {
        var me = this;
        me.getRvDetailGrid().getStore().removeAll();
        me.getRvRecordGrid().getStore().removeAll();
        me.getRvRecordGrid().setTitle(me.formatGridHeaderTitle("收款记录"));
        
        me.getRvDetailGrid().getStore().loadPage(1);
    },

    onAddRvRecord: function() {
        var me = this;
        var item = me.getRvDetailGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            PSI.MsgBox.showInfo("请选择要做收款记录的业务单据");
            return;
        }

        var rvDetail = item[0];

        var form = Ext.create("PSI.Funds.RvRecordEditForm", {
            parentForm: me,
            rvDetail: rvDetail
        })
        form.show();
    },

    refreshRvInfo: function() {
        var me = this;
        var item = me.getRvGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return;
        }
        var rv = item[0];

        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Funds/refreshRvInfo",
            method: "POST",
            params: {
                id: rv.get("id")
            },
            callback: function(options, success, response) {
                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    rv.set("actMoney", data.actMoney);
                    rv.set("balanceMoney", data.balanceMoney)
                    me.getRvGrid().getStore().commitChanges();
                }
            }

        });
    },

    refreshRvDetailInfo: function() {
        var me = this;
        var item = me.getRvDetailGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return;
        }
        var rvDetail = item[0];

        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Funds/refreshRvDetailInfo",
            method: "POST",
            params: {
                id: rvDetail.get("id")
            },
            callback: function(options, success, response) {
                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    rvDetail.set("actMoney", data.actMoney);
                    rvDetail.set("balanceMoney", data.balanceMoney)
                    me.getRvDetailGrid().getStore().commitChanges();
                }
            }

        });
    },

    onClearQuery: function() {
        var me = this;
        Ext.getCmp("comboCategory").setValue("");
        Ext.getCmp("editCustomerQuery").clearIdValue();
        Ext.getCmp("editBizUser").clearIdValue(),
        Ext.getCmp("editStartDate").setValue(null);
        Ext.getCmp("editEndDate").setValue(null);
        Ext.getCmp("comboCollect").setValue("");
        Ext.getCmp("editCode").setValue("");
        
    }
});