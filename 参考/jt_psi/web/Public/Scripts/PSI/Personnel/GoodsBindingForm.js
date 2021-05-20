/**
 * 销售订单 - 新增或编辑界面
 * 
 * @author JIATU
 */
Ext.define("PSI.Personnel.GoodsBindingForm", {
    extend: "PSI.AFX.BaseDialogForm",

    config: {
        // genBill - true的时候，是从销售合同生成销售订单
        genBill: false,
        scbillRef: null
    },
    initComponent: function() {
        var me = this;
        me.__readOnly = false;
        var entity = me.getEntity();
        this.adding = entity == null;
        var title = entity == null ? "人员业务绑定" : "人员业务绑定";
        title = me.formatTitle(title);
        var iconCls = entity == null ? "PSI-button-add" : "PSI-button-edit";

        Ext.apply(me, {
            header: {
                title: title,
                height: 40,
                iconCls: iconCls
            },
            defaultFocus: "editCustomer",
            modal : true,
			onEsc : Ext.emptyFn,
			width : 605,
			height : me.adding ? 400 : 370,
            layout: "border",
            tbar: [{
                text: "保存",
                id: "buttonSave",
                iconCls: "PSI-button-ok",
                handler: me.onOK,
                scope: me
            }, "-", {
                text: "取消",
                id: "buttonCancel",
                handler: function() {
                    if (me.__readonly) {
                        me.close();
                        return;
                    }

                    PSI.MsgBox.confirm("请确认是否取消当前操作？", function() {
                        me.close();
                    });
                },
                scope: me
            }],
            items: [{
                region: "center",
                layout: "fit",
                border: 0,
                bodyPadding: 10,
                items: [me.getGoodsGrid()]
            }, {
                region: "north",
                id: "editForm",
                layout: {
                    type: "table",
                    columns: 4
                },
                height: 40,
                bodyPadding: 10,
                border: 0,
                items: [{
                    xtype: "hidden",
                    id: "hiddenId",
                    name: "id",
                    value: entity == null ? null : entity.id
                }, {
                        id: "editBizUser",
                        labelWidth: 60,
                        labelAlign: "right",
                        labelSeparator: "",
                        fieldLabel: "业务员",
                        xtype: "textfield",
                        allowBlank: false,
                        blankText: "没有输入业务员",
                        beforeLabelTextTpl: PSI.Const.REQUIRED,
                        listeners: {
                            specialkey: {
                                fn: me.onEditSpecialKey,
                                scope: me
                            }
                        }
                    }
                ]
            }],
            listeners: {
                show: {
                    fn: me.onWndShow,
                    scope: me
                },
                close: {
                    fn: me.onWndClose,
                    scope: me
                }
            }
        });

        me.callParent(arguments);

        me.__editorList = ["editDealDate", "editCustomer", "editDealAddress",
            "editContact", "editTel", "editFax", "editOrg", "editBizUser",
            "editReceivingType", "editBillMemo"
        ];
    },

    onWindowBeforeUnload: function(e) {
        return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
    },
    
    onWndClose: function() {
        // 加上这个调用是为了解决 #IMQB2 - https://gitee.com/jtbb/jt_psi.git/issues/IMQB2
        // 这个只是目前的临时应急方法，实现的太丑陋了
        Ext.WindowManager.hideAll();

        Ext.get(window).un('beforeunload', this.onWindowBeforeUnload);
    },


    onWndShow: function() {

        Ext.get(window).on('beforeunload', this.onWindowBeforeUnload);

        var me = this;

        var el = me.getEl() || Ext.getBody();
        el.mask(PSI.Const.LOADING);
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Personnel/goodsBindingInfo",
            params: {
                id: Ext.getCmp("hiddenId").getValue(),
            },
            method: "POST",
            callback: function(options, success, response) {
                el.unmask();
                if (success) {
                    var data = Ext.JSON.decode(response.responseText);

                    Ext.getCmp("editBizUser").setValue(data[0]['login_name']);
                    var store = me.getGoodsGrid().getStore();
                    store.removeAll();
                    if (data.items) {
                        store.add(data.items);
                    }
                    if (store.getCount() == 0) {
                        store.add({});
                    }

                    if (data.billStatus && data.billStatus != 0) {
                        me.setBillReadonly();
                    }
                }
            }
        });
    },

    
    onEditSpecialKey: function(field, e) {
        if (e.getKey() === e.ENTER) {
            var me = this;
            var id = field.getId();
            for (var i = 0; i < me.__editorList.length; i++) {
                var editorId = me.__editorList[i];
                if (id === editorId) {
                    var edit = Ext.getCmp(me.__editorList[i + 1]);
                    edit.focus();
                    edit.setValue(edit.getValue());
                }
            }
        }
    },

    onLastEditSpecialKey: function(field, e) {
        if (this.__readonly) {
            return;
        }

        if (e.getKey() == e.ENTER) {
            var me = this;
            var store = me.getGoodsGrid().getStore();
            if (store.getCount() == 0) {
                store.add({});
            }
            me.getGoodsGrid().focus();
            me.__cellEditing.startEdit(0, 1);
        }
    },

    getGoodsGrid: function() {
        var me = this;
        if (me.__goodsGrid) {
            return me.__goodsGrid;
        }
        var modelName = "GoodsBinding";
        Ext.define(modelName, {
            extend: "Ext.data.Model",
            fields: ["id", "goodsId", "goodsCode", "goodsName",
                "goodsSpec", "unitName", "goodsCount", {
                    name: "goodsMoney",
                    type: "float"
                }, "goodsPrice", {
                    name: "taxRate",
                    type: "int"
                }, {
                    name: "tax",
                    type: "float"
                }, {
                    name: "moneyWithTax",
                    type: "float"
                }, "memo", "scbillDetailId", "unitResult", "unit2Name", "unit3Name", "unit2Decimal", "unit3Decimal", "locatily", "salePrice", "salePrice2", "salePrice3", "guaranteeDay"
            ]
        });
        var store = Ext.create("Ext.data.Store", {
            autoLoad: false,
            model: modelName,
            data: []
        });

        me.__cellEditing = Ext.create("PSI.UX.CellEditing", {
            clicksToEdit: 1,
            listeners: {
                beforeedit: {
                    fn: me.cellEditingBeforeEdit,
                    scope: me
                },
                edit: {
                    fn: me.cellEditingAfterEdit,
                    scope: me
                }
            }
        });

        me.__goodsGrid = Ext.create("Ext.grid.Panel", {
            viewConfig: {
                enableTextSelection: true,
                markDirty: !me.adding
            },
            // features: [{
            //     ftype: "summary"
            // }],
            plugins: [me.__cellEditing],
            columnLines: true,
            columns: [{
                    xtype: "rownumberer",
                    width: 40
                }, {
                    header: "商品编码",
                    dataIndex: "goodsCode",
                    menuDisabled: true,
                    sortable: false,
                    draggable: false,
                    editor: {
                        xtype: "psi_goods_with_saleprice_field",
                        parentCmp: me,
                        editCustomerName: "editCustomer"
                    }
                }, {
                    header: "商品名称",
                    dataIndex: "goodsName",
                    menuDisabled: true,
                    sortable: false,
                    draggable: false,
                    width: 300,
                    editor: {
                        xtype: "psi_goods_with_saleprice_field",
                        parentCmp: me,
                        editCustomerName: "editCustomer"
                    }
                },
                {
                    header: "",
                    id: "columnActionDelete",
                    align: "center",
                    menuDisabled: true,
                    draggable: false,
                    width: 40,
                    xtype: "actioncolumn",
                    items: [{
                        icon: PSI.Const.BASE_URL +
                            "Public/Images/icons/delete.png",
                        tooltip: "删除当前记录",
                        handler: function(grid, row) {
                            var store = grid.getStore();
                            store.remove(store.getAt(row));
                            if (store.getCount() == 0) {
                                store.add({});
                            }
                        },
                        scope: me
                    }]
                }, {
                    header: "",
                    id: "columnActionAdd",
                    align: "center",
                    menuDisabled: true,
                    draggable: false,
                    width: 40,
                    xtype: "actioncolumn",
                    items: [{
                        icon: PSI.Const.BASE_URL +
                            "Public/Images/icons/insert.png",
                        tooltip: "在当前记录之前插入新记录",
                        handler: function(grid, row) {
                            var store = grid.getStore();
                            store.insert(row, [{}]);
                        },
                        scope: me
                    }]
                }, {
                    header: "",
                    id: "columnActionAppend",
                    align: "center",
                    menuDisabled: true,
                    draggable: false,
                    hidden:false,
                    width: 40,
                    xtype: "actioncolumn",
                    items: [{
                        icon: PSI.Const.BASE_URL +
                            "Public/Images/icons/add.png",
                        tooltip: "在当前记录之后新增记录",
                        handler: function(grid, row) {
                            var store = grid.getStore();
                            store.insert(row + 1, [{}]);
                        },
                        scope: me
                    }]
                }
            ],
            store: store,
            listeners: {
                cellclick: function() {
                    return !me.__readonly;
                }
            }
        });

        return me.__goodsGrid;
    },
    
    onOK: function() {
        var me = this;
        Ext.getBody().mask("正在保存中...");
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Personnel/Binding",
            method: "POST",
            params: {
                jsonStr: me.getSaveData()
            },
            callback: function(options, success, response) {
                Ext.getBody().unmask();

                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    if (data.success) {
                        PSI.MsgBox.showInfo("成功保存数据", function() {
                            me.close();
                            // me.getParentForm().refreshMainGrid(data.id);
                        });
                    } else {
                        PSI.MsgBox.showInfo(data.msg);
                    }
                }
            }
        });
    },

    

    // xtype:psi_goods_with_saleprice_field回调本方法
    // 参见PSI.Goods.GoodsWithSalePriceField的onOK方法
    __setGoodsInfo: function(data) {
        var me = this;
        var item = me.getGoodsGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return;
        }
        var goods = item[0];

        goods.set("goodsId", data.id);
        goods.set("goodsCode", data.code);
        goods.set("goodsName", data.name);
        goods.set("unitName", data.unitName);
        goods.set("goodsSpec", data.spec);
        goods.set("goodsPrice", data.salePrice);
        goods.set("taxRate", data.taxRate);
        goods.set("unit2Name", data.unit2Name);
        goods.set("unit2Decimal", data.unit2Decimal);
        goods.set("unit3Name", data.unit3Name);
        goods.set("unit3Decimal", data.unit3Decimal);
        goods.set("salePrice", data.salePrice);
        goods.set("salePrice2", data.salePrice2);
        goods.set("salePrice3", data.salePrice3);
        goods.set("locality", data.locality);
        goods.set("guaranteeDay", data.guaranteeDay);

        me.calcMoney(goods);
    },

    cellEditingAfterEdit: function(editor, e) {
        var me = this;

        if (me.__readonly) {
            return;
        }

        var fieldName = e.field;
        var goods = e.record;
        var oldValue = e.originalValue;
        if (fieldName == "memo") {
            var store = me.getGoodsGrid().getStore();
            if (!me.getGenBill() && (e.rowIdx == store.getCount() - 1)) {
                store.add({});
                var row = e.rowIdx + 1;
                me.getGoodsGrid().getSelectionModel().select(row);
                me.__cellEditing.startEdit(row, 1);
            }
        } else if (fieldName == "moneyWithTax") {
            if (goods.get(fieldName) != (new Number(oldValue)).toFixed(2)) {
                me.calcTax(goods);
            }
        } else if (fieldName == "tax") {
            if (goods.get(fieldName) != (new Number(oldValue)).toFixed(2)) {
                me.calcMoneyWithTax(goods);
            }
        } else if (fieldName == "goodsMoney") {
            if (goods.get(fieldName) != (new Number(oldValue)).toFixed(2)) {
                me.calcPrice(goods);
            }
        } else if (fieldName == "goodsCount") {
            if (goods.get(fieldName) != oldValue) {
                me.calcMoney(goods);
            }
        } else if (fieldName == "goodsPrice") {
            if (isNaN(goods.get(fieldName))) {
                goods.set(fieldName, (new Number(oldValue)).toFixed(2));
            }
            if (goods.get(fieldName) != (new Number(oldValue)).toFixed(2)) {
                me.calcMoney(goods);
            }
        }
    },

    calcMoney: function(goods) {
        if (!goods) {
            return;
        }

        goods.set("goodsMoney", goods.get("goodsCount") *
            goods.get("goodsPrice"));
        var unitResult = '';

        goods.set("tax", goods.get("goodsMoney") * goods.get("taxRate") / 100);
        goods.set("moneyWithTax", goods.get("goodsMoney") + goods.get("tax"));

        //处理单位格式为 1箱3包5袋这样的格式
        var tempCount = goods.get("goodsCount");
        if (goods.get("unit3Decimal") > 1) {
            var unit3count = Math.floor(goods.get("goodsCount") / goods.get("unit3Decimal"))
            if (unit3count > 0) {
                unitResult += unit3count + goods.get("unit3Name");
                tempCount = goods.get("goodsCount") % goods.get("unit3Decimal");
            }
        }
        if (goods.get("unit2Decimal") > 1) {
            var unit2count = Math.floor(tempCount / goods.get("unit2Decimal"))
            if (unit2count > 0) {
                unitResult += unit2count + goods.get("unit2Name")
                tempCount = tempCount % goods.get("unit2Decimal");
            }
        }
        if (tempCount > 0) {
            unitResult += tempCount + goods.get("unitName")
        }
        goods.set("unitResult", unitResult);
    },

    cellEditingBeforeEdit: function(editor, e) {
        var me = this;
        var fieldName = e.field;

        if (fieldName == "goodsCode") {
            if (me.getGenBill()) {
                // 当由销售合同创建销售订单的时候，不允许修改商品
                return false;
            }
        }
        if (fieldName == "goodsPrice") {
            var store = me.getGoodsGrid().getStore();
            var item = store.getAt(e.rowIdx);
            if (item) {
                var temp = [];
                temp.push(item.get("salePrice"));
                if (item.get("salePrice2")) {
                    temp.push(item.get("salePrice2"));
                }
                if (item.get("salePrice3")) {
                    temp.push(item.get("salePrice3"));
                }
                me.getGoodsGrid().columns[e.colIdx].setEditor(new Ext.form.field.ComboBox({
                    typeAhead: true,
                    triggerAction: 'all',
                    store: temp

                }));
            }

        }
    },
    getSaveData: function() {
        var me = this;
        var result = {
            id: Ext.getCmp("hiddenId").getValue(),
            // dealDate: Ext.Date.format(Ext.getCmp("editDealDate").getValue(),
            //     "Y-m-d"),
            // customerId: Ext.getCmp("editCustomer").getIdValue(),
            // dealAddress: Ext.getCmp("editDealAddress").getValue(),
            // contact: Ext.getCmp("editContact").getValue(),
            // tel: Ext.getCmp("editTel").getValue(),
            // fax: Ext.getCmp("editFax").getValue(),
            // orgId: Ext.getCmp("editOrg").getIdValue(),
            bizUserId: Ext.getCmp("editBizUser").getValue(),
            // receivingType: Ext.getCmp("editReceivingType").getValue(),
            // billMemo: Ext.getCmp("editBillMemo").getValue(),
            // distributionType: Ext.getCmp("editDistributionType").getValue(),
            // scbillRef: me.getScbillRef(),
            items: []
        };

        var store = this.getGoodsGrid().getStore();
        for (var i = 0; i < store.getCount(); i++) {
            var item = store.getAt(i);
            result.items.push({
                id: item.get("id"),
                goodsId: item.get("goodsId"),
                goodsName:item.get('goodsName'),
                goodsCode:item.get('goodsCode'),
                // goodsCount: item.get("goodsCount"),
                // goodsPrice: item.get("goodsPrice"),
                // goodsMoney: item.get("goodsMoney"),
                // unitResult: item.get("unitResult"),
                // tax: item.get("tax"),
                // taxRate: item.get("taxRate"),
                // moneyWithTax: item.get("moneyWithTax"),
                // memo: item.get("memo"),
                // scbillDetailId: item.get("scbillDetailId")
            });
        }

        return Ext.JSON.encode(result);
    },

    setBillReadonly: function() {
        var me = this;
        me.__readonly = true;
        me.setTitle("<span style='font-size:160%;'>查看销售订单</span>");
        Ext.getCmp("buttonSave").setDisabled(true);
        Ext.getCmp("buttonCancel").setText("关闭");
        Ext.getCmp("editDealDate").setReadOnly(true);
        Ext.getCmp("editCustomer").setReadOnly(true);
        Ext.getCmp("editDealAddress").setReadOnly(true);
        Ext.getCmp("editContact").setReadOnly(true);
        Ext.getCmp("editTel").setReadOnly(true);
        Ext.getCmp("editFax").setReadOnly(true);
        Ext.getCmp("editOrg").setReadOnly(true);
        Ext.getCmp("editBizUser").setReadOnly(true);
        Ext.getCmp("editReceivingType").setReadOnly(true);
        Ext.getCmp("editDistributionType").setReadOnly(true);
        Ext.getCmp("editBillMemo").setReadOnly(true);

        Ext.getCmp("columnActionDelete").hide();
        Ext.getCmp("columnActionAdd").hide();
        Ext.getCmp("columnActionAppend").hide();
    },
});