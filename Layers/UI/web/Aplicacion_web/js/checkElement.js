//    Marca un elemento (cuyo indicador es 'element') como activo en un formulario
    function checkElement(element) { 
        var element = document.getElementById(element);
        if(element !== null){
            element.checked = true;
            return true;
        }
        return false;
    }
    

