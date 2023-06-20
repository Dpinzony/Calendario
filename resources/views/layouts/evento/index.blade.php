@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<div class="container">
    
<head>
    <meta charset='utf-8' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {

        let formulario = document.querySelector("#formularioEventos");

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale:"es",
          displayEventTime: false,
          headerToolbar: {   
            right: 'prev,next today',
            center: 'title',
            left: 'dayGridMonth,timeGridWeek,listWeek'
          },
          
          eventSources:{
            url: baseURL+"/Calendar/mostrar",
            method:"POST",
            extraParams:{
                _token: formulario._token.value,
            }
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

            axios.post("http://localhost/calendario/public/Calendar/editar/"+info.event.id).
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

          }


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
            }
        }
    )

        }

        });


    </script>
  </head>
  <body>
    <div id='calendar'></div>
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
