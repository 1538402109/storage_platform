/**
 * 物流应付账款 - 主界面
 */
Ext.define("PSI.Funds.DiPayMainForm", {
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
            items: [{
                items: [{
                    xtype: "hidden",
                    id: "tmsUrl"
                }, {
                    xtype: "hidden",
                    id: "orgCode"
                }]
            }, {
                region: "center",
                layout: "fit",
                border: 0,
                items: [me.getRvGrid()]
            }, {
                region: "south",
                layout: "border",
                border: 0,
                split: true,
                height: "50%",
                items: [{
                    region: "center",
                    border: 0,
                    layout: "fit",
                    items: [me.getRvDetailGrid()]
                }]
            }]
        });

        me.callParent(arguments);
        me.getTmsUrl();
    },

    getTmsUrl: function() {
        var me = this;
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Sale/GetTmsUrl",
            method: "GET",
            callback: function(options, success, response) {
                var data = Ext.JSON.decode(response.responseText);
                Ext.getCmp("tmsUrl").setValue(data.tmsurl);
                me.getOrgCode();
            }
        });
    },

    getOrgCode: function() {
        var me = this;
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Funds/getOrgCode",
            method: "GET",
            callback: function(options, success, response) {
                var data = Ext.JSON.decode(response.responseText);
                Ext.getCmp("orgCode").setValue(data.orgCode);
                me.onQuery();
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
            fields: ["id", "rv_money",
                "act_money", "balance_money"
            ]
        });

        var store = Ext.create("Ext.data.Store", {
            model: "PSIRv",
            autoLoad: false,
            data: []
        });

        store.on("beforeload", function() {
            Ext.apply(store.proxy.extraParams, {

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
                header: "应付金额",
                dataIndex: "rv_money",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn",
                width: 160
            }, {
                header: "已付金额",
                dataIndex: "act_money",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn",
                width: 160
            }, {
                header: "未付金额",
                dataIndex: "balance_money",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn",
                width: 160
            }],
            store: store
        });

        return me.__rvGrid;
    },

    getRvDetailGrid: function() {
        var me = this;
        if (me.__rvDetailGrid) {
            return me.__rvDetailGrid;
        }

        Ext.define("PSIRvDetail", {
            extend: "Ext.data.Model",
            fields: ["id", "rv_money", "act_money", "balance_money",
                "ref_type", "ref_number", "create_time"
            ]
        });

        var store = Ext.create("Ext.data.Store", {
            model: "PSIRvDetail",
            autoLoad: false,
            data: []
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
                dataIndex: "ref_type",
                menuDisabled: true,
                sortable: false,
                width: 120
            }, {
                header: "单号",
                dataIndex: "ref_number",
                menuDisabled: true,
                sortable: false,
                width: 120
            }, {
                header: "应付金额",
                dataIndex: "rv_money",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "已付金额",
                dataIndex: "act_money",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "未付金额",
                dataIndex: "balance_money",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "创建时间",
                dataIndex: "create_time",
                menuDisabled: true,
                sortable: false,
                width: 140
            }],
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

        var store = me.getRvGrid().getStore();
        Ext.Ajax.request({
            url: Ext.getCmp("tmsUrl").getValue() + "/psiapi/Order/PayAbles?n=" + Ext.getCmp("orgCode").getValue(),
            //url:"http://127.0.0.1:8091/psiapi/Order/PayAbles?n=TTPF",
            method: "GET",
            callback: function(options, success, response) {
                var data = Ext.JSON.decode(response.responseText);
                store.add(data.data.info);
                me.onGetRvDetail(1);
            }
        });

    },

    onGetRvDetail: function(page) {
        var me = this;
        var store = me.getRvDetailGrid().getStore();
        Ext.Ajax.request({
            url: Ext.getCmp("tmsUrl").getValue() + "/psiapi/Order/PayAblesDetail?n=" + Ext.getCmp("orgCode").getValue() + "&page=" + page + "&size=100",
            //url:"http://127.0.0.1:8091/psiapi/Order/PayAblesDetail?n=TTPF&page=1&size=100",
            method: "GET",
            callback: function(options, success, response) {
                var data = Ext.JSON.decode(response.responseText);
                store.add(data.data.items);
            }
        });
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



    onClearQuery: function() {
        var me = this;

        Ext.getCmp("editCustomerQuery").clearIdValue();
        Ext.getCmp("editSupplierQuery").clearIdValue();
        me.onQuery();
    }
});