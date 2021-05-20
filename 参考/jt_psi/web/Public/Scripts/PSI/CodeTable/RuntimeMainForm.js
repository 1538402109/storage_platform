//
// 码表运行- 主界面
//
Ext.define("PSI.CodeTable.RuntimeMainForm", {
			extend : "PSI.AFX.BaseMainExForm",
			border : 0,

			config : {
				fid : null
			},

			initComponent : function() {
				var me = this;

				Ext.apply(me, {
							tbar : {
								id : "PSI_CodeTable_RuntimeMainForm_toolBar",
								xtype : "toolbar"
							},
							layout : "border",
							items : [{
										region : "center",
										id : "PSI_CodeTable_RuntimeMainForm_panelMain",
										layout : "fit",
										border : 0,
										items : []
									}]
						});

				me.callParent(arguments);

				me.__toolBar = Ext
						.getCmp("PSI_CodeTable_RuntimeMainForm_toolBar");
				me.__panelMain = Ext
						.getCmp("PSI_CodeTable_RuntimeMainForm_panelMain");

				me.fetchMeatData();
			},

			getMetaData : function() {
				return this.__md;
			},

			fetchMeatData : function() {
				var me = this;
				var el = me.getEl();
				el && el.mask(PSI.Const.LOADING);
				me.ajax({
							url : me
									.URL("Home/CodeTable/getMetaDataForRuntime"),
							params : {
								fid : me.getFid()
							},
							callback : function(options, success, response) {
								if (success) {
									var data = me
											.decodeJSON(response.responseText);

									me.__md = data;

									me.initUI();
								}

								el && el.unmask();
							}
						});
			},

			initUI : function() {
				var me = this;

				var md = me.getMetaData();
				if (!md) {
					return;
				}

				var name = md.name;
				if (!name) {
					return;
				}

				// 按钮
				var toolBar = me.__toolBar;
				toolBar.add([{
							text : "新增" + name,
							id : "buttonAddCodeTableRecord",
							handler : me.onAddCodeTableRecord,
							scope : me
						}, {
							text : "编辑" + name,
							id : "buttonEditCodeTableRecord",
							handler : me.onEditCodeTableRecord,
							scope : me
						}, {
							text : "删除" + name,
							id : "buttonDeleteCodeTableRecord",
							handler : me.onDeleteCodeTableRecord,
							scope : me
						}, "-", {
							text : "关闭",
							handler : function() {
								me.closeWindow();
							}
						}]);

				// MainGrid
				var modelName = "PSICodeTableRuntime_" + md.tableName;

				var fields = [];
				var cols = [];
				var colsLength = md.cols.length;
				for (var i = 0; i < colsLength; i++) {
					var mdCol = md.cols[i];

					fields.push(mdCol.fieldName);
					if (mdCol.isVisible) {
						cols.push({
									header : mdCol.caption,
									dataIndex : mdCol.fieldName,
									width : parseInt(mdCol.widthInView),
									menuDisabled : true,
									sortable : false
								});
					}
				}

				Ext.define(modelName, {
							extend : "Ext.data.Model",
							fields : fields
						});

				me.__mainGrid = Ext.create("Ext.grid.Panel", {
							cls : "PSI",
							viewConfig : {
								enableTextSelection : true
							},
							columnLines : true,
							border : 0,
							columns : cols,
							store : Ext.create("Ext.data.Store", {
										model : modelName,
										autoLoad : false,
										data : []
									})
						});
				me.__panelMain.add(me.__mainGrid);

				me.refreshMainGrid();
			},

			onAddCodeTableRecord : function() {
				var me = this;

				var form = Ext.create("PSI.CodeTable.RuntimeEditForm", {
							parentForm : me,
							metaData : me.getMetaData()
						});

				form.show();
			},

			onEditCodeTableRecord : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onDeleteCodeTableRecord : function() {
				var me = this;
				me.showInfo("TODO");
			},

			getMainGrid : function() {
				return this.__mainGrid;
			},

			refreshMainGrid : function(id) {
				var me = this;

				var grid = me.getMainGrid();
				var el = grid.getEl() || Ext.getBody();
				el.mask(PSI.Const.LOADING);
				var r = {
					url : me.URL("Home/CodeTable/codeTableRecordList"),
					params : {
						fid : me.getFid()
					},
					callback : function(options, success, response) {
						var store = grid.getStore();

						store.removeAll();

						if (success) {
							var data = me.decodeJSON(response.responseText);
							store.add(data);

							if (store.getCount() > 0) {
								if (id) {
									var r = store.findExact("id", id);
									if (r != -1) {
										grid.getSelectionModel().select(r);
									}
								} else {
									grid.getSelectionModel().select(0);
								}
							}
						}

						el.unmask();
					}
				};

				me.ajax(r);
			}
		});