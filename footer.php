<?php require_once('layout/footer.php'); ?>
<?php wp_footer(); ?>
<?php if(!is_checkout()): ?>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"></script><script type="text/javascript" src="https://app.asisteclick.com/tools/whatsapp/widget/floating-wpp.min.js"></script>
<link rel="stylesheet" href="https://app.asisteclick.com/tools/whatsapp/widget/floating-wpp.min.css"><div id="AsisteClickWhatsAppWidget" style="z-index: 9999999"></div><script>$('#AsisteClickWhatsAppWidget').floatingWhatsApp({phone: '5491125163014',headerTitle: 'Haz tu consulta',popupMessage: 'Hola! ¿Cómo puedo ayudarte?',message: "",showPopup: false,position: 'right',size: '60px'});</script>
<?php endif; ?>

</html>