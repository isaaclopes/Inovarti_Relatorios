<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Block_Adminhtml_Sales_Reportproduct_Grid extends Mage_Adminhtml_Block_Report_Grid {

    protected $_subReportSize = 0;

    public function __construct() {
        parent::__construct();
        $this->setId('inovarti_relatorios_reportproduct_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setSubReportSize(false);
    }

    protected function _prepareCollection() {

        parent::_prepareCollection();
        $this->getCollection()->initReport('inovarti_relatorios/reportproduct');
        return $this;
    }

    protected function _prepareColumns() {
        // Add columns to the grid
        $this->addColumn('sku', array(
            'header' => Mage::helper('inovarti_relatorios')->__('SKU'),
            'align' => 'left',
            'sortable' => true,
            'index' => 'sku'
        ));

        $this->addColumn('order_items_name', array(
            'header' => Mage::helper('inovarti_relatorios')->__('Item Name'),
            'align' => 'left',
            'sortable' => false,
            'index' => 'order_items_name'
        ));
        $this->addColumn('qty', array(
            'header' => Mage::helper('reports')->__('Qtd Estoque Atual'),
            'sortable' => true,
            'type' => 'number',
            'index' => 'qty'
        ));
        $this->addColumn('qty_ordered', array(
            'header' => Mage::helper('reports')->__('Num. Vendidos'),
            'width' => '120px',
            'align' => 'right',
            'index' => 'qty_ordered',
            'total' => 'sum',
            'type' => 'number'
        ));
        $this->addColumn('qty_paid', array(
            'header' => Mage::helper('reports')->__('Num. Pagos'),
            'align' => 'right',
            'sortable' => false,
            'type' => 'number',
            'total' => 'sum',
            'index' => 'qty_paid'
        ));
        $this->addColumn('qty_pending', array(
            'header' => Mage::helper('reports')->__('Num. Pendentes'),
            'align' => 'right',
            'sortable' => false,
            'type' => 'number',
            'total' => 'sum',
            'index' => 'qty_pending'
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('inovarti_relatorios')->__('Excel CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('inovarti_relatorios')->__('Excel XML'));
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
