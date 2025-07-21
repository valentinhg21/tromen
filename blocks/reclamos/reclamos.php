<div class="block-form p-relative scroll-margin">
    <div class="hidden">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form class="form form-dark garantia">
                        <div class="field-container">
                            <label for="name">Nombre* <span class="required-error"></span></label>
                            <input type="text" id="name">
                        </div>
                        <div class="field-container">
                            <label for="surname">Apellido* <span class="required-error"></span></label>
                            <input type="text" id="surname">
                        </div>
                        <div class="field-container">
                            <label for="email">Email* <span class="required-error"></span> </label>
                            <input  type="email" id="email">
                        </div>
                        <div class="field-container">
                            <label for="telephone">Teléfono <span class="required-error"></span></label>
                            <input type="number" id="telephone">
                        </div>
                        <div class="field-container">
                            <label for="where_buy">¿Dónde compro el producto?</label>
                            <input type="text" id="where_buy">
                        </div>
                        <div class="field-container">
                            <label for="cateogry">Seleccionar Categoria <span class="required-error"></span></label>
                            <div class="field-container-input">
                                <div class="field-container-input__icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <i class="fa-solid fa-x"></i>
                                </div>
                                <input type="text" readonly id="category" data-id="">
                                    <div class="list-select">
                                        <ul id="list-categoria">    
                                         
                                        </ul>
                                    </div>
                            </div>
                        </div>
                        <div class="field-container">
                            <label for="cateogry">Seleccionar producto <span class="required-error"></span></label>
                            <div class="field-container-input">
                                <div class="field-container-input__icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <i class="fa-solid fa-x"></i>
                                </div>
                                <input type="text" readonly id="product" data-id="">
                                    <div class="list-select">
                                        <ul id="list-producto">    
                                       
                                        </ul>
                                    </div>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="field-check"> 
                                <label class="checkbox-container">
                                 
                                    No encontre el producto?
                                    <input type="checkbox" id="no-product-found">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="field-container d-none field-product-name">
                            <label for="product_name">Ingresa el nombre del producto <span class="required-error"></span></label>
                            <input type="text" id="product_name">
                        </div>
                        <div class="field-container">
                            <label for="numero_lote">Número de lote <span class="required-error"></span></label>
                            <input type="nunmber" id="numero_lote">
                        </div>
                        <div class="field-container">
                            <label for="image">Carga imagenes</label>
                            <div class="field-file">
                                <div class="field-file-button">
                                    <label for="file">
                                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.80113 11.2025C9.76985 11.1715 9.7276 11.1541 9.68358 11.1541C9.63955 11.1541 9.59731 11.1715 9.56602 11.2025L7.14834 13.6202C6.02896 14.7395 4.13975 14.8581 2.90386 13.6202C1.66589 12.3822 1.78449 10.4951 2.90386 9.37568L5.32155 6.95799C5.38605 6.89349 5.38605 6.78738 5.32155 6.72288L4.49346 5.89479C4.46218 5.86381 4.41993 5.84644 4.3759 5.84644C4.33188 5.84644 4.28963 5.86381 4.25835 5.89479L1.84066 8.31248C0.0804557 10.0727 0.0804557 12.9211 1.84066 14.6792C3.60087 16.4373 6.44925 16.4394 8.20737 14.6792L10.6251 12.2615C10.6896 12.197 10.6896 12.0909 10.6251 12.0264L9.80113 11.2025ZM15.1816 1.3403C13.4214 -0.419911 10.573 -0.419911 8.81492 1.3403L6.39515 3.75799C6.36417 3.78927 6.3468 3.83152 6.3468 3.87554C6.3468 3.91957 6.36417 3.96181 6.39515 3.9931L7.22116 4.81911C7.28566 4.88361 7.39177 4.88361 7.45627 4.81911L9.87395 2.40142C10.9933 1.28204 12.8825 1.16345 14.1184 2.40142C15.3564 3.63939 15.2378 5.52652 14.1184 6.6459L11.7007 9.06359C11.6698 9.09487 11.6524 9.13712 11.6524 9.18114C11.6524 9.22517 11.6698 9.26741 11.7007 9.2987L12.5288 10.1268C12.5933 10.1913 12.6994 10.1913 12.7639 10.1268L15.1816 7.7091C16.9398 5.94889 16.9398 3.10051 15.1816 1.3403ZM10.5522 5.10415C10.521 5.07318 10.4787 5.0558 10.4347 5.0558C10.3907 5.0558 10.3484 5.07318 10.3171 5.10415L5.60451 9.81469C5.57354 9.84598 5.55616 9.88822 5.55616 9.93225C5.55616 9.97627 5.57354 10.0185 5.60451 10.0498L6.42844 10.8737C6.49294 10.9382 6.59905 10.9382 6.66355 10.8737L11.3741 6.16319C11.4386 6.09869 11.4386 5.99258 11.3741 5.92808L10.5522 5.10415Z" fill="#737373"/>
                                        </svg>
                                    Adjuntar Archivo
                                    </label>
                                    <input type="file" id="file" class="d-none" multiple="true" accept="image/*">
                                </div>
                            </div>
                        </div>    
                        <div class="field-container">
                            <div class="field-textarea">
                                <label for="description">Detalle del reclamo <span class="required-error"></span></label>
                                <textarea id="description" cols="30" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="button__container">
                            <button type="submit" class="btn btn-red btn-submit-form " id="btn-reclamo">
                                Enviar </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="popup">
    <div class="popup-card">
        <div class="popup-card-close">
            <i class="fa-solid fa-x popup-close"></i>
        </div>
        <div class="popup-content d-flex justify-center align-items-center flex-column">
        </div>
        <div class="button__container d-flex justify-center align-items-center">
            <button type="button" class="btn btn-black-to-red popup-close">
                Cerrar
            </button>
        </div>
    </div>
</div>
