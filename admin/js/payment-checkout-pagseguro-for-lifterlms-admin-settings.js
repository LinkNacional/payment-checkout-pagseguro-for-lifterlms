document.addEventListener("DOMContentLoaded", function() {
    const descriptions = document.querySelectorAll('.description');
    if(descriptions){
        descriptions.forEach(description => {
            const brTags = description.querySelectorAll('br');

            if(brTags){
                brTags.forEach(brTag => {
                    brTag.remove();
                    description.style.paddingTop = '5px';
                });
            }            
        });
    }

    const label = document.querySelector('label[for="llms_gateway_pagseguro-v1_logging_enabled"]');

    // Adiciona o link de "ver logs" ao lado do texto do label
    if (label) {
        const labelParent = label.parentNode;
        const descriptionElement = labelParent.querySelector('.description');
        if(descriptionElement) descriptionElement.remove();
        const siteUrl = window.location.origin; 
        const logLink = document.createElement('a');
        logLink.href = `${siteUrl}/wp-admin/admin.php?page=llms-status&tab=logs`;
        logLink.textContent = lknPaymentCheckoutPagseguroForLifterlmsPhpVariables.seeLogs;
        logLink.target = '_blank';
        label.appendChild(logLink);
    }
});