$(function() {
   'use strict';
    var loc         = "http://" + location.hostname;
    var loc_admin  = "http://" + location.hostname + "/admin";


    //........PLACEHOLDER.......................................
    var search_tovar = $("#inputFild");
    var txt = 'Поиск...';
    search_tovar.addClass('placeholded').val(txt);
    search_tovar.focus(function()
    {
        $(this).val('');
    }).blur(function(){
        if($(this).val() == '' || $(this).val() == txt){
            $(this).val(txt);
        }

    });
    $("#searchForm").submit(function(){
        if(search_tovar.val() == txt) return false;

    })
//........FORMSTYLER.............................................
    $('select').styler();

//.......Height elem-inner.........................
    var elem  = $(".elem-inner");
    var n = 0;
    var k = 0;
    for(var i=0, count=elem.length; i<count; i++)
    {
        n = elem.eq(i).css("height");
        n = parseInt(n);
        if(n > k) k = n;
    }
    for(var i=0, count=elem.length; i<count; i++)
    {
        elem.eq(i).css("height", k);
    }
//.......Border nav................................................

//..........fancybox...............................................
    if($("a.galeri").length > 0){
        $(".galeri").fancybox({
            wrapCSS    : 'fancybox-custom',
            closeClick : true,

            openEffect : 'none',

            helpers : {
                title : {
                    type : 'inside'
                },
                overlay : {
                    css : {
                        'background' : 'rgba(106,106,106,0.6)'
                    }
                }
            }

        });
    }

//создание ссылки логотипа
    var logo = $("#logo");
    var selectReg = $("#SelectReg");
    var link_a = '';
    if(logo.hasClass("block-true") || logo.hasClass("block-false")){
        if(logo.hasClass("block-true"))
        {
            link_a = loc_admin;
            logo.wrap("<a href='" + link_a + "'></a>");
        }
        else
        {
            link_a = "http://" + selectReg.find("option:selected").val();
            logo.wrap("<a href='" + link_a + "'></a>");
        }
    }


//лупа при наведении
    var elem_block = $(".elem-inner-image");
    elem_block.each(function(){
        var lupa = $(this).find(".lupa");
        $(this).hover(
            function(){
                lupa.addClass("fancyover");
            },
            function(){
                lupa.removeClass("fancyover");
            }
        )
    });
    var sub_block = $(".sub-block");
    sub_block.each(function(){
        var lupa = $(this).find(".lupa");
        $(this).hover(
            function(){
                lupa.addClass("fancyoverB");
            },
            function(){
                lupa.removeClass("fancyoverB");
            }
        )
    });
    //....change select
    $("#SelectReg").change(function(){
        location.href = 'http://' + $(this).val();
    })
    //модально окно login
    $('#modal').modal({
        keyboard: false,
        backdrop: true,
        show: true
    });
    var close = $(".close, .close-foot");
    close.on('click', function(){
        location.href = loc;
    })
    var login = $("#login-form");
    login.on('click', function(){
        loginForm();
    })

    //передача данных формы login  ajax ..............................................
    function callBackLogin(data)
    {
        if(data)
        {
            var password = $("#password");
            var error_row = $("#error_row");
            if(error_row) error_row.remove();

            var text = '';
            var text2 = '';
            if(data == 'ok')
            {
                location.href = 'admin/products';
            }
            else{
                password.after('<div class="col-lg-6 col-lg-offset-3" id="error_row"><div class=" style3" id="error_mess"></div></div>');
                var error_mess = $("#error_mess");
                error_mess.empty();
                if(data == 'no')
                {
                    text = "неправильный логин или пароль";
                    error_mess.append(text);
                }
                else
                {
                    if(data == 'empty_login')
                    {
                        text = "не заполнено поле \"логин\"";
                        error_mess.append(text);
                    }
                    else if(data == 'empty_password')
                    {
                        text = "не заполнено поле \"пароль\"";
                        error_mess.append(text);
                    }
                    else
                    {
                        text = "не заполнено поле \"логин\"" + "<br>";
                        text2 = "не заполнено поле \"пароль\"";
                        error_mess.append(text);
                        error_mess.append(text2);
                    }
                }
            }
        }
    }
    function loginForm()
    {
        var arr = $("#loginForm").serializeArray();
        $.ajaxSetup({
            url:"/admin_panel/login.php",
            type: "POST",
            dataType:"html",
            cache:false,
            success: function(data)
            {
                callBackLogin(data);
            },
            error: function(obj, err)
            {

            }
        });
        $.ajax({
            data:{arr:arr}
        });
    }


//форма предзаказа ..............................................

    var wrap = $('#wrap');
    var title = "Предзаказ";
    var title2 = "Какая продукция Вас интересует:";
    var text  = '<br>Пожалуйста заполните следующую форму, для того чтобы определить ваши потребности и предложить наиболее актуальное решение ваших складских задач.';
    $('<div class="modal fade" id="myModalPred" tabindex="-1" role="dialog" aria-labelledby="myModalPred" aria-hidden="true" >')
        .append($('<div>').attr("class", "modal-dialog")
            .append($('<div>').attr("class", "modal-content")
                .append($('<div>').attr("class", "modal-header")
                    .append($('<button data-dismiss="modal" aria-hidden="true">').attr({type:"button", class:"close"})
                        .append('&times;'))
                    .append($('<h3 class="modal-title" id="myModalLabel">')
                        .append(title)
                    )
                    .append(text)
                )
                .append($('<div>').attr("class", "modal-body")
                    .append($('<h4>')
                        .append(title2)
                    )
                    .append($("<form>").attr({method:'post', id:'blankFormPred', class:'notsend', role:'form'})
                        .append($('<label>').attr("class", "checkbox-inline")
                            .append($("<input type='checkbox' value='Архивные' name='kindOrder' id='checkArch'>"))
                            .append("Архивные стеллажи")
                        )
                        .append($('<label>').attr("class", "checkbox-inline")
                            .append($("<input type='checkbox' value='Полочные' name='kindOrder' id='checkPol'>"))
                            .append("Полочные стеллажи")
                        )
                        .append($('<br><br>'))
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )

                                    .append($("<label>").attr("for", "nameOrder")
                                        .append("Ваше имя")
                                    )
                                    .append($("<input name='nameOrder'>").attr({class:"form-control ignore",id:"nameOrder", maxlength:"70", type:"text", placeholder:"Имя"}))
                            )

                        )
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )

                                .append($("<label>").attr("for", "cityOrder")
                                    .append("Город<span class='redStar'>*</span>")
                                )
                                .append($("<input name='cityOrder'>").attr({class:"form-control",id:"cityOrder", maxlength:"70", type:"text", placeholder:"Город"}))
                            )

                        )
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )

                                    .append($("<label>").attr("for", "mailOrder")
                                        .append("Ваш e-mail<span class='redStar'>*</span>")
                                    )
                                    .append($("<input name='mailOrder'>").attr({class:"form-control",id:"mailOrder", maxlength:"70", type:"text", placeholder:"e-mail"}))
                            )

                        )
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )

                                .append($("<label>").attr("for", "phoneOrder")
                                    .append("Ваш телефон<span class='redStar'>*</span>")
                                )
                                .append($("<input name='phoneOrder'>").attr({class:"form-control",id:"phoneOrder", maxlength:"70", type:"text", placeholder:"Телефон"}))
                            )

                        )
                        .append($("<div>").attr("class", "form-group" )
                            .append($("<label>").attr("for", "caracterOrder")
                                .append("Укажите в свободной форме характеристики вашего помещения (размеры ДхШхВ), характеристику грузов, желаемое количество полок у стеллажей и другую информацию, которая поможет нам в расчете стоимости, а также все, что вы считаете нужным нам сообщить")
                            )
                            .append($("<textarea rows='2' name='caracterOrder'>").attr({id: "caracterOrder", placeholder:"Комментарий", class:"form-control ignore", maxlength:"500"}).css("width", "100%"))
                        )
                        .append($("<div>").attr({id:"resultOrder"}))
                        .append($('<div>').attr({class:"row"})
                            .append($("<div>").attr("class", "form-group col-xs-6" )
                                .append($("<button>").attr({id:"blankButton", class:"btn btn-primary", type:"submit", "data-loading-text":"Подождите..."})
                                    .append("Отправить"))
                                .append($('<button data-dismiss="modal">').attr({class:"btn btn-default"})
                                    .append("Закрыть"))
                            )
                        )
                    )

                )
            )
        )
        .appendTo(wrap);

    var form = $('#blankFormPred');
    var button = $('#blankButton');
    var arch = $('#checkArch');
    var pol  = $('#checkPol');
    var result = $('#resultOrder');
    var order_online = $('#order_online');
    var inputs = form.find('input[type=text],textarea');
    order_online.click(function(){
        if(form.hasClass('send')){
            var resultOrderInner = $('#resultOrderInner');
            form.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
        };
    })
    inputs.on("change",function(){
        if(form.hasClass('send')){
            var resultOrderInner = $('#resultOrderInner');
            form.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
        };
    })
    arch.on('change', function(){
        if(form.hasClass('send')){
            var resultOrderInner = $('#resultOrderInner');
            form.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
        };
        /*if($(this).prop("checked")){
            button.attr("disabled",false);
        }
        else{
            if(pol.prop("checked")){
                button.attr("disabled",false);
            }
            else{
                button.attr("disabled",true);
            }

        }*/
    });
    pol.on('change', function(){
        if(form.hasClass('send')){
            var resultOrderInner = $('#resultOrderInner');
            form.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
        };
        /*if($(this).prop("checked")){
            button.attr("disabled",false);
        }
        else{
            if(arch.prop("checked")){
                button.attr("disabled",false);
            }
            else{
                button.attr("disabled",true);
            }

        }*/
    });
    function callBackFunc(data, thisForm, result){
        if(data != ''){
            thisForm.removeClass('notsend').addClass('send');
            thisForm.get(0).reset();
            var inputs = thisForm.find('input[type=text],textarea');
            var mess = '';
            if(data.rez == '1'){
                mess = 'Ваше сообщение отправлено!';
            }
            else{
                mess = 'При отправке сообщения возникли ошибки, поробуйте еще раз!';
            }
            inputs.each(function(){
                $(this).val('').removeClass('valid').siblings('span').remove();
            });
            result.append($("<div>").attr({id:"resultOrderInner"}).text(mess).slideDown(200));
        }
    }

