window.addEventListener("DOMContentLoaded", () => {
  const selects = document.querySelectorAll(".field-container-input");
  const submit = document.getElementById("btn-reclamo");
  const listCategory = document.getElementById("list-categoria");
  const listProducto = document.getElementById("list-producto");

  const fileContainers = document.querySelectorAll(".field-file");
  const checkbox = document.getElementById("no-product-found");
  const inputProductName = document.querySelector(".field-product-name");

  const SVGFILE = ` <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.80113 11.2025C9.76985 11.1715 9.7276 11.1541 9.68358 11.1541C9.63955 11.1541 9.59731 11.1715 9.56602 11.2025L7.14834 13.6202C6.02896 14.7395 4.13975 14.8581 2.90386 13.6202C1.66589 12.3822 1.78449 10.4951 2.90386 9.37568L5.32155 6.95799C5.38605 6.89349 5.38605 6.78738 5.32155 6.72288L4.49346 5.89479C4.46218 5.86381 4.41993 5.84644 4.3759 5.84644C4.33188 5.84644 4.28963 5.86381 4.25835 5.89479L1.84066 8.31248C0.0804557 10.0727 0.0804557 12.9211 1.84066 14.6792C3.60087 16.4373 6.44925 16.4394 8.20737 14.6792L10.6251 12.2615C10.6896 12.197 10.6896 12.0909 10.6251 12.0264L9.80113 11.2025ZM15.1816 1.3403C13.4214 -0.419911 10.573 -0.419911 8.81492 1.3403L6.39515 3.75799C6.36417 3.78927 6.3468 3.83152 6.3468 3.87554C6.3468 3.91957 6.36417 3.96181 6.39515 3.9931L7.22116 4.81911C7.28566 4.88361 7.39177 4.88361 7.45627 4.81911L9.87395 2.40142C10.9933 1.28204 12.8825 1.16345 14.1184 2.40142C15.3564 3.63939 15.2378 5.52652 14.1184 6.6459L11.7007 9.06359C11.6698 9.09487 11.6524 9.13712 11.6524 9.18114C11.6524 9.22517 11.6698 9.26741 11.7007 9.2987L12.5288 10.1268C12.5933 10.1913 12.6994 10.1913 12.7639 10.1268L15.1816 7.7091C16.9398 5.94889 16.9398 3.10051 15.1816 1.3403ZM10.5522 5.10415C10.521 5.07318 10.4787 5.0558 10.4347 5.0558C10.3907 5.0558 10.3484 5.07318 10.3171 5.10415L5.60451 9.81469C5.57354 9.84598 5.55616 9.88822 5.55616 9.93225C5.55616 9.97627 5.57354 10.0185 5.60451 10.0498L6.42844 10.8737C6.49294 10.9382 6.59905 10.9382 6.66355 10.8737L11.3741 6.16319C11.4386 6.09869 11.4386 5.99258 11.3741 5.92808L10.5522 5.10415Z" fill="#737373"/>
                </svg>`;
  if (fileContainers.length > 0) {
    fileContainers.forEach((fileContainer) => {
      if (fileContainer) {
        const label = fileContainer.querySelector("label");
        const input = fileContainer.querySelector("input");

        input.addEventListener("change", (e) => {
          const files = e.target.files;

          if (files && files.length > 0) {
            if (files.length > 2) {
              alert("Solo podés subir hasta 2 archivos.");
              input.value = "";
              return;
            }

            let labelHTML = "";

            Array.from(files).forEach((file) => {
              labelHTML += `
              ${SVGFILE}
              <div class="file-item">
                <span>${file.name}</span>
              </div>
            `;
            });

            label.innerHTML = labelHTML;
          }
        });
      }
    });
  }
  //  FETCH AUTH API
  // Funcion popup
  const closePopup = () => {
    let popupBtns = document.querySelectorAll(".popup-close");
    if (popupBtns.length > 0) {
      popupBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          let modal = btn.parentElement.parentElement.parentElement;
          modal.classList.remove("show");
        });
      });
    }
  };

  closePopup();
  // LISTAR CATEGORIAS
  //  LISTA PRODUCTOS
  // CONFIGURAR PRODUCTO MANUAL CHECKBOX
  // ENVIAR FORMULARIO
  let productos = [];
  let category = [];

  const authAPI = async () => {
    const url = "https://api.tromentools.com/users/token/";
    const credentials = {
      email: "tromenweb@tromen.com",
      password: "7el#1|P3G5lV",
    };

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(credentials),
      });

      if (!response.ok) {
        throw new Error(`Error ${response.status}: ${response.statusText}`);
      }

      const data = await response.json();

      return data.access;
    } catch (error) {
      console.error("Auth error:", error);
      return null;
    }
  };

  const fetchData = async () => {
    const token = await authAPI();
    if (!token) return;
    let categoryHTML = "";
    let productHTML = "";
    try {
      const [resProd, resCat] = await Promise.all([
        fetch("https://api.tromentools.com/productos/web/", {
          headers: { Authorization: `Bearer ${token}` },
        }),
        fetch("https://api.tromentools.com/tickets/list-category", {
          headers: { Authorization: `Bearer ${token}` },
        }),
      ]);

      if (resProd.ok) productos = await resProd.json();
      if (resCat.ok) category = await resCat.json();
      category.forEach((cat) => {
        categoryHTML += `
               <li><p data-id="${cat.id}">${cat.name}</p></li>    
            `;
      });
      productos.forEach((prod) => {
        productHTML += `
               <li><p data-id="${prod.id}">${prod.nombre}</p></li>    
            `;
      });

      listCategory.innerHTML = categoryHTML;
      listProducto.innerHTML = productHTML;
      if (selects.length > 0) {
        selects.forEach((select) => {
          const inputSelect = select.querySelector("input");
          if (select) {
            const listContainer = select.querySelector(".list-select");
            const opciones = listContainer.querySelectorAll("p");
            if (opciones.length > 8) {
              listContainer.classList.add("long");
            }
            inputSelect.addEventListener("click", () => {
              listContainer.classList.toggle("show");
              opciones.forEach((op) => {
                op.addEventListener("click", (e) => {
                  inputSelect.value = op.textContent;
                  inputSelect.dataset.id = op.dataset.id;
                  listContainer.classList.remove("show");
                  op.classList.remove("select");
                });
              });
            });

            window.addEventListener("click", (e) => {
              let target = e.target;
              if (!select.contains(target)) {
                listContainer.classList.remove("show");
              }
            });
          }
        });
      }
    } catch (err) {
      console.error("Fetch error:", err);
    }
  };

  fetchData();

  checkbox.addEventListener("change", (e) => {
    if (checkbox.checked) {
      inputProductName.classList.remove("d-none");
    } else {
      inputProductName.classList.add("d-none");
    }
  });

  const showErrorInput = (input, text, status) => {
    let label = input.previousElementSibling;
    let errorMsg = label.querySelector(".required-error");
    if (status) {
      if (errorMsg) {
        input.classList.add("error");
        label.classList.add("error");
        errorMsg.textContent = text;
      }
    } else {
      errorMsg.textContent = text;
      input.classList.remove("error");
      label.classList.remove("error");
    }
  };

  const showErrorSelect = (input, text, status) => {
    let label = input.parentElement.previousElementSibling;
    let errorMsg = label.querySelector(".required-error");
    if (status) {
      if (errorMsg) {
        input.classList.add("error");
        label.classList.add("error");
        errorMsg.textContent = text;
      }
    } else {
      errorMsg.textContent = text;
      input.classList.remove("error");
      label.classList.remove("error");
    }
  };

  function resetFile() {
      if (fileContainers.length > 0) {
        fileContainers.forEach((fileContainer) => {
          if (fileContainer) {
            const label = fileContainer.querySelector("label");
            const input = fileContainer.querySelector("input");
            let filesLength = input.files.length;

            if (filesLength <= 0) {
              let labelHTML = `
                      <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M9.80113 11.2025C9.76985 11.1715 9.7276 11.1541 9.68358 11.1541C9.63955 11.1541 9.59731 11.1715 9.56602 11.2025L7.14834 13.6202C6.02896 14.7395 4.13975 14.8581 2.90386 13.6202C1.66589 12.3822 1.78449 10.4951 2.90386 9.37568L5.32155 6.95799C5.38605 6.89349 5.38605 6.78738 5.32155 6.72288L4.49346 5.89479C4.46218 5.86381 4.41993 5.84644 4.3759 5.84644C4.33188 5.84644 4.28963 5.86381 4.25835 5.89479L1.84066 8.31248C0.0804557 10.0727 0.0804557 12.9211 1.84066 14.6792C3.60087 16.4373 6.44925 16.4394 8.20737 14.6792L10.6251 12.2615C10.6896 12.197 10.6896 12.0909 10.6251 12.0264L9.80113 11.2025ZM15.1816 1.3403C13.4214 -0.419911 10.573 -0.419911 8.81492 1.3403L6.39515 3.75799C6.36417 3.78927 6.3468 3.83152 6.3468 3.87554C6.3468 3.91957 6.36417 3.96181 6.39515 3.9931L7.22116 4.81911C7.28566 4.88361 7.39177 4.88361 7.45627 4.81911L9.87395 2.40142C10.9933 1.28204 12.8825 1.16345 14.1184 2.40142C15.3564 3.63939 15.2378 5.52652 14.1184 6.6459L11.7007 9.06359C11.6698 9.09487 11.6524 9.13712 11.6524 9.18114C11.6524 9.22517 11.6698 9.26741 11.7007 9.2987L12.5288 10.1268C12.5933 10.1913 12.6994 10.1913 12.7639 10.1268L15.1816 7.7091C16.9398 5.94889 16.9398 3.10051 15.1816 1.3403ZM10.5522 5.10415C10.521 5.07318 10.4787 5.0558 10.4347 5.0558C10.3907 5.0558 10.3484 5.07318 10.3171 5.10415L5.60451 9.81469C5.57354 9.84598 5.55616 9.88822 5.55616 9.93225C5.55616 9.97627 5.57354 10.0185 5.60451 10.0498L6.42844 10.8737C6.49294 10.9382 6.59905 10.9382 6.66355 10.8737L11.3741 6.16319C11.4386 6.09869 11.4386 5.99258 11.3741 5.92808L10.5522 5.10415Z" fill="#737373"/>
                      </svg>
                      Adjuntar Archivo
                      `;
              label.innerHTML = labelHTML;
            }
          }
        });
      }
    }

  const sendFormularioReclamos = async () => {
    let hasError = false;

    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const email = document.getElementById("email");
    const telephone = document.getElementById("telephone");
    const where_buy = document.getElementById("where_buy");
    const category = document.getElementById("category");
    const product = document.getElementById("product");
    const checkProduct = document.getElementById("no-product-found");
    const product_name = document.getElementById("product_name");
    const numeroLote = document.getElementById("numero_lote");
    const image = document.getElementById("file");
    const description = document.getElementById("description");

    let data = {
      category: "",
      producto: "",
      producto_nombre: "",
      description: "",
      image: [],
      where_buy: "",
      numero_lote: "",
      nombre: "",
      apellido: "",
      email: "",
      telefono: "",
    };

    if (validator.isEmpty(name.value)) {
      showErrorInput(name, "Este campo es obligatorio", true);
      hasError = true;
    } else {
      showErrorInput(name, "", false);
      data.nombre = name.value;
    }

    if (validator.isEmpty(surname.value)) {
      showErrorInput(surname, "Este campo es obligatorio", true);
      hasError = true;
    } else {
      showErrorInput(surname, "", false);
      data.apellido = surname.value;
    }

    if (validator.isEmpty(email.value)) {
      showErrorInput(email, "Este campo es obligatorio", true);
      hasError = true;
    } else if (!validator.isEmail(email.value)) {
      showErrorInput(email, "Ingresá un email válido", true);
      hasError = true;
    } else {
      showErrorInput(email, "", false);
      data.email = email.value;
    }

    if (validator.isEmpty(telephone.value)) {
      showErrorInput(telephone, "Este campo es obligatorio", true);
      hasError = true;
    } else {
      showErrorInput(telephone, "", false);
      data.telefono = telephone.value;
    }

    if (validator.isEmpty(category.value)) {
      showErrorSelect(category, "Seleccione una opción", true);
      hasError = true;
    } else {
      showErrorSelect(category, "", false);
      data.category = category.dataset.id;
    }

    if (checkProduct.checked) {
      if (validator.isEmpty(product_name.value)) {
        showErrorInput(product_name, "Este campo es obligatorio", true);
        hasError = true;
      } else {
        showErrorInput(product_name, "", false);
      }
      showErrorSelect(product, "", false);
      data.producto_nombre = product_name.value;
      data.producto = "";
    } else {
      showErrorInput(product_name, "", false);
      if (validator.isEmpty(product.value)) {
        showErrorSelect(product, "Seleccione una opción", true);
        hasError = true;
      } else {
        showErrorSelect(product, "", false);
        data.producto = product.dataset.id;
        data.producto_nombre = "";
      }
    }

    if (validator.isEmpty(description.value)) {
      showErrorInput(description, "Este campo es obligatorio", true);
      hasError = true;
    } else {
      showErrorInput(description, "", false);
      data.description = description.value;
    }

    if (!hasError) {
      let messageHTML = "";
      const popup = document.querySelector(".popup");
      const popupContainer = document.querySelector(".popup-content");
      try {
        const token = await authAPI();
        if (!token) return;

        data.where_buy = where_buy.value;
        data.numero_lote = numeroLote.value;
        data.image = Array.from(image.files);

        const formData = new FormData();

        formData.append("category", data.category);
        formData.append("producto", data.producto);
        formData.append("producto_nombre", data.producto_nombre);
        formData.append("description", data.description);
        formData.append("where_buy", data.where_buy);
        formData.append("numero_lote", data.numero_lote);
        formData.append("nombre", data.nombre);
        formData.append("apellido", data.apellido);
        formData.append("email", data.email);
        formData.append("telefono", data.telefono);

        data.image.forEach((file) => {
          formData.append("image", file);
        });

        const response = await fetch(
          "https://api.tromentools.com/tickets/create-web/",
          {
            method: "POST",
            headers: {
              Authorization: `Bearer ${token}`,
            },
            body: formData,
          }
        );

        const result = await response.json();

        // console.log("Respuesta:", result);

        if (response.ok) {
          // Limpieza del formulario
          name.value = "";
          surname.value = "";
          email.value = "";
          telephone.value = "";
          where_buy.value = "";
          category.value = "";
          product.value = "";
          checkProduct.checked = false;
          product_name.value = "";
          numeroLote.value = "";
          description.value = "";
          image.value = "";

          product_name.classList.add("d-none");
          messageHTML += `
                <div class="icon success">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="content">
                  <h2>Reclamo ID ${result.ticket_id}</h2>
                  <p>Tu reclamo se ha enviado correctamente!</p>
                </div>
            `;
          popupContainer.innerHTML = messageHTML;
          popup.classList.add("show");
          resetFile();
        } else {
          messageHTML += `
                <div class="icon error">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="content">
                  <h2>Error</h2>
                  <p>Intentalo de nuevo más tarde!</p>
                </div>
            `;
          popupContainer.innerHTML = messageHTML;
          popup.classList.add("show");
        }
      } catch (error) {
        console.error("Error al enviar:", error);
        messageHTML += `
                <div class="icon error">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="content">
                  <h2>Error</h2>
                  <p>Intentalo de nuevo más tarde!</p>
                </div>
            `;
        popupContainer.innerHTML = messageHTML;
        popup.classList.add("show");
      }
    }
  };

  if (submit) {
    submit.addEventListener("click", (e) => {
      e.preventDefault();
      sendFormularioReclamos();
    });
  }
});
