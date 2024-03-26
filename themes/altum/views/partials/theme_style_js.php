<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script>
    document.querySelector('#switch_theme_style').addEventListener('click', event => {
        let theme_style = document.querySelector('body[data-theme-style]').getAttribute('data-theme-style');
        let new_theme_style = theme_style == 'light' ? 'dark' : 'light';

        /* Set a cookie with the new theme style */
        set_cookie('theme_style', new_theme_style, 30, <?= json_encode(COOKIE_PATH) ?>);

        /* Change the css and button on the page */
        let css = document.querySelector(`#css_theme_style`);

        document.querySelector(`body[data-theme-style]`).setAttribute('data-theme-style', new_theme_style);

        switch(new_theme_style) {
            case 'dark':
                css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . (\Altum\Router::$path == 'admin' ? 'admin-' : (settings()->theme->dark_is_enabled ? 'custom-bootstrap/' : null )) . \Altum\ThemeStyle::$themes['dark'][l('direction')] . '?v=' . PRODUCT_CODE) ?>);
                document.body.classList.add('cc--darkmode');
                break;

            case 'light':
                css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . (\Altum\Router::$path == 'admin' ? 'admin-' : (settings()->theme->light_is_enabled ? 'custom-bootstrap/' : null )) . \Altum\ThemeStyle::$themes['light'][l('direction')] . '?v=' . PRODUCT_CODE) ?>);
                document.body.classList.remove('cc--darkmode');
                break;
        }

        /* Refresh the logo/title */
        document.querySelectorAll('[data-logo]').forEach(element => {
            let new_brand_value = element.getAttribute(`data-${new_theme_style}-value`);
            let new_brand_class = element.getAttribute(`data-${new_theme_style}-class`);
            let new_brand_tag = element.getAttribute(`data-${new_theme_style}-tag`)
            let new_brand_html = new_brand_tag == 'img' ? `<img src="${new_brand_value}" class="${new_brand_class}" alt="<?= l('global.accessibility.logo_alt') ?>" />` : `<${new_brand_tag} class="${new_brand_class}">${new_brand_value}</${new_brand_tag}>`;
            element.innerHTML = new_brand_html;
        });


        document.querySelector(`#switch_theme_style`).setAttribute('data-original-title', document.querySelector(`#switch_theme_style`).getAttribute(`data-title-theme-style-${theme_style}`));
        document.querySelector(`#switch_theme_style [data-theme-style="${new_theme_style}"]`).classList.remove('d-none');
        document.querySelector(`#switch_theme_style [data-theme-style="${theme_style}"]`).classList.add('d-none');
        $(`#switch_theme_style`).tooltip('hide').tooltip('show');

        event.preventDefault();
    });
    
    
    function initializeQuill(elementId) {
        return new Quill(elementId, {
            theme: 'snow'  // Specify the theme ('snow' or 'bubble')
        });
    }

    // Initialize Quill for each element
    if (document.getElementById('welcome_msg')) { var quillWelcomeMsg = initializeQuill('#welcome_msg'); }
    if (document.getElementById('directions')) { var quillDirections = initializeQuill('#directions'); }
    if (document.getElementById('bring_along')) { var quillBringalong = initializeQuill('#bring_along'); }
    if (document.getElementById('rules')) { var quillRules = initializeQuill('#rules'); }
    if (document.getElementById('add_notes')) { var quillRules = initializeQuill('#add_notes'); }
    if (document.getElementById('emergency_add_notes')) { var quillRules = initializeQuill('#emergency_add_notes'); } 
    if (document.getElementById('check_in_out_add_notes')) { var quillRules = initializeQuill('#check_in_out_add_notes'); }    
   

    var form = document.querySelector('#menuupdate_form');

    // Listen for form submission
    form.addEventListener('submit', function(event) {
        if (document.getElementById('welcome_msg')) {
            var welcomeMsgContent = document.querySelector('#welcome_msg .ql-editor').innerHTML;
            document.getElementById('welcome_msg_input').value = welcomeMsgContent;
        }
        if (document.getElementById('directions')) {
            var directionsContent = document.querySelector('#directions .ql-editor').innerHTML;
            document.getElementById('directions_input').value = directionsContent;
        }
        if (document.getElementById('bring_along')) {
            var bringalongContent = document.querySelector('#bring_along .ql-editor').innerHTML;
            document.getElementById('bring_along_input').value = bringalongContent;
        }
        if (document.getElementById('rules')) {
            var rulesContent = document.querySelector('#rules .ql-editor').innerHTML;
            document.getElementById('rules_input').value = rulesContent;
        }
        if (document.getElementById('add_notes')) {
            var addNotesContent = document.querySelector('#add_notes .ql-editor').innerHTML;
            document.getElementById('add_notes_input').value = addNotesContent;
        }
        if (document.getElementById('emergency_add_notes')) {
            var EmerAddnotesContent = document.querySelector('#emergency_add_notes .ql-editor').innerHTML;
            document.getElementById('emergency_add_notes_input').value = EmerAddnotesContent;
        }
        if (document.getElementById('check_in_out_add_notes')) {
            var chkAddnotesContent = document.querySelector('#check_in_out_add_notes .ql-editor').innerHTML;
            document.getElementById('check_in_out_add_notes_input').value = chkAddnotesContent;
        }
        
    });

    // to set the initial content from an input field
    if (document.getElementById('welcome_msg')) { quillWelcomeMsg.root.innerHTML = $("#welcomemsg").val(); }    
    if (document.getElementById('directions')) { quillDirections.root.innerHTML = $("#directions_val").val(); }
    if (document.getElementById('bring_along')) {  quillBringalong.root.innerHTML = $("#bring_along_val").val(); }
    if (document.getElementById('rules')) {    quillRules.root.innerHTML = $("#rules_val").val(); } 
    if (document.getElementById('add_notes')) {    quillRules.root.innerHTML = $("#add_notes_val").val(); } 
    if (document.getElementById('emergency_add_notes')) {    quillRules.root.innerHTML = $("#emergency_add_notes_val").val(); }  
    if (document.getElementById('check_in_out_add_notes')) {    quillRules.root.innerHTML = $("#check_in_out_add_notes_val").val(); } 
    
    
    //add health services : start
    $("#add_health_service").click(function(){
        var healthServicesDiv = document.getElementById("health_services_div");
        var separator = document.createElement("hr");
        separator.classList.add("separator");

        //healthServicesDiv.appendChild(separator);
        var clone = healthServicesDiv.cloneNode(true);

        // Remove content from the cloned elements
        var clonedInputs = clone.querySelectorAll("input[type='text'],input[type='hidden']");

        
        clonedInputs.forEach(function(input) {
            input.value = "";
        });
        // Find all divs with id "health_services_div"
        var allHealthServicesDivs = document.querySelectorAll("div[id='health_services_div']");

        // Get the last div with id "health_services_div"
        var lastHealthServicesDiv = allHealthServicesDivs[allHealthServicesDivs.length - 1];

        if (!lastHealthServicesDiv.querySelector("#remove_health_service")) {
            var removeButton = document.createElement("button");
            removeButton.setAttribute("type", "button");
            removeButton.setAttribute("class", "btn btn-danger");
            removeButton.setAttribute("id", "remove_health_service");
            removeButton.setAttribute("data-id", "");
            removeButton.textContent = "Remove";
            lastHealthServicesDiv.appendChild(removeButton);
        }


        // Append separator and clone after the last health services div
        lastHealthServicesDiv.appendChild(separator);
        lastHealthServicesDiv.parentNode.insertBefore(clone, lastHealthServicesDiv.nextSibling);

        // healthServicesDiv.appendChild(clone);

        

    })
    //add health services : end
    //remove health service : start
    $(document).on("click", "button#remove_health_service", function(){    
        var parentDiv = $(this).closest("div[id='health_services_div']");
    
        // Remove the parent div if found
        if (parentDiv.length > 0) {
            parentDiv.remove();
        }
        var allHealthServicesDivs = $("div[id='health_services_div']");
        if (allHealthServicesDivs.length === 1) {
            console.log("hello");   
            // If only one div exists, hide or remove the remove button
            $("button#remove_health_service").hide(); // or $(this).remove();
        }

    });
    //remove  health service : end


    //add_emergency : start
    $("#add_emergency").click(function(){
        var emergrncy = document.getElementById("emergency_div");
        var separator = document.createElement("hr");
        separator.classList.add("separator");

        //emergrncy.appendChild(separator);
        var clone = emergrncy.cloneNode(true);

        // Remove content from the cloned elements
        var clonedInputs = clone.querySelectorAll("input[type='text'],input[type='hidden']");
        
        clonedInputs.forEach(function(input) {
            input.value = "";
        });
        // Find all divs with id "emergency"
        var allEmergency = document.querySelectorAll("div[id='emergency_div']");

        // Get the last div with id "emergency"
        var lastemergrncy = allEmergency[allEmergency.length - 1];

        console.log('clonedInputs : ',lastemergrncy);

        if (!lastemergrncy.querySelector("#remove_emergency")) {
            var removeButton = document.createElement("button");
            removeButton.setAttribute("type", "button");
            removeButton.setAttribute("class", "btn btn-danger");
            removeButton.setAttribute("id", "remove_health_service");
            removeButton.setAttribute("data-id", "");
            removeButton.textContent = "Remove";
            lastemergrncy.appendChild(removeButton);
        }


        // Append separator and clone after the last health services div
        lastemergrncy.appendChild(separator);
        lastemergrncy.parentNode.insertBefore(clone, lastemergrncy.nextSibling);

        // healthServicesDiv.appendChild(clone);

        

    })
    //add health services : end
    //remove health service : start
    $(document).on("click", "button#remove_emergency", function(){    
        var parentDiv = $(this).closest("div[id='emergency']");
    
        // Remove the parent div if found
        if (parentDiv.length > 0) {
            parentDiv.remove();
        }
        var allEmergency = $("div[id='emergency']");
        if (allEmergency.length === 1) {
            console.log("hello");   
            // If only one div exists, hide or remove the remove button
            $("button#remove_emergency").hide(); // or $(this).remove();
        }

    });
    //remove  emergency : end
    
   
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
