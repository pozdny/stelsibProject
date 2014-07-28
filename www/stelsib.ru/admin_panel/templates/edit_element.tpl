{back}
<div align="center" class="style1">Исправить данные комплекта</div>
<form id="editForm" enctype="multipart/form-data" class="{table}" action="{action}" method="POST">
    <fieldset>
        <legend>{title}</legend>
        <div class="form-group">
            <label for="zagolovok">Название:</label>
            <input class="form-control input-sm" type="text" name="zagolovok" placeholder="Название позиции" value="{zagolovok}" />
        </div>
        <div class="form-group">
            <label for="description">Описание:</label>
            {table_redact}
        </div>

        <label for="price">Цена:</label>
        <div class="row">
            <div class="form-group">
                <div class="col-xs-3">
                    <input class="form-control input-sm text-right" type="text" name="price" placeholder="Цена" value="{price}" />
                </div>
            </div>
        </div>
        <br>
        {main_img}
        <button type="submit" name="Submit" class="btn btn-primary" >Изменить</button>
    </fieldset>
    <input name="MM_edit" type="hidden"  value="MM_edit">
    <input name="menu_eng" type="hidden" id="menu_eng" value="{menu_eng}">
    <input name="edit_id" type="hidden" id="edit_id" value="{id}">
</form>