# catalog_provider_bitrix
добавлеие в корзину пример


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
                    'PRODUCT_PROVIDER_CLASS' => '\Qs\Sale\CatalogProductProvider'
                ];

                $item->setFields($arPropItemBasket);

                $basket->save();
                if(!$item) {
                    if ($ex = $APPLICATION->GetException())
                        $strErrorExt = $ex->GetString();

                    $strError = "ERROR_ADD2BASKET";
                    $successfulAdd = false;
                }

