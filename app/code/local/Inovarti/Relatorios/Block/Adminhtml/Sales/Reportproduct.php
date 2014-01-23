<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Block_Adminhtml_Sales_Reportproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'inovarti_relatorios';
        $this->_controller = 'adminhtml_sales_reportproduct';
        $this->_headerText = Mage::helper('inovarti_relatorios')->__('Inovarti – Produtos Pedidos');
        parent::__construct();
        $this->_removeButton('add');
    }
}