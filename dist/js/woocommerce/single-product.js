import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe/dist/photoswipe-lightbox.esm.js';
window.addEventListener('DOMContentLoaded', e => {
    splideGallery();
    splideFeatureGallery();
    videoPlayer();
    videoPlayerSlider();
    relatedProduct();
})



const splideGallery = () => {
    let productMain = document.querySelector('.slide-product__main')
        if(productMain){
            let main =  new Splide( productMain, {
            
                rewind    : true,
                pagination: true,
                arrows    : true,
                breakpoints: {
                    1024: {
                        arrows    : true,
                        pagination: true,
                    }
                }
        
            })
            main.mount();
            const lightbox = new PhotoSwipeLightbox({
                mainClass: 'pswp--custom-bg',
                bgOpacity: 1,
                gallery: '#gallery--custom-bg',
                gallery: '#tromen-gallery',
                children: 'a',
                pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.js'),
            });
            lightbox.init();
        }
}

function isIOSfn() {
    const platform = window.navigator?.userAgentData?.platform || window.navigator.platform;
    const iosPlatforms = ['iPhone', 'iPad', 'iPod'];
    
    // Devuelve true si el sistema operativo es iOS, false en caso contrario
    return iosPlatforms.indexOf(platform) !== -1;
  
}
const splideFeatureGallery = () => {
    const thumbnailsCont = document.querySelector('.feature-image');

    if(thumbnailsCont){
        if(isIOSfn()){
         
            let feature =  new Splide(thumbnailsCont, {
                autoWidth: true,
                autoHeight: true,
                type: "loop",
                focus: "center",
                lazyLoad: "nearby",
                trimSpace: "move",
                pagination: false,
                arrows    : true,
                preloadPages: 4,
                focus  : 0,
                omitEnd: false,
                gap: 16,
                lazyLoad: "nearby",
                breakpoints: {
                    600: {
                        autoWidth: false,
                        autoHeight: false,
                        perPage: 1.5
                    }
                }
            })
            feature.mount();
        }else{
            let feature =  new Splide(thumbnailsCont, {
                autoWidth: true,
                autoHeight: true,
                type: "loop",
                focus: "center",
                lazyLoad: "nearby",
                trimSpace: "move",
                pagination: false,
                arrows    : true,
                preloadPages: 4,
                focus  : 0,
                omitEnd: false,
                gap: 16,
                lazyLoad: "nearby",
                breakpoints: {
                    600: {
               
                    }
                }
            })
            feature.mount();
        }

  
        const lightboxFeature = new PhotoSwipeLightbox({
            mainClass: 'pswp--custom-bg',
            bgOpacity: 1,
            gallery: '#gallery--custom-bg',
            gallery: '#tromen-feature',
            children: 'a',
            pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.js'),
        });
        lightboxFeature.init();
    }

}

const videoPlayerSlider = () => {
    const video = document.querySelector('.slide-product__videos');
    let splideVideos = document.querySelectorAll('.videos');
    let videosSplide;
    if(video){
        
        if(splideVideos.length <= 1){
             videosSplide =  new Splide(video, {
                pagination: false,
                arrows    : false,
                perPage: 1,
                focus  : 0,
                omitEnd: true,
                gap: 16,
                breakpoints: {
                    1024: {
                        arrows    : false,
                        pagination: false,
                    }
                }
     
            })
        }else{
             videosSplide =  new Splide(video, {
                pagination: false,
                arrows    : true,
                perPage: 1,
                focus  : 0,
                omitEnd: true,
                gap: 16,
                breakpoints: {
                    1024: {
                        arrows    : false,
                        pagination: true,
                    }
                }
     
            })
        }

        videosSplide.mount();
    }

}

const videoPlayer = () => {
    let videos = document.querySelectorAll('video');
    const embed = document.querySelectorAll('.plyr__video-embed');
    const video = document.querySelector('.slide-product__videos');

    if(videos.length > 0){
        videos.forEach(video => {
            let player = new Plyr(video,{
                "controls": ["play-large", "play", "progress", "current-time", "mute", "volume", "fullscreen"]
            });
        });
    }

    if(embed.length > 0){
        embed.forEach((emb) => {
            const player = new Plyr(emb);
        });
    }

    if(videos.length <= 0 && embed.length <= 0){
        // video.remove();
    }

}

const relatedProduct = () => {
    const products = document.querySelector('.products-loop');
    if(products){
        let main =  new Splide( products, {
            perPage: 3,
            arrows: true,
            pagination: false,
  
            breakpoints: {
                878: {
                    perPage: 2,
                    pagination: true,
                    arrows: false,
                },
                720: {
                    perPage: 1,
                }
          }
    
        }).mount();
    }

}