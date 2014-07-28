<div align="center" class="style1">Добавить новость</div>
<form id="editForm" enctype="multipart/form-data" class="{table}"  >
    <fieldset>
        <legend>{title}</legend>
        <div class="form-group">
            <label for="title">Заголовок новости (макс. 150 симв.):</label>
            <input class="form-control input-sm" type="text" name="title" id="title" placeholder="Зоголовок" value="{title}" />
        </div>
        <div class="form-group">
            <label for="description">Содержание:</label>
            {table_redact}
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="all" {disabled}>
                для всех поддоменов
            </label>
        </div>
        <button type="button" id="button_add_news" class="btn btn-success">Добавить</button>
    </fieldset>
    <input type="hidden" value="{link}" name="link">
</form>