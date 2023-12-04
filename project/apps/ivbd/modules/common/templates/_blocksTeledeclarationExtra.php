<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading"  style="min-height: 65px;">
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-2 text-right">
                        <span style="font-size: 27px; margin-right: 5px; margin-top: 12px;" class="glyphicon glyphicon-bullhorn"></span>
                    </div>
                    <div class="col-xs-10 text-left">
                      <h2 style="margin-top: 12px;">Actus</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body" style="padding-top: 0,6rem;">
            <div class="row">
                <ul class="list-unstyled" style="margin-left: 1rem">
                <?php $i = 0; ?>
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <?php foreach ($actus as $index => $article): ?>
                        <li>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><a href="<?php echo $article[2]['url']; ?>"><?php echo $article[0]['titre']; ?></a></strong>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <small><p class="text-muted"><?php echo date('d/m/Y H:i', strtotime($article[3]['date'])); ?></p></small>
                                </div>
                            </div>
                        </li>
                        <div class="row">
                            <div class="col-md-12">
                                <p><?php echo $article[1]['description'] ?></p>
                            </div>
                        </div>
                        <div style='text-align: right; margin-bottom: 3rem;'>
                            <strong><p><a href="<?php echo $article[2]['url']; ?>" class="text-muted">En savoir plus</a></p></strong>
                        </div>
                        <?php if (++$i > 2){ break; }?>
                        <?php endforeach;?>
                    </div>
                <div class="col-md-2"></div>
                </ul>
            </div>
          </div>
          <div class="panel-footer"  >
            <div class="row">
                <div class="col-xs-12 text-center">
                    <a class="btn btn-default" href="http://actus.ivbdpro.fr/" target="_blank">Accéder aux Actualités</a>
                </div>
            </div>
        </div>
    </div>
</div>
