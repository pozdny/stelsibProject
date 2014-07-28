<!--header-->
<header>
    <!--.container-->
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="logo" class="{block_true}"></div>
                <div id="header-select">
                    <span>Выбрать город:</span>
                    <select id="SelectReg">
                        {options}
                    </select>
                </div>
                <div class="phone">
                    <div class="phone-icon">
                        <i class="fa fa-custom-phone"></i>
                    </div>
                    <div id="txt_b">ЗВОНИТЕ! ЗВОНОК БЕСПЛАТНЫЙ.</div>
                    <a href="tel:88003854058">
                        <div class="phone-number">
                            {phone}
                        </div>
                    </a>
                    {job_time}
                    <div class="email"><a href="mailto:{email}">{email}</a></div>
                </div>
            </div>
        </div>
        {hello}
    </div>
    <!--/.container-->
</header>
<!--/header-->