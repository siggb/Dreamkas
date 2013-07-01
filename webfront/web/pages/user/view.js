define(function(require) {
    //requirements
    var $ = require('jquery'),
        Page = require('pages/page'),
        User = require('blocks/user/user'),
        UserModel = require('models/user'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/403'),
        LH = require('LH');

    return Page.extend({
        pageName: 'page_user_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function(userId) {
            var page = this;

            if (!(LH.isAllow('users', 'get') || userId === currentUserModel.id)){
                new Page403();
                return;
            }

            page.userId = userId;

            page.userModel = new UserModel({
                id: page.userId
            });

            $.when(page.userModel.fetch()).then(function(){
                page.render();

                new User({
                    model: page.userModel,
                    el: document.getElementById('user')
                });
            });
        }
    });
});