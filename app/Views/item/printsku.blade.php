<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="100" align="center" valign="middle">
<form action="{{ route('purchase.showpo') }}" method="post" name="create" id="create">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	请输入本次到货对应的单据号，非本次到货的物品打印条码时请咨询主管。
	<br />
	采购单据号:

	<input name='po_id' value='' type='text' id='po_id' size='10' /><br/><span style='font-weight:bold;color:red;font-size:10px;'>*如果从产品管理打印sku条码,请输入正确的采购单号!!!</span><br/>
	<input name="pp_id" type="hidden" id="pID" value="{{$model->id}}" />
	<input type="radio" name="labelSize" value="big" />生成大标签(70mmx29mm)<br/>
	<input type="radio" name="labelSize" value="small" checked="checked"/>生成小标签(50mmx25mm)<br/>
	<input type="radio" name="labelSize" value="middle" />生成标签(40mm x 15mm)<br/>
	<input type="radio" name="labelSize" value="middleSmall" />生成标签(40mm x 20mm)<br/>
	<input name="submint" type="submit" id="submint" value="生成" style="width:100px;"/>
	<input type="hidden" value='{{$from}}' name='from'> 
</form>
</td>
</tr>
</table>
</body>
</html>
