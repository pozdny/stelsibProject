$(function() {
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
//..........fancybox...............................................
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
//создание ссылки логотипа
    var logo = $("#logo");
    var selectReg = $("#SelectReg");
    var link_a = '';
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












});
