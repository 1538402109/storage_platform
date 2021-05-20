/**
 * 销售月报表(按业务员汇总)
 */
Ext.define("PSI.Report.SaleMonthByBizuserForm", {
    extend: "Ext.window.Window",

    initComponent: function() {
        var me = this;

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
                        id: "editQueryYear",
                        cls: "PSI-toolbox",
                        xtype: "numberfield",
                        margin: "5, 0, 0, 0",
                        labelAlign: "right",
                        labelSeparator: "",
                        fieldLabel: "年",
                        labelWidth: 20,
                        width: 100,
                        value: (new Date()).getFullYear()
                    },
                    valueField: "id",
                    displayFIeld: "text",
                    queryMode: "local",
                    editable: false,
                    value: (new Date()).getMonth() + 1,
                    width: 90
                },
                " ",
                {
                    text: "导出",
                    menu: ["-", {
                        text: "导出Excel",
                        iconCls: "PSI-button-excel",
                        scope: me,
                        handler: me.onExcel
                    }]
                },
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

    me.callParent(arguments);
},

getMainGrid: function() {
    var me = this;
    if (me.__mainGrid) {
        return me.__mainGrid;
    }

    var modelName = "PSIReportSaleMonthByBizuser";
    Ext.define(modelName, {
        extend: "Ext.data.Model",
        fields: ["bizDT", "userCode", "userName", "saleMoney",
            "rejMoney", "m", "profit", "rate", "lev2Money", "exLev2Money", "userId"
        ]
    });
    var store = Ext.create("Ext.data.Store", {
        autoLoad: false,
        model: modelName,
        data: [],
        pageSize: 20,
        remoteSort: true,
        proxy: {
            type: "ajax",
            actionMethods: {
                read: "POST"
            },
            url: PSI.Const.BASE_URL +
                "Home/Report/saleMonthByBizuserQueryData",
            reader: {
                root: 'dataList',
                totalProperty: 'totalCount'
            },
            timeout: 900000,
        }
    });
    store.on("beforeload", function() {
        store.proxy.extraParams = me.getQueryParam();
    });

    me.__mainGrid = Ext.create("Ext.grid.Panel", {
        cls: "PSI",
        viewConfig: {
            enableTextSelection: true
        },
        border: 0,
        columnLines: true,
        columns: [{
                xtype: "rownumberer"
            }, {
                header: "月份",
                dataIndex: "bizDT",
                menuDisabled: true,
                sortable: false,
                width: 80
            }, {
                header: "业务员编码",
                dataIndex: "userCode",
                menuDisabled: true,
                sortable: true
            }, {
                header: "业务员",
                dataIndex: "userName",
                menuDisabled: true,
                sortable: false
            }, {
                header: "销售出库金额",
                dataIndex: "saleMoney",
                menuDisabled: true,
                sortable: true,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "退货入库金额",
                dataIndex: "rejMoney",
                menuDisabled: true,
                sortable: true,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "净销售金额",
                dataIndex: "m",
                menuDisabled: true,
                sortable: true,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "毛利",
                dataIndex: "profit",
                menuDisabled: true,
                sortable: true,
                align: "right",
                xtype: "numbercolumn"
            }, {
                header: "毛利率",
                dataIndex: "rate",
                menuDisabled: true,
                sortable: true,
                align: "right"
            }, {
                header: "二批利润",
                dataIndex: "lev2Money",
                menuDisabled: true,
                sortable: true,
                align: "right"
            }, {
                header: "去二批毛利",
                dataIndex: "exLev2Money",
                menuDisabled: true,
                sortable: true,
                align: "right"
            },
            {
                header: "详情",
                dataIndex: "detail",
                menuDisabled: true,
                sortable: false,
                width: 120,
                renderer: function(value, md, record) {
                    return "<a href='" +
                        PSI.Const.BASE_URL +
                        "Home/Report/saleMonthByBizuser?bizdt=" +
                        encodeURIComponent(record
                            .get("bizDT")) +
                        "&userid=" +
                        encodeURIComponent(record
                            .get("userId")) +
                        "' target='_blank'>详情"
                    "</a>";
                }
            }
        ],
        store: store
    });

    return me.__mainGrid;
},

