function SaveSettings() {
    jQuery("#loading_icon").show();
    jQuery("#save_setting").hide();
    jQuery.ajax({
        dataType : 'html',
        type: 'POST',
        url : location.href,
        cache: false,
        data : jQuery('#fb-setting-form').serialize() + '&mkdev_action=save_setting',
        complete : function() {  },
        success: function(data) {
            jQuery("#loading_icon").hide();
            jQuery("#save_setting").show();
        }
    });
}

jQuery(document).ready(function($){
    jQuery(document).on("change", "#share_fb_btn_layout", function(e) {
        if (jQuery(this).val() === "link") {
            jQuery("#share_fb_link_text").closest('.row').show();
        } else {
            jQuery("#share_fb_link_text").closest('.row').hide();
        }

        if (jQuery(this).val() === "icon" || jQuery("#share_in_btn_layout").val() === "icon" || jQuery(this).val() === "link" || jQuery("#share_in_btn_layout").val() === "link") {
            jQuery("#share_color").closest('.row').show();
        } else {
            jQuery("#share_color").closest('.row').hide();
        }
    });
    jQuery(document).on("change", "#share_in_btn_layout", function(e) {
        if (jQuery(this).val() === "link") {
            jQuery("#share_in_link_text").closest('.row').show();
        } else {
            jQuery("#share_in_link_text").closest('.row').hide();
        }

        if (jQuery(this).val() === "icon" || jQuery("#share_fb_btn_layout").val() === "icon" || jQuery(this).val() === "link" || jQuery("#share_fb_btn_layout").val() === "link") {
            jQuery("#share_color").closest('.row').show();
        } else {
            jQuery("#share_color").closest('.row').hide();
        }
    });

    $('#share_color').wpColorPicker();
});