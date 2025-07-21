window.addEventListener('DOMContentLoaded', () => {
    searchHome();
})

const searchHome = () => {
    let searchForm = document.querySelector('.form-search');
    if(searchForm){
        setupCalendar('fechaIngreso', 'fechaSalida');
    }
    // Calendario

 
}