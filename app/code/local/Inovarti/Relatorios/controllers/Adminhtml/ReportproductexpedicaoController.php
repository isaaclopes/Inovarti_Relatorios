<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Adminhtml_ReportproductexpedicaoController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->_title($this->__('Reports'))
                ->_title($this->__('Products'))
                ->_title($this->__('Inovarti Produtos Pedidos Expedição'));
        $this->loadLayout();
        $this->_setActiveMenu('report/product');
        $this->_addContent($this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproductexpedicao'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproductexpedicao_grid')->toHtml()
        );
    }

    public function exportCsvAction() {

        $fileName = 'stock_productexpedicao_inovarti.csv';
        $content = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproductexpedicao_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    public function exportXmlAction() {
        
        $fileName   = 'stock_productexpedicao_inovarti.xml';
        $content    = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproductexpedicao_grid')->getExcel($fileName);
        $this->_prepareDownloadResponse($fileName, $content);
    }
}
