<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php include_stylesheets() ?>

        <style type="text/css">
                .versionner {
                    outline: 1px dotted #ff0000 !important;
                }
        </style>

        <?php include_javascripts() ?>
    </head>
    <body>        
        <!-- #global -->
        <div id="global">

            <?php include_partial('global/header'); ?>

            <!-- fin #header -->
            <div id="global_content">
                <?php echo $sf_content ?>

                <?php include_partial('global/footer'); ?>
            </div>

        </div>
        <!-- fin #global -->

        <?php include_partial('global/ajaxNotification') ?> 
        <?php include_partial('global/initMessageAide') ?>
        
        <script type="text/javascript">var jsPath = "/js/";</script>
        <script type="text/javascript" src="/js/include_dev.js"></script>
    </body>
</html>
