{back}
<div align="center" {style1}>Исправить данные {title}</div>
<table class="table_edit_menu">
<tr><td>
<form class="form_add_menu"  action="{action}" method="POST" >
<table width="100%"  >
<tr><td>
<fieldset>
<legend>{legend2}</legend>
{input_submenu}
<input name="del_input_tab" id="del_input_tab" type="hidden" value="{del_input_tab}">
{input_button}
</fieldset>

<input type="hidden" name="MM_update" id="MM_update" value="delForm" />
</td></tr>
</table>
<input name="eng_add" type="hidden" value="{eng}">
</form>
<form class="form_add_menu" action="{action}" method="POST">
{add_input}
</form>
<form name="editForm" id="editForm" action="{action}" method="POST" enctype="multipart/form-data">
<table width="100%">
<tr><td>
<fieldset>
<legend>{legend1}</legend>
<table width="100%">
<tr><td {style}><label for="rus" {style}>Название пункта по-русски:</label></td></tr>
<tr><td><input type='text' name='rus' id='rus' size='100' placeholder='Название пункта по-русски' value='{rus}' /><br /><br /></td></tr>
<tr><td {style}><label for="eng">{name_for_eng} {id_for_eng}</label></td></tr>
<tr><td><input type='text' {disabled} name='eng' id='eng' size='100' placeholder='Название пункта по-английски' value='{eng}' /><br /><br /></td></tr>
{img_for_raboty}
{table_redact}
{table_redact2}
{foto}
<tr><td><input type="submit"  form="editForm" value="Изменить" class="uix-button_edit"/></td></tr>
</table>
</fieldset>        
</td></tr>
</table>
<input name="eng_add" type="hidden" value="{eng}">
<input name="id" type="hidden" value="{id}">
<input name="tab" type="hidden" value="{tab}">
<input name="input_tab" type="hidden" value="{input_tab}">
<input type="hidden" name="MM_update" value="editForm" />
<input type="hidden" name="menu_eng" value="{menu_eng}" />
</form>

</td>
</tr>
</table>