# catalog_provider_bitrix
добавлеие в корзину пример

// ADDTOBASKET
                $result = \Bitrix\Catalog\PriceTable::getList([
                    "filter" => [
                        "PRODUCT_ID" => $_REQUEST["item"],
                    ]
                ]);
                while($temp = $result->Fetch()) {
                    $arResult['price'][$temp['CATALOG_GROUP_ID']] = $temp;
                }

                $item = $basket->createItem('catalog', $_REQUEST["item"]);
                $arPropItemBasket = [
                    'QUANTITY' => $_REQUEST["quantity"],
                    'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                    'LID' => 's1',
//                    'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
                    'PRODUCT_PROVIDER_CLASS' => '\Qs\Sale\CatalogProductProvider'
                ];

//                if($arResult['PRICE_TYPE_ID'] == 2 && $arResult['price'][$arResult['PRICE_TYPE_ID']]['PRICE']) {
////                    $arPropItemBasket['PRICE'] = $arResult['price'][$arResult['PRICE_TYPE_ID']]['PRICE'];
//                    $arPropItemBasket['CUSTOM_PRICE'] = 'Y';
//                }
                $item->setFields($arPropItemBasket);

                $basket->save();
                if(!$item) {
                    if ($ex = $APPLICATION->GetException())
                        $strErrorExt = $ex->GetString();

                    $strError = "ERROR_ADD2BASKET";
                    $successfulAdd = false;
                }

                ////end
