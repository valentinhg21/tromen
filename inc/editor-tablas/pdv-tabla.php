<?php 
// Agregar una nueva columna a la lista de publicaciones de pdv
add_filter('manage_pdv_posts_columns', 'my_custom_columns');
function my_custom_columns($columns) {
    // Obtener el índice de la columna del título
    $title_column_index = array_search('title', array_keys($columns));

    // Insertar la nueva columna después de la columna del título
    if ($title_column_index !== false) {
        // Usar array_slice para dividir el array y agregar la nueva columna
        $columns = array_slice($columns, 0, $title_column_index + 1, true) +
                   ['adherido_a_la_oferta' => 'Adherido a la Oferta'] +
                   array_slice($columns, $title_column_index + 1, null, true);
    }

    return $columns;
}
// Mostrar el checkbox en la nueva columna
add_action('manage_pdv_posts_custom_column', 'my_custom_column_content', 10, 2);
function my_custom_column_content($column, $post_id) {
    if ($column === 'adherido_a_la_oferta') {
        $valor = get_field('adherido_a_la_oferta', $post_id); // Obtener el valor del campo ACF
        // Mostrar el checkbox
        echo '<input type="checkbox" class="adherido-checkbox" data-post-id="' . esc_attr($post_id) . '"' . checked($valor, true, false) . '/>';
    }
}
// Enqueue AJAX script solo en la pantalla de pdv
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_script');
function enqueue_custom_admin_script($hook) {
    // Verifica si estamos en la lista de publicaciones de pdv
    if ($hook == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'pdv') {
        // Incrusta el script JavaScript y el CSS directamente
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                // Seleccionar todos los checkboxes de la columna 'Adherido a la Oferta'
                var checkboxes = document.querySelectorAll('.adherido-checkbox');
                var messageContainer = document.createElement('div'); // Contenedor para el mensaje
                messageContainer.style.position = 'fixed';
                messageContainer.style.top = '80px';
                messageContainer.style.right = '20px';
                messageContainer.style.backgroundColor = '#5cb85c';
                messageContainer.style.color = 'white';
                messageContainer.style.padding = '12px';
                messageContainer.style.borderRadius = '5px';
                messageContainer.style.zIndex = '9999';
                messageContainer.style.display = 'none'; // Oculto por defecto
                document.body.appendChild(messageContainer);
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        var postId = this.getAttribute('data-post-id');
                        var valor = this.checked ? 1 : 0; // 1 para activado, 0 para desactivado

                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'action': 'update_adherido_a_la_oferta',
                                'post_id': postId,
                                'valor': valor // Enviará 1 si está marcado, 0 si no
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                messageContainer.innerText = 'Valor actualizado correctamente!';
                                messageContainer.style.display = 'block';
                                // Ocultar el mensaje después de 3 segundos
                                setTimeout(function() {
                                    messageContainer.style.display = 'none';
                                }, 500);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    });
                });
            });
        </script>
        <?php
    }
}
// Manejar el cambio de estado del checkbox
add_action('wp_ajax_update_adherido_a_la_oferta', 'update_adherido_a_la_oferta');
function update_adherido_a_la_oferta() {
    $post_id = intval($_POST['post_id']);
    $valor = intval($_POST['valor']); // Asegúrate de convertirlo a entero

    // Guardar como true si valor es 1 (lo que significa "Sí") y como false si valor es 0 (lo que significa "No")
    update_field('adherido_a_la_oferta', $valor ? true : false, $post_id);
    
    // Devuelve una respuesta
    wp_send_json_success(array('valor' => $valor));
}