Meta:
@smoke
@precondition
@sprint_40
@us_104
@id_s40u104s7
@id_s40u104s2

Scenario: Сценарий для подготовки тестовых данных для сценария редактирования оприходования

Given пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает магазин с именем 's40u104-store1' и адресом 's40u104-store1'
And пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает группу с именем 's40u104-group1'
And пользователь с адресом электронной почты 's40u104@lighthouse.pro' создает продукт с именем 's40u104-product2', еденицами измерения 'Пятюня', штрихкодом 's40u104barcode2', НДС '0', ценой закупки '125,5' и ценой продажи '110' в группе с именем 's40u104-group1'