<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title><?php echo html::specialchars($title) ?></title>
        <?php echo html::stylesheet(array(
            'http://bugzilla.mozilla.org/skins/standard/global.css',
            'http://bugzilla.mozilla.org/skins/custom/global.css',
            'media/css/main',
            'media/css/redmond/jquery-ui-1.7.2.custom.css'
            )
            ,array('screen','screen','screen','screen'));
        ?>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="Top" href="https://bugzilla.mozilla.org/" />
        <?php echo html::script(array(
            'media/js/jquery-1.4.2.min.js',
            'media/js/jquery-ui-1.7.2.custom.min.js',
            'media/js/workermgmt.js',
            )
            ,array('screen','screen','screen','screen'));
        ?>
    </head>
    <body>
        <div id="container">
            <div id="mozilla-org"><a href="http://www.mozilla.org/" title="Visit mozilla.org">Visit mozilla.org</a></div>
            <div id="header">
                <h1>Mozilla Corporation - Worker Management</h1>
            </div>
            <?php echo client::messageFetchHtml(); ?>
            <?php echo $content ?>

        </div>
    </body>
</html>