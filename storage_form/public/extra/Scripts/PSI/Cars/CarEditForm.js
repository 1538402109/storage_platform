/**
 * 车辆管理 - 新增或编辑界面
 */
Ext.define("PSI.Car.CarEditForm", {
	extend : "PSI.AFX.BaseDialogForm",

	initComponent : function() {
		var me = this;
		var entity = me.getEntity();
		me.adding = entity == null;
		var buttons = [];
		if (!entity) {
			buttons.push({
						text : "保存并继续新增",
						formBind : true,
						handler : function() {
							me.onOK(true);
						},
						scope : me
					});
		}

		buttons.push({
					text : "保存",
					formBind : true,
					iconCls : "PSI-button-ok",
					handler : function() {
						me.onOK(false);
					},
					scope : me
				}, {
					text : entity == null ? "关闭" : "取消",
					handler : function() {
						me.close();
					},
					scope : me
				});

		var modelName = "PSIPriceSystem";
		Ext.define(modelName, {
					extend : "Ext.data.Model",
					fields : ["id", "name"]
				});

		var t = entity == null ? "新增车辆" : "编辑车辆";
		var f = entity == null
				? "edit-form-create.png"
				: "edit-form-update.png";
		var logoHtml = "<img style='float:left;margin:10px 20px 0px 10px;width:48px;height:48px;' src='"
				+ PSI.Const.BASE_URL
				+ "Images/"
				+ f
				+ "'></img>"
				+ "<h2 style='color:#196d83'>"
				+ t
				+ "</h2>"
				+ "<p style='color:#196d83'>标记 <span style='color:red;font-weight:bold'>*</span>的是必须录入数据的字段</p>";

		Ext.apply(me, {
			header : {
				title : me.formatTitle(PSI.Const.PROD_NAME),
				height : 40
			},
			width : 400,
			height : 320,
			layout : "border",
			items : [{
						region : "north",
						border : 0,
						height : 80,
						html : logoHtml
					}, {
						region : "center",
						border : 0,
						id : "PSI_Car_CarEditForm_editForm",
						xtype : "form",
						layout : {
							type : "table",
							columns : 1
						},
						height : "100%",
						bodyPadding : 5,
						defaultType : 'textfield',
						fieldDefaults : {
							labelWidth : 60,
							labelAlign : "right",
							labelSeparator : "",
							msgTarget : 'side',
							width : 370,
							margin : "5"
						},
						items : [{
									xtype : "hidden",
									name : "id",
									value : entity == null ? null : entity
											.get("id")
								}, {
									id : "PSI_Car_CarEditForm_editPlateNumber",
									fieldLabel : "车牌号码",
									allowBlank : false,
									blankText : "没有输入车牌号码",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									name : "plantNumber",
									value : entity == null ? null : entity
											.get("plantNumber"),
									listeners : {
										specialkey : {
											fn : me.onEditPlantNumberSpecialKey,
											scope : me
										}
									}
								}, {
									id : "PSI_Car_CarEditForm_editSize",
									fieldLabel : "车辆尺寸",
									name : "size",
									value : entity == null ? null : entity
											.get("size")
								}, {
									id : "PSI_Car_CarEditForm_editType",
									fieldLabel : "车辆型号",
									name : "type",
									value : entity == null ? null : entity
											.get("type")
								},{
									id : "PSI_Car_CarEditForm_editMemo",
									fieldLabel : "备注",
									allowBlank : false,
									blankText : "没有输入备注",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									name : "memo",
									value : entity == null ? null : entity
											.get("memo"),
									listeners : {
										specialkey : {
											fn : me.onEditMemoSpecialKey,
											scope : me
										}
									}
								}, {
									xtype : "hidden",
									name : "psId",
									id : "PSI_Customer_CategoryEditForm_editPsId"
								}],
						buttons : buttons
					}],
			listeners : {
				close : {
					fn : me.onWndClose,
					scope : me
				},
				show : {
					fn : me.onWndShow,
					scope : me
				}
			}
		});

		me.callParent(arguments);

		me.editForm = Ext.getCmp("PSI_Car_CarEditForm_editForm");

		me.editPlateNumber = Ext.getCmp("PSI_Car_CarEditForm_editPlateNumber");
		me.editSize = Ext.getCmp("PSI_Car_CarEditForm_editSize");

		me.editType = Ext.getCmp("PSI_Car_CarEditForm_editType");
		me.editMemo = Ext.getCmp("PSI_Car_CarEditForm_editMemo");
	},

	onOK : function(thenAdd) {
        var me = this;
        var f = me.editForm;
		var el = f.getEl();
		el.mask(PSI.Const.SAVING);
		f.submit({
					url : me.URL("/Home/Cars/editCar"),
					method : "POST",
					success : function(form, action) {
						el.unmask();
						PSI.MsgBox.tip("数据保存成功");
						me.focus();
						me.__lastId = action.result.id;
						if (thenAdd) {
							me.editPlateNumber.setValue(null);
							me.editSize.setValue(null);
							me.editType.setValue(null);
							me.editMemo.setValue(null);
							me.editPlateNumber.focus();

						} else {
							me.close();
						}
					},
					failure : function(form, action) {
						el.unmask();
						PSI.MsgBox.showInfo(action.result.msg, function() {
									me.editPlateNumber.focus();
								});
					}
				});
	},

	onEditPlantNumberSpecialKey : function(field, e) {
		var me = this;
		if (e.getKey() == e.ENTER) {
			var editName = me.editName;
			editName.focus();
			editName.setValue(editName.getValue());
		}
	},

	onEditSizeSpecialKey : function(field, e) {
		var me = this;

		if (e.getKey() == e.ENTER) {
			me.comboPrice.focus();
		}
    },
    onEditTypeSpecialKey : function(field, e) {
		var me = this;

		if (e.getKey() == e.ENTER) {
			me.comboPrice.focus();
		}
	},

	onComboPriceSpecialKey : function(field, e) {
		var me = this;

		if (e.getKey() == e.ENTER) {
			if (me.editForm.getForm().isValid()) {
				me.onOK(me.adding);
			}
		}
	},

	onWindowBeforeUnload : function(e) {
		return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
	},

	onWndClose : function() {
		var me = this;

		Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);

		if (me.__lastId) {
			if (me.getParentForm()) {
				me.getParentForm().freshCategoryGrid(me.__lastId);
			}
		}
	},

	onWndShow : function() {
		var me = this;

		Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);
	}
});