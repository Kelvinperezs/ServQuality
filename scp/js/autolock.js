
var autoLock = {
    
    addEvent: function(elm, evType, fn, useCapture) {
        if(elm.addEventListener) {
            elm.addEventListener(evType, fn, useCapture);
            return true;
        }else if(elm.attachEvent) {
            return elm.attachEvent('on' + evType, fn);
        }else{
            elm['on' + evType] = fn;
        }
    },

    removeEvent: function(elm, evType, fn, useCapture) {
        if(elm.removeEventListener) {
            elm.removeEventListener(evType, fn, useCapture);
            return true;
        }else if(elm.detachEvent) {
            return elm.detachEvent('on' + evType, fn);
        }else {
            elm['on' + evType] = null;
        }
    },

    //eventos entrantes
    handleEvent: function(e) {
        if(!autoLock.lockId) {
            var now = new Date().getTime();
            //Vuelva a intentar cada 5 segundos??
            if(autoLock.retry && (!autoLock.lastattemptTime || (now-autoLock.lastattemptTime)>5000)) {
                autoLock.acquireLock(e,autoLock.warn);
                autoLock.lastattemptTime=new Date().getTime();
            }
        }else{
            autoLock.renewLock(e);
        }
        if(!autoLock.lasteventTime) //distancias y avertencias
            autoLock.addEvent(window,'beforeunload',autoLock.discardWarning,true);
        autoLock.lasteventTime=new Date().getTime();
    },

    //La actividad en forma de reloj individual.
    watchForm: function(fObj,fn) {
        if(!fObj || !fObj.length)
            return;

        //Ver y Enviar evento en el formulario
        autoLock.addEvent(fObj,'submit',autoLock.onSubmit,true);
        //Observar la actividad en las áreas de texto  + Seleccionar campos.
        for (var i=0; i<fObj.length; i++) {
            switch(fObj[i].type) {
                case 'textarea':
                case 'text':
                    autoLock.addEvent(fObj[i],'keyup',autoLock.handleEvent,true);
                    break;
                case 'select-one':
                case 'select-multiple':
                    if(fObj.name!='reply') 
                        autoLock.addEvent(fObj[i],'change',autoLock.handleEvent,true);
                    break;
                default:
            }
        }
    },

    //Mira todas las formas sobre el documento.
    watchDocument: function() {

        //Ver formas de interés solamente.
        for (var i=0; i<document.forms.length; i++) {
            if(!document.forms[i].ticket_id.value || parseInt(document.forms[i].ticket_id.value)!=autoLock.tid)
                continue;
            autoLock.watchForm(document.forms[i],autoLock.checkLock);
        }
    },

    Init: function(tid) {

        //Comprueba  disponibilidad.
        if(!Http || !Http.get) { return; }
        
        //make sure we are on ticket view pageasegurarnos de que estamos en la página vista ticket!
        void(autoLock.form=document.forms['replyform']);
        if(!autoLock.form || !autoLock.form.ticket_id.value) {
                return;
        }

        void(autoLock.tid=parseInt(autoLock.form.ticket_id.value));
        autoLock.lockId=0;
        autoLock.timerId=0;
        autoLock.lasteventTime=0;
        autoLock.lastattemptTime=0;
        autoLock.acquireTime=0;
        autoLock.renewTime=0;
        autoLock.renewFreq=0; 
        autoLock.time=0;
        autoLock.lockAttempts=0; 
        autoLock.maxattempts=2; 
        autoLock.warn=true;
        autoLock.retry=true;
        autoLock.watchDocument();
        autoLock.resetTimer();
        autoLock.addEvent(window,'unload',autoLock.releaseLock,true); 
    },
          

    onSubmit: function(e) {
        if(e.type=='submit') { 
		
            autoLock.removeEvent(window,'beforeunload',autoLock.discardWarning,true);
            
            if(autoLock.warn && !autoLock.lockId && autoLock.lasteventTime) {
                var answer=confirm('Unable to acquire a lock on the ticket. Someone else could be working on the same ticket. \
                    Please confirm if you wish to continue anyways.');
                if(!answer) {
                    e.returnValue=false;
                    e.cancelBubble=true;
                    if(e.preventDefault) {
                        e.preventDefault();
                    }
                    return false;
                }
            }
        }
        return true;
    },
    
    acquireLock: function(e,warn) {      

        if(!autoLock.tid || autoLock.lockId) { return false; }
        var warn = warn || false;
        if(!autoLock.lockId) {
            Http.get({
                url: "ajax.php?api=tickets&f=acquireLock&tid="+autoLock.tid,
                callback: autoLock.setLock
            },['acquire',warn]);
        }else{
            autoLock.renewLock(e);
        }    
        return autoLock.lockId;
    },

    
    renewLock: function(e) {
        
        if(!autoLock.lockId) { return false; }
        
        var now= new Date().getTime(); 
        if(!autoLock.lastcheckTime || (now-autoLock.lastcheckTime)>=(autoLock.renewFreq*1000)){
            Http.get({
                url: "ajax.php?api=tickets&f=renewLock&id="+autoLock.lockId+'&tid='+autoLock.tid,
                callback: autoLock.setLock
                },['renew',autoLock.warn]);
        }
    }, 
     
    releaseLock: function(e) {
        
        if(!autoLock.tid) { return false; }
        Http.get({
            url: "ajax.php?api=tickets&f=releaseLock&id="+autoLock.lockId+'&tid='+autoLock.tid,
            callback: function (resp){ alert(resp);}
            });
    },

    setLock: function(reply,action,warn) {
        var warn = warn || false;
        if(reply.status == Http.Status.OK && reply.responseText) {
            var lock = eval('('+reply.responseText+')');
            if(lock.id) {
                autoLock.renewFreq=lock.time?(lock.time/2):30;
                autoLock.lastcheckTime=new Date().getTime();
            }
            autoLock.lockId=lock.id;
            switch(action){
                case 'renew':
                    if(!lock.id && lock.retry) {
                        autoLock.lockAttempts=1; 
                        autoLock.acquireLock(e,false);
                    }
                    break;
                case 'acquire':
                    if(!lock.id) {
                        autoLock.lockAttempts++;
                        if(warn && (!lock.retry || autoLock.lockAttempts>=autoLock.maxattempts)) {
                            autoLock.retry=false;
                            alert('Unable to lock the ticket. Someone else could be working on the same ticket.'); 
                        }
                    }   
                    break;
            }
        }
    },
    
    
    discardWarning: function(e) {
        e.returnValue="Any changes or info you've entered will be discarded!";
    },

   
    monitorEvents: function() {
       
    },

    clearTimer: function() {
        clearTimeout(autoLock.timerId);
    },
    
    resetTimer: function() {
        clearTimeout(autoLock.timerId);
        autoLock.timerId=setTimeout(function () { autoLock.monitorEvents() },30000);
    }
}

autoLock.addEvent(window, 'load', autoLock.Init, false);
