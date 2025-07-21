const selects = document.querySelectorAll(".field-container-input");
const form = document.querySelector(".form");

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

const searchInputForm = document.querySelectorAll(
  ".field-container-input-search"
);

if (searchInputForm.length > 0) {
  let typingTimeout;
  searchInputForm.forEach((search) => {
    const input = search.querySelector("input");
    input.addEventListener("input", async (e) => {
      clearTimeout(typingTimeout);
      typingTimeout = setTimeout(() => {
        const query = input.value;
        if (query.trim().length > 0) {
          filterListProduct(
            query,
            input.nextElementSibling.firstElementChild,
            input
          );
          // searchProduct(query, input.nextElementSibling.firstElementChild, input);
          input.nextElementSibling.firstElementChild.classList.add("show");
        }
      }, 500);
    });
  });
}

const fileContainers = document.querySelectorAll(".field-file");
if (fileContainers.length > 0) {
  fileContainers.forEach((fileContainer) => {
    if (fileContainer) {
      const label = fileContainer.querySelector("label");
      const input = fileContainer.querySelector("input");

      input.addEventListener("change", (e) => {
        let files = e.target.files[0];
        let filesLengh = e.target.files.length;
        let fileName = "";
        if (files && filesLengh >= 1) {
          let labelHTML = "";
          fileName = files.name;
          // if (fileName.length > 30) {
          //   fileName = fileName.substring(0, 30) + "...";
          // }
          labelHTML += `
              <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9.80113 11.2025C9.76985 11.1715 9.7276 11.1541 9.68358 11.1541C9.63955 11.1541 9.59731 11.1715 9.56602 11.2025L7.14834 13.6202C6.02896 14.7395 4.13975 14.8581 2.90386 13.6202C1.66589 12.3822 1.78449 10.4951 2.90386 9.37568L5.32155 6.95799C5.38605 6.89349 5.38605 6.78738 5.32155 6.72288L4.49346 5.89479C4.46218 5.86381 4.41993 5.84644 4.3759 5.84644C4.33188 5.84644 4.28963 5.86381 4.25835 5.89479L1.84066 8.31248C0.0804557 10.0727 0.0804557 12.9211 1.84066 14.6792C3.60087 16.4373 6.44925 16.4394 8.20737 14.6792L10.6251 12.2615C10.6896 12.197 10.6896 12.0909 10.6251 12.0264L9.80113 11.2025ZM15.1816 1.3403C13.4214 -0.419911 10.573 -0.419911 8.81492 1.3403L6.39515 3.75799C6.36417 3.78927 6.3468 3.83152 6.3468 3.87554C6.3468 3.91957 6.36417 3.96181 6.39515 3.9931L7.22116 4.81911C7.28566 4.88361 7.39177 4.88361 7.45627 4.81911L9.87395 2.40142C10.9933 1.28204 12.8825 1.16345 14.1184 2.40142C15.3564 3.63939 15.2378 5.52652 14.1184 6.6459L11.7007 9.06359C11.6698 9.09487 11.6524 9.13712 11.6524 9.18114C11.6524 9.22517 11.6698 9.26741 11.7007 9.2987L12.5288 10.1268C12.5933 10.1913 12.6994 10.1913 12.7639 10.1268L15.1816 7.7091C16.9398 5.94889 16.9398 3.10051 15.1816 1.3403ZM10.5522 5.10415C10.521 5.07318 10.4787 5.0558 10.4347 5.0558C10.3907 5.0558 10.3484 5.07318 10.3171 5.10415L5.60451 9.81469C5.57354 9.84598 5.55616 9.88822 5.55616 9.93225C5.55616 9.97627 5.57354 10.0185 5.60451 10.0498L6.42844 10.8737C6.49294 10.9382 6.59905 10.9382 6.66355 10.8737L11.3741 6.16319C11.4386 6.09869 11.4386 5.99258 11.3741 5.92808L10.5522 5.10415Z" fill="#737373"/>
              </svg>
                      <span>${fileName}</span>
                  `;
          label.innerHTML = labelHTML;
        }
      });
    }
  });
}

const textarea = document.querySelectorAll("textarea");
if (textarea.length > 0) {
  textarea.forEach((text) => {
    text.addEventListener("input", (e) => {
      if (text.dataset.count === "yes") {
        let count = text.nextElementSibling.firstElementChild;
        let length = text.value.length;
        let maximoCaracteres = parseInt(text.getAttribute("maxlength"));
        count.textContent = maximoCaracteres - length;
      }
    });
  });
}

