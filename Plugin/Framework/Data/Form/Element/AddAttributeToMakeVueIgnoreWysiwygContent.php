<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\Framework\Data\Form\Element;

/* On pages where CC editor is used, vue compiles whole DOM internally, this causes issues when native WYSIWYG content
   uses Magento directives ({{store url}}, etc.) - as a result, textarea content is gone and previous content is lost.
   This fix adds v-pre attribute to WYSIWYG controls, which tells vue to ignore this element and all children from being compiled:
   https://v3.vuejs.org/api/directives.html#v-pre */

class AddAttributeToMakeVueIgnoreWysiwygContent
{

    public function afterGetElementHtml(\Magento\Framework\Data\Form\Element\Editor $subject, $result)
    {

        $html = str_replace('admin__control-wysiwig', 'admin__control-wysiwig" v-pre="true', $result);

        return $html;
    }
}
