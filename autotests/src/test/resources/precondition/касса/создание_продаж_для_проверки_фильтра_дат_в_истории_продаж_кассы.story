Meta:
@smoke
@sprint_42
@us_112.1
@precondition

Scenario: Сценарий для создания продаж для проверки фильтра дат в истории продаж кассы

Given пользователь создает чек c датой 'saleTodayDate-7'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '250'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '350'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '4' и по цене '60'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 's41u1111@lighthouse.pro' в магазине с именем 'store-s41u1111' совершает продажу по созданному чеку
Given пользователь создает чек c датой 'saleTodayDate-7'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '350'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '4' и по цене '60'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 's41u1111@lighthouse.pro' в магазине с именем 'store-s41u1111' совершает продажу по созданному чеку
Given пользователь создает чек c датой 'saleTodayDate-6'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '350'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '4' и по цене '60'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 's41u1111@lighthouse.pro' в магазине с именем 'store-s41u1111' совершает продажу по созданному чеку
Given пользователь создает чек c датой 'saleTodayDate-5'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '250'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '4' и по цене '60'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 's41u1111@lighthouse.pro' в магазине с именем 'store-s41u1111' совершает продажу по созданному чеку
Given пользователь создает чек c датой 'saleTodayDate-4'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '250'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '350'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 's41u1111@lighthouse.pro' в магазине с именем 'store-s41u1111' совершает продажу по созданному чеку
Given пользователь создает чек c датой 'saleTodayDate-4'
And пользователь добавляет товар в чек с именем 'pos-product1', количеством '1' и по цене '350'
And пользователь вносит наличные в размере '1000' рублей
And пользователь с адресом электронной почты 's41u1111@lighthouse.pro' в магазине с именем 'store-s41u1111' совершает продажу по созданному чеку