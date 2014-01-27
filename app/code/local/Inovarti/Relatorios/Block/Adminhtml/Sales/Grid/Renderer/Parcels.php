<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Relatorios
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Relatorios_Block_Adminhtml_Sales_Grid_Renderer_Parcels extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $value = unserialize($row->getData($this->getColumn()->getIndex()));

        $method = (isset($value['method'])) ? $value['method'] : "";

        $types = Mage::getSingleton('payment/config')->getCcTypes();
        $Nametypes = (isset($types[$value['PaymentMethod']]) && !empty($value['PaymentMethod'])) ? $types[$value['PaymentMethod']] : "";
        $Nametypes2 = (isset($types[$value['PaymentMethod']]) && !empty($value['PaymentMethod'])) ? $types[$value['PaymentMethod']] : "";
        switch ($method) {
            case "mundipagg":
                return $Nametypes . '<br>' . $value['cc_parcelamento'] . 'x R$' . number_format($row->getData('grand_total') / $value['cc_parcelamento'], 2, ',', '.');
                break;
            case "mundipaggdoiscartao":
                $primeiro = $Nametypes . '<br>' . $value['cc_parcelamento'] . 'x R$' . number_format($value['cc_valor'] / $value['cc_parcelamento'], 2, ',', '.');
                $segundo = $Nametypes2 . '<br>' . $value['dois_cc_parcelamento'] . 'x R$' . number_format($value['dois_cc_valor'] / $value['cc_parcelamento'], 2, ',', '.');
                return $primeiro . '<br>' . $segundo;
                break;
            case "mundipagg_boleto":
                return "";
                break;
            case "mundipagg_cartaoboleto":
                return $Nametypes . '<br>' . $value['cc_parcelamento'] . 'x R$' . number_format($value['cc_valor'] / $value['cc_parcelamento'], 2, ',', '.');
                break;
            case "itaushopline_standard":
                return;
                break;
            default:
                return;
                break;
        }
    }

}
