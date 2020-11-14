/**
 * PPOM Conditional Version 2
 * More Fast and Optimized
 * April, 2020 in LockedDown (CORVID-19)
 * */

var ppom_hidden_fields = [];

jQuery(function($) {

    setTimeout(function() {
        $('form.cart').find('select option:selected, input[type="radio"]:checked, input[type="checkbox"]:checked').each(function(i, field) {

            if ($(field).closest('div.ppom-field-wrapper').hasClass('ppom-c-hide')) return;
            const data_name = $(field).data('data_name');
            ppom_check_conditions(data_name);
        });

        $('form.cart').find('div.ppom-c-show').each(function(i, field) {

            const data_name = $(field).data('data_name');
            ppom_check_conditions(data_name);
        });

    }, 100);

    // $('form.cart').on('change', 'select, input[type="radio"], input[type="checkbox"]', function(ev) {
    $('form.cart').find('select, input[type="radio"], input[type="checkbox"]').bind('change', function(ev) {

        const data_name = $(this).data('data_name');
        // console.log("Checking condition for ", data_name);
        ppom_check_conditions(data_name, function(visibility) {

            ppom_hidden_fields = [];
            $('.ppom-wrapper').find('.ppom-c-hide').each(function(i, field) {
                const data_name = $(field).data('data_name');
                ppom_hidden_fields.push(data_name);
                ppom_set_default_option(data_name);
                $("#conditionally_hidden").val(ppom_hidden_fields);
            });
        });

    });

    $(document).bind('ppom_hidden_fields_updated', function(e) {

        // $("#conditionally_hidden").val(ppom_hidden_fields);
        ppom_check_conditions(e.field);
    });


    $(document).bind('ppom_field_hidden', function(e) {

        console.log(`hidden event ${e.field}`);
        // if field is already hidden


        // ppom_get_field_type_by_id()

        const type = ppom_get_input_dom_type(e.field);
        const data_name = e.field;

        // console.log(` Type==> ${type}`);
        switch (type) {
            case 'checkbox':
            case 'radio':
            case 'imageselect':
                $(`.ppom-input[data-data_name="${data_name}"]`).prop('checked', false);
                break;
            case 'select':
                $(`.ppom-input[data-data_name="${data_name}"]`).val('');
                break;
            case 'file':
                $(`#filelist-${data_name}`).html('');
                break;
            default:
                $(`.ppom-input[data-data_name="${data_name}"]`).val('').trigger('change');
                break;
        }

        // ppom_hidden_fields.push(e.field);
        $.event.trigger({
            type: "ppom_hidden_fields_updated",
            field: e.field,
            time: new Date()
        });
    });

    $(document).bind('ppom_field_shown', function(e) {

        // Remove from array
        // $.each(ppom_hidden_fields, function(i, item) {
        //     if (item === e.field) {

        //         // Set checked/selected again
        //         ppom_set_default_option(item);

        //         ppom_hidden_fields.splice(i, 1);
        //         $.event.trigger({
        //             type: "ppom_hidden_fields_updated",
        //             field: e.field,
        //             time: new Date()
        //         });

        //     }
        // });

        console.log(`shown event ${e.field}`);
        ppom_check_conditions(e.field);
    });

});

