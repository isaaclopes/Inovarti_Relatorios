<?php
class Inovarti_Relatorios_Adminhtml_StockproductController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Inovarti Relatorio'));
        $this->loadLayout();
        $this->_setActiveMenu('inovarti/reports');
        $this->_addContent($this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_stockproduct'));
        $this->renderLayout();
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_stockproduct_grid')->toHtml()
        );
    }
    public function exportStockproductCsvAction()
    {
        $fileName = 'stock_product_inovarti.csv';
        $grid = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_stockproduct_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    public function exportStockproductExcelAction()
    {
        $fileName = 'stock_product_inovarti.xml';
        $grid = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_stockproduct_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}