<form id="editForm" class="{table}">
    <fieldset>
        <legend>{title}</legend>
        <div class="form-group">
            <div class="row">
                <div class="col-xs-6">
                    <label for="name">Название организации:</label>
                    <input class="form-control input-sm" type="text" name="name" id="name" placeholder="Введите название организации" value="{name}" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-xs-6">
                    <label for="phone">Телефон:</label>
                    <input class="form-control input-sm" type="text" name="phone" id="phone" placeholder="Введите телефон организации" value="{phone}" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-xs-6">
                    <label for="phone">Расписание:</label>
                    <input class="form-control input-sm" type="text" name="shadule" id="shadule" placeholder="Время работы" value="{shadule}" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-xs-6">
                    <label for="phone">E-mail:</label>
                    <input class="form-control input-sm" type="text" name="email" id="email" placeholder="email" value="{email}" />
                </div>
            </div>
        </div>
        <button type="button" name="Submit" class="btn btn-primary" id="button_edit">Изменить</button>
    </fieldset>
    <input name="link" type="hidden" value="{link}">
</form>

