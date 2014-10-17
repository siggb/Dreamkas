Meta:
@sprint_40
@us_105

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять возврат товаров поставщику в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Создание возврата поставщику

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/товародвижение/возврат_поставщику/создать_магазин_с_ассортиментом.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Вернуть поставщику на странице товародвижения

When пользователь* находится в модальном окне 'создания возврата поставщику'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u105-store1 |
| supplier | s40u105-supplier1 |
| product.name | s40u105-product1 |
| price | 150 |
| quantity | 5 |
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь* проверяет, что сумма итого равна '750,00'

When пользователь* нажимает на кнопку создания 'Вернуть'

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | status | sumTotal |
| 01.10.2014 | Возврат поставщику | Из s40u105-store1 | / не погашен | 750,00 |

When пользователь нажимает на оприходование с номером '10001' на странице товародвижения

When пользователь* находится в модальном окне 'редактирования возврата поставщику'

Then пользователь* в модальном окне проверяет поля
| elementName | value |
| date | 01.10.2014 |
| store | s40u105-store1|
And пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь* проверяет, что сумма итого равна '750,00'

Scenario: Создание возврата поставщику c галочкой Оплачено

Meta:
@smoke
@tech_41

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/товародвижение/возврат_поставщику/создать_магазин_с_ассортиментом.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Вернуть поставщику на странице товародвижения

When пользователь* находится в модальном окне 'создания возврата поставщику'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u105-store1 |
| supplier | s40u105-supplier1 |
| product.name | s40u105-product1 |
| price | 150 |
| quantity | 5 |
And пользователь* нажимает галочку Оплачено
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь* проверяет, что сумма итого равна '750,00'

When пользователь* нажимает на кнопку создания 'Вернуть'

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | status | sumTotal |
| 01.10.2014 | Возврат поставщику | Из s40u105-store1 | / погашен | 750,00 |

Scenario: Редактирование возврата поставщику

Meta:
@smoke
@s40u105edit

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/товародвижение/возврат_поставщику/создать_магазин_с_ассортиментом.story,
              precondition/товародвижение/возврат_поставщику/создать_возврат_поставщику.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданный возврат поставщику с помощью конструктора оприходований на странице товародвижения
And пользователь* находится в модальном окне 'редактирования возврата поставщику'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u105-store1 |
| supplier | s40u105-supplier1|
| product.name | s40u105-product2 |
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product1 | 99,13  | 10,0 шт. | 991,30 |
| s40u105-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '1 116,80'

When пользователь* в модальном окне нажимает на кнопку сохранения

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | status | sumTotal |
| 01.10.2014 | Возврат поставщику | Из s40u105-store1 | / погашен | 1 116,80 |

When пользователь нажимает на возврат поставщику с номером '10001' на странице товародвижения

When пользователь* находится в модальном окне 'редактирования возврата поставщику'

Then пользователь* в модальном окне проверяет поля
| elementName | value |
| date | 01.10.2014 |
| store | s40u105-store1 |
Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product1 | 99,13  | 10,0 шт. | 991,30 |
| s40u105-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '1 116,80'

Scenario: Удаление возврата поставщику

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/товародвижение/возврат_поставщику/создать_магазин_с_ассортиментом.story,
              precondition/товародвижение/возврат_поставщику/создать_возврат_поставщику.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданный возврат поставщику с помощью конструктора оприходований на странице товародвижения
And пользователь* находится в модальном окне 'редактирования возврата поставщику'
And пользователь* в модальном окне нажимает на кнопку удаления
And пользователь* в модальном окне подтверждает удаление

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет, что в операциях товародвижения отсутствует последний созданный возврат поставщику

Scenario: Проверка заголовка модального окна создания возврата поставщику

Meta:

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Вернуть поставщику на странице товародвижения
And пользователь* находится в модальном окне 'создания возврата поставщику'

Then пользователь* в модальном окне проверяет, что заголовок равен 'Возврат поставщику'

Scenario: Проверка заголовка модального окна редактирования возврата поставщику

Meta:

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/товародвижение/возврат_поставщику/создать_магазин_с_ассортиментом.story,
              precondition/товародвижение/возврат_поставщику/создать_возврат_поставщику.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданный возврат поставщику с помощью конструктора оприходований на странице товародвижения
And пользователь* находится в модальном окне 'редактирования возврата поставщику'

Then пользователь* в модальном окне проверяет, что заголовок равен 'Редактирование возврата'

Scenario: Поле дата выставляется автоматически при создании возврата поставщику и равно сегодняшней дате

Meta:
@smoke

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Вернуть поставщику на странице товародвижения

Then пользователь* проверяет, что поле дата товародвижения заполнено сегодняшней датой

Scenario: Удаление продукта в возврате поставщику

Meta:
@smoke
@s40u105s666

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/товародвижение/возврат_поставщику/создать_магазин_с_ассортиментом.story,
              precondition/товародвижение/возврат_поставщику/создать_возврат_поставщику.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u105@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданный возврат поставщику с помощью конструктора оприходований на странице товародвижения
When пользователь* находится в модальном окне 'редактирования возврата поставщику'
And пользователь* в модальном окне вводит данные
| elementName | value |
| date | 01.10.2014 |
| store | s40u105-store1 |
| product.name | s40u105-product2 |
And пользователь* нажимает на кнопку добавления нового товара

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product1 | 99,13  | 10,0 шт. | 991,30 |
| s40u105-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '1 116,80'

When пользователь* в модальном окне товародвижения удаляет товар с названием 's40u105-product1'

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '125,50'

When пользователь* в модальном окне нажимает на кнопку сохранения

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | status | sumTotal |
| 01.10.2014 | Возврат поставщику | Из s40u105-store1 | / погашен | 125,50 |

When пользователь нажимает на последнее созданный возврат поставщику с помощью конструктора оприходований на странице товародвижения

Then пользователь* проверяет, что список товаров содержит товары с данными
| name | price | quantity | totalPrice |
| s40u105-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь* проверяет, что сумма итого равна '125,50'