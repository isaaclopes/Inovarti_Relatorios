<?php
class Inovarti_Relatorios_Block_Adminhtml_Sales_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'inovarti_relatorios';
        $this->_controller = 'adminhtml_sales_order';
        $this->_headerText = Mage::helper('inovarti_relatorios')->__('Inovarti - Relatorio de Orders');
        parent::__construct();
        $this->_removeButton('add');
    }
}