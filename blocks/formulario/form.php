<div class="block-form p-relative scroll-margin" >
    <div class="hidden">
        <div class="container">
            <?php
            $flexibleContentPath = dirname(__FILE__) . '/flexible-content/';
            if ( have_rows( 'formulario' ) ) :
                while ( have_rows( 'formulario' ) ) :
                the_row();
                $layout = get_row_layout();
                $file = ( $flexibleContentPath . str_replace( '_', '-', $layout) . '.php' );
                if ( file_exists( $file ) ) {
                    include( $file );
                }
                endwhile;
            endif; ?>
        </div>
    </div>
</div>