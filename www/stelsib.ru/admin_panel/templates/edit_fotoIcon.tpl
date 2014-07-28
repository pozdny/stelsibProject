
<fieldset>
    <legend>{legend}</legend>
    <div class="row">
        <div class="col-lg-9">
            <div class="form-group">
                <label for="img_icon">
                    Изображение*: {img_now}
                </label>
                <input id="img_icon" type="file"  name="{name_img}">
            </div>
            <div class="form-group">
                <label>alt*:</label>
                <input class="form-control input-sm" type="text"  name="{name_alt}" size="60" value="{alt}"/>
            </div>
            <div class="form-group">
                <label>img_title*:</label>
                <input class="form-control input-sm" type="text"  name="{name_img_title}" value="{img_title}"/>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{name_del}" value="yes"> удалить фото
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            {img}
        </div>
    </div>
</fieldset>

