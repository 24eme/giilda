<!DOCTYPE html>
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
            ul.ui-menu li.ui-menu-item.existant a{
                background-color: #F9D66D;
            }
            ul.ui-menu li.ui-menu-item.existant a:hover,
            ul.ui-menu li.ui-menu-item.existant a.ui-state-hover,
            ul.ui-menu li.ui-menu-item.existant a.ui-state-active {
                background-color: #F5A804 !important;
                color: #fff;
            }
        </style>

        <?php include_javascripts() ?>
    </head>
    <body>        
        <!-- #global -->
        <div id="global">

            <?php include_partial('global/header'); ?>

            <!-- fin #header -->
            <?php
            if ($sf_user->hasFlash('global_error'))
                echo '<div style="margin-bottom: 20px;margin-left: auto; margin-right: auto; width: 700px;" class="global_error"><p><span>' . $sf_user->getFlash('global_error') . "</span></p></div>";
            ?>
            <div id="global_content">
                <div id="contenu">
                    <?php echo $sf_content ?>
                    <aside id="colonne">
                        <?php
                        include_slot('colButtons');
                        ?>
                        <?php
                        include_slot('colApplications');
                        ?>
                        
                        <?php include_component_slot('colContacts'); ?>
                        
                        <?php if (has_slot('colAide')): ?>
                            <?php include_slot('colAide'); ?>
                        <?php else: ?>
                        <div class="bloc_col" id="contrat_aide">
                            <h2>Aide</h2>

                            <div class="contenu">
                                <ul>
                                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                                    <li class="assistance"><a href="#">Assistance</a></li>
                                    <li class="contact"><a href="#">Contacter le support</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>

                    </aside>
                </div>

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
