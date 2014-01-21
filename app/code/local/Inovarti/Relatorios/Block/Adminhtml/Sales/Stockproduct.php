<?php
class Inovarti_Relatorios_Block_Adminhtml_Sales_Stockproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'inovarti_relatorios';
        $this->_controller = 'adminhtml_sales_stockproduct';
        $this->_headerText = Mage::helper('inovarti_relatorios')->__('Inovarti – Report Product Stock');
        parent::__construct();
        $this->_removeButton('add');
    }
}