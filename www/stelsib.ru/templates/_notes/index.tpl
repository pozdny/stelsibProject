<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <title>{titlepage}</title>
    <META name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=1024">
    <meta name="description" content="{description}">
    <meta name="keywords" content="{keywords}">
    {top_link}
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

<body>
<!--wrapper-->

<div id="wrap">
    <!-- Begin page content -->
    <div class="container">
        {head}
        {top_menu}
        <div id="{CONTENT_columns}">
            {left}
            <div class="CONTENT">
                <div {block_undermain}>
                    {edit}
                    {breadcrumbs}
                    {title}
                    {content}
                </div>
            </div>
        </div>
    </div>

    <div id="push"></div>
</div>
{footer}
<!-- javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{bottom_link}

</body>
</html>