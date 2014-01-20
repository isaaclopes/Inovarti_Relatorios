<?php
class Inovarti_Relatorios_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Inovarti Relatorio'));
        $this->loadLayout();
        $this->_setActiveMenu('inovarti/reports');
        $this->_addContent($this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_order'));
        $this->renderLayout();
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_order_grid')->toHtml()
        );
    }
    public function exportInovartiCsvAction()
    {
        $fileName = 'orders_inovarti.csv';
        $grid = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    public function exportInovartiExcelAction()
    {
        $fileName = 'orders_inovarti.xml';
        $grid = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}