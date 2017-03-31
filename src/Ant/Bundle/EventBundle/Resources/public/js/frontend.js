/**
 * Crea un contador de tiempo que se actualiza cada segundo y que muestra
 * cuánto falta para que expire la oferta.
 */
function muestraCuentaAtras(id, fecha) {
    var days, horas, minutos;
    
    var ahora = new Date();
    var fechaExpiracion = new Date(fecha.ano, fecha.mes, fecha.dia, fecha.hora, fecha.minuto);
    
    var falta = Math.floor( (fechaExpiracion.getTime() - ahora.getTime()) / 1000 );
    
    if (falta < 0) {
        cuentaAtras = '-';
    }
    else {
                
        days = Math.floor(falta/86400);
        falta = falta % 86400;
        
        horas = Math.floor(falta/3600);
        falta = falta % 3600;
        
        
        minutos = Math.floor(falta/60);
        falta = falta % 60;
        
        cuentaAtras = 
                    (days < 1 ? '' :  days + 'd ')
                    + (horas < 10    ? '0' + horas    : horas)    + 'h '
                    + (minutos < 10  ? '0' + minutos  : minutos)  + 'm ';
        
        setTimeout(function() {
            muestraCuentaAtras(id, fecha);
        }, 60000);
    }
    
    document.getElementById(id).innerHTML = cuentaAtras;
}