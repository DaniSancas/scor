$.extend($.fn.pickadate.defaults, {
    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
    today: 'Hoy',
    clear: 'Borrar',

    firstDay: 1,
    format: 'dddd d !de mmmm !de yyyy',
    formatSubmit: 'dddd d !de mmmm !de yyyy',
    disable: [6, 7],

    labelMonthNext: 'Siguiente mes',
    labelMonthPrev: 'Mes anterior',
    labelMonthSelect: 'Seleccione un mes',
    labelYearSelect: 'Seleccione un año'
})

$.extend($.fn.pickatime.defaults, {
    clear: 'Borrar',

    format: 'HH:i',
    formatLabel: 'HH:i',
    formatSubmit: 'HH:i'
})