<nav class="navbar navbar-default ">
    <ul class="nav navbar-nav">
        <li class="<?php if($sf_request->getParameter('action') == 'infos'): ?>active<?php endif; ?>"><a href="<?php echo url_for("ds_infos", $ds) ?>"><span>Identification</span></a></li>
          <li class="<?php if($sf_request->getParameter('action') == 'stocks'): ?>active<?php endif; ?>"><a href="<?php echo url_for("ds_stocks", $ds) ?>"><span>Stocks</span></a></li>
        <li class="<?php if($sf_request->getParameter('action') == 'validation'): ?>active<?php endif; ?>"><a href="<?php echo url_for("ds_validation", $ds) ?>"><span>Validation</span></a></li>
    </ul>
</nav>
