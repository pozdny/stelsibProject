<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="loginForm" class="form-horizontal" onsubmit="return false" name="loginForm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Введите логин и пароль</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-lg-3"><label for="login">Логин:</label></div>
                    <div class="col-lg-9"><input id="login" type="text" class="form-control" value="" placeholder="Введите Ваш логин" size="40" name="login"></div>
                </div>
                <div class="form-group" id="password">
                    <div class="col-lg-3"><label for="password">Пароль:</label></div>
                    <div class="col-lg-9"><input id="password" type="password" class="form-control" value="" placeholder="Введите Ваш пароль" size="40" name="password"></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="checkbox">
                            <label>
                                <input id="autologin" type="checkbox" value="yes" name="autologin"><span class="style5"> Автоматически входить при каждом посещении</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="login-form" >Войти</button>
                <button type="button" class="btn btn-default close-foot">Отмена</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>