<?php
/*********************************************************************
    class.group.php
**********************************************************************/

class Group {

    function update($id,$vars,&$errors) {
        if($id && Group::save($id,$vars,$errors)){
            return true;
        }
        return false;
    }

    function create($vars,&$errors) { 
        return Group::save(0,$vars,$errors);
    }

    function save($id,$vars,&$errors) {

        if($id && !$vars['group_id'])
            $errors['err']='Falta la ID de Grupo o es invalida.';
            
        if(!$vars['group_name']) {
            $errors['group_name']='Nombre de grupo requerido';
        }elseif(strlen($vars['group_name'])<5) {
            $errors['group_name']='El nombre del grupo debe tener al menos 5 caracteres.';
        }else {
            $sql='SELECT group_id FROM '.GROUP_TABLE.' WHERE group_name='.db_input($vars['group_name']);
            if($id)
                $sql.=' AND group_id!='.db_input($id);

            if(db_num_rows(db_query($sql)))
                $errors['group_name']='Este nombre de grupo ya existe.';
        }
        
        if(!$errors){
        
            $sql=' SET updated=NOW(), group_name='.db_input(Format::striptags($vars['group_name'])).
                 ', group_enabled='.db_input($vars['group_enabled']).
                 ', dept_access='.db_input($vars['depts']?implode(',',$vars['depts']):'').
                 ', can_create_tickets='.db_input($vars['can_create_tickets']).
                 ', can_delete_tickets='.db_input($vars['can_delete_tickets']).
                 ', can_edit_tickets='.db_input($vars['can_edit_tickets']).
                 ', can_transfer_tickets='.db_input($vars['can_transfer_tickets']).
                 ', can_close_tickets='.db_input($vars['can_close_tickets']).
                 ', can_ban_emails='.db_input($vars['can_ban_emails']).
                 ', can_manage_kb='.db_input($vars['can_manage_kb']);
            //echo $sql;
            if($id) {
                $res=db_query('UPDATE '.GROUP_TABLE.' '.$sql.' WHERE group_id='.db_input($id));
                if(!$res || !db_affected_rows())
                    $errors['err']='Error interno';
            }else{
                $res=db_query('INSERT INTO '.GROUP_TABLE.' '.$sql.',created=NOW()');
                if($res && ($gID=db_insert_id()))
                    return $gID;
                
                $errors['err']='No se a podido crear el grupo, Error interno';
            }
        }

        return $errors?false:true;
    }
}
?>