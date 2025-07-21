window.addEventListener('DOMContentLoaded', () => {
    let videos = document.querySelectorAll('.video-plyr');
    let embed = document.querySelectorAll('.plyr__video-embed');
    if(videos.length > 0){
        videos.forEach(video => {
            const options = {
                controls: [], // Para asegurar que no se muestren controles
                autoplay: true, // Para que el video se reproduzca automáticamente
                muted: true, // Para reproducir el video sin audio
                hideControls: true, // Oculta los controles completamente
                loop: {},
                clickToPlay: false
            };
        
            if (video.dataset.controls === 'true') {
                options.controls = ["play-large", "play", "progress", "current-time", "mute", "volume", "fullscreen"];
                options.hideControls = false; // Mostrará los controles especificados
                options.muted = false; // Habilita el audio si los controles están activos
            } else {
                options.muted = true; // Asegura que el video esté en silencio
                options.clickToPlay = false;
                options.autoplay = true;
                options.loop = {"active": true};
            }
        
            const player = new Plyr(video, options);

            if (video.dataset.controls === 'false') {
                player.play();
        
                video.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                });
            }
        });
    }
    if(embed.length > 0){
        embed.forEach((emb) => {
            const player = new Plyr(emb);
        });
    }
});