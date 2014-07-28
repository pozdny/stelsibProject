/**
 * Created by Валентина on 12.02.14.
 */
$(document).ready(function() {
    var loc     = "http://" + location.hostname;

    error = 'ошибка';
    /*............add input...........................*/
    var total = 0;
    var total_img = 0;
    add_new_input();
    var button_add_input = $('#button_add_input');
    button_add_input.on("click", function()
    {
        return add_new_input();
    });
    function add_new_input()
    {
        total++;
        $('<tr>').attr('class','trinput_'+total)
            .append($('<td>').attr('colspan','2')
                .append($('<span>Назавание позиции</span>')))
            .appendTo('#add_input');
        $('<tr>').attr('class','trinput_'+total)
            .append($('<td>').css({paddingRight:'5px',width:'60%'})
                .append($('<input type="text" class="form-control input-sm"/>').attr('id','addinput[]').attr('name','addinput[]')))
            .append($('<td>').css({width:'60px'})
                .append($('<span id="progress_'+total+'" class="padding5px"><a href="#" onclick="$(\'.trinput_'+total+'\').remove();" class="ico_delete"><img src="'+loc+'/img/icons/delete.png" alt="del" border="0"></a></span>')))
            .appendTo('#add_input');
    }
    /*............add input for images...........................*/
    add_new_img();
    /*............добавление поля для изображения...........................*/
    var button_add_img = $('#button_add_img');
    button_add_img.on("click", function()
    {
        return add_new_img();
    });


    function add_new_img()
    {
        var block_img;
        var table_add_img = $('#table_add_img');
        var div_big;
        var class_tableImg = table_add_img.attr("class");
        var div_big = $('<div>').attr('class','big_block_img').attr('id','big_block_img_'+total_img);
        div_big.appendTo(table_add_img);
        $('<div>').attr('class','row form-group')
            .append($('<div>').attr('class','col-xs-2')
                .append($('<strong>Изображение*:</strong>'))
            )
            .append($('<div>').attr('class','col-xs-10')
                .append($('<input type="file" />').attr('id','img_'+total_img).attr('name','addimg[img_'+total_img+']'))
            )
            .appendTo(div_big);

        $('<div>').attr('class','row form-group')
            .append($('<div>').attr('class','col-xs-2')
                .append($('<strong>Alt*:</strong>'))
            )
            .append($('<div>').attr('class','col-xs-10')
                .append($('<input type="text" />').attr('class', 'form-control input-sm').attr('name','addimg[alt_'+total_img+']'))
            )
            .appendTo(div_big);

        $('<div>').attr('class','row form-group')
            .append($('<div>').attr('class','col-xs-2')
                .append($('<strong>Img_title*:</strong>'))
            )
            .append($('<div>').attr('class','col-xs-10')
                .append($('<input type="text" />').attr('class', 'form-control input-sm').attr('name','addimg[title_'+total_img+']'))
            )
            .appendTo(div_big);


        $('<div>').attr('class','del_div_big')
            .append($('<span id="progressimg_'+total_img+'" class="padding5px"><a href="#" onclick="$(\'#big_block_img_'+total_img+'\').remove();" class="ico_delete"><img src="'+loc+'/img/icons/delete.png" alt="del" border="0"></a></span>'))

        .appendTo(div_big);

        total_img++;
    }

    //редактирование заголовка, содержания и метатегов страниц, содержания категории
    var button_edit = $('#button_edit');
    button_edit.on("click", function(){
        var form = $("#editForm");
        var tab_class = form.attr('class');
        var arr_class = tab_class.match(/\w+|"[^"]+"/g);
        var tab = arr_class[0]; //alert(tab);
        var num = 4;
        var arr = form.serializeArray();
        var id = $("#edit_id").attr('value');
        editForm(num, id, arr, tab);
    })
    //удаление позиции из таблиц админ.панели
    var del_button = $('.del_button');
    del_button.each(function(){
        $(this).click(function(){
            var arr = $("#dif_tab").attr("class");
            var num = 2;
            var id = $(this).attr('id');
            $(this).removeClass('del_button');
            var table = $(this).attr('class');
            var text;
            if(table=='catalog_menu')
            {
                text = 'При удалении категории будут также удалены все связанные с ней таблицы. Вы действительно хотите удалить позицию?';
            }
            else if(table=='catalog_submenu')
            {
                text = 'При удалении подкатегории будут также удалены все связанные с ней таблицы. Вы действительно хотите удалить позицию?';
            }
            else
            {
                text = 'Вы действительно хотите удалить позицию?';
            }
            if(confirm(text))
            {
                editForm(num, id, arr, table);
            }
        })

    })
    //добавление пункта
    var button_send_complect = $('#button_add');
    button_send_complect.on("click", function(){
        var form = $("#formAddElements");
        var arr = form.serializeArray();
        var id = $("#add_id").attr('value');
        var num = 3;
        var tab = form.attr("class");
        editForm(num, id, arr, tab);
    })
    //добавление новости
    var button_send_news = $('#button_add_news');
    button_send_news.on("click", function(){
        var form = $("#editForm");
        var arr = form.serializeArray();
        var num = 3;
        var id = '';
        var tab = form.attr("class");
        editForm(num, id, arr, tab);
    })
    //удаление комплекта
    var del_complect = $('#button_del');
    del_complect.on("click", function(){
        var form = $("#formElements");
        var arr = form.serializeArray();
        var tab = form.attr("class");
        var num = 2;
       // var arr = [];
        var id = '';
        var checkbox = form.find("input[id='del[]']:checked");
       // checkbox.each(function(){
       //     arr.push(this.value);

       // })
        var text = 'Вы действительно хотите удалить отмеченные позиции?';
        if(confirm(text)){
           editForm(num, id, arr, tab)
        }
    })

//функция обратного вызова
    function callBackEdit(data){
        if(data != '')
        {
            var link = data.link;

            var link_str   = loc +  link;
            document.location = link_str;
        }
        else
        {
            alert(error);
        }
    }
//функция отправки данных ajax
    function editForm(num, id, arr, table){
        $.ajaxSetup({
            url: "/admin_panel/admin_edit.php",
            type: "POST",
            dataType:"json",
            cache:false,
            success: function(data)
            {
                callBackEdit(data);
            },
            error: function(obj, err)
            {

            }
        });
        $.ajax({
            data:{
                num:num,
                id:id,
                value:arr,
                table:table
            }
        });

    }
//функция обратного вызова для популярных товаров
    function callBackEditPop(data, arr_check){
        if(data != '')
        {
            var constCount = '';
            if(data.str == 'pop'){
                constCount = 4;
            }
            else{
                constCount = 1;
            }
            var count = data.count;
            if(count == constCount){
                $.each(arr_check, function(){
                    if($(this).is(":checked")) {}
                    else{
                        $(this).attr("disabled", true);
                    };
                })
            }
            else{
                $.each(arr_check, function(){
                    if($(this).is(":checked")) {}
                    else{
                        $(this).attr("disabled", false);
                    };
                })
            }
        }
        else
        {
            alert(error);
        }
    }
//добавление комплектов в популярные
    var ComplectTable = $('#ComplectTable');
    var arr_pop = ComplectTable.find("input[id='pop[]']");
    var arr_promo = ComplectTable.find("input[id='promo[]']");
    var form = $("#formElements");
    var table = form.attr("class");
    $.each(arr_pop, function(){
        $(this).on("click", function(){
            var check = ($(this).is(":checked"));
            var id = $(this).val();
            $.ajaxSetup({
                url: "/admin_panel/admin_edit.php",
                type: "POST",
                dataType:"json",
                cache:false,
                success: function(data)
                {
                    callBackEditPop(data, arr_pop);

                },
                error: function(obj, err)
                {

                }
            });
            $.ajax({
                data:{
                    num:5,
                    id:id,
                    type:check,
                    table:table,
                    str:'pop'
                }
            });
        })
    })
    $.each(arr_promo, function(){
        $(this).on("click", function(){
            var check = ($(this).is(":checked"));
            var id = $(this).val();
            $.ajaxSetup({
                url: "/admin_panel/admin_edit.php",
                type: "POST",
                dataType:"json",
                cache:false,
                success: function(data)
                {
                    callBackEditPop(data, arr_promo);

                },
                error: function(obj, err)
                {

                }
            });
            $.ajax({
                data:{
                    num:5,
                    id:id,
                    type:check,
                    table:table,
                    str:'promo'
                }
            });
        })
    })

});
