/**
 * 自定义字段 - 商品字段，带销售价格
 */
Ext.define("PSI.Goods.GoodsWithSalePriceField", {
    extend: "Ext.form.field.Trigger",
    alias: "widget.psi_goods_with_saleprice_field",

    config: {
        parentCmp: null,
        editCustomerName: null
    },

    /**
     * 初始化组件
     */
    initComponent: function() {
        var me = this;

        me.enableKeyEvents = true;

        me.callParent(arguments);

        me.on("keydown", function(field, e) {
            if (e.getKey() == e.BACKSPACE) {
                field.setValue(null);
                e.preventDefault();
                return false;
            }

            if (e.getKey() != e.ENTER && !e.isSpecialKey(e.getKey())) {
                this.onTriggerClick(e);
            }
        });

        me.on({
            render: function(p) {
                p.getEl().on("dblclick", function() {
                    me.onTriggerClick();
                });
            },
            single: true
        });
    },

    /**
     * 单击下拉组件
     */
    onTriggerClick: function(e) {
        var me = this;
        var modelName = "PSIGoodsField";
        Ext.define(modelName, {
            extend: "Ext.data.Model",
            fields: ["id", "code", "name", "spec", "unitName",
                "salePrice", "lastPrice", "memo", "priceSystem", "taxRate",
                "unit2Decimal", "unit3Decimal", "unit2Name", "unit3Name", "salePrice", "salePrice2", "salePrice3", "locality", "guaranteeDay", "balanceCount", "batchDate", "batchDateObj"
            ]
        });

        var store = Ext.create("Ext.data.Store", {
            model: modelName,
            autoLoad: false,
            data: []
        });
        var lookupGrid = Ext.create("Ext.grid.Panel", {
            cls: "PSI",
            columnLines: true,
            border: 0,
            store: store,
            columns: [{
                    header: "编码",
                    dataIndex: "code",
                    menuDisabled: true,
                    width: 40
                }, {
                    header: "商品",
                    dataIndex: "name",
                    menuDisabled: true,

                    width: 320
                }, {
                    header: "规格型号",
                    dataIndex: "spec",
                    menuDisabled: true,
                    flex: 1,
                    width: 80
                }, {
                    header: "单位",
                    dataIndex: "unitName",
                    menuDisabled: true,
                    width: 60
                }, {
                    header: "销售价",
                    dataIndex: "salePrice",
                    menuDisabled: true,
                    align: "right",
                    xtype: "numbercolumn"
                }, {
                    header: "前次销售价",
                    dataIndex: "lastPrice",
                    menuDisabled: true,
                    align: "right"
                },
                {
                    header: "现有库存",
                    dataIndex: "balanceCount",
                    menuDisabled: true,
                    align: "right",
                    xtype: "numbercolumn"
                },
                {
                    header: "备注",
                    dataIndex: "memo",
                    menuDisabled: true,
                },

            ]
        });
        me.lookupGrid = lookupGrid;
        me.lookupGrid.on("itemdblclick", me.onOK, me);

        var wnd = Ext.create("Ext.window.Window", {
            title: "选择 - 商品",
            header: false,
            border: 0,
            width: 950,
            height: 300,
            layout: "border",
            items: [{
                region: "center",
                xtype: "panel",
                layout: "fit",
                border: 0,
                items: [lookupGrid]
            }, {
                xtype: "panel",
                region: "south",
                height: 90,
                layout: "fit",
                border: 0,
                items: [{
                    xtype: "form",
                    layout: "form",
                    bodyPadding: 5,
                    items: [{
                        id: "__editGoods",
                        xtype: "textfield",
                        fieldLabel: "商品",
                        labelWidth: 50,
                        labelAlign: "right",
                        labelSeparator: ""
                    }, {
                        xtype: "displayfield",
                        fieldLabel: " ",
                        value: "输入编码、商品名称拼音字头、规格型号拼音字头可以过滤查询",
                        labelWidth: 50,
                        labelAlign: "right",
                        labelSeparator: ""
                    }, {
                        xtype: "displayfield",
                        fieldLabel: " ",
                        value: "↑ ↓ 键改变当前选择项 ；回车键返回",
                        labelWidth: 50,
                        labelAlign: "right",
                        labelSeparator: ""
                    }]
                }]
            }],
            buttons: [{
                text: "确定",
                handler: me.onOK,
                scope: me
            }, {
                text: "取消",
                handler: function() {
                    wnd.close();
                }
            }]
        });

        var customerId = null;
        var editCustomer = Ext.getCmp(me.getEditCustomerName());
        if (editCustomer) {
            customerId = editCustomer.getIdValue();
        }

        wnd.on("close", function() {
            me.focus();
        });
        wnd.on("deactivate", function() {
            wnd.close();
        });

        me.wnd = wnd;

        var editName = Ext.getCmp("__editGoods");

        var isWaiting = false;
        var hasTime = false;
        editName.on("change", function() {
            var store = me.lookupGrid.getStore();
            if (isWaiting) {
                return;
            } else {
                if (hasTime) {
                    return;
                } else {
                    hasTime = true;
                    isWaiting = true;
                    setTimeout(() => {
                        hasTime = false;
                        isWaiting = false;
                        Ext.Ajax.request({
                            url: PSI.Const.BASE_URL +
                                "Home/Goods/queryDataWithSalePrice",
                            params: {
                                queryKey: editName.getValue(),
                                customerId: customerId
                            },
                            method: "POST",
                            callback: function(opt, success, response) {
                                store.removeAll();
                                if (success) {
                                    var data = Ext.JSON
                                        .decode(response.responseText);
                                    store.add(data);
                                    if (data.length > 0) {
                                        me.lookupGrid.getSelectionModel().select(0);
                                        editName.focus();
                                    }
                                } else {
                                    PSI.MsgBox.showInfo("网络错误");
                                }
                            },
                            scope: this
                        });
                    }, 1000);

                }
            }



        }, me);

        editName.on("specialkey", function(field, e) {
            if (e.getKey() == e.ENTER) {
                me.onOK();
            } else if (e.getKey() == e.UP) {
                var m = me.lookupGrid.getSelectionModel();
                var store = me.lookupGrid.getStore();
                var index = 0;
                for (var i = 0; i < store.getCount(); i++) {
                    if (m.isSelected(i)) {
                        index = i;
                    }
                }
                index--;
                if (index < 0) {
                    index = 0;
                }
                m.select(index);
                e.preventDefault();
                editName.focus();
            } else if (e.getKey() == e.DOWN) {
                var m = me.lookupGrid.getSelectionModel();
                var store = me.lookupGrid.getStore();
                var index = 0;
                for (var i = 0; i < store.getCount(); i++) {
                    if (m.isSelected(i)) {
                        index = i;
                    }
                }
                index++;
                if (index > store.getCount() - 1) {
                    index = store.getCount() - 1;
                }
                m.select(index);
                e.preventDefault();
                editName.focus();
            }
        }, me);

        me.wnd.on("show", function() {
            editName.focus();
            editName.fireEvent("change");
        }, me);
        wnd.showBy(me);
    },

    onOK: function() {
        var me = this;
        var grid = me.lookupGrid;
        var item = grid.getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return;
        }

        var data = item[0].getData();

        me.wnd.close();
        me.focus();
        me.setValue(data.code);
        me.focus();

        if (me.getParentCmp() && me.getParentCmp().__setGoodsInfo) {
            me.getParentCmp().__setGoodsInfo(data)
        }
    }
});