function ppom_check_conditions(data_name, callback) {
    // const all_conds = jQuery("div[data-cond='1']");
    const field_obj = jQuery(`.ppom-input[data-data_name="${data_name}"]`);

    const cond_class = `ppom-cond-${data_name}`;
    const all_conds = jQuery(`div.${cond_class}`);
    // console.log(all_conds);
    // const $ = jQuery
    jQuery.each(all_conds, function(i, input) {
        // console.log(input);

        const total_cond = parseInt(jQuery(this).data('cond-total'));
        const binding = jQuery(this).data(`cond-bind`);
        const visibility = jQuery(this).data(`cond-visibility`);
        const element_data_name = jQuery(this).data('data_name');

        let matched = 0;


        for (var t = 1; t <= total_cond; t++) {

            const cond_element = jQuery(this).data(`cond-input${t}`);
            const cond_val = jQuery(this).data(`cond-val${t}`);
            const operator = jQuery(this).data(`cond-operator${t}`);

            // const field_val = ppom_get_field_type(field_obj);
            if (cond_element !== data_name) continue;
            const ppom_type = field_obj.closest('.ppom-field-wrapper').data('type');

            // console.log(`PPOM Type ${ppom_type}`);

            let element_value = '';
            switch (ppom_type) {
                case 'radio':
                case 'checkbox':
                    element_value = jQuery(`.ppom-input[value="${cond_val}"]:checked`).val();
                    break;
                case 'image':
                    element_value = jQuery(`.ppom-input[data-label="${cond_val}"]:checked`).data('label');
                    break;
                case 'imageselect':
                    element_value = jQuery(`.ppom-input[data-title="${cond_val}"]:checked`).data('title');
                    break;

                default:
                    element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]`).val();
            }

            let is_matched = false;
            is_matched = ppom_compare_values(element_value, cond_val, operator);


            // console.log(` ${element_value} === ${cond_val}`);
            if (is_matched) {
                matched++;
            }

        }

        // console.log(`matched ${matched} === ${total_cond}`);
        // console.log(`binding ${binding}`);



        if ((matched === total_cond) || (matched > 0 && binding === 'any')) {
            jQuery(this).removeClass('ppom-c-show ppom-c-hide ppom-c-matched');

            const a_class = visibility === 'hide' ? 'ppom-c-hide' : 'ppom-c-show';
            const event_type = visibility === 'hide' ? 'ppom_field_hidden' : 'ppom_field_shown';
            jQuery(this).addClass('ppom-c-matched');

            jQuery(this).addClass(a_class)
                .trigger({
                    type: event_type,
                    field: element_data_name,
                    time: new Date()
                });
        }
        else {
            //jQuery(this).addClass('ppom-c-hide')
            const a_class = visibility === 'hide' ? 'ppom-c-show' : 'ppom-c-hide';
            const event_type = visibility === 'hide' ? 'ppom_field_shown' : 'ppom_field_hidden';

            if (event_type === 'ppom_field_hidden' && jQuery(this).hasClass('ppom-c-hide')) return;

            jQuery(this).removeClass('ppom-c-show ppom-c-hide ppom-c-matched');

            jQuery(this).addClass(a_class)
                .trigger({
                    type: event_type,
                    field: element_data_name,
                    time: new Date()
                });
        }

        if (i + 1 === all_conds.length) {
            if (typeof callback == "function")
                callback(element_data_name);
        }
    });

}


function ppom_get_input_dom_type(data_name) {

    // const field_obj = jQuery(`input[name="ppom[fields][${data_name}]"], input[name="ppom[fields][${data_name}[]]"], select[name="ppom[fields][${data_name}]"]`);
    const field_obj = jQuery(`.ppom-input[data-data_name="${data_name}"]`);
    const ppom_type = field_obj.closest('.ppom-field-wrapper').data('type');
    return ppom_type;

}

function ppom_compare_values(v1, v2, operator) {

    let result = false;
    switch (operator) {
        case 'is':
            result = v1 === v2 ? true : false;
            break;
        case 'not':
            result = v1 !== v2 ? true : false;
            break;

        case 'greater than':
            result = parseFloat(v1) > parseFloat(v2) ? true : false;
            break;
        case 'less than':
            result = parseFloat(v1) < parseFloat(v2) ? true : false;
            break;

        default:
            // code
    }
    return result;
}

function ppom_set_default_option(field_id) {

    // get product id
    var product_id = ppom_input_vars.product_id;

    var field = ppom_get_field_meta_by_id(field_id);

    switch (field.type) {

        // Check if field is 
        case 'radio':
            jQuery.each(field.options, function(label, options) {
                var opt_id = product_id + '-' + field.data_name + '-' + options.id;

                if (options.option == field.selected) {
                    jQuery("#" + opt_id).prop('checked', true);
                }
            });

            break;

        case 'select':
            jQuery("#" + field.data_name).val(field.selected);
            break;

        case 'image':
            jQuery.each(field.images, function(index, img) {

                if (img.title == field.selected) {
                    jQuery("#" + field.data_name + '-' + img.id).prop('checked', true);
                }
            });
            break;

        case 'checkbox':
            jQuery.each(field.options, function(label, options) {
                var opt_id = product_id + '-' + field.data_name + '-' + options.id;

                var default_checked = field.checked.split('\r\n');
                if (jQuery.inArray(options.option, default_checked) > -1) {
                    jQuery("#" + opt_id).prop('checked', true);

                }
            });
            break;

        case 'text':
        case 'date':
        case 'number':
            jQuery("#" + field.data_name).val(field.default_value);
            break;
    }
}
