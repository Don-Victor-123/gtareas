<?php
session_start();
require __DIR__ . '/../config/db.php';
if ($_SESSION['user']['role'] !== 'Recepcionista') exit('Acceso denegado');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Notas</title>
<link rel="stylesheet" href="dashboard.css">
<script>
document.addEventListener('DOMContentLoaded', function(){
    const dateInput = document.getElementById('selectedDate');
    const noteDate = document.getElementById('noteDate');
    const sidebar = document.getElementById('sidebar');
    document.getElementById('toggleSidebar').addEventListener('click',()=>{sidebar.classList.toggle('open');});
    function loadNotes(date){
        fetch(`get_notes.php?date=${date}&previous=1&completed=1&order=desc`)
            .then(res=>res.json())
            .then(data=>{
                const escapeHtml=s=>s
                    .replace(/&/g,'&amp;')
                    .replace(/</g,'&lt;')
                    .replace(/>/g,'&gt;')
                    .replace(/"/g,'&quot;')
                    .replace(/'/g,'&#39;');
                ['pendiente','en_proceso','completada'].forEach(status=>{
                    const ul=document.getElementById(status);
                    ul.innerHTML='';
                    data[status].forEach(note=>{
                        const li=document.createElement('li');
                        li.innerHTML=`<strong>[${escapeHtml(note.status)}]</strong> ${escapeHtml(note.title)} - ${escapeHtml(note.username)}<p>${escapeHtml(note.content)}</p><small>${note.created_at}</small>`;
                        ul.appendChild(li);
                    });
                });
                const pendientesSidebar=document.getElementById('pendientesSidebar');
                pendientesSidebar.innerHTML='';
                if(data.previos){
                    data.previos.forEach(n=>{
                        const li=document.createElement('li');
                        li.textContent=`${n.title} - ${n.username} (${n.status})`;
                        pendientesSidebar.appendChild(li);
                    });
                }
                const realizadasSidebar=document.getElementById('realizadasSidebar');
                realizadasSidebar.innerHTML='';
                if(data.realizadas){
                    data.realizadas.forEach(n=>{
                        const li=document.createElement('li');
                        li.textContent=`${n.created_at.split(' ')[0]} - ${n.title}`;
                        realizadasSidebar.appendChild(li);
                    });
                }
            });
    }
    dateInput.addEventListener('change',()=>{noteDate.value=dateInput.value;loadNotes(dateInput.value);});
    document.getElementById('noteForm').addEventListener('submit',function(e){
        e.preventDefault();
        const formData=new FormData(this);
        fetch('add_note.php',{method:'POST',body:formData})
            .then(res=>res.json())
            .then(resp=>{if(resp.success){loadNotes(dateInput.value);this.reset();noteDate.value=dateInput.value;}});
    });
    const today=new Date().toISOString().slice(0,10);
    dateInput.value=today;
    noteDate.value=today;
    loadNotes(today);
});
</script>
</head>
<body>
<button id="toggleSidebar">☰</button>
<div id="sidebar">
    <h3>Tareas Pendientes</h3>
    <ul id="pendientesSidebar"></ul>
    <h3>Realizadas</h3>
    <ul id="realizadasSidebar"></ul>
</div>
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
