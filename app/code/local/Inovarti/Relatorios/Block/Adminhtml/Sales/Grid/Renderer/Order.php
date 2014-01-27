<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Block_Adminhtml_Sales_Grid_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $value = $row->getData($this->getColumn()->getIndex());
        $valueId = $row->getData('order_id');
        $html = '<a href="' . $this->getUrl('adminhtml/sales_order/view', array('order_id' => $valueId, 'key' => $this->getCacheKey())) . '" target="_blank" title="' . $value . '" >' . $value . '</a>';
        return $html;
    }

}