const filterListProduct = (query, container, input) => {
  const options = container.querySelectorAll(".options-list-select");

  options.forEach((option) => {
    if (query.length > 0) {
      option.classList.remove("d-none");
    }
    if (option.textContent.toLowerCase().includes(query.toLowerCase())) {
      container.parentElement.classList.add("show");
      option.classList.remove("d-none");
    } else {
      container.parentElement.classList.add("show");
      option.classList.add("d-none");
    }
  });
};

const API = async (query = {}) => {
  try {
    const params = new URLSearchParams({ action: "search_products", ...query });
    const url = `${JSON.stringify(ajax_var.url).replace(
      /['"]+/g,
      ""
    )}?${params.toString()}`; // Construir la URL con los parámetros
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error("Failed to fetch data");
    }
    const products = await response.json();
    return products.data;
  } catch (error) {
    console.error("Error fetching data", error);
    throw error; // Propagar el error para manejarlo en el llamador
  }
};

const sendContactEnvialoSimple = async () => {
  if (!form.dataset.envialosimple) return;

  const url = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
  const inputs = Array.from(document.querySelectorAll("[data-envialosimple-custom-id]"));
  const formData = new FormData();
  const userData = { customFields: {} };

  inputs.forEach((input) => {
    if (input.dataset.envialosimpleCustomId === "email") {
      formData.append("email", input.value);
      userData.email = input.value;
    } else {
      formData.append(`customFields[${input.dataset.envialosimpleCustomId}]`, input.value);
      userData.customFields[input.dataset.envialosimpleCustomId] = input.value;
    }
  });

  formData.append("action", "create");

  try {
    const response = await fetch(`${url}/proxy-envialosimple.php`, {
      method: "POST",
      body: formData,
    });

    const responseData = await response.json();

    if (responseData.code === "errorMsg_contactAlreadyExist") {
      const contactResponse = await getContact(formData.get("email"), userData);
     
    } else {
      const suscribeResponse = await suscribeList(responseData);
    
    }
  } catch (error) {
    console.error("Error:", error);
  }
};

const suscribeList = async (dataUser) => {
  const url = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
  const { status, data } = dataUser;
  const formData = new FormData();

  if (status === "ok") {
    formData.append("action", "suscribe");
    formData.append("contactsIds[]", data.id);
    formData.append("listId", form.dataset.envialosimple);
    try {
      const response = await fetch(`${url}/proxy-envialosimple.php`, {
        method: "POST",
        body: formData,
      });

      const responseData = await response.json();
    
      return responseData;
    } catch (error) {
      console.error("Error:", error);
      throw error;
    }
  }
};

const getContact = async (email, data) => {
  const url = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
  const formData = new FormData();
  formData.append("action", "getContact");
  formData.append("email", email);

  try {
    const response = await fetch(`${url}/proxy-envialosimple.php`, {
      method: "POST",
      body: formData,
    });

    const responseData = await response.json();

    if (!responseData.data?.data?.length) {
      throw new Error("No se encontró el contacto.");
    }

    let dataContact = {
      status: "ok",
      data: responseData.data.data[0], // Pasar todo el objeto
    };

    // Editar contacto y suscribirlo después
    const editContactResponse = await editContact(data, responseData.data.data[0].id, dataContact);
    // console.log("Respuesta después de editar contacto:", editContactResponse);
  } catch (error) {
    console.error("Error en getContact:", error);
  }
};

const editContact = async (data, id, dataContact) => {
  const url = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
  const formData = new FormData();
  formData.append("action", "editContact");
  formData.append("id", id);

  Object.keys(data.customFields).forEach((key) => {
    formData.append(`customFields[${key}]`, data.customFields[key]);
  });

  try {
    const response = await fetch(`${url}/proxy-envialosimple.php`, {
      method: "POST",
      body: formData,
    });

    const responseData = await response.json();
    // console.log("Código de respuesta editContact:", responseData.code);

    if (responseData.code === "msg_contactUpdated") {
      // console.log("Contacto actualizado correctamente.");
      const suscribeResponse = await suscribeList(dataContact);
      // console.log("Respuesta después de suscribir:", suscribeResponse);
    } else {
      console.error("Error: No se pudo actualizar el contacto");
    }

    return responseData;
  } catch (error) {
    console.error("Error en editContact:", error);
    return { error: "Hubo un problema al editar el contacto" };
  }
};


