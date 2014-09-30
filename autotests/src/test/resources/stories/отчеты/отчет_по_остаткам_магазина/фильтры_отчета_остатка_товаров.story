Meta:
@sprint_43
@us_115.1

Narrative:
Как владелец,
Я хочу просматривать отчет по остаткам для каждого магазина,
Чтобы легче принимать решение о заказе товаров

Scenario: Отчет по остаткам загружается сразу если магазин всего один

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story

Given пользователь открывает стартовую страницу авторизации
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на пункт меню 'Отчеты' в боковом меню навигации
And пользователь нажимает на кнопку отчетов с названием 'Остатки товаров'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет поля
| elementName | value |
| фильтр магазинов | store-reports |

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 0 дн. | 0,0 шт. / дн. | 0,0 шт. |

Scenario: Отчет по остаткам не загружается сразу если магазин больше чем один

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story

Given пользователь с адресом электронной почты 'reports@lighthouse.pro' создает магазин с именем 'store-reports-2' и адресом 'address'

Given пользователь открывает стартовую страницу авторизации
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на пункт меню 'Отчеты' в боковом меню навигации
And пользователь нажимает на кнопку отчетов с названием 'Остатки товаров'

When пользователь* находится на странице 'странице отчета остатка товаров'

Then пользователь* проверяет поля
| elementName | value |
| фильтр магазинов | Выберите магазин |

Then пользователь проверяет, что на странице присутствует текст 'Для просмотра остатков выберите магазин.'

When пользователь* вводит данные в поля
| elementName | value |
| фильтр магазинов | store-reports-2 |

Then пользователь* проверяет, что список 'отчета остатка товаров' содержит точные данные
| название | штрихкод | запас | расход | остаток |
| reports-product1 | reports-barcode-1 | 0 дн. | 0,0 шт. / дн. | 0,0 шт. |

Scenario: Фильтрация отстатков по магазину

Meta:
@smoke
@ignore
@skip

Scenario: Фильтрация отстатков по группе

Meta:
@smoke
@ignore
@skip

Scenario: Фильтрация отстатков по магазину и группе

Meta:
@smoke
@ignore
@skip

Scenario: Фильтр по группе в которой не содержится товаров

Meta:
@ignore
@skip

Then пользователь проверяет, что на странице присутствует текст 'Нет ни одного продукта в справочнике. Для просмотра остатков добавьте хотя бы один продукт.'