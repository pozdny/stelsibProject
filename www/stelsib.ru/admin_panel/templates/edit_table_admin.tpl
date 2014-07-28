{back}
<div align="center" class="style1">Исправить данные об операторе</div>
<form id="editForm" class="{table} form-horizontal" >
    <fieldset>
        <legend>{legend}</legend>
            <div class="form-group">
                <div class="col-xs-2">
                    <label for="rus">Ф.И.О:</label>
                </div>
                <div class="col-xs-5">
                    <input type='text' name='name' id='rus' class="form-control input-sm" placeholder='Название пункта' value='{name}' />
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-2">
                    <label for="rus">Новый логин:</label>
                </div>
                <div class="col-xs-5">
                    <input class="form-control input-sm" type='text' name='login' value='' placeholder='Логин'/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-2">
                    <label for="rus">Новый пароль:</label>
                </div>
                <div class="col-xs-5">
                    <input class="form-control input-sm" type='text' name='password'  value='' placeholder='Пароль' />
                </div>
            </div>
        <button type="button" name="Submit" class="btn btn-primary" id="button_edit">Изменить</button>
        <input name="edit_id" type="hidden" id="edit_id" value="{id}">
        <input name="link" type="hidden"  value="{link}">
    </fieldset>

</form>


