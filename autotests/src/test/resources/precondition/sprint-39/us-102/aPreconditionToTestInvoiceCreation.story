Meta:
@smoke
@sprint_39
@us_102
@id_s39u102s2
@id_s39u102s3
@id_s39u102s6
@id_s39u102s9
@id_s39u102s10
@id_s39u102s12

Scenario: Cценарий для создания накладной

Given пользователь создает апи объект накладной с датой '28.07.2014', статусом Оплачено 'false', магазином с именем 's39u102-store', поставщиком с именем 's39u102-supplier'
And пользователь добавляет продукт с именем 's39u102-product1', ценой '150' и количеством '5' к апи объекту накладной
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает накладную через конструктор накладных