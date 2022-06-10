<?
namespace Qs\Sale;
use \Bitrix\Main\Loader,
    \Bitrix\Main\Localization\Loc;
Loader::includeModule('catalog');
Loader::includeModule('sale');
Loader::includeModule('iblock');
Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


class CatalogProductProvider extends \CCatalogProductProvider
{
    public static function GetProductData($params)
    {
        $result = parent::GetProductData($params);
        $productPrice = getPrice($params['PRODUCT_ID']);
        $arDiscount = self::getDiscount();
        foreach ($arDiscount as $discount) {
            if(in_array($discount['section'], self::getGroups($params['PRODUCT_ID']))) {
//                $productPrice['PRICE'] = round($productPrice['PRICE'] - $productPrice['PRICE'] * $discount['percent'] / 100);
//                RewriteFile($_SERVER['DOCUMENT_ROOT'].'/log11.txt', print_r($productPrice['PRICE'], true));
//                RewriteFile($_SERVER['DOCUMENT_ROOT'].'/log111.txt', print_r(self::getDiscount(), true));
            }
        }

        global $USER;
//        $arPrice = CCatalogProduct::GetOptimalPrice($params['PRODUCT_ID'], $params['QUANTITY'], $USER->GetUserGroupArray(), 'N');
        $result = [
            'BASE_PRICE' => $productPrice['PRICE'],
            'PRICE' => ($productPrice['DISCOUNT_PRICE']) ? $productPrice['DISCOUNT_PRICE'] : $productPrice['PRICE'],
        ] + $result;

        if ($productPrice['DISCOUNT_VALUE']){
            $result = [
                'DISCOUNT_PRICE' => $productPrice['PRICE'] - $productPrice['DISCOUNT_PRICE'],
                'DISCOUNT_VALUE' => $productPrice['DISCOUNT_VALUE'],
            ] + $result;
        }
        RewriteFile($_SERVER['DOCUMENT_ROOT'].'/log.txt', print_r($result, true));
        return $result;
    }
    public static function OrderProduct($params) {
        $result = parent::OrderProduct($params);
        return $result;
    }
    public function getDiscount() {
        $hlbl = 7;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
//    "filter" => array("UF_PRODUCT_ID"=>"77","UF_TYPE"=>'33')
        ));
        $discount = [];
        while($arData = $rsData->Fetch()){
            $discount[$arData['ID']]['percent'] = $arData['UF_DISCONT_NUM'];
            $discount[$arData['ID']]['section'] = $arData['UF_SECTION'];
        }
        return $discount;
    }
    public static function getGroups($id) {
        $ar_groups = [];
        $el = new \CIBlockElement;
        $db = $el->GetElementGroups($id, true);
        while($ar_group = $db->GetNext()) {
            $ar_groups[] = $ar_group["ID"];
        }
        return $ar_groups;
    }
}







