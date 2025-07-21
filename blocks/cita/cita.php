

<?php 
    $texto = get_field('cita');
?>
<div class="block-cita blog">
    <div class="container-sm">
        <div class="card">
            <svg xmlns="http://www.w3.org/2000/svg" width="57" height="57" viewBox="0 0 57 57" fill="none">
                <g clip-path="url(#clip0_2481_2300)">
                <path d="M57 24.9122H37.1599L51.1894 10.8843L46.1157 5.81063L32.0863 19.8385V0H24.9122V19.8385L10.8843 5.81063L5.81063 10.8843L19.8385 24.9122H0V32.0863H19.8385L5.81063 46.1157L10.8843 51.1894L24.9122 37.1599V57H32.0863V37.1599L46.1157 51.1894L51.1894 46.1157L37.1599 32.0863H57V24.9122Z" fill="#E2252D"/>
                </g>
                <defs>
                <clipPath id="clip0_2481_2300">
                <rect width="57" height="57" fill="white"/>
                </clipPath>
                </defs>
            </svg>
            <?php echo $texto; ?>
        </div>
   
    </div>
</div>
