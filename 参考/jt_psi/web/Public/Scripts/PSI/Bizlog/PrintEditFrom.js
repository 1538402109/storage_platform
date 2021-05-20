/**
 * 新增或编辑用户界面
 */
 Ext.define("PSI.Bizlog.PrintEditFrom", {
	extend : "PSI.AFX.BaseDialogForm",

	config : {
		defaultOrg : null
	},

	/**
	 * 初始化组件
	 */
	initComponent : function() {
		var me = this;

		var entity = me.getEntity();
		me.adding = entity == null;

		var t = entity == null ? "打印地址维护" : "打印地址维护";
		var f = entity == null
				? "edit-form-create.png"
				: "edit-form-update.png";
		var logoHtml = "<img style='float:left;margin:10px 20px 0px 10px;width:48px;height:48px;' src='"
				+ PSI.Const.BASE_URL
				+ "Public/Images/"
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
			modal : true,
			onEsc : Ext.emptyFn,
			width : 470,
			height : me.adding ? 400 : 370,
			layout : "border",
			items : [{
				region : "north",
				border : 0,
				height : 90,
				html : logoHtml
			}, {
				region : "center",
				border : 0,
				id : "editForm",
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
					value : entity === null ? null : entity.id
				},{
					id : "editPrintUrl",
					fieldLabel : "打印地址",
					allowBlank : false,
					blankText : "没有输入打印地址",
					beforeLabelTextTpl : PSI.Const.REQUIRED,
					name : "printUrl",
					value : entity === null ? null : entity.printUrl,
					listeners : {
						specialkey : {
							fn : me.onEditSpecialKey,
							scope : me
						}
					},
					width: 370
				}],
				buttons : [{
					text : "确定",
					formBind : true,
					iconCls : "PSI-button-ok",
					handler : me.onOK,
					scope : me
				}, {
					text : "取消",
					handler : function() {
						PSI.MsgBox.confirm("请确认是否取消操作?",
								function() {
									me.close();
								});
					},
					scope : me
				}]
					}],
			listeners : {
				show : {
					fn : me.onWndShow,
					scope : me
				},
				close : {
					fn : me.onWndClose,
					scope : me
				}
			}
		});

		me.callParent(arguments);

		me.__editorList = ["editPrintUrl"];

		if (me.getDefaultOrg()) {
			var org = me.getDefaultOrg();
			me.setOrg({
				id : org.get("id"),
				fullName : org.get("fullName")
			});
		}

		me.editPrintUrl = Ext.getCmp("editPrintUrl");
	},

	onWindowBeforeUnload : function(e) {
		return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
	},

	onWndClose : function() {
		var me = this;

		Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);
	},

	onWndShow : function() {
		var me = this;

		Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);

		// if (me.adding) {
		// 	me.editPrintUrl.focus();
		// 	return;
		// }

		// 下面的是编辑
		var el = me.getEl();
		el.mask(PSI.Const.LOADING);
		Ext.Ajax.request({
			url : me.URL("/Home/Printing/printInfo"),
			// url : PSI.Const.BASE_URL + "Home/Printing/printInfo",
			// params : {
				// id : me.getEntity().id
			// },
			method : "POST",
			callback : function(options, success, response) {
				if (success) {
					var data = Ext.JSON.decode(response.responseText);
					me.editPrintUrl.setValue(data.print_url);
				}

				el.unmask();
			}
		});

		me.editPrintUrl.focus();
		me.editPrintUrl.setValue(me.editPrintUrl.getValue());
	},

	setOrg : function(data) {
		var editOrgName = Ext.getCmp("editOrgName");
		editOrgName.setValue(data.fullName);

		var editOrgId = Ext.getCmp("editOrgId");
		editOrgId.setValue(data.id);
	},

	onOK : function() {
		var me = this;
		var f = Ext.getCmp("editForm");
		var el = f.getEl();
		el.mask("数据保存中...");
		f.submit({
			url : PSI.Const.BASE_URL + "/Home/Printing/printEdit",
			method : "POST",
			success : function(form, action) {
				el.unmask();
				PSI.MsgBox.showInfo("数据保存成功", function() {
					me.close();
					me.getParentForm().freshUserGrid();
				});
			},
			failure : function(form, action) {
				el.unmask();
				PSI.MsgBox.showInfo(action.result.msg, function() {
					Ext.getCmp("editPrintUrl").focus();
				});
			}
		});
	},

	onEditSpecialKey : function(field, e) {
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

	onLastEditSpecialKey : function(field, e) {
		if (e.getKey() === e.ENTER) {
			var f = Ext.getCmp("editForm");
			if (f.getForm().isValid()) {
				this.onOK();
			}
		}
	}
});