// ENVIAR EL FORMULARIO
if (form) {
  const submit = document.querySelector(".btn-submit-form");
  if (submit) {
    const current_lang = document.getElementById("current-lang-tromen").value;
    const inputs = form.querySelectorAll("input");
    const checkbox = form.querySelectorAll('input[data-type="checkbox"]');
    const textarea = form.querySelectorAll("textarea");
    const url = JSON.stringify(ajax_var.url).replace(/['"]+/g, "");
    const requerid_string =
      current_lang === "en"
        ? "This field is required."
        : "El campo es obligatorio.";
    const requerid_string_select_products =
      current_lang === "en"
        ? "Select an option from the list"
        : "Seleccionar una opción de la lista.";
    const requerid_email_string =
      current_lang === "en"
        ? "Please enter a valid email address."
        : "Ingresa un email válido.";
    const destinatario = document.querySelector(".form").dataset.destinatario;

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
    // Función para validar campos de texto, números y selects
    function validateTextInput(input) {
      if (input.dataset.requerid) {
        const label = input.previousElementSibling;
        const errorMsg = label.querySelector(".required-error");
        if (validator.isEmpty(input.value)) {
          input.classList.add("error");
          label.classList.add("error");
          if (errorMsg) {
            errorMsg.textContent = requerid_string;
          }
          return true;
        } else {
          input.classList.remove("error");
          label.classList.remove("error");
          if (errorMsg) {
            errorMsg.textContent = "";
          }
          return false;
        }
      }
    }

    function validateEmailInput(input) {
      const email = input.value.trim(); // Remove leading and trailing whitespaces
      // Get the label element associated with the input
      const label = input.previousElementSibling;
      const errorMsg = label.querySelector(".required-error");
      // Check if the input is required (based on data-requerid attribute)
      if (input.dataset.requerid && validator.isEmpty(email)) {
        // If the input is required and empty, show "Campo obligatorio" message
        input.classList.add("error");
        label.classList.add("error");
        if (errorMsg) {
          errorMsg.textContent = requerid_string;
        }
        return true; // Validation failed
      } else if (!validator.isEmail(email)) {
        // If the input is not a valid email address, show "Email inválido" message
        label.classList.add("error");
        input.classList.add("error");
        if (errorMsg) {
          errorMsg.textContent = requerid_email_string;
        }
        return true; // Validation failed
      } else {
        // If the input is not empty and contains a valid email address, remove error
        input.classList.remove("error");
        label.classList.remove("error");
        errorMsg.textContent = "";
        return false; // Validation passed
      }
    }

    function validateSelect(input) {
      if (input.dataset.requerid) {
        const label =
          input.previousElementSibling.parentElement.previousElementSibling;
        const errorMsg = label.querySelector(".required-error");
        if (validator.isEmpty(input.value)) {
          input.classList.add("error");
          label.classList.add("error");
          if (errorMsg) {
            errorMsg.textContent = requerid_string;
          }
          return true;
        } else {
          input.classList.remove("error");
          label.classList.remove("error");
          if (errorMsg) {
            errorMsg.textContent = "";
          }
          return false;
        }
      }
    }

    function validateSelectProducts(input) {
      if (input.dataset.requerid) {
        const label =
          input.previousElementSibling.parentElement.previousElementSibling;
        const errorMsg = label.querySelector(".required-error");
        if (validator.isEmpty(input.value)) {
          input.classList.add("error");
          label.classList.add("error");
          if (errorMsg) {
            errorMsg.textContent = requerid_string;
          }
          return true;
        } else {
          let isValid = false;
          const options = document.querySelectorAll(".options-list-select");
          options.forEach((option) => {
            if (input.value === option.firstElementChild.textContent) {
              isValid = true;
            }
          });
          if (isValid) {
            input.classList.remove("error");
            label?.classList.remove("error");
            if (errorMsg) errorMsg.textContent = "";
            return false;
          } else {
            input.classList.add("error");
            label?.classList.add("error");
            if (errorMsg)
              errorMsg.textContent = requerid_string_select_products;
            return true;
          }
        }
      }
    }

    // Función para validar campos de archivo
    function validateFileInput(input) {
      if (input.dataset.requerid) {
        let label = input.parentElement.parentElement.previousElementSibling;

        const errorMsg = label.querySelector(".required-error");

        if (validator.isEmpty(input.value)) {
          input.classList.add("error");
          label.classList.add("error");
          if (errorMsg) {
            errorMsg.textContent = requerid_string;
          }
          return true;
        } else {
          input.classList.remove("error");
          label.classList.remove("error");
          if (errorMsg) {
            errorMsg.textContent = "";
          }
          return false;
        }
      }
    }

    // Función para validar campos de textarea
    function validateTextarea(text) {
      if (text.dataset.requerid) {
        const label = text.previousElementSibling;
        const errorMsg = label.querySelector(".required-error");
        if (validator.isEmpty(text.value)) {
          text.classList.add("error");
          label.classList.add("error");
          if (errorMsg) {
            errorMsg.textContent = requerid_string;
          }
          return true;
        } else {
          text.classList.remove("error");
          label.classList.remove("error");
          if (errorMsg) {
            errorMsg.textContent = "";
          }
          return false;
        }
      }
    }

    // Función para recopilar datos del formulario
    function collectFormData() {
      const formData = new FormData();
      const user = {};
      inputs.forEach((input) => {
        if (input.dataset.type === "file") {
          user[input.dataset.label] = input.files[0];
        } else {
          // if (input.dataset.type === "checkbox" && !input.checked) {
          //   return;
          // }
          if (
            input.dataset.type === "select-products" ||
            input.dataset.type === "select" ||
            input.dataset.type === "text" ||
            input.dataset.type === "email" ||
            input.dataset.type === "number"
          ) {
            user[input.dataset.label] = input.value;
          }
        }
      });

      if (checkbox.length > 0) {
        let checkData = [];
        let label = "";
        checkbox.forEach((check) => {
          let text =
            check.parentElement.parentElement.previousElementSibling.dataset
              .label;
          label = text;
          if (check.checked) {
            checkData.push(`${check.dataset.nombre}`);
          }
        });

        user[label] = checkData;
      }

      textarea.forEach((text) => {
        user[text.dataset.label] = text.value;
      });

      Object.entries(user).forEach(([key, value]) => {
        formData.append(key, value);
      });

      let input_subject_1 = document.querySelectorAll(".form input")[0].value || '';
      let input_subject_2 = document.querySelectorAll(".form input")[1].value || '';
      // Generar timestamp actual
      let now = new Date();
      let timestamp = `${now.getDate().toString().padStart(2, '0')}/${(now.getMonth()+1).toString().padStart(2, '0')}/${now.getFullYear()} ${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}:${now.getSeconds().toString().padStart(2, '0')}`;

      // Armar asunto final
      let input_subject = `${input_subject_1} ${input_subject_2} – ${timestamp}`;

      formData.append("action", "send_form_contact");
      formData.append("destinatario", destinatario.trim());
      formData.append(
        "subject",
        `${form.dataset.asunto} ${input_subject} recibida desde tromen.com`
      );

      return formData;
    }

    // RESET INPUT FILE
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

    // Event listener para el botón de envío
    submit.addEventListener("click", (e) => {
      e.preventDefault();

      let error = false;
      // Validar campos de entrada
      inputs.forEach((input) => {
        let type = input.dataset.type;
        switch (type) {
          case "text":
          case "number":
            if (validateTextInput(input)) {
              error = true;
            }
            break;
          case "select":
            if (validateSelect(input)) {
              error = true;
            }
            break;
          case "email":
            if (validateEmailInput(input)) {
              error = true;
            }
            break;
          case "file":
            if (validateFileInput(input)) {
              error = true;
            }
            break;
          case "select-products":
            if (validateSelectProducts(input)) {
              error = true;
            }
            break;
          default:
            break;
        }
      });

      // Validar campos de textarea
      textarea.forEach((text) => {
        if (validateTextarea(text)) {
          error = true;
        }
      });

      // Enviar formulario si no hay errores
      if (!error) {
        const formData = collectFormData();
        fetch(`${url}`, {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            let messageHTML = "";
            const popup = document.querySelector(".popup");
            const popupContainer = document.querySelector(".popup-content");
            const form = document.querySelector(".form");
            // Manejar la respuesta del servidor si es necesario
            
            if (data.success) {
              let customMessage =
                "¡Gracias por tu mensaje! Pronto nos comunicamos.";
              // Verifica si estamos en la página "garantia"
              if (window.location.pathname.includes("garantia")) {
                customMessage = "Tu garantía ha sido registrada correctamente.";
              }
              messageHTML += `
                <div class="icon success">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="content">
                  <h2>${current_lang === "en" ? "Thank you" : "Gracias"}</h2>
                  <p>${customMessage}</p>
                </div>
              `;
              // SEND CONTACT ENVIALOSIMPLE
              sendContactEnvialoSimple();
              popupContainer.innerHTML = messageHTML;
              popup.classList.add("show");
              form.reset();
              resetFile();
              console.log('Aca todo bien')
            } else {
              messageHTML += `
              <div class="icon error">
                  <i class="fa-solid fa-check"></i>
              </div>
              <div class="content">
                <h2>ERROR</h2>
                <p>${
                  current_lang === "en"
                    ? "A server error occurred. Please try again later."
                    : "Ocurrió un error en el servidor. Intenta nuevamente más tarde."
                }
                </p>
              </div>
              `;
              popupContainer.innerHTML = messageHTML;
              popup.classList.add("show");
              form.reset();
              resetFile();
            }
          });
      }
    });
  }
}
