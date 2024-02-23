<?php
/**
 * Plugin Name: WPCM NoRedirect URL
 * Plugin URI: http://seusite.com/wpcm-noredirect-url
 * Description: Este plugin bloqueia redirecionamentos para URLs externas como medida de segurança, consultando uma lista de domínios permitidos em um arquivo separado.
 * Version: 1.2
 * Author: Daniel Oliveira da Paixão
 * Author URI: http://seusite.com
 */

defined('ABSPATH') or die('Acesso negado.');

function wpcm_noredirect_url_enqueue_script() {
    // Inclui o arquivo com a lista de domínios permitidos
    include_once(plugin_dir_path(__FILE__) . 'list.php');
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var allowedDomains = <?php echo json_encode($allowedDomains); ?>;

        document.addEventListener('click', function(e) {
            var target = e.target.closest('a');
            if (target) {
                var destinationURL = new URL(target.href);
                var domain = destinationURL.hostname;
                if (!allowedDomains.includes(domain)) {
                    e.preventDefault();
                    alert('Redirecionamento bloqueado por motivos de segurança.');
                }
            }
        });

        // Adiciona bloqueio para redirecionamentos automáticos via meta tag 'http-equiv="refresh"'
        document.querySelectorAll('meta[http-equiv="refresh"]').forEach(function(tag) {
            tag.content = '0; url=javascript:void(0);'; // Altera para evitar redirecionamento
        });
    });
    </script>
    <?php
}

add_action('wp_footer', 'wpcm_noredirect_url_enqueue_script');
