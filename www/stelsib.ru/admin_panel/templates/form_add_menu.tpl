<form class="form-inline" role="form" action="{action}" method="POST">
    <fieldset>
        <legend>Добавить {position}</legend>
        <div class="form-group">
            <input class="form-control" type="text" name="title" size="40" maxlength="70" value="" placeholder="Введите {title}"/>
        </div>
        <div class="form-group">
            <button type="submit" name="Submit" class="btn btn-success">Добавить</button>
        </div>
    </fieldset>
    <input type="hidden" name="table" value="{table}" />
</form>
