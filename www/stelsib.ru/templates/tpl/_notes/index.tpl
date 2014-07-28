<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{titlepage}</title>
    <meta name="robots" content="index, follow">
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
<!--wrap-->
<div id="wrap">
    {head}
    {top_menu}
    <!--.container-->
    <div class="container">
        <div class="row">
            {left}
            <!--content-section-->
            <div class="{col_content}" id="content-section">
                {edit}
                {breadcrumbs}
                {title}
                {content}
            </div>
            <!--/content-section-->
        </div>
    </div>
    <!--/.container-->
    {row_pop}
   



</div>
<!--/wrap-->

{footer}
<!-- javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{bottom_link}
</body>
</html>