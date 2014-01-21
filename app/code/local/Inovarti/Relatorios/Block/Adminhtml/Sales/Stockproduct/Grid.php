<?php

class Inovarti_Relatorios_Block_Adminhtml_Sales_Stockproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('inovarti_relatorios_grid');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {

        
        $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        
        
        $collection = Mage::getModel('sales/order_item')->getCollection()
                ->addfieldtofilter('main_table.created_at', array('to' => $todayStartOfDayDate))
                ->addfieldtofilter('main_table.updated_at',array('gteq' => $todayEndOfDayDate))
                ->addAttributeToSelect('created_at')
                ->addAttributeToSelect('order_id')
                ->addAttributeToSelect('updated_at')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('qty_invoiced')
                ->addAttributeToSelect('qty_backordered')
                ->addAttributeToSelect('qty_canceled')
                ->addAttributeToSelect('qty_ordered');

        $collection->getSelect()
                ->joinLeft(array(
                    's' => $collection->getTable('cataloginventory/stock_item')), "(main_table.product_id = s.product_id)", array(
                    "s.qty"), null);
        //echo $collection->getSelect()->__toString();

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns() {
        $helper = Mage::helper('inovarti_relatorios');

        $this->addColumn('purchased_on', array(
            'header' => $helper->__('Purchased On'),
            'type' => 'datetime',
            'index' => 'created_at'
        ));
        $this->addColumn('sku', array(
            'header' => $helper->__('Product SKU'),
            'sortable' => false,
            'index' => 'sku'
        ));
        $this->addColumn('name', array(
            'header' => $helper->__('Product Name'),
            'sortable' => false,
            'index' => 'name'
        ));
        $this->addColumn('qty', array(
            'header' => $helper->__('Qtd Stock'),
            'sortable' => false,
            'index' => 'qty'
        ));
        $this->addColumn('qty_invoiced', array(
            'header' => $helper->__('Qtd Sold Invoiced'),
            'sortable' => false,
            'index' => 'qty_invoiced'
        ));
        $this->addColumn('qty_canceled', array(
            'header' => $helper->__('Qtd Sold Canceled'),
            'sortable' => false,
            'index' => 'qty_canceled'
        ));
        $this->addColumn('qty_ordered', array(
            'header' => $helper->__('Qtd Sold'),
            'sortable' => false,
            'index' => 'qty_ordered'
        ));
        $this->addExportType('*/*/exportStockproductCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportStockproductExcel', $helper->__('Excel XML'));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}
