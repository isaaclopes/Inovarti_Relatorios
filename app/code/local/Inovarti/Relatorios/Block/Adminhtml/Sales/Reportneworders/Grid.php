<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Block_Adminhtml_Sales_Reportneworders_Grid extends Mage_Adminhtml_Block_Report_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('inovarti_relatorios_reportneworders_grid');
        $this->setDefaultSort('order_increment_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection() {

        parent::_prepareCollection();
        $this->getCollection()->initReport('inovarti_relatorios/reportneworders');
        return $this;
    }

    protected function _prepareColumns() {
        // Add columns to the grid
        $helper = Mage::helper('inovarti_relatorios');
        
        $this->addColumn('order_increment_id', array(
            'header' => $helper->__('Order #'),
            'align' => 'left',
            'sortable' => true,
            'index' => 'order_increment_id',
            //'renderer' => 'Inovarti_Relatorios_Block_Adminhtml_Sales_Grid_Renderer_Order',
        ));

        $this->addColumn('shipto_name', array(
            'header' => $helper->__('Ship to Name'),
            'align' => 'left',
            'sortable' => false,
            'index' => 'shipto_name'
        ));
        $this->addColumn('shipto_address', array(
            'header' => Mage::helper('reports')->__('Address'),
            'align' => 'left',
            'sortable' => false,
            'index' => 'shipto_address'
        ));
        $this->addColumn('sku', array(
            'header' => $helper->__('Skus Purchased'),
            'align' => 'left',
            'sortable' => true,
            'index' => 'sku'
        ));
        $this->addColumn('order_items_name', array(
            'header' => $helper->__('Products Purchased'),
            'align' => 'left',
            'sortable' => false,
            'index' => 'order_items_name'
        ));
        $this->addColumn('method', array(
            'header' => $helper->__('Payment Method'),
            'align' => 'left',
            'sortable' => false,
            'index' => 'method',
            'renderer' => 'Inovarti_Relatorios_Block_Adminhtml_Sales_Grid_Renderer_Paymenttitle'
        ));
        $this->addColumn('additional_information', array(
            'header' => $helper->__('Qtd Parcel'),
            'align' => 'left',
            'sortable' => false,
            'index' => 'additional_information',
            'renderer' => 'Inovarti_Relatorios_Block_Adminhtml_Sales_Grid_Renderer_Parcels'
        ));


        $this->addColumn('qty_ordered', array(
            'header' => Mage::helper('reports')->__('Quantity Ordered'),
            'align' => 'right',
            'sortable' => false,
            'type' => 'number',
            'index' => 'qty_ordered',
            'total' => 'sum'
        ));
        
        $this->addExportType('*/*/exportCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportXml', $helper->__('Excel XML'));
        return parent::_prepareColumns();
    }
    public function getRowUrl($row) {
        return false;
    }

    public function getReport($from, $to) {
        if ($from == '') {
            $from = $this->getFilter('report_from');
        }
        if ($to == '') {
            $to = $this->getFilter('report_to');
        }

        $totalObj = Mage::getModel('reports/totals');
        $totals = $totalObj->countTotals($this, $from, $to);
        $this->setTotals($totals);
        $this->addGrandTotals($totals);

        return $this->getCollection()->getReport($from, $to);
    }
}
