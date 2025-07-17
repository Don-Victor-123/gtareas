<?php
session_start();
require __DIR__ . '/../config/db.php';
if ($_SESSION['user']['role'] !== 'Recepcionista') exit('Acceso denegado');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><title>Dashboard Notas</title>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const dateInput = document.getElementById('selectedDate');
    const noteDate = document.getElementById('noteDate');
    function loadNotes(date){
        fetch(`get_notes.php?date=${date}`)
            .then(res=>res.json())
            .then(data=>{
                ['pendiente','en_proceso','completada'].forEach(status=>{
                    const ul=document.getElementById(status);
                    ul.innerHTML='';
                    data[status].forEach(note=>{
                        const li=document.createElement('li');
                        li.innerHTML = `<strong>[${note.status}]</strong> ${note.title} - ${note.username}
                        <p>${note.content}</p>
                        <small>${note.created_at}${note.modificado?' ('+note.modificado+')':''}</small>`;
                        ul.appendChild(li);
                    });
                });
            });
    }
    dateInput.addEventListener('change', ()=> { noteDate.value = dateInput.value; loadNotes(dateInput.value); });
    document.getElementById('noteForm').addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        fetch('add_note.php',{method:'POST',body:formData})
            .then(res=>res.json())
            .then(resp=>{ if(resp.success){ loadNotes(dateInput.value); this.reset(); noteDate.value = dateInput.value; } });
    });
    const today = new Date().toISOString().slice(0,10);
    dateInput.value = today;
    noteDate.value = today;
    loadNotes(today);
});
</script>
</head>
<body>
<h2>Dashboard de Notas</h2>
<label>Selecciona fecha: <input type="date" id="selectedDate"></label>
<div id="notesContainer">
    <h3>Pendiente</h3><ul id="pendiente"></ul>
    <h3>En Proceso</h3><ul id="en_proceso"></ul>
    <h3>Realizado</h3><ul id="completada"></ul>
</div>
<h3>Agregar Nota</h3>
<form id="noteForm">
    <input type="hidden" name="date" id="noteDate">
    <label>Título:<input name="title" required></label><br>
    <label>Contenido:<textarea name="content" required></textarea></label><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>