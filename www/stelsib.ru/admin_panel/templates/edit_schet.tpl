<div align="center" class="style1">Исправить данные счетчиков</div>
<form id="editForm" class="{table}">
    <fieldset>
        <legend>{legend}</legend>
        <div class="form-group">
            <label for="schet">Содержание:</label>
            <textarea class="form-control" name="schet" id="schet" cols="75" rows="15" placeholder="Введите содержание страницы">{content}</textarea>
        </div>
        <button type="button" name="Submit" class="btn btn-primary" id="button_edit">Изменить</button>
    </fieldset>
    <input name="link" type="hidden" value="{link}">
</form>