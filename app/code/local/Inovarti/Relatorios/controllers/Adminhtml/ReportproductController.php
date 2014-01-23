<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Adminhtml_ReportproductController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->_title($this->__('Sales'))->_title($this->__('Inovarti Relatorio Produtos Pedidos'));
        $this->loadLayout();
        $this->_setActiveMenu('reports/products');
        $this->_addContent($this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproduct'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproduct_grid')->toHtml()
        );
    }

    public function exportStockproductCsvAction() {
        $fileName = 'stock_product_inovarti.csv';
        $grid = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproduct_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function exportStockproductExcelXmlAction() {
        $fileName = 'stock_product_inovarti.xml';
        $grid = $this->getLayout()->createBlock('inovarti_relatorios/adminhtml_sales_reportproduct_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
