<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Model_Reportneworders extends Mage_Reports_Model_Mysql4_Product_Ordered_Collection {

    function __construct() {
        parent::__construct();
    }

    /**
     * Join fields
     *
     * @param int $from
     * @param int $to
     * @return Mage_Reports_Model_Resource_Product_Ordered_Collection
     */
    protected function _joinFields($from = '', $to = '') {
        $this->addAttributeToSelect('name')
                ->addAttributeToSelect('increment_id')
                ->addOrderedQty($from, $to)
                ->setOrder('sku', self::SORT_ORDER_ASC);

        //Mage::log('SQL: '.$this->getSelect()->__toString());
        return $this;
    }

    /**
     * Add ordered qty's
     *
     * @param string $from
     * @param string $to
     * @return Mage_Reports_Model_Resource_Product_Collection
     */
    public function addOrderedQty($from = '', $to = '') {
        $adapter = $this->getConnection();
        $compositeTypeIds = Mage::getSingleton('catalog/product_type')->getCompositeTypes();
        $orderTableAliasName = $adapter->quoteIdentifier('order');
        $addressTableAliasName = 'a';

        $orderJoinCondition = array(
            $orderTableAliasName . '.entity_id = order_items.order_id',
            $adapter->quoteInto("{$orderTableAliasName}.status in ('pagamento_confirmado','verificado_antifraude','verificado_antifraude_capturado_','processing') ", ""),
        );
        $paymentJoinCondition = array(
            'payment.parent_id = order_items.order_id',
        );
        $addressJoinCondition = array(
            $addressTableAliasName . '.entity_id = order.shipping_address_id'
        );

        $productJoinCondition = array(
            //$adapter->quoteInto('(e.type_id NOT IN (?))', $compositeTypeIds),
            'e.entity_id = order_items.product_id',
            $adapter->quoteInto('e.entity_type_id = ?', $this->getProductEntityTypeId())
        );

        if ($from != '' && $to != '') {
            $fieldName = $orderTableAliasName . '.created_at';
            $orderJoinCondition[] = $this->_prepareBetweenSql($fieldName, $from, $to);
        }

        $this->getSelect()->reset()
                ->from(
                        array('order_items' => $this->getTable('sales/order_item')), array(
                    'qty_ordered' => 'order_items.qty_ordered',
                    'order_items_name' => 'order_items.name',
                    'order_id' => 'order_items.order_id',
                    'order_increment_id' => 'order.increment_id',
                    'sku' => 'order_items.sku',
                    'type_id' => 'order_items.product_type',
                    'shipping_address_id' => 'order.shipping_address_id'
                        )
                )
                ->joinInner(
                        array('order' => $this->getTable('sales/order')), implode(' AND ', $orderJoinCondition), array(
                    "grand_total" => "order.grand_total",
                ))
                ->joinInner(
                        array('payment' => $this->getTable('sales/order_payment')), implode(' AND ', $paymentJoinCondition), array(
                    "method" => "payment.method",
                    "cctype" => "payment.cc_type",
                    "additional_information" => "payment.additional_information",
                ))
                ->joinLeft(
                        array('a' => $this->getTable('sales/order_address')), implode(' AND ', $addressJoinCondition), array(
                    'shipto_name' => "CONCAT(COALESCE(a.firstname, ''), ' ', COALESCE(a.lastname, ''))",
                    'shipto_address' => "CONCAT(COALESCE(a.street, ''), ' - ', COALESCE(a.city, ''))"
                        ), array())
                ->joinLeft(array('t' => $this->getTable('sales/shipment_track')), 't.order_id = order_items.order_id', array(
                    'TrackingNumber' => 'track_number'
                ))
                ->joinLeft(
                        array('e' => $this->getProductEntityTableName()), implode(' AND ', $productJoinCondition), array(
                    'created_at' => 'e.created_at',
                    'updated_at' => 'e.updated_at'
                ))
                ->where('parent_item_id IS NULL')
                ->where('t.carrier_code IS NULL')
                //->group('order_items.product_id')
                ->having('order_items.qty_ordered > ?', 0);
        //echo $this->getSelect()->__toString();
        return $this;
    }

    /**
     * Adding item to item array
     *
     * @param   Varien_Object $item
     * @return  Varien_Data_Collection
     */
    public function addItem(Varien_Object $item) {
        $itemId = $this->_getItemId($item);

        if (!is_null($itemId)) {
            if (isset($this->_items[$itemId])) {
                // Unnecessary exception - http://www.magentocommerce.com/boards/viewthread/10634/P0/
                //throw new Exception('Item ('.get_class($item).') with the same id "'.$item->getId().'" already exist');
            }
            $this->_items[$itemId] = $item;
        } else {
            $this->_items[] = $item;
        }
        return $this;
    }

}