//............VALIDATOR............................................
    form.validate({
        ignore: ".ignore",
        rules: {
            nameOrder: {
                characters: true
            },
            cityOrder: {
                minlength: 1,
                required:true,
                characters: true
            },
            mailOrder: {
                email: true,
                required:true
            },
            phoneOrder: {
                digits: true,
                required:true
            }

        },
        messages: {
            nameOrder: {
                characters: "Некорректное значение"
            },
            mailOrder: {
                required: "Обязательное поле",
                email: "Пожалуйста, введите корректный e-mail"
            },
            phoneOrder: {
                required: "Обязательное поле",
                digits: "Пожалуйста, введите только цифры"
            },
            cityOrder: {
                required: "Обязательное поле",
                minlength: "Минимальное количество знаков 1",
                characters: "Некорректное значение"
            }

        },
        errorElement: "span",
        success: function(label) {
            label.addClass('valid').append($("<i>").attr({class:"fa fa-check"}));
        },
        submitHandler: function() {
            var btn = $('#blankButton');
            var arr = form.serializeArray();
            btn.button('loading');
            $.ajaxSetup({
                url: "/admin_panel/ajaxFunc.php",
                type: "POST",
                dataType:"json",
                cache:false,
                success: function(data)
                {
                    callBackFunc(data, form,result);
                },
                error: function(obj, err)
                {

                }
            });
            $.ajax({
                data:{
                    num:1,
                    value:arr
                }
            }).always(function () {
                btn.text('Отправить').removeClass('disabled').attr({disabled:false});

            });
        }
    });

    //форма обратного звонка ..............................................
    var title = "Заявка на звонок";
    var text  = '<br>Мы всегда рады предоставить развернутую консультацию. Если Вас интересует что-либо из нашей сферы деятельности – стоимость наших услуг, ассортимент и т.д. – заполните форму, расположенную ниже. Менеджеры свяжутся с Вами вскоре после получения запроса.';
    $('<div class="modal fade" id="myModalBack" tabindex="-1" role="dialog" aria-labelledby="myModalBack" aria-hidden="true" >')
        .append($('<div>').attr("class", "modal-dialog")
            .append($('<div>').attr("class", "modal-content")
                .append($('<div>').attr("class", "modal-header")
                    .append($('<button data-dismiss="modal" aria-hidden="true">').attr({type:"button", class:"close"})
                        .append('&times;'))
                    .append($('<h3 class="modal-title" id="myModalLabel">')
                        .append(title)
                    )
                    .append(text)
                )
                .append($('<div>').attr("class", "modal-body")
                    .append($("<form>").attr({method:'post', id:'blankFormBack', class:'notsend', role:'form'})
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )

                                .append($("<label>").attr("for", "nameOrder")
                                    .append("Ваше имя")
                                )
                                .append($("<input name='nameOrder'>").attr({class:"form-control ignore",id:"nameOrder", maxlength:"70", type:"text", placeholder:"Имя"}))
                            )

                        )
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )

                                .append($("<label>").attr("for", "phoneOrder")
                                    .append("Контактный телефон<span class='redStar'>*</span>")
                                )
                                .append($("<input name='phoneOrder'>").attr({class:"form-control",id:"phoneOrder", maxlength:"70", type:"text", placeholder:"Телефон"}))
                            )

                        )
                        .append($("<div>").attr("class", "row")
                            .append($("<div>").attr("class", "form-group col-xs-6" )
                                .append($("<label>").attr("for", "caracterOrder")
                                    .append("Ваш вопрос")
                                )
                                .append($("<textarea rows='3' name='caracterOrder'>").attr({id: "caracterOrder", placeholder:"Вопрос", class:"form-control ignore", maxlength:"500"}).css("width", "100%"))
                            )
                        )
                        .append($("<div>").attr({id:"resultOrderBack"}))
                        .append($('<div>').attr({class:"row"})
                            .append($('<div>').attr({class:"col-xs-12"})
                                .append($("<button>").attr({id:"blankButtonBack",disabled:"disabled", class:"btn btn-primary", type:"submit", "data-loading-text":"Подождите..."})
                                    .append("Отправить"))
                                .append($('<button data-dismiss="modal">').attr({class:"btn btn-default"})
                                    .append("Закрыть"))
                            )
                        )

                    )

                )
            )
        )
        .appendTo(wrap);
    var form2 = $('#blankFormBack');
    var button2 = $('#blankButtonBack');
    var result2 = $('#resultOrderBack');
    var order_backcall = $('#order_backcall');
    order_backcall.click(function(){
        if(form2.hasClass('send')){
            var resultOrderInner = $('#resultOrderInner');
            form2.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
        };
    })
    var inputs = form2.find('input[type=text],textarea');
    inputs.on("change",function(){
        if(form2.hasClass('send')){
            var resultOrderInner = $('#resultOrderInner');
            form2.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
        };
    })
    //............VALIDATOR............................................
    form2.validate({
        ignore: ".ignore",
        rules: {
            phoneOrder: {
                digits: true,
                required:true
            }

        },
        messages: {
            phoneOrder: {
                required: "Обязательное поле",
                digits: "Пожалуйста, введите только цифры"
            }

        },
        errorElement: "span",
        success: function(label) {
            label.addClass('valid').append($("<i>").attr({class:"fa fa-check"}));
            button2.attr({disabled:false});
            if(form2.hasClass('send')){
                var resultOrderInner = $('#resultOrderInner');
                form2.removeClass('send').addClass('notsend').find(resultOrderInner).remove();
            };
        },
        submitHandler: function() {
            var btn = $('#blankButtonBack');
            var arr = form2.serializeArray();
            btn.button('loading');
            $.ajaxSetup({
                url: "/admin_panel/ajaxFunc.php",
                type: "POST",
                dataType:"json",
                cache:false,
                success: function(data)
                {
                    callBackFunc(data,form2,result2);
                },
                error: function(obj, err)
                {

                }
            });
            $.ajax({
                data:{
                    num:2,
                    value:arr
                }
            }).always(function () {
                btn.text('Отправить').removeClass('disabled').attr({disabled:false});

            });
        }
    });








});
