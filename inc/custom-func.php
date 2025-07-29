<?php 

function insert_download_button($acf, $title = '') {
    if ($acf) {
        $svg = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 10L7.64645 10.3536L8 10.7071L8.35355 10.3536L8 10ZM8.5 1C8.5 0.723858 8.27614 0.5 8 0.5C7.72386 0.5 7.5 0.723858 7.5 1L8.5 1ZM2.64645 5.35355L7.64645 10.3536L8.35355 9.64645L3.35355 4.64645L2.64645 5.35355ZM8.35355 10.3536L13.3536 5.35355L12.6464 4.64645L7.64645 9.64645L8.35355 10.3536ZM8.5 10L8.5 1L7.5 1L7.5 10L8.5 10Z" fill="currentColor"/>
        <path d="M1 12L1 13C1 14.1046 1.89543 15 3 15L13 15C14.1046 15 15 14.1046 15 13V12" stroke="currentColor"/>
        </svg>';
        $url = esc_url($acf['url']);
        echo '<a class="btn btn-icon btn-red-transparent-icon" href="' . $url . '" download>' . $svg .'<span>' . $title . '</span></a>';
    }
    return '';
}

function insert_textarea($acf, $tag = 'p'){
    $cont = 1;
    $text_area_arr = explode("\n", $acf);
    $text_area_arr = array_map('trim', $text_area_arr);
    foreach ($text_area_arr as &$valor) {
        if($valor != ''){
            echo('<' . $tag . '>' . $valor . '</' . $tag . '>');
        }
    }
}

function insert_custom_image($url, $w = '100%', $h = '100%', $alt = ''){
    if($url){
        $imageHTML = "<img loading='lazy' src='{$url}' width='{$w}' height='{$h} alt='{$alt}' />";
        echo $imageHTML;
    }
}

function insert_custom_image_json($url, $w = '100%', $h = '100%', $alt = ''){
    if($url){
        return $imageHTML = "<img loading='lazy' src='{$url}' width='{$w}' height='{$h} alt='{$alt}' />";
     
    }
}

function insert_default_image(){
    $root = IMAGE_DEFAULT;

    $imageHTML = "<img loading='lazy' src='{$root}' width='750' height='500' alt='En la imagen se muestra el logo de tromen' />";
    return $imageHTML;
}

function insert_button($acf, $mt, $classBtn, $icon = ''){
    if($mt === ''){
        $mt = 3;
    }
    if($classBtn === ''){
        $classBtn = '';
    }

    if ( $acf ):
        $link_url = esc_url($acf['url']);
        $link_title = esc_html($acf['title']);
        $link_target = esc_attr($acf['target'] ? $acf['target'] : '_self');
        $buttonHTML = "<div class='button__container mt-sm-{$mt} mt-2'>";
        $buttonHTML .= "<a class='btn {$classBtn}' href='{$link_url}' target='{$link_target}'>{$icon}{$link_title}</a>";
        $buttonHTML .= "</div>";
        echo($buttonHTML);
    endif;

}



function insert_image($acf, $size = false){
    if(!$size || $size === ''){
        $size = 1024;
    }
    if ( $acf ):
    $url = esc_url($acf['url']);
    $alt = esc_attr($acf['alt']);
    $size_type = 'large';
    $width = $acf['sizes'][ $size_type . '-width' ];
    $height = $acf['sizes'][ $size_type . '-height' ];
    $imageHTML = "<img loading='lazy' width='{$width}'" . img_responsive($acf['id'],"thumb-{$size}","{$size}px") . " 'height='{$height}' alt='{$alt}' />";
    echo $imageHTML;
    endif;
}


function insert_slug($id = ''){
    if(empty($id)){
        return get_post_field( 'post_name', get_post());
    }else{
        return get_post_field( 'post_name', get_post($id));
    }

}




function insert_acf($acf, $tag, $class = ''){
    if($class === ''){
        $class = "";
    }else{
        $class = "class='{$class}'";
    }
    if ( $acf ):
        if($tag === ''):
            $HTML = $acf;
            echo $HTML;
        else:
            $HTML = "<{$tag} $class>";
            $HTML .= $acf;
            $HTML .= "</$tag>";
            echo $HTML;
        endif;
    endif;
}



function sanitizeString($cadena) {
    $search  = ['À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','Þ','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ý','ÿ','¿'];
    $replace = ['A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','P','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','d','n','o','o','o','o','o','o','u','u','u','y','y','?'];

    $cadena = str_replace($search, $replace, $cadena);
    $cadena = strtolower($cadena);
    $cadena = preg_replace('/[^a-z0-9\- ]/', '', $cadena); // elimina caracteres no permitidos
    $cadena = str_replace(' ', '-', $cadena);
    $cadena = preg_replace('/-+/', '-', $cadena); // elimina guiones dobles
    $cadena = trim($cadena, '-'); // quita guiones al inicio y final

    return $cadena;
}




function animation($animation = "fade-in-bottom", $delay = 400){
    $attr = "data-transition='$animation' data-delay='$delay' style='opacity: 0'";

    echo $attr;
}




function limit_description_length($description, $length = 80, $link_text = 'Ver más') {
    if (strlen($description) > $length) {
        $description = substr($description, 0, $length);
        $description .= "...<span>{$link_text}</span>";
    }
    return $description;
}



function idYoutube($url) {
    $videoId = null;
    // Verifica si es un enlace de YouTube Short
    if (strpos($url, 'youtube.com/shorts/') !== false) {
        // Extrae el ID del video de la URL del Short
        $videoId = str_replace('https://www.youtube.com/shorts/', '', $url);
    } elseif (strpos($url, 'youtu.be/') !== false) {
        // Extrae el ID del video de la URL acortada
        $videoId = str_replace('https://youtu.be/', '', $url);
    } elseif (preg_match('/[?&]v=([^&]+)/', $url, $matches)) {
        // Extrae el ID del video de la URL estándar de YouTube
        $videoId = $matches[1];
    }
    // Retorna el enlace del iframe o null si no se encuentra el ID
    return $videoId ? "https://www.youtube.com/embed/" . $videoId : null;
}

function minify_html_inline($html) {
    return preg_replace('/\s+/', ' ', trim($html));
}