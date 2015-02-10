$(function () {
$(".confirm_user_deletion").click(function () {
        return confirm("Do you want to delete the selected user?");
    });
    $("#reset_password_form").validate({
        rules:{login_email:{required:true,email:true},},
        messages:{login_email:{required:"Please enter a valid email address."},}
    });
    $("#create_users_form").validate({
        rules: {
            firstname: {
                required: true,
                minlength: 6
            },
            lastname: {
                required: true,
                minlength: 6
            },
            email: {
                required: true,
                email: true
            },
            quota_total: {
                required: true,
                digit: true
            },
            quota_monthly: {
                required: true,
                digit: true
            },
            quota_hour: {
                required: true,
                digit: true
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            address: {
                required: true,
                minlength: 10
            },
            city: {
                required: true,
                minlength: 2
            },
            state: {required: true,minlength: 5},
            pincode: {required: true,digit: true},
            ex_date: {required: true,},
            m_number: {required: true,minlength: 10},
            smtp_detail:{ required:true}
        },
        messages: {
            smtp_detail : {required: "Please select the smtp address tp assign to this user"},
            firstname: {
                required: "Please enter a First Name.",
                minlength: "First Name must have at-least 6 characters."
            },
            lastname: {
                required: "Please enter a Last Name.",
                minlength: "Last Name must have at-least 6 characters."
            },
            email: {
                required: "Please enter a valid email address."
            },
            quota_total: {
                required: "Please enter Total Quota.",
               digit: "Please enter a positive number"
            },
            quota_monthly: {
                required: "Please enter Monthly Quota.",
                digit: "Please enter a positive number"
            },
            quota_hour: {
                required: "Please enter ourly Quota.",
                digit: "Please enter a positive number"
            },
            password: {
                required: "Please provide a password.",
                minlength: "Password must have at-least 5 characters."
            },
            confirm_password: {
                required: "Please provide a confirmation",
                minlength: "Confirm Password must be at least 5 characters long.",
                equalTo: "Please enter the same password as above."
            },
            address: {
                required: "Please enter an Address.",
                minlength: "Address must consist of at least 10 characters long."
            },
            city: {
                required: "Please enter The City name",
                minlength: "City name must have at-least 2 characters."
            },
            state: {
                required: "Please enter The State",
                minlength: "State must have at-least 5 characters."
            },
            pincode: {
                required: "Please enter The State",
                digit: "Please enter a positive number"
            },
            ex_date: {
                required: "Please provide expiery date for this account"
            },
            m_number: {
                required: "Please enter Mobile number.",
                minlength: "Mobile number must have at-least 10 digits."
            },
        }
    });
    $("#login_form").validate({
        rules: {
            login_email: {required:true,minlength:6,email:true},
            login_password: {required:true,minlength:5}
        },
        messages: {
            login_email: { required: "Please enter a First Name.", minlength: "First Name must have at-least 6 characters." },
            login_password: {required: "Please provide a password.",minlength: "Password must have at-least 5 characters."}
        }
    });
    
    $("#signup_form").validate({
        rules: {
            signup_first_name: {required: true,minlength: 6},
            signup_last_name: {required: true,minlength: 6},
            signup_email: {required:true,minlength:6,email:true},
            signup_password: {required: true,minlength: 5},
            signup_confirm_password: {required: true,minlength: 5,equalTo: "#signup_password"},
            signup_add_1: {required: true,minlength: 10},
            signup_city: {required: true,minlength: 2},
            signup_state: {required: true, minlength: 5},
            signup_pincode: {required: true,digit: true},
        },
        messages: {
            signup_first_name: {required: "Please enter a First Name.",minlength: "First Name must have at-least 6 characters."},
            signup_last_name: {required: "Please enter a Last Name.",minlength: "Last Name must have at-least 6 characters."},
            signup_email: { required: "Please enter a First Name.", minlength: "First Name must have at-least 6 characters." },
            signup_password: {required: "Please provide a password.",minlength: "Password must have at-least 5 characters."},
            signup_confirm_password: {required: "Please provide a password.",minlength: "Password must have at-least 5 characters.",equalTo: "Please enter the same password as above."},
            signup_add_1: {required: "Please enter an Address.",minlength: "Address must consist of at least 10 characters long."},
            signup_city: {required: "Please enter The City name",minlength: "City name must have at-least 2 characters."},
            signup_state: {required: "Please enter The State",minlength: "State must have at-least 5 characters."},
            signup_pincode: {required: "Please enter The State",digit: "Please enter a positive number"},
        }
    });
    
    
    
    
    $("#change_user_details_form").validate({
        rules: {
            firstname: {
                required: true,
                minlength: 6
            },
            lastname: {
                required: true,
                minlength: 6
            },
            address: {
                required: true,
                minlength: 10
            },
            city: {
                required: true,
                minlength: 2
            },
            state: {
                required: true,
                minlength: 5
            },
            pincode: {
                required: true,
                digit: true
            },
            m_number: {
                required: true,
                minlength: 10
            },
            password: {
                required: true,
                minlength: 5
            },
        },
        messages: {
            firstname: {
                required: "Please enter a First Name.",
                minlength: "First Name must have at-least 6 characters."
            },
            lastname: {
                required: "Please enter a Last Name.",
                minlength: "Last Name must have at-least 6 characters."
            },
            address: {
                required: "Please enter an Address.",
                minlength: "Address must consist of at least 10 characters long."
            },
            city: {
                required: "Please enter The City name",
                minlength: "City name must have at-least 2 characters."
            },
            state: {
                required: "Please enter The State",
                minlength: "State must have at-least 5 characters."
            },
            pincode: {
                required: "Please enter the Pincode",
                digit: "Please enter a positive number"
            },
            m_number: {
                required: "Please enter Mobile number.",
                minlength: "Mobile number must have at-least 10 digits."
            },
            password: {
                required: "Please provide a password.",
                minlength: "Password must have at-least 5 characters."
            },
        }
    });
    $("form.edit_contact_details_form").validate({
        rules: {
            address: {
                required: true,
                minlength: 10
            },
            city: {
                required: true,
                minlength: 2
            },
            state: {
                required: true,
                minlength: 5
            },
            pincode: {
                required: true,
                digit: true
            },
            m_number: {
                required: true,
                minlength: 10
            },
        },
        messages: {
            address: {
                required: "Please enter an Address.",
                minlength: "Address must consist of at least 10 characters long."
            },
            city: {
                required: "Please enter The City name",
                minlength: "City name must have at-least 2 characters."
            },
            state: {
                required: "Please enter The State",
                minlength: "State must have at-least 5 characters."
            },
            pincode: {
                required: "Please enter The State",
                digit: "Please enter a positive number"
            },
            m_number: {
                required: "Please enter Mobile number.",
                minlength: "Mobile number must have at-least 10 digits."
            },
        }
    });
    $("form.edit_smtp_details_form").validate({
        rules: {
            s_email: {
                required: true,
                email: true
            },
            b_email: {
                required: true,
                email: true
            },
            smtp_user: {
                required: true,
                minlength: 5
            },
            smtp_host: {
                required: true,
                minlength: 8
            },
            smtp_auth: {
                required: true,
                maxlength: 3
            },
            smtp_port: {
                required: true,
                digit: true
            },
            smtp_pass: {
                required: true,
                minlength: 5
            },
            test_smtp_user: {
                required: true,
                minlength: 5
            },
            test_smtp_host: {
                required: true,
                minlength: 8
            },
            test_smtp_auth: {
                required: true,
                maxlength: 3
            },
            test_smtp_port: {
                required: true,
                digit: true
            },
            test_smtp_pass: {
                required: true,
                minlength: 5
            },
            test_s_email: {
                required: true,
                email: true
            }
        },
        messages: {
            s_email: {
                required: "Please enter a valid email address."
            },
            b_email: {
                required: "Please enter a valid email address."
            },
            smtp_user: {
                required: "Please enter SMTP User name.",
                minlength: "SMTP User must have at-least 5 characters."
            },
            smtp_host: {
                required: "Please enter SMTP Host name.",
                minlength: "SMTP Host must have at-least 8 characters."
            },
            smtp_auth: {
                required: "SMTP Auth should be 'ssl','tls' or ''",
                maxlength: "SMTP Host must have at-most 3 characters."
            },
            smtp_port: {
                required: "Please enter the SMTP Port",
                digit: "Please enter a positive number"
            },
            smtp_pass: {
                required: "Please provide a SMTP password",
                minlength: "SMTP Password must have at-least 5 characters."
            },
            test_smtp_user: {
                required: "Please enter Test-SMTP User name.",
                minlength: "Test-SMTP User must have at-least 5 characters."
            },
            test_smtp_host: {
                required: "Please enter Test-SMTP Host name.",
                minlength: "Test-SMTP Host must have at-least 8 characters."
            },
            test_smtp_auth: {
                required: "Test-SMTP Auth should be 'ssl','tls' or ''",
                maxlength: "Test-SMTP Host must have at-most 3 characters."
            },
            test_smtp_port: {
                required: "Please enter the Test-SMTP Port",
                digit: "Please enter a positive number"
            },
            test_smtp_pass: {
                required: "Please provide a Test-SMTP password",
                minlength: "Test-SMTP Password must have at-least 5 characters."
            },
            test_s_email: {
                required: "Please enter a valid email address."
            }
        }
    });
    $("form.edit_login_details_form").validate({
        rules: {
            firstname: {required: true,minlength: 6},
            lastname: {required: true,minlength: 6},
//            email: {required: true,email: true},
            ex_date: {required: true,}
        },
        messages: {
            firstname: {required: "Please enter a First Name.",minlength: "First Name must have at-least 6 characters."},
            lastname: {required: "Please enter a Last Name.",minlength: "Last Name must have at-least 6 characters."},
//            email: {required: "Please enter a valid email address."},
            ex_date: {required: "Please provide expiery date for this account"}
        }
    });
    $("#create_group_admin_form").validate({
        rules: {
            firstname:      {required: true,minlength: 6},
            lastname:       {required: true,minlength: 6},
            email:          {required: true,email: true},
            quota_total:    {required: true,digits: true},
            quota_monthly:  {required: true,digits: true},
            quota_hour:     {required: true,digits: true},
            password:       {required: true,minlength: 5},
            confirm_password: {required: true,minlength: 5,equalTo: "#password"},
            s_email:        {required: true,email: true},
            b_email:        {required: true,email: true},
            address:        {required: true,minlength: 10},
            username:       {required: true,minlength: 5},
            city:           {required: true,minlength: 2},
            state:          {required: true,minlength: 5},
            pincode:        {required: true,digits: true},
            ex_date:        {required: true,},
            m_number:       {required: true,minlength: 10},
            smtp_user:      {required: true,minlength: 5},
            smtp_host:      {required: true,minlength: 8},
            smtp_auth:      {required: true,maxlength: 3},
            smtp_port:      {required: true,digits: true},
            smtp_pass:      {required: true,minlength: 5},
            test_smtp_user: {required: true,minlength: 5},
            test_smtp_host: {required: true,minlength: 8},
            test_smtp_auth: {required: true,maxlength: 3},
            test_smtp_port: {required: true,digits: true},
            test_smtp_pass: {required: true,minlength: 5},
            test_s_email:   {required: true,email: true}
        },
        messages: {
            firstname:      {required: "Please enter a First Name.",minlength: "First Name must have at-least 6 characters."},
            lastname:       {required: "Please enter a Last Name.",minlength: "Last Name must have at-least 6 characters."},
            email:          {required: "Please enter a valid email address."},
            quota_total:    {required: "Please enter Total Quota.",digits: "Please enter a positive number"},
            quota_monthly:  {required: "Please enter Monthly Quota.",digits: "Please enter a positive number"},
            quota_hour:     {required: "Please enter ourly Quota.",digits: "Please enter a positive number"},
            password:       {required: "Please provide a password.",minlength: "Password must have at-least 5 characters."},
            confirm_password:{required: "Please provide a confirmation",minlength: "Confirm Password must be at least 5 characters long.",equalTo: "Please enter the same password as above."},
            s_email:        {required: "Please enter a valid email address."},
            b_email:        {required: "Please enter a valid email address."},
            address:        {required: "Please enter an Address.",minlength: "Address must consist of at least 10 characters long."},
            city:           {required: "Please enter The City name",minlength: "City name must have at-least 2 characters."},
            state:          {required: "Please enter The State",minlength: "State must have at-least 5 characters."},
            pincode:        {required: "Please enter The State",digits: "Please enter a positive number"},
            ex_date:        {required: "Please provide expiery date for this account"},
            m_number:       {required: "Please enter Mobile number.",minlength: "Mobile number must have at-least 10 digits."},
            smtp_user:      {required: "Please enter SMTP User name.",minlength: "SMTP User must have at-least 5 characters."},
            smtp_host:      {required: "Please enter SMTP Host name.",minlength: "SMTP Host must have at-least 8 characters."},
            smtp_auth:      {required: "SMTP Auth should be 'ssl','tls' or ''",maxlength: "SMTP Host must have at-most 3 characters."},
            smtp_port:      {required: "Please enter the SMTP Port",digits: "Please enter a positive number"},
            smtp_pass:      {required: "Please provide a SMTP password",minlength: "SMTP Password must have at-least 5 characters."},
            test_smtp_user: {required: "Please enter Test-SMTP User name.",minlength: "Test-SMTP User must have at-least 5 characters."},
            test_smtp_host: {required: "Please enter Test-SMTP Host name.",minlength: "Test-SMTP Host must have at-least 8 characters."},
            test_smtp_auth: {required: "Test-SMTP Auth should be 'ssl','tls' or ''",maxlength: "Test-SMTP Host must have at-most 3 characters."},
            test_smtp_port: {required: "Please enter the Test-SMTP Port", digits: "Please enter a positive number"},
            test_smtp_pass: {required: "Please provide a Test-SMTP password",minlength: "Test-SMTP Password must have at-least 5 characters."},
            test_s_email:   {required: "Please enter a valid email address."}
        }
    });
    $("#change_user_password").validate({
        rules: {
            old_password: {
                required: true
            },
            new_password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            },
        },
        messages: {
            old_password: {
                required: "Please provide old password."
            },
            new_password: {
                required: "Please provide new password.",
                minlength: "Password must have at-least 5 characters."
            },
            confirm_password: {
                required: "Please provide a confirmation",
                minlength: "Confirm Password must be at least 5 characters long.",
                equalTo: "Please enter the same password as above."
            },
        }
    });
    $("#username").focus(function () {
        var firstname = $("#firstname").val();
        var lastname = $("#lastname").val();
        if (firstname && lastname && !this.value) {
            this.value = firstname + "." + lastname;
        }
    });
/*
    if (document.getElementById("pie") !== null) {
        var pieData = [{
                value: 7000,
                color: "#F38630"
            }, {
                value: 5445,
                color: "#E0E4CC"
            }];
        new Chart(document.getElementById("pie").getContext("2d")).Pie(pieData);
    }
*/


    $("#form-options>div").hide();
    $("#chose-option input").on("click", function () {
        $("#list_edit_forms, section.alert, section.panel").hide();
        name = $("input:checked").val();
        $("#option" + name).prevAll().hide();
        $("#option" + name + "~div").hide();
        $("#option" + name).slideDown(500);
    });

    $(".edit_list_form_option").hide();
    option = $("#edit-lists-options input:checked").val();
    $("#edit_using_" + option).show();
    $("#edit-lists-options").on("click", function () {
        form_type = $("#edit-lists-options input:checked").val();
        $(".edit_list_form_option").hide();
        $("#edit_using_" + form_type).show(100);
    });

});
