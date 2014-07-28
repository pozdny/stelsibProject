{back}
<div align="center" class="style1">Изменить новость</div>
<form id="editForm" enctype="multipart/form-data" class="{table}"  >
    <fieldset>
        <legend>{title1}</legend>
        <div class="form-group">
            <label for="title">Заголовок новости (макс. 150 симв.):</label>
            <input class="form-control input-sm" type="text" name="title" id="title" placeholder="Зоголовок" value="{title}" />
        </div>
        <div class="form-group">
            <label for="description">Содержание:</label>
            {table_redact}
        </div>
        <button type="button" id="button_edit" class="btn btn-primary">Изменить</button>
    </fieldset>
    <input type="hidden" value="{id}" name="edit_id" id="edit_id">
    <input type="hidden" value="{link}" name="link">
</form>