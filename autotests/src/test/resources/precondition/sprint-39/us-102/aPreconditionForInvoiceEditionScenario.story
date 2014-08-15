Meta:
@smoke
@sprint_39
@us_102
@id_s39u102s2
@id_s39u102s12

Scenario: Сценарий для подготовки тестовых данных для сценария редактирования накладной

Given пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает магазин с именем 's39u102-store1' и адресом 's39u102-store1'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает поставщика с именем 's39u102-supplier1', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает группу с именем 's39u102-group1'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает продукт с именем 's39u102-product2', еденицами измерения 'Пятюня', штрихкодом 's39u102barcode2', НДС '0', ценой закупки '125,5' и ценой продажи '110' в группе с именем 's39u102-group1'