Meta:
@smoke
@sprint_39
@us_102
@id_s39u102s1
@id_s39u102s2
@id_s39u102s3
@id_s39u102s6
@id_s39u102s9
@id_s39u102s10
@id_s39u102s12

Scenario: Сценарий для подготовки тестовых данных

Given пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает магазин с именем 's39u102-store' и адресом 's39u102-store'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает поставщика с именем 's39u102-supplier', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает группу с именем 's39u102-group'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает продукт с именем 's39u102-product1', еденицами измерения 'шт.', штрихкодом 's39u102barcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 's39u102-group'