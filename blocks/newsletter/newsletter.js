window.addEventListener("DOMContentLoaded", (e) => {
  const email = document.getElementById("email-newsletter");
  const send = document.getElementById("submit-news");
  const url = JSON.stringify(ajax_var.url).replace(/['"]+/g, "");
  const current_lang = document.getElementById('current-lang-tromen').value

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

  if (send) {
    const popup = document.querySelector(".popup");
    const popupContainer = document.querySelector(".popup-content");
    const destinatario = email.getAttribute('data-destinatario')
   
    send.addEventListener("click", (e) => {
      e.preventDefault();
      let messageHTML = "";


      if (validator.isEmpty(email.value)) {
        messageHTML += `
                <div class="icon error">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="content">
                  <h2>ERROR</h2>
                  <p>${current_lang === 'en' ? 
                    'Enter an email.' : 
                    'Ingrese un email.'}
                  </p>

                </div>
                `;
        popupContainer.innerHTML = messageHTML;
        popup.classList.add("show");
        return;
      }

      if (!validator.isEmail(email.value)) {
        messageHTML += `
                <div class="icon error">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="content">
                  <h2>ERROR</h2>
                  <p>${current_lang === 'en' ? 
                    'Enter a valid email.' : 
                    'Ingrese un email válido.'}
                  </p>
                </div>
                `;
        popupContainer.innerHTML = messageHTML;
        popup.classList.add("show");
        return;
      }

      const formData = new FormData();
      formData.append("action", "send_form_newsletter");
      formData.append("destinatario", destinatario);
      formData.append("email", email.value);

      sendContactEnvialoSimple(email.value);

      fetch(`${url}`, {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          // Verificar si la respuesta HTTP fue exitosa
          if (!response.ok) {
            throw new Error(`${response.status} - ${response.statusText}`);
          }
          return response.text(); // Procesar el cuerpo de la respuesta
        })
        .then((data) => {
          let messageHTML = "";
          if (data === "ok0") {
            messageHTML += `
              <div class="icon success">
                <i class="fa-solid fa-check"></i>
              </div>
              <div class="content">
                <h2>${current_lang === 'en' ? 'Thank you' : 'Gracias'}</h2>
                <p>${current_lang === 'en' ? 
                  'Thank you for subscribing to our newsletter!' : 
                  '¡Gracias por suscribirte a nuestro newsletter!'}</p>
              </div>
            `;
            popupContainer.innerHTML = messageHTML;
            email.value = ""; // Limpiar el campo de correo
            popup.classList.add("show");
          } else {
            messageHTML += `
              <div class="icon error">
                <i class="fa-solid fa-check"></i>
              </div>
              <div class="content">
                <h2>ERROR</h2>
                <p>${current_lang === 'en' ? 
                  'There was an error sending your message. Please try again.' : 
                  'Hubo un error al enviar tu mensaje. Por favor, intenta de nuevo.'}</p>
              </div>
            `;
            popupContainer.innerHTML = messageHTML;
            popup.classList.add("show");
          }

          // AQUI
        })
        .catch((error) => {
          // Manejar errores de red o problemas con el servidor
          console.error("Error:", error);
          let messageHTML = `
            <div class="icon error">
              <i class="fa-solid fa-check"></i>
            </div>
            <div class="content">
              <h2>ERROR</h2>
              <p>${current_lang === 'en' ? 
                'There was a network error. Please try again later.' : 
                'Hubo un error de red. Por favor, intenta más tarde.'}</p>
            </div>
          `;
          popupContainer.innerHTML = messageHTML;
          popup.classList.add("show");
        });
    });
  }



  const sendContactEnvialoSimple = async (email) => {
    const emailForm = document.getElementById("email-newsletter");
    if (!emailForm.dataset.envialosimple) return;
  
    const url = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
    const formData = new FormData();
    formData.append("email", email);
    formData.append("action", "create");
  
    try {
      const response = await fetch(`${url}/proxy-envialosimple.php`, {
        method: "POST",
        body: formData,
      });
  
      const responseData = await response.json();
  
      if (responseData.code === "errorMsg_contactAlreadyExist") {
        const contactResponse = await getContact(formData.get("email"));
        // console.log("Respuesta DESDE SEND CONTACT YA EXISTE:", contactResponse);
      } else {
        const suscribeResponse = await suscribeList(responseData);
        // console.log("Respuesta DESDE SEND CONTACT NO EXISTE:", suscribeResponse);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  const getContact = async (email) => {
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
      const suscribeResponse = await suscribeList(dataContact);
      // console.log("Respuesta después de editar contacto:", suscribeResponse);
    } catch (error) {
      console.error("Error en getContact:", error);
    }
  };

  const suscribeList = async (dataUser) => {
    const url = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
    const { status, data } = dataUser;
    const formData = new FormData();
  
    if (status === "ok") {
      formData.append("action", "suscribe");
      formData.append("contactsIds[]", data.id);
      formData.append("listId", email.dataset.envialosimple);
  
      // console.log("SE SUBSCRIBE");
      // console.log('works')
      try {
        const response = await fetch(`${url}/proxy-envialosimple.php`, {
          method: "POST",
          body: formData,
        });
  
        const responseData = await response.json();
        // console.log("Respuesta DESDE SUSCRIBE:", responseData);
        return responseData;
      } catch (error) {
        console.error("Error:", error);
        throw error;
      }
    }
  };

  
});