getSummaryGrid: function() {
    var me = this;
    if (me.__summaryGrid) {
        return me.__summaryGrid;
    }

    var modelName = "PSIReportSaleMonthByBizuserSummary";
    Ext.define(modelName, {
        extend: "Ext.data.Model",
        fields: ["bizDT", "saleMoney", "rejMoney", "m", "profit",
            "rate"
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
            title: me.formatGridHeaderTitle("月销售汇总")
        },
        viewConfig: {
            enableTextSelection: true
        },
        border: 0,
        columnLines: true,
        columns: [{
            header: "月份",
            dataIndex: "bizDT",
            menuDisabled: true,
            sortable: false,
            width: 80
        }, {
            header: "销售出库金额",
            dataIndex: "saleMoney",
            menuDisabled: true,
            sortable: false,
            align: "right",
            xtype: "numbercolumn"
        }, {
            header: "退货入库金额",
            dataIndex: "rejMoney",
            menuDisabled: true,
            sortable: false,
            align: "right",
            xtype: "numbercolumn"
        }, {
            header: "净销售金额",
            dataIndex: "m",
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
            header: "毛利率",
            dataIndex: "rate",
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

refreshSummaryGrid: function() {
    var me = this;
    var grid = me.getSummaryGrid();
    var el = grid.getEl() || Ext.getBody();
    el.mask(PSI.Const.LOADING);
    Ext.Ajax.request({
        url: PSI.Const.BASE_URL +
            "Home/Report/saleMonthByBizuserSummaryQueryData",
        params: me.getQueryParam(),
        method: "POST",
        timeout: 900000,
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

    Ext.getCmp("editQueryYear").setValue((new Date()).getFullYear());
    Ext.getCmp("editQueryMonth").setValue((new Date()).getMonth() + 1);

    me.onQuery();
},

getQueryParam: function() {
    var me = this;

    var result = {};

    var year = Ext.getCmp("editQueryYear").getValue();
    if (year) {
        result.year = year;
    } else {
        year = (new Date()).getFullYear();
        Ext.getCmp("editQueryYear").setValue(year);
        result.year = year;
    }

    var month = Ext.getCmp("editQueryMonth").getValue();
    if (month) {
        result.month = month;
    } else {
        month = (new Date()).getMonth() + 1;
        Ext.getCmp("editQueryMonth").setValue(month);
        result.month = month;
    }

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
            "Home/Report/genSaleMonthByBizuserPrintPage",
        params: {
            year: Ext.getCmp("editQueryYear").getValue(),
            month: Ext.getCmp("editQueryMonth").getValue(),
            sort: sorter,
            limit: -1
        },
        callback: function(options, success, response) {
            el.unmask();

            if (success) {
                var data = response.responseText;
                me.previewReport("销售月报表(按业务员汇总)", data);
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
            "Home/Report/genSaleMonthByBizuserPrintPage",
        params: {
            year: Ext.getCmp("editQueryYear").getValue(),
            month: Ext.getCmp("editQueryMonth").getValue(),
            sort: sorter,
            limit: -1
        },
        callback: function(options, success, response) {
            el.unmask();

            if (success) {
                var data = response.responseText;
                me.printReport("销售月报表(按业务员汇总)", data);
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

    var url = "Home/Report/saleMonthByBizuserPdf?limit=-1&year=" + year +
        "&month=" + month;
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

    var year = Ext.getCmp("editQueryYear").getValue();
    var month = Ext.getCmp("editQueryMonth").getValue();

    var url = "Home/Report/saleMonthByBizuserExcel?limit=-1&year=" + year +
        "&month=" + month;
    if (sorter) {
        url += "&sort=" + sorter;
    }

    window.open(me.URL(url));
}
});