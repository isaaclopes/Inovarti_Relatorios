<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Adminhtml_ReportnewordersController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {

        $this->_title($this->__('Reports'))
                ->_title($this->__('Products'))
                ->_title($this->__('Inovarti Pedidos itens de pagamento'));
        $this->loadLayout();
        $this->_setActiveMenu('report/product');
        $this->_addContent($this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportneworders'));
        $this->renderLayout();
    }
    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportneworders_grid')->toHtml()
        );
    }

    public function exportCsvAction() {
        
        $fileName = 'export-report_new_orders.csv';
        $content = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportneworders_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        
        $fileName   = 'export-report_new_orders.xml';
        $content    = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportneworders_grid')->getExcel($fileName);
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
}
