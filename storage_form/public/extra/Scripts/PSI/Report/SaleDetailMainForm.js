/**
 * 应收账款明细 主界面
 */
Ext.define("PSI.Report.SaleDetailMainForm", {
    extend: "PSI.AFX.BaseMainExForm",
    config: {
        userName: null,
        userId: null,
        beginDate: null,
        endDate: null,
    },
    initComponent: function() {
        var me = this;





        //
        var store = me.getMainGrid().getStore();

        Ext.apply(me, {
            tbar: [{
                    id: "pagingToobar",
                    cls: "PSI-toolbox",
                    xtype: "pagingtoolbar",
                    border: 0,
                    store: store
                }, "-", {
                    xtype: "displayfield",
                    value: "每页显示"
                }, {
                    id: "comboCountPerPage",
                    cls: "PSI-toolbox",
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
                                    .getCmp("comboCountPerPage")
                                    .getValue();
                                store.currentPage = 1;
                                Ext.getCmp("pagingToobar")
                                    .doRefresh();
                            },
                            scope: me
                        }
                    }
                }, {
                    xtype: "displayfield",
                    value: "条记录"
                }, "-", {
                    id: "editCustomerQuery",
                    fieldLabel: "客&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;户",
                    xtype: "psi_customerfield",
                    showModal: true,
                    labelAlign: "right",
                    labelSeparator: "",
                    labelWidth: 60,
                    margin: "5, 0, 0, 0"
                }, {
                    id: "editStartDate",
                    xtype: "datefield",
                    labelWidth: 60,
                    margin: "5, 0, 0, 0",
                    format: "Y-m-d",
                    labelAlign: "right",
                    labelSeparator: "",
                    fieldLabel: "起始日期",
                    value: me.getBeginDate()
                }, {
                    id: "editEndDate",
                    xtype: "datefield",
                    margin: "5, 0, 0, 0",
                    labelWidth: 60,
                    format: "Y-m-d",
                    labelAlign: "right",
                    labelSeparator: "",
                    fieldLabel: "截止日期",
                    value: me.getEndDate()
                },
                {
                    id: "editBizUser",
                    xtype: "psi_userfield",
                    queryMode: "local",
                    editable: false,
                    valueField: "id",
                    displayField: "name",
                    labelWidth: 60,
                    labelAlign: "right",
                    labelSeparator: "",
                    fieldLabel: "业&nbsp;&nbsp;务&nbsp;&nbsp;员",

                },
                " ", {
                    text: "查询",
                    iconCls: "PSI-button-refresh",
                    handler: me.onQuery,
                    scope: me
                }, {
                    text: "重置查询条件",
                    handler: me.onClearQuery,
                    scope: me
                }, {
                    text: "导出",
                    menu: [{
                        text: "导出Excel",
                        iconCls: "PSI-button-excel",
                        scope: me,
                        handler: me.onExcel
                    }]
                }
            ],
            items: [{
                region: "center",
                layout: "border",
                border: 0,
                items: [{
                    region: "center",
                    layout: "fit",
                    border: 0,
                    items: [me.getMainGrid()]
                }, {
                    region: "south",
                    layout: "fit",
                    height: 100,
                    items: [me.getSummaryGrid()]
                }]
            }]
        });
        //

        me.callParent(arguments);
        me.initParams();
        me.onQuery();

    },

    getMainGrid: function() {
        var me = this;
        if (me.__rvDetailGrid) {
            return me.__rvDetailGrid;
        }

        Ext.define("PSIRvDetail", {
            extend: "Ext.data.Model",
            fields: ["id", "bizDT", "customerName", "goodsCount", "goodsMoney",
                "goodsName", "goodsPrice", "lev2SalePrice", "userCode", 'userName', 'memo', "bname", "reGoodsCount", "reGoodsMoney", "reGoodsPrice"
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
                url: PSI.Const.BASE_URL + "Home/Report/saleAllDetailQueryData",
                reader: {
                    root: 'dataList',
                    totalProperty: 'totalCount'

                }
            },
            autoLoad: true,
            data: []
        });

        store.on("beforeload", function() {

            Ext.apply(store.proxy.extraParams, {
                caType: "customer",
                caId: Ext.getCmp("editCustomerQuery").getIdValue(),
                startDate: Ext.getCmp("editStartDate").getValue(),

                endDate: Ext.getCmp("editEndDate").getValue(),
                editBizUser: Ext.getCmp("editBizUser").getIdValue(),
            });
        });

        me.__rvDetailGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            viewConfig: {
                enableTextSelection: true
            },
            header: {
                height: 30,
                title: me.formatGridHeaderTitle("销售详情")
            },
            bbar: ["->", {
                xtype: "pagingtoolbar",
                border: 0,
                store: store
            }],
            columnLines: true,
            columns: [{
                    header: "业务日期",
                    dataIndex: "bizDT",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "客户",
                    dataIndex: "customerName",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    width: 150
                }, {
                    header: "业务员",
                    dataIndex: "userName",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                }, {
                    header: "商品名称",
                    dataIndex: "goodsName",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    width: 150

                }, {
                    header: "销售数量",
                    dataIndex: "goodsCount",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                {
                    header: "销售单价",
                    dataIndex: "goodsPrice",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                {
                    header: "销售金额",
                    dataIndex: "goodsMoney",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                {
                    header: "退货数量",
                    dataIndex: "reGoodsCount",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                {
                    header: "退货单价",
                    dataIndex: "reGoodsPrice",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                {
                    header: "退货金额",
                    dataIndex: "reGoodsMoney",
                    menuDisabled: true,
                    sortable: false,
                    align: "right"
                },
                // {
                //     header: "成本单价",
                //     dataIndex: "inventoryPrice",
                //     menuDisabled: true,
                //     sortable: false,
                //     align: "right"
                // },
                // {
                //     header: "成本金额",
                //     dataIndex: "inventoryPrice",
                //     menuDisabled: true,
                //     sortable: false,
                //     align: "right"
                // },
                {
                    header: "二批价",
                    dataIndex: "lev2SalePrice",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                }, {
                    header: "备注",
                    dataIndex: "memo",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                }
            ],
            store: store,
            listeners: {
                show: {
                    fn: me.onWndShow,
                    scope: me
                }
            }
        });

        return me.__rvDetailGrid;
    },

    onWndShow: function() {

    },


    getSummaryGrid: function() {

        var me = this;
        if (me.__summaryGrid) {
            return me.__summaryGrid;
        }

        var modelName = "PSIReportSaleMonthByGoodsSummary";
        Ext.define(modelName, {
            extend: "Ext.data.Model",
            fields: ["bizDT", "saleMoney", "rejMoney", "m", "profit",
                "rate", "reSaleMoney", "trueSaleMoney", "lev2SaleMoney"
            ]
        });
        var store = Ext.create("Ext.data.Store", {
            autoLoad: false,
            model: modelName,
            data: []
        });

        me.__summaryGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            header: {
                height: 30,
                title: me.formatGridHeaderTitle("销售汇总(正在研发中)")
            },
            viewConfig: {
                enableTextSelection: true
            },
            border: 0,
            columnLines: true,
            columns: [{
                header: "销售出库金额",
                dataIndex: "saleMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "退货入库金额",
                dataIndex: "reSaleMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "净销售金额",
                dataIndex: "trueSaleMoney",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "毛利",
                dataIndex: "profit",
                menuDisabled: true,
                sortable: false,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "二批利润",
                dataIndex: "lev2SaleMoney",
                menuDisabled: true,
                sortable: false,
                align: "right"
            }],
            store: store
        });

        return me.__summaryGrid;
    },

    onQuery: function() {

        this.refreshMainGrid();
        this.refreshSummaryGrid();
    },
    initParams: function() {
        var me = this;
        Ext.getCmp("editBizUser").setValue(me.getUserName());
        Ext.getCmp("editBizUser").setIdValue(me.getUserId());
    },

    refreshSummaryGrid: function() {
        var me = this;
        var grid = me.getSummaryGrid();
        var el = grid.getEl() || Ext.getBody();
        el.mask(PSI.Const.LOADING);
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Report/saleAllDetailQuerySumData",
            params: me.getQueryParam(),
            method: "POST",
            callback: function(options, success, response) {
                var store = grid.getStore();

                store.removeAll();

                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    store.add(data);
                }

                el.unmask();
            }
        });
    },

    onClearQuery: function() {
        var me = this;

        Ext.getCmp("editStartDate").setValue((new Date()).getFullYear());
        Ext.getCmp("editEndDate").setValue((new Date()).getMonth() + 1);
        Ext.getCmp("editBizUser").setValue('');
        Ext.getCmp("editBizUser").setIdValue('');

        Ext.getCmp("editCustomerQuery").setValue('');
        Ext.getCmp("editCustomerQuery").setIdValue('');
        //Ext.getCmp("editBizUser").setValue();
        me.onQuery();
    },

    getQueryParam: function() {
        var me = this;

        var result = {};

        result.caId = Ext.getCmp("editCustomerQuery").getIdValue(),
            result.startDate = Ext.getCmp("editStartDate").getValue(),
            result.endDate = Ext.getCmp("editEndDate").getValue(),
            result.userId = Ext.getCmp("editBizUser").getIdValue();;
        result.userName = Ext.getCmp("editBizUser").getValue();
        return result;
    },

    refreshMainGrid: function(id) {
        Ext.getCmp("pagingToobar").doRefresh();
    },

    onPrintPreview: function() {
        var lodop = getLodop();
        if (!lodop) {
            PSI.MsgBox.showInfo("没有安装Lodop控件，无法打印");
            return;
        }

        var me = this;

        var store = me.getMainGrid().getStore();
        var sorter = null;
        if (store.sorters.getCount() > 0) {
            sorter = Ext.JSON.encode([store.sorters.getAt(0)]);
        }

        var el = Ext.getBody();
        el.mask("数据加载中...");
        var r = {
            url: PSI.Const.BASE_URL +
                "Home/Report/genSaleMonthByGoodsPrintPage",
            params: {
                year: Ext.getCmp("editQueryYear").getValue(),
                month: Ext.getCmp("editQueryMonth").getValue(),
                sort: sorter,
                limit: -1,
                userId: Ext.getCmp("editBizUser").getIdValue(),
                userName: Ext.getCmp("editBizUser").getValue(),
            },
            callback: function(options, success, response) {
                el.unmask();

                if (success) {
                    var data = response.responseText;
                    me.previewReport("销售月报表(按商品汇总)", data);
                }
            }
        };
        me.ajax(r);
    },

    PRINT_PAGE_WIDTH: "200mm",
    PRINT_PAGE_HEIGHT: "15mm",

    previewReport: function(ref, data) {
        var me = this;

        var lodop = getLodop();
        if (!lodop) {
            PSI.MsgBox.showInfo("Lodop打印控件没有正确安装");
            return;
        }

        lodop.PRINT_INIT(ref);
        lodop.SET_PRINT_PAGESIZE(3, me.PRINT_PAGE_WIDTH, me.PRINT_PAGE_HEIGHT,
            "");
        lodop.ADD_PRINT_HTM("0mm", "0mm", "100%", "100%", data);
        var result = lodop.PREVIEW("_blank");
    },

    onPrint: function() {
        var lodop = getLodop();
        if (!lodop) {
            PSI.MsgBox.showInfo("没有安装Lodop控件，无法打印");
            return;
        }

        var me = this;

        var store = me.getMainGrid().getStore();
        var sorter = null;
        if (store.sorters.getCount() > 0) {
            sorter = Ext.JSON.encode([store.sorters.getAt(0)]);
        }

        var el = Ext.getBody();
        el.mask("数据加载中...");
        var r = {
            url: PSI.Const.BASE_URL +
                "Home/Report/genSaleMonthByGoodsPrintPage",
            params: {
                year: Ext.getCmp("editQueryYear").getValue(),
                month: Ext.getCmp("editQueryMonth").getValue(),
                sort: sorter,
                limit: -1,
                userId: Ext.getCmp("editBizUser").getIdValue(),
                userName: Ext.getCmp("editBizUser").getValue(),
            },
            callback: function(options, success, response) {
                el.unmask();

                if (success) {
                    var data = response.responseText;
                    me.printReport("销售月报表(按商品汇总)", data);
                }
            }
        };
        me.ajax(r);
    },

    printReport: function(ref, data) {
        var me = this;

        var lodop = getLodop();
        if (!lodop) {
            PSI.MsgBox.showInfo("Lodop打印控件没有正确安装");
            return;
        }

        lodop.PRINT_INIT(ref);
        lodop.SET_PRINT_PAGESIZE(3, me.PRINT_PAGE_WIDTH, me.PRINT_PAGE_HEIGHT,
            "");
        lodop.ADD_PRINT_HTM("0mm", "0mm", "100%", "100%", data);
        var result = lodop.PRINT();
    },

    onPDF: function() {
        var me = this;

        var store = me.getMainGrid().getStore();
        var sorter = null;
        if (store.sorters.getCount() > 0) {
            sorter = Ext.JSON.encode([store.sorters.getAt(0)]);
        }

        var year = Ext.getCmp("editQueryYear").getValue();
        var month = Ext.getCmp("editQueryMonth").getValue();
        var userId = Ext.getCmp("editBizUser").getIdValue();
        var userName = Ext.getCmp("editBizUser").getValue();
        var url = "Home/Report/saleMonthByGoodsPdf?limit=-1&year=" + year +
            "&month=" + month + "&userId=" + userId + "&userName=" + userName;
        if (sorter) {
            url += "&sort=" + sorter;
        }

        window.open(me.URL(url));
    },

    onExcel: function() {
        var me = this;

        var store = me.getMainGrid().getStore();
        var sorter = null;
        if (store.sorters.getCount() > 0) {
            sorter = Ext.JSON.encode([store.sorters.getAt(0)]);
        }

        var startDate = Ext.Date
            .format(Ext.getCmp("editStartDate").getValue(), "Y-m-d");
        var endDate = Ext.Date
            .format(Ext.getCmp("editEndDate").getValue(), "Y-m-d");
        var userId = Ext.getCmp("editBizUser").getIdValue();
        var userName = Ext.getCmp("editBizUser").getValue();
        var caId = Ext.getCmp("editCustomerQuery").getIdValue();
        var url = "Home/Report/saleAllDetailQueryExcel?limit=-1&startDate=" + startDate +
            "&endDate=" + endDate + "&userId=" + userId + "&caId=" + caId + "&userName=" + userName;
        if (sorter) {
            url += "&sort=" + sorter;
        }

        window.open(me.URL(url));
    }
});