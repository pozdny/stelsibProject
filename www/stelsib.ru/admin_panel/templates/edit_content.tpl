{back}
<div align="center" class="style1">Исправить заголовок, содержание страницы</div>
<form id="editForm" class="{table}">
    <fieldset>
        <legend>{title}</legend>
        <div class="form-group">
           <label for="zagolovok">Заголовок страницы:</label>
           <input class="form-control input-sm" type="text" name="zagolovok" id="zagolovok" placeholder="Введите заголовок страницы" value="{zagolovok}" />
        </div>
        <div class="form-group">
           <label for="content">Содержание страницы:</label>
            {table_redact}
        </div>
        <button type="button" name="Submit" class="btn btn-primary" id="button_edit">Изменить</button>
    </fieldset>
    <input name="edit_id" type="hidden" id="edit_id" value="{id}">
    <input name="link" type="hidden" value="{link}">
</form>