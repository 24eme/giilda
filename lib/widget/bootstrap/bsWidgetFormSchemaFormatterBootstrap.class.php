<?php
class bsWidgetFormSchemaFormatterBootstrap extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<li>\n  %error%%label%\n  %field%%help%\n%hidden_fields%</li>\n",
    $errorRowFormat  = "<li>\n%errors%</li>\n",
    $helpFormat      = '<br />%help%',
    $decoratorFormat = "<ul>\n  %content%</ul>";

    public function getErrorListFormatInARow()
    {
        return "<ul class=\"alert alert-danger list-unstyled\">\n%errors%  </ul>\n";
    }
}
