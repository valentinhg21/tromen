<?php
$archivo = get_field( 'archivo' );
$link = get_field( 'link' );
 ?>

<div class="csv">
    <div class="container">
        <div class="content">
            <?php 
                // Obtener el archivo CSV del campo personalizado
                $archivo = get_field('archivo'); // 'archivo' es el nombre del campo en ACF
                
                if ($archivo) :
                    $archivo_url = $archivo['url']; 
                    $csv_file_path = get_attached_file($archivo['ID']); // Obtener la ruta del archivo CSV
            ?>
                <div class="group"  <?php animation('fade-in-bottom', 200 );?>>
                    <div class="search-container">
                        <div class="search">
                            <input type="text" placeholder="¿Qué estas buscando?" name="search" id="searchInput">
                            <i class="fa-solid fa-magnifying-glass search-icon" id="search-icon-csv"></i> 
                            <i class="fa-solid fa-x search-remove" id="close-icon-csv"></i>
                        </div>
                    </div>
                    <?php insert_button($link, 0, 'btn-red-transparent') ?>
                </div>
                <?php if (($file = fopen($csv_file_path, "r")) !== FALSE) : ?>
                    <table id="csvTable" <?php animation('fade-in-bottom', 200 );?>>
                        <thead>
                            <?php 
                                // Leer la primera fila para encabezados
                                $encabezados = fgetcsv($file, 1000, ",");
                                if ($encabezados) : 
                            ?>
                                <tr>
                                    <?php foreach ($encabezados as $encabezado) : ?>
                                        <th><?php echo esc_html($encabezado); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endif; ?>
                        </thead>
                        <tbody>
                            <?php while (($datos = fgetcsv($file, 1000, ",")) !== FALSE) : ?>
                                <tr>
                                    <?php foreach ($datos as $celda) : ?> 
                                        <td><?php echo esc_html($celda); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php fclose($file); ?>
                <?php else : ?>
                    <p>No se pudo abrir el documento.</p>
                <?php endif; ?>
            <?php else : ?>
                <p>No hay documento cargado.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

