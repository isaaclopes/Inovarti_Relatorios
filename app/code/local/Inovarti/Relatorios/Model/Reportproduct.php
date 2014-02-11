<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Model_Reportproduct extends Mage_Reports_Model_Mysql4_Product_Ordered_Collection {

    function __construct() {
        parent::__construct();
    }

    protected function _joinFields($from = '', $to = '') {
        $this->addAttributeToSelect('name')
                ->addAttributeToSelect('increment_id')
                ->addOrderedQty($from, $to)
                ->setOrder('sku', self::SORT_ORDER_ASC);

        //Mage::log('SQL: '.$this->getSelect()->__toString());
        return $this;
    }

    public function addOrderedQty($from = '', $to = '') {

        $adapter = $this->getConnection();
        $compositeTypeIds = Mage::getSingleton('catalog/product_type')->getCompositeTypes();
        $orderTableAliasName = $adapter->quoteIdentifier('order');

        $orderJoinCondition = array(
            $orderTableAliasName . '.entity_id = order_items.order_id',
            $adapter->quoteInto("{$orderTableAliasName}.state <> ?", Mage_Sales_Model_Order::STATE_CANCELED),
        );
        $productJoinCondition = array(
            $adapter->quoteInto('(e.type_id NOT IN (?))', $compositeTypeIds),
            'e.entity_id = order_items.product_id',
            $adapter->quoteInto('e.entity_type_id = ?', $this->getProductEntityTypeId())
        );

        if ($from != '' && $to != '') {
            $fieldName = $orderTableAliasName . '.created_at';
            $orderJoinCondition[] = $this->_prepareBetweenSql($fieldName, $from, $to);
        }

        $this->getSelect()->reset()
                ->from(array('order_items' => $this->getTable('sales/order_item')), array(
                    'qty_ordered' => 'SUM(order_items.qty_ordered)',
                    'qty_pending' => 'SUM((SELECT order_items.qty_ordered FROM sales_flat_order P WHERE order_items.order_id= P.entity_id and P.status in (\'pending_payment\',\'payment_review\',\'pending\')  ))',
                    'qty_paid' => 'SUM((SELECT order_items.qty_ordered FROM sales_flat_order P01 WHERE order_items.order_id= P01.entity_id and P01.status in (\'pagamento_confirmado\',\'verificado_antifraude\',\'verificado_antifraude_capturado_\',\'processing\')  ))',
                    'order_items_name' => 'order_items.name'
                ))
                ->joinInner(
                        array('order' => $this->getTable('sales/order')), implode(' AND ', $orderJoinCondition), array())
                ->joinLeft(
                        array('e' => $this->getProductEntityTableName()), implode(' AND ', $productJoinCondition), array(
                    'sku' => 'e.sku',
                    'created_at' => 'e.created_at',
                    'updated_at' => 'e.updated_at'
                ))
                ->joinInner(array(
                    's' => $this->getTable('cataloginventory/stock_item')), "order_items.product_id = s.product_id", array(
                    "s.qty"), null)
                ->where('parent_item_id IS NULL')
                ->group('order_items.product_id')
                ->having('SUM(order_items.qty_ordered) > ?', 0);

        //echo $this->getSelect()->__toString();

        return $this;
    }

    public function addItem(Varien_Object $item) {
        $itemId = $this->_getItemId($item);

        if (!is_null($itemId)) {
            if (isset($this->_items[$itemId])) {
                //throw new Exception('Item ('.get_class($item).') with the same id "'.$item->getId().'" already exist');
            }
            $this->_items[$itemId] = $item;
        } else {
            $this->_items[] = $item;
        }
        return $this;
    }

}
