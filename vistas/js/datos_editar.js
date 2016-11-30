function eliminarDato(url){
    var mensaje = 'Confirma eliminar el dato seleccionado?';
    
    if (window.confirm(mensaje)){
        window.location = url;
    }
}