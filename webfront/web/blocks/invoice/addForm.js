define(
    [
        '/kit/form/form.js'
    ],
    function(form) {
        return form.extend({
            initialize: function(){
                var block = this;

                block.autocompleteToInput(block.$el.find("[lh_product_autocomplete='name']"));
                block.autocompleteToInput(block.$el.find("[lh_product_autocomplete='sku']"));
                block.autocompleteToInput(block.$el.find("[lh_product_autocomplete='barcode']"));
            },
            showErrors: function(data) {
                var block = this;

                block.removeErrors();

                _.each(data.children, function(data, field) {
                    var fieldErrors;

                    if (data.errors) {
                        fieldErrors = data.errors.join(', ');
                        if (field == 'product') {
                            var productField;
                            if (block.$el.find("[lh_product_autocomplete='barcode']").val()) {
                                productField = 'barcode';
                            } else if (block.$el.find("[lh_product_autocomplete='sku']").val()) {
                                productField = 'sku';
                            } else {
                                productField = 'name';
                            }

                            block.$el.find("[lh_product_autocomplete='" + productField + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
                        } else {
                            block.$el.find("[name='" + field + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
                        }
                    }
                });
            },
            disable: function(disabled){
                var block = this;
                if (disabled) {
                    block.$el.find('[type=submit]').closest('.button').addClass('button_disabled');
                } else {
                    block.$el.find('[type=submit]').closest('.button').removeClass('button_disabled');
                }
            },
            autocompleteToInput: function($input) {
                var name = $input.attr('lh_product_autocomplete');
                $input.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: baseApiUrl + "/products/" + name + "/search.json",
                            dataType: "json",
                            data: {
                                query: request.term
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        label: item[name],
                                        product: item
                                    }
                                }));
                            }
                        })
                    },
                    minLength: 3,
                    select: function(event, ui) {
                        $(this).parents("form").find("[lh_product_autocomplete='name']").val(ui.item.product.name);
                        $(this).parents("form").find("[lh_product_autocomplete='sku']").val(ui.item.product.sku);
                        $(this).parents("form").find("[lh_product_autocomplete='barcode']").val(ui.item.product.barcode);
                        $(this).parents("form").find("[name='product']").val(ui.item.product.id);

                        $(this).parents("form").find("[name='quantity']").focus();
                    }
                });
                $input.keyup(function(event) {
                    var keyCode = $.ui.keyCode;
                    switch (event.keyCode) {
                        case keyCode.PAGE_UP:
                        case keyCode.PAGE_DOWN:
                        case keyCode.UP:
                        case keyCode.DOWN:
                        case keyCode.ENTER:
                        case keyCode.NUMPAD_ENTER:
                        case keyCode.TAB:
                        case keyCode.LEFT:
                        case keyCode.RIGHT:
                        case keyCode.ESCAPE:
                            return;
                            break;
                        default:
                            var term = $(this).autocomplete('getTerm');
                            if (term != $(this).val()) {
                                var inputs = ['name', 'sku', 'barcode'];
                                for (var i in inputs) {
                                    if (inputs[i] != name) {
                                        $(this).parents("form").find("[lh_product_autocomplete='" + inputs[i] + "']").val('').trigger('input');
                                    }
                                }
                                $(this).parents("form").find("[name='product']").val('');
                            }
                    }
                });
            }
        });
    }
);