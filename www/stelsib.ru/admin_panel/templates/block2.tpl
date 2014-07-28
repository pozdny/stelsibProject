<form id="editForm" class="{table}" >
    <div>
        <fieldset>
            <legend>{legend}</legend>
            <div>
                <div class="form-group">
                    <label for="rus">Название пункта по-русски:</label>
                    <input type='text' class='form-control input-sm' name='rus' id='rus' placeholder='Название пункта по-русски' value='{rus}' />
                </div>
                <div class="form-group">
                    <label for="eng">Название пункта по-английски:</label>
                    <input type='text' class='form-control input-sm' name='eng' id='eng' placeholder='Название пункта по-английски' value='{eng}' {disabled}/>
                </div>
                {h1_text}
                <div class="form-group">
                    <label for="content">Текст над товарами:</label>
                    {table_redact}
                </div>
                <div class="form-group">
                    <label for="content2">Текст под товарами:</label>
                    {table_redact2}
                </div>
                <button type="button" class="btn btn-primary" id="button_edit">Изменить</button>
            </div>
        </fieldset>
    </div>
    <input name="edit_id" id="edit_id" type="hidden" value="{edit_id}">
    <input name="link" type="hidden" value="{link}">
</form>