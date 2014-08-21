Meta:
@sprint_40
@us_104

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять оприходывания товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Создание оприходывания

Meta:
@smoke
@id_s40u104s1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходывать на странице товародвижения

When пользователь* находится в модальном окне 'модальное окно создания оприходывания'
And пользователь* вводит данные в поля
| elementName | value |
| date | 08.11.2014 |
| store | s40u104-store |
| product.name | s40u104-product1 |
| price | 150 |
| quantity | 5 |
And пользователь нажимает на кнопку добавления нового товара в оприходывание

Then пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь проверяет, что сумма итого равна '750,00' в модальном окне создания оприходывания

When пользователь нажимает на кнопку Оприходывать, чтобы оприходывать товары

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | sumTotal |
| 08.11.2014 | Оприходывание | В s40u104-store | 750,00 |

When пользователь нажимает на оприходывание с номером '10001' на странице товародвижения

When пользователь* находится в модальном окне 'модальное окно редактирования оприходывания'

Then пользователь* проверяет поля
| elementName | value |
| date | 08.11.2014 |
| store | s40u104-store |
And пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
And пользователь проверяет, что сумма итого равна '750,00' в модальном окне редактирования оприходывания

Scenario: Редактирование оприходывания

Meta:
@smoke
@id_s40u104s2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionForStockInEditionScenario.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь* находится в модальном окне 'модальное окно редактирования оприходывания'
And пользователь* вводит данные в поля
| elementName | value |
| date | 08.11.2014 |
| store | s40u104-store1 |
| product.name | s40u104-product2 |
| cause | причина |
And пользователь нажимает на кнопку добавления нового товара в оприходывание в модальном окне редактирования оприходывания

Then пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 11,99  | 2,0 шт. | 23,98 |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '149,48' в модальном окне редактирования оприходывания

When пользователь нажимает на кнопку сохранения оприходывания в модальном окне редактирования оприходывания

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | sumTotal |
| 08.11.2014 | Оприходывание | В s40u104-store1 | 149,48 |

When пользователь нажимает на оприходывание с номером '10001' на странице товародвижения

When пользователь* находится в модальном окне 'модальное окно редактирования оприходывания'

Then пользователь* проверяет поля
| elementName | value |
| date | 08.11.2014 |
| store | s40u104-store1 |
Then пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 11,99  | 2,0 шт. | 23,98 |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '149,48' в модальном окне редактирования оприходывания

Scenario: Удаление оприходывания

Meta:
@smoke
@id_s40u104s3

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходывание с помощью конструктора оприходываний на странице товародвижения
And пользователь нажимает на кнопку удаления оприходывания в модальном окне редактирования оприходывания
And пользователь подтверждает удаление списания в модальном окне редактирования списания

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет, что в операциях товародвижения отсутствует последнее созданное оприходывание

Scenario: Проверка заголовка модального окна создания оприходывания

Meta:
@id_s40u104s4

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходывать на странице товародвижения
And пользователь* находится в модальном окне 'модальное окно создания оприходывания'

Then пользователь* проверяет, что заголовок модального окна равен 'Оприходывание товаров'

Scenario: Проверка заголовка модального окна редактирования оприходывания

Meta:
@id_s40u104s5


GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходывание с помощью конструктора оприходываний на странице товародвижения
And пользователь* находится в модальном окне 'модальное окно редактирования оприходывания'

Then пользователь* проверяет, что заголовок модального окна равен 'Редактирование оприходывания товаров'

Scenario: Поле дата выставляется автоматически при создании оприходывания и равно сегодняшней дате

Meta:
@id_s40u104s6
@smoke

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на кнопку Оприходывать на странице товародвижения

Then пользователь проверяет, что поле дата заполнено сегодняшней датой в модальном окне создания оприходывания

Scenario: Удаление продукта в оприходывании

Meta:
@smoke
@id_s40u104s7

GivenStories: precondition/stockIn/aPreconditionToUserCreation.story,
              precondition/stockIn/aPreconditionToTestDataCreation.story,
              precondition/stockIn/aPreconditionToTestStockInCreation.story,
              precondition/stockIn/aPreconditionForStockInEditionScenario.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u104@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на последнее созданное оприходывание с помощью конструктора оприходываний на странице товародвижения
When пользователь* находится в модальном окне 'модальное окно редактирования оприходывания'
And пользователь* вводит данные в поля
| elementName | value |
| date | 08.11.2014 |
| store | s40u104-store1 |
| product.name | s40u104-product2 |
| cause | причина |
And пользователь нажимает на кнопку добавления нового товара в оприходывание в модальном окне редактирования оприходывания

Then пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product1 | 11,99  | 2,0 шт. | 23,98 |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '149,48' в модальном окне редактирования оприходывания

When пользователь удаляет товар с названием 's40u104-product1' в модальном окне редактирования оприходывания

Then пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '125,50' в модальном окне редактирования оприходывания

When пользователь нажимает на кнопку сохранения оприходывания в модальном окне редактирования оприходывания

Then пользователь ждет пока скроется модальное окно

Then пользователь проверяет операции на странице товародвижения
| date | type | store | sumTotal |
| 08.11.2014 | Оприходывание | В s40u104-store1 | 125,50 |

When пользователь нажимает на последнее созданное оприходывание с помощью конструктора оприходываний на странице товародвижения

Then пользователь проверяет, что список товаров в оприходывании содержит товары с данными
| name | price | quantity | totalPrice |
| s40u104-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And пользователь проверяет, что сумма итого равна '125,50' в модальном окне редактирования оприходывания