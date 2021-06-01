<html>
<body>
	<table border=0 cellSpacing=0 cellPadding=0 width="100%">
		<tr>
			<td colspan="6" align="center">销售退货入库单</td>
		</tr>
		<tr>
			<td width="12%"><span style='font-size: 13'>单号</span></td>
			<td width="15%"><span style='font-size: 13'>{$data["ref"]}</span></td>
			<td width="12%"><span style='font-size: 13'>业务日期</span></td>
			<td width="15%"><span style='font-size: 13'>{$data["bizDT"]}</span></td>
			<td width="12%"><span style='font-size: 13'>打印时间</span></td>
			<td><span style='font-size: 13'>{$data["printDT"]}</span></td>
		</tr>
		<tr>
			<td><span style='font-size: 13'>客户</span></td>
			<td colspan="3"><span style='font-size: 13'>{$data["customerName"]}</span></td>
			<td><span style='font-size: 13'>入库仓库</span></td>
			<td colspan="3"><span style='font-size: 13'>{$data["warehouseName"]}</span></td>
		</tr>
	</table>
	<br />
	<table border=1 cellSpacing=0 cellPadding=1 width="100%"
		style="border-collapse: collapse" bordercolor="#333333">
		<tr>
			<td><div align="center" style='font-size: 13'>序号</div></td>
			<td><div align="center" style='font-size: 13'>商品编码</div></td>
			<td><div align="center" style='font-size: 13'>商品名称</div></td>
			<td><div align="center" style='font-size: 13'>规格型号</div></td>
			<td><div align="center" style='font-size: 13'>数量</div></td>
			<td><div align="center" style='font-size: 13'>单位</div></td>
			<td><div align="center" style='font-size: 13'>退货单价</div></td>
			<td><div align="center" style='font-size: 13'>退货金额</div></td>
		</tr>
		<foreach name="data['items']" item="v" key="i">
		<tr>
			<td><div align="center" style='font-size: 13'>{$i+1}</div></td>
			<td><div style='font-size: 13'>{$v["goodsCode"]}</div></td>
			<td><div style='font-size: 13'>{$v["goodsName"]}</div></td>
			<td><div style='font-size: 13'>{$v["goodsSpec"]}</div></td>
			<td><div align="center" style='font-size: 13'>{$v["goodsCount"]}</div></td>
			<td><div style='font-size: 13'>{$v["unitName"]}</div></td>
			<td><div align="right" style='font-size: 13'>{$v["goodsPrice"]}</div></td>
			<td><div align="right" style='font-size: 13'>{$v["goodsMoney"]}</div></td>
		</tr>
		</foreach>
		<tr>
			<td><div align="center">合计</div></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><div align="right">￥{$data["rejMoney"]}</div></td>
		</tr>
	</table>
</body>
</html>