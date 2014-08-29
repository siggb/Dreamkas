Meta:
@smoke
@precondition
@sprint_40
@us_105

Scenario: Создание пользователя, магазина и ассортимента для возврата поставщику

Given пользователь запускает консольную команду для создания пользователя с параметрами: адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

Given пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает магазин с именем 's40u105-store1' и адресом 's40u105-store1'

Given пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает поставщика с именем 's40u105-supplier1', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'

Given пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает группу с именем 's40u105-group1'
And пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает продукт с именем 's40u105-product1', еденицами измерения 'шт.', штрихкодом 's40u105Barcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 's40u105-group1'

Given пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает магазин с именем 's40u105-store2' и адресом 's40u105-store2'

Given пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает группу с именем 's40u105-group2'
And пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает продукт с именем 's40u105-product2', еденицами измерения 'Пятюня', штрихкодом 's40u105Barcode2', НДС '0', ценой закупки '125,5' и ценой продажи '110' в группе с именем 's40u105-group2'