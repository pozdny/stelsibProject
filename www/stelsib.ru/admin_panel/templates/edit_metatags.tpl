{back}
<div align="center" class="style1">Исправить title, description, keywords страницы</div>
<form  id="editForm" class="{table}">
    <fieldset>
        <legend>{title}</legend>
        <div class="form-group">
            <label for="titlepage">title страницы:</label>
            <input type="text" class="form-control"  name="titlepage"  placeholder="Введите title страницы" value="{titlepage}" />
        </div>
        <div class="form-group">
            <label for="keywords">keywords страницы:</label>
            <textarea class="form-control" name="keywords" rows="5" placeholder="Введите keywords страницы">{keywords}</textarea>
        </div>
        <div class="form-group">
            <label for="description">description страницы:</label>
            <textarea class="form-control" name="description" rows="5" placeholder="Введите description страницы">{description}</textarea>
        </div>
        <button type="button" name="Submit" class="btn btn-primary" id="button_edit">Изменить</button>
    </fieldset>
    <input name="edit_id" type="hidden" id="edit_id" value="{id}">
    <input name="link" type="hidden" value="{link}">
</form>