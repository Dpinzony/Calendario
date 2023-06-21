@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<style>
  .container{
    display:flex;
    gap: 25px;
    justify-content: flex-start;
  }

  #calendar{
    height: 100%;
    width: 100%;
    gap: 10px;
    margin: 15px;
    border: 1px solid black;
    background-color:white ;

  }
  .info{
    display: grid;
    width: 20%;
    height: 100%;
    grid-template-rows: 5% 95%;
  }

  .Task{
    margin: 5px;
    border: 1px solid black;
    background-color: #d6d0ca;
    width: 100%;
    height: 92.5%;
  }
  .cuerpo{
    width:40%;
    display:grid;
    
  }

  .Accounts{
    margin: 10px;
    width: 100%;
    height: 5%;
  }

  .buttonAc{
    border-radius: 10px;
      padding: 10px 20px;
      background-color: #f1f1f1;
      border: none;
      cursor: pointer;
  }


</style>
<div class="container">
    
<head>
    <meta charset='utf-8' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        
        let formulario = document.querySelector("#formularioEventos");

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          //initialView: 'dayGridMonth',
          locale:"es",
          displayEventTime: false,
          headerToolbar: {   
            right: 'prev,next today',
            center: 'title',
            left: 'dayGridMonth'
          },

          //event:booking,
          
          eventSources:{
            url: baseURL+"/Calendar/mostrar",
            method:"POST",
            extraParams:{
                _token: formulario._token.value,
            },
              data: {
              authenticated: {{ Auth::check() ? 'true' : 'false' }},
            },
          },

          dateClick:function(info){
            formulario.reset();

            formulario.start.value=info.dateStr;
            formulario.end.value=info.dateStr;

           $('#evento').modal('show');

          },

          eventClick:function(info){
            var evento = info.event;
            console.log(evento);

            axios.post(baseURL+"/calendario/public/Calendar/editar/"+info.event.id).
            then(
                (respuesta)=>{
                    formulario.id.value= respuesta.data.id;
                    formulario.title.value=respuesta.data.title;
                    formulario.description.value=respuesta.data.description;
                    formulario.start.value=respuesta.data.start;
                    formulario.end.value=respuesta.data.end;

                    $('#evento').modal('show');
                }
                ).catch(
                    error=>{
                        if(error.response){
                            console.log(error.response.data); 
                        }
                    }
                )

          },
          

        });
        calendar.render();

            document.getElementById("btnGuardar").addEventListener('click',function(){
            enviarDatos("/Calendar/agregar");
            });
            document.getElementById("btnEliminar").addEventListener('click',function(){
            enviarDatos("/Calendar/borrar/"+formulario.id.value);
            });
            document.getElementById("btnModificar").addEventListener('click',function(){
            enviarDatos("/Calendar/actualizar/"+formulario.id.value);
            });

        function enviarDatos(url){

            const datos= new FormData(formulario);

            const nuevaURL = baseURL+url

            axios.post(nuevaURL, datos ).
            then(
                (respuesta)=>{
               calendar.refetchEvents();
                $('#evento').modal('hide');
            }).catch( error=>{ if(error.response){
                console.log(error.response.data); 
            }}
          )};

          var currentDate = new Date().toISOString().split('T')[0];

// Filtrar las tareas del día actual
var tasks = calendar.getEvents().filter(function(event) {
  var eventDate = event.start.toISOString().split('T')[0];
  return eventDate === currentDate;
});

// Mostrar las tareas en el div "taskID"
var taskList = document.getElementById('taskID');
taskList.innerHTML = '';
tasks.forEach(function(task) {
  var listItem = document.createElement('li');
  listItem.textContent = task.title;
  taskList.appendChild(listItem);
});

  });


    </script>

  </head>
  <body style="background-color: #d9d9d9;">

      <div class='cuerpo'>
      <div class="Info">   
          <div class='Accounts'>
          <button onclick="window.location.href = 'http://localhost/calendario/public/contabilidad'">Ir a Contabilidad</button>
          </div>
        <div class='Task'>
          <p><b>&#916 Task</b></p>
          <script>
          </script>
          <ul id="taskID"></ul>
        </div>
      </div>
      </div>

      <div id='calendar' class="cal"></div>


  </body>


</div>


<!-- Modal -->
<div class="modal fade" id="evento" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tarea/Evento</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                
                <form action="" id="formularioEventos">

                {!! csrf_field() !!}

                    <div class="form-group d-none">
                      <label for="id">Id</label>
                      <input type="text" class="form-control" name="id" id="id" aria-describedby="helpId" placeholder="">
                      <small id="helpId" class="form-text text-muted"></small>
                    </div>

                    <div class="form-group">
                      <label for="title">Tarea</label>
                      <input type="text" class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="Tarea">
                      <small id="helpId" class="form-text text-muted">Nombre de la tarea</small>
                    </div>

                    <div class="form-group">
                      <label for="description">Descrpción</label>
                      <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>

                    <div class="form-group d-none">
                      <label for="start">Inicio</label>
                      <input type="date" class="form-control" name="start" id="start" aria-describedby="helpId" placeholder="">
                      <small id="helpId" class="form-text text-muted">Fecha de inicio</small>
                    </div>

                    <div class="form-group d-none">
                      <label for="end">Final</label>
                      <input type="date" class="form-control" name="end" id="end" aria-describedby="helpId" placeholder="">
                      <small id="helpId" class="form-text text-muted">Fecha de finalización</small>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-success" id="btnGuardar">Guardar</button>
                <button type="button" class="btn btn-warning" id="btnModificar">Modificar</button>
                <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

            </div>
        </div>
    </div>
</div>

@endsection
