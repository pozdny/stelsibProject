<table class="showTable table table-condensed">
    <tr align="center" valign="middle"><td align="left">
            <input type="button" accesskey="b" name="addbbcode0" value="b"
                   class="butt_reduct" style="font-weight:bold;" onClick="bbstyle(0, '{i}')"
                   onMouseOver="helpline('b', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button"  name="addbbcode28" value="strong "
                   class="butt_reduct1" style="font-weight:bold;;" onClick="bbstyle(28, '{i}')"
                   onMouseOver="helpline('str', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" accesskey="i" name="addbbcode2" value="i"
                   class="butt_reduct" style="font-style:italic;" onClick="bbstyle(2, '{i}')"
                   onMouseOver="helpline('i', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode4" value="u"
                   class="butt_reduct" style="text-decoration: underline;" onClick="bbstyle(4, '{i}')"
                   onMouseOver="helpline('u', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode22" value="h1"
                   class="butt_reduct"  onClick="bbstyle(22, '{i}')"
                   onMouseOver="helpline('h1', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode24" value="h2"
                   class="butt_reduct"  onClick="bbstyle(24, '{i}')"
                   onMouseOver="helpline('h2', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode26" value="h3"
                   class="butt_reduct"  onClick="bbstyle(26, '{i}')"
                   onMouseOver="helpline('h3', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode18" value="URL"
                   class="butt_reduct2"   onClick="bbstyle(18, '{i}')"
                   onMouseOver="helpline('w', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode12" value="ul"
                   class="butt_reduct"  onClick="bbstyle(12, '{i}')"
                   onMouseOver="helpline('l', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode14" value="ol"
                   class="butt_reduct"  onClick="bbstyle(14, '{i}')"
                   onMouseOver="helpline('o', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode30" value="txt10"
                   class="butt_reduct"  onClick="bbstyle(30, '{i}')"
                   onMouseOver="helpline('t10', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode32" value="txt13"
                   class="butt_reduct"  onClick="bbstyle(32, '{i}')"
                   onMouseOver="helpline('t13', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode34" value="txt14"
                   class="butt_reduct"  onClick="bbstyle(34, '{i}')"
                   onMouseOver="helpline('t14', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode36" value="txt15"
                   class="butt_reduct"  onClick="bbstyle(36, '{i}')"
                   onMouseOver="helpline('t15', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button" name="addbbcode38" value="bq"
                   class="butt_reduct"  onClick="bbstyle(38, '{i}')"
                   onMouseOver="helpline('bq', '{i}')" onMouseOut="helpline('h', '{i}')" />

            <input type="button"  value="[ / ]"
                   class="butt_reduct"  onClick="bbstyle(-1, '{i}')"
                   onMouseOver="helpline('a', '{i}')" onMouseOut="helpline('h', '{i}')" />
        </td></tr>
    <tr><td >Цвет шрифта:
            {addbbcode20}
            <option style="color:#000000;" value="default">По умолчанию</option>
            <option style="color:#ff3202;" value="orange" />Оранжевый</option>
            <option style="color:#5A8D2E;" value="green" />Зелёный</option>
            <option style="color:#2c52e3;" value="blue" />Синий</option>
            <option style="color:darkblue;" value="darkblue" />Темно-синий</option>
            </select>
        </td></tr>
    <tr><td colspan="10"><input type="text" name="{helpbox}" style="width:90%; font-size:11px" class="helpline" value="Подсказка: выделите нужное слово или предложение и нажмите на стиль: <b>, <i>, <u>" /></td></tr>
    <tr><td colspan="10"><textarea class="form-control" name="{name_content}"  id="{name_content}" cols="75" rows="15" placeholder="Введите содержание страницы" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{content}</textarea></td></tr>
</table>
