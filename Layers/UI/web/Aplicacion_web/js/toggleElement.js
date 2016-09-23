//Establece el estado del elemento con id element a state
    function toggleElement(element, state) { 
        var element = document.getElementById(element);
        
        if(element !== null){
            element.disabled = state;
            return true;
        }
        return false;
    }
    

    

